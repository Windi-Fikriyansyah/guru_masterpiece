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
            'bulan_tahun' => 'required',
            'tujuan' => 'required',
            'model' => 'required',
            'pendekatan' => 'required',
            'instruksi' => 'nullable',
        ]);

        try {
            $prompt = $prompt = $prompt = <<<PROMPT
Buatkan *Perencanaan Pembelajaran (Modul Ajar Pembelajaran Mendalam)* dengan format resmi *Kurikulum Merdeka Belajar, bahasa formal, sistematis, dan siap digunakan sebagai **dokumen resmi sekolah*, berdasarkan data berikut:

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

Gunakan struktur *Modul Ajar Pembelajaran Mendalam – Format Resmi Kurikulum Merdeka* berikut dan *WAJIB mengikuti urutan serta formatnya secara konsisten*.

PERENCANAAN PEMBELAJARAN MENDALAM

A. INFORMASI UMUM
- Nama sekolah: {$request->nama_sekolah}
- Nama guru: {$request->nama_guru}
- Jenjang: {$request->jenjang}
- Fase/Kelas/Semester: {$request->kelas} / {$request->semester}
- Mata pelajaran: {$request->mapel}
- Topik: {$request->topik}
- Alokasi waktu: {$request->waktu}
- Model pembelajaran: {$request->model}
- Pendekatan: {$request->pendekatan}
- Tujuan pembelajaran:
  (Tuliskan tujuan pembelajaran yang mencakup *kompetensi dan konten* sesuai Capaian Pembelajaran, menggunakan *kata kerja operasional yang terukur*)

B. IDENTIFIKASI
1. *Kesiapan Materi*
   - Uraikan kesiapan materi pembelajaran sesuai topik dan capaian pembelajaran.
   - Sertakan *visual/gambar pembelajaran* yang relevan untuk membantu pemahaman konsep (WAJIB ADA).

2. *Karakteristik Murid*
   - Uraikan kesiapan dan karakteristik murid (minat, gaya belajar, kemampuan awal).
   - Sertakan contoh pendekatan diferensiasi jika diperlukan.

3. *Dimensi Profil Lulusan*
   Sajikan dalam bentuk *TABEL Markdown* berikut (WAJIB sama persis, JANGAN dicentang):

| No | Dimensi Profil Lulusan | Kode | Centang |
|----|------------------------|------|---------|
| 1 | Keimanan dan Ketakwaan terhadap Tuhan YME | DPL1 | ☐ |
| 2 | Kewargaan | DPL2 | ☐ |
| 3 | Penalaran Kritis | DPL3 | ☐ |
| 4 | Kreativitas | DPL4 | ☐ |
| 5 | Kolaborasi | DPL5 | ☐ |
| 6 | Kemandirian | DPL6 | ☐ |
| 7 | Kesehatan | DPL7 | ☐ |
| 8 | Komunikasi | DPL8 | ☐ |

(PENTING: *Biarkan semua kotak tetap kosong*. Guru akan mencentang secara manual.)

C. DESAIN PEMBELAJARAN
1. *Tujuan Pembelajaran*
   - Selaraskan dengan tujuan pada bagian A.

2. *Kerangka Pembelajaran*
   Sajikan dalam bentuk tabel Markdown yang rapi:
   a. Praktik pedagogis  
   b. Kemitraan pembelajaran  
   c. Lingkungan pembelajaran  
   d. Pemanfaatan digital  
   - Sertakan *contoh aktivitas + gambar pendukung* yang relevan (WAJIB ADA).

D. PENGALAMAN BELAJAR
Susun langkah-langkah pembelajaran dengan prinsip *berkesadaran, bermakna, dan menggembirakan, serta mendeskripsikan pengalaman belajar **memahami – mengaplikasi – merefleksi*.

Sajikan dalam *TABEL Markdown* yang memuat:
1. *Kegiatan Pendahuluan*
   - Alokasi waktu (menit)
   - Prinsip pembelajaran
   - Aktivitas guru & murid
   - *Gambar/visual apersepsi* (WAJIB ADA)

2. *Kegiatan Inti*
   - Alokasi waktu (menit)
   - Prinsip pembelajaran
   - Aktivitas memahami, mengaplikasi, dan refleksi
   - *Gambar/visual materi inti* (WAJIB ADA)

3. *Kegiatan Penutup*
   - Alokasi waktu (menit)
   - Prinsip pembelajaran
   - Refleksi dan tindak lanjut
   - *Gambar/visual penutup* (WAJIB ADA)

E. ASESMEN PEMBELAJARAN
Buatkan asesmen lengkap dalam *TABEL Markdown*:
1. *Asesmen Awal (Assessment as Learning)*
2. *Asesmen Proses (Assessment for Learning)*
3. *Asesmen Akhir (Assessment of Learning)*
- Sertakan teknik, instrumen, dan kriteria penilaian.
- Tambahkan *visual pendukung jika relevan* (WAJIB ADA minimal satu).


| Mengetahui, | {$request->bulan_tahun} |
|---|---|
| Kepala {$request->nama_sekolah} | Guru Mata Pelajaran |
| | |
| | |
| | |
| (..........................) | (..........................) |
| NIP. | NIP. |

KETENTUAN TAMBAHAN (WAJIB DIPATUHI):
1. *GAMBAR / VISUAL WAJIB ADA*
   - Apapun mata pelajaran dan topiknya, *gambar harus ditampilkan langsung* di dalam modul.
   - Visual digunakan untuk memperjelas konsep, proses, atau pengalaman belajar (bukan hiasan).

2. *KHUSUS MAPEL PENDIDIKAN AGAMA ISLAM (JIKA DIMINTA DALIL):*
   - WAJIB menyertakan *teks ayat Al-Qur’an dan/atau Hadits secara lengkap*.
   - ❌ Jangan hanya menyebutkan nama surat, nomor ayat, atau nomor hadits.
   - Sertakan *terjemahan* dan *penjelasan keterkaitan dalil dengan tujuan dan aktivitas pembelajaran*.
   - Dalil ditempatkan secara kontekstual (tujuan, pengalaman belajar, atau refleksi).

3. Gunakan *format Markdown* secara konsisten (Heading, Bold, Tabel).
4. *JANGAN* menambahkan kata pembuka atau penutup seperti “Berikut adalah…” atau “Semoga bermanfaat”.
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
