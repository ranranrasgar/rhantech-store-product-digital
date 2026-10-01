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

<div class="flex-1 overflow-y-auto p-3 sm:p-4 md:p-8 bg-[#fafafa] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] transition-colors duration-200"
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
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
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
                                <h1 class="text-base font-extrabold text-zinc-900 dark:text-zinc-100 truncate">
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
                    <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-zinc-50 dark:bg-zinc-900 hover:bg-slate-100 dark:hover:bg-slate-700 border border-zinc-200 dark:border-zinc-800 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center gap-1 shrink-0 active:scale-95 shadow-2xs">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">storefront</span>
                        <span>Toko</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- 2. Native Wallet & Finance Card (Dompet Seller) -->
            <div class="rounded-2xl bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 p-4 sm:p-5 shadow-xs relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1.5 text-zinc-500 dark:text-zinc-400 text-xs font-semibold">
                            <span class="material-symbols-outlined text-[18px] text-zinc-700 dark:text-zinc-300">account_balance_wallet</span>
                            <span>Saldo Toko Siap Ditarik</span>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-900 text-[10px] font-bold text-zinc-600 dark:text-zinc-300">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Otomatis
                        </span>
                    </div>

                    <div class="mt-2 text-2xl sm:text-3xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">
                        Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}
                    </div>

                    <!-- Wallet Action Bar -->
                    <div class="mt-4 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-2.5">
                        <a href="{{ route('tenant.payouts.index') }}" class="flex-1 py-2.5 px-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-2xs active:scale-95 text-center">
                            <span class="material-symbols-outlined text-[17px]">payments</span>
                            <span>Tarik Dana</span>
                        </a>

                        <a href="{{ route('tenant.ads.index') }}" class="flex-1 py-2.5 px-3 rounded-xl bg-zinc-100 dark:bg-zinc-900 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center justify-center gap-1.5 border border-zinc-200 dark:border-zinc-800 active:scale-95 text-center">
                            <span class="material-symbols-outlined text-[16px] text-slate-500">ads_click</span>
                            <span class="truncate">Iklan: Rp {{ number_format($adBalance ?? 0, 0, ',', '.') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Native App Quick Action Grid (8 Sleek Modern Tiles) -->
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3.5 shadow-xs">
                <div class="grid grid-cols-4 gap-2 text-center">
                    <!-- Action 1: Tambah Produk -->
                    <a href="{{ route('tenant.products.create') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Tambah Produk</span>
                    </a>

                    <!-- Action 2: Pesanan Penjualan -->
                    <a href="{{ route('tenant.orders.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group relative">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all relative">
                            <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                            @if(($pendingOrdersCount ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center ring-2 ring-white dark:ring-slate-900">
                                    {{ $pendingOrdersCount }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Pesanan</span>
                    </a>

                    <!-- Action 3: Pusat Iklan -->
                    <a href="{{ route('tenant.ads.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group relative">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all relative">
                            <span class="material-symbols-outlined text-[20px]">campaign</span>
                            @if(isset($hasClaimedWelcomeVoucher) && !$hasClaimedWelcomeVoucher)
                                <span class="absolute -top-1 -right-1 px-1 rounded-full bg-rose-500 text-white text-[8px] font-black uppercase tracking-wider">
                                    Bonus
                                </span>
                            @endif
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Iklan Toko</span>
                    </a>

                    <!-- Action 4: Tampilan & Tema -->
                    <a href="{{ route('tenant.appearance.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">palette</span>
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Dekorasi</span>
                    </a>

                    <!-- Action 5: Rekening Bank -->
                    <a href="{{ route('tenant.bank.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">credit_card</span>
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Rekening</span>
                    </a>

                    <!-- Action 6: Bagikan Toko (Share) -->
                    <button type="button" @click="shareNative()" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group cursor-pointer">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">share</span>
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Bagikan</span>
                    </button>

                    <!-- Action 7: Analitik Performa -->
                    <a href="{{ route('tenant.performance.index') }}" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">monitoring</span>
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Analitik</span>
                    </a>

                    <!-- Action 8: Pusat Bantuan -->
                    <a href="{{ route('help.index') }}" target="_blank" class="flex flex-col items-center p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all group">
                        <div class="w-11 h-11 rounded-2xl bg-zinc-100 dark:bg-zinc-900/80 text-slate-700 dark:text-slate-200 flex items-center justify-center mb-1.5 border border-slate-200/60 dark:border-slate-700/60 shadow-2xs group-hover:bg-orange-500 group-hover:text-white dark:group-hover:bg-orange-500 dark:group-hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[20px]">help</span>
                        </div>
                        <span class="text-[11px] font-semibold text-zinc-700 dark:text-zinc-300 leading-tight">Bantuan</span>
                    </a>
                </div>
            </div>

            <!-- 4. Horizontal Swipeable Metric Chips (Statistik Kilat) -->
            <div>
                <div class="flex items-center justify-between px-1 mb-2">
                    <h2 class="text-xs font-extrabold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">
                        Ringkasan Toko
                    </h2>
                    <span class="text-[11px] text-slate-400 font-medium">Geser untuk melihat »</span>
                </div>

                <div class="flex gap-2.5 overflow-x-auto pb-2 snap-x snap-mandatory scrollbar-none" style="-webkit-overflow-scrolling: touch;">
                    <!-- Metric Card 1: Pengunjung -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400">Pengunjung</span>
                            <span class="w-6 h-6 rounded-lg bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-zinc-900 dark:text-zinc-100">
                            {{ number_format($totalVisitors ?? 0) }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5 truncate">
                            {{ number_format($productViews ?? 0) }} view produk
                        </div>
                    </div>

                    <!-- Metric Card 2: Pesanan Sukses -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400">Order Sukses</span>
                            <span class="w-6 h-6 rounded-lg bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">verified</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-zinc-900 dark:text-zinc-100">
                            {{ number_format($completedOrdersCount ?? 0) }}
                        </div>
                        <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">
                            {{ $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100 }}% Sukses
                        </div>
                    </div>

                    <!-- Metric Card 3: Menunggu Bayar -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400">Tertunda</span>
                            <span class="w-6 h-6 rounded-lg bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">schedule</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-zinc-900 dark:text-zinc-100">
                            {{ number_format($pendingOrdersCount ?? 0) }}
                        </div>
                        <div class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mt-0.5">
                            Menunggu bayar
                        </div>
                    </div>

                    <!-- Metric Card 4: Katalog Produk -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400">Produk Aktif</span>
                            <span class="w-6 h-6 rounded-lg bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">inventory_2</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-zinc-900 dark:text-zinc-100">
                            {{ number_format($activeProducts ?? 0) }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">
                            Dari {{ number_format($totalProducts ?? 0) }} produk
                        </div>
                    </div>

                    <!-- Metric Card 5: Pengikut Toko -->
                    <div class="snap-start shrink-0 w-[145px] bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3.5 shadow-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-zinc-500 dark:text-zinc-400">Pengikut</span>
                            <span class="w-6 h-6 rounded-lg bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[15px]">group</span>
                            </span>
                        </div>
                        <div class="text-lg font-black text-zinc-900 dark:text-zinc-100">
                            {{ number_format($followersCount ?? 0) }}
                        </div>
                        <div class="text-[10px] text-slate-500 font-bold mt-0.5">
                            Pelanggan setia
                        </div>
                    </div>
                </div>
            </div>

            <!-- 5b. Native Activity Chart Mobile (View, Klik, Order 7 Hari) -->
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between gap-2 pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">show_chart</span>
                        </span>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">Aktivitas 7 Hari</h3>
                    </div>
                    <div class="flex items-center gap-1.5 text-[10px] font-bold flex-wrap justify-end">
                        <span class="flex items-center gap-1 text-purple-600 dark:text-purple-400"><span class="w-2 h-2 rounded-full bg-purple-500"></span>View</span>
                        <span class="flex items-center gap-1 text-orange-600 dark:text-orange-400"><span class="w-2 h-2 rounded-full bg-orange-500"></span>Klik</span>
                        <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400"><span class="w-2 h-2 rounded-full bg-emerald-500"></span>Order</span>
                    </div>
                </div>
                <div class="mt-3 relative h-44 w-full">
                    <canvas id="tenantActivityChartMobile"></canvas>
                </div>
                <div class="mt-3 pt-2.5 border-t border-zinc-100 dark:border-zinc-800 grid grid-cols-3 gap-2 text-center">
                    <div>
                        <div class="text-xs font-black text-purple-600 dark:text-purple-400">{{ number_format($activityTotalViews ?? 0) }}</div>
                        <div class="text-[10px] text-slate-400">Views</div>
                    </div>
                    <div>
                        <div class="text-xs font-black text-orange-600 dark:text-orange-400">{{ number_format($activityTotalClicks ?? 0) }}</div>
                        <div class="text-[10px] text-slate-400">Klik</div>
                    </div>
                    <div>
                        <div class="text-xs font-black text-emerald-600 dark:text-emerald-400">{{ number_format($activityTotalOrders ?? 0) }}</div>
                        <div class="text-[10px] text-slate-400">Order</div>
                    </div>
                </div>
            </div>

            <!-- 6. Native Recent Orders Section -->
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-zinc-700 dark:text-zinc-300 text-[20px]">receipt_long</span>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">Pesanan Terkini</h3>
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
                            <div class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex items-center justify-center shrink-0 overflow-hidden text-slate-500">
                                @if($firstProduct && $firstProduct->images->count() > 0)
                                    @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-[20px]">code</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">
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
                            <div class="text-xs font-extrabold text-zinc-900 dark:text-zinc-100">
                                Rp {{ number_format($amountForTenant, 0, ',', '.') }}
                            </div>
                            <div class="mt-0.5">
                                @if($order->status === 'paid' || $order->status === 'downloaded')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm border border-emerald-500/20">
                                        Sukses
                                    </span>
                                @elseif($order->status === 'failed')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm border border-amber-500/20">
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
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-9 h-9 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-slate-700 dark:text-slate-200 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">qr_code_2</span>
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">Bagikan Tautan Toko</div>
                            <div class="text-[10px] text-slate-400 truncate">Siap dipasang di bio IG & TikTok</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <button type="button" @click="copyToClipboard()" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-2xs" :class="copied ? 'bg-emerald-600 text-white' : 'bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white'">
                            <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                        </button>
                        <button type="button" @click="showQrModal = true" class="p-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all active:scale-95 cursor-pointer" title="QR Code">
                            <span class="material-symbols-outlined text-[18px]">qr_code</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- 8. Native Top Clicked Products Section -->
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
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
                        <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0 flex items-center justify-center">
                            @if($prod->images->count() > 0)
                                @php $prodImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first(); @endphp
                                <img src="{{ asset('storage/' . $prodImg->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-slate-400 text-[18px]">inventory_2</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">{{ $prod->name }}</h4>
                            <div class="flex items-center gap-2 text-[11px] mt-0.5">
                                <span class="font-bold text-zinc-900 dark:text-zinc-100">Rp {{ number_format($prod->discount_price ?? $prod->price, 0, ',', '.') }}</span>
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
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-orange-500">search</span>
                        Paling Banyak Dicari Pembeli
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400">Tren Pasar</span>
                </div>

                <div class="space-y-2">
                    @forelse($topMarketSearches as $search)
                    <div class="p-2.5 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/30 flex items-center justify-between gap-2">
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate capitalize"># {{ $search->keyword }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                @if($search->results_count > 0)
                                    <span class="text-emerald-600 dark:text-emerald-400">{{ $search->results_count }} Produk</span>
                                @else
                                    <span class="text-amber-600 font-bold">0 Produk (Peluang Emas!)</span>
                                @endif
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-zinc-200 dark:border-zinc-800 text-xs font-black text-zinc-700 dark:text-zinc-300 shrink-0">
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
        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 space-y-6 shadow-xs">
            <div class="pb-6 border-b border-zinc-100 dark:border-zinc-800 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Left: Store Info -->
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-xl p-0.5 bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0">
                        @if($store && $store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full bg-[#00838f] rounded-xl flex items-center justify-center font-black text-xl text-white">
                                {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-900 text-[9px] font-semibold text-zinc-700 dark:text-zinc-300 mb-1 border border-zinc-200 dark:border-zinc-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Merchant Partner
                        </div>
                        <h1 class="text-xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                            {{ $store->name ?? 'Toko Saya' }}
                        </h1>
                        <a href="{{ route('store.show', $store->slug ?? 'toko-'.$store->id) }}" target="_blank" class="text-xs text-orange-600 dark:text-orange-400 hover:underline flex items-center gap-1 mt-0.5 font-semibold">
                            Lihat Toko Publik <span class="material-symbols-outlined text-[12px]">open_in_new</span>
                        </a>
                    </div>
                </div>

                <!-- Right: Compact Share & Action -->
                @php
                    $storeSlug = $store->slug ?: 'toko-' . $store->id;
                    $storeDirectUrl = url('/' . $storeSlug);
                @endphp
                <div class="flex flex-wrap items-center gap-3" x-data="{
                    storeUrl: '{{ $storeDirectUrl }}',
                    copied: false,
                    copyLink() {
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(this.storeUrl).then(() => {
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2000);
                            });
                        }
                    }
                }">
                    <!-- Compact Copy Link -->
                    <div class="flex items-center bg-slate-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 rounded-xl p-1 shrink-0">
                        <span class="material-symbols-outlined text-[16px] text-zinc-400 ml-2">link</span>
                        <input type="text" readonly :value="storeUrl" class="bg-transparent border-none px-2 py-1 text-[11px] font-mono text-zinc-600 dark:text-zinc-400 focus:outline-none w-32 sm:w-48 truncate cursor-pointer" @click="copyLink()">
                        <button type="button" @click="copyLink()" class="px-3 py-1.5 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-[10px] font-bold rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-700 transition-colors shadow-xs" :class="copied ? 'text-emerald-600' : 'text-zinc-700 dark:text-zinc-300'">
                            <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                        </button>
                    </div>

                    <a href="{{ route('tenant.products.create') }}" class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs flex items-center gap-1.5 transition-colors shrink-0 shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Tambah Produk
                    </a>
                </div>
            </div>

        <!-- 6 Essential Metrics Cards (Classic Unified Row) -->
        <div class="grid grid-cols-2 lg:grid-cols-6 divide-y lg:divide-y-0 lg:divide-x divide-zinc-100 dark:divide-zinc-800">
            
            <!-- Metric 1: Total Revenue -->
            <div class="px-2 py-3 flex flex-col justify-center text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Total Saldo</span>
                <span class="text-xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</span>
                <a href="{{ route('tenant.payouts.index') }}" class="mt-1 text-[11px] font-bold text-orange-600 dark:text-orange-400 hover:underline">Tarik Saldo</a>
            </div>

            <!-- Metric 2: Total Pengunjung -->
            <div class="px-2 py-3 flex flex-col justify-center text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Pengunjung</span>
                <span class="text-xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">{{ number_format($totalVisitors ?? 0) }}</span>
                <span class="mt-1 text-[11px] text-zinc-500 dark:text-zinc-400"><span class="font-bold text-purple-600 dark:text-purple-400">+{{ $todayStoreVisits ?? 0 }}</span> hari ini</span>
            </div>

            <!-- Metric 3: Completed Orders -->
            <div class="px-2 py-3 flex flex-col justify-center text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Pesanan Sukses</span>
                <span class="text-xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">{{ number_format($completedOrdersCount ?? 0) }}</span>
                <span class="mt-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">{{ $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100 }}% Sukses</span>
            </div>

            <!-- Metric 4: Pending Orders -->
            <div class="px-2 py-3 flex flex-col justify-center text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Menunggu Bayar</span>
                <span class="text-xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">{{ number_format($pendingOrdersCount ?? 0) }}</span>
                <a href="{{ route('tenant.orders.index', ['tab' => 'pending']) }}" class="mt-1 text-[11px] font-bold text-amber-600 dark:text-amber-400 hover:underline">Cek Invoice</a>
            </div>

            <!-- Metric 5: Active Products -->
            <div class="px-2 py-3 flex flex-col justify-center text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Katalog Produk</span>
                <span class="text-xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">{{ number_format($activeProducts ?? 0) }} <span class="text-[10px] font-semibold text-slate-400">/ {{ number_format($totalProducts ?? 0) }}</span></span>
                <a href="{{ route('tenant.products.index') }}" class="mt-1 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">Kelola</a>
            </div>

            <!-- Metric 6: Followers -->
            <div class="px-2 py-3 flex flex-col justify-center text-center">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-1">Pengikut Toko</span>
                <span class="text-xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">{{ number_format($followersCount ?? 0) }}</span>
                <a href="{{ url('/' . $storeSlug) }}" target="_blank" class="mt-1 text-[11px] font-bold text-pink-600 dark:text-pink-400 hover:underline">Lihat Toko</a>
            </div>

        </div>

                </div>
        <!-- End Unified Top Card -->

        <!-- Section: Analitik Pengunjung & Minat Pembeli (Visitor Traffic, Top Clicked, Top Searches) -->
        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 md:p-6 mb-8 shadow-xs space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-zinc-100 dark:border-zinc-800">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">visibility</span>
                    </div>
                    <div>
                        <h2 class="text-base md:text-lg font-bold text-zinc-900 dark:text-zinc-100">Analisis Pengunjung & Minat Pembeli</h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Pantau produk tokomu yang paling banyak dilihat calon pembeli dan kata kunci yang sedang dicari di marketplace.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('tenant.performance.index') }}" class="px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-xs font-bold text-zinc-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">analytics</span>
                        <span>Statistik Lengkap</span>
                    </a>
                </div>
            </div>

            <!-- 4 Quick Traffic Highlight Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                        <span>Total Kunjungan Toko</span>
                        <span class="material-symbols-outlined text-[18px] text-purple-500">storefront</span>
                    </div>
                    <div class="text-xl font-black text-zinc-900 dark:text-zinc-100">
                        {{ number_format($totalVisitors ?? 0) }}
                    </div>
                    <div class="text-[11px] text-purple-600 dark:text-purple-400 font-bold mt-1">
                        +{{ $todayStoreVisits ?? 0 }} kunjungan hari ini
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400 mb-1">
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
                    <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400 mb-1">
                        <span>Kunjungan Profil Toko</span>
                        <span class="material-symbols-outlined text-[18px] text-orange-500">visibility</span>
                    </div>
                    <div class="text-xl font-black text-orange-600 dark:text-orange-400">
                        {{ number_format($storeViews ?? 0) }}
                    </div>
                    <div class="text-[11px] text-slate-400 mt-1">
                        Halaman etalase toko dibuka
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/60 dark:border-slate-800">
                    <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400 mb-1">
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
            <div class="bg-white dark:bg-[#000000] border border-slate-200/60 dark:border-zinc-800 rounded-2xl p-5 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-orange-500">show_chart</span>
                            Tren Aktivitas Toko (7 Hari Terakhir)
                        </h3>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">View halaman toko, klik produk, dan order masuk per hari</p>
                    </div>
                    <!-- Summary Pills -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm border border-purple-500/20">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            {{ number_format($activityTotalViews ?? 0) }} Views
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm border border-orange-500/20">
                            <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                            {{ number_format($activityTotalClicks ?? 0) }} Klik
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm border border-emerald-500/20">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            {{ number_format($activityTotalOrders ?? 0) }} Order
                        </span>
                        @if(($activityConversionRate ?? 0) > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 shadow-sm border border-amber-500/20">
                            <span class="material-symbols-outlined text-[13px]">trending_up</span>
                            {{ $activityConversionRate ?? 0 }}% Konversi
                        </span>
                        @endif
                    </div>
                </div>

                <div class="relative h-56 w-full">
                    <canvas id="tenantActivityChart"></canvas>
                </div>

                <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex flex-wrap items-center gap-x-5 gap-y-1.5 text-[11px] text-zinc-500 dark:text-zinc-400">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> View Halaman Toko</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> Klik / Lihat Produk</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Order Masuk</span>
                    <span class="ml-auto text-[10px] text-slate-400">*Data 7 hari terakhir</span>
                </div>
            </div>

            <!-- Grid 2 Columns: Produk Terbanyak Diklik (Left) & Kata Kunci Dicari (Right) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Produk Terbanyak Diklik / Dilihat (7 cols) -->
                <div class="lg:col-span-7">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-amber-500">local_fire_department</span>
                            Produk Toko Terbanyak Diklik / Dilihat
                        </h3>
                        <a href="{{ route('tenant.products.index') }}" class="text-xs font-bold text-orange-600 dark:text-orange-400 hover:underline">
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
                                <span class="w-5 h-5 rounded-full {{ $idx === 0 ? 'bg-amber-500 text-white' : ($idx === 1 ? 'bg-slate-400 text-white' : ($idx === 2 ? 'bg-amber-700 text-white' : 'bg-slate-200 dark:bg-slate-700 text-zinc-600 dark:text-zinc-300')) }} text-[10px] font-black flex items-center justify-center shrink-0">
                                    {{ $idx + 1 }}
                                </span>
                                <div class="w-10 h-10 rounded-lg bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($pImg)
                                        <img src="{{ asset('storage/' . $pImg->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-[18px] text-slate-400">code</span>
                                    @endif
                                </div>
                                <div class="min-w-0 max-w-[200px] sm:max-w-xs">
                                    <a href="{{ route('products.show', $prod->slug) }}" target="_blank" class="text-xs font-bold text-zinc-900 dark:text-zinc-100 hover:text-orange-600 truncate block" title="{{ $prod->name }}">
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
                                <div class="text-xs font-black text-zinc-900 dark:text-zinc-100">
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
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-orange-500">search</span>
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
                                    <span class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate capitalize">{{ $search->keyword }}</span>
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
                                <span class="px-2 py-0.5 rounded-md bg-white dark:bg-slate-800 border border-zinc-200 dark:border-zinc-800 text-xs font-extrabold text-zinc-700 dark:text-zinc-300">
                                    {{ number_format($search->hits) }}x
                                </span>
                                <a href="{{ route('tenant.products.create') }}" class="p-1 rounded-lg text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 hover:bg-white dark:hover:bg-slate-800 transition-colors" title="Buat Produk Kategori Ini">
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
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
                    <div class="p-5 md:p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Transaksi Penjualan Terbaru</h2>
                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Pesanan produk digital terkini dari pelanggan Anda.</p>
                        </div>
                        <a href="{{ route('tenant.orders.index') }}" class="text-xs font-bold text-orange-600 dark:text-orange-400 hover:underline flex items-center gap-1">
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
                                <div class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 flex items-center justify-center shrink-0 overflow-hidden text-slate-500">
                                    @if($firstProduct && $firstProduct->images->count() > 0)
                                        @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-[20px]">code</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 truncate">
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
                                <div class="text-sm font-extrabold text-zinc-900 dark:text-zinc-100">
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
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors flex items-center gap-1">
                            Dekorasi Toko <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Kustomisasi banner, tampilan beranda, dan tema etalase toko Anda.</p>
                    </a>

                    <a href="{{ route('tenant.bank.index') }}" class="group bg-slate-50 hover:bg-slate-100/80 dark:bg-[#101e33] border border-slate-200 dark:border-[#1d3559] rounded-2xl p-5 hover:border-orange-400 dark:hover:border-orange-500 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">credit_card</span>
                        </div>
                        <h3 class="text-sm font-bold text-zinc-900 dark:text-zinc-100 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors flex items-center gap-1">
                            Rekening Bank <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Atur data rekening bank tujuan untuk pencairan dana otomatis.</p>
                    </a>
                </div>
            </div>

            <!-- Right Area: Store Products & Performance Quick Guide (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Pusat Iklan Toko & Promosi Platform -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 md:p-6 relative overflow-hidden shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-[#00838f] dark:text-teal-400 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">ads_click</span>
                            </div>
                            <div>
                                <h3 class="font-extrabold text-sm text-zinc-900 dark:text-zinc-100">Iklan Toko & Promosi</h3>
                                <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Tingkatkan penjualan dengan iklan bersponsor</p>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 text-[10px] font-bold">Rhantech Ads</span>
                    </div>

                    @if(isset($hasClaimedWelcomeVoucher) && !$hasClaimedWelcomeVoucher)
                    <div class="my-3 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-900/60 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#00838f] text-[20px]">redeem</span>
                            <div>
                                <div class="text-xs font-black text-zinc-900 dark:text-zinc-100">Bonus Saldo Rp500.000</div>
                                <div class="text-[10px] text-zinc-500 dark:text-zinc-400">Tingkatkan kunjungan toko hingga +30%</div>
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
                            <div class="text-[11px] text-zinc-500 dark:text-zinc-400 font-medium">Saldo Iklan</div>
                            <div class="text-base font-black {{ ($adBalance ?? 0) <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-zinc-900 dark:text-zinc-100' }} mt-0.5">
                                Rp {{ number_format($adBalance ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-[11px] text-zinc-500 dark:text-zinc-400 font-medium">Iklan Aktif</div>
                            <div class="text-base font-black text-zinc-900 dark:text-zinc-100 mt-0.5">
                                {{ number_format($activeAdsCount ?? 0) }} Kampanye
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-4">
                        <a href="{{ route('tenant.ads.index') }}" class="flex-1 py-2 px-3 rounded-xl bg-[#ea580c] hover:bg-[#0369a1] text-white text-xs font-bold text-center transition-colors flex items-center justify-center gap-1" style="background: #ea580c !important; color: #ffffff !important;">
                            <span>Buka Pusat Iklan</span>
                        </a>
                        <a href="{{ route('tenant.ads.top-up') }}" class="py-2 px-3 rounded-xl border border-orange-300 dark:border-orange-800 text-orange-600 dark:text-orange-400 hover:bg-orange-50 dark:hover:bg-orange-950/30 text-xs font-bold transition-colors">
                            + Isi Saldo
                        </a>
                    </div>
                </div>

                <!-- Store Quick Glance -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Koleksi Produk Anda</h2>
                        <a href="{{ route('tenant.products.index') }}" class="text-xs font-bold text-orange-600 dark:text-orange-400 hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($topProducts as $prod)
                        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors border border-transparent hover:border-slate-200/60 dark:hover:border-[#222f49]">
                            <div class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0 flex items-center justify-center">
                                @if($prod->images->count() > 0)
                                    @php $prodImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $prodImg->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-slate-400">inventory_2</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">{{ $prod->name }}</h4>
                                <div class="text-xs font-bold text-orange-600 dark:text-orange-400 mt-0.5">
                                    Rp {{ number_format($prod->discount_price ?? $prod->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('tenant.products.edit', $prod) }}" class="p-1.5 text-slate-400 hover:text-orange-600 dark:hover:text-orange-400 transition-colors" title="Edit Produk">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                        </div>
                        @empty
                        <div class="py-8 text-center text-slate-400">
                            <p class="text-xs">Belum ada produk yang diunggah.</p>
                            <a href="{{ route('tenant.products.create') }}" class="mt-2 inline-block text-xs font-bold text-orange-600 dark:text-orange-400 underline">
                                Tambah Sekarang
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Pengikut Toko Terbaru -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-pink-500 text-[20px]">group</span>
                            <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Pengikut Toko</h2>
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
                                        <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">{{ $follower->name }}</div>
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
                <div class="bg-gradient-to-br from-sky-900 to-slate-900 text-white rounded-2xl p-6 border border-orange-700/50 shadow-none">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-orange-500/20 text-orange-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                        </div>
                        <h3 class="font-bold text-sm text-white">Tips Penjualan Optimal</h3>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Lengkapi deskripsi produk digital Anda dengan informasi spesifikasi source code/aplikasi, panduan instalasi, dan link demo langsung untuk meningkatkan kepercayaan calon pembeli.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Pusat Bantuan Mitra</span>
                        <a href="{{ route('contact') }}" class="text-xs font-bold text-orange-400 hover:text-orange-300 transition-colors flex items-center gap-0.5">
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
                 class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 max-w-sm w-full text-center relative">
                <button type="button" @click="showQrModal = false" 
                        class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-full">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>

                <div class="w-12 h-12 rounded-2xl bg-teal-500/10 text-[#00838f] dark:text-teal-400 flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-2xl">qr_code_2</span>
                </div>

                <h3 class="text-base font-black text-zinc-900 dark:text-zinc-100 mb-1">QR Code Toko Anda</h3>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">
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
                            class="w-full py-2 px-4 bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 font-semibold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Salin Tautan Saja
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    (function() {


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
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(14, 165, 233, 0.10)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#f97316',
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


        if (document.readyState !== 'loading') {
            initTenantActivityChart();
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                initTenantActivityChart();
            });
        }

        document.addEventListener('livewire:navigated', function() {
            setTimeout(function() {
                initTenantActivityChart();
            }, 50);
        });
    })();
</script>
@endsection
