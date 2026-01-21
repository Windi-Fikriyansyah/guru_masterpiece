@extends('layouts.admin')

@section('header', 'Teks & Ringkasan Materi')

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

    /* Document Styling - Elegant & Print Ready (Matched to LKPD/RPP) */
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
        <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Teks & Ringkasan Generator</h2>
        <p class="text-slate-500">Buat materi ajar, ringkasan, atau poin penting pembelajaran dengan mudah.</p>
    </div>

    <!-- Input Form Card -->
    <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-xl shadow-slate-200/60 border border-slate-100">
        <div class="flex items-center gap-3 mb-8 border-b border-slate-100 pb-4">
            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                <i data-lucide="align-left" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Input Materi</h3>
                <p class="text-sm text-slate-400">Tentukan topik dan gaya materi yang diinginkan</p>
            </div>
        </div>
        
        <form id="textSummaryForm" class="space-y-6">
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
                        <option value="paud">PAUD/TK</option>
                        <option value="sd">SD</option>
                        <option value="mi">MI</option>
                        <option value="smp">SMP</option>
                        <option value="mts">MTs</option>
                        <option value="sma">SMA</option>
                        <option value="ma">MA</option>
                        <option value="smk">SMK</option>
                        <option value="umum">Umum</option>
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
                        <option value="__other__">Lainnya...</option>
                    </select>
                    <div id="manual_mapel_container" class="mt-2 hidden">
                        <input type="text" id="mapel_manual" name="mapel_manual" placeholder="Masukkan Mata Pelajaran manually" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <!-- Topik Materi -->
                 <div class="space-y-2">
                    <label for="topik" class="block text-sm font-bold text-slate-700 ml-1">Topik Materi</label>
                    <input type="text" name="topik" id="topik" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Contoh: Ekosistem Laut, Hukum Newton" required>
                </div>

                 <!-- Tipe Konten -->
                 <div class="space-y-2">
                    <label for="tipe_konten" class="block text-sm font-bold text-slate-700 ml-1">Tipe Konten</label>
                    <select name="tipe_konten" id="tipe_konten" class="w-full select2-enable" required>
                        <option value="">Pilih Tipe Konten</option>
                        <option value="Materi Lengkap">Materi Lengkap</option>
                        <option value="Ringkasan / Resume">Ringkasan / Resume</option>
                        <option value="Poin-Poin Penting">Poin-Poin Penting (Bullet Points)</option>
                        <option value="Glosarium Istilah">Glosarium Istilah</option>
                        <option value="Contoh Soal & Pembahasan">Contoh Soal & Pembahasan</option>
                    </select>
                </div>
            </div>

            <!-- Instruksi Khusus -->
            <div class="space-y-2">
                <label for="instruksi_khusus" class="block text-sm font-bold text-slate-700 ml-1">Instruksi / Keinginan Khusus</label>
                <textarea name="instruksi_khusus" id="instruksi_khusus" rows="3" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Contoh: Gunakan bahasa yang santai, perbanyak analogi, sesuaikan untuk anak visual, dll"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" id="generateBtn" class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                    <i data-lucide="wand-2" class="w-6 h-6"></i>
                    Generate Materi
                </button>
            </div>
        </form>
    </div>

    <!-- Result Section -->
    <div id="resultSection" class="space-y-6">
        <div class="flex items-center gap-4">
            <div class="h-[1px] flex-1 bg-slate-200"></div>
            <h3 class="text-lg font-bold text-slate-400 uppercase tracking-widest">Hasil Generasi AI</h3>
            <div class="h-[1px] flex-1 bg-slate-200"></div>
        </div>

        <!-- Placeholder State -->
        <div id="placeholderResult" class="bg-white rounded-[2rem] border-2 border-dashed border-slate-200 p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6">
                <i data-lucide="file-question" class="w-10 h-10"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-400 mb-2">Belum Ada Hasil</h4>
            <p class="text-slate-400 max-w-sm">Isi formulir di atas dan klik tombol generate untuk membuat materi pembelajaran Anda.</p>
        </div>

        <!-- Loading State -->
        <div id="loadingResult" class="hidden bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/60 p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
            <div class="relative w-24 h-24 mb-6">
                <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-primary border-t-transparent animate-spin"></div>
            </div>
            <h4 class="text-xl font-bold text-slate-600 mb-2">Sedang Menulis Materi...</h4>
            <p class="text-slate-400 max-w-sm">Mohon tunggu, AI sedang menyusun materi yang informatif dan terstruktur.</p>
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

        // Handle Subject Selection
        $('#mapel').on('change', function() {
            if ($(this).val() === '__other__') {
                $('#manual_mapel_container').removeClass('hidden');
                $('#mapel_manual').prop('required', true).focus();
            } else {
                $('#manual_mapel_container').addClass('hidden');
                $('#mapel_manual').prop('required', false);
            }
        });

        // Handle Form Submission
        $('#textSummaryForm').on('submit', function(e) {
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
            $('html, body').animate({
                scrollTop: resultSection.offset().top - 150
            }, 500);

            let formData = $(this).serialize();
            if ($('#mapel').val() === '__other__') {
                const manualVal = $('#mapel_manual').val();
                formData = formData.replace(/mapel=[^&]*/, 'mapel=' + encodeURIComponent(manualVal));
            }

            $.ajax({
                url: "{{ route('admin.text_summary.generate') }}",
                method: 'POST',
                data: formData,
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
                            text: 'Materi telah berhasil dibuat.',
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
            filename: 'Materi-Pembelajaran.pdf',
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
                    table { width: 100%; border-collapse: collapse; }
                    td, th { border: 1px solid #000; padding: 5px; }
                </style>
            </head>
            <body>${content}</body>
            </html>
        `;

        const converted = htmlDocx.asBlob(htmlContent);
        const link = document.createElement('a');
        link.href = URL.createObjectURL(converted);
        link.download = 'Materi-Pembelajaran.docx';
        link.click();
        
        Swal.close();
    }
</script>
@endpush
