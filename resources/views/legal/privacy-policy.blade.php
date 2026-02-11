@extends('layouts.admin')

@section('header')
    Kebijakan Privasi
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-xl shadow-slate-200/50 border border-slate-100 font-sans">
        <h1 class="text-3xl font-black text-dark mb-6">Kebijakan Privasi Guru Masterpiece</h1>
        <p class="text-slate-500 mb-8 font-medium">Terakhir Diperbarui: {{ date('d F Y') }}</p>

        <div class="space-y-8">
            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">1</span>
                    Informasi yang Kami Kumpulkan
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed mb-3">Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami saat Anda:</p>
                    <ul class="list-disc space-y-2 text-slate-600">
                        <li>Mendaftar akun di platform kami.</li>
                        <li>Menggunakan layanan generator konten AI kami.</li>
                        <li>Melakukan transaksi pembayaran paket.</li>
                        <li>Menghubungi layanan pelanggan kami.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">2</span>
                    Penggunaan Informasi
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed mb-3">Kami menggunakan informasi yang dikumpulkan untuk:</p>
                    <ul class="list-disc space-y-2 text-slate-600">
                        <li>Menyediakan, memelihara, dan meningkatkan layanan kami.</li>
                        <li>Memproses transaksi dan mengirimkan konfirmasi pembayaran.</li>
                        <li>Mengirimkan pemberitahuan administratif dan dukungan teknis.</li>
                        <li>Mencegah aktivitas penipuan atau penyalahgunaan platform.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">3</span>
                    Keamanan Data
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed">Kami menerapkan langkah-langkah keamanan teknis dan organisasional yang wajar untuk melindungi informasi pribadi Anda dari akses yang tidak sah, pengungkapan, perubahan, atau penghancuran. Data Anda disimpan dalam server yang aman dengan enkripsi standar industri.</p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">4</span>
                    Berbagi Informasi
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed">Kami tidak menjual informasi pribadi Anda kepada pihak ketiga. Kami hanya membagikan informasi Anda dengan penyedia layanan pihak ketiga (seperti gerbang pembayaran TriPay) yang diperlukan untuk menjalankan operasional layanan kami secara terintegrasi.</p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">5</span>
                    Hak Anda
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed">Anda memiliki hak untuk mengakses, memperbarui, atau menghapus informasi pribadi Anda yang kami simpan melalui pengaturan profil di platform kami kapan saja.</p>
                </div>
            </section>

            <div class="pt-8 border-t border-slate-100">
                <p class="text-slate-500 text-sm text-center italic text-balance">
                    Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami melalui saluran dukungan WhatsApp yang tersedia.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
