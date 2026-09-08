@extends('layouts.public')

@section('title', $store->name)

@php
    $headerBgUrl = $store->banner ?: 'https://images.unsplash.com/photo-1557683316-973673baf926?w=1200&h=400&fit=crop';
@endphp

@section('content')
<main class="min-h-screen bg-surface-container-lowest"
    x-data="{ 
        activeTab: 'beranda',
        isFollowing: {{ $isFollowing ? 'true' : 'false' }},
        followersCount: {{ $store->followers()->count() }},
        toggleFollow() {
            @auth
            fetch('{{ route('store.follow', $store->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.isFollowing = data.following;
                this.followersCount = this.isFollowing ? this.followersCount + 1 : this.followersCount - 1;
            })
            .catch(err => console.error(err));
            @else
            window.location.href = '{{ route('login') }}';
            @endauth
        }
    }">
    
    <!-- Store Header Banner -->
    <div class="relative w-full min-h-[350px] bg-[#1a1a1a]">
        <!-- Background Image (Mock or real if available) -->
        <div class="absolute inset-0 bg-cover bg-center opacity-80" style="background-image: url('{{ $headerBgUrl }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pt-24 pb-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end gap-6">
                <!-- Avatar -->
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-white shadow-lg overflow-hidden bg-white shrink-0">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0D8ABC&color=fff&size=128" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                
                <!-- Info -->
                <div class="flex-1 text-white">
                    <h1 class="text-3xl font-bold mb-2 drop-shadow-md">{{ $store->name }}</h1>
                    <div class="flex items-center gap-4 text-sm drop-shadow-md opacity-90">
                        <span class="flex items-center gap-1"><span class="text-yellow-400 text-lg">★</span> 4.8</span>
                        <span>•</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">inventory_2</span> {{ $products->total() }} Produk</span>
                        <span>•</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">group</span> <span x-text="followersCount"></span> Pengikut</span>
                    </div>
                </div>

                <!-- Actions -->
                @if(!auth()->check() || auth()->id() !== $store->user_id)
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
                    <button @click="toggleFollow()" 
                        :class="isFollowing ? 'bg-surface-container border-outline text-on-surface hover:bg-surface-container-high' : 'bg-primary border-primary text-white hover:bg-primary/90'"
                        class="px-6 py-2 border rounded font-bold transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]" x-text="isFollowing ? 'check' : 'add'">add</span> 
                        <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti'">Ikuti</span>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Store Navigation -->
    <div class="bg-white border-b border-outline-variant sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex overflow-x-auto hide-scrollbar">
            <button @click="activeTab = 'beranda'" :class="activeTab === 'beranda' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap">Beranda Toko</button>
            <button @click="activeTab = 'produk'" :class="activeTab === 'produk' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap">Semua Produk</button>
            <button @click="activeTab = 'kategori'" :class="activeTab === 'kategori' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap">Kategori</button>
            <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap">Profil Toko</button>
        </div>
    </div>

    <!-- Store Content (Dynamic Appearance) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <!-- Tab 1: Beranda -->
        <div x-show="activeTab === 'beranda'">

        
        @if(empty($appearance))
            <!-- FALLBACK DEFAULT VIEW -->
            <div>
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

        @else
            <!-- DYNAMIC DECORATION VIEW -->
            @foreach($appearance as $block)
                @php $data = $block['data'] ?? []; @endphp
                
                @if($block['type'] === 'banner')
                    @php $images = $data['images'] ?? []; @endphp
                    @if(count($images) > 0)
                        <div class="w-full relative overflow-hidden rounded-2xl shadow-sm bg-slate-100" x-data="{ activeSlide: 0, slides: {{ count($images) }}, 
                            next() { this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1 },
                            prev() { this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1 },
                            init() { setInterval(() => this.next(), 4000); }
                        }">
                            <!-- Slides -->
                            <div class="relative w-full h-48 md:h-80 flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${activeSlide * 100}%)`">
                                @foreach($images as $img)
                                    <div class="w-full h-full flex-shrink-0 relative">
                                        @php
                                            $link = trim($img['link'] ?? '');
                                            $isExternal = !empty($link) && (str_starts_with($link, 'http://') || str_starts_with($link, 'https://'));
                                        @endphp
                                        @if(!empty($link))
                                            <a href="{{ $link }}" @if($isExternal) target="_blank" rel="noopener noreferrer" @endif class="block w-full h-full">
                                                <img src="{{ $img['image_url'] ?? '' }}" class="w-full h-full object-cover" alt="Banner Slide">
                                            </a>
                                        @else
                                            <img src="{{ $img['image_url'] ?? '' }}" class="w-full h-full object-cover" alt="Banner Slide">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Dots Navigation -->
                            @if(count($images) > 1)
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                                <template x-for="i in slides">
                                    <button @click="activeSlide = i - 1" :class="activeSlide === i - 1 ? 'bg-white w-4' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all shadow-sm"></button>
                                </template>
                            </div>
                            <!-- Arrows -->
                            <button @click="prev()" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </button>
                            <button @click="next()" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </button>
                            @endif
                        </div>
                    @else
                        <!-- Empty Placeholder -->
                        <div class="w-full h-48 md:h-80 bg-slate-100 rounded-2xl overflow-hidden relative shadow-sm flex items-center justify-center border border-slate-200">
                            <span class="material-symbols-outlined text-5xl text-slate-300">view_carousel</span>
                            <div class="absolute bottom-4 text-center">
                                <span class="bg-white/90 backdrop-blur text-[10px] font-bold px-3 py-1 rounded-full text-slate-600 shadow-sm border border-slate-200/50">Slider Banner Toko</span>
                            </div>
                        </div>
                    @endif

                @elseif($block['type'] === 'single_image')
                    @php
                        $singleLink = trim($data['link'] ?? '');
                        $isSingleExternal = !empty($singleLink) && (str_starts_with($singleLink, 'http://') || str_starts_with($singleLink, 'https://'));
                    @endphp
                    @if(!empty($data['image_url']))
                        @if(!empty($singleLink))
                            <a href="{{ $singleLink }}" @if($isSingleExternal) target="_blank" rel="noopener noreferrer" @endif class="block w-full rounded-2xl overflow-hidden shadow-sm hover:opacity-95 transition-opacity">
                                <img src="{{ $data['image_url'] }}" alt="Promo Banner" class="w-full h-auto object-cover max-h-[400px]">
                            </a>
                        @else
                            <div class="w-full rounded-2xl overflow-hidden shadow-sm">
                                <img src="{{ $data['image_url'] }}" alt="Promo Banner" class="w-full h-auto object-cover max-h-[400px]">
                            </div>
                        @endif
                    @else
                        <div class="w-full h-40 bg-slate-50 rounded-2xl flex flex-col gap-2 items-center justify-center border border-dashed border-slate-300">
                            <span class="material-symbols-outlined text-3xl text-slate-300">image</span>
                            <span class="text-slate-400 text-xs font-bold">Banner Gambar Promo</span>
                        </div>
                    @endif

                @elseif($block['type'] === 'text')
                    @php 
                        $alignClass = match($data['align'] ?? 'center') {
                            'left' => 'text-left',
                            'right' => 'text-right',
                            default => 'text-center'
                        };
                        $sizeClass = match($data['size'] ?? 'md') {
                            'sm' => 'text-sm',
                            'lg' => 'text-xl',
                            default => 'text-base'
                        };
                    @endphp
                    <div class="w-full px-4 py-2 {{ $alignClass }}">
                        <p class="text-slate-700 {{ $sizeClass }} font-medium whitespace-pre-line">{{ $data['text'] ?? 'Selamat datang di toko kami!' }}</p>
                    </div>

                @elseif($block['type'] === 'flash_sale')
                    @php 
                        $endDate = $data['end_date'] ?? ''; 
                        // Ambil 4 produk diskon
                        $flashProducts = $products->filter(fn($p) => $p->discount_price > 0)->take(4);
                        if($flashProducts->count() === 0) {
                            $flashProducts = $products->take(4);
                        }
                    @endphp
                    <div class="w-full relative" 
                         x-data="{
                            rawEnd: '{{ $endDate }}',
                            end: 0,
                            now: new Date().getTime(),
                            timeLeft: 0,
                            days: 0, hours: 0, minutes: 0, seconds: 0,
                            init() {
                                this.end = this.rawEnd ? new Date(this.rawEnd).getTime() : new Date().getTime() + 86400000;
                                if(isNaN(this.end)) this.end = new Date().getTime() + 86400000;
                                
                                this.update();
                                setInterval(() => this.update(), 1000);
                            },
                            update() {
                                this.now = new Date().getTime();
                                this.timeLeft = this.end - this.now;
                                if(this.timeLeft > 0) {
                                    this.days = Math.floor(this.timeLeft / (1000 * 60 * 60 * 24));
                                    this.hours = Math.floor((this.timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    this.minutes = Math.floor((this.timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                    this.seconds = Math.floor((this.timeLeft % (1000 * 60)) / 1000);
                                } else {
                                    this.days = 0; this.hours = 0; this.minutes = 0; this.seconds = 0;
                                }
                            }
                         }">

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <h3 class="font-extrabold italic text-xl tracking-wide text-rose-500 drop-shadow-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[24px]">bolt</span> FLASH SALE
                                </h3>
                                
                                <div class="flex items-center gap-1 text-sm font-bold text-white">
                                    <span x-show="days > 0" x-text="days + 'h'" class="bg-slate-800 px-1.5 py-0.5 rounded"></span>
                                    <span x-text="String(hours).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                                    <span class="text-slate-800">:</span>
                                    <span x-text="String(minutes).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                                    <span class="text-slate-800">:</span>
                                    <span x-text="String(seconds).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                                </div>
                            </div>
                            
                            <a href="#" class="text-xs font-bold text-rose-500 hover:text-rose-600 hover:underline flex items-center gap-0.5">
                                Lihat Semua <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($flashProducts as $product)
                                <a href="{{ route('products.show', $product->slug) }}" class="bg-white rounded-xl overflow-hidden hover:shadow-xl hover:-translate-y-1 group border border-slate-100 transition-all">
                                    <div class="aspect-square w-full bg-slate-50 relative overflow-hidden">
                                        @if($product->images->count() > 0)
                                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                            </div>
                                        @endif
                                        @if($product->discount_price)
                                        <div class="absolute top-0 right-0 bg-rose-100 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded-bl-lg border-b border-l border-rose-200">
                                            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                        </div>
                                        @endif
                                        <div class="absolute bottom-0 left-0 right-0 bg-slate-900/5 backdrop-blur-sm">
                                            <div class="bg-gradient-to-r from-rose-500/90 to-orange-500/90 text-white text-center text-[10px] font-bold py-1 backdrop-blur-md">
                                                🔥 STOK TERBATAS
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <h3 class="text-slate-800 text-[11px] md:text-xs font-bold line-clamp-2 leading-tight group-hover:text-orange-500 transition-colors h-8">{{ $product->name }}</h3>
                                        @if($product->discount_price)
                                            <div class="font-black text-rose-600 text-sm md:text-base mt-2">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                            <div class="text-[9px] md:text-[10px] text-slate-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        @else
                                            <div class="font-black text-rose-600 text-sm md:text-base mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                @elseif($block['type'] === 'voucher')
                    @php $vouchers = $data['vouchers'] ?? []; @endphp
                    @if(count($vouchers) > 0)
                    <div class="w-full">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-rose-500">confirmation_number</span>
                            <h2 class="text-lg font-bold text-slate-800">Kupon Tersedia</h2>
                        </div>
                        <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-2 snap-x snap-mandatory">
                            @foreach($vouchers as $v)
                                @php
                                    $theme = $v['theme'] ?? 'rose';
                                    $themeColors = match($theme) {
                                        'emerald' => ['bg' => 'from-emerald-50 to-teal-50', 'border' => 'border-emerald-200', 'main' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'hover' => 'hover:bg-emerald-600'],
                                        'amber' => ['bg' => 'from-amber-50 to-yellow-50', 'border' => 'border-amber-200', 'main' => 'bg-amber-500', 'text' => 'text-amber-600', 'hover' => 'hover:bg-amber-600'],
                                        'sky' => ['bg' => 'from-sky-50 to-blue-50', 'border' => 'border-sky-200', 'main' => 'bg-sky-500', 'text' => 'text-sky-600', 'hover' => 'hover:bg-sky-600'],
                                        'violet' => ['bg' => 'from-violet-50 to-purple-50', 'border' => 'border-violet-200', 'main' => 'bg-violet-500', 'text' => 'text-violet-600', 'hover' => 'hover:bg-violet-600'],
                                        default => ['bg' => 'from-rose-50 to-orange-50', 'border' => 'border-rose-200', 'main' => 'bg-rose-500', 'text' => 'text-rose-600', 'hover' => 'hover:bg-rose-600']
                                    };
                                @endphp
                                <div class="snap-start shrink-0 w-[280px] h-24 bg-gradient-to-br {{ $themeColors['bg'] }} border {{ $themeColors['border'] }} rounded-xl flex items-center relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                    <div class="w-8 h-full {{ $themeColors['main'] }} border-r-2 border-dashed border-white/50 flex flex-col items-center justify-center text-white text-[8px] font-bold tracking-widest" style="writing-mode: vertical-rl; transform: rotate(180deg);">KUPON</div>
                                    <div class="p-4 flex-1">
                                        <h4 class="font-bold {{ $themeColors['text'] }} text-sm mb-0.5 line-clamp-1">{{ $v['title'] ?? 'Diskon' }}</h4>
                                        <p class="text-[10px] text-slate-500 line-clamp-1">{{ $v['subtitle'] ?? '' }}</p>
                                    </div>
                                    <div class="mr-3">
                                        <button class="px-4 py-1.5 {{ $themeColors['main'] }} text-white text-[10px] font-bold rounded-lg {{ $themeColors['hover'] }} shadow-sm transition-colors" onclick="alert('Kode Kupon Berhasil Diklaim!')">KLAIM</button>
                                    </div>
                                    <div class="absolute -top-3 -right-3 w-6 h-6 bg-[#f8fafc] rounded-full shadow-inner border border-slate-200/50"></div>
                                    <div class="absolute -bottom-3 -right-3 w-6 h-6 bg-[#f8fafc] rounded-full shadow-inner border border-slate-200/50"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                @elseif($block['type'] === 'products')
                    @php 
                        $count = (int)($data['count'] ?? 8);
                        $displayProducts = $products->take($count);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xl font-bold text-slate-800 border-l-4 border-sky-500 pl-3">
                                {{ ($data['type'] ?? 'latest') === 'bestseller' ? 'Produk Terlaris' : 'Pilihan Produk' }}
                            </h2>
                            <a href="#" class="text-sm text-sky-500 font-bold hover:underline">Lihat Semua</a>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                            @forelse($displayProducts as $product)
                                <!-- Product Card -->
                                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white border border-slate-200 hover:border-sky-500 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col relative">
                                    <div class="aspect-square w-full bg-slate-50 relative overflow-hidden">
                                        @if($product->images->count() > 0)
                                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
                                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                            </div>
                                        @endif
                                        @if($product->discount_price)
                                            <div class="absolute top-2 right-2 bg-rose-500 text-white font-bold text-[10px] px-2 py-1 rounded-lg shadow-sm">SALE</div>
                                        @endif
                                    </div>
                                    <div class="p-4 flex flex-col flex-1">
                                        <h3 class="font-bold text-slate-800 text-sm line-clamp-2 mb-2 group-hover:text-sky-500 transition-colors">{{ $product->name }}</h3>
                                        <div class="mt-auto">
                                            @if($product->discount_price)
                                                <div class="text-[11px] text-slate-400 line-through mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                                <div class="font-black text-sky-500 text-base">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                            @else
                                                <div class="font-black text-sky-500 text-base mt-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                            @endif
                                            
                                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100">
                                                <div class="flex items-center gap-1 text-[10px] text-slate-500">
                                                    <span class="text-amber-400 material-symbols-outlined text-[14px]">star</span> 5.0
                                                </div>
                                                <div class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">Terjual 10+</div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full py-12 text-center text-slate-400 text-sm border-2 border-dashed border-slate-200 rounded-2xl">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50 block">inventory_2</span>
                                    Belum ada produk di toko ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
                
            @endforeach
        @endif
        </div>
        
        <!-- Tab 2: Semua Produk -->
        <div x-show="activeTab === 'produk'" x-cloak>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-on-surface border-l-4 border-primary pl-3">Semua Produk</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse($products as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white border border-outline-variant hover:border-primary rounded-xl overflow-hidden hover:shadow-lg transition-all flex flex-col">
                    <div class="aspect-square w-full bg-slate-50 relative overflow-hidden">
                        @if($product->images->count() > 0)
                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-3 flex flex-col flex-1">
                        <h3 class="font-bold text-slate-800 text-[11px] md:text-sm line-clamp-2 mb-2 group-hover:text-primary transition-colors h-8">{{ $product->name }}</h3>
                        <div class="mt-auto">
                            @if($product->discount_price)
                                <div class="text-[10px] text-slate-400 line-through mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="font-bold text-primary text-sm md:text-base">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                            @else
                                <div class="font-bold text-primary text-sm md:text-base mt-4">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">Belum ada produk.</div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>


        
        <!-- Tab 3: Kategori -->
        <div x-show="activeTab === 'kategori'" x-cloak>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-on-surface border-l-4 border-primary pl-3">Kategori Produk</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($categories as $category)
                <a href="?category={{ $category->id }}" class="bg-white border border-slate-100 shadow-sm hover:shadow-md hover:border-primary transition-all p-4 rounded-xl flex flex-col items-center justify-center gap-3 group text-center h-32">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <span class="font-bold text-slate-700 text-sm group-hover:text-primary">{{ $category->name }}</span>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">Belum ada kategori.</div>
                @endforelse
            </div>
        </div>
        
        <!-- Tab 4: Profil Toko -->
        <div x-show="activeTab === 'profil'" x-cloak>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Profil Toko</h2>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-700 mb-2 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-xl">storefront</span> Deskripsi Toko</h3>
                            <p class="text-slate-600 leading-relaxed">{{ $store->description ?? 'Toko ini belum menambahkan deskripsi.' }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-bold text-slate-700 mb-2 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-xl">policy</span> Kebijakan Toko</h3>
                            <p class="text-slate-600 leading-relaxed">{{ $store->policy ?? 'Tidak ada kebijakan khusus yang ditetapkan.' }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-100 h-fit space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-slate-400">calendar_month</span>
                            <div>
                                <div class="text-xs text-slate-500">Bergabung Sejak</div>
                                <div class="font-bold text-slate-700">{{ $store->created_at->translatedFormat('F Y') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-slate-400">inventory_2</span>
                            <div>
                                <div class="text-xs text-slate-500">Total Produk</div>
                                <div class="font-bold text-slate-700">{{ $products->total() }} Produk</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-slate-400">group</span>
                            <div>
                                <div class="text-xs text-slate-500">Total Pengikut</div>
                                <div class="font-bold text-slate-700"><span x-text="followersCount"></span> Orang</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</main>
@endsection
