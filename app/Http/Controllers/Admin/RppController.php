<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Services\AIService;

class RppController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.rpp.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required',
            'nama_guru' => 'required',
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'semester' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'topik' => 'required',
            'waktu' => 'required',
            'tujuan' => 'required',
            'model' => 'required',
            'pendekatan' => 'required',
            'instruksi' => 'nullable',
        ]);

        try {
            $prompt = <<<PROMPT
Buatkan Perencanaan Pembelajaran (Modul Ajar) dengan format resmi Kurikulum Merdeka Belajar untuk:
- Nama Sekolah: {$request->nama_sekolah}
- Nama Guru: {$request->nama_guru}
- Jenjang: {$request->jenjang}
- Fase/Kelas/Semester: {$request->kelas} / {$request->semester}
- Mata Pelajaran: {$request->mapel}
- Topik: {$request->topik}
- Alokasi Waktu: {$request->waktu}
- Model Pembelajaran: {$request->model}
- Pendekatan: {$request->pendekatan}
- Tujuan Pembelajaran: {$request->tujuan}
- Instruksi Khusus: {$request->instruksi}

Gunakan struktur berikut:
1. **Identifikasi**: (Kesiapan materi, karakteristik murid, dimensi profil lulusan: DPL1 Keimanan dan Ketakwaan terhadap Tuhan YME, DPL2 Kewargaan, DPL3 Penalaran Kritis, DPL4 Kreativitas, DPL5 Kolaborasi, DPL6 Kemandirian, DPL7 Kesehatan, DPL8 Komunikasi).
2. **Desain Pembelajaran**: (Tujuan pembelajaran, praktik pedagogis, kemitraan pembelajaran, lingkungan pembelajaran, pemanfaatan digital).
3. **Pengalaman Belajar**: Langkah-langkah Pembelajaran terdiri dari:
   - Kegiatan Pendahuluan (Sebutkan jumlah menit dan prinsip yang digunakan)
   - Kegiatan Inti (Sebutkan jumlah menit dan prinsip yang digunakan)
   - Kegiatan Penutup (Sebutkan jumlah menit dan prinsip yang digunakan)
4. **Asesmen Pembelajaran**: Buatkan asesmen pada awal (as learning), proses (for learning), dan akhir pembelajaran (of learning).

Ketentuan Tambahan:
- Terapkan pembelajaran mendalam dengan prinsip berkesadaran, bermakna, dan menggembirakan.
- Selaraskan dengan Dimensi Profil Lulusan serta sertakan penjelasan langkah-langkah pembelajarannya.
- Sajikan dalam bentuk TABEL yang rapi dan siap digunakan dalam format Markdown.
- JANGAN berikan kata pembuka atau penutup seperti "Berikut adalah..." atau "Semoga bermanfaat". Langsung berikan isi Modul Ajar.
- Akhiri dokumen dengan format tanda tangan:
  Mengetahui,
  [Nama Kota/Kabupaten], [Tanggal]
  
  Kepala Sekolah                           Guru Mata Pelajaran
  
  
  
  (..........................)             (..........................)
  NIP.                                     NIP.
PROMPT;

            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan konten. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('RPP Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
