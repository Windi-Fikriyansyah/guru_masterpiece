<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Subject;

class LkpdController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.lkpd.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'materi' => 'required', 
            // 'instruksi' is optional? Let's make it optional or required based on user request "Instruksi/Tugas Siswa" implies it's a field.
            'instruksi_siswa' => 'nullable',
            'instruksi_khusus' => 'nullable',
        ]);

        try {
            $prompt = "Buatkan Lembar Kerja Peserta Didik (LKPD) yang lengkap, menarik, dan siap cetak dengan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas/Kelompok: {$request->kelas}
Mata Pelajaran/Lingkup Perkembangan: {$request->mapel}
Materi Spesifik: {$request->materi}
Instruksi/Tugas untuk Siswa: {$request->instruksi_siswa}
Instruksi/Keinginan Khusus (Guru): {$request->instruksi_khusus}

PENTING:
1. Langsung mulai dengan JUDUL LKPD yang menarik dengan format Heading 1 (# Judul).
2. Di bawah judul, buat tabel identitas menggunakan **HTML Table** agar rapi dengan border transparan. Gunakan kode berikut persis untuk bagian identitas:
<table style=\"width: 100%; border: none; margin-bottom: 20px;\">
    <tr>
        <td style=\"width: 15%; border: none; padding: 5px; font-weight: bold;\">Nama</td>
        <td style=\"width: 2%; border: none; padding: 5px;\">:</td>
        <td style=\"border: none; border-bottom: 1px dotted #000; padding: 5px;\"></td>
    </tr>
    <tr>
        <td style=\"border: none; padding: 5px; font-weight: bold;\">Kelas</td>
        <td style=\"border: none; padding: 5px;\">:</td>
        <td style=\"border: none; border-bottom: 1px solid #ddd; padding: 5px;\">{$request->kelas}</td>
    </tr>
    <tr>
        <td style=\"border: none; padding: 5px; font-weight: bold;\">Hari/Tanggal</td>
        <td style=\"border: none; padding: 5px;\">:</td>
        <td style=\"border: none; border-bottom: 1px dotted #000; padding: 5px;\"></td>
    </tr>
</table>
3. Berikan Tujuan Pembelajaran singkat.
4. Buat Petunjuk Belajar yang jelas.
5. Susun Kegiatan/Soal yang variatif (bisa Pilihan Ganda, Isian, Uraian, atau Menjodohkan) sesuai materi.
6. Gunakan format Markdown yang rapi dengan heading, bold, dan tabel jika perlu.
7. Jika diminta HOTS atau PBL, pastikan konten mencerminkan hal tersebut.

JANGAN berikan kalimat pembuka seperti 'Berikut adalah LKPD...'. Langsung ke konten. Gunakan kombinasi HTML (untuk tabel identitas) dan Markdown (untuk sisanya).";

            $apiKey = config('services.gemini.key');

            if (empty($apiKey)) {
                return response()->json(['error' => 'Gemini API Key belum dikonfigurasi. Harap tambahkan GEMINI_API_KEY di file .env'], 500);
            }

            // Retry logic similar to RppController
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

            \Log::error('Gemini API Error LKPD', ['error' => $lastError]);
            return response()->json(['error' => 'Server AI sedang sibuk. Silakan coba lagi.'], 503);

        } catch (\Exception $e) {
            \Log::error('LKPD Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
