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
ASISTEN: Kamu adalah pakar kurikulum pendidikan Indonesia (Kurikulum Merdeka).
TUGAS: Buatkan Rencana Pembelajaran / Modul Ajar berdasarkan data berikut:

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

STRUKTUR OUTPUT (WAJIB FORMAT TABEL MARKDOWN):
1. **Identitas Modul**: (Nama Sekolah, Guru, Mapel, Fase/Kelas/Semester, Alokasi Waktu).
2. **Komponen DPL1–DPL8**: (elemen terkait).
3. **Desain Pembelajaran**: (Media, Sumber Belajar, Target Peserta Didik).
4. **Pengalaman Belajar**: Tabel berisi kolom Tahap (Pendahuluan, Inti, Penutup), Kegiatan, dan Alokasi Waktu.
5. **Asesmen**: Tabel berisi jenis Asesmen Awal, Proses, dan Akhir dengan prinsip pembelajaran mendalam.
6. **Pengesahan**: Di akhir dokumen, sertakan tempat tanda tangan Kepala Sekolah dan Guru Mata Pelajaran.

ATURAN:
- JANGAN berikan kata pembuka seperti "Baik", "Tentu", atau "Berikut adalah...".
- LANGSUNG ke judul: **MODUL AJAR KURIKULUM MERDEKA**.
- Gunakan bahasa Indonesia yang formal dan edukatif.
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
