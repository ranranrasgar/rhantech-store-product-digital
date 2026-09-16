@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<style>
.leaflet-popup-content-wrapper {
    background: #ffffff;
    color: #0f172a;
    border-radius: 12px;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.15);
    padding: 0;
    overflow: hidden;
}
.dark .leaflet-popup-content-wrapper {
    background: #111726;
    color: #f1f5f9;
    border: 1px solid #222f49;
}
.leaflet-popup-content {
    margin: 0;
    line-height: 1.4;
}
.leaflet-popup-tip {
    background: #ffffff;
}
.dark .leaflet-popup-tip {
    background: #111726;
}
.custom-map-pin {
    background: transparent;
    border: none;
}
#adminGeoMap {
    cursor: grab;
}
#adminGeoMap:active {
    cursor: grabbing;
}
.leaflet-container {
    cursor: grab !important;
}
.leaflet-container.leaflet-drag-target,
.leaflet-dragging .leaflet-container,
.leaflet-dragging .leaflet-grab {
    cursor: grabbing !important;
}
</style>

<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-container-max mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Marketplace Overview</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Ringkasan performa penjualan produk digital, status toko, dan aktivitas transaksi.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-primary text-on-primary rounded-md font-label-md font-bold hover:opacity-90 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    Semua Transaksi
                </a>
            </div>
        </div>

        <!-- 4 Primary Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Total Revenue -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">payments</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600">Total</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Total Pendapatan</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>

            <!-- Total Sales Orders -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">receipt_long</span>
                    </div>
                    <span class="text-xs font-bold text-sky-600">Sukses</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Transaksi Berhasil</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ number_format($totalOrders) }}</h3>
            </div>
            
            <!-- Total Stores -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/10 text-purple-600 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">storefront</span>
                    </div>
                    <span class="text-xs font-bold text-purple-600">{{ $totalProducts }} Produk</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Total Toko</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ number_format($totalStores) }}</h3>
            </div>

            <!-- New Messages -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#E0F2FE] rounded-bl-full opacity-30"></div>
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-surface-container-high text-on-surface-variant flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                    </div>
                    @if($newMessages > 0)
                        <span class="px-2 py-0.5 bg-[#CCFBF1] text-[#0F766E] rounded-full text-xs font-bold">+{{ $newMessages }} baru</span>
                    @endif
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1 relative z-10">Pesan Masuk</p>
                <div class="flex items-baseline gap-2 relative z-10">
                    <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ $newMessages }}</h3>
                    <span class="text-xs text-on-surface-variant">belum dibaca</span>
                </div>
            </div>
        </div>

        <!-- Mini Strip: Multi-Tenant Ad Revenue & Payout Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-md">
            <!-- Pemasukan Iklan Tenant -->
            <a href="{{ route('admin.ads.index') }}" class="bg-surface rounded-md border border-outline-variant p-4 flex items-center justify-between hover:border-primary transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">campaign</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pemasukan Iklan (Top-Up)</p>
                        <h4 class="text-lg font-black text-emerald-600">Rp {{ number_format($totalAdRevenue, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-primary group-hover:underline flex items-center gap-0.5">
                        <span>Kelola Iklan</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </span>
                    <span class="text-[11px] text-on-surface-variant block mt-0.5">{{ $activeAdsCount }} Iklan Aktif</span>
                </div>
            </a>

            <!-- Saldo Iklan Beredar di Tenant -->
            <div class="bg-surface rounded-md border border-outline-variant p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-sky-500/10 text-[#0284c7] flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Saldo Iklan di Toko</p>
                        <h4 class="text-lg font-black text-[#0284c7]">Rp {{ number_format($totalAdBalance, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="text-right text-[11px] text-on-surface-variant">
                    Siap belanja iklan
                </div>
            </div>

            <!-- Manajemen Payout Tenant -->
            <a href="{{ route('admin.payouts.index') }}" class="bg-surface rounded-md border border-outline-variant p-4 flex items-center justify-between hover:border-primary transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">payments</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Permintaan Payout</p>
                        <h4 class="text-lg font-black text-on-surface">Pencairan Dana</h4>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-amber-600 group-hover:underline flex items-center gap-0.5">
                        <span>Lihat Payout</span>
                        <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                    </span>
                </div>
            </a>
        </div>

        <!-- Traffic & Visitor Highlights Strip -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Total Platform Traffic -->
            <div class="bg-surface rounded-md border border-outline-variant p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-sky-500/10 text-sky-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Kunjungan Website</p>
                        <h4 class="text-lg font-black text-on-surface">{{ number_format($totalPlatformViews) }}</h4>
                    </div>
                </div>
                <div class="text-right">
                    <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-sky-100 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">
                        +{{ number_format($todayVisitsCount) }} hari ini
                    </span>
                </div>
            </div>

            <!-- Unique Visitors Today -->
            <div class="bg-surface rounded-md border border-outline-variant p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">person_check</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pengunjung Unik</p>
                        <h4 class="text-lg font-black text-emerald-600">{{ number_format($todayUniqueVisitors) }}</h4>
                    </div>
                </div>
                <div class="text-right text-[11px] text-on-surface-variant">
                    Sesi unik hari ini
                </div>
            </div>

            <!-- Total Product Clicks / Views -->
            <div class="bg-surface rounded-md border border-outline-variant p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-indigo-500/10 text-indigo-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">ads_click</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Total Klik Produk</p>
                        <h4 class="text-lg font-black text-indigo-600">{{ number_format($totalProductViews) }}</h4>
                    </div>
                </div>
                <div class="text-right text-[11px] text-on-surface-variant">
                    Detail dilihat
                </div>
            </div>

            <!-- Total Search Queries & Unmet Demands -->
            <a href="{{ route('admin.searches.index') }}" class="bg-surface rounded-md border border-outline-variant p-4 flex items-center justify-between hover:border-primary transition group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">saved_search</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Pencarian Pembeli</p>
                        <h4 class="text-lg font-black text-amber-600">{{ number_format($totalSearchHits) }}</h4>
                    </div>
                </div>
                <div class="text-right">
                    @if($unmetDemandsCount > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 block">
                            {{ $unmetDemandsCount }} butuh produk
                        </span>
                    @endif
                    <span class="text-[11px] text-primary group-hover:underline flex items-center justify-end gap-0.5 mt-0.5">
                        <span>Lihat Analisis</span>
                        <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                    </span>
                </div>
            </a>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Revenue Growth -->
            <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-lg">
                    <div>
                        <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Tren Penjualan Produk</h3>
                        <p id="trendSubtitle" class="text-xs text-on-surface-variant">Grafik pendapatan riil 6 bulan terakhir</p>
                    </div>
                    <!-- Period Toggle (Bulanan vs Harian) -->
                    <div class="flex items-center gap-1 bg-surface-container p-1 rounded-lg border border-outline-variant shrink-0">
                        <button type="button" 
                                id="btnPeriodMonthly"
                                onclick="switchTrendPeriod('monthly')"
                                class="px-3 py-1 text-xs font-bold rounded-md transition-all bg-primary text-on-primary shadow-xs cursor-pointer">
                            Bulanan (6 Bln)
                        </button>
                        <button type="button" 
                                id="btnPeriodDaily"
                                onclick="switchTrendPeriod('daily')"
                                class="px-3 py-1 text-xs font-bold rounded-md transition-all text-on-surface hover:bg-surface-variant cursor-pointer">
                            Harian ({{ $currentMonthName ?? 'Bulan Ini' }})
                        </button>
                    </div>
                </div>
                <!-- Box border for chart area -->
                <div class="border border-outline-variant rounded-lg p-4 relative h-72 w-full flex items-end gap-2 bg-surface">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg">
                <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-1">Status Transaksi</h3>
                <p class="text-xs text-on-surface-variant mb-6">Distribusi status pesanan di sistem</p>
                <div class="relative h-44 w-full flex justify-center items-center">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col justify-center items-center pointer-events-none mt-2">
                        <span class="font-display-md text-display-md font-bold text-on-surface leading-none">{{ $allOrdersCount }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mt-1">Pesanan</span>
                    </div>
                </div>
                <div class="mt-6 space-y-2.5 px-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#10B981]"></span>
                            <span class="font-body-md text-on-surface">Sukses / Dibayar</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentSuccess }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#F59E0B]"></span>
                            <span class="font-body-md text-on-surface">Menunggu / Pending</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentPending }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#EF4444]"></span>
                            <span class="font-body-md text-on-surface">Gagal / Dibatalkan</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentFailed }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Analitik Pengunjung & Perilaku Pembeli (Traffic, Top Clicked Products, Top Searches) -->
        <div class="space-y-lg">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-outline-variant/60 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">monitoring</span>
                    </div>
                    <div>
                        <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Statistik Pengunjung & Minat Pembeli</h3>
                        <p class="text-xs text-on-surface-variant">Analisis real-time traffic website, produk paling banyak dilihat, dan kata kunci pencarian calon pembeli.</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.searches.index') }}" class="px-3 py-1.5 rounded-lg border border-outline-variant text-xs font-bold text-on-surface hover:bg-surface-container-high transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">insights</span>
                        <span>Analisis Pencarian</span>
                    </a>
                </div>
            </div>

            <!-- Row 1: 7-Day Visitor Trend Chart + Device Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
                <!-- 7-Day Traffic Chart -->
                <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                        <div>
                            <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface">Tren Kunjungan Website (7 Hari Terakhir)</h4>
                            <p class="text-xs text-on-surface-variant">Volume pageviews & pengunjung unik per hari</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs font-semibold">
                            <span class="flex items-center gap-1.5 text-sky-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Total Kunjungan
                            </span>
                            <span class="flex items-center gap-1.5 text-emerald-600">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Pengunjung Unik
                            </span>
                        </div>
                    </div>
                    <div class="border border-outline-variant rounded-lg p-4 relative h-64 w-full bg-surface">
                        <canvas id="visitorChart"></canvas>
                    </div>
                </div>

                <!-- Device Distribution & Platform Stats -->
                <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                    <div>
                        <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-1">Perangkat Pengunjung</h4>
                        <p class="text-xs text-on-surface-variant mb-5">Distribusi akses perangkat calon pembeli</p>

                        <div class="space-y-4">
                            <!-- Desktop -->
                            <div>
                                <div class="flex justify-between items-center text-xs mb-1.5">
                                    <span class="flex items-center gap-2 font-bold text-on-surface">
                                        <span class="material-symbols-outlined text-[18px] text-sky-600">computer</span>
                                        Komputer / Laptop (Desktop)
                                    </span>
                                    <span class="font-bold text-on-surface">{{ $deviceStats['desktop'] }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                                    <div class="h-full rounded-full bg-sky-500 transition-all duration-500" style="width: {{ $deviceStats['desktop'] }}%"></div>
                                </div>
                            </div>

                            <!-- Mobile -->
                            <div>
                                <div class="flex justify-between items-center text-xs mb-1.5">
                                    <span class="flex items-center gap-2 font-bold text-on-surface">
                                        <span class="material-symbols-outlined text-[18px] text-emerald-600">smartphone</span>
                                        Ponsel (Smartphone)
                                    </span>
                                    <span class="font-bold text-on-surface">{{ $deviceStats['mobile'] }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                                    <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ $deviceStats['mobile'] }}%"></div>
                                </div>
                            </div>

                            <!-- Tablet -->
                            <div>
                                <div class="flex justify-between items-center text-xs mb-1.5">
                                    <span class="flex items-center gap-2 font-bold text-on-surface">
                                        <span class="material-symbols-outlined text-[18px] text-purple-600">tablet_mac</span>
                                        Tablet & iPad
                                    </span>
                                    <span class="font-bold text-on-surface">{{ $deviceStats['tablet'] }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-surface-container-high overflow-hidden">
                                    <div class="h-full rounded-full bg-purple-500 transition-all duration-500" style="width: {{ $deviceStats['tablet'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-outline-variant/50 grid grid-cols-2 gap-2 text-center text-xs">
                        <div class="p-2.5 rounded-lg bg-surface-container-lowest border border-outline-variant/40">
                            <span class="text-on-surface-variant block text-[10px]">Toko Dilihat</span>
                            <span class="font-bold text-sm text-on-surface mt-0.5 block">{{ number_format($totalStoreViews) }}x</span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-surface-container-lowest border border-outline-variant/40">
                            <span class="text-on-surface-variant block text-[10px]">Produk Dilihat</span>
                            <span class="font-bold text-sm text-on-surface mt-0.5 block">{{ number_format($totalProductViews) }}x</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row 2: Produk Terbanyak Diklik & Kata Kunci Terbanyak Dicari -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
                <!-- Produk Terbanyak Diklik / Dilihat (Top Clicked Products) -->
                <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-amber-500">local_fire_department</span>
                                Produk Terbanyak Diklik / Dilihat
                            </h4>
                            <p class="text-xs text-on-surface-variant">Produk digital dengan daya tarik dan view terbanyak dari pembeli</p>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="font-label-sm text-primary hover:underline text-xs">
                            Semua Produk
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant text-[11px] uppercase tracking-wider text-on-surface-variant">
                                    <th class="pb-3 text-center w-10">Rank</th>
                                    <th class="pb-3">Produk</th>
                                    <th class="pb-3">Toko / Kategori</th>
                                    <th class="pb-3 text-right">Klik / Dilihat</th>
                                    <th class="pb-3 text-right pr-2">Penjualan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/40">
                                @forelse($topViewedProducts as $index => $prod)
                                @php
                                    $pct = $maxProductViews > 0 ? min(100, round(($prod->views / $maxProductViews) * 100)) : 0;
                                    $imgUrl = $prod->images->where('is_main', true)->first()?->image_path 
                                        ?? $prod->images->first()?->image_path;
                                @endphp
                                <tr class="hover:bg-surface-container-lowest transition-colors group">
                                    <td class="py-3 text-center">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-500 text-white font-black text-xs shadow-xs">1</span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-400 text-white font-black text-xs shadow-xs">2</span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-700 text-white font-black text-xs shadow-xs">3</span>
                                        @else
                                            <span class="text-xs font-bold text-on-surface-variant">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            @if($imgUrl)
                                                <img src="{{ asset('storage/' . $imgUrl) }}" alt="{{ $prod->name }}" class="w-10 h-10 rounded-lg object-cover border border-outline-variant shrink-0 bg-surface-variant">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface-variant shrink-0">
                                                    <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                                                </div>
                                            @endif
                                            <div class="min-w-0 max-w-[220px] sm:max-w-xs">
                                                <a href="{{ route('products.show', $prod->slug) }}" target="_blank" class="font-body-md font-bold text-on-surface hover:text-primary transition line-clamp-1 block" title="{{ $prod->name }}">
                                                    {{ $prod->name }}
                                                </a>
                                                <div class="text-xs text-emerald-600 dark:text-emerald-400 font-bold mt-0.5">
                                                    Rp {{ number_format($prod->price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-xs">
                                        <div class="flex items-center gap-1 text-on-surface font-semibold truncate max-w-[140px]">
                                            <span class="material-symbols-outlined text-[13px] text-teal-600">storefront</span>
                                            {{ $prod->store->name ?? 'Marketplace' }}
                                        </div>
                                        <div class="text-[11px] text-on-surface-variant mt-0.5 truncate max-w-[140px]">
                                            {{ $prod->category->name ?? 'Digital' }}
                                        </div>
                                    </td>
                                    <td class="py-3 text-right">
                                        <div class="font-bold text-on-surface text-sm">{{ number_format($prod->views) }} <span class="text-[11px] font-normal text-on-surface-variant">views</span></div>
                                        <div class="w-24 h-1.5 rounded-full bg-surface-container-high ml-auto mt-1 overflow-hidden">
                                            <div class="h-full rounded-full bg-amber-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-right pr-2">
                                        <span class="px-2 py-0.5 rounded-md text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                            {{ number_format($prod->sales_count ?? 0) }} Terjual
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-xs text-on-surface-variant">Belum ada data interaksi klik produk.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kata Kunci Terbanyak Dicari & Toko Terpopuler -->
                <div class="space-y-md">
                    <!-- Kata Kunci Terbanyak Dicari -->
                    <div class="bg-surface rounded-md border border-outline-variant p-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-primary">search</span>
                                Terbanyak Dicari
                            </h4>
                            <a href="{{ route('admin.searches.index') }}" class="font-label-sm text-primary hover:underline text-xs">
                                Analisis Lengkap
                            </a>
                        </div>
                        <p class="text-xs text-on-surface-variant mb-4">Kata kunci yang paling sering dicari oleh calon pembeli di marketplace</p>

                        <div class="space-y-2.5">
                            @forelse($topSearches as $search)
                            <div class="p-2.5 rounded-lg border border-outline-variant/60 hover:border-primary/50 transition-colors bg-surface-container-lowest flex items-center justify-between">
                                <div class="min-w-0 flex-1 pr-2">
                                    <div class="flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px] text-on-surface-variant">manage_search</span>
                                        <span class="font-bold text-xs text-on-surface truncate capitalize">{{ $search->keyword }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-[10px]">
                                        @if($search->results_count > 0)
                                            <span class="text-emerald-600 font-semibold">{{ $search->results_count }} Produk Tersedia</span>
                                        @else
                                            <span class="text-amber-600 font-bold flex items-center gap-0.5">
                                                <span>Belum Ada Produk</span>
                                                <span class="material-symbols-outlined text-[10px]">priority_high</span>
                                            </span>
                                        @endif
                                        @if($search->last_searched_at)
                                            <span class="text-on-surface-variant">• {{ \Carbon\Carbon::parse($search->last_searched_at)->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <span class="px-2 py-1 rounded-md bg-surface-container font-black text-xs text-on-surface border border-outline-variant/40">
                                        {{ number_format($search->hits) }}x
                                    </span>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-on-surface-variant py-4 text-center">Belum ada riwayat pencarian produk.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Toko Terpopuler (Top Visited Stores) -->
                    <div class="bg-surface rounded-md border border-outline-variant p-lg">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-headline-sm text-headline-sm font-bold text-on-surface flex items-center gap-2">
                                <span class="material-symbols-outlined text-[20px] text-teal-600">storefront</span>
                                Toko Terpopuler
                            </h4>
                            <a href="{{ route('admin.stores.index') }}" class="font-label-sm text-primary hover:underline text-xs">
                                Semua Toko
                            </a>
                        </div>
                        <div class="divide-y divide-outline-variant/40">
                            @forelse($topVisitedStores as $st)
                            <div class="py-2.5 first:pt-0 last:pb-0 flex items-center justify-between">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    @if($st->logo)
                                        <img src="{{ asset('storage/' . $st->logo) }}" alt="{{ $st->name }}" class="w-8 h-8 rounded-lg object-cover border border-outline-variant shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ substr($st->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('store.show', $st->slug) }}" target="_blank" class="text-xs font-bold text-on-surface hover:text-primary block truncate">
                                            {{ $st->name }}
                                        </a>
                                        <span class="text-[11px] text-on-surface-variant block">{{ $st->products_count }} Produk Aktif</span>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="text-xs font-bold text-teal-600">{{ number_format($st->views) }}</span>
                                    <span class="text-[10px] text-on-surface-variant block">kunjungan</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-on-surface-variant py-2 text-center">Belum ada data toko.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Geographic Distribution Map Section -->
        <div class="bg-surface rounded-md border border-outline-variant p-lg space-y-md">
            <!-- Header with Title & Filter Controls -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                        </div>
                        <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Peta Persebaran Mitra Toko & Pelanggan</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Visualisasi titik lokasi toko seller digital dan pelanggan/customer berdasarkan koordinat geografis di seluruh Indonesia.
                    </p>
                </div>

                <!-- Filter Controls & Legend -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="btnFilterAll" onclick="filterMapMarkers('all')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-primary text-on-primary border-primary">
                        Semua Titik ({{ $mapData['total_points'] }})
                    </button>
                    <button type="button" id="btnFilterStore" onclick="filterMapMarkers('store')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-surface border-outline-variant text-on-surface hover:border-teal-500 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#00838f]"></span>
                        Mitra Toko ({{ $mapData['total_stores'] }})
                    </button>
                    <button type="button" id="btnFilterCustomer" onclick="filterMapMarkers('customer')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-surface border-outline-variant text-on-surface hover:border-blue-500 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2563eb]"></span>
                        Pelanggan ({{ $mapData['total_customers'] }})
                    </button>
                    <button type="button" onclick="resetMapView()" title="Fokuskan Ulang Peta" 
                        class="p-1.5 rounded-lg border border-outline-variant text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">center_focus_strong</span>
                    </button>
                </div>
            </div>

            <!-- Leaflet Map Container -->
            <div class="relative w-full rounded-xl overflow-hidden border border-outline-variant bg-surface-container-low" style="height: 480px; z-index: 1;">
                <div id="adminGeoMap" class="w-full h-full"></div>
                <!-- Controls overlay hint -->
                <div class="absolute bottom-3 left-3 z-[1000] pointer-events-none bg-slate-950/80 backdrop-blur-xs text-white px-3 py-1.5 rounded-lg text-[11px] font-medium flex items-center gap-1.5 border border-white/15 shadow-md">
                    <span class="material-symbols-outlined text-[15px] text-sky-400 leading-none">pan_tool</span>
                    <span>Klik &amp; Geser (Drag) • Scroll Mouse untuk Zoom In/Out</span>
                </div>
            </div>

            <!-- Summary Footnotes -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 border-t border-outline-variant/60 text-xs">
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Total Titik Terdata</span>
                    <span class="font-bold text-sm text-on-surface mt-0.5 block">{{ $mapData['total_points'] }} Lokasi</span>
                </div>
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Mitra Toko (Sellers)</span>
                    <span class="font-bold text-sm text-teal-600 dark:text-teal-400 mt-0.5 block">{{ $mapData['total_stores'] }} Toko Tersebar</span>
                </div>
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Pelanggan Aktif</span>
                    <span class="font-bold text-sm text-blue-600 dark:text-blue-400 mt-0.5 block">{{ $mapData['total_customers'] }} Pembeli Unik</span>
                </div>
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Cakupan Wilayah</span>
                    <span class="font-bold text-sm text-on-surface mt-0.5 block truncate" title="Jawa, Sumatera, Riau, dll">Jawa, Sumatera & Nasional</span>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Recent Transactions List -->
            <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Transaksi Terbaru</h3>
                    <a href="{{ route('admin.orders.index') }}" class="font-label-sm text-primary hover:underline">Semua Transaksi</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Invoice / Pembeli</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Produk</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right pr-4">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50">
                            @forelse($recentTransactions as $order)
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="py-4">
                                    <div class="font-bold text-on-surface font-mono text-sm">{{ $order->invoice_number }}</div>
                                    <div class="text-xs text-on-surface-variant mt-0.5">{{ $order->customer_email }}</div>
                                </td>
                                <td class="py-4">
                                    @if($order->orderItems && $order->orderItems->count() > 0 && $order->orderItems->first()->product)
                                        <div class="font-body-md font-semibold text-on-surface max-w-[200px] truncate" title="{{ $order->orderItems->first()->product->name }}">
                                            {{ $order->orderItems->first()->product->name }}
                                        </div>
                                        <div class="text-xs text-on-surface-variant truncate max-w-[200px] flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">storefront</span> 
                                            {{ $order->orderItems->first()->product->store->store_name ?? 'Toko' }}
                                        </div>
                                    @else
                                        <div class="text-xs text-slate-400 italic">Produk dihapus/tidak diketahui</div>
                                    @endif
                                </td>
                                <td class="py-4 text-center font-body-md">
                                    @if($order->status === 'paid' || $order->status === 'downloaded')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Sukses</span>
                                    @elseif($order->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Batal</span>
                                    @endif
                                </td>
                                <td class="py-4 text-right pr-4 font-bold text-on-surface">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-on-surface-variant">
                                    Belum ada transaksi di platform ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Feed (Messages) -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Pesan Terbaru</h3>
                    <a href="{{ route('admin.messages.index') }}" class="font-label-sm text-primary hover:underline">Semua Pesan</a>
                </div>
                <div class="space-y-4 mt-4">
                    @forelse($recentMessages as $msg)
                    <div class="flex gap-3 items-start relative pb-3 border-b border-outline-variant/30 last:border-0 last:pb-0">
                        <div class="w-8 h-8 rounded-full border border-sky-500 flex items-center justify-center text-sky-500 flex-shrink-0 mt-0.5 bg-surface">
                            <span class="material-symbols-outlined" style="font-size: 16px;">mail</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-body-sm text-on-surface truncate">Dari: <span class="font-bold">{{ $msg->name }}</span></p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ Str::limit($msg->message, 45) }}</p>
                        </div>
                    </div>
                    @empty
                        <p class="text-xs text-on-surface-variant py-4 text-center">Belum ada pesan masuk.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        let revenueChartInstance = null;
        let statusChartInstance = null;
        let visitorChartInstance = null;
        let geoMap = null;
        let mapMarkersLayer = null;
        const mapRawData = @json($mapData);

        let revenuePeriod = 'monthly';
        const revMonthlyLabels = @json($monthLabels);
        const revMonthlyData = @json($monthlyRevenue);
        const revDailyLabels = @json($dailyLabels);
        const revDailyData = @json($dailyRevenue);
        const currentMonthName = @json($currentMonthName);

        const visitorLabels = @json($visitorChartLabels);
        const visitorTotalData = @json($visitorChartData);
        const visitorUniqueData = @json($visitorUniqueData);

        function initRevenueChart() {
            const canvas = document.getElementById('revenueChart');
            if (!canvas || typeof Chart === 'undefined') return;

            const existing = Chart.getChart(canvas);
            if (existing) existing.destroy();
            if (revenueChartInstance) {
                try { revenueChartInstance.destroy(); } catch(e) {}
                revenueChartInstance = null;
            }

            const ctxRev = canvas.getContext('2d');
            const isDaily = revenuePeriod === 'daily';
            const labels = isDaily ? revDailyLabels : revMonthlyLabels;
            const data = isDaily ? revDailyData : revMonthlyData;
            const barPct = isDaily ? 0.75 : 0.6;

            revenueChartInstance = new Chart(ctxRev, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        backgroundColor: isDaily ? '#0ea5e9' : '#0284c7',
                        borderRadius: isDaily ? 2 : 4,
                        borderSkipped: false,
                        barPercentage: barPct,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            titleFont: { family: 'Geist', size: 13 },
                            bodyFont: { family: 'Geist', size: 14, weight: 'bold' },
                            callbacks: {
                                title: function(items) {
                                    if (!items.length) return '';
                                    if (revenuePeriod === 'daily') {
                                        return 'Tgl ' + items[0].label + ' ' + currentMonthName;
                                    }
                                    return 'Bulan ' + items[0].label;
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
                                maxTicksLimit: isDaily ? 16 : 12,
                                font: { family: 'Geist', size: 11 },
                                color: '#64748B'
                            }
                        },
                        y: {
                            grid: {
                                color: function(ctx) {
                                    return document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                                },
                                drawBorder: false
                            },
                            ticks: {
                                font: { family: 'Geist', size: 11 },
                                color: '#64748B',
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

        window.switchTrendPeriod = function(period) {
            revenuePeriod = period;
            const btnMonthly = document.getElementById('btnPeriodMonthly');
            const btnDaily = document.getElementById('btnPeriodDaily');
            const subtitle = document.getElementById('trendSubtitle');

            const activeClass = "px-3 py-1 text-xs font-bold rounded-md transition-all bg-primary text-on-primary shadow-xs cursor-pointer";
            const inactiveClass = "px-3 py-1 text-xs font-bold rounded-md transition-all text-on-surface hover:bg-surface-variant cursor-pointer";

            if (period === 'daily') {
                if (btnDaily) btnDaily.className = activeClass;
                if (btnMonthly) btnMonthly.className = inactiveClass;
                if (subtitle) subtitle.textContent = "Grafik pendapatan harian per tanggal di bulan " + currentMonthName;
            } else {
                if (btnMonthly) btnMonthly.className = activeClass;
                if (btnDaily) btnDaily.className = inactiveClass;
                if (subtitle) subtitle.textContent = "Grafik pendapatan riil 6 bulan terakhir";
            }

            initRevenueChart();
        };

        function initStatusChart() {
            const canvas = document.getElementById('statusChart');
            if (!canvas || typeof Chart === 'undefined') return;

            const existing = Chart.getChart(canvas);
            if (existing) existing.destroy();
            if (statusChartInstance) {
                try { statusChartInstance.destroy(); } catch(e) {}
                statusChartInstance = null;
            }

            statusChartInstance = new Chart(canvas.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Sukses', 'Pending', 'Gagal/Batal'],
                    datasets: [{
                        data: [{{ $orderSuccess }}, {{ $orderPending }}, {{ $orderFailed }}],
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            padding: 12,
                            titleFont: { family: 'Geist', size: 13 },
                            bodyFont: { family: 'Geist', size: 14, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed !== null) label += context.parsed + ' Pesanan';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        function initVisitorChart() {
            const canvas = document.getElementById('visitorChart');
            if (!canvas || typeof Chart === 'undefined') return;

            const existing = Chart.getChart(canvas);
            if (existing) existing.destroy();
            if (visitorChartInstance) {
                try { visitorChartInstance.destroy(); } catch(e) {}
                visitorChartInstance = null;
            }

            const ctx = canvas.getContext('2d');

            // Gradient for total visits
            const gradTotal = ctx.createLinearGradient(0, 0, 0, 220);
            gradTotal.addColorStop(0, 'rgba(14, 165, 233, 0.35)');
            gradTotal.addColorStop(1, 'rgba(14, 165, 233, 0.0)');

            // Gradient for unique visitors
            const gradUnique = ctx.createLinearGradient(0, 0, 0, 220);
            gradUnique.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
            gradUnique.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            visitorChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: visitorLabels,
                    datasets: [
                        {
                            label: 'Total Kunjungan',
                            data: visitorTotalData,
                            borderColor: '#0ea5e9',
                            backgroundColor: gradTotal,
                            borderWidth: 2.5,
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointBackgroundColor: '#0ea5e9',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointHoverRadius: 6,
                        },
                        {
                            label: 'Pengunjung Unik',
                            data: visitorUniqueData,
                            borderColor: '#10b981',
                            backgroundColor: gradUnique,
                            borderWidth: 2,
                            borderDash: [4, 4],
                            fill: true,
                            tension: 0.35,
                            pointRadius: 3.5,
                            pointBackgroundColor: '#10b981',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 1.5,
                            pointHoverRadius: 6,
                        }
                    ]
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
                            backgroundColor: '#0F172A',
                            padding: 12,
                            titleFont: { family: 'Geist', size: 13 },
                            bodyFont: { family: 'Geist', size: 13 },
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + Number(context.parsed.y).toLocaleString('id-ID') + ' Pengunjung';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: {
                                font: { family: 'Geist', size: 11 },
                                color: '#64748B'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: function() {
                                    return document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                                },
                                drawBorder: false
                            },
                            ticks: {
                                font: { family: 'Geist', size: 11 },
                                color: '#64748B',
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        function createPinIcon(type) {
            const color = type === 'store' ? '#00838f' : '#2563eb';
            const icon = type === 'store' ? 'storefront' : 'person';
            return L.divIcon({
                className: 'custom-map-pin',
                html: `<div style="position: relative; display: flex; flex-direction: column; align-items: center; cursor: pointer;">
                        <div style="width: 32px; height: 32px; border-radius: 50%; background: ${color}; color: white; display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3);">
                            <span class="material-symbols-outlined" style="font-size: 16px;">${icon}</span>
                        </div>
                        <div style="width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 7px solid ${color}; margin-top: -2px;"></div>
                       </div>`,
                iconSize: [32, 37],
                iconAnchor: [16, 37],
                popupAnchor: [0, -35]
            });
        }

        function renderMarkers(filterType) {
            if (!geoMap || !mapMarkersLayer) return;
            mapMarkersLayer.clearLayers();
            let list = (filterType === 'store' ? mapRawData.stores : (filterType === 'customer' ? mapRawData.customers : mapRawData.all)) || [];
            let bounds = [];
            list.forEach(item => {
                if (!item.lat || !item.lng) return;
                const marker = L.marker([item.lat, item.lng], { icon: createPinIcon(item.type) });
                let popupContent = '';
                if (item.type === 'store') {
                    popupContent = `
                        <div style="padding: 12px; min-width: 220px; max-width: 260px; font-family: inherit;">
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                <span style="background: #ccfbf1; color: #0f766e; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">🏪 MITRA TOKO</span>
                                <span style="font-size: 11px; color: #64748b;">• ${item.products_count} Produk</span>
                            </div>
                            <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #0f172a;">${item.name}</h4>
                            <p style="margin: 4px 0 0; font-size: 11px; color: #64748b; line-height: 1.3;">📍 ${item.address}</p>
                            <div style="margin-top: 6px; font-size: 11px; color: #475569;">
                                Pemilik: <b>${item.owner}</b>
                            </div>
                            <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e2e8f0; display: flex; gap: 6px;">
                                ${item.store_url ? `<a href="${item.store_url}" target="_blank" style="padding: 4px 8px; border-radius: 6px; background: #00838f; color: white; font-size: 11px; font-weight: 700; text-decoration: none;">Buka Toko</a>` : ''}
                                <a href="${item.maps_url}" target="_blank" style="padding: 4px 8px; border-radius: 6px; background: #f1f5f9; color: #334155; font-size: 11px; font-weight: 600; text-decoration: none;">Google Maps</a>
                            </div>
                        </div>
                    `;
                } else {
                    popupContent = `
                        <div style="padding: 12px; min-width: 220px; max-width: 260px; font-family: inherit;">
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                <span style="background: #dbeafe; color: #1d4ed8; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">👤 PELANGGAN</span>
                                <span style="font-size: 11px; color: #64748b;">• ${item.orders_count} Order</span>
                            </div>
                            <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #0f172a;">${item.name}</h4>
                            <p style="margin: 4px 0 0; font-size: 11px; color: #64748b; line-height: 1.3;">📍 ${item.address}</p>
                            <div style="margin-top: 6px; font-size: 11px; color: #166534;">
                                Total Belanja: <b>Rp ${Number(item.total_spent).toLocaleString('id-ID')}</b>
                            </div>
                            <div style="margin-top: 2px; font-size: 10px; color: #94a3b8;">
                                Terakhir order: ${item.last_order}
                            </div>
                            <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 10px; color: #64748b; font-family: monospace;">${item.phone || item.email}</span>
                                <a href="${item.maps_url}" target="_blank" style="padding: 4px 8px; border-radius: 6px; background: #f1f5f9; color: #334155; font-size: 11px; font-weight: 600; text-decoration: none;">Maps</a>
                            </div>
                        </div>
                    `;
                }
                marker.bindPopup(popupContent);
                mapMarkersLayer.addLayer(marker);
                bounds.push([item.lat, item.lng]);
            });
            if (bounds.length > 0) geoMap.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
        }

        function initAdminGeoMap() {
            const mapContainer = document.getElementById('adminGeoMap');
            if (!mapContainer || typeof L === 'undefined') return;
            if (geoMap) { try { geoMap.remove(); } catch(e) {} geoMap = null; }
            if (mapContainer._leaflet_id) delete mapContainer._leaflet_id;
            geoMap = L.map('adminGeoMap', {
                scrollWheelZoom: true,
                dragging: true,
                touchZoom: true,
                doubleClickZoom: true,
                boxZoom: true,
                keyboard: true,
                zoomControl: true
            }).setView([-6.92, 107.65], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(geoMap);
            mapMarkersLayer = L.layerGroup().addTo(geoMap);
            renderMarkers('all');
            setTimeout(() => { if (geoMap) geoMap.invalidateSize(); }, 250);
        }

        window.filterMapMarkers = function(type) {
            ['btnFilterAll', 'btnFilterStore', 'btnFilterCustomer'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.className = "px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-surface border-outline-variant text-on-surface";
            });
            const activeId = type === 'store' ? 'btnFilterStore' : (type === 'customer' ? 'btnFilterCustomer' : 'btnFilterAll');
            const activeEl = document.getElementById(activeId);
            if (activeEl) activeEl.className = "px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-primary text-on-primary border-primary";
            renderMarkers(type);
        };

        window.resetMapView = function() { window.filterMapMarkers('all'); };

        function initAllDashboard() {
            setTimeout(() => {
                initRevenueChart();
                initStatusChart();
                initVisitorChart();
                initAdminGeoMap();
            }, 50);
        }

        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initAllDashboard);
        else initAllDashboard();
        document.addEventListener('livewire:navigated', initAllDashboard);
    })();
</script>
@endsection
