@extends('layouts.admin')

@section('header')
    Ketentuan Layanan
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-xl shadow-slate-200/50 border border-slate-100 font-sans">
        <h1 class="text-3xl font-black text-dark mb-6">Ketentuan Layanan Guru Masterpiece</h1>
        <p class="text-slate-500 mb-8 font-medium">Terakhir Diperbarui: {{ date('d F Y') }}</p>

        <div class="space-y-8">
            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">1</span>
                    Penerimaan Ketentuan
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed">Dengan mengakses dan menggunakan platform Guru Masterpiece, Anda setuju untuk terikat oleh Ketentuan Layanan ini. Jika Anda tidak setuju dengan bagian mana pun dari ketentuan ini, Anda tidak diperkenankan menggunakan layanan kami.</p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">2</span>
                    Deskripsi Layanan
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed">Guru Masterpiece adalah platform berbasis kecerdasan buatan (AI) yang dirancang untuk membantu pendidik di Indonesia dalam pembuatan konten pembelajaran seperti MODUL AJAR (RPP), LKPD, Materi Ajar, Media Presentasi, dan instrumen penilaian lainnya.</p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">3</span>
                    Pendaftaran & Keamanan Akun
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed">Anda bertanggung jawab penuh untuk menjaga kerahasiaan informasi akun dan kata sandi Anda. Anda menyetujui untuk segera memberi tahu kami jika ada penggunaan akun Anda yang tidak sah atau pelanggaran keamanan lainnya.</p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">4</span>
                    Cakupan Paket & Pembayaran
                </h2>
                <div class="pl-10">
                    <ul class="list-disc space-y-2 text-slate-600">
                        <li>Layanan tertentu hanya tersedia melalui pembelian paket langganan (Standard atau Premium).</li>
                        <li>Pembayaran bersifat "One-Time Payment" untuk akses selamanya sesuai dengan ketentuan paket yang dipilih.</li>
                        <li>Semua biaya yang dibayarkan tidak dapat dikembalikan (non-refundable) kecuali ditentukan lain secara hukum atau kebijakan khusus kami.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">5</span>
                    Batasan Penggunaan
                </h2>
                <div class="pl-10 text-slate-600">
                    <p class="mb-3">Anda setuju untuk tidak:</p>
                    <ul class="list-disc space-y-2">
                        <li>Menggunakan platform untuk aktivitas ilegal atau penyebaran konten berbahaya.</li>
                        <li>Melakukan tindakan yang merusak integritas sistem atau infrastruktur Guru Masterpiece.</li>
                        <li>Mendistribusikan ulang atau menjual kembali akun akses Anda kepada pihak lain tanpa izin tertulis.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">6</span>
                    Tanggung Jawab Konten AI
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed italic border-l-4 border-primary/20 pl-4 bg-slate-50 py-3 rounded-r-xl">
                        Guru Masterpiece menyediakan teknologi AI "as-is" (sebagaimana adanya). Meskipun kami berupaya memberikan hasil yang terbaik, kami tidak memberikan jaminan 100% atas akurasi konten yang dihasilkan. Pengguna wajib meninjau dan memverifikasi kebenaran setiap konten sebelum digunakan dalam proses pengajaran.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-bold text-dark mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center text-sm">7</span>
                    Hukum yang Berlaku
                </h2>
                <div class="pl-10">
                    <p class="text-slate-600 leading-relaxed">Ketentuan ini diatur dan ditafsirkan sesuai dengan hukum yang berlaku di Republik Indonesia.</p>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
