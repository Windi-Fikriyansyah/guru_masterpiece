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
            'pendekatan' => 'required',
        ]);

        try {
            $prompt = <<<PROMPT
ASISTEN: Kamu adalah pakar kurikulum pendidikan Indonesia.
TUGAS: Buatkan Rencana Pembelajaran berdasarkan Kurikulum yang dipilih.

DATA INPUT:
- Sekolah: {$request->nama_sekolah}
- Guru: {$request->nama_guru}
- Kurikulum: {$request->kurikulum}
- Jenjang: {$request->jenjang}
- Fase/Kelas: {$request->kelas}
- Semester: {$request->semester}
- Mapel: {$request->mapel}
- Topik: {$request->topik}
- Alokasi Waktu: {$request->waktu}
- Model: {$request->model}
- Pendekatan: {$request->pendekatan}
- Tujuan: {$request->tujuan}
- Instruksi Tambahan: {$request->instruksi}

ATURAN KERAS (WAJIB DIPATUHI):
1. JANGAN memberikan kata pembuka/preambule (Contoh: "Tentu...", "Berikut adalah...", "Baik saya akan...").
2. JANGAN memberikan komentar atau koreksi terhadap data input. Ikuti saja data yang diberikan.
3. LANGSUNG mulai output dengan judul: **MODUL AJAR [NAMA MAPEL]** (atau RPP jika Kurikulum 2013).
4. SEMUA komponen (Identifikasi/Identitas, Desain Pembelajaran, Pengalaman Belajar, Asesmen) WAJIB disajikan dalam format TABEL Markdown yang bersih dan siap pakai.
5. Gunakan format tabel standar: | Kolom 1 | Kolom 2 | dengan pemisah | --- | --- |.
6. Tabel Identifikasi/Identitas WAJIB memiliki susunan baris: Nama Sekolah, Nama Guru, Mata Pelajaran, Fase/Kelas/Semester (WAJIB DIGABUNG), Alokasi Waktu. JANGAN memisahkan Semester di baris tersendiri.

STRUKTUR OUTPUT:
1. Tabel Identifikasi (Gunakan baris "Fase/Kelas" yang digabung)
2. Tabel Identifikasi DPL1-DPL8 (jika Kurikulum Merdeka)
3. Tabel Desain Pembelajaran
4. Tabel Pengalaman Belajar (Pendahuluan, Inti, Penutup + Waktu)
5. Tabel Asesmen (Awal, Proses, Akhir) dengan prinsip pembelajaran mendalam.

Sajikan dalam tabel siap pakai.
PROMPT;


            $apiKey = $request->input('api_key')
                ?: config('services.gemini.key');


            if (empty($apiKey)) {
                return response()->json(['error' => 'Gemini API Key belum dikonfigurasi. Harap tambahkan GEMINI_API_KEY di file .env'], 500);
            }

            // Try multiple models in case one is overloaded
            $models = [

                'gemini-2.5-flash',
                'gemini-1.5-pro',
            ];

            $lastError = null;

            foreach ($models as $model) {
                // Retry logic per model
                for ($attempt = 1; $attempt <= 2; $attempt++) {
                    try {
                        $response = Http::timeout(180)->withHeaders([
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

                        $lastError = $response->body(); // Get full body for better debugging
                        $errorMessage = $response->json('error.message', 'Unknown error');

                        \Log::warning("Gemini Model {$model} failed (Attempt {$attempt})", [
                            'status' => $response->status(),
                            'error' => $errorMessage
                        ]);

                        // If overloaded, wait and try again
                        if (str_contains($errorMessage, 'overloaded') || $response->status() == 429) {
                            sleep(3);
                            continue;
                        }
                    } catch (\Exception $e) {
                        $lastError = $e->getMessage();
                        \Log::error("Gemini Exception for {$model}", ['message' => $lastError]);
                    }

                    // For other errors or timeouts, try the next model
                    break;
                }
            }

            \Log::error('Gemini API All Models Failed', ['last_error' => $lastError]);
            return response()->json(['error' => 'Server AI sedang sibuk atau limit tercapai. Silakan coba lagi dalam beberapa saat.'], 503);
        } catch (\Exception $e) {
            \Log::error('RPP Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
