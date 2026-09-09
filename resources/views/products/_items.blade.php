@foreach($products as $product)
@php
    $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first();
    $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
    $price = $hasDiscount ? $product->discount_price : $product->price;
    $catName = $product->category ? $product->category->name : 'Aplikasi';
    $imgAlt = 'Jual ' . $product->name . ' - Source Code ' . $catName . ' Siap Pakai';
@endphp
<a href="{{ route('products.show', $product->slug) }}" class="prod-card group bg-white dark:bg-[#1e293b] border border-gray-100 dark:border-slate-700/60">

    {{-- Image --}}
    <div class="img-wrap">
        @if($mainImg)
            <img src="{{ asset('storage/' . $mainImg->image_path) }}" 
                 alt="{{ $imgAlt }}" 
                 title="{{ $product->name }}" 
                 loading="lazy">
        @else
            <div class="no-img flex items-center justify-center text-gray-300 bg-gray-50 dark:bg-gray-800">
                <span class="material-symbols-outlined text-5xl">inventory_2</span>
            </div>
        @endif
        <div class="img-overlay">
            <span class="text-white text-xs font-semibold bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">
                Lihat Detail
            </span>
        </div>

        {{-- Iklan / Sponsored Badge Biru Langit (Shopee Style) --}}
        @if($product->relationLoaded('activeAd') && $product->activeAd)
        <div class="absolute top-2 right-2 bg-gradient-to-r from-slate-900/95 to-slate-800/95 backdrop-blur-md text-white text-[10px] font-extrabold px-2.5 py-0.5 rounded-full shadow-md flex items-center gap-1.5 z-10 border border-sky-400/50">
            <span class="w-2 h-2 rounded-full bg-[#0284c7] shadow-[0_0_8px_#38bdf8] animate-pulse"></span>
            <span class="text-sky-300 uppercase tracking-wider text-[9px]">Iklan</span>
        </div>
        @endif

        {{-- Discount badge --}}
        @if($hasDiscount)
        <div class="absolute top-2 left-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
        </div>
        @endif

        {{-- Views Badge --}}
        @if(($product->views ?? 0) > 0)
        <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-xs text-white text-[10px] font-medium px-2 py-0.5 rounded-md flex items-center gap-1">
            <span class="material-symbols-outlined text-[12px]">visibility</span>
            <span>{{ number_format($product->views) }}</span>
        </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="p-2 md:p-3 flex flex-col flex-1">
        <h3 class="text-xs md:text-sm text-gray-900 dark:text-white font-semibold line-clamp-2 leading-snug mb-1 md:mb-1.5 group-hover:text-primary transition-colors flex-1">
            {{ $product->name }}
        </h3>

        {{-- Store Name Snippet - hidden on mobile --}}
        <div class="hidden md:flex items-center gap-1.5 mb-1.5 text-[11px] text-gray-500 dark:text-gray-400">
            <span class="material-symbols-outlined text-[13px] text-primary">storefront</span>
            <span class="truncate font-medium hover:underline">{{ $product->store ? $product->store->name : ($company->company_name ?? 'Official Store') }}</span>
        </div>

        @php
            $shortCatalogDesc = !empty($product->short_description) 
                ? trim($product->short_description) 
                : Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($product->description ?? ''))), 70);
            $soldCountCatalog = (int)($product->sales_count ?: ($product->orders_count ?? 0));
            $displaySoldCatalog = $soldCountCatalog;
        @endphp
        {{-- Short description - hidden on mobile --}}
        @if(!empty($shortCatalogDesc))
            <p class="hidden md:block text-[11px] text-gray-400 dark:text-gray-400 line-clamp-2 mb-2 leading-relaxed font-normal" title="{{ $shortCatalogDesc }}">
                {{ $shortCatalogDesc }}
            </p>
        @endif

        @if($hasDiscount)
        <p class="text-[10px] md:text-xs text-gray-400 line-through mb-0.5">
            Rp{{ number_format($product->price, 0, ',', '.') }}
        </p>
        @endif

        <div class="flex items-end justify-between mt-auto pt-1 md:pt-1.5 border-t border-gray-100 dark:border-gray-800">
            <p class="text-primary font-extrabold text-sm md:text-base leading-none">
                Rp{{ number_format($price, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1 md:gap-2 text-[9px] md:text-[10px] text-gray-400">
                {{-- Sold count - hidden on mobile to save space --}}
                <span class="hidden md:inline bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold px-1.5 py-0.5 rounded">
                    {{ $displaySoldCatalog }} Terjual
                </span>
                @if($product->effective_rating > 0)
                <span class="flex items-center gap-0.5 text-amber-500 font-bold">
                    <span class="material-symbols-outlined text-[10px] md:text-[11px] fill-current">star</span>
                    <span>{{ number_format($product->effective_rating, 1) }}</span>
                </span>
                @else
                <span class="text-[9px] px-1.5 py-0.5 bg-primary/10 text-primary font-bold rounded">
                    Baru
                </span>
                @endif
            </div>
        </div>
    </div>
</a>
@endforeach
