@extends('layouts.tenant')

@section('title', 'Etalase Produk Afiliasi')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Etalase Produk Afiliasi (Showcase)
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pilih produk digital dari platform atau toko tenant lain untuk dipajang langsung di etalase toko Anda.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('help.show', 'panduan-memasang-produk-toko-lain-di-etalase-toko-saya-showcase') }}" target="_blank" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 transition flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px] text-sky-500">menu_book</span>
                    Panduan Etalase Afiliasi
                </a>
                <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-white dark:bg-[#111726] hover:bg-slate-50 dark:hover:bg-[#161f33] text-xs font-bold text-slate-700 dark:text-slate-200 transition flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    Lihat Etalase Toko Saya
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm space-y-4">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
                
                <!-- Tab Kategori Produk -->
                <div class="flex flex-wrap items-center gap-1.5 p-1 bg-slate-100 dark:bg-[#0c1220] rounded-xl border border-slate-200/60 dark:border-[#222f49]">
                    <a href="{{ route('tenant.showcase.index', ['tab' => 'semua', 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ $tab === 'semua' ? 'bg-white dark:bg-[#1f2a40] text-sky-600 dark:text-sky-400 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                        Semua Produk
                    </a>
                    <a href="{{ route('tenant.showcase.index', ['tab' => 'platform', 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ $tab === 'platform' ? 'bg-white dark:bg-[#1f2a40] text-sky-600 dark:text-sky-400 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                        Produk Platform (Official)
                    </a>
                    <a href="{{ route('tenant.showcase.index', ['tab' => 'tenant', 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ $tab === 'tenant' ? 'bg-white dark:bg-[#1f2a40] text-sky-600 dark:text-sky-400 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                        Produk Toko Tenant Lain
                    </a>
                    <a href="{{ route('tenant.showcase.index', ['tab' => 'terpasang', 'search' => request('search')]) }}" class="px-4 py-2 rounded-lg text-xs font-bold transition {{ $tab === 'terpasang' ? 'bg-white dark:bg-[#1f2a40] text-emerald-600 dark:text-emerald-400 shadow-xs' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900' }}">
                        Dipajang di Toko Saya ({{ count($myShowcaseIds) }})
                    </a>
                </div>

                <!-- Form Search -->
                <form method="GET" action="{{ route('tenant.showcase.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative w-full sm:w-64 flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk / toko..." class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition">
                    </div>
                    <button type="submit" class="px-4 py-2 text-xs font-bold bg-sky-500 hover:bg-sky-400 text-white rounded-xl shadow-xs transition">
                        Cari
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($products as $product)
                @php
                    $isInstalled = in_array($product->id, $myShowcaseIds);
                    $mainImage = $product->images->where('is_main', true)->first() ?? $product->images->first();
                @endphp
                <div class="bg-white dark:bg-[#111726] border {{ $isInstalled ? 'border-emerald-500/50 shadow-md shadow-emerald-500/5 ring-1 ring-emerald-500/20' : 'border-slate-200/80 dark:border-[#222f49]' }} rounded-2xl overflow-hidden flex flex-col transition hover:shadow-lg">
                    
                    <!-- Image Box -->
                    <div class="aspect-video w-full bg-slate-100 dark:bg-slate-800 relative overflow-hidden group">
                        @if($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                            </div>
                        @endif

                        <!-- Badge Origin -->
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($product->store)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-600/90 text-white backdrop-blur-md shadow-xs">
                                    <span class="material-symbols-outlined text-[12px]">storefront</span>
                                    {{ $product->store->name }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-sky-600/90 text-white backdrop-blur-md shadow-xs">
                                    <span class="material-symbols-outlined text-[12px]">verified</span>
                                    Platform Official
                                </span>
                            @endif
                        </div>

                        <!-- Status Terpasang Indicator -->
                        @if($isInstalled)
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white shadow-sm">
                                    <span class="material-symbols-outlined text-[13px]">check_circle</span> Terpasang di Toko
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-4 flex flex-col flex-1">
                        <div class="text-[11px] text-slate-400 mb-1">
                            {{ $product->category->name ?? 'Produk Digital' }}
                        </div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-2 mb-3 leading-snug">
                            {{ $product->name }}
                        </h3>

                        <!-- Price -->
                        <div class="mt-auto pt-3 border-t border-slate-100 dark:border-[#1d273d] flex items-end justify-between mb-4">
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400">Harga Jual</div>
                                @if($product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price)
                                    <div class="text-xs line-through text-slate-400">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="font-extrabold text-sm text-sky-600 dark:text-sky-400">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                @else
                                    <div class="font-extrabold text-sm text-slate-900 dark:text-white">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                @endif
                            </div>

                            <!-- Perkiraan Komisi -->
                            <div class="text-right">
                                <a href="{{ route('help.show', 'panduan-memasang-produk-toko-lain-di-etalase-toko-saya-showcase') }}" target="_blank" class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/40 hover:bg-amber-100 transition" title="Pelajari cara kerja bagi hasil komisi showcase">
                                    Komisi Afiliasi
                                    <span class="material-symbols-outlined text-[12px]">help</span>
                                </a>
                            </div>
                        </div>

                        <!-- Action Button: Pasang / Copot -->
                        <form action="{{ route('tenant.showcase.toggle', $product->id) }}" method="POST" class="w-full">
                            @csrf
                            @if($isInstalled)
                                <button type="submit" class="w-full py-2 px-3 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 text-xs font-bold transition flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">remove_shopping_cart</span>
                                    Copot dari Toko Saya
                                </button>
                            @else
                                <button type="submit" class="w-full py-2 px-3 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm shadow-sky-500/20">
                                    <span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
                                    + Pasang di Etalase Toko
                                </button>
                            @endif
                        </form>

                    </div>

                </div>
            @empty
                <div class="col-span-full p-16 text-center text-slate-400 bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl">
                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-400">inventory_2</span>
                    <h3 class="font-bold text-base text-slate-800 dark:text-white">Tidak ada produk ditemukan</h3>
                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau pilih tab produk yang berbeda.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="pt-4 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
