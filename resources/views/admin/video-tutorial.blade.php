@extends('layouts.admin')

@section('header')
    Video Tutorial Guru Masterpiece
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="mb-12">
        <h2 class="text-3xl font-black text-dark mb-2">Panduan Penggunaan <span class="text-primary">Aplikasi</span></h2>
        <p class="text-slate-500 text-lg">Pelajari cara memaksimalkan fitur AI Guru Masterpiece untuk meningkatkan kualitas pengajaran Anda.</p>
    </div>

    @php
        $tutorials = [
            [
                'title' => 'Cara Menyusun Modul Ajar Secara Otomatis',
                'description' => 'Tutorial lengkap langkah demi langkah menyusun modul ajar, RPP, dan RPM menggunakan AI.',
                'youtube_id' => 'WGByn62KCqw', // Placeholder
                'category' => 'Modul Ajar',
            ],
            [
                'title' => 'Membuat Soal & Rubrik Penilaian Cepat',
                'description' => 'Bagaimana cara menghasilkan soal ujian dan rubrik penilaian yang selaras dengan materi.',
                'youtube_id' => 'Gj6B_EW2NbE', // Placeholder
                'category' => 'Penilaian',
            ],
            [
                'title' => 'Eksplorasi Fitur Refleksi Guru',
                'description' => 'Menggunakan asisten AI untuk mencatat evaluasi dan refleksi harian guru.',
                'youtube_id' => '15hw-gBjsYk', // Placeholder
                'category' => 'Refleksi',
            ],
            [
                'title' => 'Menyusun Materi Presentasi Interaktif',
                'description' => 'Cara membuat poin-poin presentasi yang menarik dan terstruktur untuk di kelas.',
                'youtube_id' => 'v113geZC97w', // Placeholder
                'category' => 'Materi Ajar',
            ],
            [
                'title' => 'Menyusun Materi Presentasi Interaktif',
                'description' => 'Cara membuat poin-poin presentasi yang menarik dan terstruktur untuk di kelas.',
                'youtube_id' => 'hgpQPX4gxrc', // Placeholder
                'category' => 'Materi Ajar',
            ],
            [
                'title' => 'Menyusun Materi Presentasi Interaktif',
                'description' => 'Cara membuat poin-poin presentasi yang menarik dan terstruktur untuk di kelas.',
                'youtube_id' => 'e8UenJxU61o', // Placeholder
                'category' => 'Materi Ajar',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($tutorials as $index => $video)
        <div class="bg-white rounded-[2rem] overflow-hidden shadow-lg shadow-slate-200/50 border border-slate-100 group transition-all duration-300 hover:shadow-2xl relative">
            <!-- Number Badge -->
            <div class="absolute top-4 left-4 z-10 w-10 h-10 bg-white/90 backdrop-blur-md text-primary rounded-xl flex items-center justify-center font-black shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                {{ $index + 1 }}
            </div>
            
            <!-- Video Container -->
            <div class="aspect-video bg-slate-100 relative">
                <iframe 
                    class="w-full h-full"
                    src="https://www.youtube.com/embed/{{ $video['youtube_id'] }}" 
                    title="{{ $video['title'] }}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
                </iframe>
            </div>
            
            <!-- Content -->
            <div class="p-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold mb-4">
                    {{ $video['category'] }}
                </div>
                <h3 class="text-xl font-bold text-dark mb-3 group-hover:text-primary transition-colors">
                    {{ $video['title'] }}
                </h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    {{ $video['description'] }}
                </p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Additional Help Section -->
    <div class="mt-16 bg-slate-50 rounded-[2.5rem] p-10 lg:p-16 border border-slate-100 text-center">
        <div class="w-16 h-16 bg-primary/10 text-primary rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i data-lucide="help-circle" class="w-8 h-8"></i>
        </div>
        <h3 class="text-2xl font-bold text-dark mb-4">Masih Butuh Bantuan?</h3>
        <p class="text-slate-500 mb-8 max-w-xl mx-auto">Jika Anda tidak menemukan tutorial yang Anda cari, jangan ragu untuk bertanya di grup komunitas atau hubungi tim support kami.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('admin.group-access') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:shadow-xl transition-all flex items-center gap-2">
                <i data-lucide="users" class="w-5 h-5"></i>
                Tanya di Grup
            </a>
        </div>
    </div>
</div>
@endsection
