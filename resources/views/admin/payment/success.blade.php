@extends('layouts.admin')

@section('header')
    Pembayaran Berhasil
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-16 text-center">
    <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-emerald-100">
        <i data-lucide="check-circle-2" class="w-12 h-12"></i>
    </div>
    
    <h1 class="text-4xl font-black text-dark mb-4">Terima Kasih!</h1>
    <p class="text-xl text-slate-500 mb-12 max-w-2xl mx-auto">
        Pembayaran Anda telah kami terima. Akun Anda kini telah aktif dengan paket terbaru. Silakan nikmati seluruh fitur Guru Masterpiece AI.
    </p>

    <div class="flex flex-wrap justify-center gap-4">
        @auth
            <a href="{{ route('dashboard') }}" class="px-10 py-5 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/30 hover:shadow-xl transition-all flex items-center gap-2">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="px-10 py-5 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/30 hover:shadow-xl transition-all flex items-center gap-2">
                <i data-lucide="log-in" class="w-5 h-5"></i>
                Masuk ke Akun Saya
            </a>
        @endauth
        <a href="{{ route('admin.video-tutorial') }}" class="px-10 py-5 bg-slate-100 text-slate-600 font-black rounded-2xl hover:bg-slate-200 transition-all flex items-center gap-2">
            <i data-lucide="play-circle" class="w-5 h-5"></i>
            Lihat Tutorial
        </a>
    </div>
</div>
@endsection
