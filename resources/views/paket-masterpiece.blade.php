@extends('layouts.admin')

@section('header')
    Paket Guru Masterpiece
@endsection

@section('content')
<div class="max-w-6xl mx-auto py-8">
    <div class="text-center mb-16">
        <h2 class="text-3xl md:text-5xl font-black text-dark mb-4">Pilih Paket <span class="text-primary">Terbaik</span> Anda</h2>
        <p class="text-slate-500 text-lg max-w-2xl mx-auto">Tingkatkan efektivitas dan kualitas pengajaran Anda dengan dukungan AI tercanggih khusus untuk guru Indonesia.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-stretch">
        <!-- PAKET STANDAR -->
        <div class="group relative bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
            <div class="mb-8">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 rounded-full text-xs font-bold mb-6">
                    PILIHAN PRAKTIS
                </div>
                <h3 class="text-3xl font-black text-dark mb-2">PAKET STANDAR</h3>
                <p class="text-slate-500 font-medium">Pilihan tepat untuk guru yang ingin mulai menggunakan fitur utama Guru Masterpiece secara praktis dan efisien.</p>
            </div>

            <div class="mb-10">
                <div class="flex items-baseline gap-1 mb-2">
                    <span class="text-2xl font-bold text-dark">Rp</span>
                    <span class="text-5xl font-black text-dark">99.000</span>
                </div>
                <p class="text-slate-400 font-medium">Bayar sekali • Akses selamanya</p>
            </div>

            <div class="space-y-6 mb-12 flex-1">
                <div class="flex items-start gap-4">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-1">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="font-bold text-dark">Fitur Utama</p>
                        <p class="text-slate-500 text-sm">Akses penuh Guru Masterpiece AI. Menyusun Modul Ajar / RPP / RPM dengan cepat, rapi, dan terstruktur.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0 mt-1">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="font-bold text-dark">Support Standar</p>
                        <p class="text-slate-500 text-sm">Bantuan penggunaan aplikasi saat dibutuhkan.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">BONUS EKSKLUSIF</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-dark font-semibold">
                            <span class="text-xl">🎁</span>
                            <span>Video Tutorial Guru Masterpiece</span>
                        </div>
                        <div class="flex items-center gap-3 text-dark font-semibold">
                            <span class="text-xl">🎁</span>
                            <span>Akses Link Grup Eksklusif</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-6 mb-8 border border-slate-100">
                <p class="font-bold text-dark mb-3">Kenapa Paket Standar?</p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-primary"></i>
                        <span>Hemat biaya, manfaat maksimal</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-primary"></i>
                        <span>Cocok untuk guru pemula maupun berpengalaman</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-primary"></i>
                        <span>Sekali bayar, digunakan tanpa batas waktu</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('payment.checkout', 'standard') }}" class="w-full py-5 bg-white text-dark border-2 border-slate-200 font-black rounded-2xl text-center transition-all hover:bg-slate-50 hover:border-dark">
                PILIH PAKET STANDAR
            </a>
        </div>

        <!-- PAKET PREMIUM -->
        <div class="group relative bg-dark rounded-[2.5rem] p-8 lg:p-12 shadow-2xl shadow-primary/20 flex flex-col transition-all duration-300 hover:shadow-primary/30 hover:-translate-y-2 border border-primary/20">
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-6 py-2 bg-gradient-to-r from-primary to-accent text-white rounded-full text-sm font-black shadow-lg shadow-primary/50">
                PALING POPULER
            </div>

            <div class="mb-8 pt-4">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/20 text-primary rounded-full text-xs font-bold mb-6">
                    FITUR TERLENGKAP
                </div>
                <h3 class="text-3xl font-black text-white mb-2">PAKET PREMIUM</h3>
                <p class="text-slate-400 font-medium">Akses Selamanya • Fitur Terlengkap • Prioritas Utama</p>
            </div>

            <div class="mb-10">
                <div class="flex items-baseline gap-1 mb-2 text-white">
                    <span class="text-2xl font-bold">Rp</span>
                    <span class="text-5xl font-black">150.000</span>
                </div>
                <p class="text-slate-400 font-medium">Sekali bayar • Akses seumur hidup</p>
            </div>

            <div class="space-y-6 mb-12 flex-1 text-slate-300">
                <div class="flex items-start gap-4">
                    <div class="w-6 h-6 rounded-full bg-primary/20 text-primary flex items-center justify-center flex-shrink-0 mt-1">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white">Fitur Lengkap Tanpa Batas</p>
                        <p class="text-sm">Akses penuh seumur hidup: Modul Ajar, LKPD, Materi Ajar, Presentasi, Soal Otomatis, Rubrik Penilaian, & Evaluasi Guru.</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-6 h-6 rounded-full bg-primary/20 text-primary flex items-center justify-center flex-shrink-0 mt-1">
                        <i data-lucide="check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white">Priority Support</p>
                        <p class="text-sm">Bantuan prioritas penggunaan aplikasi saat dibutuhkan.</p>
                    </div>
                </div>

                <div class="pt-6 border-t border-white/10">
                    <p class="text-xs font-bold text-primary uppercase tracking-widest mb-4">BONUS PREMIUM (GRATIS)</p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-white font-semibold">
                            <span class="text-xl">🎁</span>
                            <span>Video Tutorial Guru Masterpiece</span>
                        </div>
                        <div class="flex items-center gap-3 text-white font-semibold">
                            <span class="text-xl">🎁</span>
                            <span>Akses Grup Premium (WhatsApp)</span>
                        </div>
                        <!-- <div class="flex items-center gap-3 text-white font-semibold">
                            <span class="text-xl">🎁</span>
                            <div class="flex flex-col">
                                <span>Program Affiliate Gratis</span>
                                <span class="text-xs text-primary font-bold">Komisi 30% per produk terjual</span>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>

            <div class="bg-white/5 rounded-2xl p-6 mb-8 border border-white/10">
                <p class="font-bold text-white mb-3 text-sm">Kenapa Paket Premium?</p>
                <div class="grid grid-cols-1 gap-2">
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-primary"></i>
                        <span>Semua fitur tanpa batas</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-primary"></i>
                        <span>Sekali bayar, manfaat jangka panjang</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <i data-lucide="check-circle-2" class="w-4 h-4 text-primary"></i>
                        <span>Dukungan prioritas & peluang penghasilan</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('payment.checkout', 'premium') }}" class="w-full py-5 bg-primary text-white font-black rounded-2xl text-center transition-all hover:bg-secondary shadow-lg shadow-primary/30">
                PILIH PAKET PREMIUM
            </a>
        </div>
    </div>
</div>
@endsection
