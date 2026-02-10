@extends('layouts.admin')

@section('header')
    Referral & Penghasilan
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">SALDO REFERRAL</p>
                <h3 class="text-3xl font-black text-dark">Rp {{ number_format($user->referral_balance, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-500 mt-2">Saldo yang dapat dicairkan atau digunakan</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">TOTAL REFERRAL</p>
                <h3 class="text-3xl font-black text-dark">{{ $earnings->count() }} Orang</h3>
                <p class="text-xs text-slate-500 mt-2">Pengguna yang menggunakan kode Anda</p>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/5 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
            <div class="relative z-10">
                <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-2">KOMISI</p>
                <h3 class="text-3xl font-black text-dark">10%</h3>
                <p class="text-xs text-slate-500 mt-2">Dari setiap transaksi yang berhasil</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Referral Link Section -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-24">
                <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6">
                    <i data-lucide="share-2" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-bold text-dark mb-2">Bagikan & Dapatkan Bonus!</h3>
                <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                    Dapatkan komisi sebesar 10% untuk setiap guru yang bergabung melalui link atau kode referral Anda.
                </p>

                <div class="space-y-6">
                    <div class="space-y-2" x-data="{ copyText: '{{ $referralLink }}', clicked: false }">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">LINK REFERRAL</label>
                        <div class="flex gap-2">
                            <input type="text" readonly :value="copyText" 
                                class="flex-1 px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-medium outline-none">
                            <button @click="navigator.clipboard.writeText(copyText); clicked = true; setTimeout(() => clicked = false, 2000)"
                                class="p-3 bg-primary text-white rounded-xl hover:bg-secondary transition-all flex items-center justify-center min-w-[48px]">
                                <i data-lucide="copy" class="w-5 h-5" x-show="!clicked"></i>
                                <i data-lucide="check" class="w-5 h-5" x-show="clicked"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2" x-data="{ copyText: '{{ $user->referral_code }}', clicked: false }">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">KODE REFERRAL</label>
                        <div class="flex gap-2">
                            <input type="text" readonly :value="copyText" 
                                class="flex-1 px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-lg font-black tracking-widest outline-none text-primary">
                            <button @click="navigator.clipboard.writeText(copyText); clicked = true; setTimeout(() => clicked = false, 2000)"
                                class="p-3 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-all flex items-center justify-center min-w-[48px]">
                                <i data-lucide="copy" class="w-5 h-5" x-show="!clicked"></i>
                                <i data-lucide="check" class="w-5 h-5" x-show="clicked"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100">
                    <p class="text-[11px] text-slate-400 flex items-center gap-2 italic">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i>
                        Komisi akan otomatis masuk ke saldo Anda setelah pembayaran referral dikonfirmasi oleh admin.
                    </p>
                </div>
            </div>
        </div>

        <!-- Earnings History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-dark">Riwayat Penghasilan</h3>
                        <p class="text-sm text-slate-500 mt-1">Daftar transaksi dari referral Anda</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Waktu</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Pengguna</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Penghasilan</th>
                                <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($earnings as $earning)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-semibold text-dark">{{ $earning->created_at->format('d M Y') }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $earning->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xs font-bold uppercase transition-transform hover:scale-110">
                                                {{ substr($earning->referee->name, 0, 1) }}
                                            </div>
                                            <div class="text-sm font-bold text-dark">{{ $earning->referee->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="text-sm font-black text-emerald-600">+ Rp {{ number_format($earning->amount, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-slate-400 font-medium">(10% Komisi)</div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wider">Berhasil</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <div class="max-w-xs mx-auto">
                                            <div class="w-16 h-16 bg-slate-100 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i data-lucide="history" class="w-8 h-8"></i>
                                            </div>
                                            <p class="text-slate-500 font-bold mb-1">Belum Ada Penghasilan</p>
                                            <p class="text-xs text-slate-400">Mulailah membagikan link referral Anda untuk mendapatkan penghasilan!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
