<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Subject;

class TextSummaryController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.text_summary.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'topik' => 'required',
            'tipe_konten' => 'required',
            'instruksi_khusus' => 'nullable',
        ]);

        try {
            $prompt = "Buatkan Materi Pembelajaran dengan format yang rapi dan informatif berdasarkan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Topik Materi: {$request->topik}
Tipe Konten: {$request->tipe_konten} (Misal: Materi Lengkap, Ringkasan Padat, Poin-Poin Penting, atau Glosarium Istilah)
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING:
1. Mulai langsung dengan Judul Materi (Heading 1).
2. Sesuaikan kedalaman materi dengan Jenjang dan Kelas siswa.
3. Untuk 'Materi Lengkap', jelaskan konsep secara komprehensif dengan contoh.
4. Untuk 'Ringkasan', ambil intisari materi saja.
5. Gunakan format Markdown (Heading, Bold, List, Tabel) agar mudah dibaca.
6. JANGAN ada kalimat pembuka basa-basi dari AI. Langsung ke konten materi.";

            $apiKey = config('services.gemini.key');

            if (empty($apiKey)) {
                return response()->json(['error' => 'Gemini API Key belum dikonfigurasi. Harap tambahkan GEMINI_API_KEY di file .env'], 500);
            }

            // Retry logic
            $models = ['gemini-2.5-flash', 'gemini-1.5-flash', 'gemini-1.5-pro'];
            $lastError = null;

            foreach ($models as $model) {
                for ($attempt = 1; $attempt <= 2; $attempt++) {
                    $response = Http::timeout(120)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                        'contents' => [['parts' => [['text' => $prompt]]]]
                    ]);

                    if ($response->successful()) {
                        $result = $response->json('candidates.0.content.parts.0.text');
                        return response()->json(['result' => $result]);
                    }

                    $lastError = $response->json('error.message', 'Unknown error');
                    if (str_contains($lastError, 'overloaded')) {
                        sleep(2);
                        continue;
                    }
                    break;
                }
            }

            \Log::error('Gemini API Error TextSummary', ['error' => $lastError]);
            return response()->json(['error' => 'Server AI sedang sibuk. Silakan coba lagi.'], 503);

        } catch (\Exception $e) {
            \Log::error('TextSummary Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
