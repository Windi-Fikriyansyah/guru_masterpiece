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

        try {
            $prompt = "Buatkan Kerangka Presentasi (Slide Deck Outline) yang menarik dan edukatif berdasarkan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Topik Presentasi: {$request->topik}
Target Jumlah Slide: {$request->jumlah_slide} slide
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING FORMAT OUTPUT:
1. Mulai dengan JUDUL PRESENTASI yang Menarik (Heading 1).
2. Buat tabel identitas singkat (Nama, Kelas, Topik) menggunakan HTML Table (style: border-bottom only) agar rapi secara vertikal.
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

3. Sajikan Outline Per Slide dengan format:
   Slide 1: [Judul Slide]
   - Poin visual/gambar: tambahkan gambar sesuai topik/materi
   - Poin-poin materi utama dan penjelasannya (bullet points) dan sedikit deskripsi
   - Narasi pembicara (opsional/singkat)

   Slide 2: [Judul Slide]
   ... dan seterusnya sampai Slide {$request->jumlah_slide}.

4. Pastikan alur materi logis: Pembukaan -> Isi Materi, penjelasan dan gambar-> Contoh/Studi Kasus -> Kesimpulan/Penutup.
5. Gunakan format Markdown yang rapi. JANGAN ada kalimat pembuka basa-basi.";

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
