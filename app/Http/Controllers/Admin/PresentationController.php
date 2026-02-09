<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\AIService;

class PresentationController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.presentation.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'topik' => 'required',
            'jumlah_slide' => 'required|numeric|min:3|max:20',
        ]);

        $request->merge(['kelas_Fase_Semester' => $request->kelas]);

        try {
            $prompt = "Buatkan *Kerangka Presentasi (Slide Deck Outline)* yang menarik, edukatif, dan siap dikembangkan menjadi slide berdasarkan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas/Fase/Semester: {$request->kelas_Fase_Semester}
Mata Pelajaran: {$request->mapel}
Topik Presentasi: {$request->topik}
Target Jumlah Slide: {$request->jumlah_slide} slide
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING FORMAT OUTPUT (WAJIB DIPATUHI):
1. Mulai langsung dengan *JUDUL PRESENTASI* yang menarik menggunakan *Heading 1 (# Judul)*.
   ❌ Jangan menambahkan kalimat pembuka atau basa-basi dari AI.

2. Di bawah judul, buat *tabel identitas singkat* menggunakan HTML berikut (WAJIB sama persis):
<table style='width: 100%; border: none; margin-bottom: 20px;'>
    <tr>
        <td style='width: 15%; border: none; padding: 5px; font-weight: bold;'>Topik</td>
        <td style='width: 2%; border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px dotted #000; padding: 5px;'>{$request->topik}</td>
    </tr>
    <tr>
        <td style='border: none; padding: 5px; font-weight: bold;'>Kelas</td>
        <td style='border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px solid #ddd; padding: 5px;'>{$request->kelas}</td>
    </tr>
</table>

3. Sajikan *outline per slide* dengan format KONSISTEN berikut:

*Slide 1: [Judul Slide]*
- *Visual/Gambar:*
  - Tampilkan *gambar secara langsung* yang relevan dengan isi slide.
  - Gambar harus edukatif, kontekstual, dan membantu pemahaman materi (bukan sekadar hiasan).

- *Materi per Poin (WAJIB ADA PENJELASAN):*
  - Sajikan poin-poin materi dalam bentuk bullet points.
  - *Setiap poin WAJIB disertai penjelasan singkat (1–2 kalimat)* yang menjelaskan makna, konsep, atau contoh sederhana sesuai jenjang siswa.

- *Narasi Pembicara (opsional):*
  - Tambahkan catatan singkat untuk guru (jika perlu) sebagai panduan penjelasan lisan.

4. Ulangi format di atas secara konsisten untuk:
   - Slide 2
   - Slide 3
   - ...
   - Hingga Slide {$request->jumlah_slide}

5. *ALUR MATERI WAJIB LOGIS*, meliputi:
   - Pembukaan / Apersepsi
   - Penyampaian konsep inti (dengan gambar)
   - Penjelasan mendalam & contoh / studi kasus
   - Ringkasan / kesimpulan / penutup

6. *GAMBAR / VISUAL:*
   - Setiap slide *HARUS memiliki minimal satu gambar*.
   - Apapun topik dan bentuk materinya, *gambar tetap harus ada*.
   - Visual digunakan untuk memperjelas konsep, proses, atau contoh, bukan sekadar dekorasi.

7. *DALIL AL-QUR’AN & HADITS (JIKA MATERI AGAMA ISLAM DAN DIMINTA):*
   - WAJIB menyertakan *teks ayat Al-Qur’an dan/atau Hadits secara lengkap*.
   - ❌ Jangan hanya menyebutkan nama surat, nomor ayat, atau nomor hadits.
   - Sertakan *terjemahan* dan *penjelasan singkat* keterkaitannya dengan materi pada slide.
   - Dalil dapat digunakan sebagai:
     - Penguat konsep,
     - Landasan nilai,
     - Stimulus diskusi atau refleksi.

8. Gunakan *format Markdown* yang rapi, jelas, dan mudah dibaca.
   - Heading
   - Bold
   - Bullet points
   - Spasi antar bagian cukup

OUTPUT AKHIR:
Kerangka presentasi yang *lengkap per slide, memiliki **materi per poin dengan penjelasan, **kaya visual, alur logis, dan siap dikembangkan menjadi **slide presentasi profesional untuk pembelajaran*.";
            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan konten. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('Presentation Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
