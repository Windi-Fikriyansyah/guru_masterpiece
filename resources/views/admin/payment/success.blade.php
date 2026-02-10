@extends('layouts.admin')

@section('header')
    Pembayaran Berhasil
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-16 text-center">
    @if($transaction->status === 'PAID')
        <div class="w-24 h-24 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-emerald-100">
            <i data-lucide="check-circle-2" class="w-12 h-12"></i>
        </div>
        <h1 class="text-4xl font-black text-dark mb-4">Terima Kasih!</h1>
        <p class="text-xl text-slate-500 mb-12 max-w-2xl mx-auto">
            Pembayaran Anda telah kami terima. Akun Anda kini telah aktif dengan paket *{{ strtoupper($transaction->package) }}*. Silakan nikmati seluruh fitur Guru Masterpiece AI.
        </p>
    @elseif($transaction->status === 'UNPAID')
        <div class="w-24 h-24 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-yellow-100 animate-pulse">
            <i data-lucide="clock" class="w-12 h-12"></i>
        </div>
        <h1 class="text-4xl font-black text-dark mb-4">Menunggu Pembayaran</h1>
        <p class="text-xl text-slate-500 mb-6 max-w-2xl mx-auto">
            Kami sedang menunggu konfirmasi pembayaran untuk transaksi <b>{{ $transaction->reference }}</b>. 
        </p>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6 mb-12 max-w-lg mx-auto">
            <p class="text-sm text-blue-800 leading-relaxed">
                Jika Anda sudah melakukan pembayaran, silakan tunggu beberapa saat karena sistem sedang melakukan verifikasi otomatis. Halaman ini akan diperbarui secara otomatis.
            </p>
        </div>
        <script>
            setTimeout(function() {
                window.location.reload();
            }, 10000); // Reload every 10 seconds to check status
        </script>
    @else
        <div class="w-24 h-24 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-8 shadow-lg shadow-red-100">
            <i data-lucide="x-circle" class="w-12 h-12"></i>
        </div>
        <h1 class="text-4xl font-black text-dark mb-4">Pembayaran {{ $transaction->status === 'EXPIRED' ? 'Kedaluwarsa' : 'Gagal' }}</h1>
        <p class="text-xl text-slate-500 mb-12 max-w-2xl mx-auto">
            Mohon maaf, transaksi Anda dengan referensi <b>{{ $transaction->reference }}</b> telah {{ strtolower($transaction->status) }}. Silakan coba lakukan pemesanan ulang.
        </p>
    @endif

    <div class="flex flex-wrap justify-center gap-4">
        @if($transaction->status === 'PAID')
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
        @elseif($transaction->status === 'UNPAID')
            <a href="{{ $transaction->checkout_url }}" target="_blank" class="px-10 py-5 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/30 hover:shadow-xl transition-all flex items-center gap-2">
                <i data-lucide="external-link" class="w-5 h-5"></i>
                Lanjut Pembayaran
            </a>
            <button onclick="window.location.reload()" class="px-10 py-5 bg-emerald-500 text-white font-black rounded-2xl shadow-lg shadow-emerald-500/30 hover:shadow-xl transition-all flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                Cek Status Sekarang
            </button>
        @else
            <a href="{{ route('paket-masterpiece') }}" class="px-10 py-5 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/30 hover:shadow-xl transition-all flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                Pilih Paket Lagi
            </a>
        @endif
        
        <a href="{{ route('admin.video-tutorial') }}" class="px-10 py-5 bg-slate-100 text-slate-600 font-black rounded-2xl hover:bg-slate-200 transition-all flex items-center gap-2">
            <i data-lucide="play-circle" class="w-5 h-5"></i>
            Lihat Tutorial
        </a>
    </div>
</div>
@endsection
