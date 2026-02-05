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
2. Buat tabel identitas siswa (Nama, Kelas, Hari/Tanggal/Tahun) menggunakan HTML Table (style: border-bottom only) agar rapi secara vertikal.
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

3. Sajikan soal dengan penomoran yang jelas and gambar sesuai materi pada soal.
4. KHUSUS PILIHAN GANDA:
   - tambahkan gambar yang jelas sesuai materi soalnya
   - Opsi jawaban (A, B, C, D, E) HARUS menggunakan format LIST MARKDOWN (tanda strip -) seperti ini:
     1. Pertanyaan...
        - A. Pilihan 1
        - B. Pilihan 2
        - C. Pilihan 3
        - D. Pilihan 4
   5. Kunci jawaban diletakkan TERPISAH (halaman baru atau paling bawah) with judul \"KUNCI JAWABAN\".
6. Sesuaikan kompleksitas soal dengan Taksonomi Bloom ({$request->taksonomi}) and Tingkat Kesulitan ({$request->kesulitan}).
7. Gunakan format Markdown yang rapi, berikan jarak antar nomor soal. JANGAN ada kalimat pembuka basa-basi.";

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
