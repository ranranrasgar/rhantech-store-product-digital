@extends('layouts.tenant')

@section('title', 'Dashboard Kreator & Toko Digital')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f8fb] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
        showPromoModal: true,
        showQuickCreateModal: false,
        showDetails: false,
        storeName: '{{ auth()->user()->name }} Studio',
        storeMode: 'hybrid',
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
        
        <!-- Welcome Title & Status Pill -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-100 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 text-xs font-bold mb-2 border border-sky-200 dark:border-sky-800/60">
                    <span class="w-2 h-2 rounded-full bg-sky-500 animate-ping"></span>
                    Hub Kreator &amp; Bisnis Digital 2026
                </div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                    Halo, {{ explode(' ', auth()->user()->name)[0] ?? 'Kreator' }}! 👋
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-xl">
                    Kelola akun dan pembelian Anda, atau aktifkan halaman publik Anda (Toko Digital, Bio Link ala Lynk.id, &amp; Portofolio) dalam 1 link.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" @click="showPromoModal = true" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#161b22] border border-sky-200 dark:border-slate-700 text-sky-600 dark:text-sky-400 text-xs font-bold shadow-xs hover:bg-sky-50 dark:hover:bg-slate-800 transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">card_giftcard</span>
                    <span>Klaim Bonus Rp500.000</span>
                </button>
                <button type="button" @click="showQuickCreateModal = true" class="px-5 py-2.5 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-bold shadow-md hover:shadow-sky-500/20 transition-all flex items-center gap-1.5 transform hover:-translate-y-0.5 cursor-pointer" style="background: #0284c7 !important; color: #ffffff !important;">
                    <span class="material-symbols-outlined text-[18px]">hub</span>
                    <span>Buat Toko &amp; Bio Link</span>
                </button>
            </div>
        </div>

        <!-- HERO CAMPAIGN BANNER: Clean, Simple, Modern (Tanpa Gradien Berlebihan) -->
        <div class="relative rounded-2xl md:rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#161b22] p-5 sm:p-7 md:p-9 shadow-xs">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                
                <!-- Kiri: Copywriting & CTA -->
                <div class="lg:col-span-8 space-y-4">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md bg-sky-50 dark:bg-sky-950/60 border border-sky-200 dark:border-sky-800 text-sky-700 dark:text-sky-300 text-xs font-bold">
                        <span class="material-symbols-outlined text-[15px]">hub</span>
                        <span>Satu Halaman: Toko Digital, Bio Link &amp; Portofolio</span>
                    </div>

                    <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 dark:text-white leading-snug">
                        Toko Digital, Bio Link &amp; Portofolio dalam <span class="text-sky-600 dark:text-sky-400">Satu Profil</span>
                    </h2>

                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-w-xl">
                        Bukan sekadar toko biasa. Jual source code &amp; aset digital, sematkan link medsos/WhatsApp ala Lynk.id, dan pamerkan portofolio karyamu dalam 1 tautan profil yang elegan.
                    </p>

                    <!-- Feature Pills (Flat, Clean, Minimalist) -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1">
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/60 px-3 py-2 rounded-xl border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                            <span class="material-symbols-outlined text-sky-600 dark:text-sky-400 text-[16px]">storefront</span>
                            <span>Toko Digital</span>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/60 px-3 py-2 rounded-xl border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                            <span class="material-symbols-outlined text-sky-600 dark:text-sky-400 text-[16px]">link</span>
                            <span>Bio Link Medsos</span>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/60 px-3 py-2 rounded-xl border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                            <span class="material-symbols-outlined text-sky-600 dark:text-sky-400 text-[16px]">folder_special</span>
                            <span>Portofolio Karya</span>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-800/60 px-3 py-2 rounded-xl border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-semibold text-slate-700 dark:text-slate-200">
                            <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[16px]">card_giftcard</span>
                            <span>Bonus Rp500rb</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2 flex flex-wrap items-center gap-3">
                        <button type="button" @click="showQuickCreateModal = true" class="px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs sm:text-sm shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">hub</span>
                            <span>Buat Toko &amp; Bio Link</span>
                        </button>
                        <button type="button" @click="showPromoModal = true" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs sm:text-sm transition-colors flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">info</span>
                            <span>Pelajari Keuntungan</span>
                        </button>
                    </div>
                </div>

                <!-- Kanan: Modern Flat Metric Cards -->
                <div class="lg:col-span-4 flex flex-col gap-3">
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/80">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Bonus Modal Promosi</span>
                            <span class="material-symbols-outlined text-sky-600 dark:text-sky-400 text-[18px]">redeem</span>
                        </div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">Rp 500.000</div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 leading-normal">
                            Saldo otomatis aktif untuk promosi katalog &amp; halaman profil Anda di platform.
                        </p>
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700/80">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jangkauan Kunjungan</span>
                            <span class="px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-400 text-[10px] font-extrabold">+30% Naik</span>
                        </div>
                        <p class="text-xs font-semibold text-slate-700 dark:text-slate-300 mt-1.5 leading-normal">
                            Multi-fitur terintegrasi: Bio Link media sosial, etalase produk &amp; tawaran jasa.
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- 3 PILIHAN MODE TAMPILAN SESUAI KEBUTUHAN -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[22px]">palette</span>
                        <span>Satu Halaman, Berbagai Mode Tampilan Fleksibel</span>
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Pilih gaya tampilan halaman publik Anda sesuai kebutuhan bisnis atau persona kreator Anda.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Mode 1: Hybrid -->
                <div class="bg-white dark:bg-[#161b22] border-2 border-sky-400 dark:border-sky-500/80 rounded-2xl p-5 shadow-xs relative flex flex-col justify-between">
                    <span class="absolute -top-2.5 right-4 px-2 py-0.5 bg-sky-500 text-white text-[9px] font-black rounded-full uppercase tracking-wider">
                        Paling Direkomendasikan
                    </span>
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[22px]">layers</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Mode Hybrid (Toko + Bio Link)</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Gabungan terbaik! Menampilkan tombol link medsos/portofolio ala Lynk.id di bagian atas, dan etalase katalog produk digital di bawahnya.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-[11px] font-semibold text-sky-600 dark:text-sky-400 flex items-center gap-1">
                        <span>Cocok untuk: Kreator, Developer &amp; Desainer</span>
                    </div>
                </div>

                <!-- Mode 2: Bio Link & Portofolio -->
                <div class="bg-white dark:bg-[#161b22] border border-slate-200/90 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition-colors">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[22px]">link</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Mode Bio Link &amp; Portofolio</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Tampilan simpel &amp; fokus untuk bio link Instagram, TikTok, dan kartu nama digital. Menampilkan link penting, kontak WhatsApp, dan portofolio.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                        <span>Cocok untuk: Influencer &amp; Freelancer Jasa</span>
                    </div>
                </div>

                <!-- Mode 3: Toko Klasik -->
                <div class="bg-white dark:bg-[#161b22] border border-slate-200/90 dark:border-slate-800 rounded-2xl p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition-colors">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[22px]">storefront</span>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Mode Toko Digital Penuh</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Marketplace toko klasik dengan banner besar, tab kategori produk, pencarian barang, dan sistem voucher diskon toko.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 flex items-center gap-1">
                        <span>Cocok untuk: Toko Software &amp; Template Digital</span>
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
                <button type="button" @click="showQuickCreateModal = true" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1 self-start sm:self-center cursor-pointer">
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
    <!-- POPUP MODAL: Aktifkan Halaman Toko & Bio Link (Simple, Jelas, Modern, No Gradien) -->
    <!-- ========================================================================= -->
    <div x-show="showPromoModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-[#161b22] w-full max-w-lg rounded-2xl sm:rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 relative max-h-[92vh] flex flex-col overflow-hidden"
             @click.away="closePromo()">
            
            <!-- Tombol Close (X) -->
            <button type="button" 
                    @click="closePromo()" 
                    class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition-colors cursor-pointer"
                    aria-label="Tutup">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>

            <!-- Modal Content Body -->
            <div class="overflow-y-auto p-5 sm:p-7 space-y-4 sm:space-y-5">

                <!-- Header: Icon & Title -->
                <div class="pr-6">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-sky-50 dark:bg-sky-950/60 border border-sky-200 dark:border-sky-800 text-sky-700 dark:text-sky-300 text-[11px] font-bold tracking-wide mb-2">
                        <span class="material-symbols-outlined text-[14px]">stars</span>
                        <span>Bonus Spesial Akun Baru</span>
                    </div>

                    <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight leading-snug">
                        Aktifkan Toko &amp; Bio Link Anda
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Satu link serbaguna untuk menjual produk digital, membagikan bio link media sosial ala Lynk.id, dan menampilkan portofolio Anda.
                    </p>
                </div>

                <!-- Highlight Bonus Card (Clean Flat, No Gradient) -->
                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-sky-600 text-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">card_giftcard</span>
                        </div>
                        <div>
                            <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400">Bonus Modal Promosi</div>
                            <div class="text-sm font-black text-slate-900 dark:text-white">Rp 500.000</div>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-md bg-emerald-100 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-300 text-[10px] font-extrabold tracking-wide shrink-0">
                        +30% Kunjungan
                    </span>
                </div>

                <!-- 3 Fitur Utama (Clean Compact List, Legible on Mobile) -->
                <div class="space-y-2">
                    <div class="flex items-start gap-3 p-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">storefront</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Toko Produk Digital</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-normal">
                                Jual source code, template, &amp; aset digital dengan transaksi otomatis.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">link</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Bio Link Media Sosial</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-normal">
                                Tautan ringkas untuk profil Instagram, TikTok, dan kontak WhatsApp.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-2.5 rounded-xl bg-white dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-outlined text-[18px]">folder_special</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white">Portofolio &amp; Jasa Kustom</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 leading-normal">
                                Pamerkan karya proyek &amp; terima order pengerjaan langsung dari klien.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Penjelasan Rincian Detail (Collapsible) -->
                <div>
                    <button type="button" 
                            @click="showDetails = !showDetails" 
                            class="text-[11px] font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]" x-text="showDetails ? 'expand_less' : 'expand_more'"></span>
                        <span x-text="showDetails ? 'Sembunyikan Mekanisme' : 'Pelajari Cara Kerja Saldo Rp500.000'"></span>
                    </button>

                    <div x-show="showDetails" x-transition class="mt-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-left space-y-2 text-[11px] text-slate-600 dark:text-slate-300 leading-relaxed">
                        <p>
                            • <strong>Saldo Iklan Rp500.000:</strong> Kredit promosi gratis di platform untuk mendongkrak profil &amp; produk Anda ke posisi teratas katalog pencarian.
                        </p>
                        <p>
                            • <strong>Penjualan 100% Milik Anda:</strong> Seluruh hasil transaksi penjualan langsung masuk ke dompet akun dan dapat dicairkan instan ke rekening bank Anda.
                        </p>
                    </div>
                </div>

                <!-- Footer Buttons & Options -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-2.5">
                    <button type="button" 
                            @click="closePromo(); showQuickCreateModal = true;" 
                            class="w-full py-3 rounded-xl bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-bold text-xs sm:text-sm shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">hub</span>
                        <span>Aktifkan Halaman Sekarang (Gratis)</span>
                    </button>

                    <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 px-1 pt-0.5">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" 
                                   @change="closePromo(true)" 
                                   class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span>Jangan tampilkan lagi</span>
                        </label>
                        <button type="button" @click="closePromo()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-medium cursor-pointer">
                            Nanti Saja
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- QUICK CREATION MODAL (1-Click Aktifkan Toko & Bio Link) -->
    <!-- ========================================================================= -->
    <div x-show="showQuickCreateModal"
         style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        <div class="bg-white dark:bg-[#161b22] w-full max-w-md rounded-2xl sm:rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 relative max-h-[92vh] flex flex-col overflow-hidden"
             @click.away="showQuickCreateModal = false">
            
            <button type="button" 
                    @click="showQuickCreateModal = false" 
                    class="absolute top-3.5 right-3.5 z-20 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition-colors cursor-pointer"
                    aria-label="Tutup">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>

            <div class="overflow-y-auto p-5 sm:p-7 space-y-4">
                <div class="pr-6">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold mb-2">
                        <span class="material-symbols-outlined text-[15px]">redeem</span>
                        <span>Bonus Saldo Iklan Rp500.000 Langsung Aktif</span>
                    </div>

                    <h3 class="text-lg font-black text-slate-900 dark:text-white">
                        Buat Halaman Toko &amp; Bio Link
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                        Tentukan nama brand atau akun Anda. Halaman langsung aktif dan siap digunakan.
                    </p>
                </div>

                <form action="{{ route('tenant.store.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Nama Toko / Brand / Akun Kreator <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               x-model="storeName"
                               required
                               placeholder="Contoh: Digital Karya Studio"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-sky-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Mode Tampilan Utama
                        </label>
                        <select name="store_mode" 
                                x-model="storeMode"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-semibold focus:ring-2 focus:ring-sky-500 focus:outline-none">
                            <option value="hybrid">Mode Hybrid (Toko + Bio Link Ala Lynk.id) — Rekomendasi</option>
                            <option value="profile">Mode Bio Link &amp; Portofolio (Simpel)</option>
                            <option value="store">Mode Toko Digital Penuh (Katalog Klasik)</option>
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">Dapat diubah kapan saja di menu Pengaturan.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Bio Singkat Profil / Toko (Opsional)
                        </label>
                        <textarea name="description" rows="2" placeholder="Menyediakan source code aplikasi web &amp; mobile berkualitas serta jasa kustom proyek"
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-sky-500 focus:outline-none">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-start gap-2 pt-1">
                        <input type="checkbox" name="agree_terms" id="agree_terms_modal" value="1" required class="mt-0.5 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        <label for="agree_terms_modal" class="text-[11px] text-slate-500 dark:text-slate-400 leading-normal">
                            Saya menyetujui <a href="{{ route('legal.terms') }}" target="_blank" class="text-sky-600 dark:text-sky-400 hover:underline">Syarat &amp; Ketentuan</a> dan Kebijakan Hak Cipta &amp; Regulasi RI.
                        </label>
                    </div>
                    
                    @if($errors->has('agree_terms'))
                        <p class="text-rose-500 text-[10px] mt-1">{{ $errors->first('agree_terms') }}</p>
                    @endif

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 rounded-xl bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white font-bold text-xs sm:text-sm shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">hub</span>
                            <span>Aktifkan Halaman &amp; Klaim Saldo Rp500.000</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
