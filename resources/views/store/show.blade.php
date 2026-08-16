@extends('layouts.public')

@section('title', $store->name)

@section('content')
<main class="min-h-screen bg-surface-container-lowest">
    
    <!-- Store Header Banner -->
    <div class="relative w-full min-h-[350px] bg-[#1a1a1a]">
        <!-- Background Image (Mock or real if available) -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?w=1200&h=400&fit=crop')] bg-cover bg-center opacity-80"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pt-24 pb-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end gap-6">
                <!-- Avatar -->
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0D8ABC&color=fff&size=128" alt="{{ $store->name }}" class="w-full h-full object-cover">
                </div>
                
                <!-- Info -->
                <div class="flex-1 text-white">
                    <h1 class="text-3xl font-bold mb-2 drop-shadow-md">{{ $store->name }}</h1>
                    <div class="flex items-center gap-4 text-sm drop-shadow-md opacity-90">
                        <span class="flex items-center gap-1"><span class="text-yellow-400 text-lg">★</span> 4.8 (1.2k ulasan)</span>
                        <span>•</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">inventory_2</span> {{ $products->total() }} Produk</span>
                        <span>•</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">group</span> 1.6K Pengikut</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 mt-4 md:mt-0">
                    <button @click="window.dispatchEvent(new CustomEvent('open-chat-with-store', { 
                        detail: { 
                            store_id: {{ $store->id }}, 
                            store_name: '{{ addslashes($store->name) }}',
                            store_slug: '{{ $store->slug }}',
                            store_logo: '{{ $store->logo ? asset('storage/' . $store->logo) : '' }}'
                        } 
                    }))" class="px-6 py-2 bg-transparent border border-white text-white rounded font-bold hover:bg-white/20 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">chat</span> Chat
                    </button>
                    <button class="px-6 py-2 bg-primary border border-primary text-white rounded font-bold hover:bg-primary/90 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add</span> Ikuti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Store Navigation -->
    <div class="bg-white border-b border-outline-variant sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex overflow-x-auto hide-scrollbar">
            <a href="#" class="px-6 py-4 font-bold text-primary border-b-2 border-primary whitespace-nowrap">Beranda Toko</a>
            <a href="#" class="px-6 py-4 font-bold text-on-surface-variant hover:text-primary transition-colors whitespace-nowrap">Semua Produk</a>
            <a href="#" class="px-6 py-4 font-bold text-on-surface-variant hover:text-primary transition-colors whitespace-nowrap">Kategori</a>
            <a href="#" class="px-6 py-4 font-bold text-on-surface-variant hover:text-primary transition-colors whitespace-nowrap">Profil Toko</a>
        </div>
    </div>

    <!-- Store Content (Products Grid) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-on-surface border-l-4 border-primary pl-3">Semua Produk</h2>
            
            <div class="flex items-center gap-2">
                <span class="text-sm text-on-surface-variant">Urutkan:</span>
                <select class="text-sm border border-outline-variant rounded px-3 py-1.5 focus:outline-none focus:border-primary">
                    <option>Terbaru</option>
                    <option>Terlaris</option>
                    <option>Harga Termurah</option>
                    <option>Harga Termahal</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @forelse($products as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="group bg-white border border-outline-variant hover:border-primary rounded overflow-hidden hover:shadow-lg transition-all flex flex-col">
                <div class="aspect-square w-full bg-surface-container-high relative overflow-hidden">
                    @if($product->images->count() > 0)
                        @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                        <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl">inventory_2</span>
                        </div>
                    @endif
                    
                    @if($product->discount_price)
                        <div class="absolute top-0 right-0 bg-error text-white font-bold text-[10px] px-2 py-1 rounded-bl-lg">SALE</div>
                    @endif
                </div>
                <div class="p-3 flex flex-col flex-1">
                    <h3 class="font-bold text-on-surface text-sm line-clamp-2 mb-2 group-hover:text-primary transition-colors">{{ $product->name }}</h3>
                    
                    <div class="mt-auto">
                        @if($product->discount_price)
                            <div class="text-xs text-on-surface-variant line-through mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="font-bold text-primary text-base">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                        @else
                            <div class="font-bold text-primary text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @endif
                        
                        <div class="flex items-center gap-1 mt-2 text-[10px] text-on-surface-variant">
                            <span class="text-yellow-400 material-symbols-outlined text-[12px]">star</span> 5.0
                            <span class="mx-1">•</span>
                            10 Terjual
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-12 text-center text-on-surface-variant">
                <span class="material-symbols-outlined text-5xl opacity-50 mb-4 block">inventory_2</span>
                <p class="font-bold text-lg text-on-surface mb-1">Toko ini belum memiliki produk aktif.</p>
                <p class="text-sm">Silakan kunjungi lagi nanti.</p>
            </div>
            @endforelse
        </div>
        
        <div class="mt-8">
            {{ $products->links() }}
        </div>

    </div>

</main>
@endsection
