<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\AIService;

class SoalController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

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

        $request->merge(['kelas_fase_semester' => $request->kelas]);

        try {
            $prompt = "Buatkan SOAL pembelajaran dengan format yang rapi, sistematis, dan siap cetak berdasarkan kriteria berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas/Fase/Semester: {$request->kelas_fase_semester}
Mata Pelajaran: {$request->mapel}
Topik/Bab: {$request->topik}
Bentuk Soal: {$request->bentuk_soal}
Jumlah Soal: {$request->jumlah_soal} butir
Taksonomi Bloom: {$request->taksonomi}
Taksonomi SOLO: {$request->taksonomi}
Tingkat Kesulitan: {$request->kesulitan}
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

Materi Tambahan (Referensi):
" . ($request->materi ?? 'Tidak ada materi spesifik, gunakan pengetahuan umum sesuai kurikulum.') . "

PENTING FORMAT OUTPUT (WAJIB DIPATUHI):
1. Mulai langsung dengan *JUDUL TOPIK* menggunakan Heading 1 (# Judul).
   ❌ Jangan menambahkan kalimat pembuka atau basa-basi apa pun.

2. Buat *tabel identitas siswa* menggunakan HTML berikut (WAJIB sama persis):
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

3. Sajikan soal dengan *penomoran jelas, jarak antar soal rapi, dan **gambar yang relevan pada setiap soal*.
   ⚠️ Apapun bentuk dan topik soal, *GAMBAR WAJIB ADA*.

4. KHUSUS SOAL PILIHAN GANDA:
   - Setiap soal *WAJIB menampilkan gambar* yang relevan secara langsung.
   - Opsi jawaban HARUS menggunakan format LIST MARKDOWN:
   
   Contoh format:
   1. [Teks Pertanyaan]
      [Tampilkan gambar yang relevan dengan soal]
      - A. Pilihan jawaban pertama
      - B. Pilihan jawaban kedua
      - C. Pilihan jawaban ketiga
      - D. Pilihan jawaban keempat
      - E. Pilihan jawaban kelima (jika diperlukan)

5. *KUNCI JAWABAN*:
   - Diletakkan TERPISAH di bagian akhir.
   - Gunakan Heading *## KUNCI JAWABAN*.
   - Sertakan pembahasan singkat jika sesuai tingkat kesulitan.

6. *GAMBAR / VISUAL (WAJIB):*
   - Setiap soal minimal memiliki satu gambar.
   - Gambar harus relevan dengan materi dan membantu pemahaman atau analisis soal.
   - Bukan sekadar dekorasi.

7. *DALIL AL-QUR’AN & HADITS (JIKA MATERI AGAMA ISLAM DAN DIMINTA):*
   - WAJIB menyertakan *teks ayat Al-Qur’an dan/atau Hadits secara lengkap*.
   - ❌ Jangan hanya menuliskan nama surat, nomor ayat, atau nomor hadits.
   - Sertakan *terjemahan* dan keterkaitan dalil dengan soal.
   - Dalil dapat dijadikan stimulus soal, bahan analisis, atau konteks soal HOTS.

8. *LEVEL KOGNITIF WAJIB DISESUAIKAN DENGAN:*
   a. *Taksonomi Bloom ({$request->taksonomi})*
      - Mengingat, Memahami, Menerapkan
      - Menganalisis, Mengevaluasi, Mencipta
      - HOTS jika diminta

   b. *Taksonomi SOLO ({$request->taksonomi})*
      Gunakan *kata kerja operasional* berikut sesuai level:

      - *Uni-struktural*:
        Menghafal, mengidentifikasi, mengenali, menyebutkan, mencocokkan, mendefinisikan, mengingat, menulis, meniru.

      - *Multi-struktural*:
        Menjelaskan, mengklasifikasikan, mendaftar, mendiskusikan, mengurutkan, menghitung, melaporkan.

      - *Relasional*:
        Menganalisis, membandingkan, mengontraskan, menyimpulkan, menerapkan, memecahkan masalah, memprediksi, merangkum, menilai.

      - *Abstrak Meluas*:
        Menggeneralisasi, berhipotesis, menciptakan, merancang, merefleksikan, membuktikan dengan prinsip dasar, membuat solusi orisinal.

9. Pastikan:
   - Bentuk soal, stimulus, dan tuntutan jawaban *selaras dengan level Bloom & SOLO*.
   - Tingkat kesulitan ({$request->kesulitan}) tercermin dari kompleksitas berpikir, bukan sekadar panjang soal.

10. Gunakan *format Markdown* yang rapi, konsisten, dan siap cetak.

OUTPUT AKHIR:
Soal yang *lengkap, berkualitas tinggi, kaya visual, terkontrol secara kognitif (Bloom & SOLO), siap cetak*, dan dapat langsung digunakan guru.";

            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan konten. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('Soal Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
