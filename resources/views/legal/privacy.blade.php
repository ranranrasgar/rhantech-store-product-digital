@extends('layouts.public')

@section('title', 'Kebijakan Privasi & Perlindungan Data Pribadi - ' . ($company->company_name ?? 'Rhantech'))
@section('meta_description', 'Kebijakan Privasi dan Perlindungan Data Pribadi pengguna platform Rhantech sesuai amanat Undang-Undang Nomor 27 Tahun 2022 (UU PDP).')

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
                <span>Kebijakan Privasi</span>
            </div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 text-xs font-bold mb-3 border border-emerald-200 dark:border-emerald-800/60">
                <span class="material-symbols-outlined text-[16px]">security</span>
                <span>Kepatuhan UU No. 27 Tahun 2022 (Pelindungan Data Pribadi - PDP)</span>
            </div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-on-background dark:text-white tracking-tight">
                Kebijakan Privasi (Privacy Policy)
            </h1>
            <p class="text-xs sm:text-sm text-on-surface-variant dark:text-slate-400 mt-2">
                Terakhir Diperbarui: <strong>September 2026</strong> | Komitmen Penuh Menjaga Kerahasiaan Data Pribadi Anda
            </p>
        </div>

        <!-- Content Body -->
        <article class="prose dark:prose-invert max-w-none text-xs sm:text-sm leading-relaxed space-y-6 text-slate-700 dark:text-slate-300">
            
            <p>
                <strong>{{ $company->company_name ?? 'Rhantech' }}</strong> berkomitmen penuh untuk menghormati dan melindungi data pribadi setiap pengunjung, pembeli, dan mitra penjual platform kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, memproses, menyimpan, dan melindungi data pribadi Anda sesuai dengan <strong>Undang-Undang Republik Indonesia Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi (UU PDP)</strong>.
            </p>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    1. Data Pribadi yang Kami Kumpulkan
                </h2>
                <p>Kami hanya mengumpulkan data pribadi yang relevan dan dibutuhkan untuk kelancaran transaksi serta operasional platform:</p>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li><strong>Data Identitas Akun:</strong> Nama lengkap, alamat email aktif, nomor telepon/WhatsApp, dan password terenkripsi saat registrasi atau login melalui Google One-Tap.</li>
                    <li><strong>Data Transaksi & Keuangan:</strong> Riwayat pemesanan produk, tanggal pembelian, metode pembayaran, invoice transaksi, serta nomor rekening bank penjual untuk pencairan dana (payout). <em>Catatan: Kami tidak pernah menyimpan data PIN perbankan atau nomor CVV kartu kredit.</em></li>
                    <li><strong>Data Profil Toko Penjual:</strong> Nama toko, deskripsi profil, logo, banner, tautan sosial media, dan titik koordinat maps yang dimasukkan secara sukarela.</li>
                    <li><strong>Data Teknis & Perangkat:</strong> Alamat IP, jenis browser, sistem operasi, serta cookie pelacakan sesi untuk menjaga keamanan akun dan analitik agregat.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    2. Tujuan Pemrosesan Data Pribadi
                </h2>
                <p>Data pribadi Anda diproses untuk tujuan yang sah dan transparan:</p>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li>Memverifikasi identitas pengguna dan otentikasi akun.</li>
                    <li>Memproses transaksi pembayaran instan dan menyediakan tautan unduh produk digital.</li>
                    <li>Menyalurkan saldo penghasilan penjualan ke rekening bank toko terdaftar.</li>
                    <li>Menyediakan fitur komunikasi interaktif (Live Chat) antara pembeli dan penjual.</li>
                    <li>Mendeteksi, mencegah, dan menindak aktivitas penipuan, pelanggaran hak cipta, atau serangan siber.</li>
                    <li>Memenuhi kepatuhan terhadap kewajiban hukum dan perpajakan di Indonesia.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    3. Keamanan & Penyimpanan Data
                </h2>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li><strong>Enkripsi SSL/TLS:</strong> Seluruh pertukaran data antara browser Anda dan server kami dienkripsi menggunakan protokol HTTPS 256-bit standar industri perbankan.</li>
                    <li><strong>Proteksi Kata Sandi:</strong> Kata sandi Anda dienkripsi satu arah menggunakan algoritma hashing <em>Bcrypt</em> yang tidak dapat dibaca bahkan oleh tim internal pengelola sistem.</li>
                    <li><strong>Penyimpanan Domestik:</strong> Basis data sistem kami disimpan di server pusat data yang mematuhi standar keandalan dan keamanan data nasional.</li>
                </ul>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    4. Hak-Hak Anda Sebagai Subjek Data Pribadi (UU PDP)
                </h2>
                <p>Berdasarkan Pasal 5 sampai Pasal 13 UU No. 27 Tahun 2022, Anda memiliki hak penuh sebagai berikut:</p>
                <ol class="list-decimal list-inside space-y-1.5 pl-2">
                    <li><strong>Hak Mendapatkan Informasi:</strong> Berhak mengetahui kejelasan identitas pengendali data dan tujuan pemrosesan data Anda.</li>
                    <li><strong>Hak Akses & Pembaruan:</strong> Berhak mengakses dan memperbarui data profil akun serta informasi rekening bank Anda setiap saat melalui menu Pengaturan Profil.</li>
                    <li><strong>Hak Penghapusan Data (Right to be Forgotten):</strong> Berhak mengajukan permohonan penutupan akun dan penghapusan data pribadi dengan menghubungi tim dukungan kami.</li>
                    <li><strong>Hak Menarik Persetujuan:</strong> Berhak menarik persetujuan pemrosesan data untuk kepentingan promosi/marketing.</li>
                </ol>
            </section>

            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-b border-slate-200 dark:border-slate-800 pb-2">
                    5. Pembagian Data Kepada Pihak Ketiga
                </h2>
                <p>
                    Kami <strong>TIDAK AKAN PERNAH</strong> menjual, menyewakan, atau memperdagangkan data pribadi Anda kepada pihak ketiga manapun untuk tujuan periklanan pihak ketiga tanpa persetujuan Anda. Pembagian data hanya dilakukan kepada mitra berlisensi resmi yang esensial untuk penyelesaian transaksi:
                </p>
                <ul class="list-disc list-inside space-y-1.5 pl-2">
                    <li><strong>Payment Gateway Berlisensi Bank Indonesia:</strong> Untuk pemrosesan otentikasi transaksi pembayaran (QRIS, VA Bank, e-Wallet).</li>
                    <li><strong>Penegak Hukum & Otoritas Resmi Pemerintah RI:</strong> Jika diwajibkan secara sah berdasarkan surat perintah pengadilan atau ketentuan perundang-undangan.</li>
                </ul>
            </section>

            <!-- Box DPO Kontak -->
            <div class="mt-8 p-5 rounded-2xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 text-[18px]">verified_user</span>
                    Pejabat Pelindungan Data Pribadi (Data Protection Officer)
                </h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-3">
                    Untuk mengajukan pertanyaan, permintaan pembaruan data, atau pelaksanaan hak subjek data Anda, silakan hubungi Pejabat Pelindungan Data Pribadi kami:
                </p>
                <div class="text-xs space-y-1 text-slate-700 dark:text-slate-300 font-medium">
                    <p><strong>Email Tim Privasi:</strong> privacy@rhantech.com / legal@rhantech.com</p>
                    <p><strong>Subjek Email:</strong> Permohonan Data Pribadi - [Nama Anda]</p>
                </div>
            </div>

        </article>

    </div>
</div>
@endsection
