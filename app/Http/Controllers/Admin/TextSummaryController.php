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
            $prompt = "Buatkan Materi Pembelajaran dengan format yang rapi dan informatif berdasarkan data berikut:
Kurikulum: {$request->kurikulum}
Jenjang: {$request->jenjang}
Kelas/Fase/Semester: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Topik Materi: {$request->topik}
Tipe Konten: {$request->tipe_konten} (Misal: Materi Lengkap, Ringkasan Padat, Poin-Poin Penting, atau Glosarium Istilah)
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING:
1. Mulai langsung dengan Judul Materi (Heading 1).
2. Sesuaikan kedalaman dan kompleksitas materi dengan jenjang dan kelas peserta didik, serta berikan penjelasan yang runtut, jelas, dan mudah dipahami.
3. Untuk Materi Lengkap:
- Jelaskan konsep secara komprehensif dan sistematis.
- Sertakan contoh-contoh kontekstual yang sesuai dengan tingkat siswa.
- Tampilkan gambar/visual secara langsung yang relevan dengan setiap penjelasan materi (bukan hanya disebutkan).
- Akhiri dengan kesimpulan yang merangkum inti materi.
- Sertakan referensi atau daftar pustaka yang valid dan relevan.
4. Untuk Ringkasan:
- Sajikan intisari materi dalam bentuk poin-poin utama.
- Gunakan bahasa ringkas, jelas, dan fokus pada konsep kunci.
5. Gunakan format Markdown (Heading, Bold, List, Tabel) agar mudah dibaca.
6. JANGAN ada kalimat pembuka basa-basi dari AI. Langsung ke konten materi.";

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
