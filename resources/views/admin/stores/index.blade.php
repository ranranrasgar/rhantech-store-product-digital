@extends('layouts.admin')

@section('title', 'Manajemen & Analisis Toko Platform')

@section('content')
<div class="p-6 space-y-6 max-w-[1600px] mx-auto" x-data="storeManager()">

    {{-- Alert Flash Message --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-300 flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-500">check_circle</span>
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-300 flex items-center gap-3">
            <span class="material-symbols-outlined text-rose-500">error</span>
            <div class="text-sm font-semibold">{{ session('error') }}</div>
        </div>
    @endif

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-primary mb-1">
                <span class="material-symbols-outlined text-sm">storefront</span>
                <span>Tenant & Marketplace Control Center</span>
            </div>
            <h1 class="text-2xl font-black text-on-surface tracking-tight">Manajemen & Analisis Toko</h1>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Pantau perkembangan bisnis tenant, kesehatan transaksi, hak akses operasional, serta kepatuhan toko di platform.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.stores.index') }}" class="px-3.5 py-2 rounded-xl border border-outline-variant bg-surface-container-low hover:bg-surface-container text-on-surface text-xs font-bold flex items-center gap-1.5 transition">
                <span class="material-symbols-outlined text-base">refresh</span>
                <span>Muat Ulang</span>
            </a>
            <a href="{{ route('admin.payouts.index') }}" class="px-3.5 py-2 rounded-xl bg-primary/10 border border-primary/20 hover:bg-primary/20 text-primary text-xs font-bold flex items-center gap-1.5 transition">
                <span class="material-symbols-outlined text-base">payments</span>
                <span>Kelola Payout Tenant</span>
            </a>
        </div>
    </div>

    {{-- 1. Kartu Metrik Ringkasan Perkembangan Platform --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        {{-- Total Toko --}}
        <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Toko</span>
                <span class="p-2 rounded-xl bg-primary/10 text-primary material-symbols-outlined text-lg">store</span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-on-surface">{{ number_format($platformSummary['total_stores']) }}</div>
                <div class="flex items-center gap-2 mt-1 text-[11px] text-on-surface-variant">
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ $platformSummary['active_stores'] }} Aktif</span>
                    <span>•</span>
                    <span class="text-amber-600 dark:text-amber-400 font-semibold">{{ $platformSummary['suspended_stores'] }} Suspend</span>
                    <span>•</span>
                    <span class="text-rose-600 dark:text-rose-400 font-semibold">{{ $platformSummary['banned_stores'] }} Banned</span>
                </div>
            </div>
        </div>

        {{-- Toko PRO --}}
        <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Mitra PRO</span>
                <span class="p-2 rounded-xl bg-amber-500/10 text-amber-600 material-symbols-outlined text-lg">verified</span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-on-surface">{{ number_format($platformSummary['pro_stores']) }}</div>
                <div class="text-[11px] text-on-surface-variant mt-1">
                    {{ $platformSummary['total_stores'] > 0 ? round(($platformSummary['pro_stores'] / $platformSummary['total_stores']) * 100, 1) : 0 }}% dari total toko
                </div>
            </div>
        </div>

        {{-- Produk Tenant --}}
        <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Produk Tenant</span>
                <span class="p-2 rounded-xl bg-blue-500/10 text-blue-600 material-symbols-outlined text-lg">inventory_2</span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-on-surface">{{ number_format($platformSummary['total_products']) }}</div>
                <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                    {{ number_format($platformSummary['active_products']) }} produk aktif dijual
                </div>
            </div>
        </div>

        {{-- Pesanan Tenant --}}
        <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Pesanan Selesai</span>
                <span class="p-2 rounded-xl bg-purple-500/10 text-purple-600 material-symbols-outlined text-lg">shopping_cart_checkout</span>
            </div>
            <div class="mt-3">
                <div class="text-2xl font-black text-on-surface">{{ number_format($platformSummary['completed_orders']) }}</div>
                <div class="text-[11px] text-on-surface-variant mt-1">
                    dari {{ number_format($platformSummary['total_orders']) }} total transaksi
                </div>
            </div>
        </div>

        {{-- Saldo Beredar Tenant --}}
        <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 shadow-xs flex flex-col justify-between col-span-2 md:col-span-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Total Saldo Tenant</span>
                <span class="p-2 rounded-xl bg-emerald-500/10 text-emerald-600 material-symbols-outlined text-lg">account_balance_wallet</span>
            </div>
            <div class="mt-3">
                <div class="text-xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ number_format($platformSummary['total_store_balance'], 0, ',', '.') }}</div>
                <div class="text-[11px] text-on-surface-variant mt-1">
                    Saldo Iklan: Rp {{ number_format($platformSummary['total_ad_balance'], 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Filter & Pencarian Bar --}}
    <div class="bg-surface-container-lowest p-4 rounded-2xl border border-outline-variant/60 shadow-xs">
        <form method="GET" action="{{ route('admin.stores.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            {{-- Search Bar --}}
            <div class="lg:col-span-4">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase">Cari Toko / Pemilik</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama toko, slug, nama / email owner..." 
                        class="w-full pl-9 pr-3 py-2 text-sm rounded-xl bg-surface-container-low border border-outline-variant text-on-surface placeholder:text-on-surface-variant/50 focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
            </div>

            {{-- Filter Status --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase">Status</label>
                <select name="status" class="w-full py-2 px-3 text-sm rounded-xl bg-surface-container-low border border-outline-variant text-on-surface focus:ring-2 focus:ring-primary transition">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>🟢 Aktif Normal</option>
                    <option value="pro" {{ request('status') == 'pro' ? 'selected' : '' }}>⭐ Toko PRO</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>🟡 Ditangguhkan</option>
                    <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>🔴 Diblokir (Banned)</option>
                </select>
            </div>

            {{-- Filter Mode --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase">Mode Toko</label>
                <select name="mode" class="w-full py-2 px-3 text-sm rounded-xl bg-surface-container-low border border-outline-variant text-on-surface focus:ring-2 focus:ring-primary transition">
                    <option value="all" {{ request('mode') == 'all' || !request('mode') ? 'selected' : '' }}>Semua Mode</option>
                    <option value="store" {{ request('mode') == 'store' ? 'selected' : '' }}>Store Mode</option>
                    <option value="profile" {{ request('mode') == 'profile' ? 'selected' : '' }}>Profile Mode</option>
                    <option value="hybrid" {{ request('mode') == 'hybrid' ? 'selected' : '' }}>Hybrid Mode</option>
                </select>
            </div>

            {{-- Sorting --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-bold text-on-surface-variant mb-1.5 uppercase">Urutan</label>
                <select name="sort" class="w-full py-2 px-3 text-sm rounded-xl bg-surface-container-low border border-outline-variant text-on-surface focus:ring-2 focus:ring-primary transition">
                    <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>Terbaru Bergabung</option>
                    <option value="balance_desc" {{ request('sort') == 'balance_desc' ? 'selected' : '' }}>Saldo Tertinggi</option>
                    <option value="ad_balance_desc" {{ request('sort') == 'ad_balance_desc' ? 'selected' : '' }}>Saldo Iklan Tertinggi</option>
                    <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>Produk Terbanyak</option>
                    <option value="views_desc" {{ request('sort') == 'views_desc' ? 'selected' : '' }}>Views Terbanyak</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A - Z</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Paling Awal</option>
                </select>
            </div>

            {{-- Tombol Filter --}}
            <div class="lg:col-span-2 flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-4 rounded-xl bg-primary text-on-primary text-sm font-bold hover:bg-primary/90 transition shadow-xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-base">filter_list</span>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['search', 'status', 'mode', 'sort']))
                    <a href="{{ route('admin.stores.index') }}" title="Reset Filter" class="p-2 rounded-xl border border-outline-variant bg-surface-container-low hover:bg-surface-container text-on-surface-variant flex items-center justify-center transition">
                        <span class="material-symbols-outlined text-base">filter_alt_off</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- 3. Tabel Daftar Toko Komprehensif --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/60 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low/70 border-b border-outline-variant/50 text-[11px] font-extrabold uppercase tracking-wider text-on-surface-variant">
                        <th class="py-3.5 px-4">Toko & Identitas</th>
                        <th class="py-3.5 px-4">Pemilik (Tenant)</th>
                        <th class="py-3.5 px-4">Status & PRO</th>
                        <th class="py-3.5 px-4">Perkembangan Produk</th>
                        <th class="py-3.5 px-4">Penjualan & Omset</th>
                        <th class="py-3.5 px-4">Saldo Toko</th>
                        <th class="py-3.5 px-4 text-center">Aksi Moderasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 text-sm">
                    @forelse ($stores as $store)
                        @php
                            $metric = $orderMetrics[$store->id] ?? ['total_orders' => 0, 'paid_orders' => 0, 'pending_orders' => 0, 'gross_sales' => 0];
                            $status = $store->status ?? 'active';
                        @endphp
                        <tr class="hover:bg-surface-container-low/40 transition-colors {{ $status === 'banned' ? 'bg-rose-500/5' : ($status === 'suspended' ? 'bg-amber-500/5' : '') }}">
                            
                            {{-- 1. Toko & Identitas --}}
                            <td class="py-4 px-4 align-top">
                                <div class="flex items-start gap-3">
                                    {{-- Logo Toko --}}
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-11 h-11 rounded-xl object-cover border border-outline-variant/50 shadow-xs shrink-0">
                                    @else
                                        <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary font-black text-sm flex items-center justify-center border border-primary/20 shrink-0">
                                            {{ strtoupper(substr($store->name, 0, 2)) }}
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <div class="font-extrabold text-on-surface flex items-center gap-1.5">
                                            <span class="truncate max-w-[180px]">{{ $store->name }}</span>
                                            <a href="{{ url('/' . $store->slug) }}" target="_blank" title="Kunjungi Etalase Publik" class="text-primary hover:text-primary/80 transition-colors shrink-0">
                                                <span class="material-symbols-outlined text-sm">open_in_new</span>
                                            </a>
                                        </div>
                                        <div class="text-xs text-primary font-mono mt-0.5 truncate max-w-[180px]">
                                            /{{ $store->slug }}
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-1.5">
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-surface-container border border-outline-variant/40 text-on-surface-variant">
                                                {{ $store->store_mode ?? 'store' }}
                                            </span>
                                            <span class="text-[11px] text-on-surface-variant flex items-center gap-0.5" title="Total Pengunjung Toko">
                                                <span class="material-symbols-outlined text-[13px]">visibility</span>
                                                {{ number_format($store->views) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Pemilik (Tenant) --}}
                            <td class="py-4 px-4 align-top">
                                <div class="min-w-0">
                                    <div class="font-bold text-on-surface truncate max-w-[180px]">
                                        {{ $store->user->name ?? 'Tidak Ada Akun' }}
                                    </div>
                                    <div class="text-xs text-on-surface-variant truncate max-w-[180px] mt-0.5">
                                        {{ $store->user->email ?? '-' }}
                                    </div>
                                    @if($store->user && $store->user->phone)
                                        <div class="text-[11px] text-on-surface-variant/80 mt-0.5 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">phone</span>
                                            <span>{{ $store->user->phone }}</span>
                                        </div>
                                    @endif
                                    <div class="text-[10px] text-on-surface-variant/60 mt-1">
                                        Daftar: {{ $store->created_at->format('d M Y') }}
                                    </div>
                                </div>
                            </td>

                            {{-- 3. Status & PRO --}}
                            <td class="py-4 px-4 align-top">
                                <div class="space-y-1.5">
                                    {{-- Status Badge --}}
                                    @if($status === 'banned')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-500/10 text-rose-700 dark:text-rose-300 border border-rose-500/30" title="{{ $store->ban_reason }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Diblokir</span>
                                        </span>
                                    @elseif($status === 'suspended')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-300 border border-amber-500/30" title="{{ $store->ban_reason }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>Ditangguhkan</span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            <span>Aktif</span>
                                        </span>
                                    @endif

                                    {{-- Status PRO --}}
                                    <div>
                                        @if($store->is_pro)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-gradient-to-r from-amber-500/15 to-orange-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">
                                                <span class="material-symbols-outlined text-[12px]">verified</span>
                                                <span>PRO ({{ $store->pro_plan ?: 'Aktif' }})</span>
                                            </span>
                                        @else
                                            <span class="inline-block text-[11px] text-on-surface-variant/70">
                                                Reguler (Non-PRO)
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Ban reason snippet if exists --}}
                                    @if(($status === 'banned' || $status === 'suspended') && $store->ban_reason)
                                        <div class="text-[10px] text-rose-600 dark:text-rose-400 truncate max-w-[150px]" title="Alasan: {{ $store->ban_reason }}">
                                            "{{ $store->ban_reason }}"
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- 4. Perkembangan Produk --}}
                            <td class="py-4 px-4 align-top">
                                <div>
                                    <a href="{{ route('admin.products.index', ['store_id' => $store->id]) }}" class="font-extrabold text-on-surface hover:text-primary transition-colors flex items-center gap-1">
                                        <span>{{ $store->products_count }} Produk</span>
                                        <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                    </a>
                                    <div class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">
                                        {{ $store->active_products_count }} Aktif di etalase
                                    </div>
                                    <div class="text-[11px] text-on-surface-variant mt-1 flex items-center gap-2">
                                        <span>{{ $store->followers_count }} Pengikut</span>
                                        <span>•</span>
                                        <span>{{ $store->ads_count }} Iklan</span>
                                    </div>
                                </div>
                            </td>

                            {{-- 5. Penjualan & Omset --}}
                            <td class="py-4 px-4 align-top">
                                <div>
                                    <div class="font-black text-on-surface">
                                        Rp {{ number_format($metric['gross_sales'], 0, ',', '.') }}
                                    </div>
                                    <a href="{{ route('admin.orders.index', ['search' => $store->name]) }}" class="text-xs text-on-surface-variant hover:text-primary transition-colors mt-0.5 block">
                                        <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $metric['paid_orders'] }}</span> / {{ $metric['total_orders'] }} pesanan selesai
                                    </a>
                                    @if($metric['pending_orders'] > 0)
                                        <div class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold mt-0.5">
                                            {{ $metric['pending_orders'] }} pending
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- 6. Saldo Toko & Saldo Iklan --}}
                            <td class="py-4 px-4 align-top">
                                <div>
                                    <div class="font-bold text-primary">
                                        Rp {{ number_format($store->balance, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[11px] text-on-surface-variant mt-0.5">
                                        Iklan: <span class="font-semibold">Rp {{ number_format($store->ad_balance, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-[10px] text-on-surface-variant/70 mt-1">
                                        Fee Penarikan: {{ $store->getPayoutFeePercentage() }}%
                                    </div>
                                </div>
                            </td>

                            {{-- 7. Kolom Aksi Moderasi --}}
                            <td class="py-4 px-4 align-top text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Tombol Detail / Health --}}
                                    <button type="button" @click="openDetailModal({{ $store->id }})" title="Lihat Analisis Detail Toko"
                                        class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface transition cursor-pointer">
                                        <span class="material-symbols-outlined text-base">analytics</span>
                                    </button>

                                    {{-- Tombol Edit --}}
                                    <button type="button" @click="openEditModal({{ json_encode($store) }})" title="Edit Data & Status PRO Toko"
                                        class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary transition cursor-pointer">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </button>

                                    {{-- Tombol Banned / Unban --}}
                                    @if($status === 'banned' || $status === 'suspended')
                                        <button type="button" @click="openUnbanModal({{ json_encode($store) }})" title="Pulihkan / Aktifkan Kembali Toko"
                                            class="p-2 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 transition cursor-pointer">
                                            <span class="material-symbols-outlined text-base">lock_open</span>
                                        </button>
                                    @else
                                        <button type="button" @click="openBanModal({{ json_encode($store) }})" title="Bekukan / Banned Toko"
                                            class="p-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 transition cursor-pointer">
                                            <span class="material-symbols-outlined text-base">block</span>
                                        </button>
                                    @endif

                                    {{-- Tombol Hapus Aman --}}
                                    <button type="button" @click="openDeleteModal({{ $store->id }})" title="Hapus Toko (Inspeksi Relasi)"
                                        class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 transition cursor-pointer">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 px-4 text-center text-on-surface-variant">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <span class="material-symbols-outlined text-4xl opacity-40 mb-2">storefront</span>
                                    <p class="font-bold text-base text-on-surface">Tidak ada toko ditemukan</p>
                                    <p class="text-xs text-on-surface-variant mt-1">Coba sesuaikan kata kunci pencarian atau filter status Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($stores->hasPages())
            <div class="p-4 border-t border-outline-variant/30">
                {{ $stores->links() }}
            </div>
        @endif
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 1: EDIT TOKO & PENGATURAN PRO                      --}}
    {{-- ======================================================== --}}
    <div x-show="isEditModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.away="isEditModalOpen = false" class="bg-surface-container-lowest border border-outline-variant rounded-2xl w-full max-w-xl max-h-[90vh] overflow-y-auto shadow-2xl p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-outline-variant/40 pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">edit_note</span>
                    <h3 class="font-extrabold text-base text-on-surface">Edit Data & Konfigurasi Toko</h3>
                </div>
                <button type="button" @click="isEditModalOpen = false" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container transition">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <form :action="editActionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Nama Toko <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="currentStore.name" required
                            class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-2.5 text-on-surface focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Slug / Username URL <span class="text-rose-500">*</span></label>
                        <input type="text" name="slug" x-model="currentStore.slug" required
                            class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-2.5 text-on-surface font-mono focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1">Mode Toko</label>
                    <select name="store_mode" x-model="currentStore.store_mode" class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-2.5 text-on-surface focus:ring-2 focus:ring-primary">
                        <option value="store">Store (Marketplace Produk Digital)</option>
                        <option value="profile">Profile (Personal Link-in-bio)</option>
                        <option value="hybrid">Hybrid (Toko + Profil)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1">Deskripsi Singkat / Bio Toko</label>
                    <textarea name="description" x-model="currentStore.description" rows="2"
                        class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-2.5 text-on-surface focus:ring-2 focus:ring-primary"></textarea>
                </div>

                <div class="p-4 rounded-xl bg-surface-container-low/60 border border-outline-variant/40 space-y-3">
                    <div class="text-xs font-bold uppercase tracking-wider text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm">verified</span>
                        <span>Pengaturan Paket PRO & Biaya</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Status Toko PRO</label>
                            <select name="is_pro" x-model="currentStore.is_pro" class="w-full text-sm bg-surface-container border border-outline-variant rounded-xl p-2 text-on-surface">
                                <option :value="0">Reguler (Non-PRO)</option>
                                <option :value="1">Mitra PRO Aktif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Nama Plan PRO</label>
                            <input type="text" name="pro_plan" x-model="currentStore.pro_plan" placeholder="Contoh: Bulanan / Tahunan"
                                class="w-full text-sm bg-surface-container border border-outline-variant rounded-xl p-2 text-on-surface">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Masa Berlaku PRO</label>
                            <input type="date" name="pro_expires_at" x-model="currentStore.pro_expires_at_formatted"
                                class="w-full text-sm bg-surface-container border border-outline-variant rounded-xl p-2 text-on-surface">
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Kosongkan jika Lifetime Pro.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-on-surface mb-1">Custom Payout Fee (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="custom_payout_fee_percentage" x-model="currentStore.custom_payout_fee_percentage" placeholder="Default otomatis"
                                class="w-full text-sm bg-surface-container border border-outline-variant rounded-xl p-2 text-on-surface">
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Otomatis: PRO 1.0%, Reguler 2.5%</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Penyesuaian Saldo Toko (Rp)</label>
                        <input type="number" step="100" min="0" name="balance" x-model="currentStore.balance"
                            class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-2.5 text-on-surface font-mono focus:ring-2 focus:ring-primary">
                        <p class="text-[10px] text-amber-600 dark:text-amber-400 mt-0.5">Ubah hanya jika perlu koreksi manual dari admin.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Default Komisi Afiliasi (%)</label>
                        <input type="number" step="0.5" min="0" max="100" name="default_affiliate_commission" x-model="currentStore.default_affiliate_commission"
                            class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-2.5 text-on-surface focus:ring-2 focus:ring-primary">
                    </div>
                </div>

                <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                    <button type="button" @click="isEditModalOpen = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-primary text-on-primary text-sm font-bold hover:bg-primary/90 transition shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 2: BANNED / SUSPEND TOKO                           --}}
    {{-- ======================================================== --}}
    <div x-show="isBanModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.away="isBanModalOpen = false" class="bg-surface-container-lowest border border-outline-variant rounded-2xl w-full max-w-lg shadow-2xl p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">block</span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-on-surface">Tindakan Pembatasan / Banned Toko</h3>
                    <p class="text-xs text-on-surface-variant">Toko: <strong class="text-on-surface" x-text="banStoreData.name"></strong></p>
                </div>
            </div>

            <form :action="banActionUrl" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1">Pilih Tindakan Sanksi <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-3 rounded-xl border cursor-pointer transition flex items-start gap-2.5"
                            :class="banForm.target_status === 'suspended' ? 'border-amber-500 bg-amber-500/10 text-amber-900 dark:text-amber-200' : 'border-outline-variant bg-surface-container-low text-on-surface'">
                            <input type="radio" name="target_status" value="suspended" x-model="banForm.target_status" class="mt-0.5 text-amber-600 focus:ring-amber-500">
                            <div>
                                <div class="text-xs font-bold">Ditangguhkan (Suspend)</div>
                                <div class="text-[10px] opacity-80 mt-0.5">Peninjauan sementara, dapat dipulihkan kapan saja.</div>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border cursor-pointer transition flex items-start gap-2.5"
                            :class="banForm.target_status === 'banned' ? 'border-rose-500 bg-rose-500/10 text-rose-900 dark:text-rose-200' : 'border-outline-variant bg-surface-container-low text-on-surface'">
                            <input type="radio" name="target_status" value="banned" x-model="banForm.target_status" class="mt-0.5 text-rose-600 focus:ring-rose-500">
                            <div>
                                <div class="text-xs font-bold">Diblokir (Banned)</div>
                                <div class="text-[10px] opacity-80 mt-0.5">Pelanggaran berat, etalase ditutup total.</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1">Alasan Tindakan <span class="text-rose-500">*</span></label>
                    <textarea name="reason" x-model="banForm.reason" rows="3" required placeholder="Contoh: Mengunggah produk digital yang melanggar hak cipta / konten terlarang..."
                        class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-3 text-on-surface focus:ring-2 focus:ring-rose-500 placeholder:text-on-surface-variant/50"></textarea>
                    <p class="text-[10px] text-on-surface-variant mt-1">Alasan ini akan dicantumkan secara transparan di email dan notifikasi tenant.</p>
                </div>

                <div class="space-y-2 pt-2 border-t border-outline-variant/30">
                    <label class="flex items-center gap-2 text-xs text-on-surface font-semibold cursor-pointer">
                        <input type="checkbox" name="send_notification" value="1" checked class="rounded border-outline-variant text-primary focus:ring-primary">
                        <span>Kirim Push Notification & Notifikasi Lonceng ke Tenant</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs text-on-surface font-semibold cursor-pointer">
                        <input type="checkbox" name="send_email" value="1" checked class="rounded border-outline-variant text-primary focus:ring-primary">
                        <span>Kirim Email Resmi ke <span class="font-mono text-primary" x-text="banStoreData.user?.email || 'Pemilik'"></span></span>
                    </label>
                </div>

                <div class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                    <button type="button" @click="isBanModalOpen = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-rose-600 text-white text-sm font-bold hover:bg-rose-700 transition shadow-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base">gavel</span>
                        <span>Eksekusi Sanksi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 3: UNBAN / PULIHKAN TOKO                           --}}
    {{-- ======================================================== --}}
    <div x-show="isUnbanModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.away="isUnbanModalOpen = false" class="bg-surface-container-lowest border border-outline-variant rounded-2xl w-full max-w-md shadow-2xl p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">lock_open</span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-on-surface">Pulihkan Status Toko</h3>
                    <p class="text-xs text-on-surface-variant">Kembalikan status toko menjadi <strong>AKTIF</strong>.</p>
                </div>
            </div>

            <p class="text-sm text-on-surface-variant">
                Apakah Anda yakin ingin memulihkan toko <strong class="text-on-surface" x-text="unbanStoreData.name"></strong>? Seluruh produk akan kembali aktif dan pemilik toko akan menerima notifikasi bahwa toko sudah pulih.
            </p>

            <form :action="unbanActionUrl" method="POST" class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                @csrf
                @method('PATCH')
                <button type="button" @click="isUnbanModalOpen = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700 transition shadow-xs flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">check_circle</span>
                    <span>Ya, Aktifkan Toko</span>
                </button>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 4: HAPUS TOKO & INSPEKSI TABEL TERKAIT             --}}
    {{-- ======================================================== --}}
    <div x-show="isDeleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.away="isDeleteModalOpen = false" class="bg-surface-container-lowest border border-outline-variant rounded-2xl w-full max-w-lg shadow-2xl p-6 space-y-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">delete_forever</span>
                </div>
                <div>
                    <h3 class="font-extrabold text-base text-on-surface">Hapus Toko & Data Terkait</h3>
                    <p class="text-xs text-rose-600 dark:text-rose-400 font-semibold">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                </div>
            </div>

            {{-- Loading State --}}
            <div x-show="isLoadingRelated" class="py-8 flex flex-col items-center justify-center gap-2 text-on-surface-variant text-xs">
                <span class="material-symbols-outlined text-2xl animate-spin text-primary">progress_activity</span>
                <span>Menginspeksi data terkait di database...</span>
            </div>

            {{-- Data Terkait Toko --}}
            <div x-show="!isLoadingRelated" class="space-y-4">
                <p class="text-xs text-on-surface-variant">
                    Berikut adalah rekapitulasi data pada sistem yang terhubung langsung dengan toko <strong class="text-on-surface" x-text="relatedData.store?.name"></strong>:
                </p>

                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-blue-500">inventory_2</span>
                            <span>Produk Terdaftar</span>
                        </span>
                        <span class="font-black text-on-surface" x-text="relatedData.related?.products || 0"></span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-purple-500">shopping_bag</span>
                            <span>Pesanan Terhubung</span>
                        </span>
                        <span class="font-black text-on-surface" x-text="relatedData.related?.orders || 0"></span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-amber-500">campaign</span>
                            <span>Iklan Penjual</span>
                        </span>
                        <span class="font-black text-on-surface" x-text="relatedData.related?.ads || 0"></span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-emerald-500">forum</span>
                            <span>Riwayat Pesan Chat</span>
                        </span>
                        <span class="font-black text-on-surface" x-text="relatedData.related?.chats || 0"></span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-sky-500">group</span>
                            <span>Pengikut Toko</span>
                        </span>
                        <span class="font-black text-on-surface" x-text="relatedData.related?.followers || 0"></span>
                    </div>

                    <div class="p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 flex items-center justify-between">
                        <span class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm text-rose-500">account_balance</span>
                            <span>Permintaan Payout</span>
                        </span>
                        <span class="font-black text-on-surface" x-text="relatedData.related?.payouts || 0"></span>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-800 dark:text-rose-200 text-xs space-y-1">
                    <div class="font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">warning</span>
                        <span>Sisa Saldo Toko: Rp <span x-text="new Intl.NumberFormat('id-ID').format(relatedData.store?.balance || 0)"></span></span>
                    </div>
                    <p class="text-[11px] opacity-90 leading-relaxed">
                        Sistem akan membersihkan seluruh produk, kategori, tipe, iklan, dan obrolan toko secara aman. Riwayat pesanan pembeli akan tetap disimpan secara anonim agar pembeli tidak kehilangan akses produk.
                    </p>
                </div>

                {{-- Konfirmasi Ketik Nama --}}
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1">
                        Ketik nama toko <strong class="text-rose-600 font-mono" x-text="relatedData.store?.name"></strong> untuk konfirmasi:
                    </label>
                    <input type="text" x-model="deleteConfirmInput" placeholder="Ketik nama toko persis..."
                        class="w-full text-sm bg-surface-container-low border border-outline-variant rounded-xl p-2.5 text-on-surface focus:ring-2 focus:ring-rose-500 font-semibold">
                </div>
            </div>

            <form :action="deleteActionUrl" method="POST" class="pt-3 border-t border-outline-variant/30 flex items-center justify-end gap-2">
                @csrf
                @method('DELETE')
                <button type="button" @click="isDeleteModalOpen = false" class="px-4 py-2 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container transition">
                    Batal
                </button>
                <button type="submit" :disabled="deleteConfirmInput !== relatedData.store?.name"
                    class="px-5 py-2 rounded-xl bg-rose-600 text-white text-sm font-bold hover:bg-rose-700 disabled:opacity-40 disabled:cursor-not-allowed transition shadow-xs flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-base">delete</span>
                    <span>Hapus Permanen</span>
                </button>
            </form>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL 5: DETAIL ANALISIS KESEHATAN TOKO                  --}}
    {{-- ======================================================== --}}
    <div x-show="isDetailModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <div @click.away="isDetailModalOpen = false" class="bg-surface-container-lowest border border-outline-variant rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-outline-variant/40 pb-3">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-xl">analytics</span>
                    <h3 class="font-extrabold text-base text-on-surface">Analisis Perkembangan & Detail Toko</h3>
                </div>
                <button type="button" @click="isDetailModalOpen = false" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container transition">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <div x-show="isLoadingDetail" class="py-12 flex flex-col items-center justify-center gap-2 text-on-surface-variant text-xs">
                <span class="material-symbols-outlined text-3xl animate-spin text-primary">progress_activity</span>
                <span>Memuat data kesehatan toko...</span>
            </div>

            <div x-show="!isLoadingDetail" class="space-y-4">
                {{-- Store Summary Banner --}}
                <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/50 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="text-lg font-black text-on-surface" x-text="detailData.store?.name"></div>
                        <div class="text-xs text-primary font-mono" x-text="'/' + detailData.store?.slug"></div>
                        <div class="text-xs text-on-surface-variant mt-1">
                            Pemilik: <span class="font-semibold text-on-surface" x-text="detailData.store?.owner_name"></span> (<span x-text="detailData.store?.owner_email"></span>)
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="'/' + detailData.store?.slug" target="_blank" class="px-3 py-1.5 rounded-xl bg-primary text-on-primary text-xs font-bold hover:bg-primary/90 transition flex items-center gap-1">
                            <span>Buka Toko</span>
                            <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </a>
                    </div>
                </div>

                {{-- Status Card --}}
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40">
                        <div class="text-[11px] font-bold text-on-surface-variant uppercase">Status Operasional</div>
                        <div class="text-sm font-black uppercase mt-1" :class="detailData.store?.status === 'banned' ? 'text-rose-600' : (detailData.store?.status === 'suspended' ? 'text-amber-600' : 'text-emerald-600')" x-text="detailData.store?.status || 'active'"></div>
                    </div>

                    <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40">
                        <div class="text-[11px] font-bold text-on-surface-variant uppercase">Saldo Utama Toko</div>
                        <div class="text-sm font-black text-primary mt-1" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(detailData.store?.balance || 0)"></div>
                    </div>

                    <div class="p-3 rounded-xl bg-surface-container-low border border-outline-variant/40">
                        <div class="text-[11px] font-bold text-on-surface-variant uppercase">Saldo Iklan (Ads)</div>
                        <div class="text-sm font-black text-amber-600 mt-1" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(detailData.store?.ad_balance || 0)"></div>
                    </div>
                </div>

                {{-- Rekap Tabel Terkait & Link Cepat --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">Tautan & Entitas Terkait</h4>
                    <div class="divide-y divide-outline-variant/20 border border-outline-variant/40 rounded-xl overflow-hidden bg-surface-container-low/50 text-xs">
                        <div class="p-3 flex items-center justify-between">
                            <span class="text-on-surface font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-500 text-sm">inventory_2</span>
                                <span>Produk Terdaftar</span>
                            </span>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-on-surface" x-text="(detailData.related?.active_products || 0) + ' aktif / ' + (detailData.related?.products || 0) + ' total'"></span>
                                <a :href="'{{ route('admin.products.index') }}?store_id=' + detailData.store?.id" class="text-primary hover:underline font-bold flex items-center gap-0.5">
                                    <span>Lihat Semua</span>
                                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                                </a>
                            </div>
                        </div>

                        <div class="p-3 flex items-center justify-between">
                            <span class="text-on-surface font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined text-purple-500 text-sm">shopping_bag</span>
                                <span>Transaksi / Pesanan Toko</span>
                            </span>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-on-surface" x-text="(detailData.related?.completed_orders || 0) + ' lunas / ' + (detailData.related?.orders || 0) + ' pesanan'"></span>
                                <a :href="'{{ route('admin.orders.index') }}?search=' + encodeURIComponent(detailData.store?.name || '')" class="text-primary hover:underline font-bold flex items-center gap-0.5">
                                    <span>Lihat Pesanan</span>
                                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                                </a>
                            </div>
                        </div>

                        <div class="p-3 flex items-center justify-between">
                            <span class="text-on-surface font-semibold flex items-center gap-2">
                                <span class="material-symbols-outlined text-emerald-500 text-sm">payments</span>
                                <span>Permintaan Penarikan Dana (Payout)</span>
                            </span>
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-on-surface" x-text="(detailData.related?.payouts || 0) + ' permohonan'"></span>
                                <a href="{{ route('admin.payouts.index') }}" class="text-primary hover:underline font-bold flex items-center gap-0.5">
                                    <span>Buka Payout</span>
                                    <span class="material-symbols-outlined text-xs">chevron_right</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan Banned jika ada --}}
                <div x-show="detailData.store?.ban_reason" class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-800 dark:text-rose-200 text-xs">
                    <div class="font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">info</span>
                        <span>Catatan Sanksi / Banned:</span>
                    </div>
                    <p class="mt-1" x-text="detailData.store?.ban_reason"></p>
                </div>
            </div>

            <div class="pt-3 border-t border-outline-variant/30 flex justify-end">
                <button type="button" @click="isDetailModalOpen = false" class="px-4 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-sm font-semibold transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function storeManager() {
    return {
        // Edit Modal State
        isEditModalOpen: false,
        editActionUrl: '',
        currentStore: {},

        // Ban Modal State
        isBanModalOpen: false,
        banActionUrl: '',
        banStoreData: {},
        banForm: {
            target_status: 'suspended',
            reason: '',
            send_notification: true,
            send_email: true
        },

        // Unban Modal State
        isUnbanModalOpen: false,
        unbanActionUrl: '',
        unbanStoreData: {},

        // Delete Modal State
        isDeleteModalOpen: false,
        deleteActionUrl: '',
        deleteConfirmInput: '',
        isLoadingRelated: false,
        relatedData: {},

        // Detail Modal State
        isDetailModalOpen: false,
        isLoadingDetail: false,
        detailData: {},

        openEditModal(store) {
            this.currentStore = {
                ...store,
                pro_expires_at_formatted: store.pro_expires_at ? store.pro_expires_at.substring(0, 10) : ''
            };
            this.editActionUrl = '{{ url('admin/stores') }}/' + store.id;
            this.isEditModalOpen = true;
        },

        openBanModal(store) {
            this.banStoreData = store;
            this.banForm = {
                target_status: store.status === 'banned' ? 'banned' : 'suspended',
                reason: store.ban_reason || '',
                send_notification: true,
                send_email: true
            };
            this.banActionUrl = '{{ url('admin/stores') }}/' + store.id + '/ban';
            this.isBanModalOpen = true;
        },

        openUnbanModal(store) {
            this.unbanStoreData = store;
            this.unbanActionUrl = '{{ url('admin/stores') }}/' + store.id + '/unban';
            this.isUnbanModalOpen = true;
        },

        openDeleteModal(storeId) {
            this.deleteActionUrl = '{{ url('admin/stores') }}/' + storeId;
            this.deleteConfirmInput = '';
            this.isLoadingRelated = true;
            this.isDeleteModalOpen = true;

            fetch('{{ url('admin/stores') }}/' + storeId + '/related-data')
                .then(res => res.json())
                .then(data => {
                    this.relatedData = data;
                    this.isLoadingRelated = false;
                })
                .catch(err => {
                    console.error(err);
                    this.isLoadingRelated = false;
                });
        },

        openDetailModal(storeId) {
            this.isLoadingDetail = true;
            this.isDetailModalOpen = true;

            fetch('{{ url('admin/stores') }}/' + storeId + '/related-data')
                .then(res => res.json())
                .then(data => {
                    this.detailData = data;
                    this.isLoadingDetail = false;
                })
                .catch(err => {
                    console.error(err);
                    this.isLoadingDetail = false;
                });
        }
    };
}
</script>
@endpush
@endsection
