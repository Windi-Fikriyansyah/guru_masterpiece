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
            'nama_sekolah' => 'required',
            'nama_guru' => 'required',
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'semester' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'topik' => 'required',
            'waktu' => 'required',
            'tujuan' => 'required',
            'model' => 'required',
            'pendekatan' => 'required',
            'instruksi' => 'nullable',
        ]);

        try {
            $prompt = <<<PROMPT
Buatkan Perencanaan Pembelajaran (Modul Ajar) dengan format resmi Kurikulum Merdeka Belajar untuk:
- Nama Sekolah: {$request->nama_sekolah}
- Nama Guru: {$request->nama_guru}
- Jenjang: {$request->jenjang}
- Fase/Kelas/Semester: {$request->kelas} / {$request->semester}
- Mata Pelajaran: {$request->mapel}
- Topik: {$request->topik}
- Alokasi Waktu: {$request->waktu}
- Model Pembelajaran: {$request->model}
- Pendekatan: {$request->pendekatan}
- Tujuan Pembelajaran: {$request->tujuan}
- Instruksi Khusus: {$request->instruksi}

Gunakan struktur berikut:
1. **Identifikasi**: (Kesiapan materi, karakteristik murid, dimensi profil lulusan: DPL1 Keimanan dan Ketakwaan terhadap Tuhan YME, DPL2 Kewargaan, DPL3 Penalaran Kritis, DPL4 Kreativitas, DPL5 Kolaborasi, DPL6 Kemandirian, DPL7 Kesehatan, DPL8 Komunikasi).
2. **Desain Pembelajaran**: (Tujuan pembelajaran, praktik pedagogis, kemitraan pembelajaran, lingkungan pembelajaran, pemanfaatan digital).
3. **Pengalaman Belajar**: Langkah-langkah Pembelajaran terdiri dari:
   - Kegiatan Pendahuluan (Sebutkan jumlah menit dan prinsip yang digunakan)
   - Kegiatan Inti (Sebutkan jumlah menit dan prinsip yang digunakan)
   - Kegiatan Penutup (Sebutkan jumlah menit dan prinsip yang digunakan)
4. **Asesmen Pembelajaran**: Buatkan asesmen pada awal (as learning), proses (for learning), dan akhir pembelajaran (of learning).

Ketentuan Tambahan:
- Terapkan pembelajaran mendalam dengan prinsip berkesadaran, bermakna, dan menggembirakan.
- Selaraskan dengan Dimensi Profil Lulusan serta sertakan penjelasan langkah-langkah pembelajarannya.
- Sajikan dalam bentuk TABEL yang rapi dan siap digunakan dalam format Markdown.
- JANGAN berikan kata pembuka atau penutup seperti "Berikut adalah..." atau "Semoga bermanfaat". Langsung berikan isi Modul Ajar.
- Akhiri dokumen dengan format tanda tangan:
  Mengetahui,
  [Nama Kota/Kabupaten], [Tanggal]
  
  Kepala Sekolah                           Guru Mata Pelajaran
  
  
  
  (..........................)             (..........................)
  NIP.                                     NIP.
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
