<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\AIService;

class TextSummaryController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.text_summary.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'topik' => 'required',
            'tipe_konten' => 'required',
            'instruksi_khusus' => 'nullable',
        ]);

        try {
            $prompt = "Buatkan Materi Pembelajaran dengan format yang rapi, panjang, mendalam, dan informatif berdasarkan data berikut:

Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas/Fase/Semester: {$request->kelas_fase_semester}
Mata Pelajaran: {$request->mapel}
Topik Materi: {$request->topik}
Tipe Konten: {$request->tipe_konten} 
(Misal: Materi Lengkap, Ringkasan Padat, Poin-Poin Penting, atau Glosarium Istilah)
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING (WAJIB DIPATUHI):
1. Mulai langsung dengan *Judul Materi (Heading 1)*. 
   JANGAN menambahkan kalimat pembuka, sapaan, atau basa-basi dari AI.

2. Materi harus *panjang, lengkap, dan utuh*, berupa penjelasan naratif yang runtut dan mudah dipahami.
   ❌ Bukan hanya poin-poin singkat.
   ✅ Wajib ada uraian penjelasan konsep secara detail.

3. Sesuaikan *kedalaman, bahasa, dan kompleksitas materi* dengan jenjang dan kelas peserta didik.

4. Untuk *Materi Lengkap*, WAJIB:
   - Menjelaskan konsep secara *komprehensif, sistematis, dan berurutan*.
   - Memberikan *contoh kontekstual* yang dekat dengan kehidupan siswa sesuai jenjangnya.
   - Menampilkan *gambar/visual secara langsung di dalam materi* yang relevan dengan setiap subpembahasan.
     ⚠️ Gambar harus benar-benar membantu pemahaman konsep, bukan sekadar hiasan.
   - Menyertakan *kesimpulan* yang merangkum inti materi.
   - Menyertakan *referensi atau daftar pustaka* yang valid dan relevan.

5. Untuk *Ringkasan*:
   - Sajikan inti materi dalam poin-poin utama.
   - Tetap jelas, padat, dan fokus pada konsep kunci.

6. Jika materi adalah *Pendidikan Agama (Islam)* dan diminta dalil:
   - WAJIB menuliskan *teks ayat Al-Qur’an dan/atau hadits secara lengkap*.
   - ❌ Jangan hanya menuliskan nama surat, nomor ayat, atau nomor hadits.
   - Sertakan *terjemahan yang relevan* dan *penjelasan singkat keterkaitan dalil dengan materi*.

7. Gambar/visual:
   - WAJIB ADA pada setiap materi, apapun topiknya.
   - Disesuaikan langsung dengan isi pembahasan.
   - Digunakan untuk memperjelas konsep, proses, atau contoh dalam materi.

8. Gunakan *format Markdown* secara konsisten:
   - Heading (H1, H2, H3)
   - Bold dan Italic seperlunya
   - List (bullet/numbering)
   - Tabel jika diperlukan

OUTPUT AKHIR:
Materi ajar yang lengkap, panjang, sistematis, mudah dipahami siswa, kaya penjelasan, dan didukung visual gambar yang relevan di setiap bagian materi.";
            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan konten. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('TextSummary Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
