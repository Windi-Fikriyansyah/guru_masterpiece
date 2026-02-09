@extends('layouts.admin')

@section('header')
    {{ Auth::user()->package === 'premium' ? 'Akses Grup Premium' : 'Akses Link Grup Eksklusif' }}
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="bg-white rounded-[2.5rem] p-10 lg:p-16 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
        <div class="relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-bold mb-8">
                <i data-lucide="users" class="w-4 h-4"></i>
                Komunitas Guru Masterpiece
            </div>

            @if(Auth::user()->package === 'premium')
                <h2 class="text-3xl lg:text-5xl font-black text-dark mb-6 tracking-tight leading-tight">
                    Selamat Datang di <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-secondary">Grup Premium!</span>
                </h2>
                <p class="text-xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Sebagai pengguna <strong>Paket Premium</strong>, Anda berhak bergabung dengan komunitas eksklusif kami di WhatsApp untuk mendapatkan dukungan prioritas, update fitur terbaru, dan berbagi inspirasi dengan para guru hebat lainnya.
                </p>

                <div class="flex flex-col items-center gap-6">
                    <a href="https://bit.ly/GM_Premium9" target="_blank" class="px-10 py-5 bg-[#25D366] text-white font-black rounded-2xl shadow-lg shadow-emerald-200 hover:shadow-xl hover:scale-105 transition-all flex items-center gap-3 text-lg">
                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                        GABUNG GRUP WHATSAPP PREMIUM
                    </a>
                    <p class="text-sm text-slate-400 font-medium">Link ini khusus untuk member Paket Premium Guru Masterpiece.</p>
                </div>
            @elseif(Auth::user()->package === 'standard')
                <h2 class="text-3xl lg:text-5xl font-black text-dark mb-6 tracking-tight leading-tight">
                    Akses <span class="text-primary">Grup Eksklusif</span>
                </h2>
                <p class="text-xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Terima kasih telah menggunakan <strong>Paket Standar</strong>. Silakan bergabung di grup komunitas kami untuk mendapatkan video tutorial dan bantuan penggunaan aplikasi.
                </p>

                <div class="flex flex-col items-center gap-6">
                    <a href="https://bit.ly/GM_Exclusive6" target="_blank" class="px-10 py-5 bg-primary text-white font-black rounded-2xl shadow-lg shadow-primary/30 hover:shadow-xl hover:scale-105 transition-all flex items-center gap-3 text-lg">
                        <i data-lucide="message-circle" class="w-6 h-6"></i>
                        GABUNG GRUP EKSKLUSIF
                    </a>
                    <p class="text-sm text-slate-400 font-medium">Dapatkan info terbaru dan support melalui grup ini.</p>
                </div>
            @else
                <h2 class="text-3xl lg:text-5xl font-black text-dark mb-6 tracking-tight leading-tight">
                    Belum Memiliki <span class="text-primary">Paket</span>
                </h2>
                <p class="text-xl text-slate-600 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Anda belum memiliki akses ke grup komunitas. Silakan pilih paket langganan untuk mendapatkan akses grup dan fitur AI lainnya.
                </p>
                <a href="{{ route('paket-masterpiece') }}" class="px-8 py-4 bg-primary text-white font-black rounded-xl hover:bg-secondary transition-all">
                    Pilih Paket Sekarang
                </a>
            @endif
        </div>

        <!-- Decorative Elements -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-secondary/5 rounded-full blur-3xl"></div>
    </div>

    @if(Auth::user()->package !== 'premium')
    <div class="mt-12 bg-gradient-to-br from-indigo-600 to-violet-800 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl">
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <h3 class="text-2xl font-bold mb-2">Ingin Akses Grup Premium?</h3>
                <p class="text-indigo-100 opacity-90">Upgrade ke Paket Premium sekarang dan dapatkan komisi affiliate 30% serta dukungan prioritas.</p>
            </div>
            <a href="{{ route('paket-masterpiece') }}" class="px-8 py-4 bg-white text-indigo-600 font-black rounded-xl hover:bg-slate-50 transition-all whitespace-nowrap">
                LIHAT PAKET PREMIUM
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
