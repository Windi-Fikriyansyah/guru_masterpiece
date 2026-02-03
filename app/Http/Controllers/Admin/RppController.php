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
Buatkan Perencanaan Pembelajaran (Modul Ajar Pebelajaran Mendalam) dengan format resmi Kurikulum Merdeka Belajar untuk:
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

Gunakan struktur Modul Ajar Pembelajaran Mendalam - Format Resmi Kurikulum Merdeka (Susun Perencanaan Pembelajaran Mendalam sesuai Kurikulum Merdeka dengan bahasa formal, sistematis, dan siap digunakan sebagai dokumen resmi sekolah. Gunakan format dan urutan berikut secara konsisten)

PERENCANAAN PEMBELAJARAN MENDALAM

A. INFORMASI UMUM
- Nama sekolah [isi]
- Nama guru [isi]
- Jenjang [isi]
- Fase/kelas/semester [isi]
- Mata pelajaran [isi]
- Topik [isi]
- Alokasi waktu [isi]
- Model pembelajaran [isi]
- Pendekatan [isi]
- Tujuan pembelajaran [isi] (Tuliskan tujuan pembelajaran yang mencakup kompetensi dan konten sesuai capaian pembelajaran, menggunakan kata kerja operasional yang terukur)
B. IDENTIFIKASI:
1. Kesiapan materi (buatkan kesiapan materinya)
2. karakteristik murid (buatkan kesiapan muridnya)
3. Menentukan dimensi profil lulusan: DPL1 Keimanan dan Ketakwaan terhadap Tuhan YME, DPL2 Kewargaan, DPL3 Penalaran Kritis, DPL4 Kreativitas, DPL5 Kolaborasi, DPL6 Kemandirian, DPL7 Kesehatan, DPL8 Komunikasi), Sajikan Dimensi Profil Lulusan (DPL1–DPL8) dalam tabel ceklis (☐) yang lengkap, sehingga pendidik tinggal mencentang dimensi yang dipilih sesuai pembelajaran.
C. DESAIN PEMBELAJARAN:
1. Tujuan pembelajaran
2. kerangka pembelajaran: praktik pedagogis, kemitraan pembelajaran, lingkungan pembelajaran, pemanfaatan digital).
D. PENGALAMAN BELAJAR: 
Langkah-langkah Pembelajaran dengan prinsip berkesadaran, bermakna, menggembirakan dan Mendeskripsikan pengalaman belajar memahami, mengaplikasi, dan merefleksi. Terdiri dari:
1. Kegiatan Pendahuluan (Sebutkan jumlah menit dan prinsip yang digunakan)
2. Kegiatan Inti (Sebutkan jumlah menit dan prinsip yang digunakan)
3. Kegiatan Penutup (Sebutkan jumlah menit dan prinsip yang digunakan)
E. ASESMEN PEMBELAJARAN: Buatkan asesmen:
1. Asesmen pada awal pembelajaran (as learning)
2. Asesmen pada proses pembelajaran (for learning)
3. Asesmen pada akhir pembelajaran (of learning)

Instruksi/ketentuan Tambahan:
- Terapkan pembelajaran mendalam dengan prinsip berkesadaran, bermakna, dan menggembirakan.
- Selaraskan dengan Dimensi Profil Lulusan serta sertakan penjelasan langkah-langkah pembelajarannya.
- Sajikan semua dalam bentuk TABEL yang rapi dan siap digunakan dalam format Markdown.
- JANGAN berikan kata pembuka atau penutup seperti "Berikut adalah..." atau "Semoga bermanfaat". Langsung berikan isi dan penjelasan Modul Ajar.
- Akhiri dokumen dengan format tanda tangan:

Mengetahui,
[Nama Kota/Kabupaten], [Tanggal, Tahun]
  
 Kepala Sekolah                  


(..........................)     
NIP. 

Guru Mata Pelajaran
  
  
  
(..........................) 
NIP. 
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
