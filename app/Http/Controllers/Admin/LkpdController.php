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
            $prompt = "Buatkan Lembar Kerja Peserta Didik (LKPD) yang lengkap, menarik, dan siap cetak berdasarkan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas/Fase/Semester: {$request->kelas_fase_semester}
Mata Pelajaran: {$request->mapel}
Materi Spesifik: {$request->materi}
Instruksi/Tugas untuk Siswa: {$request->instruksi_siswa}
Instruksi/Keinginan Khusus (Guru): {$request->instruksi_khusus}

PENTING (WAJIB DIPATUHI):
1. Langsung mulai dengan *JUDUL LKPD* yang menarik menggunakan *Heading 1 (# Judul)*.
   ❌ Jangan menambahkan kalimat pembuka seperti 'Berikut adalah LKPD...'.

2. Di bawah judul, buat *tabel identitas siswa* menggunakan *HTML Table* berikut (WAJIB sama persis):
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

3. Sertakan *Tujuan Pembelajaran* yang singkat, jelas, dan sesuai materi.

4. Buat *Petunjuk Belajar/Pengerjaan* yang runtut dan mudah dipahami siswa.

5. Susun *kegiatan dan soal yang lengkap dan variatif*, dapat berupa:
   - Pilihan Ganda
   - Isian Singkat
   - Uraian
   - Menjodohkan
   - Studi Kasus / HOTS / PBL (jika diminta)
   
   Setiap jenis soal *WAJIB disertai kunci jawaban*.

6. *GAMBAR / VISUAL (WAJIB ADA):*
   - Sertakan *gambar yang relevan dengan materi LKPD*, apapun jenis materinya.
   - Gambar ditampilkan langsung di dalam LKPD, bukan hanya disebutkan.
   - Tambahkan gambar pada:
     - Setiap kegiatan atau soal (jika membantu pemahaman),
     - Setiap jenis soal,
     - Bagian kunci jawaban (jika visual membantu memperjelas).
   - Gambar harus *berfungsi edukatif*, bukan sekadar hiasan.

7. Jika materi LKPD adalah *Pendidikan Agama (Islam)* dan diminta dalil:
   - WAJIB menyertakan *teks ayat Al-Qur’an dan/atau Hadits secara lengkap*.
   - ❌ Jangan hanya menuliskan nama surat, nomor ayat, atau nomor hadits.
   - Sertakan *terjemahan* dan *penjelasan singkat* keterkaitannya dengan materi LKPD.

8. Tambahkan *lembar evaluasi/refleksi siswa atau kelompok*, disertai:
   - Pertanyaan reflektif sesuai materi,
   - Pedoman atau rubrik penskoran sederhana.

9. Gunakan *kombinasi HTML (tabel identitas)* dan *Markdown (heading, bold, list, tabel)* secara rapi dan konsisten.

10. Jika diminta *HOTS atau PBL*, pastikan:
    - Soal bersifat analitis, kontekstual, dan menuntut berpikir tingkat tinggi,
    - Siswa diajak mengamati, menalar, dan menyimpulkan.

OUTPUT AKHIR:
LKPD yang *lengkap, sistematis, siap cetak*, kaya visual gambar, mudah digunakan guru, dan mudah dipahami siswa.";
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
