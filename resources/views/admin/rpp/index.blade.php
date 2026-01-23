@extends('layouts.admin')

@section('header')
    RPP / Modul Ajar Generator
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="mb-10 text-center">
            <h2 class="text-3xl font-extrabold text-slate-800 mb-2">BUAT RPM/RPP/MODUL AJAR OTOMATIS</h2>
            <p class="text-slate-500">Buat rencana pembelajaran yang komprehensif dalam hitungan detik</p>
        </div>

        <!-- Generator Form Card -->
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden mb-12">
            <div class="p-8 lg:p-12">
                <form id="rppForm" class="space-y-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Nama Sekolah</label>
                            <input type="text" name="nama_sekolah" placeholder="Contoh: SD Negeri 1 Pontianak"
                                class="w-full bg-slate-50 rounded-2xl px-5 py-4" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700 ml-1">Nama Guru</label>
                            <input type="text" name="nama_guru" placeholder="Contoh: Budi Santoso, S.Pd"
                                class="w-full bg-slate-50 rounded-2xl px-5 py-4" required>
                        </div>
                       
                        

                        <!-- Kurikulum -->
                        <div class="space-y-2">
                            <label for="kurikulum" class="text-sm font-bold text-slate-700 ml-1">Kurikulum</label>
                            <select id="kurikulum" name="kurikulum" class="search-select w-full" required>
                                <option value="">Pilih Kurikulum</option>
                                <option value="merdeka">Kurikulum Merdeka</option>
                                <option value="k13">Kurikulum 2013 (K13)</option>
                                <option value="KBC">KBC</option>
                                <option value="Internasional">Kurikulum Internasional</option>
                            </select>
                        </div>

                        <!-- Jenjang -->
                        <div class="space-y-2">
                            <label for="jenjang" class="text-sm font-bold text-slate-700 ml-1">Jenjang</label>
                            <select id="jenjang" name="jenjang" class="search-select w-full" required>
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
                            <label for="kelas" class="text-sm font-bold text-slate-700 ml-1">Kelas/Fase/Semester</label>
                            <input type="text" id="kelas" name="kelas"
                                placeholder="Contoh: Kelas IV Fase B atau kelas X Fase E"
                                class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-600 placeholder:text-slate-400"
                                required>
                        </div>

                        <!-- Mata Pelajaran -->
                        <div class="space-y-2">
                            <label for="mapel" class="text-sm font-bold text-slate-700 ml-1">Mata Pelajaran</label>
                            <select id="mapel" name="mapel" class="search-select w-full" required>
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                @endforeach
                                <option value="__other__">Lainnya...</option>
                            </select>
                            <div id="manual_mapel_container" class="mt-2 hidden">
                                <input type="text" id="mapel_manual" name="mapel_manual"
                                    placeholder="Masukkan Mata Pelajaran manually"
                                    class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-600 placeholder:text-slate-400">
                            </div>
                        </div>

                        <!-- Topik Utama -->
                        <div class="space-y-2">
                            <label for="topik" class="text-sm font-bold text-slate-700 ml-1">Topik/Materi Ajar</label>
                            <input type="text" id="topik" name="topik"
                                placeholder="Masukkan Topik atau Materi Ajar"
                                class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-600 placeholder:text-slate-400"
                                required>
                        </div>

                        <!-- Alokasi Waktu -->
                        <div class="space-y-2">
                            <label for="waktu" class="text-sm font-bold text-slate-700 ml-1">Alokasi Waktu</label>
                            <input type="text" id="waktu" name="waktu" placeholder="Contoh: 2x45 Menit (2JP)"
                                class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-600 placeholder:text-slate-400"
                                required>
                        </div>

                        <!-- Model Pembelajaran -->
                        <div class="space-y-2">
                            <label for="model" class="text-sm font-bold text-slate-700 ml-1">Model Pembelajaran</label>
                            <select id="model" name="model" class="search-select w-full" required>
                                <option value="">Pilih Model</option>
                                <option value="pjbl">Project Based Learning (PjBL)</option>
                                <option value="pbl">Problem Based Learning (PBL)</option>
                                <option value="discovery">Discovery Learning</option>
                                <option value="inquiry">Inquiry Learning</option>
                                <option value="cooperative">Cooperative Learning</option>
                                <option value="direct">Direct Instruction</option>
                            </select>
                        </div>

                        <!-- Pendekatan -->
                        <div class="space-y-2">
                            <label for="pendekatan" class="text-sm font-bold text-slate-700 ml-1">Pendekatan</label>
                            <select id="pendekatan" name="pendekatan" class="search-select w-full" required>
                                <option value="">Pilih Pendekatan</option>
                                <option value="Pembelajaran Mendalam">Pembelajaran Mendalam</option>
                                <option value="__other__">Lainnya...</option>
                            </select>
                            <div id="manual_pendekatan_container" class="mt-2 hidden">
                                <input type="text" id="pendekatan_manual" name="pendekatan_manual"
                                    placeholder="Masukkan Pendekatan manually"
                                    class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-600 placeholder:text-slate-400">
                            </div>
                        </div>

                        <!-- Tujuan Pembelajaran -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="tujuan" class="text-sm font-bold text-slate-700 ml-1">Tujuan
                                Pembelajaran</label>
                            <textarea id="tujuan" name="tujuan" rows="3"
                                placeholder="Apa yang ingin dicapai setelah pembelajaran ini?"
                                class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-600 placeholder:text-slate-400 resize-none"
                                required></textarea>
                        </div>

                        <!-- Instruksi Khusus -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="instruksi" class="text-sm font-bold text-slate-700 ml-1">Intruksi Khusus</label>
                            <textarea id="instruksi" name="instruksi" rows="3"
                                placeholder="Buatkan isi rencana pembelajaran dengan lengkap dan....."
                                class="w-full bg-slate-50 border-none rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary/20 transition-all font-medium text-slate-600 placeholder:text-slate-400 resize-none"></textarea>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="generateBtn"
                            class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold py-5 rounded-2xl shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3">
                            <i data-lucide="sparkles" class="w-6 h-6"></i>
                            <span>Generate RPP Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- AI Generation Result Section -->
        <div id="resultSection" class="space-y-6">
            <div class="flex items-center gap-4">
                <div class="h-[1px] flex-1 bg-slate-200"></div>
                <h3 class="text-lg font-bold text-slate-400 uppercase tracking-widest">Hasil Generasi AI</h3>
                <div class="h-[1px] flex-1 bg-slate-200"></div>
            </div>

            <div id="placeholderResult"
                class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6">
                    <i data-lucide="file-text" class="w-10 h-10"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-400 mb-2">Belum Ada Hasil</h4>
                <p class="text-slate-400 max-w-sm">Isi formulir di atas dan klik tombol generate untuk mulai membuat
                    RPP/Modul Ajar Anda.</p>
            </div>

            <!-- Loading State -->
            <div id="loadingResult"
                class="hidden bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/60 p-12 text-center min-h-[300px] flex flex-col items-center justify-center">
                <div class="relative w-20 h-20 mb-6">
                    <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-primary border-t-transparent animate-spin">
                    </div>
                </div>
                <h4 class="text-xl font-bold text-slate-600 mb-2">Sedang Menghasilkan RPP...</h4>
                <p class="text-slate-400 max-w-sm">Mohon tunggu, AI sedang menyusun rencana pembelajaran Anda.</p>
            </div>

            <div id="rppActions" class="hidden flex flex-wrap gap-3 mb-4 justify-end">
                <button id="downloadPdfBtn"
                    class="flex items-center gap-2 px-5 py-2.5 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition-all">
                    <i data-lucide="file-down" class="w-5 h-5"></i>
                    <span>Unduh PDF</span>
                </button>
                <button id="downloadWordBtn"
                    class="flex items-center gap-2 px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    <span>Unduh Word</span>
                </button>
                <button id="copyBtn"
                    class="flex items-center gap-2 px-5 py-2.5 bg-slate-600 hover:bg-slate-700 text-white font-semibold rounded-xl transition-all">
                    <i data-lucide="copy" class="w-5 h-5"></i>
                    <span>Salin Teks</span>
                </button>
            </div>

            <div id="rppOutput"
                class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 p-8 lg:p-12 hidden">
                <!-- Results will be injected here -->
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Premium Select2 Styling */
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important;
            /* slate-50 */
            border: none !important;
            border-radius: 1rem !important;
            /* rounded-2xl */
            height: auto !important;
            padding: 0.75rem 1.25rem !important;
            transition: all 0.2s;
            font-weight: 500;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #475569 !important;
            line-height: 1.5 !important;
            padding-left: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 1rem !important;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
        }

        .select2-dropdown {
            border-radius: 1rem !important;
            border: 1px solid #f1f5f9 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden;
            margin-top: 5px;
            padding: 5px;
        }

        .select2-results__option {
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            margin-bottom: 2px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #6366f1 !important;
        }

        /* RPP Document Styling - Elegant & Print Ready */
        #rppOutput {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            line-height: 1.8;
            color: #1e293b;
            background: white;
            padding: 3rem;
            max-width: 210mm;
            /* A4 width */
            margin: 0 auto;
        }

        /* Headers */
        #rppOutput h1 {
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

        #rppOutput h2 {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 1.5rem 0 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        #rppOutput h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: #334155;
            margin: 1.5rem 0 0.75rem;
            padding-left: 0.5rem;
            border-left: 4px solid #6366f1;
        }

        #rppOutput h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #475569;
            margin: 1rem 0 0.5rem;
        }

        /* Paragraphs and Text */
        #rppOutput p {
            margin-bottom: 1rem;
            text-align: justify;
            color: #334155;
        }

        /* Lists */
        #rppOutput ul,
        #rppOutput ol {
            margin: 0.75rem 0 1rem 1.5rem;
            color: #334155;
        }

        #rppOutput ul {
            list-style-type: disc;
        }

        #rppOutput ol {
            list-style-type: decimal;
        }

        #rppOutput li {
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }

        #rppOutput li::marker {
            color: #6366f1;
            font-weight: 600;
        }

        /* Tables - Clean Professional Look */
        #rppOutput table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 0.95rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
            overflow: hidden;
        }

        #rppOutput thead {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
        }

        #rppOutput th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }

        #rppOutput td {
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: top;
        }

        #rppOutput tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        #rppOutput tbody tr:hover {
            background-color: #f1f5f9;
        }

        #rppOutput tbody tr:last-child td {
            border-bottom: none;
        }

        /* Horizontal Rules */
        #rppOutput hr {
            border: none;
            height: 1px;
            background: linear-gradient(to right, transparent, #cbd5e1, transparent);
            margin: 2rem 0;
        }

        /* Strong and Emphasis */
        #rppOutput strong {
            font-weight: 700;
            color: #1e293b;
        }

        #rppOutput em {
            font-style: italic;
            color: #475569;
        }

        /* Blockquotes */
        #rppOutput blockquote {
            border-left: 4px solid #6366f1;
            background: #f8fafc;
            padding: 1rem 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #475569;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        /* Code blocks for special content */
        #rppOutput code {
            background: #f1f5f9;
            padding: 0.2rem 0.4rem;
            border-radius: 0.25rem;
            font-size: 0.9em;
            color: #6366f1;
        }

        /* Print Styles */
        @media print {
            #rppOutput {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
            }

            #rppOutput h1 {
                font-size: 14pt;
                margin-top: 0;
            }

            #rppOutput h2 {
                font-size: 12pt;
                page-break-after: avoid;
            }

            #rppOutput h3 {
                font-size: 11pt;
                page-break-after: avoid;
            }

            #rppOutput table {
                page-break-inside: avoid;
                font-size: 10pt;
            }

            #rppOutput thead {
                background: #334155 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            #rppOutput tbody tr:nth-child(even) {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://unpkg.com/html-docx-js/dist/html-docx.js"></script>
    <script>
        $(document).ready(function() {
            $('.search-select').select2({
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

            // Handle Pendekatan Selection
            $('#pendekatan').on('change', function() {
                if ($(this).val() === '__other__') {
                    $('#manual_pendekatan_container').removeClass('hidden');
                    $('#pendekatan_manual').prop('required', true).focus();
                } else {
                    $('#manual_pendekatan_container').addClass('hidden');
                    $('#pendekatan_manual').prop('required', false);
                }
            });

            // AJAX Generate RPP
            const $form = $('#rppForm');
            const $btn = $('#generateBtn');
            const $resultSection = $('#resultSection');
            const $placeholder = $('#placeholderResult');
            const $loading = $('#loadingResult');
            const $rppOutput = $('#rppOutput');
            const $rppActions = $('#rppActions');

            $form.on('submit', function(e) {
                e.preventDefault();

                // Show loading, hide others
                $placeholder.addClass('hidden');
                $rppOutput.addClass('hidden');
                $rppActions.addClass('hidden');
                $loading.removeClass('hidden');

                // Update button
                $btn.prop('disabled', true).addClass('opacity-75');
                $btn.find('span').text('Sedang Menghasilkan...');
                $btn.find('i').addClass('animate-spin');

                // Scroll to loading


                let formData = $(this).serialize();
                if ($('#mapel').val() === '__other__') {
                    const manualVal = $('#mapel_manual').val();
                    formData = formData.replace(/mapel=[^&]*/, 'mapel=' + encodeURIComponent(manualVal));
                }

                if ($('#pendekatan').val() === '__other__') {
                    const manualPendekatan = $('#pendekatan_manual').val();
                    formData = formData.replace(/pendekatan=[^&]*/, 'pendekatan=' + encodeURIComponent(
                        manualPendekatan));
                }

                $.ajax({
                    url: "{{ route('admin.rpp.generate') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.result) {
                            // Render Markdown
                            const html = marked.parse(response.result);
                            $rppOutput.html(html);

                            // Switch UI
                            $loading.addClass('hidden');
                            $rppOutput.removeClass('hidden');
                            $rppActions.removeClass('hidden');

                            // Re-init Lucide icons for new buttons
                            if (typeof lucide !== 'undefined') {
                                lucide.createIcons();
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'RPP Berhasil Dibuat!',
                                text: 'Hasil telah tampil di bawah.',
                                confirmButtonColor: '#6366f1'
                            });
                        }
                    },
                    error: function(xhr) {
                        // Hide loading, show placeholder
                        $loading.addClass('hidden');
                        $placeholder.removeClass('hidden');

                        let errorMsg = 'Terjadi kesalahan sistem.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMsg,
                            confirmButtonColor: '#ef4444'
                        });
                    },
                    complete: function() {
                        $btn.prop('disabled', false).removeClass('opacity-75');
                        $btn.find('span').text('Generate RPP Sekarang');
                        $btn.find('i').removeClass('animate-spin');
                    }
                });
            });

            // Download PDF
            $('#downloadPdfBtn').on('click', function() {
                const element = document.getElementById('rppOutput');
                const opt = {
                    margin: [10, 10, 10, 10],
                    filename: 'RPP-ModulAjar.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    }
                };

                Swal.fire({
                    title: 'Menyiapkan PDF...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                html2pdf().set(opt).from(element).save().then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'File PDF berhasil diunduh.',
                        confirmButtonColor: '#6366f1',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            });

            // Download Word
            $('#downloadWordBtn').on('click', function() {
                Swal.fire({
                    title: 'Menyiapkan Word...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const content = document.getElementById('rppOutput').innerHTML;
                const htmlContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <style>
                        body { font-family: 'Calibri', sans-serif; font-size: 12pt; line-height: 1.5; }
                        h1 { text-align: center; font-size: 14pt; font-weight: bold; }
                        h2 { font-size: 13pt; font-weight: bold; border-bottom: 1px solid #000; }
                        h3 { font-size: 12pt; font-weight: bold; }
                        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                        th { background-color: #f0f0f0; font-weight: bold; }
                        ul, ol { margin-left: 20px; }
                    </style>
                </head>
                <body>${content}</body>
                </html>
            `;

                const converted = htmlDocx.asBlob(htmlContent);
                const link = document.createElement('a');
                link.href = URL.createObjectURL(converted);
                link.download = 'RPP-ModulAjar.docx';
                link.click();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'File Word berhasil diunduh.',
                    confirmButtonColor: '#6366f1',
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            // Copy to clipboard functionality
            $('#copyBtn').on('click', function() {
                const text = $('#rppOutput').text();
                navigator.clipboard.writeText(text).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Tersalin!',
                        text: 'Konten RPP berhasil disalin ke clipboard.',
                        confirmButtonColor: '#6366f1',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            });
        });
    </script>
@endpush
