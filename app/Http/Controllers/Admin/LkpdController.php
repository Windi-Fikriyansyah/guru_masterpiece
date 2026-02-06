<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\AIService;

class LkpdController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

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
            'instruksi_siswa' => 'nullable',
            'instruksi_khusus' => 'nullable',
        ]);

        $request->merge(['kelas_fase_semester' => $request->kelas]);

        try {
            $prompt = "Buatkan Lembar Kerja Peserta Didik (LKPD) yang lengkap, menarik, dan siap cetak dengan data berikut:
Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas/Fase/Semester: {$request->kelas_fase_semester}
Mata Pelajaran: {$request->mapel}
Materi Spesifik: {$request->materi}
Instruksi/Tugas untuk Siswa: {$request->instruksi_siswa}
Instruksi/Keinginan Khusus (Guru): {$request->instruksi_khusus}

PENTING:
1. Langsung mulai dengan JUDUL LKPD yang menarik dengan format Heading 1 (# Judul).
2. Di bawah judul, buat tabel identitas menggunakan HTML Table agar rapi dengan border transparan. Gunakan kode berikut persis untuk bagian identitas:
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
        <td style=\"border: none; padding: 5px; font-weight: bold;\">Hari/Tanggal/Tahun</td>
        <td style=\"border: none; padding: 5px;\">:</td>
        <td style=\"border: none; border-bottom: 1px dotted #000; padding: 5px;\"></td>
    </tr>
</table>
3. Berikan Tujuan Pembelajaran singkat.
4. Buat Petunjuk Belajar yang jelas.
5. Susun Kegiatan/Soal dan kunci jawaban yang variatif serta tambahkan gambar sesuai materi soal (bisa Pilihan Ganda, Isian, Uraian, atau Menjodohkan dan kunci jawaban dari setiap jenis soal) sesuai materi.
6. Tambahkan gambar yang relevan dengan materi pada:
-  Setiap kegiatan atau soal (jika diperlukan untuk membantu pemahaman),
-  Setiap jenis soal,
-  Bagian kunci jawaban (jika visual membantu memperjelas jawaban).
7. Susun lembar evaluasi/refleksi murid/kelompok berupa soal sesuai materi dan pedoman penskoran
8. Gunakan format Markdown yang rapi dengan heading, bold, dan tabel jika perlu.
9. Jika diminta HOTS atau PBL, pastikan konten mencerminkan hal tersebut.

JANGAN berikan kalimat pembuka seperti 'Berikut adalah LKPD...'. Langsung ke konten. Gunakan kombinasi HTML (untuk tabel identitas) dan Markdown (untuk sisanya).";
// tes
            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan konten. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('LKPD Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
