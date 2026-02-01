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
Kelas: {$request->kelas}
Mata Pelajaran: {$request->mapel}
Topik Materi: {$request->topik}
Tipe Konten: {$request->tipe_konten} (Misal: Materi Lengkap, Ringkasan Padat, Poin-Poin Penting, atau Glosarium Istilah)
Instruksi/Keinginan Khusus: {$request->instruksi_khusus}

PENTING:
1. Mulai langsung dengan Judul Materi (Heading 1).
2. Sesuaikan kedalaman materi dengan Jenjang dan Kelas siswa.
3. Untuk 'Materi Lengkap', jelaskan konsep secara komprehensif dengan contoh.
4. Untuk 'Ringkasan', ambil intisari materi saja.
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
