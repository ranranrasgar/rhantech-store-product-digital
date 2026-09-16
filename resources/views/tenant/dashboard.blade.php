@extends('layouts.tenant')

@section('title', 'Dashboard Toko')

@section('content')
@php
    $storeSlug = $store ? ($store->slug ?: 'toko-' . $store->id) : 'toko';
    $storeDirectUrl = url('/' . $storeSlug);
    $storeTokoUrl = $store ? route('store.show', $storeSlug) : url('/');
    $encodedUrl = urlencode($storeDirectUrl);
    $storeTitle = $store->name ?? 'Toko Saya';
    $shareMessage = "Kunjungi toko digital resmi {$storeTitle} di Rhantech untuk melihat berbagai produk digital, source code, dan template terbaik: {$storeDirectUrl}";
    $encodedMsg = urlencode($shareMessage);
@endphp

<div class="flex-1 overflow-y-auto p-3 sm:p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
        copied: false,
        showQrModal: false,
        storeUrl: '{{ $storeDirectUrl }}',
        copyToClipboard() {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(this.storeUrl).then(() => {
                    this.triggerCopied();
                }).catch(() => {
                    this.fallbackCopy();
                });
            } else {
                this.fallbackCopy();
            }
        },
        fallbackCopy() {
            const input = document.getElementById('store-link-input-mobile') || document.getElementById('store-link-input');
            if (input) {
                input.select();
                document.execCommand('copy');
                this.triggerCopied();
            }
        },
        triggerCopied() {
            this.copied = true;
            setTimeout(() => { this.copied = false; }, 2500);
        },
        shareNative() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($storeTitle) }}',
                    text: '{{ addslashes($shareMessage) }}',
                    url: this.storeUrl
                }).catch(() => {});
            } else {
                this.copyToClipboard();
            }
        }
     }">
    <div class="max-w-7xl mx-auto">

        <!-- ========================================================================= -->
        <!-- MOBILE NATIVE APP VIEW (Visible on Mobile < 768px Only)                   -->
        <!-- ========================================================================= -->
        <div class="block md:hidden space-y-4 pb-8">
            <!-- 1. Native Mobile Top Profile & Header Card -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-12 rounded-2xl p-0.5 bg-slate-200 dark:bg-slate-700 shrink-0 overflow-hidden shadow-xs">
                            @if($store && $store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-[14px]">
                            @else
                                <div class="w-full h-full bg-[#00838f] rounded-[14px] flex items-center justify-center font-black text-lg text-white">
                                    {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h1 class="text-base font-extrabold text-slate-900 dark:text-white truncate">
                                    {{ $store->name ?? 'Toko Saya' }}
                                </h1>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Merchant Partner
                                </span>
                                @if(auth()->check() && auth()->user()->store && auth()->user()->store->isPro())
                                    <span class="bg-amber-500 text-white text-[9px] px-1.5 py-0.2 rounded font-black tracking-wide">PRO</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Public Store Link Pill -->
                    @if($store && $store->slug)
                    <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center gap-1 shrink-0 active:scale-95 shadow-2xs">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">storefront</span>
                        <span>Toko</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- 2. Native Wallet & Finance Card (Dompet Seller) -->
            <div class="rounded-2xl bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] p-4 sm:p-5 shadow-xs relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[18px] text-slate-700 dark:text-slate-300">account_balance_wallet</span>
                            <span>Saldo Toko Siap Ditarik</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Otomatis
                        </span>
                    </div>

                    <div class="mt-2 text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}
                    </div>

                    <!-- Wallet Action Bar -->
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#222f49] flex items-center justify-between gap-2.5">
                        <a href="{{ route('tenant.payouts.index') }}" class="flex-1 py-2.5 px-3 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-2xs active:scale-95 text-center">
                            <span class="material-symbols-outlined text-[17px]">payments</span>
                            <span>Tarik Dana</span>
                        </a>

                        <a href="{{ route('tenant.ads.index') }}" class="flex-1 py-2.5 px-3 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center justify-center gap-1.5 border border-slate-200/80 dark:border-slate-700 active:scale-95 text-center">
                            <span class="material-symbols-outlined text-[16px] text-slate-500">ads_click</span>
                            <span class="truncate">Iklan: Rp {{ number_format($adBalance ?? 0, 0, ',', '.') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Native App Quick Action Grid (8 Sleek Modern Tiles) -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-3.5 shadow-xs">
                <div class="grid grid-cols-4 gap-2 text-center">
                    <!-- Action 1: Tambah Produk -->
                    <a href="{{ route('tenant.products.create') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Tambah Produk</span>
                    </a>

                    <!-- Action 2: Pesanan Penjualan -->
                    <a href="{{ route('tenant.orders.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group relative">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all relative">
                            <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                            @if(($pendingOrdersCount ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center ring-2 ring-white dark:ring-slate-900">
                                    {{ $pendingOrdersCount }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Pesanan</span>
                    </a>

                    <!-- Action 3: Pusat Iklan -->
                    <a href="{{ route('tenant.ads.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group relative">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all relative">
                            <span class="material-symbols-outlined text-[20px]">campaign</span>
                            @if(isset($hasClaimedWelcomeVoucher) && !$hasClaimedWelcomeVoucher)
                                <span class="absolute -top-1 -right-1 px-1 rounded-full bg-rose-500 text-white text-[8px] font-black uppercase tracking-wider">
                                    Bonus
                                </span>
                            @endif
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Iklan Toko</span>
                    </a>

                    <!-- Action 4: Tampilan & Tema -->
                    <a href="{{ route('tenant.appearance.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">palette</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Dekorasi</span>
                    </a>

                    <!-- Action 5: Rekening Bank -->
                    <a href="{{ route('tenant.bank.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">credit_card</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Rekening</span>
                    </a>

                    <!-- Action 6: Bagikan Toko (Share) -->
                    <button type="button" @click="shareNative()" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group cursor-pointer">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">share</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Bagikan</span>
                    </button>

                    <!-- Action 7: Analitik Performa -->
                    <a href="{{ route('tenant.performance.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">monitoring</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Analitik</span>
                    </a>

                    <!-- Action 8: Pusat Bantuan -->
                    <a href="{{ route('help.index') }}" target="_blank" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-sky-500 group-hover:text-white dark:group-hover:bg-sky-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">help</span>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-700 dark:text-slate-300 leading-tight">Bantuan</span>
                    </a>
                </div>
            </div>

            <!-- 4. Horizontal Swipeable Metric Chips (Statistik Kilat) -->
            <div>
                <div class="flex items-center justify-between px-1 mb-2">
                    <h2 class="text-xs font-extrabold text-slate-700 dark:text-slate-300 uppercase tracking-wider">
                        Ringkasan Toko
                    </h2>
                    <span class="text-[11px] text-slate-400 font-medium">Geser untuk melihat »</span>
                </div>

                <div class="flex gap-2.5 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-none" style="-webkit-overflow-scrolling: touch;">
                    <!-- Metric Card 1: Pengunjung -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Pengunjung</span>
                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-slate-900 dark:text-white">
                            {{ number_format($totalVisitors ?? 0) }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5 truncate">
                            {{ number_format($productViews ?? 0) }} view produk
                        </div>
                    </div>

                    <!-- Metric Card 2: Pesanan Sukses -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Order Sukses</span>
                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">verified</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-slate-900 dark:text-white">
                            {{ number_format($completedOrdersCount ?? 0) }}
                        </div>
                        <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">
                            {{ $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100 }}% Sukses
                        </div>
                    </div>

                    <!-- Metric Card 3: Menunggu Bayar -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Tertunda</span>
                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">schedule</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-slate-900 dark:text-white">
                            {{ number_format($pendingOrdersCount ?? 0) }}
                        </div>
                        <div class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mt-0.5">
                            Menunggu bayar
                        </div>
                    </div>

                    <!-- Metric Card 4: Katalog Produk -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Produk Aktif</span>
                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">inventory_2</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-slate-900 dark:text-white">
                            {{ number_format($activeProducts ?? 0) }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">
                            Dari {{ number_format($totalProducts ?? 0) }} produk
                        </div>
                    </div>

                    <!-- Metric Card 5: Pengikut Toko -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400">Pengikut</span>
                            <span class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">group</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-slate-900 dark:text-white">
                            {{ number_format($followersCount ?? 0) }}
                        </div>
                        <div class="text-[10px] text-slate-500 font-bold mt-0.5">
                            Pelanggan setia
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5. Native Mini Sales Trend Chart -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100 dark:border-[#222f49]">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">monitoring</span>
                        </span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Tren Penjualan</h3>
                    </div>

                    <!-- Segmented Control Switcher -->
                    <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800/80 p-0.5 rounded-xl border border-slate-200/60 dark:border-slate-700/60">
                        <button type="button" id="btnTenantPeriodMonthlyMobile" onclick="switchTenantTrendPeriod('monthly')" class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition-all bg-sky-500 text-white dark:bg-sky-600 dark:text-white shadow-2xs cursor-pointer">
                            6 Bulan
                        </button>
                        <button type="button" id="btnTenantPeriodDailyMobile" onclick="switchTenantTrendPeriod('daily')" class="px-2.5 py-1 text-[11px] font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 cursor-pointer">
                            Harian
                        </button>
                    </div>
                </div>

                <div class="mt-3 relative h-48 w-full">
                    <canvas id="tenantSalesChartMobile"></canvas>
                </div>

                <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-[#222f49] flex items-center justify-between text-xs">
                    <span class="text-slate-500 dark:text-slate-400 text-[11px]">Total Pendapatan:</span>
                    <span id="tenantPeriodTotalMobile" class="font-extrabold text-slate-900 dark:text-white text-sm">
                        Rp {{ number_format(array_sum($monthlySales ?? []), 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- 5b. Native Activity Chart Mobile (View, Klik, Order 7 Hari) -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100 dark:border-[#222f49]">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">show_chart</span>
                        </span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Aktivitas 7 Hari</h3>
                    </div>
                    <div class="flex items-center gap-1.5 text-[10px] font-bold flex-wrap justify-end">
                        <span class="flex items-center gap-1 text-purple-600 dark:text-purple-400"><span class="w-2 h-2 rounded-full bg-purple-500"></span>View</span>
                        <span class="flex items-center gap-1 text-sky-600 dark:text-sky-400"><span class="w-2 h-2 rounded-full bg-sky-500"></span>Klik</span>
                        <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Order</span>
                    </div>
                </div>
                <div class="mt-3 relative h-44 w-full">
                    <canvas id="tenantActivityChartMobile"></canvas>
                </div>
                <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-[#222f49] grid grid-cols-3 gap-2 text-center">
                    <div>
                        <div class="text-xs font-black text-purple-600 dark:text-purple-400">{{ number_format($activityTotalViews ?? 0) }}</div>
                        <div class="text-[10px] text-slate-400">Views</div>
                    </div>
                    <div>
                        <div class="text-xs font-black text-sky-600 dark:text-sky-400">{{ number_format($activityTotalClicks ?? 0) }}</div>
                        <div class="text-[10px] text-slate-400">Klik</div>
                    </div>
                    <div>
                        <div class="text-xs font-black text-emerald-600 dark:text-emerald-400">{{ number_format($activityTotalOrders ?? 0) }}</div>
                        <div class="text-[10px] text-slate-400">Order</div>
                    </div>
                </div>
            </div>

            <!-- 6. Native Recent Orders Section -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-[20px]">receipt_long</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Pesanan Terkini</h3>
                    </div>
                    <a href="{{ route('tenant.orders.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors flex items-center gap-0.5">
                        Semua <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                    </a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse($recentOrders as $order)
                    @php
                        $tenantItems = $order->orderItems->filter(function($item) use ($store) {
                            return $item->product && $item->product->store_id == $store->id;
                        });
                        $firstItem = $tenantItems->first() ?? $order->orderItems->first();
                        $firstProduct = $firstItem ? $firstItem->product : $order->product;
                        $amountForTenant = $tenantItems->isNotEmpty() ? $tenantItems->sum(function($item){ return $item->price * $item->quantity; }) : $order->amount;
                    @endphp
                    <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden text-slate-500">
                                @if($firstProduct && $firstProduct->images->count() > 0)
                                    @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-[20px]">code</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">
                                    {{ $firstProduct->name ?? 'Produk Digital' }}
                                </h4>
                                <div class="flex items-center gap-1.5 text-[10px] text-slate-400 mt-0.5 font-mono">
                                    <span>#{{ substr($order->invoice_number, -8) }}</span>
                                    <span>•</span>
                                    <span>{{ $order->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-right shrink-0">
                            <div class="text-xs font-extrabold text-slate-900 dark:text-white">
                                Rp {{ number_format($amountForTenant, 0, ',', '.') }}
                            </div>
                            <div class="mt-0.5">
                                @if($order->status === 'paid' || $order->status === 'downloaded')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        Sukses
                                    </span>
                                @elseif($order->status === 'failed')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-6 text-center text-slate-400">
                        <span class="material-symbols-outlined text-3xl mb-1 opacity-40">shopping_bag</span>
                        <p class="text-xs">Belum ada transaksi penjualan baru.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- 7. Native Share & QR Quick Sheet Trigger -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-900 dark:text-white truncate">Bagikan Tautan Toko</div>
                            <div class="text-[10px] text-slate-400 truncate">Siap dipasang di bio IG & TikTok</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" @click="copyToClipboard()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-2xs" :class="copied ? 'bg-emerald-600 text-white' : 'bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white'">
                            <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                        </button>
                        <button type="button" @click="showQrModal = true" class="p-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all active:scale-95 cursor-pointer" title="QR Code">
                            <span class="material-symbols-outlined text-[18px]">qr_code</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 8. Native Top Clicked Products Section -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-amber-500">local_fire_department</span>
                        Produk Terbanyak Diklik
                    </h3>
                    <a href="{{ route('tenant.products.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($topClickedProducts as $prod)
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-800/60">
                        <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                            @if($prod->images->count() > 0)
                                @php $prodImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first(); @endphp
                                <img src="{{ asset('storage/' . $prodImg->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">inventory_2</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $prod->name }}</h4>
                            <div class="flex items-center gap-2 text-[11px] mt-0.5">
                                <span class="font-bold text-slate-900 dark:text-white">Rp {{ number_format($prod->discount_price ?? $prod->price, 0, ',', '.') }}</span>
                                <span class="text-amber-600 font-bold">• {{ number_format($prod->views) }} views</span>
                            </div>
                        </div>
                        <a href="{{ route('tenant.products.edit', $prod) }}" class="p-1.5 text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors" title="Edit">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>
                    </div>
                    @empty
                    <div class="py-4 text-center text-slate-400 text-xs">
                        Belum ada produk yang diunggah.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- 9. Native Top Market Search Trends -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-slate-800/80 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-sky-500">search</span>
                        Paling Banyak Dicari Pembeli
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400">Tren Pasar</span>
                </div>

                <div class="space-y-2">
                    @forelse($topMarketSearches as $search)
                    <div class="p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-900 dark:text-white truncate capitalize"># {{ $search->keyword }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                @if($search->results_count > 0)
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ $search->results_count }} Produk</span>
                                @else
                                    <span class="text-amber-600 font-bold">0 Produk (Peluang Emas!)</span>
                                @endif
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-black text-slate-700 dark:text-slate-300 shrink-0">
                            {{ $search->hits }}x
                        </span>
                    </div>
                    @empty
                    <div class="py-3 text-center text-xs text-slate-400">Belum ada riwayat pencarian.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- DESKTOP VIEW (Visible on Desktop >= 768px Only - 100% UNTOUCHED ORIGINAL) -->
        <!-- ========================================================================= -->
        <div class="hidden md:block space-y-8">

        <!-- Header Hero & Quick Info -->
        <div class="rounded-2xl bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] p-6 md:p-7 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl p-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0">
                        @if($store && $store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full bg-[#00838f] rounded-xl flex items-center justify-center font-black text-2xl text-white">
                                {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 border border-slate-200 dark:border-slate-700">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Merchant Partner
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                            {{ $store->name ?? 'Toko Saya' }}
                        </h1>
                        <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-xl line-clamp-1">
                            {{ $store->description ?: 'Kelola produk digital, pantau penjualan, dan tingkatkan penghasilan Anda.' }}
                        </p>
                    </div>
                </div>

                <!-- Action Hub Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    @if($store && $store->slug)
                    <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs md:text-sm font-semibold transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">storefront</span>
                        Lihat Toko Publik
                    </a>
                    @endif
                    <a href="{{ route('tenant.products.create') }}" class="px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs md:text-sm font-bold transition-all flex items-center gap-2 shadow-2xs active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- Cuplikan Tren Pembeli (Classic Ticker di Bawah Card Banner) -->
        <div class="bg-white dark:bg-[#161b22] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-3 sm:px-5 sm:py-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                
                <!-- Label / Icon -->
                <div class="flex items-center gap-2 shrink-0">
                    <span class="w-7 h-7 rounded-lg bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">local_fire_department</span>
                    </span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                        Tren Dicari Pembeli:
                    </span>
                </div>

                <!-- Scrolling Marquee Ticker -->
                <div class="flex-1 overflow-hidden relative py-0.5 mask-fade-edges">
                    @if(isset($trendingSearches) && $trendingSearches->isNotEmpty())
                    <div class="marquee-track flex items-center gap-2">
                        @for($i = 0; $i < 2; $i++)
                            @foreach($trendingSearches as $search)
                            <a href="{{ route('products.index', ['search' => $search->keyword]) }}" target="_blank" 
                               title="Lihat persaingan katalog: {{ $search->keyword }}"
                               class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 hover:bg-slate-100 dark:bg-slate-800/80 dark:hover:bg-slate-700/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-medium transition-colors shrink-0 group">
                                <span class="text-amber-500 font-bold text-xs">#</span>
                                <span class="font-bold text-slate-900 dark:text-white whitespace-nowrap">{{ $search->keyword }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 whitespace-nowrap">({{ $search->hits }}x dicari)</span>
                                @if($search->results_count === 0)
                                <span class="text-[9px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 px-1.5 py-0.5 rounded whitespace-nowrap">
                                    Peluang Emas
                                </span>
                                @endif
                                <span class="material-symbols-outlined text-[12px] text-slate-400 group-hover:text-sky-600 dark:group-hover:text-sky-400">open_in_new</span>
                            </a>
                            @endforeach
                        @endfor
                    </div>
                    @else
                    <span class="text-xs text-slate-400 italic">Belum ada data pencarian pembeli.</span>
                    @endif
                </div>

                <!-- CTA Action Link -->
                <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                    <a href="{{ route('tenant.products.create') }}" class="text-xs font-bold text-[#00838f] dark:text-teal-400 hover:underline flex items-center gap-1">
                        <span>+ Buat Produk</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </a>
                </div>

            </div>
        </div>

        <style>
            @keyframes marquee-scroll-horizontal {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .marquee-track {
                width: max-content;
                animation: marquee-scroll-horizontal 28s linear infinite;
            }
            .marquee-track:hover {
                animation-play-state: paused;
            }
            .mask-fade-edges {
                mask-image: linear-gradient(to right, transparent 0%, black 2%, black 98%, transparent 100%);
                -webkit-mask-image: linear-gradient(to right, transparent 0%, black 2%, black 98%, transparent 100%);
            }
        </style>

        @if($store)
        @php
            $storeSlug = $store->slug ?: 'toko-' . $store->id;
            // Tautan resmi langsung sesuai setting Tautan URL / Slug Toko di dashboard/store
            $storeDirectUrl = url('/' . $storeSlug);
            $storeTokoUrl = route('store.show', $storeSlug);
            $encodedUrl = urlencode($storeDirectUrl);
            $storeTitle = $store->name;
            $shareMessage = "Kunjungi toko digital resmi {$storeTitle} di Rhantech untuk melihat berbagai produk digital, source code, dan template terbaik: {$storeDirectUrl}";
            $encodedMsg = urlencode($shareMessage);
        @endphp

        <!-- Modul Promosi & Bagikan Tautan Toko -->
        <div x-data="{
            copied: false,
            showQrModal: false,
            storeUrl: '{{ $storeDirectUrl }}',
            copyToClipboard() {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(this.storeUrl).then(() => {
                        this.triggerCopied();
                    }).catch(() => {
                        this.fallbackCopy();
                    });
                } else {
                    this.fallbackCopy();
                }
            },
            fallbackCopy() {
                const input = document.getElementById('store-link-input');
                if (input) {
                    input.select();
                    document.execCommand('copy');
                    this.triggerCopied();
                }
            },
            triggerCopied() {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2500);
            },
            shareNative() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ addslashes($storeTitle) }}',
                        text: '{{ addslashes($shareMessage) }}',
                        url: this.storeUrl
                    }).catch(() => {});
                } else {
                    this.copyToClipboard();
                }
            }
        }" class="bg-white dark:bg-[#161b22] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 md:p-6">

            <div class="space-y-5">
                
                <!-- Header: Title & Badges -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 md:w-11 md:h-11 rounded-2xl bg-teal-500/10 text-[#00838f] dark:text-teal-400 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px] md:text-[24px]">share</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-base md:text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                                    Promosikan & Bagikan Toko Anda
                                </h2>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/60">
                                    <span>✨</span> Siap Dipakai di Bio TikTok & Instagram
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Tautan resmi toko berdasarkan slug di pengaturan toko. Bagikan ke medsos atau jadikan bio profil untuk menjaring calon pembeli.
                            </p>
                        </div>
                    </div>

                    <!-- Quick Action: Ganti Slug Toko -->
                    <a href="{{ route('tenant.store.index') }}" 
                       class="self-start sm:self-center inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-[#00838f] text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-[#00838f] dark:hover:text-teal-400 transition-colors"
                       title="Ubah URL / Slug Toko di Pengaturan">
                        <span class="material-symbols-outlined text-[16px] text-[#00838f] dark:text-teal-400">settings_suggest</span>
                        <span>Atur Slug Toko</span>
                    </a>
                </div>

                <!-- Main URL Box & Copy Actions -->
                <div class="bg-slate-50 dark:bg-slate-900/80 p-2 sm:p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row items-stretch md:items-center gap-2.5">
                    <div class="flex items-center gap-2.5 flex-1 min-w-0 px-2.5 py-1">
                        <span class="material-symbols-outlined text-[20px] text-[#00838f] dark:text-teal-400 shrink-0">link</span>
                        <input type="text" id="store-link-input" readonly :value="storeUrl" 
                               @click="copyToClipboard()"
                               class="w-full bg-transparent border-none p-0 text-xs md:text-sm font-mono font-bold text-slate-900 dark:text-white focus:outline-none select-all cursor-pointer truncate"
                               title="Klik untuk salin tautan">
                    </div>

                    <!-- Action Buttons: Copy, Open, QR -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Copy Button -->
                        <button type="button" @click="copyToClipboard()"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl font-bold text-xs transition-all active:scale-95 cursor-pointer shadow-2xs"
                                :class="copied ? 'bg-emerald-600 text-white' : 'bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white'">
                            <span class="material-symbols-outlined text-[17px]" x-text="copied ? 'check_circle' : 'content_copy'"></span>
                            <span x-text="copied ? 'Tersalin! 🎉' : 'Salin Tautan'"></span>
                        </button>

                        <!-- Open Store Button -->
                        <a :href="storeUrl" target="_blank"
                           class="p-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-colors shrink-0"
                           title="Buka Toko di Tab Baru">
                            <span class="material-symbols-outlined text-[17px]">open_in_new</span>
                        </a>

                        <!-- QR Code Button -->
                        <button type="button" @click="showQrModal = true"
                                class="p-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-colors shrink-0 cursor-pointer"
                                title="Lihat & Download QR Code Toko">
                            <span class="material-symbols-outlined text-[17px]">qr_code_2</span>
                        </button>
                    </div>
                </div>

                <!-- Social Media Share Buttons -->
                <div class="pt-1 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-bold text-slate-500 dark:text-slate-400 text-[11px] uppercase tracking-wider mr-1">Bagikan Langsung:</span>

                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ $encodedMsg }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-semibold text-xs border border-emerald-200 dark:border-emerald-800/60 transition-colors"
                           title="Bagikan ke WhatsApp Chat / Status">
                            <svg class="w-3.5 h-3.5 fill-current text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>WhatsApp</span>
                        </a>

                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedMsg }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/40 dark:hover:bg-sky-900/50 text-sky-700 dark:text-sky-300 font-semibold text-xs border border-sky-200 dark:border-sky-800/60 transition-colors"
                           title="Bagikan ke Telegram">
                            <svg class="w-3.5 h-3.5 fill-current text-sky-500" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.196 1.006.128.832.942z"/></svg>
                            <span>Telegram</span>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-semibold text-xs border border-blue-200 dark:border-blue-800/60 transition-colors"
                           title="Bagikan ke Facebook">
                            <svg class="w-3.5 h-3.5 fill-current text-blue-600" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                            <span>Facebook</span>
                        </a>

                        <!-- X (Twitter) -->
                        <a href="https://twitter.com/intent/tweet?text={{ $encodedMsg }}&url={{ $encodedUrl }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs border border-slate-200 dark:border-slate-700 transition-colors"
                           title="Bagikan ke Twitter / X">
                            <svg class="w-3.5 h-3.5 fill-current text-slate-800 dark:text-white" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span>X</span>
                        </a>

                        <!-- Mobile Native Share Sheet -->
                        <button type="button" @click="shareNative()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 font-semibold text-xs border border-purple-200 dark:border-purple-800/60 transition-colors cursor-pointer"
                                title="Bagikan via Aplikasi Lain di HP">
                            <span class="material-symbols-outlined text-[15px] text-purple-600 dark:text-purple-400">send_to_mobile</span>
                            <span>Lainnya</span>
                        </button>
                    </div>

                    <!-- Guidance Note -->
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-[#00838f] dark:text-teal-400">info</span>
                        <span>Tautan otomatis mengarahkan pengunjung ke etalase tokomu.</span>
                    </div>
                </div>
            </div>

            <!-- Modal QR Code -->
            <div x-show="showQrModal" x-cloak style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
                <div @click.outside="showQrModal = false"
                     class="bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-2xl p-6 max-w-sm w-full text-center relative">
                    <button type="button" @click="showQrModal = false" 
                            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-full">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>

                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-2xl">qr_code_2</span>
                    </div>

                    <h3 class="text-base font-black text-slate-900 dark:text-white mb-1">QR Code Toko Anda</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                        Scan dengan kamera HP untuk langsung membuka toko: <span class="font-bold text-sky-600 dark:text-sky-400">{{ $store->name }}</span>
                    </p>

                    <div class="p-3 bg-white rounded-xl border border-slate-200 inline-block mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $encodedUrl }}" 
                             alt="QR Code Toko {{ $store->name }}"
                             class="w-48 h-48 rounded-lg object-contain mx-auto">
                    </div>

                    <div class="space-y-2">
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ $encodedUrl }}&download=1"
                           target="_blank" download="qr-toko-{{ $storeSlug }}.png"
                           class="w-full py-2.5 px-4 bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            <span>Download Gambar QR Code</span>
                        </a>
                        <button type="button" @click="copyToClipboard(); showQrModal = false;"
                                class="w-full py-2 px-4 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            Salin Tautan Saja
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- 6 Essential Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 md:gap-5">
            <!-- Metric 1: Total Revenue -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Saldo</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Siap ditarik</span>
                        <a href="{{ route('tenant.payouts.index') }}" class="font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-0.5">
                            Tarik Saldo <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Total Pengunjung / Visitor -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pengunjung</span>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">visibility</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($totalVisitors ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span class="text-purple-600 dark:text-purple-400 font-bold">+{{ $todayStoreVisits ?? 0 }} hari ini</span>
                        <a href="{{ route('tenant.performance.index') }}" class="font-bold text-slate-600 dark:text-slate-300 hover:text-purple-600 dark:hover:text-purple-400 hover:underline">
                            Statistik
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Completed Orders -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pesanan Berhasil</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">verified</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($completedOrdersCount ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Dari {{ number_format($totalOrdersCount ?? 0) }} order</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100 }}% Sukses
                        </span>
                    </div>
                </div>
            </div>

            <!-- Metric 4: Pending Orders -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Menunggu Bayar</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">schedule</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($pendingOrdersCount ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Invoice tertunda</span>
                        <a href="{{ route('tenant.orders.index', ['tab' => 'pending']) }}" class="font-bold text-amber-600 dark:text-amber-400 hover:underline">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric 5: Active Products -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Katalog Produk</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($activeProducts ?? 0) }} <span class="text-xs font-semibold text-slate-400">/ {{ number_format($totalProducts ?? 0) }}</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Produk aktif</span>
                        <a href="{{ route('tenant.products.index') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Kelola Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric 6: Pengikut Toko (Followers) -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pengikut Toko</span>
                    <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-600 dark:text-pink-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">group</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($followersCount ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Pelanggan setia</span>
                        <a href="{{ url('/' . $storeSlug) }}" target="_blank" class="font-bold text-pink-600 dark:text-pink-400 hover:underline flex items-center gap-0.5">
                            Lihat Toko <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Trend Chart: Bulanan & Harian -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 md:p-6 mb-8 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-[#222f49]">
                <div>
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[22px]">monitoring</span>
                        </div>
                        <div>
                            <h2 class="text-base md:text-lg font-bold text-slate-900 dark:text-white">Tren Penjualan Toko</h2>
                            <p id="tenantTrendSubtitle" class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Grafik pendapatan riil 6 bulan terakhir
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Period Controls -->
                <div class="flex items-center self-start sm:self-auto gap-1 bg-slate-100 dark:bg-slate-800/80 p-1 rounded-xl border border-slate-200/60 dark:border-slate-700/60">
                    <button type="button" 
                            id="btnTenantPeriodMonthly" 
                            onclick="switchTenantTrendPeriod('monthly')"
                            class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all bg-sky-500 text-white dark:bg-sky-600 dark:text-white shadow-2xs cursor-pointer">
                        Bulanan (6 Bln)
                    </button>
                    <button type="button" 
                            id="btnTenantPeriodDaily" 
                            onclick="switchTenantTrendPeriod('daily')"
                            class="px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 cursor-pointer">
                        Harian ({{ $currentMonthName ?? 'Bulan Ini' }})
                    </button>
                </div>
            </div>

            <!-- Chart Canvas Container -->
            <div class="mt-4 relative h-72 w-full">
                <canvas id="tenantSalesChart"></canvas>
            </div>

            <!-- Footer Stats Insight -->
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-[#222f49] flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span>
                        <span id="tenantChartLegendLabel">Pendapatan Bersih Penjualan</span>
                    </div>
                    <div class="text-slate-700 dark:text-slate-300 font-medium">
                        Total Periode Ini: <span id="tenantPeriodTotal" class="font-bold text-sky-600 dark:text-sky-400">Rp {{ number_format(array_sum($monthlySales ?? []), 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="text-slate-400 text-[11px]">
                    *Dihitung otomatis berdasarkan transaksi lunas (Paid &amp; Downloaded)
                </div>
            </div>
        </div>

        <!-- Section: Analitik Pengunjung & Minat Pembeli (Visitor Traffic, Top Clicked, Top Searches) -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 md:p-6 mb-8 shadow-xs space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-[#222f49]">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">visibility</span>
                    </div>
                    <div>
                        <h2 class="text-base md:text-lg font-bold text-slate-900 dark:text-white">Analisis Pengunjung & Minat Pembeli</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pantau produk tokomu yang paling banyak dilihat calon pembeli dan kata kunci yang sedang dicari di marketplace.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('tenant.performance.index') }}" class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">analytics</span>
                        <span>Statistik Lengkap</span>
                    </a>
                </div>
            </div>

            <!-- 4 Quick Traffic Highlight Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                        <span>Total Kunjungan Toko</span>
                        <span class="material-symbols-outlined text-[18px] text-purple-500">storefront</span>
                    </div>
                    <div class="text-xl font-black text-slate-900 dark:text-white">
                        {{ number_format($totalVisitors ?? 0) }}
                    </div>
                    <div class="text-[11px] text-purple-600 dark:text-purple-400 font-bold mt-1">
                        +{{ $todayStoreVisits ?? 0 }} kunjungan hari ini
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                        <span>Tayangan / Klik Produk</span>
                        <span class="material-symbols-outlined text-[18px] text-indigo-500">ads_click</span>
                    </div>
                    <div class="text-xl font-black text-indigo-600 dark:text-indigo-400">
                        {{ number_format($productViews ?? 0) }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Detail produk dilihat pembeli
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                        <span>Kunjungan Profil Toko</span>
                        <span class="material-symbols-outlined text-[18px] text-sky-500">visibility</span>
                    </div>
                    <div class="text-xl font-black text-sky-600 dark:text-sky-400">
                        {{ number_format($storeViews ?? 0) }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Halaman etalase toko dibuka
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1">
                        <span>Peluang Pasar Baru</span>
                        <span class="material-symbols-outlined text-[18px] text-amber-500">auto_awesome</span>
                    </div>
                    <div class="text-xl font-black text-amber-600 dark:text-amber-400">
                        {{ $unmetMarketDemandsCount ?? 0 }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Kata kunci dicari belum ada produk
                    </div>
                </div>
            </div>

            <!-- 7-Day Activity Trend Chart: View Toko, Klik Produk, Order -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/60 dark:border-[#222f49] rounded-2xl p-5 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-sky-500">show_chart</span>
                            Tren Aktivitas Toko (7 Hari Terakhir)
                        </h3>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">View halaman toko, klik produk, dan order masuk per hari</p>
                    </div>
                    <!-- Summary Pills -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            {{ number_format($activityTotalViews ?? 0) }} Views
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                            {{ number_format($activityTotalClicks ?? 0) }} Klik
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            {{ number_format($activityTotalOrders ?? 0) }} Order
                        </span>
                        @if(($activityConversionRate ?? 0) > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            <span class="material-symbols-outlined text-[13px]">trending_up</span>
                            {{ $activityConversionRate ?? 0 }}% Konversi
                        </span>
                        @endif
                    </div>
                </div>

                <div class="relative h-56 w-full">
                    <canvas id="tenantActivityChart"></canvas>
                </div>

                <div class="mt-3 pt-3 border-t border-slate-100 dark:border-[#222f49] flex flex-wrap items-center gap-x-5 gap-y-1.5 text-[11px] text-slate-500 dark:text-slate-400">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> View Halaman Toko</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Klik / Lihat Produk</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Order Masuk</span>
                    <span class="ml-auto text-[10px] text-slate-400">*Data 7 hari terakhir</span>
                </div>
            </div>

            <!-- Grid 2 Columns: Produk Terbanyak Diklik (Left) & Kata Kunci Dicari (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Produk Terbanyak Diklik / Dilihat (7 cols) -->
                <div class="lg:col-span-7">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-amber-500">local_fire_department</span>
                            Produk Toko Terbanyak Diklik / Dilihat
                        </h3>
                        <a href="{{ route('tenant.products.index') }}" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline">
                            Semua Produk
                        </a>
                    </div>

                    <div class="border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($topClickedProducts as $idx => $prod)
                        @php
                            $pct = $maxProductViews > 0 ? min(100, round(($prod->views / $maxProductViews) * 100)) : 0;
                            $pImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first();
                        @endphp
                        <div class="p-3 flex items-center justify-between gap-3 hover:bg-slate-50/70 dark:hover:bg-slate-900/40 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="w-5 h-5 rounded-full {{ $idx === 0 ? 'bg-amber-500 text-white' : ($idx === 1 ? 'bg-slate-400 text-white' : ($idx === 2 ? 'bg-amber-700 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300')) }} text-[10px] font-black flex items-center justify-center shrink-0">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($pImg)
                                        <img src="{{ asset('storage/' . $pImg->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">code</span>
                                    @endif
                                </div>
                                <div class="min-w-0 max-w-[200px] sm:max-w-xs">
                                    <a href="{{ route('products.show', $prod->slug) }}" target="_blank" class="text-xs font-bold text-slate-900 dark:text-white hover:text-sky-600 truncate block" title="{{ $prod->name }}">
                                        {{ $prod->name }}
                                    </a>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                        <span class="font-bold text-emerald-600 dark:text-emerald-400">Rp {{ number_format($prod->discount_price ?? $prod->price, 0, ',', '.') }}</span>
                                        <span>•</span>
                                        <span>{{ $prod->sales_count ?? 0 }} Terjual</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <div class="text-xs font-black text-slate-900 dark:text-white">
                                    {{ number_format($prod->views) }} <span class="text-[10px] font-normal text-slate-400">views</span>
                                </div>
                                <div class="w-20 h-1.5 rounded-full bg-slate-200 dark:bg-slate-700 ml-auto mt-1 overflow-hidden">
                                    <div class="h-full rounded-full bg-amber-500" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-xs text-slate-400">
                            Belum ada riwayat klik produk.
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Kata Kunci Terbanyak Dicari Pembeli (5 cols) -->
                <div class="lg:col-span-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-sky-500">search</span>
                            Kata Kunci Terbanyak Dicari
                        </h3>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pasar Pembeli</span>
                    </div>

                    <div class="space-y-2">
                        @forelse($topMarketSearches as $search)
                        <div class="p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between gap-2 hover:border-slate-200 dark:hover:border-slate-700 transition-colors">
                            <div class="min-w-0">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-amber-500 font-bold text-xs">#</span>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white truncate capitalize">{{ $search->keyword }}</span>
                                </div>
                                <div class="mt-0.5 text-[10px]">
                                    @if($search->results_count > 0)
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ $search->results_count }} Produk Tersedia</span>
                                    @else
                                        <span class="text-amber-600 dark:text-amber-400 font-bold flex items-center gap-0.5">
                                            <span>Belum Ada Produk</span>
                                            <span class="px-1 py-0.2 rounded bg-amber-500/10 text-[9px]">Peluang Emas!</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-extrabold text-slate-700 dark:text-slate-300">
                                    {{ number_format($search->hits) }}x
                                </span>
                                <a href="{{ route('tenant.products.create') }}" class="p-1 rounded-lg text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-white dark:hover:bg-slate-800 transition-colors" title="Buat Produk Kategori Ini">
                                    <span class="material-symbols-outlined text-[16px]">add_circle</span>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-xs text-slate-400">
                            Belum ada riwayat pencarian.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area: 2 Columns (7:5) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Area: Recent Orders (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl overflow-hidden">
                    <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#222f49] flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Transaksi Penjualan Terbaru</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pesanan produk digital terkini dari pelanggan Anda.</p>
                        </div>
                        <a href="{{ route('tenant.orders.index') }}" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1">
                            Semua Pesanan <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($recentOrders as $order)
                        @php
                            $tenantItems = $order->orderItems->filter(function($item) use ($store) {
                                return $item->product && $item->product->store_id == $store->id;
                            });
                            $firstItem = $tenantItems->first() ?? $order->orderItems->first();
                            $firstProduct = $firstItem ? $firstItem->product : $order->product;
                            $amountForTenant = $tenantItems->isNotEmpty() ? $tenantItems->sum(function($item){ return $item->price * $item->quantity; }) : $order->amount;
                        @endphp
                        <div class="p-4 md:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/70 dark:hover:bg-[#161f33]/60 transition-colors">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden text-slate-500">
                                    @if($firstProduct && $firstProduct->images->count() > 0)
                                        @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-[20px]">code</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                        {{ $firstProduct->name ?? 'Produk Digital' }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5 font-mono">
                                        <span>{{ $order->invoice_number }}</span>
                                        <span>•</span>
                                        <span>{{ $order->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-1 shrink-0">
                                <div class="text-sm font-extrabold text-slate-900 dark:text-white">
                                    Rp {{ number_format($amountForTenant, 0, ',', '.') }}
                                </div>
                                <div>
                                    @if($order->status === 'paid' || $order->status === 'downloaded')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                        </span>
                                    @elseif($order->status === 'failed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">shopping_bag</span>
                            <p class="text-sm">Belum ada transaksi penjualan baru.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Feature Shortcuts Banner -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('tenant.appearance.index') }}" class="group bg-slate-50 hover:bg-slate-100/80 dark:bg-[#131b2e] border border-slate-200 dark:border-[#263553] rounded-2xl p-5 hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">palette</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors flex items-center gap-1">
                            Dekorasi Toko <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kustomisasi banner, tampilan beranda, dan tema etalase toko Anda.</p>
                    </a>

                    <a href="{{ route('tenant.bank.index') }}" class="group bg-slate-50 hover:bg-slate-100/80 dark:bg-[#101e33] border border-slate-200 dark:border-[#1d3559] rounded-2xl p-5 hover:border-sky-400 dark:hover:border-sky-500 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">credit_card</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors flex items-center gap-1">
                            Rekening Bank <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Atur data rekening bank tujuan untuk pencairan dana otomatis.</p>
                    </a>
                </div>
            </div>

            <!-- Right Area: Store Products & Performance Quick Guide (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Pusat Iklan Toko & Promosi Platform -->
                <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-5 md:p-6 relative overflow-hidden shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 text-[#00838f] dark:text-teal-400 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">ads_click</span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-sm text-slate-900 dark:text-white">Iklan Toko & Promosi</h3>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400">Tingkatkan penjualan dengan iklan bersponsor</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px] font-bold">Rhantech Ads</span>
                    </div>

                    @if(isset($hasClaimedWelcomeVoucher) && !$hasClaimedWelcomeVoucher)
                    <div class="my-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#00838f] text-[20px]">redeem</span>
                            <div>
                                <div class="text-xs font-black text-slate-900 dark:text-white">Bonus Saldo Rp500.000</div>
                                <div class="text-[10px] text-slate-500 dark:text-slate-400">Tingkatkan kunjungan toko hingga +30%</div>
                            </div>
                        </div>
                        <form action="{{ route('tenant.ads.claim-voucher') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#00838f] hover:bg-[#00727d] text-white text-[11px] font-bold shadow-xs cursor-pointer">
                                Klaim
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="my-2 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-1.5 text-[11px] font-bold text-emerald-700 dark:text-emerald-300">
                        <span class="material-symbols-outlined text-[14px]">verified</span>
                        <span>Bonus Saldo Rp500.000 Aktif (+30% Kunjungan)</span>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3 my-3 p-3 rounded-xl bg-slate-50/80 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800">
                        <div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Saldo Iklan</div>
                            <div class="text-base font-black {{ ($adBalance ?? 0) <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-900 dark:text-white' }} mt-0.5">
                                Rp {{ number_format($adBalance ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Iklan Aktif</div>
                            <div class="text-base font-black text-slate-900 dark:text-white mt-0.5">
                                {{ number_format($activeAdsCount ?? 0) }} Kampanye
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <a href="{{ route('tenant.ads.index') }}" class="flex-1 py-2 px-3 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs font-bold text-center transition-colors flex items-center justify-center gap-1" style="background: #0284c7 !important; color: #ffffff !important;">
                            <span>Buka Pusat Iklan</span>
                        </a>
                        <a href="{{ route('tenant.ads.top-up') }}" class="py-2 px-3 rounded-xl border border-sky-300 dark:border-sky-800 text-sky-600 dark:text-sky-400 hover:bg-sky-50 dark:hover:bg-sky-950/30 text-xs font-bold transition-colors">
                            + Isi Saldo
                        </a>
                    </div>
                </div>

                <!-- Store Quick Glance -->
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Koleksi Produk Anda</h2>
                        <a href="{{ route('tenant.products.index') }}" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($topProducts as $prod)
                        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors border border-transparent hover:border-slate-200/60 dark:hover:border-[#222f49]">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                @if($prod->images->count() > 0)
                                    @php $prodImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $prodImg->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-slate-400">inventory_2</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $prod->name }}</h4>
                                <div class="text-xs font-bold text-sky-600 dark:text-sky-400 mt-0.5">
                                    Rp {{ number_format($prod->discount_price ?? $prod->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('tenant.products.edit', $prod) }}" class="p-1.5 text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors" title="Edit Produk">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                        </div>
                        @empty
                        <div class="py-8 text-center text-slate-400">
                            <p class="text-xs">Belum ada produk yang diunggah.</p>
                            <a href="{{ route('tenant.products.create') }}" class="mt-2 inline-block text-xs font-bold text-sky-600 dark:text-sky-400 underline">
                                Tambah Sekarang
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pengikut Toko Terbaru -->
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-pink-500 text-[20px]">group</span>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Pengikut Toko</h2>
                        </div>
                        <span class="text-xs font-bold text-pink-600 dark:text-pink-400 bg-pink-500/10 px-2.5 py-0.5 rounded-full">
                            {{ $followersCount ?? 0 }} Pengikut
                        </span>
                    </div>

                    @if(isset($recentFollowers) && $recentFollowers->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($recentFollowers as $follower)
                            <div class="flex items-center justify-between gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors border border-slate-100 dark:border-slate-800/60">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full overflow-hidden bg-slate-200 dark:bg-slate-700 shrink-0">
                                        <x-user-avatar :user="$follower" class="w-full h-full" />
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $follower->name }}</div>
                                        <div class="text-[10px] text-slate-400 truncate">{{ $follower->email }}</div>
                                    </div>
                                </div>
                                <span class="text-[10px] text-slate-400 whitespace-nowrap">
                                    {{ $follower->pivot->created_at ? $follower->pivot->created_at->diffForHumans() : 'Baru saja' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-slate-400">
                            <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600 mb-1">person_add</span>
                            <p class="text-xs">Belum ada pengikut toko baru.</p>
                            <p class="text-[11px] text-slate-500 mt-1">Bagikan tautan tokomu ke media sosial untuk menarik pengikut setia.</p>
                        </div>
                    @endif
                </div>

                <!-- Tips & Growth Guide -->
                <div class="bg-gradient-to-br from-sky-900 to-slate-900 text-white rounded-2xl p-6 border border-sky-700/50 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                        </div>
                        <h3 class="font-bold text-sm text-white">Tips Penjualan Optimal</h3>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Lengkapi deskripsi produk digital Anda dengan informasi spesifikasi source code/aplikasi, panduan instalasi, dan link demo langsung untuk meningkatkan kepercayaan calon pembeli.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Pusat Bantuan Mitra</span>
                        <a href="{{ route('contact') }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition-colors flex items-center gap-0.5">
                            Hubungi Tim Support <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </a>
                    </div>
                </div>

            </div>

        </div><!-- Closing hidden md:block space-y-8 -->

        <!-- Modal QR Code Toko (Shared by Desktop and Mobile) -->
        <div x-show="showQrModal" x-cloak style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
            <div @click.outside="showQrModal = false"
                 class="bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-2xl p-6 max-w-sm w-full text-center relative">
                <button type="button" @click="showQrModal = false" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-full">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>

                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-[#00838f] dark:text-teal-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">qr_code_2</span>
                </div>

                <h3 class="text-base font-black text-slate-900 dark:text-white mb-1">QR Code Toko Anda</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                    Scan dengan kamera HP untuk langsung membuka toko: <span class="font-bold text-[#00838f] dark:text-teal-400">{{ $storeTitle }}</span>
                </p>

                <div class="p-3 bg-white rounded-xl border border-slate-200 inline-block mb-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $encodedUrl }}" 
                         alt="QR Code Toko {{ $storeTitle }}"
                         class="w-48 h-48 rounded-lg object-contain mx-auto">
                </div>

                <div class="space-y-2">
                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ $encodedUrl }}&download=1"
                       target="_blank" download="qr-toko-{{ $storeSlug }}.png"
                       class="w-full py-2.5 px-4 bg-[#00838f] hover:bg-[#00727d] text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">download</span>
                        <span>Download Gambar QR Code</span>
                    </a>
                    <button type="button" @click="copyToClipboard(); showQrModal = false;"
                            class="w-full py-2 px-4 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Salin Tautan Saja
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    (function() {
        let tenantSalesChartInstance = null;
        let tenantSalesChartMobileInstance = null;
        let currentTrendPeriod = 'monthly';

        const revMonthlyLabels = @json($monthLabels ?? []);
        const revMonthlyData = @json($monthlySales ?? []);
        const revDailyLabels = @json($dailyLabels ?? []);
        const revDailyData = @json($dailySales ?? []);
        const currentMonthName = @json($currentMonthName ?? 'Bulan Ini');

        // Activity Chart Data (7-day: Views, Clicks, Orders)
        const activityLabels = @json($activityChartLabels ?? []);
        const activityViews = @json($activityViewsData ?? []);
        const activityClicks = @json($activityClicksData ?? []);
        const activityOrders = @json($activityOrdersData ?? []);

        let tenantActivityChartInstance = null;
        let tenantActivityChartMobileInstance = null;

        function initTenantActivityChart() {
            if (typeof Chart === 'undefined') return;
            const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#0F172A' : '#1E293B',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(ctx) {
                                const icons = ['👁', '🖱', '🛒'];
                                return ' ' + ctx.dataset.label + ': ' + ctx.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 }, color: isDark ? '#94A3B8' : '#64748B' }
                    },
                    y: {
                        grid: { color: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)', drawBorder: false },
                        ticks: {
                            font: { size: 11 },
                            color: isDark ? '#94A3B8' : '#64748B',
                            precision: 0
                        }
                    }
                }
            };

            const chartData = {
                labels: activityLabels,
                datasets: [
                    {
                        label: 'View Toko',
                        data: activityViews,
                        borderColor: '#a855f7',
                        backgroundColor: 'rgba(168, 85, 247, 0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#a855f7',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Klik Produk',
                        data: activityClicks,
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14, 165, 233, 0.10)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#0ea5e9',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1.5,
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Order Masuk',
                        data: activityOrders,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                    }
                ]
            };

            // Desktop
            const canvas = document.getElementById('tenantActivityChart');
            if (canvas) {
                const existing = Chart.getChart(canvas);
                if (existing) existing.destroy();
                if (tenantActivityChartInstance) { try { tenantActivityChartInstance.destroy(); } catch(e) {} }
                tenantActivityChartInstance = new Chart(canvas.getContext('2d'), {
                    type: 'line',
                    data: chartData,
                    options: commonOptions
                });
            }

            // Mobile
            const canvasM = document.getElementById('tenantActivityChartMobile');
            if (canvasM) {
                const existingM = Chart.getChart(canvasM);
                if (existingM) existingM.destroy();
                if (tenantActivityChartMobileInstance) { try { tenantActivityChartMobileInstance.destroy(); } catch(e) {} }
                const mobileOpts = JSON.parse(JSON.stringify(commonOptions));
                mobileOpts.scales.x.ticks.font = { size: 9 };
                mobileOpts.scales.y.ticks.font = { size: 9 };
                tenantActivityChartMobileInstance = new Chart(canvasM.getContext('2d'), {
                    type: 'line',
                    data: chartData,
                    options: mobileOpts
                });
            }
        }


        function initTenantSalesChart() {
            if (typeof Chart === 'undefined') return;

            const isDark = document.documentElement.classList.contains('dark') || document.body.classList.contains('dark');
            const isDaily = currentTrendPeriod === 'daily';
            const labels = isDaily ? revDailyLabels : revMonthlyLabels;
            const data = isDaily ? revDailyData : revMonthlyData;
            const barPct = isDaily ? 0.75 : 0.55;

            // --- 1. Desktop Chart ---
            const canvas = document.getElementById('tenantSalesChart');
            if (canvas) {
                const existing = Chart.getChart(canvas);
                if (existing) existing.destroy();
                if (tenantSalesChartInstance) {
                    try { tenantSalesChartInstance.destroy(); } catch(e) {}
                    tenantSalesChartInstance = null;
                }

                const ctx = canvas.getContext('2d');
                let gradient = ctx.createLinearGradient(0, 0, 0, 240);
                if (isDaily) {
                    gradient.addColorStop(0, 'rgba(14, 165, 233, 0.9)'); // sky-500
                    gradient.addColorStop(1, 'rgba(14, 165, 233, 0.25)');
                } else {
                    gradient.addColorStop(0, 'rgba(2, 132, 199, 0.95)'); // sky-600
                    gradient.addColorStop(1, 'rgba(2, 132, 199, 0.3)');
                }

                tenantSalesChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: data,
                            backgroundColor: gradient,
                            hoverBackgroundColor: isDaily ? '#0284c7' : '#0369a1',
                            borderRadius: isDaily ? 3 : 6,
                            borderSkipped: false,
                            barPercentage: barPct,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? '#0F172A' : '#1E293B',
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { family: 'Geist', size: 13, weight: 'bold' },
                                bodyFont: { family: 'Geist', size: 14, weight: '600' },
                                callbacks: {
                                    title: function(items) {
                                        if (!items.length) return '';
                                        if (currentTrendPeriod === 'daily') {
                                            return 'Tanggal ' + items[0].label + ' ' + currentMonthName;
                                        }
                                        return 'Bulan ' + items[0].label;
                                    },
                                    label: function(context) {
                                        return 'Penjualan: Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false, drawBorder: false },
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: isDaily ? 16 : 12,
                                    font: { family: 'Geist', size: 11 },
                                    color: isDark ? '#94A3B8' : '#64748B'
                                }
                            },
                            y: {
                                grid: {
                                    color: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { family: 'Geist', size: 11 },
                                    color: isDark ? '#94A3B8' : '#64748B',
                                    callback: function(v) {
                                        if (v >= 1000000) return (v / 1000000) + 'M';
                                        if (v >= 1000) return (v / 1000) + 'K';
                                        return v;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // --- 2. Mobile Mini Chart ---
            const canvasMobile = document.getElementById('tenantSalesChartMobile');
            if (canvasMobile) {
                const existingM = Chart.getChart(canvasMobile);
                if (existingM) existingM.destroy();
                if (tenantSalesChartMobileInstance) {
                    try { tenantSalesChartMobileInstance.destroy(); } catch(e) {}
                    tenantSalesChartMobileInstance = null;
                }

                const ctxM = canvasMobile.getContext('2d');
                let gradientM = ctxM.createLinearGradient(0, 0, 0, 180);
                if (isDaily) {
                    gradientM.addColorStop(0, 'rgba(14, 165, 233, 0.9)');
                    gradientM.addColorStop(1, 'rgba(14, 165, 233, 0.2)');
                } else {
                    gradientM.addColorStop(0, 'rgba(2, 132, 199, 0.95)');
                    gradientM.addColorStop(1, 'rgba(2, 132, 199, 0.25)');
                }

                tenantSalesChartMobileInstance = new Chart(ctxM, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: data,
                            backgroundColor: gradientM,
                            hoverBackgroundColor: isDaily ? '#0284c7' : '#0369a1',
                            borderRadius: 4,
                            borderSkipped: false,
                            barPercentage: isDaily ? 0.7 : 0.5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? '#0F172A' : '#1E293B',
                                padding: 8,
                                cornerRadius: 6,
                                titleFont: { family: 'Geist', size: 11, weight: 'bold' },
                                bodyFont: { family: 'Geist', size: 12, weight: '600' },
                                callbacks: {
                                    title: function(items) {
                                        if (!items.length) return '';
                                        return (currentTrendPeriod === 'daily' ? 'Tgl ' : 'Bln ') + items[0].label;
                                    },
                                    label: function(context) {
                                        return 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false, drawBorder: false },
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: isDaily ? 8 : 6,
                                    font: { family: 'Geist', size: 10 },
                                    color: isDark ? '#94A3B8' : '#64748B'
                                }
                            },
                            y: {
                                grid: {
                                    color: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { family: 'Geist', size: 10 },
                                    color: isDark ? '#94A3B8' : '#64748B',
                                    callback: function(v) {
                                        if (v >= 1000000) return (v / 1000000) + 'M';
                                        if (v >= 1000) return (v / 1000) + 'K';
                                        return v;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Update Total Periode Ini text for Desktop & Mobile
            const totalSum = data.reduce((acc, val) => acc + (Number(val) || 0), 0);
            const totalElem = document.getElementById('tenantPeriodTotal');
            if (totalElem) {
                totalElem.textContent = 'Rp ' + Number(totalSum).toLocaleString('id-ID');
            }
            const totalElemMobile = document.getElementById('tenantPeriodTotalMobile');
            if (totalElemMobile) {
                totalElemMobile.textContent = 'Rp ' + Number(totalSum).toLocaleString('id-ID');
            }
        }

        window.switchTenantTrendPeriod = function(period) {
            currentTrendPeriod = period;
            const btnMonthly = document.getElementById('btnTenantPeriodMonthly');
            const btnDaily = document.getElementById('btnTenantPeriodDaily');
            const subtitle = document.getElementById('tenantTrendSubtitle');

            const btnMonthlyM = document.getElementById('btnTenantPeriodMonthlyMobile');
            const btnDailyM = document.getElementById('btnTenantPeriodDailyMobile');

            const activeClass = "px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all bg-sky-500 text-white dark:bg-sky-600 dark:text-white shadow-2xs cursor-pointer";
            const inactiveClass = "px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 cursor-pointer";

            const activeClassM = "px-2.5 py-1 text-[11px] font-bold rounded-lg transition-all bg-sky-500 text-white dark:bg-sky-600 dark:text-white shadow-2xs cursor-pointer";
            const inactiveClassM = "px-2.5 py-1 text-[11px] font-bold rounded-lg transition-all text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 cursor-pointer";

            if (period === 'daily') {
                if (btnDaily) btnDaily.className = activeClass;
                if (btnMonthly) btnMonthly.className = inactiveClass;
                if (btnDailyM) btnDailyM.className = activeClassM;
                if (btnMonthlyM) btnMonthlyM.className = inactiveClassM;
                if (subtitle) subtitle.textContent = "Grafik penjualan harian per tanggal di bulan " + currentMonthName;
            } else {
                if (btnMonthly) btnMonthly.className = activeClass;
                if (btnDaily) btnDaily.className = inactiveClass;
                if (btnMonthlyM) btnMonthlyM.className = activeClassM;
                if (btnDailyM) btnDailyM.className = inactiveClassM;
                if (subtitle) subtitle.textContent = "Grafik pendapatan riil 6 bulan terakhir";
            }

            initTenantSalesChart();
        };

        if (document.readyState !== 'loading') {
            initTenantSalesChart();
            initTenantActivityChart();
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                initTenantSalesChart();
                initTenantActivityChart();
            });
        }

        document.addEventListener('livewire:navigated', function() {
            setTimeout(function() {
                initTenantSalesChart();
                initTenantActivityChart();
            }, 50);
        });
    })();
</script>
@endsection
