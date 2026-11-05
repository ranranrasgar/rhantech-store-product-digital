{{-- Etalase produk pada halaman brand --}}
@php
    $activeCategory = request('category');
    $hasFilter = request()->anyFilled(['q', 'search', 'category']);
    $storeUrl = route('store.show', $store->slug);
@endphp
<section class="bp-section" id="produk">
    <h2 class="bp-title">Produk <small>{{ $products->total() }}</small></h2>

    <form method="GET" action="{{ $storeUrl }}#produk" class="bp-tools">
        @if($activeCategory)
            <input type="hidden" name="category" value="{{ $activeCategory }}">
        @endif
        <label class="bp-search">
            <span class="sr-only">Cari produk</span>
            <i class="ph ph-magnifying-glass"></i>
            <input type="search" name="q" value="{{ request('q', request('search')) }}" placeholder="Cari di {{ $store->name }}">
        </label>
        <select name="sort" class="bp-sort" aria-label="Urutkan produk" onchange="this.form.requestSubmit()">
            <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
            <option value="popular" @selected(request('sort') === 'popular')>Terpopuler</option>
            <option value="price_asc" @selected(request('sort') === 'price_asc')>Termurah</option>
            <option value="price_desc" @selected(request('sort') === 'price_desc')>Termahal</option>
        </select>
    </form>

    @if($categories->count() > 1)
        <nav class="bp-chips" aria-label="Kategori produk">
            <a href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => null]) }}#produk" class="bp-chip {{ $activeCategory ? '' : 'is-active' }}">Semua</a>
            @foreach($categories as $category)
                <a href="{{ request()->fullUrlWithQuery(['category' => $category->id, 'page' => null]) }}#produk"
                   class="bp-chip {{ (string) $activeCategory === (string) $category->id ? 'is-active' : '' }}">{{ $category->name }}</a>
            @endforeach
        </nav>
    @endif

    @if($products->isEmpty())
        <div class="bp-empty">
            {{ $hasFilter ? 'Tidak ada produk yang cocok.' : 'Belum ada produk aktif.' }}
            @if($hasFilter)
                <br><a href="{{ $storeUrl }}#produk">Lihat semua produk</a>
            @endif
        </div>
    @else
        <div class="bp-grid">
            @foreach($products as $product)
                @php
                    $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first();
                    $hasDiscount = $product->discount_price > 0 && $product->discount_price < $product->price;
                    $reviewCount = $product->effective_reviews_count;
                @endphp
                <a href="{{ route('products.show', $product->slug) }}" class="bp-product">
                    <div class="bp-thumb">
                        @if($mainImg)
                            <img src="{{ \App\Support\ImageThumb::url($mainImg->image_path, 480) }}" alt="{{ $product->name }}" width="480" height="480" loading="{{ $loop->index < 2 ? 'eager' : 'lazy' }}" decoding="async">
                        @else
                            <div class="bp-thumb-empty"><i class="ph ph-package"></i></div>
                        @endif
                        @if($hasDiscount)
                            <span class="bp-off">-{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%</span>
                        @endif
                    </div>
                    <div class="bp-info">
                        <h3 class="bp-pname">{{ $product->name }}</h3>
                        @if($reviewCount > 0 && $product->effective_rating > 0)
                            <div class="bp-stars"><i class="ph ph-star"></i> {{ number_format($product->effective_rating, 1) }} ({{ $reviewCount }})</div>
                        @endif
                        <div class="bp-price">
                            @if($hasDiscount)
                                <s>Rp {{ number_format($product->price, 0, ',', '.') }}</s>
                                <b>Rp {{ number_format($product->discount_price, 0, ',', '.') }}</b>
                            @else
                                <b>{{ $product->price > 0 ? 'Rp ' . number_format($product->price, 0, ',', '.') : 'Gratis' }}</b>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="bp-pages">{{ $products->links() }}</div>
    @endif
</section>
