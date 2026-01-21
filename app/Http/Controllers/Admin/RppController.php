<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RppController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.rpp.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'topik' => 'required',
            'waktu' => 'required',
            'tujuan' => 'required',
            'model' => 'required',
        ]);

        try {
            $prompt = "Buatkan RPP / Modul Ajar yang lengkap, rapi, dan sistematis dengan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Topik Utama: {$request->topik}
Alokasi Waktu: {$request->waktu}
Tujuan Pembelajaran: {$request->tujuan}
Model Pembelajaran: {$request->model}
Instruksi Khusus: {$request->instruksi}

PENTING: Langsung mulai dengan judul RPP/Modul Ajar tanpa kalimat pembuka atau pengantar apapun. JANGAN tulis kalimat seperti 'Tentu, berikut adalah...' atau 'Ini adalah RPP yang...'. Langsung tulis konten RPP dimulai dari heading judul.

Gunakan format Markdown yang elegan dengan heading, bullet points, dan tabel jika diperlukan. Pastikan kontennya berkualitas dan sesuai dengan standar pendidikan di Indonesia.";

            $apiKey = config('services.gemini.key');

            if (empty($apiKey)) {
                return response()->json(['error' => 'Gemini API Key belum dikonfigurasi. Harap tambahkan GEMINI_API_KEY di file .env'], 500);
            }

            // Try multiple models in case one is overloaded
            $models = [
                'gemini-2.5-flash',
                'gemini-1.5-flash',
                'gemini-1.5-pro',
            ];

            $lastError = null;
            
            foreach ($models as $model) {
                // Retry up to 3 times per model
                for ($attempt = 1; $attempt <= 2; $attempt++) {
                    $response = Http::timeout(120)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ]
                    ]);

                    if ($response->successful()) {
                        $result = $response->json('candidates.0.content.parts.0.text');
                        return response()->json(['result' => $result]);
                    }

                    $lastError = $response->json('error.message', 'Unknown error');
                    
                    // If overloaded, wait and try again
                    if (str_contains($lastError, 'overloaded')) {
                        sleep(2);
                        continue;
                    }
                    
                    // If it's a different error, try next model
                    break;
                }
            }

            \Log::error('Gemini API Error', ['error' => $lastError]);
            return response()->json(['error' => 'Server AI sedang sibuk. Silakan coba lagi dalam beberapa saat.'], 503);

        } catch (\Exception $e) {
            \Log::error('RPP Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
