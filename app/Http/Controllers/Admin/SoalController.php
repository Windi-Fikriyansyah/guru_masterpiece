<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Subject;

class SoalController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.soal.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'bentuk_soal' => 'required',
            'jumlah_soal' => 'required|numeric|min:1|max:50',
            'taksonomi' => 'required',
            'kesulitan' => 'required',
            'topik' => 'required',
        ]);

        try {
            $prompt = "Buatkan Soal Ujian dengan format yang rapi dan siap cetak berdasarkan kriteria berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Topik/Bab: {$request->topik}
Bentuk Soal: {$request->bentuk_soal}
Jumlah Soal: {$request->jumlah_soal} butir
Taksonomi Bloom: {$request->taksonomi}
Tingkat Kesulitan: {$request->kesulitan}
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

Materi Tambahan (Referensi):
" . ($request->materi ?? 'Tidak ada materi spesifik, gunakan pengetahuan umum sesuai kurikulum.') . "

PENTING FORMAT OUTPUT:
1. Mulai dengan JUDUL (Heading 1).
2. Buat tabel identitas siswa (Nama, Kelas, Hari/Tanggal) menggunakan HTML Table (style: border-bottom only) agar rapi secara vertikal.
<table style='width: 100%; border: none; margin-bottom: 20px;'>
    <tr>
        <td style='width: 15%; border: none; padding: 5px; font-weight: bold;'>Nama</td>
        <td style='width: 2%; border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px dotted #000; padding: 5px;'></td>
    </tr>
    <tr>
        <td style='border: none; padding: 5px; font-weight: bold;'>Kelas</td>
        <td style='border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px solid #ddd; padding: 5px;'>{$request->kelas}</td>
    </tr>
</table>

3. Sajikan soal dengan penomoran yang jelas.
4. KHUSUS PILIHAN GANDA:
   - Opsi jawaban (A, B, C, D, E) HARUS menggunakan format LIST MARKDOWN (tanda strip -) seperti ini:
     1. Pertanyaan...
        - A. Pilihan 1
        - B. Pilihan 2
        - C. Pilihan 3
        - D. Pilihan 4
5. Kunci jawaban diletakkan TERPISAH (halaman baru atau paling bawah) dengan judul \"KUNCI JAWABAN\".
6. Sesuaikan kompleksitas soal dengan Taksonomi Bloom ({$request->taksonomi}) dan Tingkat Kesulitan ({$request->kesulitan}).
7. Gunakan format Markdown yang rapi, berikan jarak antar nomor soal. JANGAN ada kalimat pembuka basa-basi.";

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

            \Log::error('Gemini API Error Soal', ['error' => $lastError]);
            return response()->json(['error' => 'Server AI sedang sibuk. Silakan coba lagi.'], 503);

        } catch (\Exception $e) {
            \Log::error('Soal Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
