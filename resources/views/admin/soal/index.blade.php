@extends('layouts.admin')

@section('header', 'Generator Soal Otomatis')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* Custom style to make Select2 match Tailwind inputs */
    .select2-container .select2-selection--single {
        height: 46px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.75rem !important;
        padding-top: 8px !important;
        background-color: #f8fafc;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 10px !important;
        right: 10px !important;
    }
    .select2-container--default .select2-selection--single:focus {
        border-color: #6366f1 !important;
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
    }

    /* Document Styling - Elegant & Print Ready */
    #resultContent {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        line-height: 1.8;
        color: #1e293b;
        background: white;
        padding: 3rem;
        max-width: 210mm; /* A4 width */
        margin: 0 auto;
    }

    #resultContent h1 {
        text-align: center;
        font-size: 1.5rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #0f172a;
        margin-bottom: 0.5rem;
        border-bottom: 3px double #334155;
        padding-bottom: 1rem;
    }

    #resultContent h2 {
        text-align: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin: 1.5rem 0 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }

    #resultContent h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #334155;
        margin: 1.5rem 0 0.75rem;
        padding-left: 0.5rem;
        border-left: 4px solid #6366f1;
    }

    #resultContent p {
        margin-bottom: 1rem;
        text-align: justify;
        color: #334155;
    }

    #resultContent ul, #resultContent ol {
        margin: 0.75rem 0 1rem 1.5rem;
        color: #334155;
    }

    #resultContent ul {
        list-style-type: disc;
    }

    #resultContent ol {
        list-style-type: decimal;
    }

    #resultContent li {
        margin-bottom: 0.5rem;
        padding-left: 0.25rem;
    }

    #resultContent table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        font-size: 0.95rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    #resultContent thead {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
    }

    #resultContent th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.05em;
    }

    #resultContent td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        vertical-align: top;
    }

    #resultContent tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    @media print {
        #resultContent {
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Generator Soal Otomatis</h2>
        <p class="text-slate-500">Buat soal ujian yang berkualitas, variatif, dan sesuai kurikulum dalam hitungan detik.</p>
    </div>

    <!-- Input Form Card -->
    <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-xl shadow-slate-200/60 border border-slate-100">
        <div class="flex items-center gap-3 mb-8 border-b border-slate-100 pb-4">
            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                <i data-lucide="file-check-2" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Konfigurasi Soal</h3>
                <p class="text-sm text-slate-400">Atur parameter soal yang ingin dibuat</p>
            </div>
        </div>
        
        <form id="soalForm" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kurikulum -->
                <div class="space-y-2">
                    <label for="kurikulum" class="block text-sm font-bold text-slate-700 ml-1">Kurikulum</label>
                    <select name="kurikulum" id="kurikulum" class="w-full select2-enable" required>
                        <option value="">Pilih Kurikulum</option>
                        <option value="Kurikulum Merdeka">Kurikulum Merdeka</option>
                        <option value="Kurikulum 2013">Kurikulum 2013</option>
                        <option value="Cambridge">Cambridge</option>
                        <option value="International Primary Curriculum">International Primary Curriculum</option>
                    </select>
                </div>

                <!-- Jenjang -->
                <div class="space-y-2">
                    <label for="jenjang" class="block text-sm font-bold text-slate-700 ml-1">Jenjang</label>
                    <select name="jenjang" id="jenjang" class="w-full select2-enable" required>
                        <option value="">Pilih Jenjang</option>
                        <option value="PAUD/TK">PAUD/TK</option>
                        <option value="SD/MI">SD/MI</option>
                        <option value="SMP/MTS">SMP/MTS</option>
                        <option value="SMA/MA/SMK">SMA/MA/SMK</option>
                        <option value="Kuliah">Kuliah</option>
                        <option value="Umum">Umum</option>
                    </select>
                </div>

                <!-- Kelas/Kelompok -->
                <div class="space-y-2">
                    <label for="kelas" class="block text-sm font-bold text-slate-700 ml-1">Kelas / Kelompok</label>
                    <input type="text" name="kelas" id="kelas" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Contoh: TK A atau Kelompok B" required>
                </div>

                <!-- Mata Pelajaran -->
                <div class="space-y-2">
                    <label for="mapel" class="block text-sm font-bold text-slate-700 ml-1">Mata Pelajaran / Lingkup Perkembangan</label>
                    <select name="mapel" id="mapel" class="w-full select2-enable" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bentuk Soal -->
                <div class="space-y-2">
                    <label for="bentuk_soal" class="block text-sm font-bold text-slate-700 ml-1">Bentuk Soal</label>
                    <select name="bentuk_soal" id="bentuk_soal" class="w-full select2-enable" required>
                        <option value="">Pilih Bentuk</option>
                        <option value="Pilihan Ganda">Pilihan Ganda</option>
                        <option value="Uraian (Essay)">Uraian (Essay)</option>
                        <option value="Isian Singkat">Isian Singkat</option>
                        <option value="Benar / Salah">Benar / Salah</option>
                        <option value="Menjodohkan">Menjodohkan</option>
                    </select>
                </div>

                 <!-- Jumlah Soal -->
                 <div class="space-y-2">
                    <label for="jumlah_soal" class="block text-sm font-bold text-slate-700 ml-1">Jumlah Soal</label>
                    <input type="number" name="jumlah_soal" id="jumlah_soal" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Contoh: 10" min="1" max="50" required>
                </div>

                <!-- Taksonomi Bloom -->
                <div class="space-y-2">
                    <label for="taksonomi" class="block text-sm font-bold text-slate-700 ml-1">Taksonomi Bloom</label>
                    <select name="taksonomi" id="taksonomi" class="w-full select2-enable" required>
                        <option value="">Pilih Taksonomi</option>
                        <option value="C1 - Pengetahuan">C1 - Pengetahuan</option>
                        <option value="C2 - Pemahaman">C2 - Pemahaman</option>
                        <option value="C3 - Penerapan">C3 - Penerapan</option>
                        <option value="C4 - Analisis">C4 - Analisis</option>
                        <option value="C5 - Evaluasi">C5 - Evaluasi</option>
                        <option value="C6 - Kreasi">C6 - Kreasi</option>
                        <option value="Campuran">Campuran</option>
                    </select>
                </div>

                <!-- Tingkat Kesulitan -->
                <div class="space-y-2">
                    <label for="kesulitan" class="block text-sm font-bold text-slate-700 ml-1">Tingkat Kesulitan</label>
                    <select name="kesulitan" id="kesulitan" class="w-full select2-enable" required>
                        <option value="">Pilih Kesulitan</option>
                        <option value="Mudah">Mudah</option>
                        <option value="Sedang">Sedang</option>
                        <option value="Sulit">Sulit</option>
                        <option value="HOTS (High Order Thinking Skills)">HOTS</option>
                    </select>
                </div>
            </div>

            <!-- Topik Materi -->
            <div class="space-y-2">
                <label for="topik" class="block text-sm font-bold text-slate-700 ml-1">Topik/Bab Soal</label>
                <input type="text" name="topik" id="topik" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Contoh: Operasi Hitung Campuran, Perang Diponegoro" required>
            </div>

             <!-- Tempel Materi -->
             <div class="space-y-2">
                <label for="materi" class="block text-sm font-bold text-slate-700 ml-1">Tempel Materi (Opsional)</label>
                <textarea name="materi" id="materi" rows="4" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Tempelkan teks materi di sini agar soal lebih akurat sesuai sumber belajar..."></textarea>
            </div>

            <!-- Instruksi Khusus -->
            <div class="space-y-2">
                <label for="instruksi_khusus" class="block text-sm font-bold text-slate-700 ml-1">Instruksi / Keinginan Khusus</label>
                <textarea name="instruksi_khusus" id="instruksi_khusus" rows="2" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Contoh: sertakan gambar ilustrasi (deskripsi), soal cerita, dll"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" id="generateBtn" class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                    <i data-lucide="wand-2" class="w-6 h-6"></i>
                    Generate Soal
                </button>
            </div>
        </form>
    </div>

    <!-- Result Section -->
    <div id="resultSection" class="space-y-6">
        <div class="flex items-center gap-4">
            <div class="h-[1px] flex-1 bg-slate-200"></div>
            <h3 class="text-lg font-bold text-slate-400 uppercase tracking-widest">Hasil Generasi Soal</h3>
            <div class="h-[1px] flex-1 bg-slate-200"></div>
        </div>

        <!-- Placeholder State -->
        <div id="placeholderResult" class="bg-white rounded-[2rem] border-2 border-dashed border-slate-200 p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6">
                <i data-lucide="file-question" class="w-10 h-10"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-400 mb-2">Belum Ada Soal</h4>
            <p class="text-slate-400 max-w-sm">Isi parameter soal di atas dan klik tombol generate untuk membuat soal ujian Anda.</p>
        </div>

        <!-- Loading State -->
        <div id="loadingResult" class="hidden bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/60 p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
            <div class="relative w-24 h-24 mb-6">
                <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-primary border-t-transparent animate-spin"></div>
            </div>
            <h4 class="text-xl font-bold text-slate-600 mb-2">Sedang Meracik Soal...</h4>
            <p class="text-slate-400 max-w-sm">Mengkalibrasi tingkat kesulitan dan menyusun butir soal yang valid.</p>
        </div>

        <!-- Actions -->
        <div id="actionButtons" class="hidden flex flex-wrap gap-3 justify-center mb-6">
            <button onclick="downloadPDF()" class="flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-red-500/20">
                <i data-lucide="file-down" class="w-5 h-5"></i>
                <span>Unduh PDF</span>
            </button>
            <button onclick="downloadWord()" class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-600/20">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span>Unduh Word</span>
            </button>
            <button onclick="copyResult()" class="flex items-center gap-2 px-5 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-semibold rounded-xl transition-all shadow-lg shadow-slate-700/20">
                <i data-lucide="copy" class="w-5 h-5"></i>
                <span>Salin Teks</span>
            </button>
        </div>

        <!-- Result Content -->
        <div id="resultContent" class="hidden bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 p-8 lg:p-16">
            <!-- Content Injected Here -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://unpkg.com/html-docx-js/dist/html-docx.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2-enable').select2({
            width: '100%'
        });

        // Handle Form Submission
        $('#soalForm').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#generateBtn');
            const originalBtnHtml = btn.html();
            
            // UI References
            const placeholder = $('#placeholderResult');
            const loading = $('#loadingResult');
            const actions = $('#actionButtons');
            const content = $('#resultContent');
            const resultSection = $('#resultSection');

            // Set Loading UI
            btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
            placeholder.addClass('hidden');
            content.addClass('hidden');
            actions.addClass('hidden');
            loading.removeClass('hidden');

            // Scroll to loading
            const main = $('main');

