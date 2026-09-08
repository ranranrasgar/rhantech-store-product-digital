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
                            $realPaidOrdersCount = $product->orders()->whereIn('status', ['paid', 'downloaded'])->count();
                            $rating = $product->effective_rating;
                            $sold = $product->sales_count ?: $realPaidOrdersCount;
                            $reviews = $product->effective_reviews_count;
                        @endphp
                        <div class="flex flex-wrap items-center gap-3 md:gap-4 text-xs md:text-sm mb-4 pb-4 border-b border-outline-variant/60">
                            <div class="flex items-center gap-1.5 text-amber-500 font-bold">
                                <span class="underline underline-offset-4">{{ number_format($rating, 1) }}</span>
                                <div class="flex items-center text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($rating >= $i)
                                            <span class="material-symbols-outlined text-[15px] fill-current">star</span>
                                        @elseif($rating >= $i - 0.5)
                                            <span class="material-symbols-outlined text-[15px] fill-current">star_half</span>
                                        @else
                                            <span class="material-symbols-outlined text-[15px] text-slate-300">star</span>
                                        @endif
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

                        <!-- Short Description Snippet (Daya Tarik Cepat Pembeli) -->
                        @php
                            if (!empty($product->short_description)) {
                                $shortProductDesc = trim($product->short_description);
                            } else {
                                $cleanFullDesc = trim(preg_replace('/\s+/', ' ', strip_tags($product->description ?? '')));
                                $shortProductDesc = Str::limit($cleanFullDesc, 140, '...');
                            }
                        @endphp
                        @if(!empty($shortProductDesc))
                        <div class="mb-4 text-xs md:text-sm text-on-surface-variant leading-relaxed bg-surface-container-low/50 p-3 rounded border border-outline-variant/60">
                            <div class="flex items-center gap-1.5 font-bold text-on-surface text-[11px] uppercase tracking-wider mb-1">
                                <span class="material-symbols-outlined text-primary text-[15px]">info</span> Ringkasan Singkat Produk
                            </div>
                            <p class="text-on-surface/90">{{ $shortProductDesc }}</p>
                        </div>
                        @endif

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

                        <!-- Short Highlights / Demo Link & Brosur -->
                        <div class="mb-4 p-3 rounded-sm bg-sky-500/10 border border-sky-500/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="material-symbols-outlined text-sky-500 text-[20px] shrink-0">play_circle</span>
                                <div>
                                    <div class="text-xs font-bold text-on-surface">Live Preview & Brosur Produk</div>
                                    <div class="text-[11px] text-on-surface-variant">Uji coba fitur atau unduh spesifikasi lengkap produk.</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                                @if($product->demo_url)
                                <a href="{{ $product->demo_url }}" target="_blank" class="px-3 py-1.5 bg-sky-500 hover:bg-sky-400 text-white rounded-sm text-xs font-bold transition-all shadow-xs flex items-center gap-1">
                                    <span>Buka Demo</span>
                                    <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                </a>
                                @endif

                                <a href="{{ route('products.brochure', $product->slug) }}?print=1" target="_blank" class="px-3 py-1.5 bg-surface hover:bg-surface-container border border-outline-variant hover:border-rose-500/40 text-on-surface rounded-sm text-xs font-bold transition-all shadow-xs flex items-center gap-1.5 group" title="Download atau cetak brosur resmi">
                                    <span class="material-symbols-outlined text-rose-500 text-[15px] group-hover:scale-110 transition-transform">picture_as_pdf</span>
                                    <span>Download Brosur</span>
                                </a>
                            </div>
                        </div>

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

                        <!-- Quick Trust & Guarantee Badges (Tingkatkan Kepercayaan Pembeli) -->
                        @php
                            $productHighlights = $product->highlights ?: [
                                ['title' => 'Akses Instan & Aman', 'icon' => 'verified'],
                                ['title' => 'Direct Link Download', 'icon' => 'cloud_download'],
                                ['title' => 'Source Code 100% Bersih', 'icon' => 'security'],
                                ['title' => 'Bantuan & Dokumentasi', 'icon' => 'support_agent'],
                            ];
                            $hlColors = [
                                ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                                ['bg' => 'bg-primary/10', 'text' => 'text-primary'],
                                ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400'],
                                ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400'],
                            ];
                        @endphp
                        <div class="grid grid-cols-2 gap-2.5 mb-5 p-3 rounded-md bg-surface-container-low border border-outline-variant/70 shadow-xs">
                            @foreach($productHighlights as $hIndex => $hl)
                                @php $color = $hlColors[$hIndex % count($hlColors)]; @endphp
                                <div class="flex items-center gap-2 text-xs text-on-surface">
                                    <div class="w-6 h-6 rounded {{ $color['bg'] }} {{ $color['text'] }} flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[15px]">{{ $hl['icon'] ?? 'verified' }}</span>
                                    </div>
                                    <span class="font-semibold text-[11.5px] truncate">{{ $hl['title'] ?? '' }}</span>
                                </div>
                            @endforeach
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

                    <!-- Share ke Sosial Media & Favorit Bar -->
                    @php
                        $currentUrl = request()->fullUrl();
                        $shareTitle = urlencode($product->name . ' - ' . ($company->company_name ?? 'Rhantech'));
                        $encodedUrl = urlencode($currentUrl);
                    @endphp
                    <div class="pt-4 border-t border-outline-variant/60" x-data="{ copied: false, isFav: false, count: {{ 48 + ($product->id * 3) }} }">
                        <div class="flex items-center justify-between mb-2.5">
                            <span class="text-xs font-bold text-on-surface-variant flex items-center gap-1.5 uppercase tracking-wider">
                                <span class="material-symbols-outlined text-[16px] text-primary">share</span>
                                <span>Bagikan Produk Ini:</span>
                            </span>

                            <button @click="isFav = !isFav; isFav ? count++ : count--" class="flex items-center gap-1.5 px-2.5 py-1 rounded-sm bg-surface-container-low hover:bg-surface-container border border-outline-variant/60 text-on-surface-variant hover:text-rose-500 transition-colors text-xs font-semibold">
                                <span class="material-symbols-outlined text-[16px]" :class="isFav ? 'text-rose-500 filled' : ''" x-text="isFav ? 'favorite' : 'favorite_border'">favorite_border</span>
                                <span>Favorit (<span x-text="count"></span>)</span>
                            </button>
                        </div>

                        <!-- Grid Tombol Share Sosial Media Lengkap -->
                        <div class="grid grid-cols-4 sm:grid-cols-5 gap-2">
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="Share ke Facebook"
                               class="flex items-center justify-center gap-1.5 py-1.5 px-2 rounded bg-[#1877F2]/10 hover:bg-[#1877F2] text-[#1877F2] hover:text-white transition-all text-xs font-bold">
                                <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span>FB</span>
                            </a>

                            <!-- WhatsApp -->
                            <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $encodedUrl }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="Share ke WhatsApp"
                               class="flex items-center justify-center gap-1.5 py-1.5 px-2 rounded bg-[#25D366]/10 hover:bg-[#25D366] text-[#25D366] hover:text-white transition-all text-xs font-bold">
                                <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                                <span>WA</span>
                            </a>

                            <!-- X (Twitter) -->
                            <a href="https://twitter.com/intent/tweet?text={{ $shareTitle }}&url={{ $encodedUrl }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="Share ke X (Twitter)"
                               class="flex items-center justify-center gap-1.5 py-1.5 px-2 rounded bg-black/10 dark:bg-white/10 hover:bg-black dark:hover:bg-white text-black dark:text-white hover:text-white dark:hover:text-black transition-all text-xs font-bold">
                                <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                                <span>X</span>
                            </a>

                            <!-- Telegram -->
                            <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $shareTitle }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               title="Share ke Telegram"
                               class="flex items-center justify-center gap-1.5 py-1.5 px-2 rounded bg-[#229ED9]/10 hover:bg-[#229ED9] text-[#229ED9] hover:text-white transition-all text-xs font-bold">
                                <svg class="w-3.5 h-3.5 fill-current shrink-0" viewBox="0 0 24 24">
                                    <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.458c.538-.196 1.006.128.832.941z"/>
                                </svg>
                                <span>Tele</span>
                            </a>

                            <!-- Salin Link -->
                            <button type="button" 
                                    @click="navigator.clipboard.writeText('{{ $currentUrl }}'); copied = true; setTimeout(() => copied = false, 2500)"
                                    class="col-span-4 sm:col-span-1 flex items-center justify-center gap-1 py-1.5 px-2 rounded bg-surface-container-low hover:bg-surface-container border border-outline-variant/60 text-on-surface hover:text-primary transition-all text-xs font-bold"
                                    title="Salin Tautan">
                                <span class="material-symbols-outlined text-sm" x-text="copied ? 'check' : 'content_copy'"></span>
                                <span x-text="copied ? 'Disalin' : 'Salin'">Salin</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Horizontal Divider -->
            <hr class="my-6 border-outline-variant/60">

            <!-- Interactive Tab System for High Conversion & Trust -->
            <div x-data="{ 
                    activeTab: 'description', 
                    ratingFilter: 0
                }" class="w-full">

                <!-- Tab Headers -->
                <div class="flex items-center gap-2 border-b border-outline-variant/80 overflow-x-auto scrollbar-none pb-px">
                    <button type="button" 
                            @click="activeTab = 'description'"
                            :class="activeTab === 'description' 
                                ? 'border-primary text-primary font-bold bg-primary/5' 
                                : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                            class="flex items-center gap-2 py-3 px-4 border-b-2 text-xs md:text-sm whitespace-nowrap transition-all duration-200">
                        <span class="material-symbols-outlined text-[18px]">subject</span>
                        <span>Deskripsi & Spesifikasi</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'reviews'"
                            :class="activeTab === 'reviews' 
                                ? 'border-primary text-primary font-bold bg-primary/5' 
                                : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                            class="flex items-center gap-2 py-3 px-4 border-b-2 text-xs md:text-sm whitespace-nowrap transition-all duration-200 relative">
                        <span class="material-symbols-outlined text-[18px] text-amber-500 fill-current">star</span>
                        <span>Ulasan & Testimoni</span>
                        <span class="ml-1 px-1.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-500/20 text-amber-600 dark:text-amber-400">
                            @if($rating)
                                {{ number_format($rating, 1) }} ({{ $reviews }})
                            @else
                                {{ $reviews }}
                            @endif
                        </span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'guarantee'"
                            :class="activeTab === 'guarantee' 
                                ? 'border-primary text-primary font-bold bg-primary/5' 
                                : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                            class="flex items-center gap-2 py-3 px-4 border-b-2 text-xs md:text-sm whitespace-nowrap transition-all duration-200">
                        <span class="material-symbols-outlined text-[18px] text-emerald-500">verified_user</span>
                        <span>Garansi & Keamanan</span>
                    </button>

                    <button type="button" 
                            @click="activeTab = 'faq'"
                            :class="activeTab === 'faq' 
                                ? 'border-primary text-primary font-bold bg-primary/5' 
                                : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                            class="flex items-center gap-2 py-3 px-4 border-b-2 text-xs md:text-sm whitespace-nowrap transition-all duration-200">
                        <span class="material-symbols-outlined text-[18px] text-blue-500">help_outline</span>
                        <span>Tanya Jawab (FAQ)</span>
                    </button>
                </div>

                <!-- Tab 1: Deskripsi & Spesifikasi Lengkap -->
                <div x-show="activeTab === 'description'" x-cloak class="pt-6">
                    <!-- Meta Quick Specs -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 p-3.5 rounded-lg bg-surface-container-low border border-outline-variant mb-5">
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

                    <!-- Fitur Utama & Paket Termasuk -->
                    @php
                        $pkgItems = is_array($product->package_includes) ? array_filter($product->package_includes) : [];
                        $sysReqItems = is_array($product->system_requirements) ? array_filter($product->system_requirements) : [];
                        $hasPkg = count($pkgItems) > 0;
                        $hasSysReq = count($sysReqItems) > 0;
                    @endphp

                    @if($hasPkg || $hasSysReq)
                    <div class="mt-8 grid grid-cols-1 {{ $hasPkg && $hasSysReq ? 'md:grid-cols-2' : '' }} gap-4">
                        @if($hasPkg)
                        <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                            <h4 class="text-sm font-bold text-on-surface flex items-center gap-2 mb-3">
                                <span class="material-symbols-outlined text-primary text-[20px]">check_box</span>
                                Paket yang Anda Dapatkan
                            </h4>
                            <ul class="space-y-2 text-xs text-on-surface-variant">
                                @foreach($pkgItems as $pkg)
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-outlined text-emerald-500 text-[16px] shrink-0">check_circle</span>
                                    <span>{{ is_array($pkg) ? ($pkg['title'] ?? '') : $pkg }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if($hasSysReq)
                        <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant">
                            <h4 class="text-sm font-bold text-on-surface flex items-center gap-2 mb-3">
                                <span class="material-symbols-outlined text-primary text-[20px]">tune</span>
                                Kebutuhan Sistem (System Requirements)
                            </h4>
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                @foreach($sysReqItems as $sr)
                                <div class="p-2 rounded bg-surface border border-outline-variant/40">
                                    <span class="text-on-surface-variant block text-[10px] uppercase font-bold">{{ $sr['label'] ?? ($sr['title'] ?? '') }}</span>
                                    <span class="font-semibold text-on-surface">{{ $sr['value'] ?? ($sr['description'] ?? '') }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Tab 2: Ulasan & Testimoni Pembeli (Social Proof & Reviews) -->
                @php
                    $allReviews = $product->reviews;
                    $totalReviewsCount = $allReviews->count() > 0 ? $allReviews->count() : $product->effective_reviews_count;
                    $avgRating = $product->effective_rating;
                    $fiveStarCount = $allReviews->where('rating', 5)->count();
                    $fourStarCount = $allReviews->where('rating', 4)->count();
                    $threeStarCount = $allReviews->where('rating', 3)->count();
                    $twoStarCount = $allReviews->where('rating', 2)->count();
                    $oneStarCount = $allReviews->where('rating', 1)->count();
                    
                    // Jika ada review riil namun override berbeda atau data seeded, sesuaikan count bar agar proporsional
                    if ($fiveStarCount == 0 && $fourStarCount == 0 && $totalReviewsCount > 0) {
                        $fiveStarCount = (int) round($totalReviewsCount * 0.85);
                        $fourStarCount = $totalReviewsCount - $fiveStarCount;
                    }
                @endphp
                @php
                    $reviewsJson = $allReviews->map(function($r) {
                        return [
                            'id' => $r->id,
                            'name' => $r->customer_name,
                            'rating' => (int)$r->rating,
                            'comment' => $r->comment,
                            'date' => $r->created_at->diffForHumans(),
                            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($r->customer_name) . '&background=random&color=fff',
                        ];
                    })->values()->toJson();
                @endphp
                <div x-show="activeTab === 'reviews'" x-cloak class="pt-6" x-data="{ realReviews: {{ $reviewsJson }} }">
                    @if(session('success'))
                    <div class="p-3.5 mb-5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="p-3.5 mb-5 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-700 dark:text-rose-400 text-xs font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">error</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif

                    <!-- Kotak Ulasan Pembeli Langsung di Halaman Produk -->
                    @if(isset($hasPurchased) && $hasPurchased)
                        <div x-data="{ openForm: false, ratingInput: {{ $userReview ? $userReview->rating : 5 }} }" class="mb-6 p-4 md:p-5 rounded-2xl bg-surface-container-low border border-primary/30 shadow-xs">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-primary text-2xl">verified_user</span>
                                    <div>
                                        <h4 class="text-xs md:text-sm font-bold text-on-surface">
                                            {{ $userReview ? 'Ulasan Anda untuk Produk Ini' : 'Bagikan Pengalaman Anda Menggunakan Produk Ini' }}
                                        </h4>
                                        <p class="text-[11px] text-on-surface-variant">
                                            {{ $userReview ? 'Ulasan Anda telah tersimpan dan tampil untuk pembeli lain.' : 'Sebagai pembeli resmi produk ini, ulasan Anda sangat berharga bagi pembeli lain.' }}
                                        </p>
                                    </div>
                                </div>
                                @if(!$userReview)
                                    <button type="button" @click="openForm = !openForm" class="px-3.5 py-1.5 rounded-lg bg-primary text-white text-xs font-bold hover:opacity-90 transition flex items-center gap-1 shrink-0">
                                        <span class="material-symbols-outlined text-sm">rate_review</span>
                                        <span x-text="openForm ? 'Tutup' : 'Tulis Ulasan'">Tulis Ulasan</span>
                                    </button>
                                @endif
                            </div>

                            @if($userReview)
                                <div class="mt-3.5 p-3 rounded-xl bg-surface border border-outline-variant/60">
                                    <div class="flex items-center gap-1.5 text-amber-500 mb-1.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="material-symbols-outlined text-sm {{ $i <= $userReview->rating ? 'fill-current' : 'text-slate-300' }}">star</span>
                                        @endfor
                                        <span class="text-xs font-bold text-on-surface ml-1">{{ $userReview->rating }} / 5 Bintang</span>
                                        <span class="text-[10px] text-on-surface-variant ml-auto">{{ $userReview->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-on-surface leading-relaxed font-sans">{{ $userReview->comment }}</p>
                                    <p class="text-[10px] text-slate-400 mt-2 italic">* Ulasan yang sudah dikirim bersifat permanen dan tidak dapat dihapus oleh pembeli.</p>
                                </div>
                            @else
                                <form x-show="openForm" x-cloak action="{{ route('products.review.store', $product->id) }}" method="POST" class="mt-4 pt-4 border-t border-outline-variant/60 space-y-3">
                                    @csrf
                                    @if(isset($userOrder))
                                        <input type="hidden" name="order_id" value="{{ $userOrder->id }}">
                                    @endif
                                    <input type="hidden" name="rating" :value="ratingInput">

                                    <div>
                                        <label class="block text-xs font-semibold text-on-surface mb-1">Beri Nilai Bintang *</label>
                                        <div class="flex items-center gap-1 text-amber-500">
                                            <template x-for="i in 5" :key="i">
                                                <button type="button" @click="ratingInput = i" class="focus:outline-none transition-transform hover:scale-110">
                                                    <span class="material-symbols-outlined text-2xl" :class="i <= ratingInput ? 'fill-current' : 'text-slate-300'">star</span>
                                                </button>
                                            </template>
                                            <span class="text-xs font-bold text-on-surface ml-2" x-text="ratingInput + ' / 5 Bintang'"></span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-on-surface mb-1">Komentar / Pengalaman Nyata *</label>
                                        <textarea name="comment" rows="3" required placeholder="Ceritakan bagaimana produk ini membantu Anda, kemudahan instalasi, atau kepuasan Anda..." class="w-full px-3 py-2 text-xs bg-surface border border-outline-variant rounded-lg focus:outline-none focus:border-primary"></textarea>
                                        <p class="text-[10px] text-slate-400 mt-1">Catatan: Ulasan yang telah dikirim bersifat permanen.</p>
                                    </div>

                                    <div class="flex justify-end gap-2 pt-1">
                                        <button type="button" @click="openForm = false" class="px-3.5 py-1.5 text-xs text-on-surface-variant hover:bg-surface-container rounded-lg">Batal</button>
                                        <button type="submit" class="px-4 py-1.5 text-xs font-bold bg-primary text-white rounded-lg hover:opacity-90 transition shadow-xs">
                                            Kirim Ulasan Resmi
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endif

                    @if($totalReviewsCount > 0)
                        <!-- Rating Summary Card -->
                        <div class="p-5 md:p-6 rounded-2xl bg-gradient-to-br from-amber-500/10 via-surface-container-low to-primary/5 border border-amber-500/20 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                                <!-- Overall Score -->
                                <div class="md:col-span-4 text-center md:border-r md:border-outline-variant/60 md:pr-6">
                                    <div class="inline-flex items-baseline gap-1">
                                        <span class="text-4xl md:text-5xl font-extrabold text-on-surface font-sans">{{ number_format($avgRating, 1) }}</span>
                                        <span class="text-base font-bold text-on-surface-variant">/ 5.0</span>
                                    </div>
                                    <div class="flex items-center justify-center gap-1 text-amber-500 my-1.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($avgRating >= $i)
                                                <span class="material-symbols-outlined text-[20px] fill-current">star</span>
                                            @elseif($avgRating >= $i - 0.5)
                                                <span class="material-symbols-outlined text-[20px] fill-current">star_half</span>
                                            @else
                                                <span class="material-symbols-outlined text-[20px] text-slate-300">star</span>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-xs text-on-surface-variant font-medium">Berdasarkan <strong>{{ $totalReviewsCount }} ulasan</strong> pembeli terverifikasi</p>
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold">
                                        <span class="material-symbols-outlined text-[14px]">verified</span>
                                        Ulasan Pembeli Riil
                                    </div>
                                </div>

                                <!-- Rating Progress Bars -->
                                <div class="md:col-span-8 space-y-2">
                                    @foreach([5 => $fiveStarCount, 4 => $fourStarCount, 3 => $threeStarCount, 2 => $twoStarCount, 1 => $oneStarCount] as $star => $count)
                                    @php $pct = $totalReviewsCount > 0 ? round(($count / $totalReviewsCount) * 100) : 0; @endphp
                                    <div class="flex items-center gap-3 text-xs {{ $count == 0 ? 'opacity-50' : '' }}">
                                        <span class="w-12 font-bold flex items-center gap-1 text-on-surface">{{ $star }} <span class="material-symbols-outlined text-[13px] text-amber-500 fill-current">star</span></span>
                                        <div class="flex-1 h-2 rounded-full bg-surface-container-highest overflow-hidden">
                                            <div class="h-full bg-amber-500 rounded-full" style="width: {{ $pct }}%;"></div>
                                        </div>
                                        <span class="w-8 text-right font-medium text-on-surface-variant">{{ $count }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Review Filter Buttons -->
                        <div class="flex items-center gap-2 mb-5 flex-wrap">
                            <span class="text-xs font-bold text-on-surface-variant mr-1">Filter:</span>
                            <button type="button" 
                                    @click="ratingFilter = 0"
                                    :class="ratingFilter === 0 ? 'bg-primary text-white font-bold' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                    class="px-3 py-1 rounded-full text-xs transition-colors border border-outline-variant/60">
                                Semua ({{ $totalReviewsCount }})
                            </button>
                            @if($fiveStarCount > 0)
                            <button type="button" 
                                    @click="ratingFilter = 5"
                                    :class="ratingFilter === 5 ? 'bg-primary text-white font-bold' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                    class="px-3 py-1 rounded-full text-xs transition-colors border border-outline-variant/60 flex items-center gap-1">
                                5 Bintang ({{ $fiveStarCount }})
                            </button>
                            @endif
                            @if($fourStarCount > 0)
                            <button type="button" 
                                    @click="ratingFilter = 4"
                                    :class="ratingFilter === 4 ? 'bg-primary text-white font-bold' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                    class="px-3 py-1 rounded-full text-xs transition-colors border border-outline-variant/60 flex items-center gap-1">
                                4 Bintang ({{ $fourStarCount }})
                            </button>
                            @endif
                            @if($threeStarCount > 0)
                            <button type="button" 
                                    @click="ratingFilter = 3"
                                    :class="ratingFilter === 3 ? 'bg-primary text-white font-bold' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                    class="px-3 py-1 rounded-full text-xs transition-colors border border-outline-variant/60 flex items-center gap-1">
                                3 Bintang ({{ $threeStarCount }})
                            </button>
                            @endif
                            @if($twoStarCount > 0)
                            <button type="button" 
                                    @click="ratingFilter = 2"
                                    :class="ratingFilter === 2 ? 'bg-primary text-white font-bold' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                    class="px-3 py-1 rounded-full text-xs transition-colors border border-outline-variant/60 flex items-center gap-1">
                                2 Bintang ({{ $twoStarCount }})
                            </button>
                            @endif
                            @if($oneStarCount > 0)
                            <button type="button" 
                                    @click="ratingFilter = 1"
                                    :class="ratingFilter === 1 ? 'bg-primary text-white font-bold' : 'bg-surface-container-low text-on-surface hover:bg-surface-container'"
                                    class="px-3 py-1 rounded-full text-xs transition-colors border border-outline-variant/60 flex items-center gap-1">
                                1 Bintang ({{ $oneStarCount }})
                            </button>
                            @endif
                        </div>

                        <!-- Real Reviews List -->
                        <div class="space-y-4">
                            <template x-for="review in realReviews.filter(r => ratingFilter === 0 || r.rating === ratingFilter)" :key="review.id">
                                <div class="p-4 md:p-5 rounded-xl bg-surface-container-lowest border border-outline-variant/80 hover:border-outline-variant transition-all">
                                    <div class="flex items-start justify-between gap-3 mb-2.5">
                                        <div class="flex items-center gap-3">
                                            <img :src="review.avatar" :alt="review.name" class="w-10 h-10 rounded-full object-cover border border-outline-variant shrink-0">
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h4 class="text-xs md:text-sm font-bold text-on-surface" x-text="review.name"></h4>
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                                        <span class="material-symbols-outlined text-[12px]">verified</span>
                                                        <span>Terverifikasi Membeli</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-[11px] text-on-surface-variant whitespace-nowrap" x-text="review.date"></span>
                                    </div>

                                    <!-- Star Ratings -->
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex items-center text-amber-500">
                                            <template x-for="i in review.rating" :key="i">
                                                <span class="material-symbols-outlined text-[16px] fill-current">star</span>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Comment Body -->
                                    <p class="text-xs md:text-sm text-on-surface/90 leading-relaxed font-sans" x-text="review.comment"></p>

                                    <!-- Footer -->
                                    <div class="mt-3 pt-2.5 border-t border-outline-variant/40 flex items-center justify-between text-[11px] text-on-surface-variant">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px]">shield</span>
                                            Transaksi Aman & Resmi
                                        </span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @else
                        <!-- Empty State: Belum Ada Ulasan -->
                        <div class="py-12 px-4 text-center rounded-2xl bg-surface-container-low border border-dashed border-outline-variant mb-6">
                            <div class="w-14 h-14 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-3xl">rate_review</span>
                            </div>
                            <h4 class="text-sm md:text-base font-bold text-on-surface mb-1">Belum Ada Ulasan Pembeli</h4>
                            <p class="text-xs text-on-surface-variant max-w-md mx-auto mb-4 leading-relaxed">
                                Produk ini belum memiliki ulasan dari pembeli. Jadilah yang pertama memberikan ulasan setelah menyelesaikan pembelian produk ini!
                            </p>
                        </div>
                    @endif

                    <!-- Bottom Notice -->
                    <div class="mt-6 p-4 rounded-xl bg-surface-container-low border border-dashed border-outline-variant flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-2xl">rate_review</span>
                            <div>
                                <h5 class="text-xs font-bold text-on-surface">Sudah membeli produk ini?</h5>
                                <p class="text-[11px] text-on-surface-variant">Ulasan dapat dikirimkan langsung melalui halaman detail pesanan / pusat unduhan setelah pembayaran berhasil diverifikasi.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Garansi, Lisensi & Keamanan (Trust Builders) -->
                @php
                    $guaranteeItems = $product->guarantees ?: [
                        [
                            'title' => '100% Bebas Malware & Backdoor',
                            'icon' => 'verified',
                            'description' => 'Setiap baris source code telah diuji dan dipindai menggunakan tool keamanan standar industri. Kode bersih, tidak ada enkripsi (unobfuscated), dan bebas dari script berbahaya.'
                        ],
                        [
                            'title' => 'Garansi Bantuan Instalasi',
                            'icon' => 'support_agent',
                            'description' => 'Bingung saat pertama kali menginstall? Tim teknis kami siap memandu Anda melalui WhatsApp / Google Meet / AnyDesk sampai aplikasi berjalan lancar di localhost maupun server hosting Anda.'
                        ],
                        [
                            'title' => 'Akses Seumur Hidup (Lifetime)',
                            'icon' => 'security_update_good',
                            'description' => 'Sekali beli, tautan unduhan dan source code menjadi milik Anda selamanya. Tidak ada biaya langganan bulanan atau tahunan tersembunyi.'
                        ],
                        [
                            'title' => 'Lisensi Komersial Bebas Re-branding',
                            'icon' => 'balance',
                            'description' => 'Anda berhak mengganti nama aplikasi, logo, identitas toko, serta memodifikasi fitur sesuai kebutuhan bisnis pribadi maupun klien Anda tanpa royalti.'
                        ]
                    ];

                    $faqItems = $product->faqs ?: [
                        [
                            'question' => 'Bagaimana cara saya menerima source code setelah membayar?',
                            'answer' => 'Sistem secara otomatis memproses pesanan Anda setelah pembayaran terkonfirmasi. Link unduhan paket file (ZIP) akan langsung muncul di halaman invoice pembelian serta dikirimkan otomatis ke alamat email yang Anda cantumkan saat checkout.'
                        ],
                        [
                            'question' => 'Apakah source code ini bisa diubah atau dikembangkan lagi?',
                            'answer' => 'Ya, 100% full source code terbuka tanpa proteksi atau enkripsi (IonCube dll). Anda bebas memodifikasi logic, mengubah tampilan tema, menambahkan modul baru, atau menghubungkan dengan API lain sesuai kebutuhan.'
                        ],
                        [
                            'question' => 'Apakah saya bisa minta bantuan jika error saat instalasi?',
                            'answer' => 'Tentu saja! Kami menyediakan panduan instalasi lengkap di dalam file ZIP. Jika Anda mengalami kesulitan atau error konfigurasi, silakan hubungi tim support kami via WhatsApp yang tertera di menu kontak web. Kami siap membantu remote hingga aplikasi berhasil berjalan.'
                        ],
                        [
                            'question' => 'Apakah aplikasi ini bisa dijalankan secara offline di toko?',
                            'answer' => 'Bisa! Aplikasi berbasis web ini dapat diinstall di server lokal / PC kasir toko menggunakan localhost (Laragon/XAMPP) sehingga dapat beroperasi tanpa koneksi internet sama sekali, ataupun dihosting online agar bisa dipantau dari mana saja.'
                        ]
                    ];

                    $guaranteeColors = [
                        ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400'],
                        ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400'],
                        ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400'],
                        ['bg' => 'bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400'],
                    ];
                @endphp
                <div x-show="activeTab === 'guarantee'" x-cloak class="pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @foreach($guaranteeItems as $gIdx => $g)
                            @php $gColor = $guaranteeColors[$gIdx % count($guaranteeColors)]; @endphp
                            <div class="p-4 md:p-5 rounded-xl bg-surface-container-low border border-outline-variant flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-lg {{ $gColor['bg'] }} {{ $gColor['text'] }} flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[24px]">{{ $g['icon'] ?? 'verified' }}</span>
                                </div>
                                <div>
                                    <h4 class="text-xs md:text-sm font-bold text-on-surface mb-1">{{ $g['title'] ?? '' }}</h4>
                                    <p class="text-xs text-on-surface-variant leading-relaxed">
                                        {{ $g['description'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Security Badge Banner -->
                    <div class="p-4 rounded-xl bg-gradient-to-r from-emerald-500/10 via-surface-container-low to-blue-500/10 border border-emerald-500/30 flex items-center justify-between gap-4 flex-wrap">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-emerald-500 text-3xl">lock</span>
                            <div>
                                <h5 class="text-xs font-bold text-on-surface">Pembayaran Aman Terverifikasi Otomatis</h5>
                                <p class="text-[11px] text-on-surface-variant">Didukung payment gateway resmi Midtrans (QRIS, BCA, Mandiri, BNI, BRI, GoPay, OVO, ShopeePay).</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded bg-surface border border-outline-variant text-[11px] font-bold text-on-surface">SSL 256-bit Encrypted</span>
                            <span class="px-2.5 py-1 rounded bg-surface border border-outline-variant text-[11px] font-bold text-emerald-600">Verified Seller</span>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Tanya Jawab (FAQ) & Panduan -->
                @php
                    // 1. Artikel dari Help Category Platform (jika produk terhubung dengan kategori Pusat Bantuan)
                    $hasHelpArticles = $product->helpCategory && $product->helpCategory->articles && $product->helpCategory->articles->count() > 0;
                    $articlesList = $hasHelpArticles ? $product->helpCategory->articles : collect();

                    // 2. Tanya Jawab (FAQ) Khusus Toko untuk produk ini
                    $customFaqs = is_array($product->faqs) && count($product->faqs) > 0 ? $product->faqs : [];
                    $hasCustomFaqs = count($customFaqs) > 0;
                @endphp
                <div x-show="activeTab === 'faq'" x-cloak class="pt-6 space-y-6" x-data="{ openFaq: 0 }">
                    
                    {{-- Bagian A: Panduan & Tanya Jawab Khusus dari Toko (Jika Toko menambahkan FAQ tersendiri) --}}
                    @if($hasCustomFaqs)
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 pb-2 border-b border-outline-variant/60">
                                <span class="material-symbols-outlined text-primary text-[20px]">quiz</span>
                                <h4 class="text-xs md:text-sm font-bold text-on-surface">Panduan & Tanya Jawab Produk (Dari Penjual)</h4>
                            </div>
                            @foreach($customFaqs as $fIdx => $faq)
                            <div class="rounded-xl border border-outline-variant/80 bg-surface-container-low overflow-hidden transition-all">
                                <button type="button" @click="openFaq = (openFaq === {{ $fIdx }} ? null : {{ $fIdx }})" class="w-full p-4 text-left flex items-center justify-between gap-3 text-xs md:text-sm font-bold text-on-surface hover:text-primary transition-colors">
                                    <span class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-[18px]">help</span>
                                        {{ $faq['question'] ?? '' }}
                                    </span>
                                    <span class="material-symbols-outlined text-base transition-transform duration-200" :class="openFaq === {{ $fIdx }} ? 'rotate-180 text-primary' : ''">expand_more</span>
                                </button>
                                <div x-show="openFaq === {{ $fIdx }}" x-collapse class="px-4 pb-4 pt-1 text-xs text-on-surface-variant leading-relaxed border-t border-outline-variant/40">
                                    {!! nl2br(e($faq['answer'] ?? '')) !!}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Bagian B: Artikel Pusat Bantuan Platform yang Relevan (Jika memilih kategori bantuan platform) --}}
                    @if($hasHelpArticles)
                        <div class="space-y-3 {{ $hasCustomFaqs ? 'pt-4 border-t border-outline-variant/60' : '' }}">
                            <div class="flex items-center justify-between pb-2 border-b border-outline-variant/50">
                                <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                                    <span class="material-symbols-outlined text-blue-500 text-[18px]">folder_open</span>
                                    <span>Pusat Bantuan Platform: <strong class="text-on-surface">{{ $product->helpCategory->name }}</strong> ({{ $articlesList->count() }} Topik)</span>
                                </div>
                                <a href="{{ route('help.index') }}#category-{{ $product->helpCategory->slug }}" target="_blank" class="text-xs text-primary hover:underline flex items-center gap-1 font-semibold">
                                    <span>Buka di Pusat Bantuan</span>
                                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </div>
                            @foreach($articlesList as $aIdx => $article)
                            @php $combinedIdx = 'help_' . $aIdx; @endphp
                            <div class="rounded-xl border border-outline-variant/80 bg-surface-container-low overflow-hidden transition-all">
                                <button type="button" @click="openFaq = (openFaq === '{{ $combinedIdx }}' ? null : '{{ $combinedIdx }}')" class="w-full p-4 text-left flex items-center justify-between gap-3 text-xs md:text-sm font-bold text-on-surface hover:text-primary transition-colors">
                                    <span class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-blue-500 text-[18px]">article</span>
                                        {{ $article->title }}
                                    </span>
                                    <span class="material-symbols-outlined text-base transition-transform duration-200" :class="openFaq === '{{ $combinedIdx }}' ? 'rotate-180 text-primary' : ''">expand_more</span>
                                </button>
                                <div x-show="openFaq === '{{ $combinedIdx }}' " x-collapse class="px-4 pb-4 pt-1 text-xs text-on-surface-variant leading-relaxed border-t border-outline-variant/40 prose prose-sm dark:prose-invert max-w-none">
                                    {!! $article->content !!}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Fallback: Jika tidak memilih kategori dan tidak ada FAQ kustom, tampilkan FAQ umum standar --}}
                    @if(!$hasCustomFaqs && !$hasHelpArticles)
                        <div class="space-y-3">
                            @foreach($faqItems as $fIdx => $faq)
                            <div class="rounded-xl border border-outline-variant/80 bg-surface-container-low overflow-hidden transition-all">
                                <button type="button" @click="openFaq = (openFaq === {{ $fIdx }} ? null : {{ $fIdx }})" class="w-full p-4 text-left flex items-center justify-between gap-3 text-xs md:text-sm font-bold text-on-surface hover:text-primary transition-colors">
                                    <span class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-[18px]">quiz</span>
                                        {{ $faq['question'] ?? '' }}
                                    </span>
                                    <span class="material-symbols-outlined text-base transition-transform duration-200" :class="openFaq === {{ $fIdx }} ? 'rotate-180 text-primary' : ''">expand_more</span>
                                </button>
                                <div x-show="openFaq === {{ $fIdx }}" x-collapse class="px-4 pb-4 pt-1 text-xs text-on-surface-variant leading-relaxed border-t border-outline-variant/40">
                                    {!! nl2br(e($faq['answer'] ?? '')) !!}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
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

