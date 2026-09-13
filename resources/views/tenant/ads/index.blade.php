@extends('layouts.tenant')

@section('title', 'Iklan & Promosi Toko - Rhantech Seller Center')

@section('content')
<div class="flex-1 overflow-y-auto p-3.5 sm:p-4 md:p-8 bg-[#f5f6f8] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
        activeMobileTab: 'all',
        showZeroBalanceModal: {{ $hasZeroBalance ? 'true' : 'false' }},
        showPromoModal: false,
        init() {
            // Cek jika modal saldo habis sudah di-dismiss dalam sesi ini
            if (sessionStorage.getItem('dismissed_zero_balance_modal')) {
                this.showZeroBalanceModal = false;
            }

            // Aturan Modal Promo: CUKUP 1 KALI SAJA saat pertama kali buka halaman iklan
            const hasSeenPromo = localStorage.getItem('seen_seller_promo_modal');
            const hasClaimedVoucher = {{ (isset($hasClaimedWelcomeVoucher) && $hasClaimedWelcomeVoucher) ? 'true' : 'false' }};

            if (!hasSeenPromo && !hasClaimedVoucher && !this.showZeroBalanceModal) {
                this.showPromoModal = true;
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

    <div class="max-w-7xl mx-auto space-y-5 md:space-y-6">

        <!-- Notification Alerts -->
        @if(session('success'))
            <div class="p-3.5 md:p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs md:text-sm flex items-center gap-2.5 shadow-xs">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-3.5 md:p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-xs md:text-sm flex items-center gap-2.5 shadow-xs">
                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[20px]">warning</span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 md:p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs md:text-sm flex items-center gap-2.5 shadow-xs">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-[20px]">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- 1. DEDICATED NATIVE MOBILE EXPERIENCE (Visible on Mobile < 768px Only)   -->
        <!-- ========================================================================= -->
        <div class="block md:hidden space-y-4 pb-12">
            
            <!-- Mobile App Bar / Header -->
            <div class="pt-1">
                <h1 class="text-lg font-black text-slate-900 dark:text-white tracking-tight">Iklan & Promosi</h1>
                <p class="text-[11px] text-slate-500 dark:text-slate-400">Jangkau calon pembeli produk digital Anda</p>
            </div>

            <!-- Native Wallet & Balance Card -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-4 shadow-xs space-y-3.5">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400 font-semibold">
                            <span class="material-symbols-outlined text-[16px] text-[#00838f]">account_balance_wallet</span>
                            <span>Saldo Iklan Toko</span>
                        </div>
                        <div class="text-2xl font-black mt-0.5 {{ $adBalance <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white' }} tracking-tight">
                            Rp{{ number_format($adBalance, 0, ',', '.') }}
                        </div>
                    </div>
                    <a href="{{ route('tenant.ads.top-up') }}" class="px-4 py-2 bg-[#00838f] hover:bg-[#00727d] text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-1 shrink-0 active:scale-95">
                        <span class="material-symbols-outlined text-[16px]">payments</span>
                        <span>Isi Saldo</span>
                    </a>
                </div>

                @if($adBalance <= 0)
                <div class="p-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-500 text-[16px] shrink-0">error</span>
                    <p class="text-[11px] text-rose-700 dark:text-rose-300 leading-snug font-medium">
                        Iklan dijeda karena saldo Rp0. Isi saldo untuk mengaktifkan kembali.
                    </p>
                </div>
                @endif

                <div class="pt-3 border-t border-slate-100 dark:border-[#222f49] grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-[11px] text-slate-400 block">Biaya Hari Ini</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">Rp{{ number_format($todaySpent, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] text-slate-400 block">Status Iklan</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $activeAdsCount }} Aktif</span>
                        <span class="text-slate-400 text-[11px]">/ {{ $totalAdsCount }} Total</span>
                    </div>
                </div>
            </div>

            <!-- Native Horizontal Snap Carousel (Promo Voucher & Quick Guide) -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Program & Bantuan Iklan</span>
                    <span class="text-[10px] text-slate-400">Geser untuk info »</span>
                </div>
                <div class="flex gap-3 overflow-x-auto pb-1 snap-x scrollbar-none" style="scrollbar-width: none; -ms-overflow-style: none;">
                    
                    <!-- Card 1: Voucher Disubsidi Platform -->
                    <div class="snap-start shrink-0 w-[280px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-3.5 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="px-2 py-0.5 rounded-full bg-teal-50 dark:bg-teal-950/50 text-[#00838f] dark:text-teal-400 text-[10px] font-bold">Voucher Toko</span>
                                <span class="material-symbols-outlined text-[#00838f] text-[18px]">redeem</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug">Voucher Disubsidi Platform Rp500.000</h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Dukungan promosi dari platform untuk toko Anda.</p>
                        </div>
                        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-[#222f49]">
                            @if(isset($hasClaimedWelcomeVoucher) && $hasClaimedWelcomeVoucher)
                                <div class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">verified</span>
                                    <span>Bonus Aktif di Saldo</span>
                                </div>
                            @else
                                <form action="{{ route('tenant.ads.claim-voucher') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-1.5 px-3 rounded-lg bg-[#00838f] text-white text-[11px] font-bold shadow-xs cursor-pointer text-center">
                                        Klaim Sekarang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Card 2: Buku Panduan Bidding -->
                    <div class="snap-start shrink-0 w-[260px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-3.5 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="px-2 py-0.5 rounded-full bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 text-[10px] font-bold">Panduan Rumus</span>
                                <span class="material-symbols-outlined text-sky-600 text-[18px]">menu_book</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug">Cara Kerja Saldo & Bidding Iklan</h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Pelajari impresi gratis dan pemotongan biaya per klik.</p>
                        </div>
                        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-[#222f49]">
                            <a href="{{ route('help.show', 'panduan-rumus-modal-iklan-harga-bid-per-klik-cpc-sistem-pemotongan-saldo') }}" target="_blank" class="w-full py-1.5 px-3 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#00838f] hover:text-white text-[11px] font-bold text-center block transition-colors">
                                Baca Panduan
                            </a>
                        </div>
                    </div>

                    <!-- Card 3: Optimasi Kata Kunci -->
                    <div class="snap-start shrink-0 w-[260px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-3.5 shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold">Tips Konversi</span>
                                <span class="material-symbols-outlined text-emerald-600 text-[18px]">trending_up</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug">Pasang Kata Kunci Terpopuler</h4>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-1">Mencocokkan kata pencarian pembeli untuk +25% closing.</p>
                        </div>
                        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-[#222f49]">
                            <a href="{{ route('tenant.ads.create') }}" class="w-full py-1.5 px-3 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-[#00838f] hover:text-white text-[11px] font-bold text-center block transition-colors">
                                Mulai Pasang
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Native Pill Tabs Filter -->
            <div class="flex items-center gap-2 p-1 bg-slate-200/60 dark:bg-slate-800/80 rounded-xl">
                <button type="button" @click="activeMobileTab = 'all'" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-center" :class="activeMobileTab === 'all' ? 'bg-white dark:bg-[#111726] text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400'">
                    Semua ({{ $totalAdsCount }})
                </button>
                <button type="button" @click="activeMobileTab = 'active'" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-center" :class="activeMobileTab === 'active' ? 'bg-white dark:bg-[#111726] text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400'">
                    Berjalan ({{ $activeAdsCount }})
                </button>
                <button type="button" @click="activeMobileTab = 'paused'" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-center" :class="activeMobileTab === 'paused' ? 'bg-white dark:bg-[#111726] text-slate-900 dark:text-white shadow-xs' : 'text-slate-600 dark:text-slate-400'">
                    Dijeda ({{ $pausedAdsCount }})
                </button>
            </div>

            <!-- Native Ads Card Feed -->
            <div class="space-y-3">
                @forelse($ads as $ad)
                <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-4 shadow-xs space-y-3"
                     x-show="activeMobileTab === 'all' || (activeMobileTab === 'active' && '{{ $ad->status }}' === 'active') || (activeMobileTab === 'paused' && '{{ $ad->status }}' !== 'active')">
                    
                    <!-- Card Top Header -->
                    <div class="flex items-start gap-3">
                        <div class="w-13 h-13 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                            @if($ad->product && $ad->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $ad->product->images->first()->image_path) }}" alt="{{ $ad->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-slate-400 text-[24px]">storefront</span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-1.5 mb-1">
                                <span class="text-[9px] font-extrabold uppercase px-1.5 py-0.2 rounded bg-teal-50 dark:bg-teal-950/50 text-[#00838f] dark:text-teal-400 border border-teal-100 dark:border-teal-900/50">IKLAN</span>
                                @if($ad->status === 'active')
                                    @if($adBalance > 0)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Berjalan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                                            Saldo Habis
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                        Dijeda
                                    </span>
                                @endif
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1">{{ $ad->name }}</h4>
                            @if($ad->product)
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                    {{ $ad->product->name }} • <strong class="text-emerald-600 dark:text-emerald-400">Rp{{ number_format($ad->product->price, 0, ',', '.') }}</strong>
                                </p>
                            @else
                                <p class="text-[11px] text-[#00838f] font-semibold">Iklan Profil Toko</p>
                            @endif
                        </div>
                    </div>

                    <!-- 3 Mini Stats Box -->
                    <div class="grid grid-cols-3 gap-2 p-2.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-100 dark:border-slate-800 text-center">
                        <div>
                            <span class="text-[10px] text-slate-400 block">Tayang</span>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200">{{ number_format($ad->views_count) }}</span>
                        </div>
                        <div class="border-x border-slate-200 dark:border-slate-800">
                            <span class="text-[10px] text-slate-400 block">Klik</span>
                            <span class="text-xs font-black text-[#00838f] dark:text-teal-400">{{ number_format($ad->clicks_count) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block">Biaya</span>
                            <span class="text-xs font-black text-slate-800 dark:text-slate-200">Rp{{ number_format($ad->spent_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Target & Budget Info -->
                    <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 px-0.5">
                        <div class="truncate flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">tune</span>
                            <span>{{ $ad->bidding_mode === 'auto' ? 'Bidding Otomatis' : 'Rp' . number_format($ad->bid_price, 0, ',', '.') . '/klik' }}</span>
                        </div>
                        <div class="truncate">
                            Budget: <strong class="text-slate-700 dark:text-slate-300">{{ $ad->budget_type === 'unlimited' ? 'Tak Terbatas' : 'Rp' . number_format($ad->daily_budget, 0, ',', '.') . '/hr' }}</strong>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 border-t border-slate-100 dark:border-[#222f49] flex items-center justify-between gap-2">
                        <form action="{{ route('tenant.ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iklan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="py-1.5 px-2.5 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 text-xs font-semibold flex items-center gap-1 transition-colors cursor-pointer">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                <span>Hapus</span>
                            </button>
                        </form>

                        <form action="{{ route('tenant.ads.toggle', $ad->id) }}" method="POST" class="flex-1 max-w-[140px]">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full py-1.5 px-3 rounded-xl text-xs font-bold transition-all shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer {{ $ad->status === 'active' ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800' : 'bg-[#00838f] text-white hover:bg-[#00727d]' }}">
                                <span class="material-symbols-outlined text-[16px]">{{ $ad->status === 'active' ? 'pause' : 'play_arrow' }}</span>
                                <span>{{ $ad->status === 'active' ? 'Jeda Iklan' : 'Aktifkan' }}</span>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-8 text-center shadow-xs">
                    <div class="w-14 h-14 rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-[#00838f] flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-[28px]">ads_click</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Belum Ada Iklan</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Promosikan produk digital Anda agar muncul di hasil pencarian teratas pembeli.</p>
                    <a href="{{ route('tenant.ads.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-[#00838f] text-white text-xs font-bold rounded-xl shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">add</span> Buat Iklan Sekarang
                    </a>
                </div>
                @endforelse
            </div>

            @if($ads->hasPages())
                <div class="pt-2">
                    {{ $ads->links() }}
                </div>
            @endif

            <!-- Mobile Floating Action Button (+) Buat Iklan (Bawah Kanan - No Shadow, Lightweight Animation) -->
            <style>
                @keyframes fabFloatAnim {
                    0%, 100% { transform: translateY(0) scale(1); }
                    50% { transform: translateY(-5px) scale(1.03); }
                }
                .fab-animated {
                    animation: fabFloatAnim 2.4s ease-in-out infinite;
                    will-change: transform;
                }
                .fab-animated:active {
                    animation: none;
                    transform: scale(0.92);
                }
            </style>
            <a href="{{ route('tenant.ads.create') }}" 
               class="fab-animated fixed bottom-20 right-4 z-40 md:hidden w-14 h-14 rounded-full bg-[#00838f] hover:bg-[#00727d] text-white border-2 border-white dark:border-slate-800 flex items-center justify-center cursor-pointer select-none group"
               title="Buat Iklan Baru"
               aria-label="Buat Iklan Baru">
                <span class="material-symbols-outlined text-[30px] font-bold transition-transform duration-300 group-hover:rotate-90">add</span>
            </a>
        </div>

        <!-- ========================================================================= -->
        <!-- 2. PRESERVED DESKTOP VIEW (Visible on Desktop >= 768px Only)              -->
        <!-- ========================================================================= -->
        <div class="hidden md:block space-y-6">
            
            <!-- Breadcrumb & Title Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <a href="{{ route('tenant.dashboard') }}" class="hover:text-[#00838f] transition-colors">Beranda</a>
                    <span>&gt;</span>
                    <span class="text-slate-800 dark:text-slate-200 font-semibold">Iklan Toko & Promosi</span>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('tenant.ads.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#00838f] hover:bg-[#00727d] text-white rounded-xl text-xs md:text-sm font-bold shadow-xs transition-all transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Buat Iklan Baru
                    </a>
                </div>
            </div>

            <!-- Card Saldo Saya & Program Spesial Toko (Clean Solid Cards) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <!-- Kolom Kiri: Saldo Saya (2 Span) -->
                <div class="lg:col-span-2 bg-white dark:bg-[#111726] rounded-2xl p-5 md:p-6 border border-slate-200/90 dark:border-[#222f49] shadow-xs relative overflow-hidden">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#00838f] text-[20px]">account_balance_wallet</span>
                            <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Saldo Saya</h2>
                        </div>
                        <a href="{{ route('tenant.ads.top-up') }}" class="text-xs font-semibold text-[#00838f] hover:underline flex items-center gap-0.5">
                            Lihat Rincian <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 mb-4 border-b border-slate-100 dark:border-[#222f49]">
                        <div>
                            <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                <span>Saldo Iklan</span>
                                <a href="{{ route('help.show', 'cara-kerja-saldo-iklan-bonus-saldo-rp500000') }}" target="_blank" class="text-slate-400 hover:text-[#00838f] transition-colors" title="Pelajari cara kerja saldo iklan & bonus Rp500.000">
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
                        <a href="{{ route('tenant.ads.top-up') }}" class="px-6 py-2.5 bg-[#00838f] hover:bg-[#00727d] text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">payments</span>
                            Isi Saldo
                        </a>
                        <button type="button" @click="showPromoModal = true" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition-all flex items-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px] text-amber-500">card_giftcard</span>
                            Panduan & Promo Spesial
                        </button>
                        <a href="{{ route('help.show', 'panduan-rumus-modal-iklan-harga-bid-per-klik-cpc-sistem-pemotongan-saldo') }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs sm:text-sm font-semibold rounded-xl transition-all flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-[#00838f]">menu_book</span>
                            Buku Panduan Iklan
                        </a>
                    </div>
                </div>

                <!-- Kolom Kanan: Voucher & Program Spesial Toko (Solid Clean Card) -->
                <div class="bg-white dark:bg-[#111726] rounded-2xl p-5 md:p-6 border border-slate-200/90 dark:border-[#222f49] shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-teal-50 dark:bg-teal-950/50 text-[#00838f] dark:text-teal-400 text-[11px] font-bold mb-3 border border-teal-100 dark:border-teal-900/40">
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

                    <div class="mt-5 pt-4 border-t border-slate-100 dark:border-[#222f49]">
                        @if(isset($hasClaimedWelcomeVoucher) && $hasClaimedWelcomeVoucher)
                        <div class="space-y-2">
                            <div class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                <span class="material-symbols-outlined text-[18px]">verified</span>
                                <span>Bonus Rp500.000 Aktif di Saldo Iklan</span>
                            </div>
                            <a href="{{ route('tenant.ads.create') }}" class="w-full py-2.5 px-4 rounded-xl bg-[#00838f] hover:bg-[#00727d] text-white text-xs sm:text-sm font-bold text-center block shadow-xs transition-all">
                                Pasang Iklan Sekarang
                            </a>
                        </div>
                        @else
                        <form action="{{ route('tenant.ads.claim-voucher') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 px-4 rounded-xl bg-[#00838f] hover:bg-[#00727d] text-white text-xs sm:text-sm font-bold text-center flex items-center justify-center gap-2 shadow-xs transition-all cursor-pointer">
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
                        <span class="w-2 h-5 bg-[#00838f] rounded-full"></span>
                        <h3 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100">
                            Rekomendasi untuk Mengoptimalkan Iklan
                        </h3>
                    </div>
                    <span class="text-xs text-slate-500 dark:text-slate-400">4 Rekomendasi Aktif</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Card Rekomendasi 1 -->
                    <div class="bg-white dark:bg-[#111726] p-4 rounded-2xl border border-slate-200/90 dark:border-[#222f49] shadow-xs flex flex-col justify-between hover:border-[#00838f]/50 transition-colors">
                        <div>
                            <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/50 text-[#00838f] flex items-center justify-center mb-3">
                                <span class="material-symbols-outlined text-[20px]">auto_graph</span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-snug line-clamp-2">
                                Aktifkan Isi Saldo Otomatis dari Penghasilan Toko
                            </h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 line-clamp-2">
                                Mencegah iklan terhenti di tengah pencarian saat kuota saldo habis.
                            </p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#222f49]">
                            <a href="{{ route('tenant.ads.top-up') }}" class="w-full py-1.5 px-3 rounded-lg bg-teal-50 dark:bg-teal-950/40 text-[#00838f] hover:bg-[#00838f] hover:text-white text-xs font-bold text-center block transition-all">
                                Atur Saldo
                            </a>
                        </div>
                    </div>

                    <!-- Card Rekomendasi 2 -->
                    <div class="bg-white dark:bg-[#111726] p-4 rounded-2xl border border-slate-200/90 dark:border-[#222f49] shadow-xs flex flex-col justify-between hover:border-[#00838f]/50 transition-colors">
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
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#222f49]">
                            <a href="{{ route('tenant.ads.create') }}" class="w-full py-1.5 px-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white text-xs font-bold text-center block transition-all">
                                Pasang Kata Kunci
                            </a>
                        </div>
                    </div>

                    <!-- Card Rekomendasi 3 -->
                    <div class="bg-white dark:bg-[#111726] p-4 rounded-2xl border border-slate-200/90 dark:border-[#222f49] shadow-xs flex flex-col justify-between hover:border-[#00838f]/50 transition-colors">
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
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#222f49]">
                            <a href="{{ route('tenant.ads.create') }}" class="w-full py-1.5 px-3 rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 hover:bg-sky-600 hover:text-white text-xs font-bold text-center block transition-all">
                                Terapkan Sekarang
                            </a>
                        </div>
                    </div>

                    <!-- Card Rekomendasi 4 -->
                    <div class="bg-white dark:bg-[#111726] p-4 rounded-2xl border border-slate-200/90 dark:border-[#222f49] shadow-xs flex flex-col justify-between hover:border-[#00838f]/50 transition-colors">
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
                        <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#222f49]">
                            <button type="button" @click="showPromoModal = true" class="w-full py-1.5 px-3 rounded-lg bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 hover:bg-purple-600 hover:text-white text-xs font-bold text-center block transition-all cursor-pointer">
                                Pelajari Syarat
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Iklan Toko & Produk (Desktop Table) -->
            <div class="bg-white dark:bg-[#111726] rounded-2xl border border-slate-200/90 dark:border-[#222f49] shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-[#222f49] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
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
                            <tr class="bg-slate-50/80 dark:bg-[#0c1220] border-b border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-400 font-bold uppercase tracking-wider">
                                <th class="py-3.5 px-5">Iklan / Produk</th>
                                <th class="py-3.5 px-4">
                                    <div class="flex items-center gap-1">
                                        <span>Tipe & Target</span>
                                        <a href="{{ route('help.show', 'panduan-memasang-iklan-produk-bidding-manual-vs-otomatis') }}" target="_blank" class="text-slate-400 hover:text-[#00838f] transition-colors" title="Buka Panduan Bidding & Kata Kunci">
                                            <span class="material-symbols-outlined text-[14px] align-middle">help</span>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3.5 px-4">
                                    <div class="flex items-center gap-1">
                                        <span>Modal / Biaya</span>
                                        <a href="{{ route('help.show', 'panduan-rumus-modal-iklan-harga-bid-per-klik-cpc-sistem-pemotongan-saldo') }}" target="_blank" class="text-slate-400 hover:text-[#00838f] transition-colors" title="Buka Panduan Rumus Modal & Pemotongan Biaya">
                                            <span class="material-symbols-outlined text-[14px] align-middle">help</span>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">
                                    <div class="flex items-center gap-1">
                                        <span>Impresi / Klik</span>
                                        <a href="{{ route('help.show', 'panduan-rumus-modal-iklan-harga-bid-per-klik-cpc-sistem-pemotongan-saldo') }}" target="_blank" class="text-slate-400 hover:text-[#00838f] transition-colors" title="Buka Panduan Impresi Gratis & Biaya Klik (CPC)">
                                            <span class="material-symbols-outlined text-[14px] align-middle">help</span>
                                        </a>
                                    </div>
                                </th>
                                <th class="py-3.5 px-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#222f49]">
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
                                        <span class="material-symbols-outlined text-[13px] text-[#00838f]">date_range</span>
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
                                        <button type="submit" class="px-2.5 py-1 rounded-lg border text-xs font-semibold transition-colors cursor-pointer {{ $ad->status === 'active' ? 'border-amber-300 text-amber-700 hover:bg-amber-50 dark:hover:bg-amber-950/40' : 'border-emerald-300 text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}">
                                            {{ $ad->status === 'active' ? 'Jeda' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('tenant.ads.destroy', $ad->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kampanye iklan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-800 dark:text-rose-400 font-semibold text-xs transition-colors cursor-pointer">
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
                                    <a href="{{ route('tenant.ads.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 rounded-xl bg-[#00838f] text-white text-xs font-bold shadow-xs hover:bg-[#00727d] transition-all">
                                        <span class="material-symbols-outlined text-[16px]">add</span> Buat Iklan Produk Sekarang
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ads->hasPages())
                    <div class="p-4 border-t border-slate-100 dark:border-[#222f49]">
                        {{ $ads->links() }}
                    </div>
                @endif
            </div>

        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- POPUP MODAL 1: Saldo Habis                                                -->
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

        <div class="bg-white dark:bg-[#111726] w-full max-w-sm rounded-3xl p-6 text-center shadow-2xl border border-slate-200 dark:border-[#222f49] relative"
             @click.away="closeZeroBalanceModal()">
            
            <button type="button" @click="closeZeroBalanceModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>

            <div class="w-20 h-20 mx-auto mb-3 overflow-hidden flex items-center justify-center">
                <img src="{{ asset('images/empty_wallet_alert.jpg') }}" alt="Saldo Habis" class="w-full h-full object-contain drop-shadow-md rounded-2xl">
            </div>

            <h3 class="text-base font-black text-[#00838f]">
                Saldo habis, semua iklanmu dihentikan
            </h3>

            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                Saldo iklanmu <strong>Rp0</strong>. Semua iklan telah dihentikan. Silakan isi saldo agar iklanmu bisa tetap berjalan.
            </p>

            <div class="mt-6 space-y-2">
                <a href="{{ route('tenant.ads.top-up') }}" class="w-full py-2.5 px-4 bg-[#00838f] hover:bg-[#00727d] text-white text-xs font-bold rounded-xl shadow-xs transition-all block text-center">
                    Isi Saldo Sekarang
                </a>
                <button type="button" @click="closeZeroBalanceModal()" class="w-full py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:underline">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- POPUP MODAL 2: Voucher Spesial Toko (No Gradient Banners)                 -->
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

        <div class="bg-white dark:bg-[#111726] w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl border border-slate-200 dark:border-[#222f49] relative"
             @click.away="closePromoModal()">
            
            <button type="button" @click="closePromoModal()" class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-white/80 dark:bg-black/40 backdrop-blur-sm text-slate-500 hover:text-slate-800 dark:hover:text-white flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
            </button>

            <!-- Banner Header Solid Clean Illustration -->
            <div class="relative h-40 sm:h-48 w-full overflow-hidden bg-slate-900 flex items-center justify-center">
                <img src="{{ asset('images/sky_blue_gift_banner.jpg') }}" 
                     alt="Promo Iklan Toko Rhantech" 
                     class="w-full h-full object-cover">
            </div>

            <!-- Header Title -->
            <div class="px-6 md:px-8 text-center pt-5 pb-2">
                <h3 class="text-lg md:text-xl font-black text-[#00838f] tracking-tight">
                    Buat Iklan dan Dapatkan Voucher Spesial Toko!
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Dukungan promosi dari platform untuk meningkatkan omset toko digital Anda.</p>
            </div>

            <!-- Content Cards -->
            <div class="p-5 md:p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    
                    <!-- Card 1 -->
                    <div class="bg-slate-50 dark:bg-[#0c1220] p-3.5 rounded-2xl border border-slate-200/80 dark:border-slate-800 text-left">
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-1.5">1. Buat Iklan Produk</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Pilih produk unggulan, atur kata kunci, dan mulai tayang dengan biaya hemat per klik.
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="bg-slate-50 dark:bg-[#0c1220] p-3.5 rounded-2xl border border-slate-200/80 dark:border-slate-800 text-left">
                        <div class="text-[11px] font-bold text-[#00838f] dark:text-teal-400 mb-1.5">2. Voucher Spesial</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Dapatkan subsidi voucher belanja dan tambahan eksposur kunjungan toko hingga +30%.
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-slate-50 dark:bg-[#0c1220] p-3.5 rounded-2xl border border-slate-200/80 dark:border-slate-800 text-left">
                        <div class="text-[11px] font-bold text-slate-700 dark:text-slate-200 mb-1.5">3. Peningkatan Omset</div>
                        <div class="text-[10px] text-slate-500 dark:text-slate-400 leading-relaxed">
                            Potensi penjualan harian naik hingga 4x lipat dengan rasio konversi tinggi.
                        </div>
                    </div>

                </div>

                <!-- Footer Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-[#222f49]">
                    <button type="button" @click="closePromoModal(true)" class="text-xs font-medium text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        Jangan tampilkan lagi
                    </button>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button type="button" @click="closePromoModal()" class="flex-1 sm:flex-none px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-center">
                            Tutup
                        </button>
                        <a href="{{ route('tenant.ads.create') }}" class="flex-1 sm:flex-none px-5 py-2 rounded-xl bg-[#00838f] hover:bg-[#00727d] text-white text-xs font-bold shadow-xs transition-all text-center">
                            Buat Iklan Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
