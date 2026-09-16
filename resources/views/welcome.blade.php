@extends('layouts.public')
@section('title', ($company->company_name ?? 'rhantech') . ' - We Build Digital Experiences')

@section('content')

{{-- Mobile marquee & slider styling --}}
<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
@keyframes marquee-scroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.store-marquee-track {
    display: flex;
    animation: marquee-scroll 28s linear infinite;
    width: max-content;
}
.store-marquee-track:hover { animation-play-state: paused; }
.store-marquee-wrap {
    overflow: hidden;
    mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 6%, black 94%, transparent 100%);
}
.store-slide-card {
    display: flex; flex-direction: row; align-items: flex-start; gap: 10px;
    background: white;
    border: 1.5px solid #e2e8f0;
    border-radius: 14px; padding: 10px 12px;
    min-width: 270px; max-width: 300px;
    text-decoration: none;
    transition: all 0.22s ease;
    flex-shrink: 0;
}
.dark .store-slide-card { background: #1e293b; border-color: #334155; }
.store-slide-card:hover { border-color: #00b3cc; }
.store-slide-logo { width: 42px; height: 42px; border-radius: 10px; object-fit: cover; flex-shrink: 0; border: 1.5px solid #e2e8f0; }
.dark .store-slide-logo { border-color: #334155; }
.store-slide-logo-fallback {
    width: 42px; height: 42px; border-radius: 10px;
    background: #0ea5e9;
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 16px; flex-shrink: 0;
}
@keyframes hero-float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-5px);
    }
}
.animate-hero-float {
    animation: hero-float 4.5s ease-in-out infinite;
}
</style>

{{-- =========================================================================
     DESKTOP LAYOUT (hidden md:block)
     Preserves full support for admin settings (Hero Beranda & Toko), corporate
     sections (Popular Products, Services, Projects) and original desktop layout.
     ========================================================================= --}}
<div class="hidden md:block">
    <!-- Hero Section -->
    <section class="pb-12 md:pb-2xl px-4 sm:px-lg md:px-xl max-w-container-max mx-auto min-h-[70vh] md:min-h-[85vh] flex flex-col justify-center relative" id="home">
        <!-- Abstract Background Element -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-secondary-container/20 rounded-full blur-[100px] -z-10"></div>
        <div class="absolute bottom-20 left-10 w-[300px] h-[300px] bg-primary/5 rounded-full blur-[80px] -z-10"></div>
        
        @php
            $heroMode = $company->hero_mode ?? 'custom';
            $heroBadge = $company->hero_badge ?? 'Marketplace Produk Digital';
            $heroTitle = $company->hero_title ?? 'Katalog Developer & Aplikasi Siap Pakai';
            $heroSubtitle = $company->hero_subtitle ?? 'Temukan source code siap deploy, template aplikasi, dan sistem digital berkualitas langsung dari developer terverifikasi untuk mempercepat proyek Anda.';
            $heroBtnPrimaryText = $company->hero_btn_primary_text ?? 'Jelajahi Produk';
            $heroBtnPrimaryUrl = $company->hero_btn_primary_url ?? url('/products');
            $heroBtnSecondaryText = $company->hero_btn_secondary_text ?? "Hubungi Kami";
            $heroBtnSecondaryUrl = $company->hero_btn_secondary_url ?? url('/contact');
            $heroStatsVal = $company->hero_stats_val ?? '99%';
            $heroStatsLabel = $company->hero_stats_label ?? 'Kepuasan Pengguna';
            $heroStatsShow = (bool) ($company->hero_stats_show ?? true);
            $heroImageUrl = !empty($company->hero_image) 
                ? asset('storage/' . $company->hero_image) 
                : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80';
        @endphp

        @if($heroMode === 'top_stores' && isset($topStores) && $topStores->count() > 0)
            {{-- MODE 1: FULL HERO SHOWCASE 10 TOKO TERFAVORIT / TERLARIS --}}
            <div class="z-10 pt-2 md:pt-4" x-data="{
                activeStore: 0,
                storesCount: {{ $topStores->count() }},
                timer: null,
                touchStartX: null,
                touchStartY: null,
                init() {
                    if (this.storesCount > 1) {
                        this.startAutoPlay();
                    }
                },
                startAutoPlay() {
                    this.stopAutoPlay();
                    this.timer = setInterval(() => {
                        this.next(false);
                    }, 5000);
                },
                stopAutoPlay() {
                    if (this.timer) {
                        clearInterval(this.timer);
                        this.timer = null;
                    }
                },
                next(restart = true) {
                    this.activeStore = (this.activeStore + 1) % this.storesCount;
                    if (restart && this.storesCount > 1) this.startAutoPlay();
                },
                prev(restart = true) {
                    this.activeStore = (this.activeStore - 1 + this.storesCount) % this.storesCount;
                    if (restart && this.storesCount > 1) this.startAutoPlay();
                },
                goTo(index) {
                    this.activeStore = index;
                    if (this.storesCount > 1) this.startAutoPlay();
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
                        if (diffX > 0) this.next();
                        else this.prev();
                    }
                    this.touchStartX = null;
                }
            }"
            @mouseenter="stopAutoPlay()"
            @mouseleave="storesCount > 1 && startAutoPlay()">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6 md:mb-8">
                    <div>
                        <span class="inline-flex items-center gap-1.5 py-1 px-3.5 rounded-full bg-primary/10 text-primary font-bold text-xs mb-2.5 md:mb-3 border border-primary/20">
                            <span class="material-symbols-outlined text-[15px]">verified</span>
                            {{ $heroBadge ?: 'Marketplace Produk Digital' }}
                        </span>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-on-background dark:text-white text-balance leading-[1.15] tracking-tight">
                            @if(!empty($heroTitle) && $heroTitle !== 'We Build Digital Experiences' && $heroTitle !== 'Brand & Developer Aplikasi Digital Terbaik')
                                @if(Str::contains($heroTitle, '&'))
                                    @php
                                        $parts = explode('&', $heroTitle, 2);
                                    @endphp
                                    {{ trim($parts[0]) }} &amp; <span class="text-primary font-extrabold">{{ trim($parts[1]) }}</span>
                                @else
                                    {{ $heroTitle }}
                                @endif
                            @else
                                Developer &amp; <span class="text-primary font-extrabold">Aplikasi Siap Pakai</span>
                            @endif
                        </h1>
                        <p class="text-xs sm:text-sm md:text-body-lg text-on-surface-variant max-w-2xl mt-2 text-balance leading-relaxed">
                            {{ (!empty($heroSubtitle) && !Str::contains($heroSubtitle, 'engineering excellence') && !Str::contains($heroSubtitle, 'keunggulan dalam bidang engineering')) ? $heroSubtitle : 'Temukan source code siap deploy, template aplikasi, dan sistem digital berkualitas langsung dari developer terverifikasi untuk mempercepat proyek Anda.' }}
                        </p>
                    </div>
                    
                    <!-- Controls Nav Slider -->
                    <div class="flex items-center justify-between md:justify-end gap-3 shrink-0 pt-2 md:pt-0">
                        <button @click="prev()" class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-surface border border-outline-variant hover:bg-surface-container flex items-center justify-center text-on-surface transition-colors cursor-pointer" aria-label="Sebelumnya">
                            <span class="material-symbols-outlined text-[18px] md:text-[20px] leading-none">arrow_back</span>
                        </button>
                        <div class="text-xs font-bold text-on-surface-variant">
                            <span x-text="activeStore + 1" class="text-primary font-black text-sm"></span> / {{ $topStores->count() }}
                        </div>
                        <button @click="next()" class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-surface border border-outline-variant hover:bg-surface-container flex items-center justify-center text-on-surface transition-colors cursor-pointer" aria-label="Berikutnya">
                            <span class="material-symbols-outlined text-[18px] md:text-[20px] leading-none">arrow_forward</span>
                        </button>
                    </div>
                </div>

                <!-- Carousel Display Cards (Smooth Sliding Track Viewport) -->
                <div class="relative overflow-hidden rounded-2xl md:rounded-3xl bg-surface-container-low border border-outline-variant"
                     @touchstart.passive="handleTouchStart($event)"
                     @touchend.passive="handleTouchEnd($event)">
                    <div class="flex items-stretch transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] will-change-transform"
                         :style="'transform: translateX(-' + (activeStore * 100) + '%);'">
                        @foreach($topStores as $index => $store)
                            <div class="w-full shrink-0 p-4 sm:p-6 md:p-8 grid grid-cols-1 md:grid-cols-12 gap-5 md:gap-8 items-center box-border transition-opacity duration-500"
                                 :class="activeStore === {{ $index }} ? 'opacity-100' : 'opacity-25 pointer-events-none'">
                            
                            <!-- Left Info -->
                            <div class="md:col-span-7 flex flex-col items-start">
                                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-3 md:mb-4">
                                    <span class="px-2.5 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-lg text-[11px] md:text-xs font-black border border-amber-500/20 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px]">military_tech</span>
                                        #{{ $index + 1 }}
                                    </span>
                                    <span class="text-[11px] md:text-xs font-medium text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-emerald-500">shopping_bag</span>
                                        {{ $store->sales_count ?? 0 }} Penjualan
                                    </span>
                                    <span class="text-[11px] md:text-xs font-medium text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-sky-500">inventory_2</span>
                                        {{ $store->products_count ?? 0 }} Produk
                                    </span>
                                </div>

                                <div class="flex items-center gap-3.5 md:gap-4 mb-3 md:mb-4">
                                    @if($store->logo)
                                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-13 h-13 md:w-16 md:h-16 rounded-xl md:rounded-2xl object-cover border-2 border-primary/30">
                                    @else
                                        <div class="w-13 h-13 md:w-16 md:h-16 rounded-xl md:rounded-2xl bg-primary text-white flex items-center justify-center font-black text-xl md:text-2xl">
                                            {{ strtoupper(substr($store->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <h2 class="text-xl sm:text-2xl md:text-3xl font-black text-on-surface hover:text-primary transition-colors truncate">
                                            <a href="{{ route('store.show', $store->slug) }}">{{ $store->name }}</a>
                                        </h2>
                                        <p class="text-[11px] md:text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                            <span class="material-symbols-outlined text-[13px] md:text-[14px] text-primary">verified</span> Official Partner Store
                                        </p>
                                    </div>
                                </div>

                                <p class="text-xs md:text-sm text-on-surface-variant mb-4 md:mb-6 line-clamp-2 md:line-clamp-3 leading-relaxed">
                                    {{ $store->description ?: 'Toko resmi vendor penyedia template aplikasi, source code, dan sistem digital berkualitas tinggi dengan garansi dan dukungan penuh.' }}
                                </p>

                                <!-- Showcase Produk Unggulan Toko Ini -->
                                @if($store->products && $store->products->count() > 0)
                                <div class="w-full mb-4 md:mb-6">
                                    <div class="text-[10px] md:text-[11px] font-bold uppercase tracking-wider text-on-surface-variant mb-1.5 md:mb-2">Katalog Populer Toko Ini:</div>
                                    <div class="flex flex-wrap gap-1.5 md:gap-2">
                                        @foreach($store->products->take(3) as $sp)
                                            <a href="{{ route('products.show', $sp->slug) }}" class="px-2.5 py-1 md:px-3 md:py-1.5 rounded-lg bg-surface border border-outline-variant hover:border-primary text-[11px] md:text-xs font-semibold text-on-surface flex items-center gap-1.5 transition-all">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                                <span class="truncate max-w-[130px] sm:max-w-[180px]">{{ $sp->name }}</span>
                                                <span class="text-primary font-bold shrink-0">Rp{{ number_format($sp->price, 0, ',', '.') }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="flex items-center gap-2.5 md:gap-3 w-full sm:w-auto">
                                    <a href="{{ route('store.show', $store->slug) }}" class="flex-1 sm:flex-none justify-center px-4 md:px-6 py-2.5 md:py-3 bg-sky-500 hover:bg-sky-600 text-white rounded-xl font-label-md text-xs md:text-sm transition-colors flex items-center gap-1.5 md:gap-2 font-bold">
                                        <span class="material-symbols-outlined text-[16px] md:text-[18px]">storefront</span>
                                        Kunjungi Toko
                                    </a>
                                    <a href="{{ url('/products') }}" class="flex-1 sm:flex-none justify-center px-3.5 md:px-5 py-2.5 md:py-3 bg-surface border border-outline-variant text-on-surface rounded-xl font-label-md text-xs md:text-sm hover:bg-surface-container transition-colors font-semibold text-center">
                                        Lihat Semua Vendor
                                    </a>
                                </div>
                            </div>

                            <!-- Right Showcase: Produk Terlaris Milik Toko Ini -->
                            <div class="md:col-span-5 relative w-full">
                                @php
                                    $storeTopProducts = $store->products ? $store->products->take(2)->values() : collect();
                                @endphp

                                @if($storeTopProducts->isNotEmpty())
                                    <div class="relative w-full h-[220px] sm:h-[320px] md:h-[360px] rounded-2xl overflow-hidden border border-outline-variant/70 bg-slate-950"
                                         x-data="{ 
                                             prodIdx: 0, 
                                             totalProds: {{ $storeTopProducts->count() }},
                                             timer: null,
                                             init() {
                                                 if (this.totalProds > 1) {
                                                     this.timer = setInterval(() => {
                                                         this.prodIdx = (this.prodIdx + 1) % this.totalProds;
                                                     }, 3500);
                                                 }
                                             }
                                         }">
                                        @foreach($storeTopProducts as $pIdx => $tp)
                                            @php
                                                $tpImg = $tp->images ? ($tp->images->where('is_main', true)->first() ?? $tp->images->first()) : null;
                                                $tpImgUrl = $tpImg ? asset('storage/' . $tpImg->image_path) : null;
                                            @endphp
                                            <a href="{{ route('products.show', $tp->slug) }}"
                                               x-show="prodIdx === {{ $pIdx }}"
                                               x-cloak
                                               x-transition:enter="transition opacity duration-700 ease-in-out"
                                               x-transition:enter-start="opacity-0"
                                               x-transition:enter-end="opacity-100"
                                               x-transition:leave="transition opacity duration-700 ease-in-out absolute inset-0"
                                               x-transition:leave-start="opacity-100"
                                               x-transition:leave-end="opacity-0"
                                               class="absolute inset-0 w-full h-full block group overflow-hidden">
                                                @if($tpImgUrl)
                                                    <img src="{{ $tpImgUrl }}" 
                                                         alt="{{ $tp->name }}" 
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                                         onerror="this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80'">
                                                @else
                                                    <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                                                        <span class="material-symbols-outlined text-5xl text-white/30">inventory_2</span>
                                                    </div>
                                                @endif

                                                <!-- Badge Produk Terlaris / Unggulan -->
                                                <div class="absolute top-2.5 sm:top-3.5 left-2.5 sm:left-3.5 z-10 flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full bg-slate-950/80 backdrop-blur-md border border-white/20 text-white text-[10px] sm:text-[11px] font-bold">
                                                    <span class="material-symbols-outlined text-[13px] sm:text-[14px] text-amber-400">local_fire_department</span>
                                                    <span>{{ $pIdx === 0 ? 'Produk Paling Laris' : 'Rekomendasi' }}</span>
                                                </div>

                                                @if($storeTopProducts->count() > 1)
                                                <!-- Indikator Slide Produk -->
                                                <div class="absolute top-2.5 sm:top-3.5 right-2.5 sm:right-3.5 z-10 flex gap-1 bg-black/40 backdrop-blur-xs px-2 py-1 rounded-full">
                                                    @foreach($storeTopProducts as $dIdx => $dp)
                                                        <span class="w-1.5 h-1.5 rounded-full transition-all" :class="prodIdx === {{ $dIdx }} ? 'bg-white w-3' : 'bg-white/40'"></span>
                                                    @endforeach
                                                </div>
                                                @endif

                                                <!-- Bottom Overlay & Product Details -->
                                                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent pointer-events-none"></div>
                                                <div class="absolute bottom-0 inset-x-0 p-3 sm:p-4 text-white flex items-end justify-between gap-2 sm:gap-3 z-10">
                                                    <div class="min-w-0">
                                                        <div class="flex items-center gap-1 sm:gap-1.5 text-amber-400 text-[11px] sm:text-xs font-bold mb-0.5 sm:mb-1">
                                                            <span class="material-symbols-outlined text-[13px] sm:text-[14px]">star</span>
                                                            <span>{{ $tp->effective_rating }}</span>
                                                            <span class="text-white/60 text-[10px] sm:text-[11px]">({{ $tp->effective_reviews_count }})</span>
                                                        </div>
                                                        <h4 class="text-xs sm:text-sm md:text-base font-black truncate drop-shadow group-hover:text-primary transition-colors">
                                                            {{ $tp->name }}
                                                        </h4>
                                                        <p class="text-[10px] sm:text-xs text-white/70 truncate">
                                                            {{ $tp->category->name ?? 'Aplikasi Digital' }}
                                                        </p>
                                                    </div>
                                                    <div class="shrink-0 text-right">
                                                        <div class="text-emerald-400 font-extrabold text-xs sm:text-sm md:text-base">
                                                            Rp{{ number_format($tp->discount_price && $tp->discount_price < $tp->price ? $tp->discount_price : $tp->price, 0, ',', '.') }}
                                                        </div>
                                                        <span class="inline-flex items-center gap-0.5 sm:gap-1 text-[10px] sm:text-[11px] font-semibold text-white/80 group-hover:text-primary mt-0.5">
                                                            Lihat Detail <span class="material-symbols-outlined text-[11px] sm:text-[12px]">arrow_forward</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="relative w-full h-[220px] sm:h-[320px] md:h-[360px] rounded-2xl overflow-hidden border border-outline-variant/60 bg-slate-900 flex items-center justify-center p-6 text-center">
                                        <div class="flex flex-col items-center">
                                            @if($store->logo)
                                                <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-white/30 mb-3">
                                            @else
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 text-white flex items-center justify-center font-black text-2xl sm:text-3xl mb-3 border border-white/30">
                                                    {{ strtoupper(substr($store->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <h3 class="text-white font-black text-base sm:text-lg">{{ $store->name }}</h3>
                                            <span class="text-xs text-slate-300 mt-1">Pusat Aplikasi &amp; Source Code Terpercaya</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>

                <!-- Dots Indicator -->
                <div class="flex items-center justify-center gap-1.5 sm:gap-2 mt-4 sm:mt-6">
                    @foreach($topStores as $index => $store)
                        <button @click="goTo({{ $index }})" 
                                :class="activeStore === {{ $index }} ? 'w-6 sm:w-8 bg-primary' : 'w-2 sm:w-2.5 bg-outline-variant hover:bg-on-surface-variant'" 
                                class="h-2 sm:h-2.5 rounded-full transition-all cursor-pointer"
                                title="{{ $store->name }}"></button>
                    @endforeach
                </div>
            </div>

        @else
            {{-- MODE 2 & 3: CUSTOM BANNER / DUAL SHOWCASE --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-lg items-center pt-2 md:pt-4">
                <div class="md:col-span-7 flex flex-col items-start z-10">
                    @if($heroBadge)
                    <span class="inline-block py-1 px-3 rounded-full bg-surface-container text-on-surface font-label-md text-xs sm:text-label-md mb-4 md:mb-6 border border-outline-variant/30">
                        {{ $heroBadge }}
                    </span>
                    @endif

                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[54px] font-extrabold text-on-background dark:text-white mb-4 md:mb-6 text-balance leading-[1.15] tracking-tight">
                        @if(Str::contains($heroTitle, '&'))
                            @php
                                $parts = explode('&', $heroTitle, 2);
                            @endphp
                            {{ trim($parts[0]) }} &amp; <span class="text-primary font-extrabold">{{ trim($parts[1]) }}</span>
                        @elseif(Str::contains($heroTitle, 'Digital'))
                            {!! Str::replace('Digital', '<span class="text-primary font-extrabold">Digital</span>', e($heroTitle)) !!}
                        @else
                            {{ $heroTitle }}
                        @endif
                    </h1>

                    <p class="text-sm md:text-body-lg text-on-surface-variant mb-6 md:mb-xl max-w-2xl text-balance leading-relaxed">
                        {{ (!empty($heroSubtitle) && !Str::contains($heroSubtitle, 'engineering excellence') && !Str::contains($heroSubtitle, 'keunggulan dalam bidang engineering')) ? $heroSubtitle : 'Temukan source code siap deploy, template aplikasi, dan sistem digital berkualitas langsung dari developer terverifikasi untuk mempercepat proyek Anda.' }}
                    </p>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 md:gap-4 w-full sm:w-auto">
                        @if($heroBtnPrimaryText)
                        <a class="w-full sm:w-auto text-center px-6 md:px-8 py-3.5 md:py-4 bg-sky-500 hover:bg-sky-600 text-white rounded-xl md:rounded-lg font-label-md text-xs sm:text-label-md transition-colors border-0 font-bold" href="{{ $heroBtnPrimaryUrl }}" wire:navigate>
                            {{ $heroBtnPrimaryText }}
                        </a>
                        @endif

                        @if($heroBtnSecondaryText)
                        <a class="w-full sm:w-auto text-center px-6 md:px-8 py-3.5 md:py-4 bg-transparent text-on-background dark:text-white border border-outline-variant rounded-xl md:rounded-lg font-label-md text-xs sm:text-label-md hover:bg-surface-container-low transition-all font-semibold" href="{{ $heroBtnSecondaryUrl }}" wire:navigate>
                            {{ $heroBtnSecondaryText }}
                        </a>
                        @endif
                    </div>
                </div>
                
                <div class="md:col-span-5 relative mt-6 md:mt-0 z-10 w-full">
                    @if($heroMode === 'both' && isset($topStores) && $topStores->count() > 0)
                        {{-- DUAL MODE: MINI ROTATING TOP STORES SLIDER ON THE RIGHT --}}
                        <div class="relative rounded-2xl overflow-hidden border border-outline-variant bg-surface p-4 sm:p-6"
                             x-data="{
                                currentStore: 0,
                                total: {{ $topStores->count() }},
                                init() {
                                    setInterval(() => {
                                        this.currentStore = (this.currentStore + 1) % this.total;
                                    }, 4000);
                                }
                             }">
                            <div class="flex items-center justify-between pb-3 mb-4 border-b border-outline-variant">
                                <span class="text-xs font-black uppercase tracking-wider text-emerald-500 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">stars</span> Top 10 Toko Terfavorit
                                </span>
                                <span class="text-[11px] font-bold text-on-surface-variant">Bergantian Otomatis</span>
                            </div>

                            @foreach($topStores as $sIndex => $tStore)
                                <div x-show="currentStore === {{ $sIndex }}" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        @if($tStore->logo)
                                            <img src="{{ asset('storage/' . $tStore->logo) }}" class="w-12 h-12 md:w-14 md:h-14 rounded-xl object-cover border border-outline-variant">
                                        @else
                                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-primary text-white flex items-center justify-center font-black text-lg md:text-xl">
                                                {{ strtoupper(substr($tStore->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-amber-500/10 text-amber-600 border border-amber-500/20">Top #{{ $sIndex + 1 }}</span>
                                            <h3 class="font-black text-base md:text-lg text-on-surface mt-0.5">{{ $tStore->name }}</h3>
                                            <p class="text-[11px] md:text-xs text-on-surface-variant">{{ $tStore->sales_count ?? 0 }} Penjualan • {{ $tStore->products_count ?? 0 }} Produk</p>
                                        </div>
                                    </div>

                                    {{-- Product Thumbnails Showcase (Click to enter store) --}}
                                    <a href="{{ route('store.show', $tStore->slug) }}" 
                                       class="group/showcase block rounded-2xl overflow-hidden border border-outline-variant/80 bg-slate-50 dark:bg-slate-900/60 p-3 hover:border-primary hover:shadow-md transition-all duration-300 relative"
                                       title="Kunjungi {{ $tStore->name }}">
                                        
                                        @if($tStore->products && $tStore->products->count() > 0)
                                            {{-- Section label / preview header --}}
                                            <div class="flex items-center justify-between mb-2.5 px-0.5">
                                                <span class="text-[11px] font-bold text-on-surface-variant flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px] text-primary">shopping_bag</span>
                                                    <span>Produk Toko</span>
                                                </span>
                                                <span class="text-[10px] font-bold text-primary group-hover/showcase:translate-x-0.5 transition-transform flex items-center gap-0.5">
                                                    <span>Lihat Toko</span>
                                                    <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                                                </span>
                                            </div>

                                            @if($tStore->products->count() === 1)
                                                {{-- 1 Product: Hero-style horizontal card --}}
                                                @php 
                                                    $p = $tStore->products->first(); 
                                                    $pImg = $p->images->where('is_main', true)->first() ?? $p->images->first();
                                                    $effectivePrice = $p->discount_price ?: $p->price;
                                                @endphp
                                                <div class="flex items-center gap-3 bg-white dark:bg-slate-800/80 rounded-xl p-2.5 border border-outline-variant/60">
                                                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 shrink-0 border border-outline-variant/40">
                                                        @if($pImg)
                                                            <img src="{{ asset('storage/' . $pImg->image_path) }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover/showcase:scale-105 transition duration-300">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                                <span class="material-symbols-outlined text-2xl">inventory_2</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        @if($p->category)
                                                            <span class="text-[9px] font-bold uppercase px-1.5 py-0.5 rounded bg-primary/10 text-primary">{{ $p->category->name }}</span>
                                                        @endif
                                                        <h4 class="font-bold text-xs sm:text-sm text-on-surface mt-1 line-clamp-2 leading-snug group-hover/showcase:text-primary transition-colors">
                                                            {{ $p->name }}
                                                        </h4>
                                                        <div class="mt-1.5 flex items-baseline gap-1.5">
                                                            <span class="font-black text-xs sm:text-sm text-primary">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</span>
                                                            @if($p->discount_price && $p->discount_price < $p->price)
                                                                <span class="text-[10px] text-on-surface-variant line-through">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                            @elseif($tStore->products->count() === 2)
                                                {{-- 2 Products: 2-column grid --}}
                                                <div class="grid grid-cols-2 gap-2.5">
                                                    @foreach($tStore->products->take(2) as $p)
                                                        @php 
                                                            $pImg = $p->images->where('is_main', true)->first() ?? $p->images->first();
                                                            $effectivePrice = $p->discount_price ?: $p->price;
                                                        @endphp
                                                        <div class="bg-white dark:bg-slate-800/80 rounded-xl p-2 border border-outline-variant/60 flex flex-col justify-between">
                                                            <div class="w-full h-20 sm:h-24 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 mb-1.5 border border-outline-variant/40">
                                                                @if($pImg)
                                                                    <img src="{{ asset('storage/' . $pImg->image_path) }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover/showcase:scale-105 transition duration-300">
                                                                @else
                                                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                                        <span class="material-symbols-outlined text-xl">inventory_2</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h4 class="font-bold text-[11px] text-on-surface truncate group-hover/showcase:text-primary transition-colors">{{ $p->name }}</h4>
                                                                <span class="font-black text-[11px] text-primary block mt-0.5">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                            @else
                                                {{-- 3 Products: 3-column grid --}}
                                                <div class="grid grid-cols-3 gap-2">
                                                    @foreach($tStore->products->take(3) as $p)
                                                        @php 
                                                            $pImg = $p->images->where('is_main', true)->first() ?? $p->images->first();
                                                            $effectivePrice = $p->discount_price ?: $p->price;
                                                        @endphp
                                                        <div class="bg-white dark:bg-slate-800/80 rounded-xl p-1.5 border border-outline-variant/60 flex flex-col">
                                                            <div class="w-full h-16 sm:h-20 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-700 mb-1 border border-outline-variant/40">
                                                                @if($pImg)
                                                                    <img src="{{ asset('storage/' . $pImg->image_path) }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover/showcase:scale-105 transition duration-300">
                                                                @else
                                                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                                        <span class="material-symbols-outlined text-lg">inventory_2</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <h4 class="font-bold text-[10px] text-on-surface truncate group-hover/showcase:text-primary transition-colors leading-tight">{{ $p->name }}</h4>
                                                            <span class="font-black text-[10px] text-primary mt-0.5">Rp {{ number_format($effectivePrice, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                        @else
                                            {{-- Store has 0 products yet (e.g. Bio Link or new store) --}}
                                            <div class="h-36 sm:h-40 rounded-xl bg-gradient-to-br from-teal-500/10 via-sky-500/5 to-purple-500/10 border border-outline-variant/60 p-4 flex flex-col justify-center items-center text-center">
                                                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-2">
                                                    <span class="material-symbols-outlined text-2xl">
                                                        {{ $tStore->store_mode === 'profile' ? 'link' : ($tStore->store_mode === 'hybrid' ? 'auto_awesome' : 'storefront') }}
                                                    </span>
                                                </div>
                                                <p class="font-bold text-xs text-on-surface line-clamp-1">
                                                    {{ $tStore->description ?: 'Kunjungi profil toko untuk melihat detail dan kontak.' }}
                                                </p>
                                                <span class="mt-2.5 inline-flex items-center gap-1 text-[11px] font-bold text-primary bg-white dark:bg-slate-800 px-3 py-1 rounded-full shadow-xs border border-outline-variant/60 group-hover/showcase:bg-primary group-hover/showcase:text-white transition-all">
                                                    <span>Jelajahi Profil Toko</span>
                                                    <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                                                </span>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex items-center justify-between pt-2">
                                        <div class="flex gap-1">
                                            @foreach($topStores as $dotIdx => $d)
                                                <span class="w-2 h-2 rounded-full" :class="currentStore === {{ $dotIdx }} ? 'bg-primary' : 'bg-outline-variant'"></span>
                                            @endforeach
                                        </div>
                                        <a href="{{ route('store.show', $tStore->slug) }}" class="px-3.5 py-1.5 md:px-4 md:py-2 bg-primary text-white rounded-lg text-xs font-bold hover:brightness-110 transition-all flex items-center gap-1.5">
                                            Kunjungi Toko <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- SINGLE IMAGE MODE --}}
                        <div class="relative rounded-2xl overflow-hidden border border-outline-variant/50 group">
                            <div class="absolute inset-0 bg-primary/10 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                            <img fetchpriority="high" alt="{{ $heroTitle }}" class="w-full h-[260px] sm:h-[400px] md:h-[550px] lg:h-[600px] object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $heroImageUrl }}"/>
                        </div>
                    @endif
                    
                    <!-- Compact Floating Trust Pill (Configurable in Admin) -->
                    @if($heroStatsShow && ($heroStatsVal || $heroStatsLabel))
                    <div class="hidden sm:inline-flex absolute -bottom-3 left-3 sm:-bottom-3.5 sm:left-4 z-30 animate-hero-float"
                         x-data="{
                            currentStat: 0,
                            totalStats: 3,
                            timer: null,
                            init() {
                                this.timer = setInterval(() => {
                                    this.currentStat = (this.currentStat + 1) % this.totalStats;
                                }, 3500);
                            }
                         }"
                         @mouseenter="clearInterval(timer)"
                         @mouseleave="timer = setInterval(() => { currentStat = (currentStat + 1) % totalStats; }, 3500)">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/95 dark:bg-[#151c2c]/95 backdrop-blur-md border border-slate-200/90 dark:border-slate-700/80 shadow-md shadow-slate-900/5 dark:shadow-black/30 ring-1 ring-black/[0.04] dark:ring-white/[0.06] select-none">
                            
                            {{-- Slide 0: Main Stat (e.g. 1356 Jenis • Produk Siap Pakai) --}}
                            <div x-show="currentStat === 0" 
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-200 transform absolute"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-teal-500/10 text-[#00838f] dark:text-teal-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[13px]">rocket_launch</span>
                                </span>
                                <span class="font-black text-xs text-slate-800 dark:text-white leading-none whitespace-nowrap">{{ $heroStatsVal }}</span>
                                <span class="text-slate-300 dark:text-slate-600 text-[10px]">•</span>
                                <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap truncate max-w-[140px] leading-none">{{ $heroStatsLabel }}</span>
                            </div>

                            {{-- Slide 1: Fast Automated Transaction --}}
                            <div x-show="currentStat === 1" 
                                 x-cloak
                                 style="display: none;"
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-200 transform absolute"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[13px]">bolt</span>
                                </span>
                                <span class="font-black text-xs text-slate-800 dark:text-white leading-none whitespace-nowrap">Unduh Instan</span>
                                <span class="text-slate-300 dark:text-slate-600 text-[10px]">•</span>
                                <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap truncate max-w-[140px] leading-none">QRIS &amp; Otomatis</span>
                            </div>

                            {{-- Slide 2: Verified Developer & Guarantee --}}
                            <div x-show="currentStat === 2" 
                                 x-cloak
                                 style="display: none;"
                                 x-transition:enter="transition ease-out duration-300 transform"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-200 transform absolute"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[13px]">verified_user</span>
                                </span>
                                <span class="font-black text-xs text-slate-800 dark:text-white leading-none whitespace-nowrap">100% Bergaransi</span>
                                <span class="text-slate-300 dark:text-slate-600 text-[10px]">•</span>
                                <span class="text-[11px] font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap truncate max-w-[140px] leading-none">Terverifikasi</span>
                            </div>

                            {{-- Small live pulse green dot --}}
                            <span class="relative flex h-1.5 w-1.5 shrink-0 ml-0.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Aplikasi Populer Paling Sering Dilihat (Desktop 4-Column Grid) --}}
        @if(isset($popularProducts) && $popularProducts->count() > 0)
        <div class="mt-10 md:mt-14 pt-6 md:pt-8 border-t border-outline-variant/30 z-10 w-full">
            <div class="flex items-center justify-between gap-2 mb-4 md:mb-5">
                <div class="flex items-center gap-2 md:gap-2.5 min-w-0">
                    <span class="p-1.5 md:p-2 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px] md:text-[20px]">trending_up</span>
                    </span>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base md:text-lg font-black text-on-background dark:text-white leading-tight truncate">
                            Aplikasi Populer Paling Sering Dilihat
                        </h2>
                        <p class="text-[11px] md:text-xs text-on-surface-variant mt-0.5 hidden sm:block">Produk &amp; sistem digital rekomendasi yang paling diminati calon pembeli</p>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 text-[11px] md:text-xs font-bold text-primary hover:underline group shrink-0">
                    <span>Lihat Semua Katalog</span>
                    <span class="material-symbols-outlined text-[13px] md:text-[14px] transition-transform group-hover:translate-x-0.5">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
                @foreach($popularProducts as $prod)
                @php
                    $mainImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first();
                    $hasDiscount = $prod->discount_price && $prod->discount_price > 0 && $prod->discount_price < $prod->price;
                    $effectivePrice = $hasDiscount ? $prod->discount_price : $prod->price;
                    $soldCount = $prod->sales_count ?: ($prod->orders_count ?? 0);
                    $displaySold = (int)$soldCount;

                    // Rating & Reviews count
                    $ratingDisplay = $prod->effective_rating;
                    $reviewsDisplayCount = $prod->effective_reviews_count;

                    // Short description
                    if (!empty($prod->short_description)) {
                        $descText = trim($prod->short_description);
                    } else {
                        $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags($prod->description ?? '')));
                        $descText = Str::limit($cleanDesc, 110, '...');
                    }
                @endphp
                <a href="{{ route('products.show', $prod->slug) }}" class="group bg-surface rounded-xl sm:rounded-2xl border border-outline-variant hover:border-primary/50 overflow-hidden transition-colors duration-200 flex flex-col">
                    <div class="relative aspect-square w-full bg-surface-container overflow-hidden">
                        @if($mainImg)
                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80'">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-outline-variant bg-surface-container-high">
                                <span class="material-symbols-outlined text-2xl sm:text-3xl">inventory_2</span>
                            </div>
                        @endif

                        @if($hasDiscount)
                            <span class="absolute top-1.5 sm:top-2.5 left-1.5 sm:left-2.5 bg-red-500 text-white text-[9px] sm:text-[10px] font-black px-1.5 sm:px-2 py-0.5 rounded">
                                -{{ round((($prod->price - $prod->discount_price) / $prod->price) * 100) }}%
                            </span>
                        @endif

                        <div class="absolute bottom-1.5 sm:bottom-2 right-1.5 sm:right-2 bg-black/60 backdrop-blur-xs text-white text-[9px] sm:text-[10px] font-medium px-1.5 sm:px-2 py-0.5 rounded flex items-center gap-0.5 sm:gap-1">
                            <span class="material-symbols-outlined text-[10px] sm:text-[12px]">visibility</span>
                            <span>{{ number_format($prod->views ?? 0) }}</span>
                        </div>
                    </div>

                    <div class="p-2.5 sm:p-4 flex flex-col flex-1">
                        <div class="flex items-center justify-between gap-1 mb-1 sm:mb-1.5">
                            <span class="text-[9px] sm:text-[10px] font-black text-primary uppercase tracking-wider truncate">
                                {{ $prod->category->name ?? ($prod->type->name ?? 'Aplikasi') }}
                            </span>
                            @if($ratingDisplay > 0)
                            <span class="inline-flex items-center gap-0.5 text-[10px] sm:text-xs font-bold text-amber-500 shrink-0">
                                <span class="material-symbols-outlined text-[11px] sm:text-[13px] fill-current text-amber-500">star</span>
                                <span>{{ number_format((float)$ratingDisplay, 1) }}</span>
                                @if($reviewsDisplayCount > 0)
                                    <span class="text-[9px] sm:text-[10px] font-normal text-on-surface-variant">({{ $reviewsDisplayCount }})</span>
                                @endif
                            </span>
                            @else
                            <span class="text-[9px] sm:text-[10px] px-1.5 py-0.5 bg-primary/10 text-primary font-bold rounded shrink-0">
                                Baru
                            </span>
                            @endif
                        </div>

                        <h3 class="text-xs sm:text-sm font-bold text-on-background dark:text-white line-clamp-2 leading-snug group-hover:text-primary transition-colors mb-1 sm:mb-1.5">
                            {{ $prod->name }}
                        </h3>

                        {{-- Toko --}}
                        <div class="flex items-center gap-1 text-[10px] sm:text-xs text-on-surface-variant mb-2 sm:mb-2.5">
                            <span class="material-symbols-outlined text-[12px] sm:text-[14px] text-primary shrink-0">storefront</span>
                            <span class="truncate font-medium">{{ $prod->store ? $prod->store->name : ($company->company_name ?? 'Official Store') }}</span>
                        </div>

                        {{-- Deskripsi singkat di desktop --}}
                        @if(!empty($descText))
                            <div class="hidden md:block mb-3 p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface-variant leading-relaxed">
                                <p class="font-normal line-clamp-2">{{ $descText }}</p>
                            </div>
                        @endif

                        <div class="pt-2 sm:pt-3 border-t border-outline-variant/40 flex items-center justify-between mt-auto">
                            <div class="min-w-0">
                                @if($hasDiscount)
                                    <p class="text-[9px] sm:text-[10px] text-on-surface-variant line-through leading-none mb-0.5 truncate">
                                        Rp{{ number_format($prod->price, 0, ',', '.') }}
                                    </p>
                                @endif
                                <p class="text-xs sm:text-sm font-black text-primary leading-tight truncate">
                                    Rp{{ number_format($effectivePrice, 0, ',', '.') }}
                                </p>
                            </div>
                            <span class="text-[9px] sm:text-[10px] px-1.5 sm:px-2 py-0.5 bg-surface-container rounded text-on-surface-variant font-semibold shrink-0">
                                {{ $displaySold }} Terjual
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </section>

    <!-- Services Section -->
    @if(isset($services) && $services->count() > 0)
    <section id="services" class="py-12 md:py-2xl bg-surface-container">
        <div class="max-w-container-max mx-auto px-4 sm:px-lg">
            <div class="text-center mb-8 md:mb-xl">
                <h2 class="text-2xl md:font-headline-xl font-black text-on-background dark:text-white mb-2 md:mb-md">Our Services</h2>
                <p class="text-xs md:text-body-lg text-on-surface-variant max-w-2xl mx-auto">Comprehensive digital solutions tailored to your business needs.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-lg">
                @foreach($services as $service)
                <div class="bg-surface rounded-xl p-4 sm:p-lg border border-outline-variant hover:border-primary/50 transition-colors group">
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-lg bg-secondary-container/20 text-secondary flex items-center justify-center mb-3 md:mb-md group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">{{ $service->icon ?? 'layers' }}</span>
                    </div>
                    <h3 class="text-base md:font-headline-sm font-bold text-on-background dark:text-white mb-1 md:mb-sm">{{ $service->name }}</h3>
                    <p class="text-xs md:text-body-md text-on-surface-variant leading-relaxed">{{ $service->short_description ?: Str::limit($service->description, 120) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Projects Preview -->
    @if(isset($projects) && $projects->count() > 0)
    <section id="projects" class="py-12 md:py-2xl">
        <div class="max-w-container-max mx-auto px-4 sm:px-lg">
            <div class="flex flex-row justify-between items-end mb-6 md:mb-xl gap-2 md:gap-md">
                <div>
                    <h2 class="text-2xl md:font-headline-xl font-black text-on-background dark:text-white mb-1 md:mb-md">Featured Work</h2>
                    <p class="text-xs md:text-body-lg text-on-surface-variant max-w-2xl hidden sm:block">A glimpse into some of our recent successful partnerships.</p>
                </div>
                <a href="{{ route('projects.index') }}" class="px-3 py-2 md:px-lg md:py-3 rounded-lg border border-outline-variant font-label-md text-xs md:text-label-md text-primary hover:bg-surface-container transition-colors inline-flex items-center gap-1 shrink-0 font-bold" wire:navigate>
                    <span>Lihat Semua</span> <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 md:gap-gutter">
                @foreach($projects as $project)
                <a href="{{ route('projects.show', $project->slug) }}" wire:navigate class="bg-surface rounded-xl border border-outline-variant overflow-hidden hover:border-primary/50 transition-colors duration-200 group block cursor-pointer">
                    <div class="relative h-44 sm:h-48 overflow-hidden">
                        @if($project->thumbnail)
                        <img loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ media_url($project->thumbnail) }}" alt="{{ $project->title }}"/>
                        @else
                        <div class="w-full h-full bg-surface-container flex items-center justify-center text-outline-variant">
                            <span class="material-symbols-outlined text-4xl">image</span>
                        </div>
                        @endif
                        <div class="absolute top-2.5 right-2.5 bg-surface-bright/90 backdrop-blur text-on-surface font-label-md text-[10px] md:text-xs px-2 py-0.5 rounded">
                            {{ $project->projectCategory->name ?? 'Uncategorized' }}
                        </div>
                    </div>
                    <div class="p-3 sm:p-md">
                        <h3 class="text-sm md:font-headline-sm font-bold text-on-background dark:text-white mb-1 truncate group-hover:text-primary transition-colors">{{ $project->title }}</h3>
                        <span class="inline-flex items-center gap-1 font-label-md text-xs md:text-sm text-secondary group-hover:text-primary transition-colors font-semibold">
                            View Detail <span class="material-symbols-outlined text-xs group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>


{{-- =========================================================================
     MOBILE LAYOUT (block md:hidden)
     App-style layout for mobile:
     1. Toko Pilihan slider (showing accurate product count, rating, description)
     2. Produk Populer 2-column compact grid
     ========================================================================= --}}
<div class="block md:hidden">
    {{-- TOKO PILIHAN (BISA DIGESER KANAN KIRI OLEH USER) --}}
    @if(isset($topStores) && $topStores->count() > 0)
    <section class="py-4 border-b border-outline-variant/30 bg-surface/50">
        <div class="px-4 mb-2.5 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <span class="p-1 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[16px] text-emerald-600">storefront</span>
                </span>
                <h2 class="text-xs font-black text-on-background dark:text-white uppercase tracking-wider">Toko Pilihan</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-0.5">
                Lihat Semua <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
            </a>
        </div>
        {{-- Touch/Drag Interactive & Auto-sliding Track --}}
        @php
            $displayStores = $topStores->concat($topStores);
            if ($topStores->count() < 4) {
                $displayStores = $displayStores->concat($displayStores);
            }
        @endphp
        <div class="overflow-x-auto hide-scrollbar py-1 -mx-0 px-4 select-none cursor-grab active:cursor-grabbing"
             x-data="{
                 isPaused: false,
                 rafId: null,
                 resumeTimer: null,
                 speed: 0.65,
                 initSlider() {
                     const el = this.$refs.slider;
                     if (!el) return;
                     const step = () => {
                         if (!this.isPaused && el) {
                             el.scrollLeft += this.speed;
                             const half = el.scrollWidth / 2;
                             if (half > 0 && el.scrollLeft >= half) {
                                 el.scrollLeft -= half;
                             }
                         }
                         this.rafId = requestAnimationFrame(step);
                     };
                     this.rafId = requestAnimationFrame(step);
                 },
                 pause() {
                     this.isPaused = true;
                     if (this.resumeTimer) clearTimeout(this.resumeTimer);
                 },
                 resume() {
                     this.isPaused = false;
                 },
                 resumeWithDelay() {
                     if (this.resumeTimer) clearTimeout(this.resumeTimer);
                     this.resumeTimer = setTimeout(() => {
                         this.isPaused = false;
                     }, 2000);
                 }
             }"
             x-init="initSlider()"
             @touchstart="pause()"
             @touchend="resumeWithDelay()"
             @pointerdown="pause()"
             @pointerup="resumeWithDelay()"
             @mouseenter="pause()"
             @mouseleave="resume()"
             x-ref="slider"
             style="-webkit-overflow-scrolling: touch; scroll-behavior: auto; -ms-overflow-style: none; scrollbar-width: none;">
            <div class="flex items-stretch gap-2.5 w-max">
                @foreach($displayStores as $store)
                @php
                    $storeDesc = $store->description ?: ($store->bio ?: 'Kreator produk digital & template terpercaya.');
                @endphp
                <a href="{{ route('store.show', $store->slug) }}" class="store-slide-card shrink-0 select-none cursor-pointer">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="store-slide-logo"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="store-slide-logo-fallback" style="display:none;">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
                    @else
                        <div class="store-slide-logo-fallback">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <p class="font-black text-xs text-on-background dark:text-white truncate leading-tight">{{ $store->name }}</p>
                            <span class="inline-flex items-center gap-0.5 px-1 py-0.5 rounded bg-amber-500/10 text-amber-600 dark:text-amber-400 font-black text-[9px] shrink-0">
                                <span class="text-amber-500 text-[10px]">★</span> {{ number_format($store->rating ?? 4.9, 1) }}
                            </span>
                        </div>
                        <p class="text-[10px] text-on-surface-variant/80 dark:text-slate-400 line-clamp-1 leading-snug">
                            {{ $storeDesc }}
                        </p>
                        <div class="flex items-center justify-between gap-2 mt-1.5 pt-1 border-t border-outline-variant/30 text-[9.5px]">
                            <span class="inline-flex items-center gap-1 font-bold text-primary">
                                <span class="material-symbols-outlined text-[12px]">inventory_2</span>
                                <span>{{ $store->products_count ?? 0 }} Produk</span>
                            </span>
                            @if(!empty($store->sales_count) && $store->sales_count > 0)
                                <span class="text-on-surface-variant font-medium">
                                    {{ $store->sales_count }} Terjual
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Aktif</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- KATEGORI PRODUK DIGITAL (3 KATEGORI UTAMA + COMBO BOX LAINNYA) --}}
    @if(isset($categories) && $categories->count() > 0)
    <section class="py-3 px-3 border-b border-outline-variant/30 bg-surface/30">
        <div class="flex items-center justify-between gap-2 mb-2 px-1">
            <div class="flex items-center gap-1.5">
                <span class="p-1 rounded-lg bg-cyan-500/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[15px]">category</span>
                </span>
                <h2 class="text-xs font-black text-on-background dark:text-white uppercase tracking-wider">Kategori Pilihan</h2>
            </div>
            <a href="{{ route('products.index') }}" class="text-[11px] font-bold text-primary hover:underline">
                Katalog
            </a>
        </div>

        @php
            $visibleCategories = $categories->take(3);
            $comboCategories = $categories->slice(3);
        @endphp

        <div class="flex items-center gap-1.5 overflow-x-auto hide-scrollbar scroll-smooth py-0.5 px-0.5 whitespace-nowrap" style="-ms-overflow-style: none; scrollbar-width: none;">
            {{-- 3 Kategori yang terlihat langsung --}}
            @foreach($visibleCategories as $cat)
                <a href="{{ route('products.index', ['category' => $cat->id]) }}" 
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-semibold bg-surface dark:bg-slate-800 border border-outline-variant hover:border-primary text-on-surface dark:text-gray-200 shrink-0 transition-colors whitespace-nowrap">
                    <span>{{ $cat->name }}</span>
                    @if($cat->products_count > 0)
                        <span class="text-[9px] px-1.5 py-0.2 rounded-full bg-surface-container dark:bg-slate-700 text-on-surface-variant font-bold">{{ $cat->products_count }}</span>
                    @endif
                </a>
            @endforeach

            {{-- Sisanya masuk ke combo box --}}
            @if($comboCategories->count() > 0)
            <div class="relative shrink-0 min-w-[130px] max-w-[180px]">
                <select onchange="if(this.value) window.location.href = this.value" 
                        aria-label="Pilih Kategori Lainnya"
                        class="w-full appearance-none pl-3 pr-7 py-1.5 rounded-full text-xs font-semibold bg-surface dark:bg-slate-800 border border-outline-variant text-on-surface dark:text-gray-200 hover:border-primary focus:outline-none focus:ring-1 focus:ring-primary transition-colors cursor-pointer truncate whitespace-nowrap">
                    <option value="">+ Lainnya ({{ $comboCategories->count() }})</option>
                    <option value="{{ route('products.index') }}">Semua Kategori</option>
                    @foreach($comboCategories as $cCat)
                        <option value="{{ route('products.index', ['category' => $cCat->id]) }}">
                            {{ $cCat->name }} ({{ $cCat->products_count }})
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-on-surface-variant">
                    <span class="material-symbols-outlined text-[15px]">expand_more</span>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- PRODUK POPULER (2-COLUMN COMPACT MOBILE GRID) --}}
    @if(isset($popularProducts) && $popularProducts->count() > 0)
    <section class="py-4 px-3 max-w-[1280px] mx-auto border-b border-outline-variant/30">
        <div class="flex items-center justify-between gap-2 mb-3 px-1">
            <div class="flex items-center gap-1.5">
                <span class="p-1 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[16px]">local_fire_department</span>
                </span>
                <h2 class="text-xs font-black text-on-background dark:text-white uppercase tracking-wider">Produk Populer</h2>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-0.5 text-[11px] font-bold text-primary hover:underline shrink-0">
                Lihat Semua <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-2 gap-2">
            @foreach($popularProducts as $prod)
            @php
                $mainImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first();
                $hasDiscount = $prod->discount_price && $prod->discount_price > 0 && $prod->discount_price < $prod->price;
                $effectivePrice = $hasDiscount ? $prod->discount_price : $prod->price;
                $ratingDisplay = $prod->effective_rating;
            @endphp
            <a href="{{ route('products.show', $prod->slug) }}"
               class="group bg-surface rounded-xl border border-outline-variant hover:border-primary/40 overflow-hidden transition-colors flex flex-col">
                <div class="relative aspect-square w-full bg-surface-container overflow-hidden">
                    @if($mainImg)
                        <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $prod->name }}"
                             class="w-full h-full object-cover object-top" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-outline-variant bg-surface-container-high">
                            <span class="material-symbols-outlined text-2xl">inventory_2</span>
                        </div>
                    @endif
                    @if($hasDiscount)
                    <span class="absolute top-1 left-1 bg-red-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded">
                        -{{ round((($prod->price - $prod->discount_price) / $prod->price) * 100) }}%
                    </span>
                    @endif
                    <div class="absolute bottom-1 right-1 bg-black/60 backdrop-blur-xs text-white text-[8.5px] font-medium px-1.5 py-0.5 rounded flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[9px]">visibility</span>
                        <span>{{ number_format($prod->views ?? 0) }}</span>
                    </div>
                </div>
                <div class="p-2 flex flex-col flex-1">
                    <h3 class="text-xs font-semibold text-on-background dark:text-white line-clamp-2 leading-tight mb-1">
                        {{ $prod->name }}
                    </h3>
                    @if($hasDiscount)
                    <p class="text-[9px] text-on-surface-variant line-through leading-none mb-0.5">Rp{{ number_format($prod->price, 0, ',', '.') }}</p>
                    @endif
                    <div class="flex items-end justify-between mt-auto pt-1 border-t border-outline-variant/30">
                        <p class="text-xs font-black text-primary leading-tight">Rp{{ number_format($effectivePrice, 0, ',', '.') }}</p>
                        <span class="flex items-center gap-0.5 text-amber-500 font-bold text-[9.5px]">
                            <span class="material-symbols-outlined text-[10px] fill-current">star</span>
                            <span>{{ number_format((float)$ratingDisplay, 1) }}</span>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- KATALOG PRODUK TERBARU (MOBILE 2-COLUMN GRID) --}}
    @if(isset($latestProducts) && $latestProducts->count() > 0)
    <section class="py-4 px-3 max-w-[1280px] mx-auto">
        <div class="flex items-center justify-between gap-2 mb-3 px-1">
            <div class="flex items-center gap-1.5">
                <span class="p-1 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[16px]">new_releases</span>
                </span>
                <h2 class="text-xs font-black text-on-background dark:text-white uppercase tracking-wider">Koleksi Terbaru</h2>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-0.5 text-[11px] font-bold text-primary hover:underline shrink-0">
                Katalog Lengkap <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-2 gap-2">
            @foreach($latestProducts as $prod)
            @php
                $mainImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first();
                $hasDiscount = $prod->discount_price && $prod->discount_price > 0 && $prod->discount_price < $prod->price;
                $effectivePrice = $hasDiscount ? $prod->discount_price : $prod->price;
                $ratingDisplay = $prod->effective_rating;
            @endphp
            <a href="{{ route('products.show', $prod->slug) }}"
               class="group bg-surface rounded-xl border border-outline-variant hover:border-primary/40 overflow-hidden transition-colors flex flex-col">
                <div class="relative aspect-square w-full bg-surface-container overflow-hidden">
                    @if($mainImg)
                        <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $prod->name }}"
                             class="w-full h-full object-cover object-top" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-outline-variant bg-surface-container-high">
                            <span class="material-symbols-outlined text-2xl">inventory_2</span>
                        </div>
                    @endif
                    @if($hasDiscount)
                    <span class="absolute top-1 left-1 bg-red-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded">
                        -{{ round((($prod->price - $prod->discount_price) / $prod->price) * 100) }}%
                    </span>
                    @endif
                    <div class="absolute bottom-1 right-1 bg-black/60 backdrop-blur-xs text-white text-[8.5px] font-medium px-1.5 py-0.5 rounded flex items-center gap-0.5">
                        <span class="material-symbols-outlined text-[9px]">visibility</span>
                        <span>{{ number_format($prod->views ?? 0) }}</span>
                    </div>
                </div>
                <div class="p-2 flex flex-col flex-1">
                    <h3 class="text-xs font-semibold text-on-background dark:text-white line-clamp-2 leading-tight mb-1">
                        {{ $prod->name }}
                    </h3>
                    @if($hasDiscount)
                    <p class="text-[9px] text-on-surface-variant line-through leading-none mb-0.5">Rp{{ number_format($prod->price, 0, ',', '.') }}</p>
                    @endif
                    <div class="flex items-end justify-between mt-auto pt-1 border-t border-outline-variant/30">
                        <p class="text-xs font-black text-primary leading-tight">Rp{{ number_format($effectivePrice, 0, ',', '.') }}</p>
                        <span class="flex items-center gap-0.5 text-amber-500 font-bold text-[9.5px]">
                            <span class="material-symbols-outlined text-[10px] fill-current">star</span>
                            <span>{{ number_format((float)$ratingDisplay, 1) }}</span>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center justify-center gap-1.5 w-full py-2.5 rounded-xl bg-surface dark:bg-slate-800 border border-outline-variant text-xs font-bold text-on-surface dark:text-white hover:border-primary transition-colors">
                <span>Buka Semua Produk & Filter Toko</span>
                <span class="material-symbols-outlined text-[16px] text-primary">storefront</span>
            </a>
        </div>
    </section>
    @endif

    {{-- MOBILE FOOTER --}}
    <footer class="mt-8 py-6 px-4 bg-surface-container dark:bg-slate-900 border-t border-outline-variant/40 text-center text-xs text-on-surface-variant">
        <div class="flex items-center justify-center gap-2 mb-2 font-black text-sm text-on-background dark:text-white">
            <img src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('logo.png') }}" alt="{{ $company->company_name ?? 'rhantech' }}" class="h-6 w-auto">
            <span>{{ $company->company_name ?? 'rhantech' }}</span>
        </div>
        <p class="text-[11px] leading-relaxed max-w-xs mx-auto mb-4 text-on-surface-variant/80">
            Platform belanja produk digital, source code, sistem dan layanan IT terpercaya.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-[11px] font-semibold text-primary mb-4">
            <a href="{{ route('products.index') }}">Produk & Toko</a>
            <a href="{{ route('projects.index') }}" wire:navigate>Portfolio</a>
            <a href="{{ route('about') }}" wire:navigate>Tentang Kami</a>
            <a href="{{ url('/contact') }}" wire:navigate>Kontak</a>
            <a href="{{ route('tenant.dashboard') }}">Area Mitra</a>
        </div>
        <div class="text-[10px] text-on-surface-variant/60 pt-2 border-t border-outline-variant/30">
            © {{ date('Y') }} {{ $company->company_name ?? 'rhantech' }}. All rights reserved.
        </div>
    </footer>
</div>
@endsection
