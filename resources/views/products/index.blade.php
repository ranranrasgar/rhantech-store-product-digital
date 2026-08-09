@extends('layouts.public')
@section('title', 'Digital Products Store')
@section('content')
<main class="pt-24 pb-2xl min-h-screen bg-surface dark:bg-surface-container-lowest">
    <div class="max-w-container-max mx-auto px-lg">
        <div class="text-center mb-xl">
            <h1 class="font-headline-lg text-headline-lg font-black text-primary dark:text-on-primary-container mb-2">Digital Products</h1>
            <p class="font-body-lg text-on-surface-variant max-w-2xl mx-auto">High-quality source codes, scripts, and digital assets ready for your next project.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-xl">
            <!-- Sidebar Filters -->
            <aside class="w-full lg:w-1/4">
                <div class="bg-surface-container-low p-6 rounded-2xl border border-outline-variant sticky top-28">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="material-symbols-outlined text-primary">filter_list</span>
                        <h2 class="font-headline-sm font-bold text-on-surface">Filters</h2>
                    </div>
                    <form action="{{ route('products.index') }}" method="GET" class="flex flex-col gap-5">
                        <div>
                            <label for="search" class="block font-label-sm font-bold text-on-surface-variant mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by name..." class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label for="category" class="block font-label-sm font-bold text-on-surface-variant mb-1">Category</label>
                            <select name="category" id="category" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="type" class="block font-label-sm font-bold text-on-surface-variant mb-1">Type</label>
                            <select name="type" id="type" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label for="min_price" class="block font-label-sm font-bold text-on-surface-variant mb-1">Min Price</label>
                                <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <div class="flex-1">
                                <label for="max_price" class="block font-label-sm font-bold text-on-surface-variant mb-1">Max Price</label>
                                <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                        </div>
                        <div>
                            <label for="sort" class="block font-label-sm font-bold text-on-surface-variant mb-1">Sort By</label>
                            <select name="sort" id="sort" class="w-full bg-surface-container border border-outline-variant rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>Best Seller</option>
                            </select>
                        </div>
                        
                        <div class="flex flex-col gap-2 mt-2">
                            <button type="submit" class="w-full py-2.5 bg-primary text-on-primary font-bold rounded-lg hover:bg-primary/90 transition-colors">Apply Filter</button>
                            @if(request()->hasAny(['search', 'category', 'type', 'min_price', 'max_price', 'sort']))
                            <a href="{{ route('products.index') }}" class="w-full py-2.5 text-on-surface-variant bg-surface border border-outline-variant font-bold hover:bg-surface-container rounded-lg transition-colors text-center">Clear Filters</a>
                            @endif
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Main Content (Products Grid) -->
            <div class="w-full lg:w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
                    @forelse($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="group block bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-video w-full bg-surface-container-high relative overflow-hidden">
                            @if($product->images->count() > 0)
                                @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl">code</span>
                                </div>
                            @endif
                            @if($product->discount_price)
                                <div class="absolute top-2 right-2 bg-error text-white font-bold font-label-sm px-2 py-1 rounded">SALE</div>
                            @endif
                        </div>
                        <div class="p-4 flex flex-col h-[calc(100%-auto)]">
                            <h2 class="font-headline-sm font-bold text-on-surface line-clamp-1 mb-1 group-hover:text-[#06B6D4] transition-colors">{{ $product->name }}</h2>
                            <div class="flex gap-2 mb-2">
                                @if($product->category)
                                <span class="bg-secondary-container text-on-secondary-container text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $product->category->name }}</span>
                                @endif
                                @if($product->type)
                                <span class="bg-tertiary-container text-on-tertiary-container text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">{{ $product->type->name }}</span>
                                @endif
                            </div>
                            <p class="font-body-sm text-on-surface-variant line-clamp-2 mb-4">{{ $product->description }}</p>
                            
                            <div class="flex items-center justify-between mt-auto">
                                <div>
                                    @if($product->discount_price)
                                        <div class="font-bold text-on-surface text-lg">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                        <div class="text-xs text-on-surface-variant line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    @else
                                        <div class="font-bold text-on-surface text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                                <div class="w-8 h-8 rounded-full bg-secondary-container text-on-secondary-container flex items-center justify-center group-hover:bg-[#06B6D4] group-hover:text-white transition-colors">
                                    <span class="material-symbols-outlined text-sm">shopping_cart</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full py-12 text-center text-on-surface-variant bg-surface-container-low rounded-2xl border border-outline-variant">
                        <span class="material-symbols-outlined text-5xl opacity-50 mb-4 block">inventory_2</span>
                        <h3 class="font-headline-sm font-bold mb-2">No products available yet.</h3>
                        <p>Check back later for new digital products and source codes.</p>
                    </div>
                    @endforelse
                </div>
                
                <div class="mt-xl">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
