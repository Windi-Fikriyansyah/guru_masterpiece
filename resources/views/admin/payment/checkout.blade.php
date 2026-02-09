@extends('layouts.admin')

@section('header')
    Checkout Pembayaran
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Detail Paket -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24">
                <h3 class="text-xl font-bold text-dark mb-6">Ringkasan Pesanan</h3>
                
                <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">PAKET YANG DIPILIH</p>
                    <h4 class="text-2xl font-black text-primary mb-1">{{ $selectedPackage['name'] }}</h4>
                    <p class="text-slate-500 text-sm">Akses fitur Guru Masterpiece AI</p>
                </div>

                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Harga Paket</span>
                        <span class="font-bold text-dark">Rp {{ number_format($selectedPackage['amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-slate-500">PPN (0%)</span>
                        <span class="font-bold text-dark">Rp 0</span>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-lg font-bold text-dark">Total Bayar</span>
                        <span class="text-2xl font-black text-primary">Rp {{ number_format($selectedPackage['amount'], 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-xl p-4 flex gap-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-blue-600 flex-shrink-0"></i>
                    <p class="text-xs text-blue-700 leading-relaxed">
                        Pembayaran aman dan terverifikasi secara otomatis melalui system kami.
                    </p>
                </div>
            </div>
        </div>

        <!-- Pilih Metode Pembayaran -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] p-8 lg:p-12 shadow-xl shadow-slate-200/50 border border-slate-100">
                <h3 class="text-2xl font-black text-dark mb-8">Pilih Metode Pembayaran</h3>

                @if(session('error'))
                    <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-8 border border-red-100 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="package" value="{{ $package }}">
                    
                    @if(!Auth::check())
                    <div class="mb-12">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Informasi Akun</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-dark ml-1">Nama Lengkap</label>
                                <input type="text" name="name" required placeholder="Masukkan nama Anda" 
                                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-primary focus:bg-white outline-none transition-all text-sm font-medium">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-dark ml-1">Email Aktif</label>
                                <input type="email" name="email" required placeholder="nama@email.com" 
                                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-primary focus:bg-white outline-none transition-all text-sm font-medium">
                            </div>
                            <div class="space-y-2 sm:col-span-2">
                                <label class="text-sm font-bold text-dark ml-1">No. WhatsApp (08xxx / 62xxx)</label>
                                <input type="text" name="whatsapp" required placeholder="Contoh: 08123456789" 
                                    class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:border-primary focus:bg-white outline-none transition-all text-sm font-medium">
                            </div>
                        </div>
                        <p class="mt-3 text-[11px] text-slate-400 flex items-center gap-1.5 ml-1">
                            <i data-lucide="info" class="w-3.5 h-3.5"></i>
                            Gunakan email & WhatsApp aktif. Kami akan mengirimkan detail login Anda ke WhatsApp setelah pembayaran berhasil.
                        </p>
                    </div>
                    @endif
                    
                    <div class="space-y-8">
                        @php
                            $groups = [
                                'Virtual Account' => ['VA'],
                                'E-Wallet' => ['EWALLET'],
                                'Convenience Store' => ['CS'],
                            ];
                        @endphp

                        @foreach($groups as $label => $types)
                            @php
                                $filteredChannels = array_filter($channels, function($channel) use ($types) {
                                    return in_array($channel['group'], $types) && $channel['active'];
                                });
                            @endphp

                            @if(count($filteredChannels) > 0)
                                <div>
                                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">{{ $label }}</h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($filteredChannels as $channel)
                                            <label class="relative flex items-center p-4 border-2 border-slate-100 rounded-2xl cursor-pointer hover:border-primary/50 transition-all group has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                                                <input type="radio" name="method" value="{{ $channel['code'] }}" class="hidden peer" required>
                                                <div class="flex items-center gap-4 w-full">
                                                    <div class="w-12 h-8 flex items-center justify-center">
                                                        <img src="{{ $channel['icon_url'] }}" alt="{{ $channel['name'] }}" class="max-w-full max-h-full object-contain grayscale group-hover:grayscale-0 peer-checked:grayscale-0">
                                                    </div>
                                                    <div class="flex-1">
                                                        <p class="font-bold text-dark text-sm">{{ $channel['name'] }}</p>
                                                        <p class="text-[10px] text-slate-400 font-medium">Proses Instan</p>
                                                    </div>
                                                    <div class="w-5 h-5 border-2 border-slate-200 rounded-full flex items-center justify-center peer-checked:border-primary">
                                                        <div class="w-2.5 h-2.5 bg-primary rounded-full scale-0 peer-checked:scale-100 transition-transform"></div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <button type="submit" class="w-full mt-12 py-5 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/30 hover:shadow-xl hover:bg-secondary transition-all flex items-center justify-center gap-3 text-lg">
                        <i data-lucide="credit-card" class="w-6 h-6"></i>
                        BAYAR SEKARANG
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
