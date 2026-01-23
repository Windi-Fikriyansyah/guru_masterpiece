<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RppController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.rpp.index', compact('subjects'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'jenjang' => 'required',
            'kelas' => 'required',
            'mapel' => 'required',
            'topik' => 'required',
            'waktu' => 'required',
            'tujuan' => 'required',
            'model' => 'required',
            'pendekatan' => 'required',
        ]);

        try {
            $prompt = <<<PROMPT
Nama Sekolah      : {$request->nama_sekolah}
Nama Guru         : {$request->nama_guru}
Jenjang           : {$request->jenjang}
Kelas/Fase/Semester : {$request->kelas}
Mata Pelajaran    : {$request->mapel}
Topik             : {$request->topik}
Alokasi Waktu     : {$request->waktu}
Model Pembelajaran: {$request->model}
Pendekatan        : {$request->pendekatan}
Tujuan Pembelajaran:
{$request->tujuan}

Instruksi Khusus:
{$request->instruksi}

--------------------------------------------

Buatkan **Perencanaan Pembelajaran (Modul Ajar)** dengan **format resmi Kurikulum Merdeka Belajar** berdasarkan data di atas.

⚠️ **KETENTUAN PENTING**
- Langsung mulai dengan **JUDUL MODUL AJAR**
- **JANGAN** menulis kalimat pembuka seperti:
  "Berikut ini...", "Tentu...", "Saya akan..."
- Gunakan **bahasa formal, baku, dan administratif pendidikan Indonesia**
- Sajikan **dalam bentuk TABEL yang rapi (Markdown standard)**
- Terapkan **pembelajaran mendalam** dengan prinsip:
  **berkesadaran, bermakna, dan menggembirakan**
- Gunakan pemisah baris tabel yang simpel (cukup 3 tanda hubung: `---`)

---

### STRUKTUR WAJIB MODUL AJAR

#### 1. IDENTIFIKASI
Buatkan tabel yang memuat:
- Kesiapan Materi
- Karakteristik Murid
- Dimensi Profil Lulusan (wajib semua):
  - DPL1 Keimanan dan Ketakwaan terhadap Tuhan YME
  - DPL2 Kewargaan
  - DPL3 Penalaran Kritis
  - DPL4 Kreativitas
  - DPL5 Kolaborasi
  - DPL6 Kemandirian
  - DPL7 Kesehatan
  - DPL8 Komunikasi

---

#### 2. DESAIN PEMBELAJARAN
Dalam bentuk tabel, jelaskan secara rinci:
- Tujuan Pembelajaran
- Praktik Pedagogis
- Kemitraan Pembelajaran
- Lingkungan Pembelajaran
- Pemanfaatan Teknologi/Digital

---

#### 3. PENGALAMAN BELAJAR
Buat tabel **Langkah-Langkah Pembelajaran** yang terdiri dari:

**a. Kegiatan Pendahuluan**
- Jumlah menit
- Prinsip pembelajaran yang digunakan
- Uraian langkah kegiatan

**b. Kegiatan Inti**
- Jumlah menit
- Prinsip pembelajaran yang digunakan
- Aktivitas guru dan peserta didik
- Keterkaitan dengan Dimensi Profil Lulusan

**c. Kegiatan Penutup**
- Jumlah menit
- Prinsip pembelajaran yang digunakan
- Refleksi dan tindak lanjut

---

#### 4. ASESMEN PEMBELAJARAN
Buat tabel asesmen yang memuat:
- Asesmen Awal (diagnostik)
- Asesmen Proses (formatif)
- Asesmen Akhir (sumatif)

Pastikan asesmen mencakup:
- **Asesmen sebagai pembelajaran**
- **Asesmen untuk pembelajaran**
- **Asesmen hasil pembelajaran**

Lengkapi dengan:
- Teknik asesmen
- Instrumen
- Kriteria penilaian

---

#### 5. PENUTUP DOKUMEN
Akhiri Modul Ajar dengan format resmi berikut:

Mengetahui,
Kepala Sekolah
Nama Kota/Kabupaten

( Tanda Tangan )
**Nama Kepala Sekolah**

---

Guru Kelas / Guru Mata Pelajaran

( Tanda Tangan )
**Nama Guru**
PROMPT;

            $apiKey = config('services.gemini.key');

            if (empty($apiKey)) {
                return response()->json(['error' => 'Gemini API Key belum dikonfigurasi. Harap tambahkan GEMINI_API_KEY di file .env'], 500);
            }

            // Try multiple models in case one is overloaded
            $models = [
                
                'gemini-2.5-flash',
                'gemini-1.5-pro',
            ];

            $lastError = null;

            foreach ($models as $model) {
                // Retry logic per model
                for ($attempt = 1; $attempt <= 2; $attempt++) {
                    try {
                        $response = Http::timeout(180)->withHeaders([
                            'Content-Type' => 'application/json',
                        ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => $prompt]
                                    ]
                                ]
                            ]
                        ]);

                        if ($response->successful()) {
                            $result = $response->json('candidates.0.content.parts.0.text');
                            return response()->json(['result' => $result]);
                        }

                        $lastError = $response->body(); // Get full body for better debugging
                        $errorMessage = $response->json('error.message', 'Unknown error');
                        
                        \Log::warning("Gemini Model {$model} failed (Attempt {$attempt})", [
                            'status' => $response->status(),
                            'error' => $errorMessage
                        ]);

                        // If overloaded, wait and try again
                        if (str_contains($errorMessage, 'overloaded') || $response->status() == 429) {
                            sleep(3);
                            continue;
                        }
                    } catch (\Exception $e) {
                        $lastError = $e->getMessage();
                        \Log::error("Gemini Exception for {$model}", ['message' => $lastError]);
                    }

                    // For other errors or timeouts, try the next model
                    break;
                }
            }

            \Log::error('Gemini API All Models Failed', ['last_error' => $lastError]);
            return response()->json(['error' => 'Server AI sedang sibuk atau limit tercapai. Silakan coba lagi dalam beberapa saat.'], 503);
        } catch (\Exception $e) {
            \Log::error('RPP Generation Exception', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
}
