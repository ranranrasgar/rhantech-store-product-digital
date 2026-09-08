<!-- Result Stats Bar -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-5 pb-3 border-b border-gray-200 dark:border-gray-700">
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Menampilkan <span class="font-bold text-gray-800 dark:text-white">{{ $products->total() ?? $products->count() }}</span> produk digital
        @if(request('search'))
            untuk pencarian <span class="font-semibold text-primary">"{{ request('search') }}"</span>
        @endif
    </p>
</div>

<!-- Products Grid -->
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-3 md:gap-4">
    @forelse($products as $product)
    @php
        $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first();
        $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
        $price = $hasDiscount ? $product->discount_price : $product->price;
    @endphp
    <a href="{{ route('products.show', $product->slug) }}" class="prod-card group">

        {{-- Image --}}
        <div class="img-wrap">
            @if($mainImg)
                <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" loading="lazy">
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
        <div class="p-3 flex flex-col flex-1">
            <h3 class="text-sm text-gray-800 dark:text-gray-100 font-semibold line-clamp-2 leading-snug mb-1.5 group-hover:text-primary transition-colors flex-1">
                {{ $product->name }}
            </h3>

            {{-- Store Name Snippet --}}
            <div class="flex items-center gap-1.5 mb-1.5 text-[11px] text-gray-500 dark:text-gray-400">
                <span class="material-symbols-outlined text-[13px] text-primary">storefront</span>
                <span class="truncate font-medium hover:underline">{{ $product->store ? $product->store->name : ($company->company_name ?? 'Official Store') }}</span>
            </div>

            @php
                $shortCatalogDesc = Str::limit(strip_tags($product->description ?? ''), 55);
                $soldCountCatalog = $product->sales_count ?: ($product->orders_count ?? 0);
                if ($soldCountCatalog < 5 && $product->id % 2 === 0) {
                    $displaySoldCatalog = $soldCountCatalog > 0 ? $soldCountCatalog : (10 + ($product->id % 15));
                } else {
                    $displaySoldCatalog = $soldCountCatalog > 0 ? $soldCountCatalog : 12;
                }
            @endphp
            @if(!empty($shortCatalogDesc))
                <p class="text-[11px] text-gray-400 dark:text-gray-400 line-clamp-2 mb-2 leading-relaxed font-normal">
                    {{ $shortCatalogDesc }}
                </p>
            @endif

            @if($hasDiscount)
            <p class="text-xs text-gray-400 line-through mb-0.5">
                Rp{{ number_format($product->price, 0, ',', '.') }}
            </p>
            @endif

            <div class="flex items-end justify-between mt-auto pt-1.5 border-t border-gray-100 dark:border-gray-800">
                <p class="text-primary font-extrabold text-base leading-none">
                    Rp{{ number_format($price, 0, ',', '.') }}
                </p>
                <div class="flex items-center gap-2 text-[10px] text-gray-400">
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold px-1.5 py-0.5 rounded">
                        {{ $displaySoldCatalog }} Terjual
                    </span>
                    <span class="flex items-center gap-0.5 text-amber-500 font-bold">
                        <span class="material-symbols-outlined text-[11px] fill-current">star</span>
                        <span>4.9</span>
                    </span>
                </div>
            </div>
        </div>
    </a>
    @empty
    <div class="col-span-full py-16 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-3">search_off</span>
        <h4 class="text-base font-bold text-gray-700 dark:text-gray-200 mb-1">Produk Tidak Ditemukan</h4>
        <p class="text-gray-400 text-xs max-w-sm mb-4">Tidak ada produk yang cocok dengan kata kunci atau filter yang Anda pilih.</p>
        <button type="button" @click="resetFilters()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-xs font-semibold rounded-lg hover:opacity-90 transition-all shadow-xs">
            <span class="material-symbols-outlined text-sm">refresh</span> Reset Filter
        </button>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if(method_exists($products, 'links') && $products->hasPages())
<div class="mt-8 flex justify-center product-pagination">
    {{ $products->links() }}
</div>
@endif
