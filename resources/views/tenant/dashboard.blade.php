@extends('layouts.tenant')

@section('title', 'Dashboard Toko')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header Hero & Quick Info -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0f172a] via-[#1e293b] to-[#0284c7] dark:from-[#0b1329] dark:via-[#111c38] dark:to-[#0369a1] text-white p-6 md:p-8 shadow-xl border border-white/10">
            <!-- Background Glow Effects -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl p-1 bg-white/10 backdrop-blur-md border border-white/20 shadow-inner overflow-hidden shrink-0">
                        @if($store && $store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 rounded-xl flex items-center justify-center font-black text-2xl text-white">
                                {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-sky-200 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Merchant Partner
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2">
                            {{ $store->name ?? 'Toko Saya' }}
                        </h1>
                        <p class="text-xs md:text-sm text-slate-300 mt-1 max-w-xl line-clamp-1">
                            {{ $store->description ?: 'Kelola produk digital, pantau penjualan, dan tingkatkan penghasilan Anda.' }}
                        </p>
                    </div>
                </div>

                <!-- Action Hub Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    @if($store && $store->slug)
                    <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white text-xs md:text-sm font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">storefront</span>
                        Lihat Toko Publik
                    </a>
                    @endif
                    <a href="{{ route('tenant.products.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs md:text-sm font-bold shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 transition-all duration-200 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        <!-- 4 Essential Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1: Total Revenue -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Saldo Penjual</span>
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

            <!-- Metric 2: Completed Orders -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
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
                        <span>Dari {{ number_format($totalOrdersCount ?? 0) }} total order</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100 }}% Sukses
                        </span>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Pending Orders -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Menunggu Pembayaran</span>
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

            <!-- Metric 4: Active Products -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
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
                        <span class="text-slate-400">Produk berstatus aktif</span>
                        <a href="{{ route('tenant.products.index') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Kelola Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area: 2 Columns (7:5) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Area: Recent Orders (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
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
                    <a href="{{ route('tenant.appearance.index') }}" class="group bg-gradient-to-br from-indigo-50 to-white dark:from-[#131b2e] dark:to-[#111726] border border-indigo-100 dark:border-[#263553] rounded-2xl p-5 hover:border-indigo-400 dark:hover:border-indigo-500 transition-all shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 mb-3 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[20px]">palette</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors flex items-center gap-1">
                            Dekorasi Toko <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kustomisasi banner, tampilan beranda, dan tema etalase toko Anda.</p>
                    </a>

                    <a href="{{ route('tenant.bank.index') }}" class="group bg-gradient-to-br from-sky-50 to-white dark:from-[#101e33] dark:to-[#111726] border border-sky-100 dark:border-[#1d3559] rounded-2xl p-5 hover:border-sky-400 dark:hover:border-sky-500 transition-all shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center shadow-md shadow-sky-500/20 mb-3 group-hover:scale-105 transition-transform">
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
                
                <!-- Store Quick Glance -->
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm p-6">
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

                <!-- Tips & Growth Guide -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-6 shadow-md border border-slate-700 relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-sky-500/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                        </div>
                        <h3 class="font-bold text-sm text-white">Tips Penjualan Optimal</h3>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Lengkapi deskripsi produk digital Anda dengan informasi spesifikasi source code/aplikasi, panduan instalasi, dan link demo langsung untuk meningkatkan kepercayaan calon pembeli.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-700/60 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Pusat Bantuan Mitra</span>
                        <a href="{{ route('contact') }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition-colors flex items-center gap-0.5">
                            Hubungi Tim Support <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
