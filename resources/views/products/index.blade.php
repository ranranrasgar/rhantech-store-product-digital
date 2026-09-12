@extends('layouts.shopee')
@section('title', 'Jual Source Code & Aplikasi Digital Siap Pakai - ' . ($company->company_name ?? 'R-Tech'))
@section('meta_description', 'Katalog terlengkap jual beli source code aplikasi web, aplikasi kasir (POS), sistem informasi sekolah, toko online, Android/iOS & script PHP siap pakai bergaransi.')
@section('meta_keywords', 'aplikasi kasir pos, source code web, jual source code laravel, script php indonesia, sistem informasi sekolah, template website, download source code murah')
@section('canonical_url', route('products.index'))

@section('schema_json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "Katalog Produk Digital & Source Code Aplikasi",
  "description": "Katalog terlengkap jual beli source code aplikasi web, kasir POS, sistem informasi sekolah, toko online siap pakai.",
  "url": "{{ route('products.index') }}",
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      {
        "@type": "ListItem",
        "position": 1,
        "name": "Beranda",
        "item": "{{ url('/') }}"
      },
      {
        "@type": "ListItem",
        "position": 2,
        "name": "Katalog Produk Digital",
        "item": "{{ route('products.index') }}"
      }
    ]
  }
}
</script>
@endsection

@section('content')