main.animate({
    scrollTop: main.scrollTop() + resultSection.position().top - 120
}, 500);


            $.ajax({
                url: "{{ route('admin.soal.generate') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.result) {
                        const htmlContent = marked.parse(response.result);
                        content.html(htmlContent);
                        
                        // Switch to Result UI
                        loading.addClass('hidden');
                        content.removeClass('hidden');
                        actions.removeClass('hidden');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Soal berhasil dibuat.',
                            confirmButtonColor: '#6366f1',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        showError('Gagal mendapatkan hasil dari AI.');
                    }
                },
                error: function(xhr) {
                    loading.addClass('hidden');
                    placeholder.removeClass('hidden');
                    
                    let errorMsg = 'Terjadi kesalahan sistem.';
                    if(xhr.responseJSON && xhr.responseJSON.error) {
                        errorMsg = xhr.responseJSON.error;
                    }
                    showError(errorMsg);
                },
                complete: function() {
                    btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed').html(originalBtnHtml);
                }
            });
        });
    });

    function showError(msg) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: msg,
            confirmButtonColor: '#ef4444'
        });
    }

    function copyResult() {
        const text = document.getElementById('resultContent').innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Teks berhasil disalin ke clipboard.',
                confirmButtonColor: '#6366f1',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }

    function downloadPDF() {
        const element = document.getElementById('resultContent');
        const opt = {
            margin: [15, 15, 15, 15],
            filename: 'Soal-Ujian.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        Swal.fire({
            title: 'Memproses PDF...',
            didOpen: () => Swal.showLoading()
        });

        html2pdf().set(opt).from(element).save().then(() => {
            Swal.close();
        });
    }

    function downloadWord() {
        Swal.fire({
            title: 'Memproses Word...',
            didOpen: () => Swal.showLoading()
        });

        const content = document.getElementById('resultContent').innerHTML;
        const htmlContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <style>
                    body { font-family: 'Calibri', sans-serif; font-size: 11pt; }
                    h1 { font-size: 14pt; font-weight: bold; text-align: center; }
                    h2 { font-size: 13pt; font-weight: bold; margin-top: 15px; }
                    h3 { font-size: 12pt; font-weight: bold; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
                    td, th { border: 1px solid #000; padding: 5px; vertical-align: top; }
                    ul, ol { margin-left: 0; padding-left: 20px; }
                    ul { list-style-type: none; }
                    ol { list-style-type: decimal; }
                    li { margin-bottom: 5px; }
                </style>
            </head>
            <body>${content}</body>
            </html>
        `;

        const converted = htmlDocx.asBlob(htmlContent);
        const link = document.createElement('a');
        link.href = URL.createObjectURL(converted);
        link.download = 'Soal-Ujian.docx';
        link.click();
        
        Swal.close();
    }
</script>
@endpush
