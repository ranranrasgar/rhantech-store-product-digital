@extends('layouts.shopee')
@section('title', 'Katalog Produk Digital')
@section('content')

<style>
    body { background: #f0f4f8; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Product card */
    .prod-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: transform 0.22s ease, box-shadow 0.22s ease;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .prod-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.13);
    }
    .prod-card .img-wrap {
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
        background: #f8fafc;
    }
    .prod-card .img-wrap img,
    .prod-card .img-wrap .no-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }
    .prod-card:hover .img-wrap img { transform: scale(1.06); }
    .prod-card .img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 55%);
        opacity: 0;
        transition: opacity 0.25s ease;
        display: flex; align-items: flex-end; justify-content: center;
        padding-bottom: 12px;
    }
    .prod-card:hover .img-overlay { opacity: 1; }

    /* Category chip */
    .cat-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
        border: 2px solid transparent;
        transition: all 0.18s ease;
        cursor: pointer;
        white-space: nowrap;
        background: #fff;
        color: #555;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }
    .cat-chip:hover, .cat-chip.active {
        border-color: var(--theme-primary, #00b3cc);
        color: var(--theme-primary, #00b3cc);
        background: rgba(0,179,204,0.06);
    }

    /* Hero gradient */
    .hero-strip {
        background: linear-gradient(135deg, #00b3cc 0%, #0077a8 100%);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
    }
    .hero-strip::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }

    /* Quick service card */
    .svc-card {
        background: #fff;
        border-radius: 14px;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 6px;
        padding: 16px 8px;
        font-size: 11px;
        font-weight: 600;
        color: #444;
        box-shadow: 0 1px 6px rgba(0,0,0,0.07);
        transition: all 0.2s ease;
        cursor: pointer;
        text-align: center;
        min-width: 80px;
    }
    .svc-card:hover { background: var(--theme-primary, #00b3cc); color: #fff; transform: translateY(-3px); }
    .svc-card:hover span { color: #fff; }
    .svc-card span { color: var(--theme-primary, #00b3cc); transition: color 0.2s; }

    /* Section title */
    .section-title {
        font-size: 18px; font-weight: 800; color: #1a202c;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 16px;
    }
    .section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: linear-gradient(to right, rgba(0,179,204,0.3), transparent);
        border-radius: 2px;
    }
    .section-title .accent {
        width: 5px; height: 20px;
        background: var(--theme-primary, #00b3cc);
        border-radius: 4px;
    }
</style>

<main class="pt-[70px] md:pt-[108px] pb-16 min-h-screen">
<div class="max-w-[1280px] mx-auto px-4 md:px-6">

    {{-- ── HERO STRIP WITH TOP PRODUCTS (PRODUK UNGGULAN PALING BANYAK DIKLIK) ── --}}
    <div class="hero-strip p-5 md:p-6 mb-6 flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-6">
        <div class="text-white max-w-sm shrink-0">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 backdrop-blur-xs text-[11px] font-bold uppercase tracking-wider text-white mb-2">
                <span class="material-symbols-outlined text-[14px] text-amber-300">local_fire_department</span>
                Produk Unggulan
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mb-2">
                Paling Banyak Dilihat
            </h1>
            <p class="text-xs md:text-sm text-white/80 leading-relaxed">
                Koleksi produk digital pilihan terbaik dan paling banyak diminati oleh pengguna.
            </p>
        </div>

        {{-- Top Products Mini Cards Carousel / List --}}
        <div class="flex-1 overflow-x-auto hide-scrollbar pb-1">
            <div class="flex items-center gap-3 min-w-max lg:justify-end">
                @forelse($topProducts as $top)
                    @php
                        $topImg = $top->images->where('is_main', true)->first() ?? $top->images->first();
                        $topDiscount = $top->discount_price && $top->discount_price > 0 && $top->discount_price < $top->price;
                        $topPrice = $topDiscount ? $top->discount_price : $top->price;
                    @endphp
                    <a href="{{ route('products.show', $top->slug) }}" 
                       class="group/card w-[170px] bg-white dark:bg-gray-800 rounded-xl p-2.5 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col border border-white/20 text-gray-800 dark:text-gray-100 relative">
                        
                        {{-- Top Badge / Views --}}
                        <div class="absolute top-1.5 right-1.5 z-10 bg-amber-500 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full flex items-center gap-0.5 shadow-xs">
                            <span class="material-symbols-outlined text-[10px]">trending_up</span>
                            <span>{{ number_format($top->views ?? 0) }}</span>
                        </div>

                        {{-- Image thumbnail --}}
                        <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 relative mb-2">
                            @if($topImg)
                                <img src="{{ asset('storage/' . $topImg->image_path) }}" alt="{{ $top->name }}" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <span class="material-symbols-outlined text-2xl">image</span>
                                </div>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h4 class="text-xs font-bold line-clamp-1 leading-snug group-hover/card:text-primary transition-colors mb-1" title="{{ $top->name }}">
                            {{ $top->name }}
                        </h4>

                        {{-- Store Name --}}
                        <div class="flex items-center gap-1 text-[10px] text-gray-500 dark:text-gray-400 mb-1.5">
                            <span class="material-symbols-outlined text-[12px] text-primary">storefront</span>
                            <span class="truncate font-medium">{{ $top->store ? $top->store->name : ($company->company_name ?? 'Official Store') }}</span>
                        </div>

                        {{-- Price --}}
                        <div class="mt-auto flex items-baseline justify-between pt-1 border-t border-gray-100 dark:border-gray-700">
                            <span class="text-primary font-bold text-xs">
                                Rp{{ number_format($topPrice, 0, ',', '.') }}
                            </span>
                            @if($topDiscount)
                                <span class="text-[9px] text-gray-400 line-through">
                                    Rp{{ number_format($top->price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="text-white/70 text-xs py-4">Belum ada produk unggulan</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── BANNER ROW ── --}}
    <div class="flex gap-3 mb-8 h-[160px] md:h-[240px]">
        @if(isset($banners) && $banners->has('main'))
            @php $mainBanner = $banners->get('main'); @endphp
            <a href="{{ $mainBanner->link ?? '#' }}" class="flex-[2] overflow-hidden rounded-xl shadow-sm relative group cursor-pointer block">
                <img src="{{ asset('storage/' . $mainBanner->image_path) }}" alt="{{ $mainBanner->title ?? 'Banner Utama' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>
            </a>
        @else
            <div class="flex-[2] overflow-hidden rounded-xl shadow-sm relative group cursor-pointer bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                <span class="text-gray-400 text-xs">Banner Utama (Kiri)</span>
            </div>
        @endif

        <div class="flex-1 flex flex-col gap-3">
            @if(isset($banners) && $banners->has('side_1'))
                @php $side1 = $banners->get('side_1'); @endphp
                <a href="{{ $side1->link ?? '#' }}" class="flex-1 overflow-hidden rounded-xl shadow-sm cursor-pointer group block">
                    <img src="{{ asset('storage/' . $side1->image_path) }}" alt="{{ $side1->title ?? 'Banner Samping Atas' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @else
                <div class="flex-1 overflow-hidden rounded-xl shadow-sm cursor-pointer group bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                    <span class="text-gray-400 text-xs">Samping Atas</span>
                </div>
            @endif

            @if(isset($banners) && $banners->has('side_2'))
                @php $side2 = $banners->get('side_2'); @endphp
                <a href="{{ $side2->link ?? '#' }}" class="flex-1 overflow-hidden rounded-xl shadow-sm cursor-pointer group block">
                    <img src="{{ asset('storage/' . $side2->image_path) }}" alt="{{ $side2->title ?? 'Banner Samping Bawah' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @else
                <div class="flex-1 overflow-hidden rounded-xl shadow-sm cursor-pointer group bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                    <span class="text-gray-400 text-xs">Samping Bawah</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── MAIN CATALOG SECTION: LEFT SIDEBAR FILTER & RIGHT PRODUCTS LIST ── --}}
    <section x-data="{
        search: '{{ request('search') }}',
        category: '{{ request('category') }}',
        type: '{{ request('type') }}',
        store: '{{ request('store') }}',
        sort: '{{ request('sort', 'latest') }}',
        loading: false,
        debounceTimer: null,

        init() {
            window.addEventListener('popstate', () => {
                const params = new URLSearchParams(window.location.search);
                this.search = params.get('search') || '';
                this.category = params.get('category') || '';
                this.type = params.get('type') || '';
                this.store = params.get('store') || '';
                this.sort = params.get('sort') || 'latest';
                this.fetchProducts(false);
            });
            this.bindPagination();
        },

        setCategory(id) {
            if (this.category == id) return;
            this.category = id;
            this.fetchProducts(true);
        },

        setType(id) {
            if (this.type == id) return;
            this.type = id;
            this.fetchProducts(true);
        },

        setStore(id) {
            if (this.store == id) return;
            this.store = id;
            this.fetchProducts(true);
        },

        setSort(sortVal) {
            if (this.sort == sortVal) return;
            this.sort = sortVal;
            this.fetchProducts(true);
        },

        onSearchInput() {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.fetchProducts(true);
            }, 350);
        },

        clearSearch() {
            this.search = '';
            this.fetchProducts(true);
        },

        resetFilters() {
            this.search = '';
            this.category = '';
            this.type = '';
            this.store = '';
            this.sort = 'latest';
            this.fetchProducts(true);
        },

        fetchProducts(updateUrl = true) {
            this.loading = true;
            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.category) params.append('category', this.category);
            if (this.type) params.append('type', this.type);
            if (this.store) params.append('store', this.store);
            if (this.sort && this.sort !== 'latest') params.append('sort', this.sort);
            params.append('ajax', '1');

            const url = '{{ route('products.index') }}?' + params.toString();

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const container = document.getElementById('products-grid-container');
                if (container) {
                    container.innerHTML = html;
                    this.bindPagination();
                }
                if (updateUrl) {
                    params.delete('ajax');
                    const newQuery = params.toString();
                    const newUrl = '{{ route('products.index') }}' + (newQuery ? '?' + newQuery : '');
                    window.history.pushState({}, '', newUrl);
                }
            })
            .catch(err => {
                console.error('Error fetching products:', err);
            })
            .finally(() => {
                this.loading = false;
            });
        },

        bindPagination() {
            const container = document.getElementById('products-grid-container');
            if (!container) return;
            const links = container.querySelectorAll('.product-pagination a');
            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const pageUrl = new URL(link.getAttribute('href'));
                    const page = pageUrl.searchParams.get('page');
                    if (!page) return;

                    this.loading = true;
                    const params = new URLSearchParams();
                    if (this.search) params.append('search', this.search);
                    if (this.category) params.append('category', this.category);
                    if (this.type) params.append('type', this.type);
                    if (this.store) params.append('store', this.store);
                    if (this.sort && this.sort !== 'latest') params.append('sort', this.sort);
                    params.append('page', page);
                    params.append('ajax', '1');

                    fetch('{{ route('products.index') }}?' + params.toString(), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(res => res.text())
                    .then(html => {
                        container.innerHTML = html;
                        this.bindPagination();
                        params.delete('ajax');
                        window.history.pushState({}, '', '{{ route('products.index') }}?' + params.toString());
                        window.scrollTo({ top: container.offsetTop - 120, behavior: 'smooth' });
                    })
                    .finally(() => {
                        this.loading = false;
                    });
                });
            });
        }
    }">
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            
            {{-- ── SIDEBAR FILTER (LEFT SIDE) ── --}}
            <aside class="w-full lg:w-72 shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 sticky top-24 shadow-sm">
                    
                    {{-- Header Filter --}}
                    <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">tune</span>
                            <h2 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Filter & Pencarian</h2>
                        </div>
                        <button type="button" 
                                x-show="search || category || type || store || sort !== 'latest'" 
                                x-cloak
                                @click="resetFilters()" 
                                class="text-xs font-semibold text-rose-500 hover:underline flex items-center gap-0.5 transition-opacity">
                            <span class="material-symbols-outlined text-sm">restart_alt</span> Reset
                        </button>
                    </div>

                    {{-- Form Filter & Pencarian --}}
                    <form @submit.prevent="fetchProducts(true)" class="space-y-4">
                        <div>
                            <label for="product-search" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                Pencarian Produk
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="product-search" 
                                       x-model="search"
                                       @input="onSearchInput()" 
                                       placeholder="Cari nama produk, toko..." 
                                       class="w-full pl-9 pr-8 py-2 text-xs md:text-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-base">
                                    search
                                </span>
                                <button type="button"
                                        x-show="search" 
                                        x-cloak
                                        @click="clearSearch()" 
                                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors"
                                        title="Hapus pencarian">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                            </div>
                        </div>

                        {{-- Sort Dropdown --}}
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2">
                                Urutkan Berdasarkan
                            </label>
                            <select x-model="sort" @change="fetchProducts(true)" class="w-full px-3 py-2 text-xs md:text-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg border border-gray-200 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="latest">Terbaru</option>
                                <option value="popular">Paling Banyak Dilihat</option>
                                <option value="best_seller">Terlaris</option>
                                <option value="price_low">Harga Terendah</option>
                                <option value="price_high">Harga Tertinggi</option>
                            </select>
                        </div>
                    </form>

                    {{-- Filter Kategori --}}
                    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2.5 flex items-center justify-between">
                            <span>Kategori Produk</span>
                            <span class="text-[11px] font-normal lowercase opacity-75">({{ $categories->count() }})</span>
                        </h3>

                        <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
                            <button type="button" 
                                    @click="setCategory('')" 
                                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                                    :class="!category ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm" :class="!category ? 'text-primary' : 'text-gray-400'">apps</span>
                                    Semua Kategori
                                </span>
                            </button>

                            @foreach($categories as $cat)
                            <button type="button" 
                                    @click="setCategory('{{ $cat->id }}')" 
                                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                                    :class="category == '{{ $cat->id }}' ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                                <div class="flex items-center gap-1.5 truncate pr-2">
                                    <span class="truncate">{{ $cat->name }}</span>
                                    @if($cat->store)
                                        <span class="text-[9px] px-1 py-0.2 rounded bg-amber-500/10 text-amber-600 dark:text-amber-400 font-normal shrink-0" title="Kategori dari toko {{ $cat->store->name }}">
                                            {{ Str::limit($cat->store->name, 10) }}
                                        </span>
                                    @endif
                                </div>
                                @if(isset($cat->products_count))
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full transition-colors shrink-0"
                                      :class="category == '{{ $cat->id }}' ? 'bg-primary text-white font-bold' : 'bg-gray-100 dark:bg-gray-700 text-gray-500'">
                                    {{ $cat->products_count }}
                                </span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Filter Tipe / Platform --}}
                    @if(isset($types) && $types->count() > 0)
                    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2.5 flex items-center justify-between">
                            <span>Tipe / Platform</span>
                            <span class="text-[11px] font-normal lowercase opacity-75">({{ $types->count() }})</span>
                        </h3>

                        <div class="space-y-1 max-h-56 overflow-y-auto pr-1">
                            <button type="button" 
                                    @click="setType('')" 
                                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                                    :class="!type ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm" :class="!type ? 'text-primary' : 'text-gray-400'">devices</span>
                                    Semua Tipe
                                </span>
                            </button>

                            @foreach($types as $tp)
                            <button type="button" 
                                    @click="setType('{{ $tp->id }}')" 
                                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                                    :class="type == '{{ $tp->id }}' ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                                <div class="flex items-center gap-1.5 truncate pr-2">
                                    <span class="truncate">{{ $tp->name }}</span>
                                    @if($tp->store)
                                        <span class="text-[9px] px-1 py-0.2 rounded bg-sky-500/10 text-sky-600 dark:text-sky-400 font-normal shrink-0" title="Tipe dari toko {{ $tp->store->name }}">
                                            {{ Str::limit($tp->store->name, 10) }}
                                        </span>
                                    @endif
                                </div>
                                @if(isset($tp->products_count))
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full transition-colors shrink-0"
                                      :class="type == '{{ $tp->id }}' ? 'bg-primary text-white font-bold' : 'bg-gray-100 dark:bg-gray-700 text-gray-500'">
                                    {{ $tp->products_count }}
                                </span>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Filter Toko (Store) --}}
                    @if(isset($stores) && $stores->count() > 0)
                    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2.5 flex items-center justify-between">
                            <span>Toko / Mitra</span>
                            <span class="text-[11px] font-normal lowercase opacity-75">({{ $stores->count() }})</span>
                        </h3>

                        <div class="space-y-1 max-h-44 overflow-y-auto pr-1">
                            <button type="button" 
                                    @click="setStore('')" 
                                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                                    :class="!store ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                                <span class="flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-sm" :class="!store ? 'text-primary' : 'text-gray-400'">storefront</span>
                                    Semua Toko
                                </span>
                            </button>

                            @foreach($stores as $st)
                            <button type="button" 
                                    @click="setStore('{{ $st->id }}')" 
                                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-lg text-xs md:text-sm text-left transition-all"
                                    :class="store == '{{ $st->id }}' ? 'bg-primary/10 text-primary font-bold border-l-4 border-primary' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'">
                                <span class="truncate pr-2">{{ $st->name }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Active Filter Tags --}}
                    <div x-show="search || category || type || store" x-cloak class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-2">Filter Aktif:</span>
                        <div class="flex flex-wrap gap-1.5">
                            <template x-if="search">
                                <span class="inline-flex items-center gap-1 text-[11px] bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2.5 py-0.5 rounded-full border border-gray-300 dark:border-gray-600">
                                    <span x-text="'&quot;' + (search.length > 15 ? search.substring(0, 15) + '...' : search) + '&quot;'"></span>
                                    <button type="button" @click="clearSearch()" class="hover:text-rose-500">
                                        <span class="material-symbols-outlined text-xs">close</span>
                                    </button>
                                </span>
                            </template>

                            <template x-if="category">
                                <span class="inline-flex items-center gap-1 text-[11px] bg-primary/15 text-primary px-2.5 py-0.5 rounded-full font-semibold">
                                    <span>Kategori Terpilih</span>
                                    <button type="button" @click="setCategory('')" class="hover:text-rose-500">
                                        <span class="material-symbols-outlined text-xs">close</span>
                                    </button>
                                </span>
                            </template>

                            <template x-if="type">
                                <span class="inline-flex items-center gap-1 text-[11px] bg-sky-500/15 text-sky-600 dark:text-sky-400 px-2.5 py-0.5 rounded-full font-semibold">
                                    <span>Tipe Terpilih</span>
                                    <button type="button" @click="setType('')" class="hover:text-rose-500">
                                        <span class="material-symbols-outlined text-xs">close</span>
                                    </button>
                                </span>
                            </template>

                            <template x-if="store">
                                <span class="inline-flex items-center gap-1 text-[11px] bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 px-2.5 py-0.5 rounded-full font-semibold">
                                    <span>Toko Terpilih</span>
                                    <button type="button" @click="setStore('')" class="hover:text-rose-500">
                                        <span class="material-symbols-outlined text-xs">close</span>
                                    </button>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ── PRODUCTS GRID & RESULTS (RIGHT SIDE) ── --}}
            <main class="flex-1 w-full min-w-0 relative">
                {{-- Loading Indicator Overlay --}}
                <div x-show="loading" 
                     x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 bg-white/60 dark:bg-black/50 backdrop-blur-xs z-20 flex items-start justify-center pt-24 rounded-xl">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-2.5 rounded-xl shadow-xl flex items-center gap-3">
                        <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-200">Memperbarui katalog...</span>
                    </div>
                </div>

                {{-- Dynamic Products Grid Container --}}
                <div id="products-grid-container" class="transition-opacity duration-200" :class="loading ? 'opacity-40' : 'opacity-100'">
                    @include('products._list', ['products' => $products])
                </div>
            </main>

        </div>
    </section>

</div>
</main>
@endsection
