@extends('layouts.shopee')
@section('title', $product->name . ' | PPOB')
@section('content')

<!-- Global style overrides to match PPOB better -->
<style>
    body { background-color: #f5f5f5; }
    
    
    
    .text-xxs { font-size: 0.65rem; }
    .bg-primary-light { background-color: #fceceb; }
</style>

@php
    $imageUrls = $product->images->map(function($img) {
        return asset('storage/' . $img->image_path);
    })->values()->toJson();
@endphp
<main x-data="{ 
        showLightbox: false, 
        images: {{ $imageUrls }}, 
        currentIndex: 0,
        openLightbox(src) {
            let idx = this.images.indexOf(src);
            if(idx === -1) idx = 0;
            this.currentIndex = idx;
            this.showLightbox = true;
        },
        next() {
            if(this.images.length > 0) {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            }
        },
        prev() {
            if(this.images.length > 0) {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            }
        }
    }" 
    class="pt-[60px] md:pt-[100px] pb-12 min-h-screen font-sans">
    
    <div class="max-w-[1200px] mx-auto px-4">
        
        <!-- Breadcrumb -->
        <div class="flex items-center text-sm text-blue-600 mb-4 whitespace-nowrap overflow-hidden text-ellipsis">
            <a href="{{ url('/') }}" class="hover:underline text-gray-500">Katalog Produk</a>
            <span class="mx-2 text-gray-400">❯</span>
            @if($product->category)
                <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="hover:underline text-gray-500">{{ $product->category->name }}</a>
                <span class="mx-2 text-gray-400">❯</span>
            @endif
            @if($product->type)
                <a href="{{ route('products.index', ['type' => $product->type->id]) }}" class="hover:underline text-gray-500">{{ $product->type->name }}</a>
                <span class="mx-2 text-gray-400">❯</span>
            @endif
            <span class="text-gray-800">{{ $product->name }}</span>
        </div>

        <!-- Main Product Card -->
        <div class="bg-white rounded shadow-sm flex flex-col md:flex-row p-4 mb-4">
            
            <!-- Left: Images -->
            <div class="w-full md:w-[450px] shrink-0 md:mr-8 mb-4 md:mb-0">
                <div @click="openLightbox(document.getElementById('mainImage').src)" class="aspect-square w-full relative overflow-hidden mb-2 group cursor-zoom-in" id="image-container" onmousemove="zoomImage(event)" onmouseleave="resetZoomImage()">
                    @if($product->images->count() > 0)
                        @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                        <img id="mainImage" src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover origin-center transition-transform duration-75 ease-out group-hover:scale-[2]">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 border border-gray-200">
                            <span class="material-symbols-outlined text-6xl">inventory_2</span>
                        </div>
                    @endif
                </div>
                
                @if($product->images->count() > 1)
                <div class="flex gap-2 overflow-x-auto hide-scrollbar">
                    @foreach($product->images as $img)
                    <button onclick="document.getElementById('mainImage').src = '{{ asset('storage/' . $img->image_path) }}'" class="w-[82px] h-[82px] shrink-0 border border-transparent hover:border-primary focus:border-primary transition-colors overflow-hidden">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
                
            </div>

            <!-- Right: Details -->
            <div class="flex-1 flex flex-col pt-2 w-full">
                
                <!-- Title & Badge -->
                <h1 class="text-lg md:text-xl font-medium text-gray-800 leading-snug mb-2 flex items-start gap-2">
                    <span class="bg-primary text-white text-xs px-1.5 py-0.5 rounded-sm font-bold shrink-0 mt-1">Official</span>
                    <span>{{ $product->name }}</span>
                </h1>
                
                <!-- Ratings & Sales -->
                @php 
                    $rating = number_format(rand(40, 50) / 10, 1);
                    $sold = rand(10, 1000);
                    $reviews = rand(5, 500);
                @endphp
                <div class="flex flex-wrap items-center gap-2 md:gap-4 text-sm mb-4">
                    <div class="flex items-center gap-1 text-primary border-r border-gray-300 pr-2 md:pr-4">
                        <span class="font-medium underline underline-offset-4">{{ $rating }}</span>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-[14px] text-primary filled">{{ $i <= round($rating) ? 'star' : 'star_border' }}</span>
                            @endfor
                        </div>
                    </div>
                    <div class="flex items-center gap-1 border-r border-gray-300 pr-2 md:pr-4 text-gray-800">
                        <span class="font-medium underline underline-offset-4">{{ $reviews }}</span> <span class="text-gray-500 hidden md:inline">Penilaian</span>
                    </div>
                    <div class="flex items-center gap-1 text-gray-800">
                        <span class="font-medium">{{ $sold }}</span> <span class="text-gray-500">terjual</span>
                    </div>
                </div>

                <!-- Price Block -->
                <div class="bg-gray-50 p-3 md:p-4 rounded flex items-center gap-2 md:gap-3 mb-6 flex-wrap">
                    @if($product->discount_price)
                        <span class="text-gray-400 line-through text-sm md:text-base">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        <div class="text-primary text-2xl md:text-3xl font-medium">Rp{{ number_format($product->discount_price, 0, ',', '.') }}</div>
                        <span class="bg-surface-variant text-primary text-[10px] md:text-xs font-bold px-1.5 py-0.5 rounded-sm">{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}% OFF</span>
                    @else
                        <div class="text-primary text-2xl md:text-3xl font-medium">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                    @endif
                </div>



                <div x-data="productCart">
                    <!-- Quantity -->
                    <div class="flex items-center gap-4 mb-8">
                        <span class="text-sm text-gray-500 w-[70px] md:w-[100px] shrink-0">Kuantitas</span>
                        <div class="flex items-center">
                            <button type="button" @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center border border-gray-300 text-gray-600 hover:bg-gray-50">-</button>
                            <input type="text" x-model="qty" class="w-12 h-8 border-y border-gray-300 text-center text-sm focus:outline-none" readonly>
                            <button type="button" @click="if(qty < maxQty) qty++" class="w-8 h-8 flex items-center justify-center border border-gray-300 text-gray-600 hover:bg-gray-50">+</button>
                        </div>
                        <span class="text-xs md:text-sm text-gray-500 ml-2">Tersedia <span x-text="maxQty"></span> buah</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col md:flex-row gap-2 md:gap-4">
                        <button type="button"
                            @click="addToCart(false)"
                            :disabled="loading"
                            class="bg-surface-variant border border-primary text-primary px-4 md:px-8 py-3 rounded-sm flex items-center justify-center gap-2 hover:bg-surface-variant transition-colors text-sm md:text-base disabled:opacity-60">
                            <span class="material-symbols-outlined text-[20px]" x-text="loading ? 'hourglass_empty' : 'add_shopping_cart'">add_shopping_cart</span>
                            <span x-text="loading ? 'Menambahkan...' : 'Masukkan Keranjang'">Masukkan Keranjang</span>
                        </button>
                        <button type="button"
                            @click="addToCart(true)"
                            :disabled="loadingBuy"
                            class="bg-primary text-white px-4 md:px-12 py-3 rounded-sm flex items-center justify-center hover:bg-primary/90 transition-colors shadow-sm text-sm md:text-base disabled:opacity-60">
                            <span x-text="loadingBuy ? 'Memproses...' : 'Beli Sekarang'">Beli Sekarang</span>
                        </button>
                    </div>

                    <!-- Hidden form for Beli Sekarang redirect -->
                    <form id="buyNowForm" action="{{ route('checkout.select') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </div>

                <div class="mt-8 border-t border-gray-200 pt-4">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-4 h-4 bg-primary rounded-full flex items-center justify-center text-white"><span class="text-[10px]">✓</span></div>
                        Garansi 100% Ori <span class="text-gray-500 ml-1">Garansi uang kembali jika produk tidak ori</span>
                    </div>
                </div>

                @php $company = \App\Models\CompanyProfile::first(); @endphp
                <div class="flex items-center gap-2 md:gap-4 mt-2 flex-wrap" x-data="{ isFav: false, count: {{ rand(100, 500) }} }">
                    <span class="text-gray-600 text-sm">Share:</span>
                    @if($company && $company->facebook)
                        <a href="{{ $company->facebook }}" target="_blank" class="text-blue-600 hover:opacity-80"><span class="material-symbols-outlined">facebook</span></a>
                    @endif
                    @if($company && $company->instagram)
                        <a href="{{ $company->instagram }}" target="_blank" class="text-blue-400 hover:opacity-80"><span class="material-symbols-outlined">photo_camera</span></a>
                    @endif
                    @if($company && $company->youtube)
                        <a href="{{ $company->youtube }}" target="_blank" class="text-red-500 hover:opacity-80"><span class="material-symbols-outlined">play_circle</span></a>
                    @endif
                    
                    <div class="w-px h-4 bg-gray-300 mx-2 hidden md:block"></div>
                    <button @click="isFav = !isFav; isFav ? count++ : count--" class="flex items-center gap-1 text-gray-500 hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]" :class="isFav ? 'text-primary' : ''" x-text="isFav ? 'favorite' : 'favorite_border'">favorite_border</span>
                        <span class="text-sm">Favorit (<span x-text="count"></span>)</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Store Profile Block -->
        @if($product->store)
        <div class="bg-white rounded shadow-sm p-6 flex flex-col md:flex-row items-center justify-between mb-4 gap-6">
            <div class="flex items-center gap-4 md:border-r border-gray-200 md:pr-12 w-full md:w-auto">
                <div class="w-20 h-20 rounded-full border border-gray-200 overflow-hidden shrink-0 relative">
                    <img src="{{ $product->store->logo ? asset('storage/' . $product->store->logo) : 'https://ui-avatars.com/api/?name='.urlencode($product->store->name).'&background=random&color=fff' }}" class="w-full h-full object-cover">
                    <!-- Online indicator -->
                    <div class="w-3 h-3 bg-green-500 border-2 border-white rounded-full absolute bottom-1 right-1"></div>
                </div>
                <div class="flex flex-col">
                    <div class="font-medium text-gray-800 text-lg mb-1">{{ $product->store->name }}</div>
                    <div class="text-xs text-gray-500 mb-3">Aktif 3 menit lalu</div>
                    <div class="flex gap-2">
                        <a href="#" class="border border-primary bg-surface-variant text-primary px-3 py-1 text-sm flex items-center gap-1 rounded-sm"><span class="material-symbols-outlined text-[16px]">chat</span> Chat Sekarang</a>
                        <a href="{{ route('store.show', $product->store->slug) }}" class="border border-gray-300 text-gray-700 px-3 py-1 text-sm flex items-center gap-1 rounded-sm hover:bg-gray-50"><span class="material-symbols-outlined text-[16px]">storefront</span> Kunjungi Toko</a>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 grid grid-cols-2 md:grid-cols-3 gap-y-4 gap-x-8 text-sm text-gray-600">
                <div class="flex justify-between items-center"><span class="text-gray-400">Penilaian</span> <span class="text-primary">120,4RB</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-400">Persentase Chat Dibalas</span> <span class="text-primary">98%</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-400">Bergabung</span> <span class="text-primary">4 tahun lalu</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-400">Produk</span> <span class="text-primary">{{ $product->store->products()->count() ?? 100 }}</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-400">Waktu Chat Dibalas</span> <span class="text-primary">hitungan jam</span></div>
                <div class="flex justify-between items-center"><span class="text-gray-400">Pengikut</span> <span class="text-primary">1.2JT</span></div>
            </div>
        </div>
        @endif

        <!-- Description -->
        <div class="bg-white rounded shadow-sm">
            <div class="bg-gray-50 p-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-800">Deskripsi Produk</h2>
            </div>
            <div class="p-6 text-sm text-gray-800 font-sans leading-relaxed whitespace-pre-line">
                {{ $product->description }}
            </div>
        </div>
        
    </div>
    <!-- Lightbox Modal -->
    <div x-show="showLightbox" x-transition.opacity.duration.300ms style="display: none;"
         class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4 md:p-8 backdrop-blur-sm"
         @click.self="showLightbox = false"
         @keydown.window.escape="showLightbox = false"
         @keydown.window.arrow-right="if(showLightbox) next()"
         @keydown.window.arrow-left="if(showLightbox) prev()">
        
        <button @click="showLightbox = false" class="absolute top-4 right-4 md:top-6 md:right-6 text-white/70 hover:text-white transition-colors bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 flex items-center justify-center z-10 backdrop-blur">
            <span class="material-symbols-outlined text-2xl">close</span>
        </button>

        <button x-show="images.length > 1" @click.stop="prev()" class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 md:w-14 md:h-14 flex items-center justify-center z-10 backdrop-blur transition-colors">
            <span class="material-symbols-outlined text-2xl md:text-4xl">chevron_left</span>
        </button>
        
        <button x-show="images.length > 1" @click.stop="next()" class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 rounded-full w-10 h-10 md:w-14 md:h-14 flex items-center justify-center z-10 backdrop-blur transition-colors">
            <span class="material-symbols-outlined text-2xl md:text-4xl">chevron_right</span>
        </button>

        <img :src="images[currentIndex]" class="max-w-full max-h-full object-contain shadow-2xl transition-all duration-300 select-none" alt="Fullscreen" @click.self="showLightbox = false" x-transition>

        <div x-show="images.length > 1" class="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 text-white bg-white/10 backdrop-blur px-5 py-2 rounded-full text-sm font-bold tracking-wider z-10">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>
    </div>

</main>

<!-- Toast Notification -->
<div id="cartToast"
    style="display:none; position:fixed; bottom:80px; right:20px; z-index:9999; min-width:260px;"
    class="bg-gray-900 text-white text-sm px-4 py-3 rounded-lg shadow-xl flex items-center gap-3">
    <span id="cartToastIcon" class="material-symbols-outlined text-green-400 text-[20px]">check_circle</span>
    <span id="cartToastMsg">Produk ditambahkan ke keranjang!</span>
    <a href="{{ route('cart.index') }}" class="ml-auto text-primary font-medium text-xs underline whitespace-nowrap">Lihat Keranjang</a>
</div>

<script>
// Must register BEFORE Alpine initializes (defer means scripts in body run after DOM)
document.addEventListener('alpine:init', () => {
    Alpine.data('productCart', () => ({
        qty: 1,
        maxQty: {{ rand(10, 500) }},
        loading: false,
        loadingBuy: false,

        addToCart(buyNow) {
            const loadingKey = buyNow ? 'loadingBuy' : 'loading';
            this[loadingKey] = true;

            fetch('{{ route('cart.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    product_id: {{ $product->id }},
                    quantity: this.qty
                })
            })
            .then(r => r.json())
            .then(data => {
                this[loadingKey] = false;
                if (data.success) {
                    // Update cart badge
                    document.querySelectorAll('[data-cart-count]').forEach(el => {
                        el.textContent = data.cart_count;
                        el.style.display = data.cart_count > 0 ? '' : 'none';
                    });

                    if (buyNow) {
                        const form = document.getElementById('buyNowForm');
                        form.querySelectorAll('input[name="selected_ids[]"]').forEach(i => i.remove());
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected_ids[]';
                        input.value = {{ $product->id }};
                        form.appendChild(input);
                        form.submit();
                    } else {
                        showCartToast(data.message || 'Produk ditambahkan ke keranjang!');
                    }
                } else {
                    showCartToast('Gagal menambahkan ke keranjang.', true);
                }
            })
            .catch(() => {
                this[loadingKey] = false;
                showCartToast('Terjadi kesalahan. Coba lagi.', true);
            });
        }
    }));
});

function showCartToast(msg, isError = false) {
    const toast = document.getElementById('cartToast');
    const icon  = document.getElementById('cartToastIcon');
    document.getElementById('cartToastMsg').textContent = msg;
    icon.textContent = isError ? 'error' : 'check_circle';
    icon.className   = 'material-symbols-outlined text-[20px] ' + (isError ? 'text-red-400' : 'text-green-400');
    toast.style.display = 'flex';
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => { toast.style.display = 'none'; }, 3500);
}
</script>

<script>
function zoomImage(e) {
    const img = document.getElementById('mainImage');
    if (!img) return;
    const container = document.getElementById('image-container');
    const rect = container.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    img.style.transformOrigin = `${x}% ${y}%`;
}
function resetZoomImage() {
    const img = document.getElementById('mainImage');
    if (!img) return;
    img.style.transformOrigin = 'center center';
}
</script>

@endsection
