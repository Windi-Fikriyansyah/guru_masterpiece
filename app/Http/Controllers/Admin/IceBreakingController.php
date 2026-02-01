<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\AIService;

class IceBreakingController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.ice_breaking.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'tipe_game' => 'required',
            'jumlah_siswa' => 'required|numeric',
            'tujuan' => 'required',
        ]);

        try {
            $prompt = "Buatkan ide Ice Breaking yang kreatif, seru, dan edukatif berdasarkan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Tipe Permainan: {$request->tipe_game}
Estimasi Jumlah Siswa: {$request->jumlah_siswa} orang
Tujuan Ice Breaking: {$request->tujuan}
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING FORMAT OUTPUT:
1. Mulai dengan JUDUL KOLEKSI ICE BREAKING (Heading 1).
2. Buat tabel identitas singkat menggunakan HTML Table (style: border-bottom only).
<table style='width: 100%; border: none; margin-bottom: 20px;'>
    <tr>
        <td style='width: 20%; border: none; padding: 5px; font-weight: bold;'>Tujuan</td>
        <td style='width: 2%; border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px dotted #000; padding: 5px;'>{$request->tujuan}</td>
    </tr>
    <tr>
        <td style='border: none; padding: 5px; font-weight: bold;'>Tipe Permainan</td>
        <td style='border: none; padding: 5px;'>:</td>
        <td style='border: none; border-bottom: 1px solid #ddd; padding: 5px;'>{$request->tipe_game} ({$request->jumlah_siswa} siswa)</td>
    </tr>
</table>

3. Berikan 3 Opsi/Ide Ice Breaking yang berbeda. Untuk setiap opsi, gunakan format:
   ### Opsi 1: [Nama Permainan yang Menarik]
   - **Durasi:** [... menit]
   - **Alat/Bahan:** [Sebutkan jika ada, atau 'Tanpa Alat']
   - **Persiapan:** [Langkah singkat]
   - **Cara Bermain:**
     1. Langkah 1...
     2. Langkah 2...
   - **Manfaat/Pesan Moral:** [Penjelasan singkat]

   (Lakukan hal yang sama untuk Opsi 2 dan Opsi 3)

4. Gunakan bahasa yang santai namun sopan (fun tone).
5. Gunakan format Markdown yang rapi. JANGAN ada kalimat pembuka basa-basi.";

            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan konten. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('Ice Breaking Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
