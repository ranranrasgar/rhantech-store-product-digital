@extends('layouts.public')

@section('title', 'Syarat dan Ketentuan Layanan - ' . ($company->company_name ?? 'Rhantech'))
@section('meta_description', 'Syarat dan Ketentuan Layanan penggunaan platform marketplace produk digital Rhantech sesuai regulasi hukum Republik Indonesia (PP 80/2019 & UU ITE).')

@section('content')
<div class="py-12 md:py-16 bg-surface-container-lowest dark:bg-[#070b12] text-on-surface dark:text-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8 pb-6 border-b border-outline-variant/40 dark:border-slate-800">
            <div class="flex items-center gap-2 text-xs text-on-surface-variant/70 dark:text-slate-400 mb-3">
                <a href="{{ url('/') }}" class="hover:text-primary transition-colors">Beranda</a>
                <span>/</span>
                <span class="text-primary font-semibold">Legal & Regulasi</span>
                <span>/</span>
                <span>Syarat & Ketentuan</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-xs font-bold mb-3 border border-sky-200 dark:border-sky-800/60">
                <span class="material-symbols-outlined text-[16px]">gavel</span>
                <span>Regulasi PMSE Republik Indonesia (PP No. 80/2019 & UU ITE)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-on-background dark:text-white tracking-tight">
                Syarat dan Ketentuan Layanan (Terms of Service)
            </h1>
            <p class="text-xs sm:text-sm text-on-surface-variant dark:text-slate-400 mt-2">
                Terakhir Diperbarui: <strong>September 2026</strong> | Berlaku Efektif untuk Seluruh Pengguna Platform
            </p>
        </div>

        <!-- Content Body -->
        <article class="prose dark:prose-invert max-w-none text-xs sm:text-sm leading-relaxed space-y-6 text-slate-700 dark:text-slate-300">
            
            <div class="p-4 rounded-2xl bg-sky-50 dark:bg-slate-800/60 border border-sky-200 dark:border-sky-800 text-slate-800 dark:text-slate-200">
                <h3 class="text-sm sm:text-base font-bold text-sky-800 dark:text-sky-300 mb-1 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    Pernyataan Kepatuhan Hukum
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-300">
                    Selamat datang di <strong>{{ $company->company_name ?? 'Rhantech' }}</strong>. Dokumen Syarat dan Ketentuan ini merupakan kontrak elektronik yang sah dan mengikat secara hukum antara Anda (Pengguna/Penjual/Pembeli) dengan platform penyelenggara berdasarkan <strong>Undang-Undang Nomor 1 Tahun 2024 tentang Perubahan Kedua UU ITE</strong> dan <strong>Peraturan Pemerintah Nomor 80 Tahun 2019 tentang Perdagangan Melalui Sistem Elektronik (PMSE)</strong>.
                </p>
            </div>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    1. Definisi Istilah
                </h2>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li><strong>Platform:</strong> Website, sistem, dan layanan digital yang dikelola oleh {{ $company->company_name ?? 'Rhantech' }}.</li>
                    <li><strong>Produk Digital:</strong> Source code perangkat lunak, script aplikasi, template website, modul plugin, aset desain, dan produk digital berwujud data elektronik yang ditransaksikan.</li>
                    <li><strong>Penjual (Tenant):</strong> Pengguna terdaftar yang membuka toko digital dan mengunggah produk digital untuk diperjualbelikan.</li>
                    <li><strong>Pembeli:</strong> Pengguna yang melakukan pesanan dan pembayaran untuk memperoleh lisensi pengunduhan produk digital.</li>
                    <li><strong>Safe Harbor (Perantara):</strong> Kedudukan hukum Platform sebagai penyedia sistem perantara (intermediary service provider) yang mempertemukan Penjual dan Pembeli.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    2. Ketentuan Akun & Pendaftaran Toko
                </h2>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li>Pengguna wajib berusia minimal 18 tahun atau memiliki kecakapan hukum untuk membuat perjanjian yang mengikat.</li>
                    <li>Pengguna wajib memberikan data identitas diri, email aktif, dan nomor kontak yang benar dan dapat diverifikasi.</li>
                    <li>Setiap toko baru berhak mendapatkan alokasi subsidi promosi (Saldo Iklan Rp500.000) sesuai kebijakan promo yang berlaku. Saldo ini adalah kredit promosi di dalam sistem dan tidak dapat dicairkan langsung ke rekening bank.</li>
                    <li>Platform berhak membekukan atau menutup akun yang terbukti melakukan pendaftaran fiktif, spamming, atau aktivitas manipulatif.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    3. Kewajiban & Larangan Bagi Penjual (Seller)
                </h2>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li><strong>Orisinalitas & Hak Cipta:</strong> Penjual menjamin bahwa seluruh source code dan produk digital yang diunggah adalah karya orisinal miliknya atau telah memiliki lisensi sah untuk didistribusikan secara komersial.</li>
                    <li><strong>Larangan Warez / Nulled:</strong> DILARANG KERAS mengunggah script hasil bajakan, nulled script, cracked software, file berlisensi GPL yang dimanipulasi secara ilegal, atau karya curian dari pihak lain.</li>
                    <li><strong>Bebas Malware:</strong> DILARANG menyisipkan kode berbahaya seperti trojan, virus, backdoor, ransomware, cryptocurrency miner, atau script pencuri kredensial.</li>
                    <li><strong>Dukungan Produk:</strong> Penjual wajib memberikan panduan instalasi (README) dan merespons pertanyaan pembeli terkait kendala file rusak dalam waktu maksimal 3x24 jam.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    4. Ketentuan Pembelian & Lisensi Produk
                </h2>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li>Pembelian produk digital di platform memberikan hak <strong>Lisensi Penggunaan Non-Eksklusif</strong> kepada Pembeli, bukan pengalihan hak cipta kepemilikan utama (kecuali ada perjanjian khusus).</li>
                    <li>Pembeli dilarang menjual kembali (re-distribute / re-sell) source code mentah yang dibeli kepada pihak ketiga tanpa modifikasi signifikan, kecuali memiliki Lisensi Extended / Developer.</li>
                    <li>Pembeli berhak atas akses pengunduhan file berulang tanpa batas waktu selama produk tetap tayang di platform.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    5. Batasan Tanggung Jawab Platform (Safe Harbor Principle)
                </h2>
                <p>
                    Berdasarkan <strong>Surat Edaran Mahkamah Agung (SEMA) No. 2 Tahun 2019</strong> dan <strong>Permenkominfo No. 5 Tahun 2016</strong> mengenai batasan tanggung jawab penyedia platform:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li>Platform bertindak sebagai perantara teknis dan tidak bertanggung jawab secara pidana maupun perdata atas perselisihan hak cipta yang timbul akibat unggahan mandiri pengguna, sepanjang Platform telah menyediakan prosedur pelaporan dan segera mencabut konten yang melanggar (Notice and Takedown).</li>
                    <li>Platform tidak bertanggung jawab atas kerugian finansial atau kerusakan sistem server pembeli akibat kesalahan konfigurasi atau modifikasi mandiri pada source code.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    6. Hukum yang Mengatur & Penyelesaian Sengketa
                </h2>
                <p>
                    Syarat dan Ketentuan ini diatur dan ditafsirkan sepenuhnya berdasarkan <strong>Hukum Negara Republik Indonesia</strong>. Segala perselisihan yang timbul akan diselesaikan terlebih dahulu melalui musyawarah mufakat. Apabila tidak tercapai kesepakatan, sengketa akan diajukan ke yurisdiksi Pengadilan Negeri yang berwenang di wilayah domisili Platform.
                </p>
            </section>

            <!-- Box Bantuan & Kontak Layanan Pengaduan -->
            <div class="mt-8 p-5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">contact_support</span>
                    Layanan Pengaduan Konsumen
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
                    Jika Anda memiliki pertanyaan mengenai Syarat dan Ketentuan ini atau ingin menyampaikan keluhan layanan, silakan hubungi tim legal kami:
                </p>
                <div class="text-xs space-y-1 text-slate-700 dark:text-slate-300 font-medium">
                    <p><strong>Email Dukungan Legal:</strong> legal@rhantech.com / support@rhantech.com</p>
                    <p><strong>WhatsApp Support:</strong> +62 812-3456-7890</p>
                    <p><strong>Pusat Panduan:</strong> <a href="{{ route('help.index') }}" class="text-primary underline">Kunjungi Pusat Bantuan Aplikasi</a></p>
                </div>
            </div>

        </article>

    </div>
</div>
@endsection
