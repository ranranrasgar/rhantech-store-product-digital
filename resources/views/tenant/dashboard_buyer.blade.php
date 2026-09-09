@extends('layouts.tenant')

@section('title', 'Dashboard - Seller Center')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f8fb] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
        showPromoModal: true,
        showQuickCreateModal: false,
        showDetails: false,
        storeName: '{{ auth()->user()->name }} Store',
        init() {
            if (sessionStorage.getItem('dismissed_buyer_promo_modal')) {
                this.showPromoModal = false;
            }
        },
        closePromo(dontShowSession = false) {
            this.showPromoModal = false;
            if (dontShowSession) {
                sessionStorage.setItem('dismissed_buyer_promo_modal', '1');
            }
        }
     }">

    <div class="max-w-6xl mx-auto space-y-7">
        
        <!-- Welcome Title & Status Pill (Biru Langit Theme) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 text-xs font-bold mb-2 border border-sky-200 dark:border-sky-800/60">
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
                    Program Akselerasi Kreator Digital 2026
                </div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    Halo, {{ explode(' ', auth()->user()->name)[0] ?? 'User' }}! 👋
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-xl">
                    Kelola akun dan pembelian Anda, atau buka toko digital Anda sekarang untuk mulai meraih jutaan rupiah dari produk digital.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" @click="showPromoModal = true" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#161b22] border border-sky-200 dark:border-slate-700 text-sky-600 dark:text-sky-400 text-xs font-bold shadow-xs hover:bg-sky-50 dark:hover:bg-slate-800 transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">card_giftcard</span>
                    <span>Klaim Voucher Toko</span>
                </button>
                <button type="button" @click="showQuickCreateModal = true" class="px-5 py-2.5 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-bold shadow-md hover:shadow-sky-500/20 transition-all flex items-center gap-1.5 transform hover:-translate-y-0.5 cursor-pointer" style="background: #0284c7 !important; color: #ffffff !important;">
                    <span class="material-symbols-outlined text-[18px]">storefront</span>
                    <span>Buka Toko Gratis</span>
                </button>
            </div>
        </div>

        <!-- HERO CAMPAIGN BANNER: Biru Langit Glowing Gradient with 3D Render Graphics -->
        <div class="relative rounded-3xl overflow-hidden shadow-xl border border-sky-200/80 dark:border-slate-800 bg-gradient-to-br from-sky-600 via-cyan-600 to-blue-700 text-white p-6 md:p-10">
            <!-- Background Glows -->
            <div class="absolute -right-16 -top-16 w-80 h-80 bg-cyan-300/25 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-80 h-80 bg-blue-900/40 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                
                <!-- Kiri: Copywriting & CTA -->
                <div class="lg:col-span-7 space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-md border border-white/25 text-white text-xs font-extrabold uppercase tracking-wider">
                        <span class="material-symbols-outlined text-[15px] text-cyan-200">stars</span>
                        Spesial Mitra Baru: Komisi 0% & Saldo Promosi
                    </div>

                    <h2 class="text-2xl md:text-4xl font-black text-white leading-tight">
                        Ubah Source Code & Karya Digital Menjadi <span class="text-cyan-200 underline decoration-cyan-300 decoration-wavy decoration-2">Penghasilan Nyata</span>
                    </h2>

                    <p class="text-xs md:text-sm text-sky-100 leading-relaxed max-w-lg">
                        Punya *source code*, aplikasi, script, template desain, atau aset digital? Bergabunglah dengan kreator di platform kami. Toko Anda langsung siap menerima pembeli dalam hitungan detik!
                    </p>

                    <!-- Feature Pills -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 pt-2">
                        <div class="flex items-center gap-2 bg-black/15 backdrop-blur-xs px-3 py-2 rounded-xl border border-white/10 text-xs font-semibold">
                            <span class="material-symbols-outlined text-emerald-300 text-[18px]">check_circle</span>
                            <span>Gratis Selamanya</span>
                        </div>
                        <div class="flex items-center gap-2 bg-black/15 backdrop-blur-xs px-3 py-2 rounded-xl border border-white/10 text-xs font-semibold">
                            <span class="material-symbols-outlined text-amber-300 text-[18px]">bolt</span>
                            <span>Payout Instan</span>
                        </div>
                        <div class="flex items-center gap-2 bg-black/15 backdrop-blur-xs px-3 py-2 rounded-xl border border-white/10 text-xs font-semibold">
                            <span class="material-symbols-outlined text-cyan-300 text-[18px]">ads_click</span>
                            <span>Iklan Promosi Toko</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-3 flex flex-wrap items-center gap-3">
                        <button type="button" @click="showQuickCreateModal = true" class="px-7 py-3 rounded-xl bg-white text-sky-700 hover:bg-sky-50 font-black text-xs md:text-sm shadow-xl transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">storefront</span>
                            Buka Toko Sekarang (1 Menit)
                        </button>
                        <button type="button" @click="showPromoModal = true" class="px-5 py-3 rounded-xl bg-black/20 hover:bg-black/30 border border-white/30 text-white font-bold text-xs md:text-sm transition-all flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">info</span>
                            Pelajari Keuntungan
                        </button>
                    </div>
                </div>

                <!-- Kanan: 3D Illustration Graphic & Floating Metrics -->
                <div class="lg:col-span-5 flex items-center justify-center relative">
                    <div class="relative w-full max-w-sm rounded-3xl overflow-hidden shadow-2xl border-2 border-white/30 bg-white/10 backdrop-blur-xs group">
                        <img src="{{ asset('images/sky_blue_gift_banner.jpg') }}" 
                             alt="Hadiah Spesial Toko Baru" 
                             class="w-full h-56 md:h-64 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-4">
                            <div class="text-white text-xs font-bold">
                                <span class="text-cyan-200">Voucher Rp500.000</span> siap diklaim setelah tokomu aktif!
                            </div>
                        </div>
                    </div>

                    <!-- Floating Badges -->
                    <div class="absolute -top-3 -left-3 bg-emerald-500 text-white text-[11px] font-black px-3 py-1.5 rounded-xl shadow-lg rotate-[-6deg] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">payments</span>
                        Estimasi Rp2.400.000+/bln
                    </div>
                    <div class="absolute -bottom-3 -right-3 bg-cyan-400 text-slate-950 text-[11px] font-black px-3 py-1.5 rounded-xl shadow-lg rotate-[6deg] flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span>
                        Trafik Naik +30%
                    </div>
                </div>

            </div>
        </div>

        <!-- TREN PENCARIAN PEMBELI (Market Opportunity Insight) -->
        <div class="bg-white dark:bg-[#161b22] rounded-2xl p-5 border border-slate-200/90 dark:border-slate-800 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-sky-100 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">local_fire_department</span>
                    </span>
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Peluang Emas: Kata Kunci Paling Banyak Dicari Pembeli</h3>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Permintaan tinggi dari pembeli aktif di platform saat ini</p>
                    </div>
                </div>
                <button type="button" @click="showQuickCreateModal = true" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1 self-start sm:self-center">
                    Mulai Jual Kategori Ini <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </button>
            </div>

            <div class="flex flex-wrap gap-2">
                @if(isset($trendingSearches) && $trendingSearches->isNotEmpty())
                    @foreach($trendingSearches as $ts)
                    <a href="{{ route('products.index', ['search' => $ts->keyword]) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-sky-50 dark:bg-slate-800/80 dark:hover:bg-sky-950/40 border border-slate-200 dark:border-slate-700 hover:border-sky-300 text-slate-800 dark:text-slate-200 text-xs font-semibold transition-all group">
                        <span class="text-sky-500 font-bold">#</span>
                        <span>{{ $ts->keyword }}</span>
                        <span class="text-[10px] text-slate-400 group-hover:text-sky-600">({{ $ts->hits }}x dicari)</span>
                    </a>
                    @endforeach
                @else
                    <span class="text-xs text-slate-400">Source Code Web, Aplikasi Android, Landing Page, Template Tailwind, Skripsi IT.</span>
                @endif
            </div>
        </div>

        <!-- TWO QUICK ACTION CARDS (Pembelian & Eksplorasi) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Card 1: Pembelian Saya -->
            <a href="{{ route('tenant.purchases.index') }}" class="group bg-white dark:bg-[#161b22] border border-slate-200/90 dark:border-slate-800 hover:border-emerald-400 dark:hover:border-emerald-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[26px]">shopping_bag</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 transition-colors">arrow_forward</span>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-1">Riwayat Pembelian Saya</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Akses kembali semua file *source code*, lisensi, dan invoice dari produk digital yang pernah Anda beli di platform.
                    </p>
                </div>
            </a>

            <!-- Card 2: Jelajahi Katalog -->
            <a href="{{ route('products.index') }}" class="group bg-white dark:bg-[#161b22] border border-slate-200/90 dark:border-slate-800 hover:border-sky-400 dark:hover:border-sky-500 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-[26px]">explore</span>
                    </div>
                    <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 group-hover:text-sky-500 transition-colors">arrow_forward</span>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white mb-1">Eksplorasi Katalog Produk</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Temukan ribuan script, tema web, sistem ERP, dan produk siap pakai terbaik dengan harga terjangkau.
                    </p>
                </div>
            </a>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- POPUP MODAL: "Buat Toko & Klaim Voucher" (Biru Langit Style) -->
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

        <div class="bg-white dark:bg-[#161b22] w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800 relative"
             @click.away="closePromo()">
            
            <!-- Tombol Close (X) -->
            <button type="button" @click="closePromo()" class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-white/80 dark:bg-black/40 backdrop-blur-sm text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>

            <!-- Banner Header 3D Festive Biru Langit -->
            <div class="relative h-44 sm:h-52 w-full overflow-hidden bg-gradient-to-r from-sky-500 via-cyan-500 to-blue-600">
                <img src="{{ asset('images/sky_blue_gift_banner.jpg') }}" 
                     alt="Rhantech Festive Gift" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-[#161b22] via-transparent to-transparent"></div>
            </div>

            <!-- Header Title -->
            <div class="px-6 md:px-8 text-center -mt-6 relative z-10">
                <h3 class="text-xl md:text-2xl font-black text-sky-600 dark:text-sky-400 tracking-tight">
                    Buka Toko dan Dapatkan Voucher Spesial Rp500.000!
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Buka toko pertamamu sekarang dan nikmati dukungan promosi eksklusif dari platform.
                </p>
            </div>

            <!-- Konten 3 Langkah Card ala Biru Langit -->
            <div class="p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5 items-center">
                    
                    <!-- Card 1 -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 text-center">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Langkah 1</div>
                        <h4 class="text-xs font-black text-slate-900 dark:text-white">Buka Toko Gratis</h4>
                        <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                            Tanpa biaya pendaftaran. Langsung upload produk digital Anda.
                        </div>
                    </div>

                    <!-- Card 2 (Highlight) -->
                    <div class="bg-gradient-to-b from-sky-50 to-cyan-50 dark:from-slate-800 dark:to-slate-800/80 p-4 rounded-2xl border-2 border-sky-500 text-center relative shadow-sm">
                        <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 px-2 py-0.5 bg-sky-500 text-white text-[9px] font-black rounded-full uppercase tracking-wider">
                            Benefit
                        </span>
                        <div class="text-[10px] font-bold uppercase tracking-wider text-sky-600 mb-1">Voucher Toko</div>
                        <h4 class="text-xs font-black text-slate-900 dark:text-white">Kunjungan +30%</h4>
                        <div class="mt-2 text-[11px] text-slate-600 dark:text-slate-300 font-medium">
                            Disubsidi voucher belanja platform ke tokomu.
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-slate-50 dark:bg-slate-800/60 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 text-center">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Hasil Nyata</div>
                        <h4 class="text-xs font-black text-emerald-600 dark:text-emerald-400">Estimasi Penjualan</h4>
                        <div class="mt-2 text-xs font-black text-slate-900 dark:text-white">
                            Rp1.350.000 - Rp2.425.000
                        </div>
                    </div>

                </div>

                <!-- Penjelasan Rincian Detail: 500rb & Kunjungan 30% -->
                <div x-show="showDetails" x-transition class="p-4 sm:p-5 rounded-2xl bg-sky-50 dark:bg-slate-800/90 border border-sky-200 dark:border-sky-800 text-left space-y-3.5 shadow-inner">
                    <div class="flex items-center gap-2 text-sky-800 dark:text-sky-300 font-extrabold text-xs sm:text-sm">
                        <span class="material-symbols-outlined text-[20px] text-sky-600">verified</span>
                        <span>Mekanisme Resmi: Saldo Rp500.000 & Peningkatan Kunjungan +30%</span>
                    </div>

                    <div class="space-y-3 text-xs text-slate-700 dark:text-slate-200">
                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-[#0284c7] text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-sm">1</span>
                            <div>
                                <strong class="text-slate-900 dark:text-white text-xs sm:text-sm">500rb Larinya ke Mana?</strong>
                                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 mt-0.5 leading-relaxed">
                                    Saldo <strong>Rp500.000</strong> akan langsung otomatis masuk ke <strong>Saldo Iklan Toko</strong> Anda saat toko berhasil diaktifkan. Anda dapat melihatnya langsung di menu <em>Marketing &amp; Promosi &rarr; Iklan Toko</em>. Saldo ini berfungsi sebagai modal promosi untuk memasang iklan tanpa biaya pribadi.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-[#0284c7] text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-sm">2</span>
                            <div>
                                <strong class="text-slate-900 dark:text-white text-xs sm:text-sm">Bagaimana Cara Kunjungan +30% Masuk ke Toko?</strong>
                                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 mt-0.5 leading-relaxed">
                                    Setiap produk yang Anda iklankan dengan saldo promo ini otomatis diprioritaskan di <strong>posisi teratas hasil pencarian katalog</strong> dan beranda marketplace, serta diberi <strong>Badge Khusus "Iklan"</strong> dengan indikator status aktif. Hal ini menarik minat pembeli hingga menghasilkan rata-rata kenaikan kunjungan toko <strong>+30%</strong>.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-[#0284c7] text-white font-black text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-sm">3</span>
                            <div>
                                <strong class="text-slate-900 dark:text-white text-xs sm:text-sm">Estimasi Penjualan Rp1.350.000 - Rp2.425.000:</strong>
                                <p class="text-[11px] sm:text-xs text-slate-600 dark:text-slate-400 mt-0.5 leading-relaxed">
                                    Kombinasi modal saldo Rp500.000 dan lonjakan trafik pembeli tertarget memungkinkan toko baru meraih omzet rata-rata di atas 1,3 juta hingga 2,4 juta rupiah sejak minggu pertama aktif.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Tombol Ganda -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="closePromo(true)" class="text-xs font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        Jangan tampilkan lagi
                    </button>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="button" @click="showDetails = !showDetails" class="flex-1 sm:flex-none px-4 py-2.5 rounded-xl border border-sky-300 dark:border-slate-700 text-xs font-bold text-sky-700 dark:text-sky-300 hover:bg-sky-50 dark:hover:bg-slate-800 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]" x-text="showDetails ? 'expand_less' : 'info'"></span>
                            <span x-text="showDetails ? 'Tutup Rincian' : 'Lihat Rincian'"></span>
                        </button>
                        <button type="button" @click="closePromo(); showQuickCreateModal = true;" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-bold shadow-md hover:shadow-lg transition-all text-center flex items-center justify-center gap-2 cursor-pointer" style="background: #0284c7 !important; color: #ffffff !important;">
                            <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                            <span>Buka Toko &amp; Klaim Rp500.000</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- QUICK STORE CREATION MODAL (1-Click Aktifkan Toko) -->
    <!-- ========================================================================= -->
    <div x-show="showQuickCreateModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-[#161b22] w-full max-w-md rounded-3xl p-6 md:p-8 shadow-2xl border border-slate-200 dark:border-slate-800 relative"
             @click.away="showQuickCreateModal = false">
            
            <button type="button" @click="showQuickCreateModal = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>

            <div class="w-12 h-12 rounded-2xl bg-sky-100 dark:bg-sky-950/60 text-sky-600 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-[28px]">storefront</span>
            </div>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold mb-2">
                <span class="material-symbols-outlined text-[15px]">redeem</span>
                <span>Bonus Saldo Iklan Rp500.000 Langsung Aktif</span>
            </div>

            <h3 class="text-lg font-black text-slate-900 dark:text-white">
                Buka Toko Anda Sekarang
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                Tentukan nama toko Anda. Setelah disimpan, tokomu langsung aktif dan otomatis menerima <strong>Saldo Iklan Rp500.000 gratis</strong> untuk mendongkrak kunjungan toko hingga +30%!
            </p>

            <form action="{{ route('tenant.store.store') }}" method="POST" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Nama Toko Anda <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           x-model="storeName"
                           required
                           placeholder="Contoh: Digital Karya Store"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs md:text-sm font-semibold focus:ring-2 focus:ring-sky-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Deskripsi Singkat (Opsional)
                    </label>
                    <textarea name="description" rows="2" placeholder="Menyediakan source code aplikasi web & mobile berkualitas"
                              class="w-full px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white font-extrabold text-xs md:text-sm shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer" style="background: #0284c7 !important; color: #ffffff !important;">
                        <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                        <span>Aktifkan Toko &amp; Dapatkan Saldo Rp500.000</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
