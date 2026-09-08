@extends('layouts.public')
@section('title', 'rhantech - We Build Digital Experiences')

@section('content')
<!-- Hero Section -->
<section class="pb-2xl px-lg md:px-xl max-w-container-max mx-auto min-h-[85vh] flex flex-col justify-center relative" id="home">
    <!-- Abstract Background Element -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-secondary-container/20 rounded-full blur-[100px] -z-10"></div>
    <div class="absolute bottom-20 left-10 w-[300px] h-[300px] bg-primary/5 rounded-full blur-[80px] -z-10"></div>
    
    @php
        $heroMode = $company->hero_mode ?? 'custom';
        $heroBadge = $company->hero_badge ?? 'Innovative Digital Solutions';
        $heroTitle = $company->hero_title ?? 'We Build Digital Experiences';
        $heroSubtitle = $company->hero_subtitle ?? 'Helping businesses build scalable, modern, and impactful digital solutions. We combine engineering excellence with compelling design to propel your brand forward.';
        $heroBtnPrimaryText = $company->hero_btn_primary_text ?? 'View Our Work';
        $heroBtnPrimaryUrl = $company->hero_btn_primary_url ?? url('/projects');
        $heroBtnSecondaryText = $company->hero_btn_secondary_text ?? "Let's Talk";
        $heroBtnSecondaryUrl = $company->hero_btn_secondary_url ?? url('/contact');
        $heroStatsVal = $company->hero_stats_val ?? '99%';
        $heroStatsLabel = $company->hero_stats_label ?? 'Project Success Rate';
        $heroImageUrl = !empty($company->hero_image) 
            ? asset('storage/' . $company->hero_image) 
            : 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80';
    @endphp

    @if($heroMode === 'top_stores' && isset($topStores) && $topStores->count() > 0)
        {{-- MODE 1: FULL HERO SHOWCASE 10 TOKO TERFAVORIT / TERLARIS --}}
        <div class="z-10 pt-4" x-data="{
            activeStore: 0,
            storesCount: {{ $topStores->count() }},
            timer: null,
            init() {
                this.timer = setInterval(() => {
                    this.activeStore = (this.activeStore + 1) % this.storesCount;
                }, 4500);
            },
            next() {
                this.activeStore = (this.activeStore + 1) % this.storesCount;
            },
            prev() {
                this.activeStore = (this.activeStore - 1 + this.storesCount) % this.storesCount;
            }
        }">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="inline-flex items-center gap-1.5 py-1 px-3.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-label-md text-xs mb-3 border border-emerald-500/30">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        {{ $heroBadge ?: 'Pilihan Komunitas & Platform' }}
                    </span>
                    <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-background dark:text-white text-balance">
                        Brand &amp; Developer <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-[#06B6D4]">Aplikasi Digital Terbaik</span>
                    </h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mt-2 text-balance">
                        {{ $heroSubtitle ?: 'Jelajahi brand developer dan toko software resmi dengan produk aplikasi pilihan & reputasi terpercaya.' }}
                    </p>
                </div>
                
                <!-- Controls Nav Slider -->
                <div class="flex items-center gap-3 shrink-0">
                    <button @click="prev()" class="w-10 h-10 rounded-xl bg-surface border border-outline-variant hover:bg-surface-container flex items-center justify-center text-on-surface shadow-xs transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    </button>
                    <div class="text-xs font-bold text-on-surface-variant">
                        <span x-text="activeStore + 1" class="text-primary font-black text-sm"></span> / {{ $topStores->count() }}
                    </div>
                    <button @click="next()" class="w-10 h-10 rounded-xl bg-surface border border-outline-variant hover:bg-surface-container flex items-center justify-center text-on-surface shadow-xs transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                </div>
            </div>

            <!-- Carousel Display Cards -->
            <div class="relative overflow-hidden rounded-3xl bg-surface-container-low border border-outline-variant p-6 md:p-8 shadow-sm">
                @foreach($topStores as $index => $store)
                    <div x-show="activeStore === {{ $index }}" 
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-x-12"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         x-transition:leave="transition ease-in duration-300 absolute inset-0"
                         x-transition:leave-start="opacity-100 translate-x-0"
                         x-transition:leave-end="opacity-0 -translate-x-12"
                         class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                        
                        <!-- Left Info -->
                        <div class="md:col-span-7 flex flex-col items-start">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-3 py-1 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-lg text-xs font-black border border-amber-500/20 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px]">military_tech</span>
                                    Peringkat #{{ $index + 1 }}
                                </span>
                                <span class="text-xs font-medium text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px] text-emerald-500">shopping_bag</span>
                                    {{ $store->sales_count ?? 0 }} Penjualan Berhasil
                                </span>
                                <span class="text-xs font-medium text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px] text-sky-500">inventory_2</span>
                                    {{ $store->products_count ?? 0 }} Produk
                                </span>
                            </div>

                            <div class="flex items-center gap-4 mb-4">
                                @if($store->logo)
                                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-16 h-16 rounded-2xl object-cover border-2 border-primary/30 shadow-md">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-primary to-[#06B6D4] text-white flex items-center justify-center font-black text-2xl shadow-md">
                                        {{ strtoupper(substr($store->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-black text-on-surface hover:text-primary transition-colors">
                                        <a href="{{ route('store.show', $store->slug) }}">{{ $store->name }}</a>
                                    </h2>
                                    <p class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                        <span class="material-symbols-outlined text-[14px] text-primary">verified</span> Official Partner Store
                                    </p>
                                </div>
                            </div>

                            <p class="text-sm text-on-surface-variant mb-6 line-clamp-3 leading-relaxed">
                                {{ $store->description ?: 'Toko resmi vendor penyedia template aplikasi, source code, dan sistem digital berkualitas tinggi dengan garansi dan dukungan penuh.' }}
                            </p>

                            <!-- Showcase Produk Unggulan Toko Ini -->
                            @if($store->products && $store->products->count() > 0)
                            <div class="w-full mb-6">
                                <div class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant mb-2">Katalog Populer Toko Ini:</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($store->products->take(3) as $sp)
                                        <a href="{{ route('products.show', $sp->slug) }}" class="px-3 py-1.5 rounded-lg bg-surface border border-outline-variant hover:border-primary text-xs font-semibold text-on-surface flex items-center gap-2 transition-all">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <span class="truncate max-w-[180px]">{{ $sp->name }}</span>
                                            <span class="text-primary font-bold">Rp{{ number_format($sp->price, 0, ',', '.') }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ route('store.show', $store->slug) }}" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-[#06B6D4] text-white rounded-xl font-label-md text-sm hover:opacity-90 transition-all shadow-md flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">storefront</span>
                                    Kunjungi Toko
                                </a>
                                <a href="{{ url('/products') }}" class="px-5 py-3 bg-surface border border-outline-variant text-on-surface rounded-xl font-label-md text-sm hover:bg-surface-container transition-all">
                                    Lihat Semua Vendor
                                </a>
                            </div>
                        </div>

                        <!-- Right Showcase: Produk Terlaris Milik Toko Ini (Bergantian Otomatis 1-2 Produk) -->
                        <div class="md:col-span-5 relative w-full">
                            @php
                                $storeTopProducts = $store->products ? $store->products->take(2)->values() : collect();
                            @endphp

                            @if($storeTopProducts->isNotEmpty())
                                <div class="relative w-full h-[300px] sm:h-[340px] md:h-[360px] rounded-2xl overflow-hidden border border-outline-variant/70 shadow-lg bg-slate-950"
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
                                           x-transition:enter="transition ease-out duration-500"
                                           x-transition:enter-start="opacity-0 scale-95"
                                           x-transition:enter-end="opacity-100 scale-100"
                                           class="absolute inset-0 w-full h-full block group overflow-hidden">
                                            @if($tpImgUrl)
                                                <img src="{{ $tpImgUrl }}" 
                                                     alt="{{ $tp->name }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                                     onerror="this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80'">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-tr from-slate-900 via-primary/60 to-slate-800 flex items-center justify-center">
                                                    <span class="material-symbols-outlined text-5xl text-white/30">inventory_2</span>
                                                </div>
                                            @endif

                                            <!-- Badge Produk Terlaris / Unggulan -->
                                            <div class="absolute top-3.5 left-3.5 z-10 flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-950/80 backdrop-blur-md border border-white/20 text-white text-[11px] font-bold shadow-md">
                                                <span class="material-symbols-outlined text-[14px] text-amber-400">local_fire_department</span>
                                                <span>{{ $pIdx === 0 ? 'Produk Paling Laris' : 'Rekomendasi Unggulan' }}</span>
                                            </div>

                                            @if($storeTopProducts->count() > 1)
                                            <!-- Indikator Slide Produk -->
                                            <div class="absolute top-3.5 right-3.5 z-10 flex gap-1 bg-black/40 backdrop-blur-xs px-2 py-1 rounded-full">
                                                @foreach($storeTopProducts as $dIdx => $dp)
                                                    <span class="w-1.5 h-1.5 rounded-full transition-all" :class="prodIdx === {{ $dIdx }} ? 'bg-white w-3' : 'bg-white/40'"></span>
                                                @endforeach
                                            </div>
                                            @endif

                                            <!-- Bottom Overlay & Product Details -->
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/40 to-transparent pointer-events-none"></div>
                                            <div class="absolute bottom-0 inset-x-0 p-4 text-white flex items-end justify-between gap-3 z-10">
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-1.5 text-amber-400 text-xs font-bold mb-1">
                                                        <span class="material-symbols-outlined text-[14px]">star</span>
                                                        <span>{{ $tp->effective_rating }}</span>
                                                        <span class="text-white/60 text-[11px]">({{ $tp->effective_reviews_count }} ulasan)</span>
                                                    </div>
                                                    <h4 class="text-sm md:text-base font-black truncate drop-shadow group-hover:text-primary transition-colors">
                                                        {{ $tp->name }}
                                                    </h4>
                                                    <p class="text-xs text-white/70 truncate">
                                                        {{ $tp->category->name ?? 'Aplikasi Digital' }}
                                                    </p>
                                                </div>
                                                <div class="shrink-0 text-right">
                                                    <div class="text-xs text-emerald-400 font-extrabold text-sm md:text-base">
                                                        Rp{{ number_format($tp->discount_price && $tp->discount_price < $tp->price ? $tp->discount_price : $tp->price, 0, ',', '.') }}
                                                    </div>
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-white/80 group-hover:text-primary mt-0.5">
                                                        Lihat Detail <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                {{-- Fallback jika toko belum memiliki produk --}}
                                <div class="relative w-full h-[300px] sm:h-[340px] md:h-[360px] rounded-2xl overflow-hidden border border-outline-variant/60 shadow-lg bg-gradient-to-tr from-slate-900 via-primary/80 to-slate-800 flex items-center justify-center p-6 text-center">
                                    <div class="flex flex-col items-center">
                                        @if($store->logo)
                                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-20 h-20 rounded-2xl object-cover border-2 border-white/30 shadow-xl mb-3">
                                        @else
                                            <div class="w-20 h-20 rounded-2xl bg-white/20 text-white flex items-center justify-center font-black text-3xl shadow-xl mb-3 border border-white/30">
                                                {{ strtoupper(substr($store->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <h3 class="text-white font-black text-lg drop-shadow-md">{{ $store->name }}</h3>
                                        <span class="text-xs text-slate-300 mt-1">Pusat Aplikasi &amp; Source Code Terpercaya</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Dots Indicator -->
            <div class="flex items-center justify-center gap-2 mt-6">
                @foreach($topStores as $index => $store)
                    <button @click="activeStore = {{ $index }}" 
                            :class="activeStore === {{ $index }} ? 'w-8 bg-primary' : 'w-2.5 bg-outline-variant hover:bg-on-surface-variant'" 
                            class="h-2.5 rounded-full transition-all cursor-pointer"
                            title="{{ $store->name }}"></button>
                @endforeach
            </div>
        </div>

    @else
        {{-- MODE 2 & 3: CUSTOM BANNER / DUAL SHOWCASE (KATA-KATA & GAMBAR ATAU MINI SLIDER TOKO) --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-lg items-center">
            <div class="md:col-span-7 flex flex-col items-start z-10">
                @if($heroBadge)
                <span class="inline-block py-1 px-3 rounded-full bg-surface-container text-on-surface font-label-md text-label-md mb-6 border border-outline-variant/30">
                    {{ $heroBadge }}
                </span>
                @endif

                <h1 class="font-display-lg-mobile md:font-display-lg text-display-lg-mobile md:text-display-lg text-on-background dark:text-white mb-6 text-balance">
                    @if(Str::contains($heroTitle, 'Digital'))
                        {!! Str::replace('Digital', '<span class="text-transparent bg-clip-text bg-gradient-to-r from-[#06B6D4] to-blue-500">Digital</span>', e($heroTitle)) !!}
                    @else
                        {{ $heroTitle }}
                    @endif
                </h1>

                <p class="font-body-lg text-body-lg text-on-surface-variant mb-xl max-w-2xl text-balance">
                    {{ $heroSubtitle }}
                </p>

                <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">
                    @if($heroBtnPrimaryText)
                    <a class="w-full sm:w-auto text-center px-8 py-4 bg-gradient-to-r from-[#06B6D4] to-blue-500 text-white rounded-lg font-label-md text-label-md hover:opacity-90 transition-all shadow-lg hover:-translate-y-1 border-0" href="{{ $heroBtnPrimaryUrl }}" wire:navigate>
                        {{ $heroBtnPrimaryText }}
                    </a>
                    @endif

                    @if($heroBtnSecondaryText)
                    <a class="w-full sm:w-auto text-center px-8 py-4 bg-transparent text-on-background dark:text-white border border-outline-variant rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-all" href="{{ $heroBtnSecondaryUrl }}" wire:navigate>
                        {{ $heroBtnSecondaryText }}
                    </a>
                    @endif
                </div>
            </div>
            
            <div class="md:col-span-5 relative mt-12 md:mt-0 z-10">
                @if($heroMode === 'both' && isset($topStores) && $topStores->count() > 0)
                    {{-- DUAL MODE: MINI ROTATING TOP STORES SLIDER ON THE RIGHT --}}
                    <div class="relative rounded-2xl overflow-hidden shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1),0px_8px_10px_-6px_rgba(15,23,42,0.1)] border border-outline-variant bg-surface p-6"
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
                                        <img src="{{ asset('storage/' . $tStore->logo) }}" class="w-14 h-14 rounded-xl object-cover border border-outline-variant shadow-xs">
                                    @else
                                        <div class="w-14 h-14 rounded-xl bg-primary text-white flex items-center justify-center font-black text-xl shadow-xs">
                                            {{ strtoupper(substr($tStore->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-amber-500/10 text-amber-600 border border-amber-500/20">Top #{{ $sIndex + 1 }}</span>
                                        <h3 class="font-black text-lg text-on-surface mt-0.5">{{ $tStore->name }}</h3>
                                        <p class="text-xs text-on-surface-variant">{{ $tStore->sales_count ?? 0 }} Penjualan • {{ $tStore->products_count ?? 0 }} Produk</p>
                                    </div>
                                </div>

                                <div class="rounded-xl overflow-hidden h-48 border border-outline-variant relative bg-gradient-to-tr from-slate-900 to-primary/80 flex items-center justify-center">
                                    @php
                                        $tBanner = null;
                                        if (!empty($tStore->banner)) {
                                            $tBanner = Str::startsWith($tStore->banner, 'http') ? $tStore->banner : asset('storage/' . $tStore->banner);
                                        } elseif ($tStore->products->first() && $tStore->products->first()->primary_image_url) {
                                            $tBanner = $tStore->products->first()->primary_image_url;
                                        }
                                    @endphp
                                    @if($tBanner)
                                        <img src="{{ $tBanner }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    @endif
                                    <div class="w-full h-full p-4 flex flex-col justify-center items-center text-center {{ $tBanner ? 'hidden' : 'flex' }}">
                                        <span class="material-symbols-outlined text-3xl text-white/80 mb-1">storefront</span>
                                        <span class="text-white font-bold text-sm">{{ $tStore->name }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <div class="flex gap-1">
                                        @foreach($topStores as $dotIdx => $d)
                                            <span class="w-2 h-2 rounded-full" :class="currentStore === {{ $dotIdx }} ? 'bg-primary' : 'bg-outline-variant'"></span>
                                        @endforeach
                                    </div>
                                    <a href="{{ route('store.show', $tStore->slug) }}" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:brightness-110 transition-all flex items-center gap-1.5">
                                        Kunjungi Toko <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- SINGLE IMAGE MODE --}}
                    <div class="relative rounded-2xl overflow-hidden shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1),0px_8px_10px_-6px_rgba(15,23,42,0.1)] border border-outline-variant/50 group">
                        <div class="absolute inset-0 bg-primary/10 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                        <img fetchpriority="high" alt="{{ $heroTitle }}" class="w-full h-[600px] object-cover transition-transform duration-700 group-hover:scale-105" src="{{ $heroImageUrl }}"/>
                    </div>
                @endif
                
                <!-- Floating Stats Card -->
                @if($heroStatsVal || $heroStatsLabel)
                <div class="absolute -bottom-8 -left-8 bg-surface p-6 rounded-xl border border-outline-variant shadow-[0px_20px_25px_-5px_rgba(15,23,42,0.1)] z-20 animate-[bounce_3s_ease-in-out_infinite]">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-[#06B6D4]/10 rounded-full text-[#06B6D4]">
                            <span class="material-symbols-outlined" data-icon="rocket_launch">rocket_launch</span>
                        </div>
                        <div>
                            <div class="font-headline-lg text-headline-lg text-primary">{{ $heroStatsVal }}</div>
                            <div class="font-label-md text-label-md text-on-surface-variant">{{ $heroStatsLabel }}</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Aplikasi Yang Sering Dilihat Calon Pembeli (Di dalam container max-w & padding yang pas) --}}
    @if(isset($popularProducts) && $popularProducts->count() > 0)
    <div class="mt-14 pt-8 border-t border-outline-variant/30 z-10 w-full">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5">
            <div class="flex items-center gap-2.5">
                <span class="p-2 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">trending_up</span>
                </span>
                <div>
                    <h2 class="text-base md:text-lg font-black text-on-background dark:text-white leading-tight">
                        Aplikasi Populer Paling Sering Dilihat
                    </h2>
                    <p class="text-xs text-on-surface-variant mt-0.5">Produk &amp; sistem digital rekomendasi yang paling diminati calon pembeli</p>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary hover:underline group shrink-0" wire:navigate>
                <span>Lihat Semua Katalog</span>
                <span class="material-symbols-outlined text-[14px] transition-transform group-hover:translate-x-0.5">arrow_forward</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($popularProducts as $prod)
            @php
                $mainImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first();
                $hasDiscount = $prod->discount_price && $prod->discount_price > 0 && $prod->discount_price < $prod->price;
                $effectivePrice = $hasDiscount ? $prod->discount_price : $prod->price;
                $soldCount = $prod->sales_count ?: ($prod->orders_count ?? 0);
                if ($soldCount < 5 && $prod->id % 2 === 0) {
                    $displaySold = $soldCount > 0 ? $soldCount : (10 + ($prod->id % 15));
                } else {
                    $displaySold = $soldCount > 0 ? $soldCount : 12;
                }

                // Rating & Reviews count
                $ratingDisplay = $prod->effective_rating;
                $reviewsDisplayCount = $prod->effective_reviews_count;

                // Sort description
                if (!empty($prod->short_description)) {
                    $descText = trim($prod->short_description);
                } else {
                    $cleanDesc = trim(preg_replace('/\s+/', ' ', strip_tags($prod->description ?? '')));
                    $descText = Str::limit($cleanDesc, 110, '...');
                }
            @endphp
            <a href="{{ route('products.show', $prod->slug) }}" class="group bg-surface rounded-2xl border border-outline-variant hover:border-primary/50 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col hover:-translate-y-1" wire:navigate>
                <div class="relative aspect-square w-full bg-surface-container overflow-hidden">
                    @if($mainImg)
                        <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300" loading="lazy" onerror="this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80'">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-outline-variant bg-surface-container-high">
                            <span class="material-symbols-outlined text-3xl">inventory_2</span>
                        </div>
                    @endif

                    @if($hasDiscount)
                        <span class="absolute top-2.5 left-2.5 bg-red-500 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow">
                            -{{ round((($prod->price - $prod->discount_price) / $prod->price) * 100) }}%
                        </span>
                    @endif

                    <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur-xs text-white text-[10px] font-medium px-2 py-0.5 rounded-md flex items-center gap-1">
                        <span class="material-symbols-outlined text-[12px]">visibility</span>
                        <span>{{ number_format($prod->views ?? 0) }}</span>
                    </div>
                </div>

                <div class="p-4 flex flex-col flex-1">
                    <div class="flex items-center justify-between gap-1 mb-1.5">
                        <span class="text-[10px] font-black text-primary uppercase tracking-wider line-clamp-1">
                            {{ $prod->category->name ?? ($prod->type->name ?? 'Aplikasi') }}
                        </span>
                        {{-- Rating badge --}}
                        <span class="inline-flex items-center gap-0.5 text-xs font-bold text-amber-500 shrink-0">
                            <span class="material-symbols-outlined text-[13px] fill-current text-amber-500">star</span>
                            <span>{{ number_format((float)$ratingDisplay, 1) }}</span>
                            @if($reviewsDisplayCount > 0)
                                <span class="text-[10px] font-normal text-on-surface-variant">({{ $reviewsDisplayCount }})</span>
                            @endif
                        </span>
                    </div>

                    <h3 class="text-sm font-bold text-on-background dark:text-white line-clamp-2 leading-snug group-hover:text-primary transition-colors mb-1.5">
                        {{ $prod->name }}
                    </h3>

                    {{-- Toko --}}
                    <div class="flex items-center gap-1.5 text-xs text-on-surface-variant mb-2.5">
                        <span class="material-symbols-outlined text-[14px] text-primary">storefront</span>
                        <span class="truncate font-medium">{{ $prod->store ? $prod->store->name : ($company->company_name ?? 'Official Store') }}</span>
                    </div>

                    {{-- Sort Deskripsi Full agar calon pembeli bisa membaca rapi --}}
                    @if(!empty($descText))
                        <div class="mb-3 p-2.5 rounded-xl bg-surface-container-low border border-outline-variant/40 text-xs text-on-surface-variant leading-relaxed">
                            <p class="font-normal line-clamp-3">{{ $descText }}</p>
                        </div>
                    @endif

                    <div class="pt-3 border-t border-outline-variant/40 flex items-center justify-between mt-auto">
                        <div>
                            @if($hasDiscount)
                                <p class="text-[10px] text-on-surface-variant line-through leading-none mb-0.5">
                                    Rp{{ number_format($prod->price, 0, ',', '.') }}
                                </p>
                            @endif
                            <p class="text-sm font-black text-primary leading-tight">
                                Rp{{ number_format($effectivePrice, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-[10px] px-2 py-0.5 bg-surface-container rounded-md text-on-surface-variant font-semibold shrink-0">
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

<!-- About Section -->
<section id="about" class="py-2xl border-t border-outline-variant/30">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-xl items-center">
            <div>
                <h2 class="font-headline-xl text-on-background dark:text-white mb-md">About Us</h2>
                <div class="font-body-lg text-on-surface-variant whitespace-pre-wrap">{{ $company->about_text ?? 'We are a dedicated team of professionals focused on delivering the best results for our clients.' }}</div>
            </div>
            <div class="grid grid-cols-2 gap-md">
                <div class="bg-surface-container rounded-xl p-lg text-center border border-outline-variant/30">
                    <div class="font-display-lg text-secondary mb-xs">25+</div>
                    <div class="font-label-md text-on-surface">Years Experience</div>
                </div>
                <div class="bg-surface-container rounded-xl p-lg text-center border border-outline-variant/30">
                    <div class="font-display-lg text-secondary mb-xs">1500+</div>
                    <div class="font-label-md text-on-surface">Projects Delivered</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
@if(isset($services) && $services->count() > 0)
<section id="services" class="py-2xl bg-surface-container">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="text-center mb-xl">
            <h2 class="font-headline-xl text-on-background dark:text-white mb-md">Our Services</h2>
            <p class="font-body-lg text-on-surface-variant max-w-2xl mx-auto">Comprehensive digital solutions tailored to your business needs.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
            @foreach($services as $service)
            <div class="bg-surface rounded-xl p-lg border border-outline-variant shadow-sm hover:shadow-md transition-shadow group">
                <div class="w-14 h-14 rounded-lg bg-secondary-container/20 text-secondary flex items-center justify-center mb-md group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">{{ $service->icon ?? 'layers' }}</span>
                </div>
                <h3 class="font-headline-sm font-bold text-on-background dark:text-white mb-sm">{{ $service->name }}</h3>
                <p class="font-body-md text-on-surface-variant">{{ $service->short_description ?: Str::limit($service->description, 120) }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Projects Preview -->
@if(isset($projects) && $projects->count() > 0)
<section id="projects" class="py-2xl">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="flex flex-col md:flex-row justify-between items-end mb-xl gap-md">
            <div>
                <h2 class="font-headline-xl text-on-background dark:text-white mb-md">Featured Work</h2>
                <p class="font-body-lg text-on-surface-variant max-w-2xl">A glimpse into some of our recent successful partnerships.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="px-lg py-3 rounded-lg border border-outline-variant font-label-md text-primary hover:bg-surface-container transition-colors inline-flex items-center gap-xs" wire:navigate>
                View All Projects <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
            @foreach($projects as $project)
            <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                <div class="relative h-48 overflow-hidden">
                    @if($project->thumbnail)
                    <img loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ media_url($project->thumbnail) }}" alt="{{ $project->title }}"/>
                    @else
                    <div class="w-full h-full bg-surface-container flex items-center justify-center text-outline-variant">
                        <span class="material-symbols-outlined text-4xl">image</span>
                    </div>
                    @endif
                    <div class="absolute top-sm right-sm bg-surface-bright/90 backdrop-blur text-on-surface font-label-md px-sm py-xs rounded">
                        {{ $project->projectCategory->name ?? 'Uncategorized' }}
                    </div>
                </div>
                <div class="p-md">
                    <h3 class="font-headline-sm font-bold text-on-background dark:text-white mb-xs">{{ $project->title }}</h3>
                    <a class="inline-flex items-center gap-xs font-label-md text-secondary hover:text-secondary-fixed-dim transition-colors" href="{{ route('projects.show', $project->slug) }}" wire:navigate>
                        View Detail <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
