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
        object-position: top;
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

    {{-- ── HERO STRIP WITH TOP PRODUCTS (PRODUK UNGGULAN PALING BANYAK DIKLIK) - HIDDEN ON MOBILE ── --}}
    <div class="hero-strip p-5 md:p-6 mb-6 hidden md:flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-6">
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
    @php
        $hasMain = isset($banners) && $banners->has('main') && $banners->get('main')->image_path;
        $hasSide1 = isset($banners) && $banners->has('side_1') && $banners->get('side_1')->image_path;
        $hasSide2 = isset($banners) && $banners->has('side_2') && $banners->get('side_2')->image_path;
        $anyActiveBanner = $hasMain || $hasSide1 || $hasSide2;
    @endphp

    @if($anyActiveBanner)
    <div class="hidden md:flex flex-col md:flex-row gap-3 mb-8 {{ $hasMain && ($hasSide1 || $hasSide2) ? 'h-auto md:h-[240px]' : '' }}">
        @if($hasMain)
            @php $mainBanner = $banners->get('main'); @endphp
            <a href="{{ $mainBanner->link ?? '#' }}" class="{{ ($hasSide1 || $hasSide2) ? 'flex-[2] h-[160px] md:h-full' : 'w-full h-[180px] md:h-[260px]' }} overflow-hidden rounded-xl shadow-sm relative group cursor-pointer block">
                <img src="{{ asset('storage/' . $mainBanner->image_path) }}" alt="{{ $mainBanner->title ?? 'Banner Utama' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-r from-black/25 to-transparent"></div>
            </a>
        @endif

        @if($hasSide1 || $hasSide2)
        <div class="{{ $hasMain ? 'flex-1' : 'w-full' }} flex flex-col sm:flex-row md:flex-col gap-3">
            @if($hasSide1)
                @php $side1 = $banners->get('side_1'); @endphp
                <a href="{{ $side1->link ?? '#' }}" class="flex-1 h-[115px] md:h-full overflow-hidden rounded-xl shadow-sm cursor-pointer group block">
                    <img src="{{ asset('storage/' . $side1->image_path) }}" alt="{{ $side1->title ?? 'Banner Samping Atas' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @endif

            @if($hasSide2)
                @php $side2 = $banners->get('side_2'); @endphp
                <a href="{{ $side2->link ?? '#' }}" class="flex-1 h-[115px] md:h-full overflow-hidden rounded-xl shadow-sm cursor-pointer group block">
                    <img src="{{ asset('storage/' . $side2->image_path) }}" alt="{{ $side2->title ?? 'Banner Samping Bawah' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @endif
        </div>
        @endif
    </div>
    @endif

    {{-- ── MAIN CATALOG SECTION: LEFT SIDEBAR FILTER & RIGHT PRODUCTS LIST ── --}}
    <section x-data="{
        search: '{{ request('search') }}',
        category: '{{ request('category') }}',
        type: '{{ request('type') }}',
        store: '{{ request('store') }}',
        sort: '{{ request('sort', 'latest') }}',
        loading: false,
        loadingMore: false,
        currentPage: {{ $products->currentPage() }},
        lastPage: {{ $products->lastPage() }},
        hasMorePages: {{ $products->hasMorePages() ? 'true' : 'false' }},
        mobileFilterOpen: false,
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
            this.initInfiniteScroll();
        },

        initInfiniteScroll() {
            window.addEventListener('scroll', () => {
                if (this.loading || this.loadingMore || !this.hasMorePages) return;
                // Trigger when user scrolls down towards the bottom of page
                const scrollPosition = window.innerHeight + window.pageYOffset;
                const threshold = document.documentElement.offsetHeight - 750;
                if (scrollPosition >= threshold) {
                    this.loadMoreProducts();
                }
            }, { passive: true });
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

        getActiveFilterCount() {
            let count = 0;
            if (this.category) count++;
            if (this.type) count++;
            if (this.store) count++;
            return count;
        },

        fetchProducts(updateUrl = true) {
            this.loading = true;
            this.currentPage = 1;
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
                    const gridEl = document.getElementById('products-items-grid');
                    if (gridEl) {
                        this.currentPage = parseInt(gridEl.dataset.currentPage) || 1;
                        this.lastPage = parseInt(gridEl.dataset.lastPage) || 1;
                        this.hasMorePages = gridEl.dataset.hasMore === '1';
                    }
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

        loadMoreProducts() {
            if (this.loadingMore || !this.hasMorePages) return;
            this.loadingMore = true;
            const nextPage = this.currentPage + 1;

            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.category) params.append('category', this.category);
            if (this.type) params.append('type', this.type);
            if (this.store) params.append('store', this.store);
            if (this.sort && this.sort !== 'latest') params.append('sort', this.sort);
            params.append('page', nextPage);
            params.append('ajax', '1');

            fetch('{{ route('products.index') }}?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = html;
                const newGrid = tempDiv.querySelector('#products-items-grid');
                const targetGrid = document.getElementById('products-items-grid');

                if (newGrid && targetGrid) {
                    const newCards = newGrid.querySelectorAll('.prod-card');
                    newCards.forEach(card => targetGrid.appendChild(card));
                    this.currentPage = parseInt(newGrid.dataset.currentPage) || nextPage;
                    this.lastPage = parseInt(newGrid.dataset.lastPage) || this.lastPage;
                    this.hasMorePages = newGrid.dataset.hasMore === '1';
                } else {
                    this.hasMorePages = false;
                }
            })
            .catch(err => {
                console.error('Error loading more products:', err);
            })
            .finally(() => {
                this.loadingMore = false;
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
                        const gridEl = document.getElementById('products-items-grid');
                        if (gridEl) {
                            this.currentPage = parseInt(gridEl.dataset.currentPage) || parseInt(page);
                            this.lastPage = parseInt(gridEl.dataset.lastPage) || this.lastPage;
                            this.hasMorePages = gridEl.dataset.hasMore === '1';
                        }
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

        {{-- ── MOBILE SHOPEE-STYLE SUB-HEADER (TABS & FILTER ICON) ── --}}
        <div class="md:hidden sticky top-[68px] z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 -mx-4 px-4 py-2 mb-3 shadow-xs">
            <div class="flex items-center justify-between gap-2 overflow-x-auto hide-scrollbar">
                {{-- Quick Sort Pills --}}
                <div class="flex items-center gap-1.5 flex-1 overflow-x-auto hide-scrollbar py-0.5">
                    <button type="button" 
                            @click="setSort('latest')" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all"
                            :class="sort === 'latest' ? 'bg-primary text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200'">
                        Terbaru
                    </button>
                    <button type="button" 
                            @click="setSort('popular')" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all"
                            :class="sort === 'popular' ? 'bg-primary text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200'">
                        Terpopuler
                    </button>
                    <button type="button" 
                            @click="setSort('best_seller')" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all"
                            :class="sort === 'best_seller' ? 'bg-primary text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200'">
                        Terlaris
                    </button>
                    <button type="button" 
                            @click="setSort(sort === 'price_low' ? 'price_high' : 'price_low')" 
                            class="px-3 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all flex items-center gap-0.5"
                            :class="(sort === 'price_low' || sort === 'price_high') ? 'bg-primary text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200'">
                        <span>Harga</span>
                        <span class="material-symbols-outlined text-xs">
                            <span x-text="sort === 'price_high' ? 'arrow_downward' : 'arrow_upward'"></span>
                        </span>
                    </button>
                </div>

                {{-- Shopee-style Filter Icon Button --}}
                <button type="button" 
                        @click="mobileFilterOpen = true" 
                        class="shrink-0 flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors relative"
                        title="Buka Filter">
                    <span class="material-symbols-outlined text-[16px] text-primary">tune</span>
                    <span>Filter</span>
                    <template x-if="getActiveFilterCount() > 0">
                        <span class="w-4 h-4 rounded-full bg-rose-500 text-white text-[9px] font-black flex items-center justify-center -mr-1"
                              x-text="getActiveFilterCount()"></span>
                    </template>
                </button>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 items-start">
            
            {{-- ── DESKTOP SIDEBAR FILTER (LEFT SIDE) ── --}}
            <aside class="hidden lg:block w-72 shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 sticky top-24 shadow-sm">
                    @include('products._filter_content', ['suffix' => 'desktop'])
                </div>
            </aside>

            {{-- ── MOBILE SHOPEE-STYLE FILTER SLIDE-OVER DRAWER ── --}}
            <div x-show="mobileFilterOpen" 
                 x-cloak
                 class="fixed inset-0 z-50 overflow-hidden lg:hidden" 
                 aria-labelledby="slide-over-title" 
                 role="dialog" 
                 aria-modal="true">
                {{-- Backdrop --}}
                <div x-show="mobileFilterOpen"
                     x-transition:enter="ease-in-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in-out duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="mobileFilterOpen = false"
                     class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"></div>

                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="mobileFilterOpen"
                         x-transition:enter="transform transition ease-in-out duration-300"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in-out duration-300"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="w-screen max-w-xs bg-white dark:bg-gray-800 shadow-2xl flex flex-col justify-between">
                        
                        {{-- Drawer Header --}}
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-900">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">tune</span>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white uppercase tracking-wider">Filter & Urutkan</h3>
                            </div>
                            <button type="button" 
                                    @click="mobileFilterOpen = false" 
                                    class="p-1 rounded-full text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                <span class="material-symbols-outlined text-xl">close</span>
                            </button>
                        </div>

                        {{-- Drawer Body --}}
                        <div class="p-4 overflow-y-auto flex-1">
                            @include('products._filter_content', ['suffix' => 'mobile'])
                        </div>

                        {{-- Drawer Footer Actions --}}
                        <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-3">
                            <button type="button" 
                                    @click="resetFilters(); mobileFilterOpen = false;" 
                                    class="flex-1 py-2.5 px-3 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 text-center hover:bg-gray-100 dark:hover:bg-gray-750 transition-colors">
                                Reset
                            </button>
                            <button type="button" 
                                    @click="mobileFilterOpen = false" 
                                    class="flex-1 py-2.5 px-3 bg-primary text-white rounded-lg text-xs font-bold text-center hover:opacity-90 shadow-sm transition-opacity">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

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
