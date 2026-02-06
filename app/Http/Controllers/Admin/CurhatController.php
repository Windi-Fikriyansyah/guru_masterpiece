<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AIService;

class CurhatController extends Controller
{
    protected AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        return view('admin.curhat.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'cerita' => 'required|string|min:10',
            'gaya_respon' => 'required|string',
        ]);

        try {
            $gaya = $request->gaya_respon;
            
            $prompt = "Anda adalah seorang pendamping guru yang bijaksasna, penuh empati, dan sangat mendukung. 
Seorang guru ingin mencurahkan perasaannya atau berbagi cerita sebagai berikut:
'{$request->cerita}'

Tugas Anda adalah memberikan respon dengan gaya: {$gaya}.

Panduan Respon berdasarkan gaya:
1. Memberi Motivasi: Berikan semangat, ingatkan akan mulianya profesi guru, dan bangkitkan rasa percaya diri.
2. Mendengarkan Saja: Berikan respon yang sangat empatik, validasi perasaan mereka, dan tunjukkan bahwa Anda memahami beban yang mereka rasakan tanpa menghakimi.
3. Memberi Solusi Praktis: Berikan langkah-langkah konkret, tips, atau strategi yang bisa langsung diterapkan untuk menghadapi masalah tersebut.
4. Humor/Menghibur: Gunakan humor yang sehat, anekdot lucu tentang dunia guru yang relevan, atau cara pandang yang jenaka untuk meringankan suasana hati.

PENTING:
- Langsung berikan respon tanpa kalimat pembuka seperti 'Baik, ini respon saya...'.
- Gunakan bahasa Indonesia yang hangat, sopan, dan akrab (Gunakan panggilan 'Bapak/Ibu Guru' atau 'Rekan Guru').
- Gunakan format Markdown yang rapi (bold, italic, list jika perlu).
- Pastikan respon benar-benar sesuai dengan gaya yang dipilih.";

            $result = $this->aiService->generateContent($prompt);

            if ($result) {
                return response()->json(['result' => $result]);
            }

            return response()->json(['error' => 'Gagal menghasilkan respon. Silakan coba lagi.'], 500);

        } catch (\Exception $e) {
            \Log::error('Curhat Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
