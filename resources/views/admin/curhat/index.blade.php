@extends('layouts.admin')

@section('header', 'Refleksi Guru')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
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

    #resultContent {
        font-family: 'Inter', sans-serif;
        line-height: 1.8;
        color: #1e293b;
        background: white;
        padding: 2.5rem;
        margin: 0 auto;
    }

    #resultContent p {
        margin-bottom: 1.25rem;
    }

    #resultContent strong {
        color: #4f46e5;
    }

    #resultContent ul, #resultContent ol {
        margin: 1rem 0 1.5rem 1.5rem;
    }

    #resultContent li {
        margin-bottom: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    
    <!-- Header -->
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-slate-800 mb-2">Refleksi Pembelajaran Guru</h2>
        <p class="text-slate-500 text-lg">Catat pengalaman, tantangan, dan evaluasi untuk pembelajaran yang terus berkembang</p>
    </div>

    <!-- Input Form Card -->
    <div class="bg-white rounded-[2rem] p-8 lg:p-10 shadow-xl shadow-slate-200/60 border border-slate-100">
        <div class="flex items-center gap-3 mb-8 border-b border-slate-100 pb-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                <i data-lucide="heart" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Ruang Refleksi</h3>
                <p class="text-sm text-slate-400">Kami siap mendengarkan pengalaman dan cerita Anda hari ini.</p>
            </div>
        </div>
        
        <form id="curhatForm" class="space-y-6">
            @csrf
            
            <!-- Cerita -->
            <div class="space-y-2">
                <label for="cerita" class="block text-sm font-bold text-slate-700 ml-1">Apa yang ingin Anda ceritakan hari ini?</label>
                <textarea name="cerita" id="cerita" rows="5" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-5 py-4 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700 placeholder:text-slate-400" placeholder="Tuliskan refleksi atau tantangan Anda dengan jujur dan bebas. Contoh: Beberapa murid cepat memahami materi, sementara yang lain tertinggal. Saya butuh cara menerapkan diferensiasi yang lebih efektif." required></textarea>
            </div>

            <!-- Gaya Respon -->
            <div class="space-y-2">
    <label for="gaya_respon" class="block text-sm font-bold text-slate-700 ml-1">
        Ingin Respon Seperti Apa?
    </label>
    <p class="text-xs text-slate-500 ml-1">
        Pilih gaya respon yang paling Anda butuhkan saat ini
    </p>

    <select name="gaya_respon" id="gaya_respon" 
        class="w-full select2-enable"
        data-placeholder="Pilih gaya respon..."
        required>
        
        <option value=""></option> <!-- placeholder untuk Select2 -->
        <option value="Solutif & Praktis">Solutif & Praktis</option>
        <option value="Empatik & Menenangkan">Empatik & Menenangkan</option>
        <option value="Reflektif & Mendalam">Reflektif & Mendalam</option>
        <option value="Inspiratif & Memotivasi">Inspiratif & Memotivasi</option>
        <option value="Ringkas & Langsung ke Inti">Ringkas & Langsung ke Inti</option>
        <option value="Terstruktur & Sistematis">Terstruktur & Sistematis</option>
        <option value="Humor & Menghibur">Humor & Menghibur</option>
    </select>
</div>


            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit" id="generateBtn" class="w-full bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-600/30 hover:shadow-xl hover:shadow-indigo-600/40 transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2 text-lg">
                    <i data-lucide="message-square" class="w-6 h-6"></i>
                    Dapatkan Respon
                </button>
            </div>
        </form>
    </div>

    <!-- Result Section -->
    <div id="resultSection" class="space-y-6">
        <div class="flex items-center gap-4">
            <div class="h-[1px] flex-1 bg-slate-200"></div>
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Respon Untuk Anda</h3>
            <div class="h-[1px] flex-1 bg-slate-200"></div>
        </div>

        <!-- Placeholder State -->
        <div id="placeholderResult" class="bg-white rounded-[2rem] border-2 border-dashed border-slate-200 p-12 text-center min-h-[250px] flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center text-slate-300 mb-6">
                <i data-lucide="sparkles" class="w-10 h-10"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-400 mb-2">Belum Ada Respon</h4>
            <p class="text-slate-400 max-w-sm">Tuliskan cerita Anda di atas dan kami akan memberikan respon terbaik.</p>
        </div>

        <!-- Loading State -->
        <div id="loadingResult" class="hidden bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/60 p-12 text-center min-h-[250px] flex flex-col items-center justify-center">
            <div class="relative w-20 h-20 mb-6">
                <div class="absolute inset-0 rounded-full border-4 border-indigo-50"></div>
                <div class="absolute inset-0 rounded-full border-4 border-indigo-600 border-t-transparent animate-spin"></div>
            </div>
            <h4 class="text-xl font-bold text-slate-600 mb-2">Sedang Merangkai Kata...</h4>
            <p class="text-slate-400 max-w-sm">Mohon tunggu sebentar, kami sedang menyiapkan kata-kata terbaik untuk Bapak/Ibu.</p>
        </div>

        <!-- Result Content -->
        <div id="resultContent" class="hidden bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-indigo-600"></div>
            <div id="resultText" class="p-8 lg:p-12">
                <!-- Content Injected Here -->
            </div>
            
            <div class="bg-slate-50 p-6 flex justify-end border-t border-slate-100">
                <button onclick="copyResult()" class="flex items-center gap-2 px-6 py-2.5 bg-white border border-slate-200 hover:border-indigo-600 hover:text-indigo-600 text-slate-600 font-semibold rounded-xl transition-all shadow-sm">
                    <i data-lucide="copy" class="w-5 h-5"></i>
                    <span>Salin Respon</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2-enable').select2({
            width: '100%'
        });

        // Handle Form Submission
        $('#curhatForm').on('submit', function(e) {
            e.preventDefault();
            
            const btn = $('#generateBtn');
            const originalBtnHtml = btn.html();
            
            const placeholder = $('#placeholderResult');
            const loading = $('#loadingResult');
            const content = $('#resultContent');
            const resultText = $('#resultText');
            const resultSection = $('#resultSection');

            // Set Loading UI
            btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
            placeholder.addClass('hidden');
            content.addClass('hidden');
            loading.removeClass('hidden');

            // Scroll to loading
            $('html, body').animate({
                scrollTop: resultSection.offset().top - 100
            }, 500);

            $.ajax({
                url: "{{ route('admin.curhat.generate') }}",
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.result) {
                        const htmlContent = marked.parse(response.result);
                        resultText.html(htmlContent);
                        
                        loading.addClass('hidden');
                        content.removeClass('hidden');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Selesai!',
                            text: 'Kami telah merespon cerita Anda.',
                            confirmButtonColor: '#4f46e5',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        showError('Gagal mendapatkan respon dari AI.');
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
            title: 'Maaf...',
            text: msg,
            confirmButtonColor: '#ef4444'
        });
    }

    function copyResult() {
        const text = document.getElementById('resultText').innerText;
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Disalin!',
                text: 'Respon telah disalin ke clipboard.',
                confirmButtonColor: '#4f46e5',
                timer: 1500,
                showConfirmButton: false
            });
        });
    }
</script>
@endpush
