@extends('layouts.admin')

@section('header')
    Dashboard Overview
@endsection

@section('content')


    <div class="space-y-8">
        <!-- Hero Section -->
        <div class="bg-white rounded-[2.5rem] p-10 lg:p-16 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
            <div class="relative z-10 max-w-4xl">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-bold mb-6">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                    AI-Powered Teaching Assistant
                </div>
                <h1 class="text-4xl lg:text-6xl font-black text-slate-800 mb-6 tracking-tight leading-tight">
                    GURU <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">MASTERPIECE</span>
                </h1>
                <p class="text-xl lg:text-2xl font-bold text-slate-600 mb-8 leading-relaxed">
                    Asisten AI Cerdas untuk Menyusun Perangkat Pembelajaran Guru dengan Cepat, Rapi, dan Siap Digunakan
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.rpp') }}" class="px-8 py-4 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:shadow-xl hover:shadow-primary/40 transform hover:-translate-y-1 transition-all flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        Mulai Buat Perangkat
                    </a>
                    <a href="#features" class="px-8 py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-all flex items-center gap-2">
                        Pelajari Selengkapnya
                    </a>
                </div>
            </div>
            
            <!-- Decorative Elements -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl"></div>
            <div class="absolute right-10 bottom-10 opacity-10 blur-sm pointer-events-none hidden lg:block">
                <i data-lucide="graduation-cap" class="w-64 h-64 text-primary"></i>
            </div>
        </div>

        <!-- Content Sections -->
        <div id="features" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-[2rem] p-8 shadow-lg shadow-slate-200/40 border border-slate-50">
                <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="layout-template" class="w-7 h-7"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4">Efisiensi & Struktur</h3>
                <p class="text-slate-600 leading-relaxed text-lg">
                    Guru Masterpiece adalah aplikasi berbasis AI yang membantu guru menyusun perangkat pembelajaran secara cepat, rapi, dan terstruktur. Mulai dari Modul Ajar/RPP, LKPD/LKM, materi ajar, materi presentasi, soal, rubrik penilaian, hingga catatan evaluasi guru, semuanya dirancang selaras dengan kurikulum, pendekatan deep learning, dan kebutuhan peserta didik.
                </p>
                <div class="mt-6 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                    <p class="text-emerald-700 font-medium italic">"Dengan Guru Masterpiece, guru lebih hemat waktu, pembelajaran pun menjadi lebih bermakna."</p>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-lg shadow-slate-200/40 border border-slate-50">
                <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-6">
                    <i data-lucide="map" class="w-7 h-7"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-4">Panduan Langkah Demi Langkah</h3>
                <p class="text-slate-600 leading-relaxed text-lg mb-6">
                    Anda tidak perlu bingung memulai dari mana. Guru Masterpiece memandu guru sejak tahap perencanaan pembelajaran, penentuan tujuan dan materi, hingga penyusunan perangkat ajar secara lengkap dan terstruktur. Semuanya disusun selangkah demi selangkah.
                </p>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-slate-700 font-medium">
                        <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs">1</div>
                        Perencanaan & Tujuan
                    </div>
                    <div class="flex items-center gap-3 text-slate-700 font-medium">
                        <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs">2</div>
                        Penyusunan Konten Materi
                    </div>
                    <div class="flex items-center gap-3 text-slate-700 font-medium">
                        <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs">3</div>
                        Generasi Perangkat Lengkap
                    </div>
                </div>
            </div>
        </div>

        <!-- Integrated Dashboard Card -->
        <div class="bg-gradient-to-br from-indigo-600 to-violet-800 rounded-[2rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-indigo-200">
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2">
                    <h3 class="text-2xl lg:text-3xl font-bold mb-4">Satu Dashboard, Semua Kebutuhan</h3>
                    <p class="text-indigo-100 text-lg leading-relaxed opacity-90">
                        Seluruh proses dilakukan dalam satu dashboard terpadu, dilengkapi pengelolaan materi dan aktivitas pembelajaran, dan dukungan AI cerdas untuk menghasilkan perangkat pembelajaran yang profesional, siap digunakan, dan selaras dengan kurikulum.
                    </p>
                </div>
                <div class="flex justify-center lg:justify-end">
                    <div class="w-48 h-48 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-md border border-white/20">
                        <i data-lucide="panels-top-left" class="w-24 h-24 text-white opacity-80"></i>
                    </div>
                </div>
            </div>
            
            <!-- Abstract background shape -->
            <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 -top-10 w-48 h-48 bg-indigo-400/20 rounded-full blur-3xl"></div>
        </div>
    </div>
@endsection
