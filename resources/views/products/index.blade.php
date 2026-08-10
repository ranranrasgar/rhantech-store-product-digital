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

    {{-- ── HERO STRIP ── --}}
    <div class="hero-strip p-5 md:p-8 mb-6 flex flex-col md:flex-row items-center gap-4 md:gap-8">
        <div class="flex-1 text-white">
            <p class="text-xs md:text-sm font-semibold uppercase tracking-widest opacity-80 mb-1">Platform Produk Digital</p>
            <h1 class="text-2xl md:text-4xl font-extrabold leading-tight mb-3">
                Temukan Solusi<br class="hidden md:block"> Digital Terbaik Anda
            </h1>
            <p class="text-sm md:text-base opacity-80 max-w-md">Source code, aplikasi, template & layanan digital lainnya — semua ada di sini.</p>
        </div>
        <div class="flex flex-wrap gap-3 justify-center md:justify-end shrink-0">
            @php
                $quickLinks = [
                    ['icon' => 'phone_iphone',          'text' => 'Pulsa'],
                    ['icon' => 'wifi',                  'text' => 'Paket Data'],
                    ['icon' => 'electric_bolt',         'text' => 'Listrik PLN'],
                    ['icon' => 'water_drop',            'text' => 'PDAM'],
                    ['icon' => 'health_and_safety',     'text' => 'BPJS'],
                    ['icon' => 'sports_esports',        'text' => 'Voucher Game'],
                    ['icon' => 'account_balance_wallet','text' => 'E-Wallet'],
                    ['icon' => 'confirmation_number',   'text' => 'Tiket'],
                ];
            @endphp
            @foreach($quickLinks as $link)
            <a href="#" class="svc-card w-[70px] md:w-[80px] bg-white/10 hover:bg-white text-white hover:text-primary border border-white/20 hover:border-transparent rounded-xl py-3 px-2 flex flex-col items-center gap-1 text-[11px] font-semibold transition-all">
                <span class="material-symbols-outlined text-[24px] text-white" style="color:inherit">{{ $link['icon'] }}</span>
                {{ $link['text'] }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── BANNER ROW ── --}}
    <div class="flex gap-3 mb-6 h-[200px] md:h-[300px]">
        @if(isset($banners) && $banners->has('main'))
            @php $mainBanner = $banners->get('main'); @endphp
            <a href="{{ $mainBanner->link ?? '#' }}" class="flex-[2] overflow-hidden shadow-md relative group cursor-pointer block">
                <img src="{{ asset('storage/' . $mainBanner->image_path) }}" alt="{{ $mainBanner->title ?? 'Banner Utama' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>
            </a>
        @else
            <div class="flex-[2] overflow-hidden shadow-md relative group cursor-pointer bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                <span class="text-gray-400">Banner Utama (Kiri)</span>
            </div>
        @endif

        <div class="flex-1 flex flex-col gap-3">
            @if(isset($banners) && $banners->has('side_1'))
                @php $side1 = $banners->get('side_1'); @endphp
                <a href="{{ $side1->link ?? '#' }}" class="flex-1 overflow-hidden shadow-md cursor-pointer group block">
                    <img src="{{ asset('storage/' . $side1->image_path) }}" alt="{{ $side1->title ?? 'Banner Samping Atas' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @else
                <div class="flex-1 overflow-hidden shadow-md cursor-pointer group bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                    <span class="text-gray-400 text-xs">Samping Atas</span>
                </div>
            @endif

            @if(isset($banners) && $banners->has('side_2'))
                @php $side2 = $banners->get('side_2'); @endphp
                <a href="{{ $side2->link ?? '#' }}" class="flex-1 overflow-hidden shadow-md cursor-pointer group block">
                    <img src="{{ asset('storage/' . $side2->image_path) }}" alt="{{ $side2->title ?? 'Banner Samping Bawah' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
            @else
                <div class="flex-1 overflow-hidden shadow-md cursor-pointer group bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                    <span class="text-gray-400 text-xs">Samping Bawah</span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── CATEGORY CHIPS ── --}}
    @if($categories->count() > 0)
    <div class="mb-6">
        <div class="section-title text-base">
            <span class="accent"></span>
            Kategori
        </div>
        <div class="flex gap-2 overflow-x-auto hide-scrollbar pb-1">
            <a href="{{ route('products.index') }}"
               class="cat-chip {{ !request('category') ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[16px]">apps</span>
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('products.index', ['category' => $cat->id]) }}"
               class="cat-chip {{ request('category') == $cat->id ? 'active' : '' }}">
                <span class="material-symbols-outlined text-[16px]">folder</span>
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── PRODUCTS GRID ── --}}
    <div>
        <div class="section-title">
            <span class="accent"></span>
            {{ request('category') ? ($categories->find(request('category'))->name ?? 'Produk') : 'Semua Produk' }}
            <span class="text-sm font-normal text-gray-400">({{ $products->total() ?? $products->count() }} produk)</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">
            @forelse($products as $product)
            @php
                $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first();
                $price = $product->discount_price ?? $product->price;
            @endphp
            <a href="{{ route('products.show', $product->slug) }}" class="prod-card" wire:navigate>

                {{-- Image --}}
                <div class="img-wrap">
                    @if($mainImg)
                        <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div class="no-img flex items-center justify-center text-gray-300 bg-gray-50">
                            <span class="material-symbols-outlined text-5xl">inventory_2</span>
                        </div>
                    @endif
                    <div class="img-overlay">
                        <span class="text-white text-xs font-semibold bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                            Lihat Detail
                        </span>
                    </div>

                    {{-- Discount badge --}}
                    @if($product->discount_price && $product->price > $product->discount_price)
                    <div class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                        -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-3 flex flex-col flex-1">
                    <p class="text-sm text-gray-800 font-medium line-clamp-2 leading-snug mb-2 flex-1">
                        {{ $product->name }}
                    </p>

                    @if($product->discount_price)
                    <p class="text-xs text-gray-400 line-through mb-0.5">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </p>
                    @endif

                    <div class="flex items-end justify-between mt-auto">
                        <p class="text-primary font-bold text-base leading-none">
                            Rp{{ number_format($price, 0, ',', '.') }}
                        </p>
                        <p class="text-[10px] text-gray-400">
                            {{ number_format(rand(10, 9999)) }} terjual
                        </p>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-20 flex flex-col items-center text-center">
                <span class="material-symbols-outlined text-7xl text-gray-200 mb-4">search_off</span>
                <p class="text-gray-400 font-medium text-lg mb-1">Produk belum tersedia</p>
                <p class="text-gray-300 text-sm">Coba kategori lain atau cek kembali nanti.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
</main>

@endsection
