@extends('layouts.shopee')
@section('title', $product->name . ' - ' . ($company->company_name ?? 'Rhantech'))

@section('content')
@php
    $imageUrls = $product->images->map(function($img) {
        return asset('storage/' . $img->image_path);
    })->values()->toJson();
    if ($product->images->isEmpty()) {
        $imageUrls = json_encode([]);
    }
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
    class="pt-6 md:pt-10 pb-16 min-h-screen bg-background text-on-background font-sans transition-colors duration-200">
    
    <div class="max-w-[1240px] mx-auto px-4 sm:px-6">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center text-xs md:text-sm text-on-surface-variant mb-6 overflow-x-auto whitespace-nowrap scrollbar-none py-1">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">home</span>
                Beranda
            </a>
            <span class="mx-2 text-outline-variant">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-primary transition-colors">Katalog Produk</a>
            @if($product->category)
                <span class="mx-2 text-outline-variant">/</span>
                <a href="{{ route('products.index', ['category' => $product->category->id]) }}" class="hover:text-primary transition-colors">{{ $product->category->name }}</a>
            @endif
            @if($product->type)
                <span class="mx-2 text-outline-variant">/</span>
                <a href="{{ route('products.index', ['type' => $product->type->id]) }}" class="hover:text-primary transition-colors">{{ $product->type->name }}</a>
            @endif
            <span class="mx-2 text-outline-variant">/</span>
            <span class="text-on-surface font-semibold truncate max-w-[200px] md:max-w-none">{{ $product->name }}</span>
        </nav>

        <!-- Main Product Presentation Card -->
        <div class="bg-surface rounded-sm border border-outline-variant shadow-xs p-4 md:p-6 mb-8">
            
            <!-- Top Section: Images (Left) + Product Main Details (Right) -->
            <div class="flex flex-col lg:flex-row gap-6 md:gap-8">
                
                <!-- Left Column: Interactive Gallery -->
                <div class="w-full lg:w-[440px] shrink-0">
                    <div class="sticky top-20">
                        <!-- Main Featured Image -->
                        <div @click="images.length > 0 && openLightbox(document.getElementById('mainImage')?.src)" 
                             class="aspect-square w-full rounded-sm bg-surface-container-low border border-outline-variant relative overflow-hidden mb-2.5 group cursor-zoom-in shadow-xs flex items-center justify-center" 
                             id="image-container" 
                             onmousemove="zoomImage(event)" 
                             onmouseleave="resetZoomImage()">
                            @if($product->images->count() > 0)
                                @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                <img id="mainImage" src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover origin-center transition-transform duration-100 ease-out group-hover:scale-[1.75]">
                                <div class="absolute bottom-2 right-2 bg-surface/80 dark:bg-black/60 backdrop-blur-md px-2 py-0.5 rounded-sm text-xs font-semibold text-on-surface flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-[14px]">zoom_in</span> Perbesar
                                </div>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-6xl text-outline-variant mb-2">image</span>
                                    <span class="text-xs font-medium">Foto Produk Digital</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Thumbnails Carousel -->
                        @if($product->images->count() > 1)
                        <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-none">
                            @foreach($product->images as $img)
                            <button onclick="document.getElementById('mainImage').src = '{{ asset('storage/' . $img->image_path) }}'" 
                                    class="w-16 h-16 rounded-sm shrink-0 border-2 border-transparent hover:border-primary focus:border-primary transition-all overflow-hidden bg-surface-container-low p-0.5 shadow-xs">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover rounded-sm">
                            </button>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Details, Store Info, Guarantees & Purchase Actions -->
                <div class="flex-1 flex flex-col justify-between w-full">
                    
                    <div>
                        <!-- Badges & Tags -->
                        <div class="flex flex-wrap items-center gap-2 mb-2.5">
                            <span class="px-2 py-0.5 rounded-sm bg-primary/10 text-primary border border-primary/20 text-xs font-bold uppercase tracking-wider">
                                Official Product
                            </span>
                            @if($product->category)
                                <span class="px-2 py-0.5 rounded-sm bg-surface-container-high text-on-surface-variant text-xs font-semibold">
                                    {{ $product->category->name }}
                                </span>
                            @endif
                            @if($product->type)
                                <span class="px-2 py-0.5 rounded-sm bg-surface-container text-on-surface-variant text-xs font-semibold">
                                    {{ $product->type->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="text-xl md:text-2xl font-extrabold text-on-surface tracking-tight leading-tight mb-3">
                            {{ $product->name }}
                        </h1>
                        
                        <!-- Ratings & Stats -->
                        @php 
                            $rating = number_format(4.8 + (crc32($product->id) % 3) / 10, 1);
                            $sold = 25 + (crc32($product->slug) % 250);
                            $reviews = max(5, intval($sold * 0.4));
                        @endphp
                        <div class="flex flex-wrap items-center gap-3 md:gap-4 text-xs md:text-sm mb-4 pb-4 border-b border-outline-variant/60">
                            <div class="flex items-center gap-1.5 text-amber-500 font-bold">
                                <span class="underline underline-offset-4">{{ $rating }}</span>
                                <div class="flex items-center text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-[15px]">{{ $i <= round($rating) ? 'star' : 'star_half' }}</span>
                                    @endfor
                                </div>
                            </div>
                            <div class="h-3.5 w-px bg-outline-variant"></div>
                            <div class="text-on-surface-variant">
                                <span class="font-bold text-on-surface">{{ $reviews }}</span> Penilaian
                            </div>
                            <div class="h-3.5 w-px bg-outline-variant"></div>
                            <div class="text-on-surface-variant">
                                <span class="font-bold text-on-surface">{{ $sold }}</span> Terjual
                            </div>
                        </div>

                        <!-- Price Card -->
                        <div class="bg-surface-container-low rounded-sm p-4 mb-4 border border-outline-variant flex flex-wrap items-baseline gap-3">
                            @if($product->discount_price)
                                <div class="text-2xl md:text-3xl font-black text-primary">
                                    Rp{{ number_format($product->discount_price, 0, ',', '.') }}
                                </div>
                                <div class="text-on-surface-variant line-through text-sm md:text-base">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                <span class="px-1.5 py-0.5 rounded-sm bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-extrabold">
                                    Diskon {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                </span>
                            @else
                                <div class="text-2xl md:text-3xl font-black text-primary">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>

                        <!-- Short Highlights / Demo Link -->
                        @if($product->demo_url)
                        <div class="mb-4 p-3 rounded-sm bg-sky-500/10 border border-sky-500/20 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-sky-500 text-[20px]">play_circle</span>
                                <div>
                                    <div class="text-xs font-bold text-on-surface">Live Preview / Demo Online</div>
                                    <div class="text-[11px] text-on-surface-variant">Uji coba langsung fitur aplikasi sebelum membeli.</div>
                                </div>
                            </div>
                            <a href="{{ $product->demo_url }}" target="_blank" class="px-3 py-1 bg-sky-500 hover:bg-sky-400 text-white rounded-sm text-xs font-bold transition-all shadow-xs flex items-center gap-1 shrink-0">
                                <span>Buka Demo</span>
                                <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                            </a>
                        </div>
                        @endif

                        <!-- Store Compact Info Snippet (Positioned inside details column) -->
                        @if($product->store)
                        <div class="mb-4 p-3 rounded-sm bg-surface-container-low border border-outline-variant flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-sm bg-surface border border-outline-variant p-0.5 overflow-hidden shrink-0">
                                    <img src="{{ $product->store->logo ? asset('storage/' . $product->store->logo) : 'https://ui-avatars.com/api/?name='.urlencode($product->store->name).'&background=random&color=fff' }}" class="w-full h-full object-cover rounded-sm">
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1">
                                        <span class="text-xs font-bold text-on-surface truncate">{{ $product->store->name }}</span>
                                        <span class="material-symbols-outlined text-primary text-[14px]">verified</span>
                                    </div>
                                    <div class="text-[11px] text-on-surface-variant flex items-center gap-2 mt-0.5">
                                        <span class="text-amber-500 font-bold">★ 4.9</span>
                                        <span>•</span>
                                        <span>{{ $product->store->products()->count() }} Produk</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('store.show', $product->store->slug) }}" class="px-3 py-1 bg-surface hover:bg-surface-container border border-outline-variant rounded-sm text-xs font-bold text-on-surface transition-all flex items-center gap-1 shrink-0 shadow-xs">
                                <span class="material-symbols-outlined text-[14px]">storefront</span>
                                <span>Kunjungi</span>
                            </a>
                        </div>
                        @endif

                        <!-- Quick Guarantee Badges -->
                        <div class="grid grid-cols-2 gap-3 mb-5 p-2.5 rounded-sm bg-surface-container/50 border border-outline-variant/60">
                            <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-emerald-500 text-[16px]">verified</span>
                                <span>Akses Instan & Aman</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-primary text-[16px]">cloud_download</span>
                                <span>Direct Link Download</span>
                            </div>
                        </div>

                        <!-- Cart & Checkout Interactive Form -->
                        <div x-data="{
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
                                        quantity: 1
                                    })
                                })
                                .then(r => r.json())
                                .then(data => {
                                    this[loadingKey] = false;
                                    if (data.success) {
                                        document.querySelectorAll('[data-cart-count]').forEach(el => {
                                            el.textContent = data.cart_count;
                                            el.style.display = data.cart_count > 0 ? '' : 'none';
                                        });
                                        if (buyNow) {
                                            const form = document.getElementById('buyNowForm');
                                            form.querySelectorAll('input[name=\'selected_ids[]\']').forEach(i => i.remove());
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
                        }">
                            <!-- Action Buttons -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                                @if($product->store)
                                    <button type="button"
                                        @click="window.dispatchEvent(new CustomEvent('open-chat-with-store', { 
                                            detail: { 
                                                store_id: {{ $product->store->id }}, 
                                                store_name: '{{ addslashes($product->store->name) }}',
                                                store_slug: '{{ $product->store->slug }}',
                                                store_logo: '{{ $product->store->logo ? asset('storage/' . $product->store->logo) : '' }}',
                                                product: {
                                                    id: {{ $product->id }},
                                                    name: '{{ addslashes($product->name) }}',
                                                    price: '{{ number_format($product->price, 0, ',', '.') }}',
                                                    image: '{{ $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : '' }}'
                                                }
                                            } 
                                        }))"
                                        class="w-full px-4 py-3 rounded-sm border border-emerald-600 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition-all font-bold text-sm flex items-center justify-center gap-2 cursor-pointer">
                                        <span class="material-symbols-outlined text-[18px]">chat</span>
                                        <span>Chat Penjual</span>
                                    </button>
                                @endif
                                <button type="button"
                                    @click="addToCart(false)"
                                    :disabled="loading"
                                    class="w-full px-4 py-3 rounded-sm border-2 border-primary text-primary hover:bg-primary/5 transition-all font-bold text-sm flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px]" x-text="loading ? 'hourglass_empty' : 'add_shopping_cart'">add_shopping_cart</span>
                                    <span x-text="loading ? 'Menambahkan...' : 'Keranjang'">Keranjang</span>
                                </button>
                                <button type="button"
                                    @click="addToCart(true)"
                                    :disabled="loadingBuy"
                                    class="w-full px-4 py-3 rounded-sm bg-primary hover:brightness-110 text-white shadow-md shadow-primary/20 transition-all font-bold text-sm flex items-center justify-center gap-2 disabled:opacity-60 cursor-pointer">
                                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                                    <span x-text="loadingBuy ? 'Memproses...' : 'Beli Sekarang'">Beli Sekarang</span>
                                </button>
                            </div>

                            <!-- Hidden form for Beli Sekarang redirect -->
                            <form id="buyNowForm" action="{{ route('checkout.select') }}" method="POST" style="display:none;">
                                @csrf
                            </form>
                        </div>
                    </div>

                    <!-- Share & Favorite Bar -->
                    <div class="flex items-center justify-between pt-3.5 border-t border-outline-variant/60 flex-wrap gap-3 text-xs" x-data="{ isFav: false, count: {{ 48 + ($product->id * 3) }} }">
                        <div class="flex items-center gap-3">
                            <span class="text-on-surface-variant font-medium">Bagikan:</span>
                            <div class="flex items-center gap-1.5">
                                <a href="https://api.whatsapp.com/send?text={{ urlencode(url()->current()) }}" target="_blank" class="w-7 h-7 rounded-sm bg-surface-container flex items-center justify-center text-emerald-500 hover:scale-110 transition-transform" title="Share ke WhatsApp">
                                    <span class="material-symbols-outlined text-[15px]">chat</span>
                                </a>
                                <button onclick="navigator.clipboard.writeText(window.location.href); showCartToast('Link produk berhasil disalin!');" class="w-7 h-7 rounded-sm bg-surface-container flex items-center justify-center text-on-surface hover:scale-110 transition-transform" title="Salin Tautan">
                                    <span class="material-symbols-outlined text-[15px]">link</span>
                                </button>
                            </div>
                        </div>

                        <button @click="isFav = !isFav; isFav ? count++ : count--" class="flex items-center gap-1.5 px-2.5 py-1 rounded-sm bg-surface-container text-on-surface-variant hover:text-rose-500 transition-colors">
                            <span class="material-symbols-outlined text-[16px]" :class="isFav ? 'text-rose-500 filled' : ''" x-text="isFav ? 'favorite' : 'favorite_border'">favorite_border</span>
                            <span class="font-semibold">Favorit (<span x-text="count"></span>)</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Horizontal Divider -->
            <hr class="my-6 border-outline-variant/60">

            <!-- Integrated Description & Specifications Section -->
            <div>
                <h2 class="text-base md:text-lg font-bold text-on-surface flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-primary text-[20px]">subject</span>
                    Deskripsi Lengkap & Spesifikasi Produk
                </h2>

                <!-- Meta Quick Specs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 p-3.5 rounded-sm bg-surface-container-low border border-outline-variant mb-4">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant block">Kategori</span>
                        <span class="text-xs md:text-sm font-semibold text-on-surface">{{ $product->category->name ?? 'Digital Solution' }}</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant block">Tipe Produk</span>
                        <span class="text-xs md:text-sm font-semibold text-on-surface">{{ $product->type->name ?? 'Source Code & Template' }}</span>
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant block">Format Pengiriman</span>
                        <span class="text-xs md:text-sm font-semibold text-emerald-600 dark:text-emerald-400">Download Link / Instant Access</span>
                    </div>
                </div>

                <!-- Long Description Body -->
                <div class="prose dark:prose-invert max-w-none text-xs md:text-sm text-on-surface/90 leading-relaxed whitespace-pre-line font-sans">
                    {{ $product->description }}
                </div>
            </div>

        </div>
        
    </div>

    <!-- Fullscreen Lightbox Modal -->
    <div x-show="showLightbox" x-transition.opacity.duration.300ms style="display: none;"
         class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4 md:p-8 backdrop-blur-md"
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

        <img :src="images[currentIndex]" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl transition-all duration-300 select-none" alt="Fullscreen Preview" @click.self="showLightbox = false" x-transition>

        <div x-show="images.length > 1" class="absolute bottom-6 md:bottom-8 left-1/2 -translate-x-1/2 text-white bg-white/10 backdrop-blur px-5 py-2 rounded-full text-xs font-bold tracking-wider z-10">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>
    </div>

</main>

<!-- Modern Toast Notification -->
<div id="cartToast"
    style="display:none; position:fixed; bottom:30px; right:24px; z-index:9999; min-width:280px;"
    class="bg-surface-container-highest text-on-surface border border-outline-variant text-sm px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 backdrop-blur-md">
    <span id="cartToastIcon" class="material-symbols-outlined text-emerald-500 text-[22px]">check_circle</span>
    <span id="cartToastMsg" class="font-medium">Produk ditambahkan ke keranjang!</span>
    <a href="{{ route('cart.index') }}" class="ml-auto text-primary font-bold text-xs underline whitespace-nowrap">Keranjang</a>
</div>

<script>
function showCartToast(msg, isError = false) {
    const toast = document.getElementById('cartToast');
    const icon  = document.getElementById('cartToastIcon');
    document.getElementById('cartToastMsg').textContent = msg;
    icon.textContent = isError ? 'error' : 'check_circle';
    icon.className   = 'material-symbols-outlined text-[22px] ' + (isError ? 'text-rose-500' : 'text-emerald-500');
    toast.style.display = 'flex';
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => { toast.style.display = 'none'; }, 3500);
}

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

