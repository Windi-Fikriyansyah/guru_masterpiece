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
1. Solusi & Praktis: Berikan langkah-langkah konkret, tips, atau strategi sederhana yang dapat langsung diterapkan di kelas. Fokus pada solusi realistis, mudah dipahami, dan sesuai konteks masalah guru.
2. Empatik & Menenangkan: Berikan respon yang hangat dan penuh empati. Validasi perasaan guru, tunjukkan bahwa pengalaman dan tantangan mereka dipahami dan dihargai, tanpa menghakimi atau terburu-buru memberi solusi.
3. Reflektif & Mendalam: Ajak guru merenungkan pengalaman mengajar dengan membantu melihat akar masalah, makna pembelajaran, dan proses yang terjadi. Gunakan pertanyaan reflektif dan sudut pandang yang menumbuhkan kesadaran.
4. Inspiratif & Memotivasi: Berikan semangat dan dorongan positif. Ingatkan kembali tentang nilai dan peran mulia profesi guru, serta hadirkan sudut pandang baru yang membangkitkan kepercayaan diri dan harapan.
5. Ringkas & Langsung ke Inti: Sampaikan respon secara singkat, jelas, dan to the point. Fokus pada inti permasalahan dan solusi utama tanpa penjelasan panjang.
6. Terstruktur & Sistematis: Berikan respon dengan analisis yang rapi dan bertahap, menggunakan poin-poin atau urutan langkah yang jelas agar mudah diikuti dan dipahami.
7. Humor & Menghibur: Gunakan humor yang sehat, ringan, dan relevan dengan dunia guru untuk mencairkan suasana. Tetap jaga empati dan makna solusi, tanpa meremehkan masalah yang dihadapi.

PENTING:
- Langsung berikan respon tanpa kalimat pembuka seperti 'Baik, ini respon saya...'.
- Gunakan bahasa Indonesia yang hangat, sopan, dan akrab (Gunakan panggilan 'Bapak/Ibu Guru' atau 'Rekan Guru').
- Bahasa harus sopan, profesional, dan mendukung, Gaya respon disesuaikan dengan emosi dan konteks guru dan Humor tidak bersifat sarkastik atau merendahkan.
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
