<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\AIService;

class RubricController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.rubric.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'nama_tugas' => 'required',
            'kriteria' => 'required',
        ]);

        try {
            $prompt = "Buatkan Rubrik Penilaian (Assessment Rubric) yang detail dan profesional berdasarkan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Nama Tugas/Proyek: {$request->nama_tugas}
Kriteria Penilaian Utama: {$request->kriteria}
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING FORMAT OUTPUT:
1. Mulai dengan JUDUL RUBRIK (Heading 1).
2. Buat tabel identitas singkat (Nama Tugas, Kelas, Mapel) menggunakan HTML Table (style: border-bottom only) agar rapi secara vertikal.
<table style='width: 100%; border: none; margin-bottom: 20px;'>
    <tr>
        <td style='width: 20%; border: none; padding: 5px; font-weight: bold;'>Tugas/Proyek</td>
        <td style='width: 2%; border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px dotted #000; padding: 5px;'>{$request->nama_tugas}</td>
    </tr>
    <tr>
        <td style='border: none; padding: 5px; font-weight: bold;'>Kelas</td>
        <td style='border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px solid #ddd; padding: 5px;'>{$request->kelas}</td>
    </tr>
</table>

3. Sajikan TABEL RUBRIK UTAMA dengan kolom:
   - Kriteria (Aspek yang dinilai)
   - Skor 4 (Sangat Baik)
   - Skor 3 (Baik)
   - Skor 2 (Cukup)
   - Skor 1 (Perlu Bimbingan)

   Isi deskripsi indikator untuk setiap level skor secara spesifik dan terukur sesuai Kriteria yang diminta ({$request->kriteria}).

4. Tambahkan Rumus / Pedoman Penilaian Singkat di bawah tabel.
5. Gunakan format Markdown Table untuk tabel rubriknya.
Example Markdown Table:
| Kriteria | Skor 4 | Skor 3 | Skor 2 | Skor 1 |
| --- | --- | --- | --- | --- |
| ... | ... | ... | ... | ... |

6. JANGAN ada kalimat pembuka basa-basi.";

            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan konten. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('Rubric Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
