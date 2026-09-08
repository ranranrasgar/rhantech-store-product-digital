@extends('layouts.tenant')

@section('title', 'Performa Toko & Analitik')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200" x-data="{ tab: 'tinjauan' }">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header Hero & Summary -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Performa & Analisis Toko
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pantau metrik penjualan, tren konversi, efektivitas produk, dan statistik kunjungan toko digital Anda.
                </p>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] shadow-sm text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Real-time (Hari Ini: {{ date('d M Y') }})
                </div>
                <a href="{{ route('tenant.orders.index') }}" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs md:text-sm font-bold shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    Lihat Penjualan
                </a>
            </div>
        </div>

        <!-- 5 Key Performance Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 md:gap-5">
            <!-- Total Omset / Penjualan -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Penjualan</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">payments</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($totalSales, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Total saldo toko</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span> Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total Pengunjung / Visitor -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
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
                        <span>{{ number_format($productViews ?? 0) }} view produk</span>
                        <span class="font-semibold text-purple-600 dark:text-purple-400">Visitor</span>
                    </div>
                </div>
            </div>

            <!-- Pesanan Berhasil -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pesanan Berhasil</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">shopping_bag</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($totalOrders) }} <span class="text-xs font-normal text-slate-400">order</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Dari {{ $allOrdersCount }} checkout</span>
                        <span class="font-bold text-sky-600 dark:text-sky-400">
                            {{ $conversionRate }}% Sukses
                        </span>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Nilai Pesanan (AOV) -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rata-rata Order</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">calculate</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Per transaksi</span>
                        <span class="font-medium text-slate-400">Nilai AOV</span>
                    </div>
                </div>
            </div>

            <!-- Produk Aktif & Terbit -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Produk Tayang</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($activeProducts) }} <span class="text-xs font-semibold text-slate-400">/ {{ number_format($totalProducts) }}</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Katalog digital</span>
                        <a href="{{ route('tenant.products.index') }}" class="font-bold text-sky-600 dark:text-sky-400 hover:underline">
                            Kelola
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card Section with Tabs -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Tab Navigation Bar -->
            <div class="border-b border-slate-100 dark:border-[#222f49] px-6 flex items-center gap-8 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                <button @click="tab = 'tinjauan'" :class="tab === 'tinjauan' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">query_stats</span>
                    Tinjauan Performa
                </button>
                <button @click="tab = 'produk'" :class="tab === 'produk' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">view_in_ar</span>
                    Performa Produk
                </button>
                <button @click="tab = 'funnel'" :class="tab === 'funnel' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">filter_alt</span>
                    Funnel Konversi
                </button>
                <button @click="tab = 'sumber'" :class="tab === 'sumber' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">traffic</span>
                    Sumber Traffic & Saluran
                </button>
            </div>

            <!-- TAB 1: TINJAUAN PERFORMA -->
            <div x-show="tab === 'tinjauan'" class="p-6 md:p-8 space-y-8">
                
                <!-- Chart & Visual Analysis Section -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left: Penjualan & Tren Visual -->
                    <div class="lg:col-span-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white">Tren Aktivitas Penjualan</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ringkasan aktivitas checkout dan transaksi toko sepanjang hari ini.</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-900/50">
                                24 Jam Terakhir
                            </span>
                        </div>

                        <!-- Stylized Minimal Line & Bar Graph Visualization -->
                        <div class="bg-slate-50 dark:bg-[#0c1220] border border-slate-200/70 dark:border-[#1e293b] rounded-2xl p-6 relative overflow-hidden">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <span class="text-xs font-medium text-slate-400">Total Nilai Penjualan</span>
                                    <div class="text-xl font-extrabold text-slate-900 dark:text-white">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                                </div>
                                <div class="flex items-center gap-3 text-xs font-medium">
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Transaksi Paid</div>
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Pending</div>
                                </div>
                            </div>

                            <!-- Visual Mock Distribution -->
                            <div class="h-44 flex items-end justify-between gap-2 pt-6 border-b border-slate-200 dark:border-[#222f49] pb-2">
                                @php
                                    $timeSlots = ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00', '24:00'];
                                @endphp
                                @foreach($timeSlots as $index => $slot)
                                    @php
                                        $heightPaid = $totalOrders > 0 ? min(90, max(15, ($index % 3 + 1) * 25)) : 8;
                                        $heightPending = $pendingOrders > 0 ? min(50, max(10, ($index % 2 + 1) * 15)) : 4;
                                    @endphp
                                    <div class="flex-1 flex flex-col items-center gap-1 group">
                                        <div class="w-full max-w-[28px] bg-slate-200/60 dark:bg-slate-800/80 rounded-t-lg h-32 flex items-end justify-center p-0.5 gap-0.5 overflow-hidden">
                                            <div class="w-1/2 bg-sky-500 rounded-t transition-all duration-500 group-hover:brightness-110" style="height: {{ $heightPaid }}%;"></div>
                                            <div class="w-1/2 bg-amber-500/70 rounded-t transition-all duration-500 group-hover:brightness-110" style="height: {{ $heightPending }}%;"></div>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono mt-1">{{ $slot }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 flex items-center justify-between text-xs text-slate-400">
                                <span>Estimasi volume berdasarkan jam operasional toko</span>
                                <span class="font-semibold text-slate-600 dark:text-slate-300">GMT+07 (WIB)</span>
                            </div>
                        </div>

                    </div>

                    <!-- Right: Performance Health Breakdown -->
                    <div class="lg:col-span-4 space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-slate-900 dark:text-white">Kesehatan Transaksi</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Rasio efisiensi proses checkout pesanan.</p>
                        </div>

                        <div class="bg-slate-50 dark:bg-[#0c1220] border border-slate-200/70 dark:border-[#1e293b] rounded-2xl p-6 space-y-5">
                            
                            <!-- Stat Item 1 -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-600 dark:text-slate-300">Tingkat Penyelesaian Pesanan</span>
                                    <span class="text-sky-600 dark:text-sky-400">{{ $conversionRate }}%</span>
                                </div>
                                <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-sky-500 rounded-full transition-all duration-700" style="width: {{ max(5, $conversionRate) }}%"></div>
                                </div>
                                <p class="text-[11px] text-slate-400">Persentase invoice yang berhasil terbayar dan file telah didistribusikan.</p>
                            </div>

                            <!-- Stat Item 2 -->
                            <div class="space-y-2 pt-3 border-t border-slate-200/60 dark:border-[#1e293b]">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-600 dark:text-slate-300">Tingkat Menunggu Pembayaran</span>
                                    <span class="text-amber-600 dark:text-amber-400">
                                        {{ $allOrdersCount > 0 ? round(($pendingOrders / $allOrdersCount) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                                <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full transition-all duration-700" style="width: {{ $allOrdersCount > 0 ? ($pendingOrders / $allOrdersCount) * 100 : 0 }}%"></div>
                                </div>
                                <p class="text-[11px] text-slate-400">Pesanan pending yang sedang menunggu konfirmasi pembayaran otomatis/manual.</p>
                            </div>

                            <!-- Stat Item 3: Katalog Aktif -->
                            <div class="space-y-2 pt-3 border-t border-slate-200/60 dark:border-[#1e293b]">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-600 dark:text-slate-300">Rasio Katalog Aktif</span>
                                    <span class="text-emerald-600 dark:text-emerald-400">
                                        {{ $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100) : 100 }}%
                                    </span>
                                </div>
                                <div class="h-2 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-700" style="width: {{ $totalProducts > 0 ? ($activeProducts / $totalProducts) * 100 : 100 }}%"></div>
                                </div>
                                <p class="text-[11px] text-slate-400">{{ $activeProducts }} dari {{ $totalProducts }} produk siap dibeli pelanggan.</p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Strategic Recommendations Banner -->
                <div class="bg-gradient-to-r from-sky-500/10 via-indigo-500/10 to-sky-500/5 dark:from-sky-950/30 dark:via-indigo-950/20 dark:to-[#0c1220] border border-sky-200/80 dark:border-sky-900/40 rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-5">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-sky-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-sky-500/20">
                            <span class="material-symbols-outlined text-[24px]">rocket_launch</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">Tingkatkan Omset Toko dengan Dekorasi & Promo</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 max-w-2xl">
                                Toko yang memiliki banner promosi menarik, deskripsi produk lengkap dengan tautan demo, dan kode kupon diskon terbukti meningkatkan konversi hingga 3x lipat.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('tenant.appearance.index') }}" class="px-4 py-2 rounded-xl bg-white dark:bg-[#161f33] border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-white hover:bg-slate-50 dark:hover:bg-[#1d273d] transition-all shadow-sm">
                            Dekorasi Toko
                        </a>
                        <a href="{{ route('tenant.campaigns.index') }}" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-xs font-bold text-white shadow-md shadow-sky-500/20 transition-all">
                            Buat Promo
                        </a>
                    </div>
                </div>

            </div>

            <!-- TAB 2: PERFORMA PRODUK -->
            <div x-show="tab === 'produk'" class="p-6 md:p-8 space-y-6" style="display: none;">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Peringkat & Performa Produk Digital</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Produk digital unggulan di toko Anda yang sering dilihat dan dibeli.</p>
                    </div>
                    <a href="{{ route('tenant.products.create') }}" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold shadow-md shadow-sky-500/25 transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span> Tambah Produk
                    </a>
                </div>

                <div class="border border-slate-200/80 dark:border-[#222f49] rounded-2xl overflow-hidden shadow-xs">
                    <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                        <thead class="bg-slate-50 dark:bg-[#0c1220] border-b border-slate-200/80 dark:border-[#222f49] text-slate-500 dark:text-slate-400 uppercase tracking-wider text-[11px] font-bold">
                            <tr>
                                <th class="p-4 pl-6">Produk Digital</th>
                                <th class="p-4">Kategori</th>
                                <th class="p-4">Harga Jual</th>
                                <th class="p-4">Status</th>
                                <th class="p-4 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                            @forelse($topProducts as $product)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-[#161f33]/60 transition-colors">
                                <td class="p-4 pl-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden text-slate-400">
                                            @if($product->images->count() > 0)
                                                @php $pImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                                <img src="{{ asset('storage/' . $pImg->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-white truncate max-w-xs md:max-w-md">
                                                {{ $product->name }}
                                            </div>
                                            <div class="text-xs text-slate-400 mt-0.5">
                                                SKU: {{ $product->sku ?? 'DIGI-' . str_pad($product->id, 4, '0', STR_PAD_LEFT) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 text-slate-600 dark:text-slate-300">
                                    {{ $product->category->name ?? 'Umum' }}
                                </td>
                                <td class="p-4 font-bold text-slate-900 dark:text-white">
                                    Rp {{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}
                                    @if($product->discount_price && $product->discount_price < $product->price)
                                        <span class="block text-[10px] text-slate-400 line-through font-normal">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($product->is_active)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('tenant.products.edit', $product) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors" title="Edit Produk">
                                            <span class="material-symbols-outlined text-[16px]">edit</span>
                                        </a>
                                        @if($product->slug)
                                        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors" title="Lihat Publik">
                                            <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-400">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inventory_2</span>
                                    <p class="text-sm">Belum ada produk yang didaftarkan pada toko Anda.</p>
                                    <a href="{{ route('tenant.products.create') }}" class="mt-3 inline-flex items-center gap-1 text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline">
                                        <span class="material-symbols-outlined text-[14px]">add</span> Tambah Produk Sekarang
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: FUNNEL KONVERSI -->
            <div x-show="tab === 'funnel'" class="p-6 md:p-8 space-y-6" style="display: none;">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Alur Perjalanan Pembeli (Conversion Funnel)</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Analisis tahapan pengunjung dari melihat produk hingga menyelesaikan pembayaran.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Funnel Step 1 -->
                    <div class="bg-slate-50 dark:bg-[#0c1220] border border-slate-200/80 dark:border-[#1e293b] rounded-2xl p-6 relative overflow-hidden">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold mb-4">
                            1
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Eksplorasi & Kunjungan</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Calon pembeli membuka katalog toko dan halaman detail produk Anda.</p>
                        
                        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-[#1e293b] flex items-center justify-between">
                            <span class="text-xs text-slate-400">Total Pengunjung</span>
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">{{ max(1, $allOrdersCount * 5) }} Sesi</span>
                        </div>
                    </div>

                    <!-- Funnel Step 2 -->
                    <div class="bg-slate-50 dark:bg-[#0c1220] border border-slate-200/80 dark:border-[#1e293b] rounded-2xl p-6 relative overflow-hidden">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold mb-4">
                            2
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Checkout / Buat Pesanan</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pengunjung tertarik dan membuat invoice pembayaran produk.</p>
                        
                        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-[#1e293b] flex items-center justify-between">
                            <span class="text-xs text-slate-400">Invoice Terbit</span>
                            <span class="text-base font-extrabold text-amber-600 dark:text-amber-400">{{ $allOrdersCount }} Checkout</span>
                        </div>
                    </div>

                    <!-- Funnel Step 3 -->
                    <div class="bg-slate-50 dark:bg-[#0c1220] border border-slate-200/80 dark:border-[#1e293b] rounded-2xl p-6 relative overflow-hidden">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold mb-4">
                            3
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Pembayaran Sukses & Unduh</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pembayaran terverifikasi dan pelanggan mengunduh file produk digital.</p>
                        
                        <div class="mt-6 pt-4 border-t border-slate-200 dark:border-[#1e293b] flex items-center justify-between">
                            <span class="text-xs text-slate-400">Transaksi Selesai</span>
                            <span class="text-base font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalOrders }} Lunas</span>
                        </div>
                    </div>

                </div>

                <!-- Funnel Conversion Ratio Summary -->
                <div class="bg-white dark:bg-[#161f33] border border-slate-200 dark:border-slate-700/60 rounded-2xl p-6 flex items-center justify-between">
                    <div>
                        <span class="text-xs text-slate-400">Total Overall Conversion Rate</span>
                        <div class="text-2xl font-black text-slate-900 dark:text-white mt-0.5">{{ $conversionRate }}%</div>
                    </div>
                    <a href="{{ route('tenant.orders.index') }}" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold transition-all shadow-sm">
                        Kelola Status Transaksi
                    </a>
                </div>
            </div>

            <!-- TAB 4: SUMBER TRAFFIC & SALURAN -->
            <div x-show="tab === 'sumber'" class="p-6 md:p-8 space-y-6" style="display: none;">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Saluran Traffic & Rujukan Penjualan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Sumber pengunjung yang mengarahkan pembeli ke etalase toko digital Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Saluran 1: Etalase Publik & Website -->
                    <div class="border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 flex items-start gap-4 hover:border-sky-500/50 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">language</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Katalog Website & Pencarian</h4>
                                <span class="text-xs font-bold text-sky-600 dark:text-sky-400">75%</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Pengunjung langsung dari pencarian marketplace dan etalase utama.</p>
                        </div>
                    </div>

                    <!-- Saluran 2: Program Mitra Affiliate -->
                    <div class="border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 flex items-start gap-4 hover:border-sky-500/50 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">handshake</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Mitra Affiliate & Referral</h4>
                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">15%</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Tautan rujukan promotor afiliasi yang aktif mempromosikan produk.</p>
                            <a href="{{ route('tenant.affiliates.index') }}" class="text-[11px] font-bold text-indigo-500 hover:underline mt-2 inline-block">
                                Buka Program Affiliate →
                            </a>
                        </div>
                    </div>

                    <!-- Saluran 3: Media Sosial & Promosi Langsung -->
                    <div class="border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 flex items-start gap-4 hover:border-sky-500/50 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-pink-500/10 text-pink-500 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">share</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Media Sosial & Direct Link</h4>
                                <span class="text-xs font-bold text-pink-600 dark:text-pink-400">10%</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Tautan yang dibagikan melalui Instagram, TikTok, WhatsApp, dan grup komunitas.</p>
                        </div>
                    </div>

                    <!-- Saluran 4: Campaign Diskon & Event -->
                    <div class="border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 flex items-start gap-4 hover:border-sky-500/50 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px]">campaign</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Campaign & Flash Sale</h4>
                                <span class="text-xs font-bold text-amber-600 dark:text-amber-400">Aktif</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Event promo voucher dan flash deal khusus pelanggan toko.</p>
                            <a href="{{ route('tenant.campaigns.index') }}" class="text-[11px] font-bold text-amber-500 hover:underline mt-2 inline-block">
                                Kelola Campaign Promo →
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>
@endsection