<style>
    body { background: #f0f4f8; }
    html.dark body { background: #0f172a !important; }
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Product card */
    .prod-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.05);
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
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

    /* Dark Mode support for prod-card */
    html.dark .prod-card,
    .dark .prod-card {
        background: rgb(var(--theme-surface-container, 30 41 59)) !important;
        border: 1px solid rgb(var(--theme-outline-variant, 51 65 85) / 0.5) !important;
        box-shadow: 0 4px 14px rgba(0,0,0,0.35);
    }
    html.dark .prod-card:hover,
    .dark .prod-card:hover {
        box-shadow: 0 12px 28px rgba(0,0,0,0.5);
        border-color: rgba(var(--theme-primary-rgb, 0, 179, 204), 0.5) !important;
    }
    html.dark .prod-card .img-wrap,
    .dark .prod-card .img-wrap {
        background: rgb(var(--theme-surface-lowest, 15 23 42)) !important;
    }

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
    html.dark .cat-chip,
    .dark .cat-chip {
        background: rgb(var(--theme-surface-container, 30 41 59));
        color: #94a3b8;
        box-shadow: 0 1px 4px rgba(0,0,0,0.3);
    }
    html.dark .cat-chip:hover,
    html.dark .cat-chip.active,
    .dark .cat-chip:hover,
    .dark .cat-chip.active {
        border-color: var(--theme-primary, #00b3cc);
        color: var(--theme-primary, #00b3cc);
        background: rgba(0,179,204,0.15);
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
    html.dark .svc-card,
    .dark .svc-card {
        background: rgb(var(--theme-surface-container, 30 41 59));
        color: #cbd5e1;
        box-shadow: 0 1px 6px rgba(0,0,0,0.3);
    }

    /* Section title */
    .section-title {
        font-size: 18px; font-weight: 800; color: #1a202c;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 16px;
    }
    html.dark .section-title,
    .dark .section-title {
        color: #f1f5f9;
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

<div class="pt-3 md:pt-6 pb-16 min-h-screen">
<div class="max-w-[1280px] mx-auto px-2 md:px-6">

    @php
        $isSearching = request()->filled('search') || request()->filled('category') || request()->filled('type') || request()->filled('store');
    @endphp

    @if(!$isSearching)
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
        <div class="flex-1 overflow-x-auto hide-scrollbar pb-1 scroll-smooth">
            <div class="flex items-center gap-3 min-w-max lg:justify-end">
                @forelse($topProducts as $top)
                    @php
                        $topImg = $top->images->where('is_main', true)->first() ?? $top->images->first();
                        $topDiscount = $top->discount_price && $top->discount_price > 0 && $top->discount_price < $top->price;
                        $topPrice = $topDiscount ? $top->discount_price : $top->price;
                    @endphp
                    <a href="{{ route('products.show', $top->slug) }}" 
                       class="group/card w-[170px] bg-white dark:bg-gray-800 rounded-xl p-2.5 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col border border-white/20 text-gray-800 dark:text-gray-100 relative overflow-hidden">
                        
                        {{-- Top Badge / Views --}}
                        <div class="absolute top-1.5 right-1.5 z-10 bg-amber-500 text-white text-[9px] font-extrabold px-1.5 py-0.5 rounded-full flex items-center gap-0.5 shadow-xs">
                            <span class="material-symbols-outlined text-[10px]">trending_up</span>
                            <span>{{ number_format($top->views ?? 0) }}</span>
                        </div>

                        {{-- Image thumbnail --}}
                        <div class="w-full aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 relative mb-2">
                            @if($topImg)
                                <img src="{{ asset('storage/' . $topImg->image_path) }}" 
                                     alt="Produk Unggulan: {{ $top->name }} - {{ $top->category ? $top->category->name : 'Source Code Aplikasi' }}" 
                                     title="{{ $top->name }}"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-300">
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
                        <div class="mt-auto flex items-baseline justify-between flex-wrap gap-x-1 pt-1 border-t border-gray-100 dark:border-gray-700 min-w-0">
                            <span class="text-primary font-bold text-xs shrink-0">
                                Rp{{ number_format($topPrice, 0, ',', '.') }}
                            </span>
                            @if($topDiscount)
                                <span class="text-[9px] text-gray-400 line-through truncate max-w-full">
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

    {{-- ── SEAMLESS SPONSORED STORE CAROUSEL STRIP (TOKO REKOMENDASI TERVERIFIKASI) ── --}}
    @if(isset($sponsoredStores) && $sponsoredStores->count() > 0)
    <div x-data="{
            activeStoreIndex: 0,
            totalStores: {{ $sponsoredStores->count() }},
            autoPlayTimer: null,
            touchStartX: null,
            touchStartY: null,
            init() {
                if (this.totalStores > 1) {
                    this.startAutoPlay();
                }
            },
            startAutoPlay() {
                this.stopAutoPlay();
                this.autoPlayTimer = setInterval(() => {
                    this.nextStore();
                }, 6000);
            },
            stopAutoPlay() {
                if (this.autoPlayTimer) clearInterval(this.autoPlayTimer);
            },
            nextStore() {
                this.activeStoreIndex = (this.activeStoreIndex + 1) % this.totalStores;
                this.restartAutoPlay();
            },
            prevStore() {
                this.activeStoreIndex = (this.activeStoreIndex - 1 + this.totalStores) % this.totalStores;
                this.restartAutoPlay();
            },
            restartAutoPlay() {
                if (this.totalStores > 1) {
                    this.startAutoPlay();
                }
            },
            handleTouchStart(e) {
                if (e.target.closest('.overflow-x-auto')) {
                    this.touchStartX = null;
                    return;
                }
                this.touchStartX = e.touches[0].clientX;
                this.touchStartY = e.touches[0].clientY;
            },
            handleTouchEnd(e) {
                if (this.touchStartX === null) return;
                const diffX = this.touchStartX - e.changedTouches[0].clientX;
                const diffY = this.touchStartY - e.changedTouches[0].clientY;
                if (Math.abs(diffX) > 40 && Math.abs(diffX) > Math.abs(diffY)) {
                    if (diffX > 0) this.nextStore();
                    else this.prevStore();
                }
                this.touchStartX = null;
            }
         }"
         @mouseenter="stopAutoPlay()"
         @mouseleave="totalStores > 1 && startAutoPlay()"
         @touchstart.passive="handleTouchStart($event)"
         @touchend.passive="handleTouchEnd($event)"
         class="mb-6 rounded-xl border border-slate-200/80 dark:border-slate-800/80 p-3 md:py-2.5 md:px-4 bg-white/40 dark:bg-slate-900/40 backdrop-blur-xs transition-all relative overflow-hidden">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            
            <!-- Sliding Track Viewport -->
            <div class="flex-1 min-w-0 overflow-hidden">
                <div class="flex transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] will-change-transform"
                     :style="'transform: translateX(-' + (activeStoreIndex * 100) + '%);'">
                    
                    @foreach($sponsoredStores as $sIndex => $spStore)
                    @php
                        // Ambil produk beriklan aktif dari toko ini
                        $adProductIds = $spStore->ads->where('status', 'active')->pluck('product_id')->filter()->unique();
                        $adProducts = $spStore->ads->where('status', 'active')->map(fn($a) => $a->product)->filter();
                        
                        // Ambil seluruh produk toko (termasuk yang tidak diiklankan & produk etalase showcase)
                        $otherAds = $spStore->ads->where('status', '!=', 'active')->map(fn($a) => $a->product)->filter();
                        $ownProds = $spStore->products ?? collect();
                        $showcaseProds = $spStore->showcaseProducts ?? collect();
                        
                        // Gabungkan: produk beriklan diutamakan di depan, diikuti produk lainnya tanpa duplikasi
                        $storeDisplayProducts = $adProducts
                            ->concat($otherAds)
                            ->concat($ownProds)
                            ->concat($showcaseProds)
                            ->unique('id')
                            ->take(6);
                    @endphp
                    <div class="w-full shrink-0 min-w-full flex flex-col lg:flex-row lg:items-center justify-between gap-3"
                         :class="activeStoreIndex === {{ $sIndex }} ? '' : 'pointer-events-none'">
                        
                        <!-- 1. Info Toko Rekomendasi / Populer (Kiri) -->
                        <div class="flex items-center gap-2.5 shrink-0 max-w-full lg:max-w-[220px] xl:max-w-[250px] pr-20 lg:pr-0">
                            <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 shadow-2xs flex items-center justify-center">
                                @if($spStore->logo)
                                    <img src="{{ asset('storage/' . $spStore->logo) }}" alt="{{ $spStore->name }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($spStore->name) }}&background=0284c7&color=fff" alt="{{ $spStore->name }}" class="w-full h-full object-cover">
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <a href="{{ route('store.show', $spStore->slug) }}" class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white hover:text-[#0284c7] transition-colors truncate block">
                                        {{ $spStore->name }}
                                    </a>
                                    @if($spStore->is_sponsored_ad ?? false)
                                        <span class="px-1.5 py-0.5 rounded-full bg-sky-50 dark:bg-sky-950/60 text-[#0284c7] dark:text-sky-400 text-[9px] font-bold border border-sky-200/80 dark:border-sky-800/60 flex items-center gap-0.5" title="Toko Rekomendasi (Iklan Aktif)">
                                            <span class="material-symbols-outlined text-[11px]">verified</span>
                                            Toko Rekomendasi
                                        </span>
                                    @else
                                        <span class="px-1.5 py-0.5 rounded-full bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 text-[9px] font-bold border border-amber-200/80 dark:border-amber-800/60 flex items-center gap-0.5" title="Toko Populer (Banyak Dilihat)">
                                            <span class="material-symbols-outlined text-[11px]">trending_up</span>
                                            Toko Populer
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                                    {{ $spStore->description ?: 'Mitra resmi dengan koleksi produk digital pilihan.' }}
                                </p>
                            </div>
                        </div>

                        <!-- 2. Produk Toko (Tengah - Termasuk Yang Tidak Diiklankan) -->
                        <div class="flex-1 overflow-x-auto hide-scrollbar py-0.5 min-w-0 scroll-smooth">
                            <div class="flex items-center gap-2">
                                @forelse($storeDisplayProducts as $p)
                                    @php
                                        $pImg = $p->images->where('is_main', true)->first() ?? $p->images->first();
                                        $hasDisc = $p->discount_price && $p->discount_price > 0 && $p->discount_price < $p->price;
                                        $pPrice = $hasDisc ? $p->discount_price : $p->price;
                                        $isAd = $adProductIds->contains($p->id);
                                        $activeAd = $isAd ? $spStore->ads->firstWhere('product_id', $p->id) : null;
                                        $pLink = ($activeAd && $activeAd->status === 'active')
                                            ? route('products.show', ['slug' => $p->slug, 'ad_id' => $activeAd->id])
                                            : route('products.show', $p->slug);
                                    @endphp
                                    <a href="{{ $pLink }}" 
                                       class="group/pmini flex items-center gap-2 p-1.5 pr-2.5 rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white/70 dark:bg-slate-800/60 hover:bg-white dark:hover:bg-slate-800 hover:border-sky-400 dark:hover:border-sky-500 transition-all shadow-2xs hover:shadow-xs shrink-0 w-[155px] sm:w-[170px] xl:w-[185px] min-w-0 relative overflow-hidden">
                                        
                                        <!-- Thumbnail -->
                                        <div class="w-9 h-9 md:w-10 md:h-10 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shrink-0 relative">
                                            @if($pImg)
                                                <img src="{{ asset('storage/' . $pImg->image_path) }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover/pmini:scale-105 transition-transform duration-200">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                                </div>
                                            @endif
                                            @if($isAd)
                                                <span class="absolute top-0.5 right-0.5 w-1.5 h-1.5 rounded-full bg-[#0284c7] ring-1 ring-white dark:ring-slate-900" title="Produk Bersponsor"></span>
                                            @endif
                                        </div>

                                        <!-- Info -->
                                        <div class="min-w-0 flex-1 flex flex-col justify-center">
                                            <h4 class="text-[11px] font-bold text-slate-800 dark:text-slate-100 truncate group-hover/pmini:text-[#0284c7] transition-colors leading-tight mb-0.5" title="{{ $p->name }}">
                                                {{ $p->name }}
                                            </h4>
                                            <div class="flex items-baseline gap-1 flex-wrap min-w-0">
                                                <span class="text-[10px] sm:text-[11px] font-black text-[#0284c7] shrink-0 leading-none">
                                                    Rp{{ number_format($pPrice, 0, ',', '.') }}
                                                </span>
                                                @if($hasDisc)
                                                <span class="text-[8px] sm:text-[8.5px] text-slate-400 line-through truncate max-w-full leading-none">
                                                    Rp{{ number_format($p->price, 0, ',', '.') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-xs text-slate-400 italic py-1">Belum ada produk untuk toko ini.</div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                    @endforeach

                </div>
            </div>

            <!-- Controls (Sejajar nama toko di kanan atas pada mobile, di kanan produk pada desktop) -->
            @if($sponsoredStores->count() > 1)
            <div class="absolute top-2.5 right-2.5 lg:static flex items-center justify-end gap-1.5 shrink-0 z-10 lg:border-l border-slate-200/60 dark:border-slate-800/60 lg:pl-3">
                <button type="button" 
                        @click="prevStore()" 
                        aria-label="Toko Sebelumnya"
                        class="w-6 h-6 md:w-7 md:h-7 rounded-lg border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-800/90 hover:bg-white dark:hover:bg-slate-700 active:scale-90 flex items-center justify-center text-slate-600 dark:text-slate-300 transition-all cursor-pointer shadow-2xs">
                    <span class="material-symbols-outlined text-[14px] md:text-[15px]">chevron_left</span>
                </button>
                <div class="flex items-center gap-0.5 px-1 select-none">
                    <span class="text-[10px] font-mono font-bold text-slate-700 dark:text-slate-200" x-text="activeStoreIndex + 1"></span>
                    <span class="text-[10px] font-mono text-slate-400">/</span>
                    <span class="text-[10px] font-mono font-medium text-slate-500">{{ $sponsoredStores->count() }}</span>
                </div>
                <button type="button" 
                        @click="nextStore()" 
                        aria-label="Toko Berikutnya"
                        class="w-6 h-6 md:w-7 md:h-7 rounded-lg border border-slate-200 dark:border-slate-700 bg-white/90 dark:bg-slate-800/90 hover:bg-white dark:hover:bg-slate-700 active:scale-90 flex items-center justify-center text-slate-600 dark:text-slate-300 transition-all cursor-pointer shadow-2xs">
                    <span class="material-symbols-outlined text-[14px] md:text-[15px]">chevron_right</span>
                </button>
            </div>
            @endif

        </div>

    </div>
    @endif

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

        {{-- ── SEARCH RESULT NOTICE HEADER (TAMPIL HANYA JIKA SEDANG MENCARI/FILTER) ── --}}
        @if($isSearching)
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 bg-white dark:bg-gray-800 p-3.5 md:p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-2xl">manage_search</span>
                </div>
                <div>
                    <h2 class="text-sm md:text-base font-bold text-gray-800 dark:text-white leading-tight flex flex-wrap items-center gap-1.5">
                        <span>Hasil Pencarian:</span>
                        @if(request('search'))
                            <span class="text-primary font-black">"{{ request('search') }}"</span>
                        @endif
                        @if(request('category') && $categories->find(request('category')))
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-700 dark:text-gray-300 font-medium">Kategori: {{ $categories->find(request('category'))->name }}</span>
                        @endif
                        @if(request('type') && $types->find(request('type')))
                            <span class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-gray-700 dark:text-gray-300 font-medium">Tipe: {{ $types->find(request('type'))->name }}</span>
                        @endif
                    </h2>
                    <p class="text-[11px] md:text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Ditemukan <span class="font-bold text-gray-800 dark:text-gray-200">{{ $products->total() ?? $products->count() }}</span> produk digital yang sesuai
                    </p>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold transition-colors">
                <span class="material-symbols-outlined text-sm">close</span>
                <span>Reset Pencarian</span>
            </a>
        </div>
        @endif

        {{-- ── TOKO / AKUN DITEMUKAN ── --}}
        @if(request('search') && isset($matchedStores) && $matchedStores->isNotEmpty())
        <div class="mb-5 bg-gradient-to-r from-sky-50/80 via-white to-sky-50/40 dark:from-slate-800/90 dark:via-slate-800 dark:to-slate-800/60 p-3.5 md:p-4 rounded-xl border border-sky-200/80 dark:border-slate-700 shadow-xs">
            <div class="text-[11px] font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400 mb-2.5 flex items-center justify-between">
                <span class="flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">storefront</span>
                    <span>Toko / Akun Ditemukan ({{ $matchedStores->count() }})</span>
                </span>
                <span class="text-[10px] text-slate-400 font-normal">Hasil pencarian toko</span>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                @foreach($matchedStores as $mStore)
                <div class="flex items-center justify-between gap-3 p-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs hover:border-sky-400 transition-all group">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 flex items-center justify-center">
                            @if($mStore->logo)
                                <img src="{{ asset('storage/' . $mStore->logo) }}" alt="{{ $mStore->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($mStore->name) }}&background=0284c7&color=fff" alt="{{ $mStore->name }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('store.show', $mStore->slug) }}" class="text-xs md:text-sm font-extrabold text-slate-900 dark:text-white group-hover:text-primary transition-colors truncate block">
                                    {{ $mStore->name }}
                                </a>
                                @if($mStore->is_pro)
                                    <span class="px-1 py-0.2 rounded bg-amber-500/10 text-amber-500 text-[8px] font-black border border-amber-500/30 shrink-0">PRO</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                <span class="text-sky-600 dark:text-sky-400 font-medium">/{{ $mStore->slug }}</span>
                                <span>•</span>
                                <span>{{ $mStore->products_count }} Produk</span>
                                @if($mStore->user && $mStore->user->name !== $mStore->name)
                                <span>•</span>
                                <span class="truncate max-w-[80px]" title="Pemilik akun: {{ $mStore->user->name }}">👤 {{ $mStore->user->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('store.show', $mStore->slug) }}" 
                       class="px-3 py-1.5 rounded-lg bg-primary text-white hover:bg-primary/90 text-xs font-bold shrink-0 transition-all shadow-xs flex items-center gap-1">
                        <span>Kunjungi</span>
                        <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── MOBILE & TABLET SUB-HEADER (TABS & FILTER ICON) - VISIBLE ON SCREENS < 1024px ── --}}
        <div class="lg:hidden sticky top-[calc(env(safe-area-inset-top,0px)+54px)] md:top-[86px] z-30 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 -mx-2 md:-mx-6 px-3 md:px-6 py-2 mb-4 shadow-xs">
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

        <div class="flex flex-col lg:flex-row gap-3 md:gap-6 items-start">
            
            {{-- ── DESKTOP SIDEBAR FILTER (LEFT SIDE) ── --}}
            <aside class="hidden lg:block w-72 shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 sticky top-24 shadow-sm">
                    @include('products._filter_content', ['suffix' => 'desktop'])
                </div>
            </aside>

            {{-- ── MOBILE SHOPEE-STYLE FILTER SLIDE-OVER DRAWER ── --}}
            <div x-show="mobileFilterOpen" 
                 x-cloak
                 style="display: none;"
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
            <div class="flex-1 w-full min-w-0 relative">
                {{-- Loading Indicator Overlay --}}
                <div x-show="loading" 
                     x-cloak
                     style="display: none;"
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
            </div>

        </div>
    </section>

</div>
</div>
@endsection
