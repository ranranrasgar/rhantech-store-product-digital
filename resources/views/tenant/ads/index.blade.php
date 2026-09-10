@extends('layouts.tenant')

@section('title', 'Iklan & Promosi Toko - Rhantech Seller Center')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f6f8] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
        showZeroBalanceModal: {{ $hasZeroBalance ? 'true' : 'false' }},
        showPromoModal: false,
        init() {
            // Cek jika modal saldo habis sudah di-dismiss dalam sesi ini
            if (sessionStorage.getItem('dismissed_zero_balance_modal')) {
                this.showZeroBalanceModal = false;
            }

            // Aturan Modal Promo: CUKUP 1 KALI SAJA saat pertama kali buka halaman iklan
            // Jangan pernah tampil lagi jika:
            // 1. Toko sudah mengklaim voucher 500rb ($hasClaimedWelcomeVoucher)
            // 2. User sudah pernah membuka/melihat modal ini sebelumnya (localStorage: 'seen_seller_promo_modal')
            // 3. Modal saldo nol sedang aktif
            const hasSeenPromo = localStorage.getItem('seen_seller_promo_modal');
            const hasClaimedVoucher = {{ (isset($hasClaimedWelcomeVoucher) && $hasClaimedWelcomeVoucher) ? 'true' : 'false' }};

            if (!hasSeenPromo && !hasClaimedVoucher && !this.showZeroBalanceModal) {
                this.showPromoModal = true;
                // Kunci permanen agar cukup 1x saja seumur hidup akun/browser
                localStorage.setItem('seen_seller_promo_modal', '1');
            }
        },
        closeZeroBalanceModal() {
            this.showZeroBalanceModal = false;
            sessionStorage.setItem('dismissed_zero_balance_modal', '1');
        },
        closePromoModal(dontShowAgain = true) {
            this.showPromoModal = false;
            localStorage.setItem('seen_seller_promo_modal', '1');
        }
     }">

    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Breadcrumb & Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('tenant.dashboard') }}" class="hover:text-[#0284c7] transition-colors">Beranda</a>
                <span>&gt;</span>
                <span class="text-slate-800 dark:text-slate-200 font-semibold">Iklan Toko & Promosi</span>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('tenant.ads.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-lg text-xs md:text-sm font-bold shadow-sm transition-all transform hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Buat Iklan Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-sm flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">warning</span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-sm flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Saldo Saya & Banner Promosi Toko (Rhantech Seller) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <!-- Kolom Kiri: Saldo Saya (2 Span) -->
            <div class="lg:col-span-2 bg-white dark:bg-[#161b22] rounded-2xl p-5 md:p-6 border border-slate-200/90 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#0284c7] text-[20px]">account_balance_wallet</span>
                        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Saldo Saya</h2>
                    </div>
                    <a href="{{ route('tenant.ads.top-up') }}" class="text-xs font-semibold text-[#0284c7] hover:underline flex items-center gap-0.5">
                        Lihat Rincian <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 mb-4 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                            <span>Saldo Iklan</span>
                            <a href="{{ route('help.show', 'cara-kerja-saldo-iklan-bonus-saldo-rp500000') }}" target="_blank" class="text-slate-400 hover:text-[#0284c7] transition-colors" title="Pelajari cara kerja saldo iklan & bonus Rp500.000">
                                <span class="material-symbols-outlined text-[14px]">help</span>
                            </a>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black {{ $adBalance <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white' }} mt-1">
                            Rp{{ number_format($adBalance, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                            Biaya Iklan Hari Ini
                            <span class="material-symbols-outlined text-[14px] text-slate-400" title="Total biaya klik & impresi iklan toko hari ini">info</span>
                        </div>
                        <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                            Rp{{ number_format($todaySpent, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                @if($adBalance <= 0)
                <div class="p-3 mb-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-rose-500 text-[18px] shrink-0 mt-0.5">error</span>
                    <p class="text-xs text-rose-700 dark:text-rose-300 leading-relaxed font-medium">
                        <strong>Pengumuman:</strong> Semua iklan telah dijeda karena saldo Rp0. Isi saldo sekarang agar iklan Anda aktif kembali dan produk menjangkau pembeli!
                    </p>
                </div>
                @endif

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('tenant.ads.top-up') }}" class="px-6 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">payments</span>
                        Isi Saldo
                    </a>
                    <button type="button" @click="showPromoModal = true" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-amber-500">card_giftcard</span>
                        Panduan & Promo Spesial
                    </button>
                    <a href="{{ route('help.show', 'panduan-rumus-modal-iklan-harga-bid-per-klik-cpc-sistem-pemotongan-saldo') }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-[#0284c7]">menu_book</span>
                        Buku Panduan Iklan
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Voucher & Program Spesial Toko -->
            <div class="bg-gradient-to-br from-sky-50 to-cyan-50/60 dark:from-slate-800/80 dark:to-slate-900 rounded-2xl p-5 md:p-6 border border-sky-200/80 dark:border-slate-800 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[#0284c7]/10 text-[#0284c7] text-[11px] font-bold mb-3">
                        <span class="material-symbols-outlined text-[14px]">local_offer</span>
                        Voucher Spesial Toko
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white leading-snug">
                        Tingkatkan Penjualan dengan Voucher Disubsidi Platform!
                    </h3>
                    <ul class="text-xs text-slate-600 dark:text-slate-300 mt-3 space-y-2">
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-500">check_circle</span>
                            Tingkatkan daya saing iklanmu sampai <strong class="text-emerald-600 dark:text-emerald-400">+10%</strong>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-500">check_circle</span>
                            Toko diprioritaskan di hasil pencarian & beranda
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-500">check_circle</span>
                            Subsidi potongan belanja untuk pembeli produkmu
                        </li>
                    </ul>
                </div>

                <div class="mt-5 pt-4 border-t border-sky-200/60 dark:border-slate-700/60">
                    @if(isset($hasClaimedWelcomeVoucher) && $hasClaimedWelcomeVoucher)
                    <div class="space-y-2">
                        <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            <span class="material-symbols-outlined text-[18px]">verified</span>
                            <span>Bonus Rp500.000 Aktif di Saldo Iklan</span>
                        </div>
                        <a href="{{ route('tenant.ads.create') }}" class="w-full py-2.5 px-4 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-bold text-center block shadow-sm hover:opacity-95 transition-all" style="background: #0284c7 !important; color: #ffffff !important;">
                            Pasang Iklan Sekarang
                        </a>
                    </div>
                    @else
                    <form action="{{ route('tenant.ads.claim-voucher') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs sm:text-sm font-bold text-center flex items-center justify-center gap-2 shadow-sm transition-all cursor-pointer" style="background: #0284c7 !important; color: #ffffff !important;">
                            <span class="material-symbols-outlined text-[18px]">redeem</span>
                            <span>Klaim Bonus Rp500.000 Sekarang</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekomendasi untuk Mengoptimalkan Iklan (Rhantech Card Grid) -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-5 bg-[#0284c7] rounded-full"></span>
                    <h3 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100">
                        Rekomendasi untuk Mengoptimalkan Iklan
                    </h3>
                </div>
                <span class="text-xs text-slate-500 dark:text-slate-400">4 Rekomendasi Aktif</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card Rekomendasi 1 -->
                <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:border-[#0284c7]/50 transition-colors">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-sky-100 dark:bg-sky-950/50 text-[#0284c7] flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">auto_graph</span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2">
                            Aktifkan Isi Saldo Otomatis dari Penghasilan Toko
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                            Mencegah iklan terhenti di tengah pencarian saat kuota saldo habis.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('tenant.ads.top-up') }}" class="w-full py-1.5 px-3 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-[#0284c7] hover:bg-[#0284c7] hover:text-white text-xs font-bold text-center block transition-all">
                            Atur Saldo
                        </a>
                    </div>
                </div>

                <!-- Card Rekomendasi 2 -->
                <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:border-[#0284c7]/50 transition-colors">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">trending_up</span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2">
                            Pasang Iklan pada Kata Kunci Terpopuler
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                            Dapatkan peningkatan konversi hingga +25% dengan mencocokkan kata pencarian pembeli.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('tenant.ads.create') }}" class="w-full py-1.5 px-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white text-xs font-bold text-center block transition-all">
                            Pasang Kata Kunci
                        </a>
                    </div>
                </div>

                <!-- Card Rekomendasi 3 -->
                <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:border-[#0284c7]/50 transition-colors">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-sky-100 dark:bg-sky-950/50 text-sky-600 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">bolt</span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2">
                            Mode Modal Dinamis Harian
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                            Optimalkan jam tayang iklan di jam sibuk pembeli berbelanja produk digital.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('tenant.ads.create') }}" class="w-full py-1.5 px-3 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 hover:bg-sky-600 hover:text-white text-xs font-bold text-center block transition-all">
                            Terapkan Sekarang
                        </a>
                    </div>
                </div>

                <!-- Card Rekomendasi 4 -->
                <div class="bg-white dark:bg-[#161b22] p-4 rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm flex flex-col justify-between hover:border-[#0284c7]/50 transition-colors">
                    <div>
                        <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-950/50 text-purple-600 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">stars</span>
                        </div>
                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2">
                            Klaim Badge &ldquo;Mitra Pilihan&rdquo;
                        </h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                            Iklan tokomu akan tampil menonjol dengan label verifikasi resmi platform.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showPromoModal = true" class="w-full py-1.5 px-3 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 hover:bg-purple-600 hover:text-white text-xs font-bold text-center block transition-all">
                            Pelajari Syarat
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Iklan Toko & Produk -->
        <div class="bg-white dark:bg-[#161b22] rounded-2xl border border-slate-200/90 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Daftar Iklan Toko & Produk</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Pantau status, impresi pencarian, klik, dan performa iklan Anda secara langsung.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        Total: {{ $totalAdsCount }}
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400">
                        Aktif: {{ $activeAdsCount }}
                    </span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-400">
                        Dijeda: {{ $pausedAdsCount }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 dark:bg-slate-900/80 border-b border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-5">Iklan / Produk</th>
                            <th class="py-3.5 px-4">
                                <div class="flex items-center gap-1">
                                    <span>Tipe & Target</span>
                                    <a href="{{ route('help.show', 'panduan-memasang-iklan-produk-bidding-manual-vs-otomatis') }}" target="_blank" class="text-slate-400 hover:text-[#0284c7] transition-colors" title="Buka Panduan Bidding & Kata Kunci">
                                        <span class="material-symbols-outlined text-[14px] align-middle">help</span>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3.5 px-4">
                                <div class="flex items-center gap-1">
                                    <span>Modal / Biaya</span>
                                    <a href="{{ route('help.show', 'panduan-rumus-modal-iklan-harga-bid-per-klik-cpc-sistem-pemotongan-saldo') }}" target="_blank" class="text-slate-400 hover:text-[#0284c7] transition-colors" title="Buka Panduan Rumus Modal & Pemotongan Biaya">
                                        <span class="material-symbols-outlined text-[14px] align-middle">help</span>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">
                                <div class="flex items-center gap-1">
                                    <span>Impresi / Klik</span>
                                    <a href="{{ route('help.show', 'panduan-rumus-modal-iklan-harga-bid-per-klik-cpc-sistem-pemotongan-saldo') }}" target="_blank" class="text-slate-400 hover:text-[#0284c7] transition-colors" title="Buka Panduan Impresi Gratis & Biaya Klik (CPC)">
                                        <span class="material-symbols-outlined text-[14px] align-middle">help</span>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($ads as $ad)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($ad->product && $ad->product->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $ad->product->images->first()->image_path) }}" alt="{{ $ad->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-slate-400 text-[22px]">storefront</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 dark:text-white truncate max-w-xs sm:max-w-md">
                                            {{ $ad->name }}
                                        </div>
                                        @if($ad->product)
                                            <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate">
                                                Produk: {{ $ad->product->name }} (Rp{{ number_format($ad->product->price, 0, ',', '.') }})
                                            </div>
                                        @else
                                            <div class="text-[11px] text-teal-600 dark:text-teal-400 font-semibold">
                                                Iklan Profil Toko
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-slate-600 dark:text-slate-300">
                                <div class="font-semibold">{{ $ad->bidding_mode === 'auto' ? 'Bidding Otomatis' : 'Manual (Rp' . number_format($ad->bid_price, 0, ',', '.') . '/klik)' }}</div>
                                @if(!empty($ad->target_keywords))
                                    <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-[150px]">
                                        Kata Kunci: {{ implode(', ', $ad->target_keywords) }}
                                    </div>
                                @else
                                    <div class="text-[10px] text-slate-400 mt-0.5">Semua Kata Kunci Relevan</div>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-700 dark:text-slate-300">
                                <div class="font-bold">
                                    {{ $ad->budget_type === 'unlimited' ? 'Tak Terbatas' : 'Rp' . number_format($ad->daily_budget, 0, ',', '.') . '/hari' }}
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Terpakai: Rp{{ number_format($ad->spent_amount, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[13px] text-sky-500">date_range</span>
                                    @if($ad->period_type === 'custom' && $ad->start_date && $ad->end_date)
                                        <span>{{ \Carbon\Carbon::parse($ad->start_date)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($ad->end_date)->format('d/m/y') }}</span>
                                    @else
                                        <span>Periode Tanpa Batas</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                @if($ad->status === 'active')
                                    @if($adBalance > 0)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Berjalan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Saldo Habis
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        Dijeda
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-slate-600 dark:text-slate-300">
                                <div class="font-bold">{{ number_format($ad->views_count) }} tayang</div>
                                <div class="text-[11px] text-slate-400">{{ number_format($ad->clicks_count) }} klik</div>
                            </td>
                            <td class="py-4 px-5 text-right space-x-2">
                                <form action="{{ route('tenant.ads.toggle', $ad->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg border text-xs font-semibold transition-colors {{ $ad->status === 'active' ? 'border-amber-300 text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/40' : 'border-emerald-300 text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}">
                                        {{ $ad->status === 'active' ? 'Jeda' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('tenant.ads.destroy', $ad->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kampanye iklan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 dark:text-rose-400 font-semibold text-xs transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                                <span class="material-symbols-outlined text-[44px] text-slate-300 dark:text-slate-600 mb-2 block">ads_click</span>
                                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Belum ada iklan promosi yang dibuat.</p>
                                <p class="text-xs text-slate-400 mt-1">Tingkatkan visibilitas produk Anda di platform dengan membuat iklan pertama!</p>
                                <a href="{{ route('tenant.ads.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl bg-[#0284c7] text-white text-xs font-bold shadow-sm hover:bg-[#0369a1] transition-all">
                                    <span class="material-symbols-outlined text-[16px]">add</span> Buat Iklan Produk Sekarang
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ads->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $ads->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 1. POPUP MODAL: "Saldo habis, semua iklanmu dihentikan" (Persis Screenshot 2) -->
    <!-- ========================================================================= -->
    <div x-show="showZeroBalanceModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-[#161b22] w-full max-w-sm rounded-3xl p-6 text-center shadow-2xl border border-slate-200 dark:border-slate-800 relative"
             @click.away="closeZeroBalanceModal()">
            
            <!-- Tombol Close (X) -->
            <button type="button" @click="closeZeroBalanceModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>

            <!-- 3D Alert Modal Render -->
            <div class="w-24 h-24 mx-auto mb-2 overflow-hidden flex items-center justify-center">
                <img src="{{ asset('images/empty_wallet_alert.jpg') }}" alt="Saldo Habis" class="w-full h-full object-contain drop-shadow-md rounded-2xl">
            </div>

            <h3 class="text-base font-black text-[#0284c7]">
                Saldo habis, semua iklanmu dihentikan
            </h3>

            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                Saldo iklanmu <strong>Rp0</strong>. Semua iklan telah dihentikan. Silakan isi saldo agar iklanmu bisa tetap berjalan.
            </p>

            <div class="mt-6 space-y-2">
                <a href="{{ route('tenant.ads.top-up') }}" class="w-full py-2.5 px-4 bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-bold rounded-xl shadow-md transition-all block">
                    Isi Saldo Sekarang
                </a>
                <button type="button" @click="closeZeroBalanceModal()" class="w-full py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:underline">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. POPUP MODAL: "Buat Iklan dan Dapatkan Voucher Spesial Toko!" (Persis Screenshot 1) -->
    <!-- ========================================================================= -->
    <div x-show="showPromoModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-[#161b22] w-full max-w-3xl rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800 relative"
             @click.away="closePromoModal()">
            
            <!-- Tombol Close (X) -->
            <button type="button" @click="closePromoModal()" class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-white/80 dark:bg-black/40 backdrop-blur-sm text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>

            <!-- Banner Header 3D Festive Ilustrasi Biru Langit -->
            <div class="relative h-44 sm:h-52 w-full overflow-hidden bg-gradient-to-r from-sky-500 to-cyan-600">
                <img src="{{ asset('images/sky_blue_gift_banner.jpg') }}" 
                     alt="Promo Iklan Toko Rhantech" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-[#161b22] via-transparent to-transparent"></div>
            </div>

            <!-- Header Title -->
            <div class="px-6 md:px-8 text-center -mt-6 relative z-10">
                <h3 class="text-xl md:text-2xl font-black text-[#0284c7] tracking-tight">
                    Buat Iklan dan Dapatkan Voucher Spesial Toko!
                </h3>
            </div>

            <!-- Konten 3 Langkah Card Promosi Rhantech -->
            <div class="p-6 md:p-8 space-y-6">
                <div class="flex flex-col md:flex-row items-center gap-3">
                    
                    <!-- Card 1: Buat Iklan Produk -->
                    <div class="flex-1 bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 text-left w-full">
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-2">Buat Iklan Produk</div>
                        
                        <div class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 mb-2">
                            <div class="text-[10px] text-slate-400">Pilih Produk</div>
                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200 flex items-center justify-between">
                                <span>Pilih Produk Otomatis</span>
                                <span class="material-symbols-outlined text-[14px] text-slate-400">edit</span>
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-500 mb-1">
                            Saldo Iklan Saya: <strong class="text-slate-900 dark:text-white">Rp{{ number_format($adBalance, 0, ',', '.') }}</strong>
                            <a href="{{ route('tenant.ads.top-up') }}" class="text-[#0284c7] font-bold underline ml-1">Isi Saldo</a>
                        </div>
                        <div class="text-[10px] text-slate-400">
                            Estimasi ROI <strong>5,4 - 9,7</strong><br>
                            Atur modal, dapatkan penjualan maksimal.
                        </div>
                    </div>

                    <!-- Chevron Arrow 1 -->
                    <span class="text-[#0284c7] font-black text-xl select-none hidden md:block">»</span>

                    <!-- Card 2: Dapatkan Tambahan Kunjungan & Voucher Spesial Toko -->
                    <div class="flex-1 bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 text-left w-full">
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-2 flex items-center justify-between">
                            <span>Dapatkan Kunjungan & Voucher</span>
                            <span class="material-symbols-outlined text-[14px] text-slate-400">help</span>
                        </div>
                        
                        <div class="p-2 rounded-xl bg-sky-50/80 dark:bg-sky-950/30 border border-sky-200 dark:border-sky-800/60 mb-2 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#0284c7] text-[20px]">local_activity</span>
                            <div>
                                <div class="text-[11px] font-extrabold text-[#0284c7]">Voucher Spesial Toko</div>
                                <div class="text-[9px] text-slate-500">Estimasi Voucher Tambahan Platform</div>
                            </div>
                        </div>

                        <div class="p-2 rounded-xl bg-cyan-50/80 dark:bg-cyan-950/30 border border-cyan-200 dark:border-cyan-800/60 flex items-center gap-2">
                            <span class="material-symbols-outlined text-cyan-600 dark:text-cyan-400 text-[20px]">ads_click</span>
                            <div>
                                <div class="text-[11px] font-extrabold text-cyan-700 dark:text-cyan-300">Kunjungan +30%</div>
                                <div class="text-[9px] text-slate-500">Dukungan promosi dari platform</div>
                            </div>
                        </div>
                    </div>

                    <!-- Chevron Arrow 2 -->
                    <span class="text-[#0284c7] font-black text-xl select-none hidden md:block">»</span>

                    <!-- Card 3: Peningkatan Penjualan -->
                    <div class="flex-1 bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 text-center w-full flex flex-col justify-center">
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-2">Dapatkan Peningkatan Penjualan</div>
                        
                        <div class="p-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 my-auto">
                            <div class="text-[10px] text-slate-400">Estimasi Penjualan Harian</div>
                            <div class="text-xs sm:text-sm font-black text-[#0284c7] mt-1">
                                Rp1.350.000 - Rp2.425.000
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Tombol Ganda -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="closePromoModal(true)" class="text-xs font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        Jangan tampilkan lagi
                    </button>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <a href="{{ route('tenant.ads.index') }}" @click="closePromoModal()" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-center">
                            Lihat Rincian
                        </a>
                        <a href="{{ route('tenant.ads.create') }}" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-[#0284c7] hover:bg-[#d73211] text-white text-xs font-bold shadow-md transition-all text-center">
                            Buat Iklan & Dapatkan Voucher!
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
