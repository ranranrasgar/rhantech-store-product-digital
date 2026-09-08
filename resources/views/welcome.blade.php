@extends('layouts.public')
@section('title', ($company->company_name ?? 'rhantech') . ' - Temukan Produk Digital Terbaik')
@section('content')

<style>
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
    mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, transparent 0%, black 8%, black 92%, transparent 100%);
}
.hero-search-input {
    width: 100%; height: 52px;
    padding: 0 56px 0 20px;
    border-radius: 16px;
    border: 2px solid #e2e8f0;
    background: white;
    font-size: 15px; outline: none;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    transition: border-color 0.2s, box-shadow 0.2s;
    color: inherit;
}
.dark .hero-search-input { background: #1e293b; color: #f1f5f9; border-color: #334155; }
.hero-search-input:focus { border-color: #00b3cc; box-shadow: 0 4px 24px rgba(0,179,204,0.18); }
.hero-search-btn {
    position: absolute; right: 6px; top: 6px;
    height: 40px; width: 40px; border-radius: 12px;
    background: linear-gradient(135deg, #00b3cc, #0077a8);
    border: none; color: #fff;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: opacity 0.2s;
}
.hero-search-btn:hover { opacity: 0.88; }
.store-slide-card {
    display: flex; flex-direction: row; align-items: center; gap: 14px;
    background: white;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px; padding: 14px 18px;
    min-width: 240px; max-width: 260px;
    text-decoration: none;
    transition: all 0.22s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    flex-shrink: 0;
}
.dark .store-slide-card { background: #1e293b; border-color: #334155; }
.store-slide-card:hover { border-color: #00b3cc; box-shadow: 0 6px 24px rgba(0,179,204,0.15); transform: translateY(-3px); }
.store-slide-logo { width: 48px; height: 48px; border-radius: 10px; object-fit: cover; flex-shrink: 0; border: 1.5px solid #e2e8f0; }
.store-slide-logo-fallback {
    width: 48px; height: 48px; border-radius: 10px;
    background: linear-gradient(135deg, #00b3cc, #0077a8);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 20px; flex-shrink: 0;
}
</style>

{{-- HERO --}}
<section class="relative flex flex-col items-center justify-center text-center px-4 pt-10 pb-8 md:pt-16 md:pb-12 overflow-hidden" id="home">
    <div class="absolute inset-0 -z-10">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[400px] rounded-full blur-[100px] opacity-30" style="background: radial-gradient(circle, #00b3cc, transparent);"></div>
    </div>

    <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-surface-container border border-outline-variant text-on-surface-variant text-xs font-bold mb-5 shadow-xs">
        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
        Ribuan Produk Digital Tersedia
    </span>

    <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-on-background dark:text-white mb-3 leading-tight max-w-3xl">
        Cari &amp; Beli Produk Digital
        <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00b3cc] to-[#06B6D4]">Terbaik</span>
    </h1>
    <p class="text-sm text-on-surface-variant mb-6 max-w-lg">
        Source code, template, sistem informasi, dan aplikasi siap pakai dari developer terpercaya.
    </p>

    <div class="relative w-full max-w-xl mx-auto mb-5">
        <form action="{{ route('products.index') }}" method="GET">
            <input class="hero-search-input" type="text" name="search"
                   placeholder="Cari produk, source code, aplikasi..."
                   value="{{ request('search') }}" autocomplete="off">
            <button type="submit" class="hero-search-btn" aria-label="Cari">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </button>
        </form>
    </div>

    <div class="flex flex-wrap justify-center gap-2 text-xs mb-6">
        @foreach(['Laravel', 'CodeIgniter', 'Flutter', 'React Native', 'Kasir'] as $tag)
        <a href="{{ route('products.index', ['search' => $tag]) }}"
           class="px-3 py-1.5 rounded-full bg-surface-container border border-outline-variant text-on-surface-variant hover:border-primary hover:text-primary transition-all font-medium">
            {{ $tag }}
        </a>
        @endforeach
    </div>

    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-xl text-sm hover:opacity-90 transition-all shadow-lg hover:-translate-y-0.5"
           wire:navigate>
            <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
            Belanja Sekarang
        </a>
        @guest
        <a href="{{ route('register') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-surface border-2 border-outline-variant text-on-surface font-bold rounded-xl text-sm hover:border-primary hover:text-primary transition-all"
           wire:navigate>
            <span class="material-symbols-outlined text-[18px]">storefront</span>
            Buka Toko Gratis
        </a>
        @endguest
    </div>
</section>

{{-- STORE MARQUEE SLIDER --}}
@if(isset($topStores) && $topStores->count() > 0)
<section class="py-5 md:py-7 border-t border-outline-variant/30">
    <div class="px-4 md:px-6 max-w-[1280px] mx-auto mb-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="p-1.5 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px] text-emerald-600">storefront</span>
            </span>
            <h2 class="text-sm font-black text-on-background dark:text-white">Toko Pilihan</h2>
        </div>
        <a href="{{ route('products.index') }}" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-0.5" wire:navigate>
            Lihat Semua <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
        </a>
    </div>
    <div class="store-marquee-wrap">
        <div class="store-marquee-track gap-3" style="padding: 4px 16px;">
            @for($loop = 0; $loop < 2; $loop++)
                @foreach($topStores as $store)
                <a href="{{ route('store.show', $store->slug) }}" class="store-slide-card" wire:navigate>
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="store-slide-logo"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div class="store-slide-logo-fallback" style="display:none;">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
                    @else
                        <div class="store-slide-logo-fallback">{{ strtoupper(substr($store->name, 0, 1)) }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="font-black text-sm text-on-background dark:text-white truncate leading-tight">{{ $store->name }}</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $store->products_count ?? 0 }} Produk @if($store->sales_count) · {{ $store->sales_count }} Terjual @endif</p>
                        <span class="inline-flex items-center gap-1 mt-1">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                            <span class="text-[10px] text-emerald-600 font-semibold">Aktif</span>
                        </span>
                    </div>
                </a>
                @endforeach
            @endfor
        </div>
    </div>
</section>
@endif

{{-- POPULAR PRODUCTS 2-COLUMN --}}
@if(isset($popularProducts) && $popularProducts->count() > 0)
<section class="py-5 md:py-7 px-4 md:px-6 max-w-[1280px] mx-auto">
    <div class="flex items-center justify-between gap-2 mb-4">
        <div class="flex items-center gap-2">
            <span class="p-1.5 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[18px]">local_fire_department</span>
            </span>
            <h2 class="text-sm font-black text-on-background dark:text-white">Produk Populer</h2>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1 text-[11px] font-bold text-primary hover:underline shrink-0" wire:navigate>
            Lihat Semua <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
        </a>
    </div>
    <div class="grid grid-cols-2 gap-2 md:gap-3">
        @foreach($popularProducts as $prod)
        @php
            $mainImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first();
            $hasDiscount = $prod->discount_price && $prod->discount_price > 0 && $prod->discount_price < $prod->price;
            $effectivePrice = $hasDiscount ? $prod->discount_price : $prod->price;
            $ratingDisplay = $prod->effective_rating;
        @endphp
        <a href="{{ route('products.show', $prod->slug) }}"
           class="group bg-surface rounded-xl border border-outline-variant hover:border-primary/40 overflow-hidden shadow-xs hover:shadow-md transition-all duration-200 flex flex-col hover:-translate-y-0.5"
           wire:navigate>
            <div class="relative aspect-square w-full bg-surface-container overflow-hidden">
                @if($mainImg)
                    <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $prod->name }}"
                         class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300" loading="lazy">
                @else
                    <div class="w-full h-full flex items-center justify-center text-outline-variant bg-surface-container-high">
                        <span class="material-symbols-outlined text-3xl">inventory_2</span>
                    </div>
                @endif
                @if($hasDiscount)
                <span class="absolute top-1.5 left-1.5 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded shadow">
                    -{{ round((($prod->price - $prod->discount_price) / $prod->price) * 100) }}%
                </span>
                @endif
                <div class="absolute bottom-1.5 right-1.5 bg-black/60 backdrop-blur-xs text-white text-[9px] font-medium px-1.5 py-0.5 rounded flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[10px]">visibility</span>
                    <span>{{ number_format($prod->views ?? 0) }}</span>
                </div>
            </div>
            <div class="p-2 flex flex-col flex-1">
                <h3 class="text-xs font-semibold text-on-background dark:text-white line-clamp-2 leading-snug group-hover:text-primary transition-colors mb-1">
                    {{ $prod->name }}
                </h3>
                @if($hasDiscount)
                <p class="text-[10px] text-on-surface-variant line-through leading-none mb-0.5">Rp{{ number_format($prod->price, 0, ',', '.') }}</p>
                @endif
                <div class="flex items-end justify-between mt-auto pt-1 border-t border-outline-variant/40">
                    <p class="text-xs font-black text-primary leading-tight">Rp{{ number_format($effectivePrice, 0, ',', '.') }}</p>
                    <span class="flex items-center gap-0.5 text-amber-500 font-bold text-[10px]">
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
@endsection
