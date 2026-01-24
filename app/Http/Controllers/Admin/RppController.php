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
Nama Sekolah        : {$request->nama_sekolah}
Nama Guru           : {$request->nama_guru}
Jenjang             : {$request->jenjang}
Fase/Kelas/Semester : {$request->kelas} / {$request->semester}
Mata Pelajaran      : {$request->mapel}
Topik               : {$request->topik}
Alokasi Waktu       : {$request->waktu}
Model Pembelajaran  : {$request->model}
Pendekatan          : {$request->pendekatan}
Tujuan Pembelajaran :
{$request->tujuan}

Instruksi Khusus:
{$request->instruksi}

==================================================

BUATKAN **MODUL AJAR KURIKULUM MERDEKA** SECARA RESMI DAN ADMINISTRATIF.

⚠️ **ATURAN KERAS (WAJIB DIPATUHI)**
- LANGSUNG mulai dengan **JUDUL: MODUL AJAR**
- **DILARANG** menulis kata pembuka seperti:
  “Berikut ini…”, “Tentu…”, “Saya akan…”, “Adapun…”
- Gunakan **bahasa baku, formal, administratif pendidikan Indonesia**
- **SEMUA BAGIAN WAJIB DALAM BENTUK TABEL (Markdown)**
- Tidak boleh ada paragraf narasi di luar tabel
- Terapkan **pembelajaran mendalam**:
  berkesadaran, bermakna, dan menggembirakan
- Gunakan pemisah tabel standar Markdown (`| --- |`)

==================================================

## MODUL AJAR

### A. IDENTITAS MODUL
| Komponen | Uraian |
| :--- | :--- |
| Nama Sekolah | |
| Mata Pelajaran | |
| Fase / Kelas / Semester | |
| Topik | |
| Alokasi Waktu | |
| Model Pembelajaran | |
| Pendekatan Pembelajaran | |
| Nama Guru | |

---

### B. IDENTIFIKASI
| Aspek | Deskripsi |
| :--- | :--- |
| Kesiapan Materi | |
| Karakteristik Murid | |
| DPL1 Keimanan dan Ketakwaan terhadap Tuhan YME | |
| DPL2 Kewargaan | |
| DPL3 Penalaran Kritis | |
| DPL4 Kreativitas | |
| DPL5 Kolaborasi | |
| DPL6 Kemandirian | |
| DPL7 Kesehatan | |
| DPL8 Komunikasi | |

---

### C. DESAIN PEMBELAJARAN
| Komponen | Uraian |
| :--- | :--- |
| Tujuan Pembelajaran | |
| Praktik Pedagogis | |
| Kemitraan Pembelajaran | |
| Lingkungan Pembelajaran | |
| Pemanfaatan Teknologi / Media Digital | |

---

### D. PENGALAMAN BELAJAR

#### 1. Kegiatan Pendahuluan
| Waktu (Menit) | Prinsip Pembelajaran | Langkah Kegiatan |
| :--- | :--- | :--- |

#### 2. Kegiatan Inti
| Waktu (Menit) | Aktivitas Guru & Peserta Didik | Dimensi Profil Lulusan |
| :--- | :--- | :--- |

#### 3. Kegiatan Penutup
| Waktu (Menit) | Prinsip Pembelajaran | Refleksi & Tindak Lanjut |
| :--- | :--- | :--- |

---

### E. ASESMEN PEMBELAJARAN
(Wajib dalam bentuk tabel terpisah, jangan digabung dengan teks lain)

| Jenis Asesmen | Tujuan | Teknik | Instrumen | Kriteria |
| :--- | :--- | :--- | :--- | :--- |
| Asesmen Awal (Diagnostik) | | | | |
| Asesmen Proses (Formatif) | | | | |
| Asesmen Akhir (Sumatif) | | | | |

---

### F. PENGESAHAN

| Mengetahui | |
| :--- | :--- |
| Kepala Sekolah | |
| Nama Kota / Kabupaten | |
| Tanda Tangan | |
| Nama Kepala Sekolah | |

<br>

| Guru Mata Pelajaran | |
| :--- | :--- |
| Tanda Tangan | |
| Nama Guru | |

==================================================

ISI SEMUA TABEL SECARA LENGKAP, REALISTIS, DAN SIAP DIGUNAKAN DALAM PEMBELAJARAN NYATA.
PASTIKAN SETIAP TABEL MEMILIKI BARIS KOSONG SEBELUM DAN SESUDAHNYA AGAR TERRENDER DENGAN BENAR.
PROMPT;


            $apiKey = $request->input('api_key')
                ?: config('services.gemini.key');


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
