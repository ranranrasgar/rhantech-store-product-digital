<!-- Result Stats Bar - hidden on mobile -->
<div class="hidden md:flex flex-wrap items-center justify-between gap-3 mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
    <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400">
        Menampilkan <span class="font-bold text-gray-800 dark:text-white">{{ $products->total() ?? $products->count() }}</span> produk digital
        @if(request('search'))
            untuk pencarian <span class="font-semibold text-primary">"{{ request('search') }}"</span>
        @endif
    </p>
</div>

<!-- Products Grid -->
<div id="products-items-grid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-2 md:gap-4"
     data-current-page="{{ $products->currentPage() }}"
     data-last-page="{{ $products->lastPage() }}"
     data-has-more="{{ $products->hasMorePages() ? '1' : '0' }}">
    @if($products->count() > 0)
        @include('products._items', ['products' => $products])
    @else
        <div class="col-span-full py-16 flex flex-col items-center text-center bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <span class="material-symbols-outlined text-6xl text-gray-300 dark:text-gray-600 mb-3">search_off</span>
            <h4 class="text-base font-bold text-gray-700 dark:text-gray-200 mb-1">Produk Tidak Ditemukan</h4>
            <p class="text-gray-400 text-xs max-w-sm mb-4">Tidak ada produk yang cocok dengan kata kunci atau filter yang Anda pilih.</p>
            <button type="button" @click="resetFilters()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary text-white text-xs font-semibold rounded-lg hover:opacity-90 transition-all shadow-xs">
                <span class="material-symbols-outlined text-sm">refresh</span> Reset Filter
            </button>
        </div>
    @endif
</div>

{{-- Sentinel & Loading Indicator for Infinite Scroll --}}
<div id="infinite-scroll-sentinel" class="py-6 flex flex-col items-center justify-center">
    <div x-show="loadingMore" x-cloak class="flex items-center gap-2 text-xs font-bold text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 px-4 py-2 rounded-full border border-gray-200 dark:border-gray-700 shadow-sm">
        <svg class="animate-spin h-4 w-4 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>
        <span>Memuat produk lainnya...</span>
    </div>
    <template x-if="!hasMorePages && !loading && !loadingMore && currentPage > 1">
        <p class="text-[11px] text-gray-400 dark:text-gray-500 italic mt-2">Semua produk sudah ditampilkan</p>
    </template>
</div>

{{-- Pagination (Desktop Fallback / Explicit Page Navigation) --}}
@if(method_exists($products, 'links') && $products->hasPages())
<div class="hidden md:flex justify-center product-pagination pb-6">
    {{ $products->links() }}
</div>
@endif

