{{-- Partial: Single Appearance Block Renderer --}}
@php
    $type = $block['type'] ?? '';
    $data = $block['data'] ?? [];
@endphp

@if($type === 'banner')
    @php $images = $data['images'] ?? []; @endphp
    @if(count($images) > 0)
        <div class="w-full relative overflow-hidden rounded-2xl shadow-sm bg-slate-100 dark:bg-slate-800" x-data="{ 
            activeSlide: 0, 
            slides: {{ count($images) }}, 
            next() { this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1 },
            prev() { this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1 },
            init() { setInterval(() => this.next(), 4000); }
        }">
            <div class="relative w-full h-44 sm:h-64 md:h-80 flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${activeSlide * 100}%)`">
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
            
            @if(count($images) > 1)
            <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5">
                <template x-for="i in slides">
                    <button @click="activeSlide = i - 1" :class="activeSlide === i - 1 ? 'bg-white w-4' : 'bg-white/50 w-2'" class="h-1.5 rounded-full transition-all shadow-sm"></button>
                </template>
            </div>
            <button @click="prev()" class="absolute left-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[16px]">chevron_left</span>
            </button>
            <button @click="next()" class="absolute right-2 top-1/2 -translate-y-1/2 w-7 h-7 rounded-full bg-black/40 hover:bg-black/60 text-white flex items-center justify-center transition-colors">
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
            </button>
            @endif
        </div>
    @endif

@elseif($type === 'single_image')
    @php
        $singleLink = trim($data['link'] ?? '');
        $isSingleExternal = !empty($singleLink) && (str_starts_with($singleLink, 'http://') || str_starts_with($singleLink, 'https://'));
    @endphp
    @if(!empty($data['image_url']))
        @if(!empty($singleLink))
            <a href="{{ $singleLink }}" @if($isSingleExternal) target="_blank" rel="noopener noreferrer" @endif class="block w-full rounded-2xl overflow-hidden shadow-sm hover:opacity-95 transition-opacity">
                <img src="{{ $data['image_url'] }}" alt="Promo Banner" class="w-full h-auto object-cover max-h-[350px]">
            </a>
        @else
            <div class="w-full rounded-2xl overflow-hidden shadow-sm">
                <img src="{{ $data['image_url'] }}" alt="Promo Banner" class="w-full h-auto object-cover max-h-[350px]">
            </div>
        @endif
    @endif

@elseif($type === 'text')
    @php 
        $textContent = trim($data['text'] ?? '');
    @endphp
    @if(!empty($textContent))
        @php 
            $alignClass = match($data['align'] ?? 'center') {
                'left' => 'text-left',
                'right' => 'text-right',
                default => 'text-center'
            };
            $sizeClass = match($data['size'] ?? 'md') {
                'sm' => 'text-xs',
                'lg' => 'text-lg',
                default => 'text-sm'
            };
        @endphp
        <div class="w-full px-3 py-2 {{ $alignClass }}">
            <p class="text-slate-700 dark:text-slate-300 {{ $sizeClass }} font-medium whitespace-pre-line">{{ $textContent }}</p>
        </div>
    @endif

@elseif($type === 'flash_sale')
    @php 
        $endDate = $data['end_date'] ?? '';
        $selectedIds = $data['product_ids'] ?? [];

        if (!empty($selectedIds)) {
            $flashProducts = \App\Models\Product::whereIn('id', $selectedIds)
                ->where('store_id', $store->id)
                ->where('is_active', true)
                ->with(['images' => fn($q) => $q->where('is_main', true)->limit(1)])
                ->get()
                ->sortBy(fn($p) => array_search($p->id, $selectedIds))
                ->take(8)
                ->values();
        } else {
            $flashProducts = \App\Models\Product::where('store_id', $store->id)
                ->where('is_active', true)
                ->whereNotNull('discount_price')
                ->where('discount_price', '>', 0)
                ->with(['images' => fn($q) => $q->where('is_main', true)->limit(1)])
                ->take(4)
                ->get();
            if ($flashProducts->isEmpty()) {
                $flashProducts = \App\Models\Product::where('store_id', $store->id)
                    ->where('is_active', true)
                    ->with(['images' => fn($q) => $q->where('is_main', true)->limit(1)])
                    ->latest()
                    ->take(4)
                    ->get();
            }
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
                    this.minutes = Math.floor((this.timeLeft % (1000 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((this.timeLeft % 1000) / 1000);
                } else {
                    this.days = 0; this.hours = 0; this.minutes = 0; this.seconds = 0;
                }
            }
         }">

        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <h3 class="font-black italic text-base sm:text-lg tracking-wide text-rose-500 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[20px]">bolt</span> FLASH SALE
                </h3>
                <div class="flex items-center gap-1 text-xs font-bold text-white">
                    <span x-show="days > 0" x-text="days + 'h'" class="bg-slate-800 px-1.5 py-0.5 rounded"></span>
                    <span x-text="String(hours).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                    <span class="text-slate-800">:</span>
                    <span x-text="String(minutes).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                    <span class="text-slate-800">:</span>
                    <span x-text="String(seconds).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($flashProducts as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="bg-white dark:bg-[#161b22] rounded-xl overflow-hidden hover:shadow-lg transition-all group border border-slate-100 dark:border-[#30363d] flex flex-col">
                    <div class="aspect-square w-full bg-slate-50 dark:bg-[#0d1117] relative overflow-hidden">
                        @if($product->images->count() > 0)
                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-3xl">inventory_2</span>
                            </div>
                        @endif
                        @if($product->discount_price && $product->discount_price < $product->price)
                        <div class="absolute top-0 right-0 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-bl-md">
                            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                        </div>
                        @endif
                    </div>
                    <div class="p-2.5 flex flex-col flex-1">
                        <h4 class="text-slate-800 dark:text-slate-200 text-xs font-bold line-clamp-1 group-hover:text-rose-500 transition-colors">{{ $product->name }}</h4>
                        <div class="mt-auto pt-1.5">
                            @if($product->discount_price && $product->discount_price < $product->price)
                                <div class="font-black text-rose-500 text-xs sm:text-sm">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-slate-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @else
                                <div class="font-black text-rose-500 text-xs sm:text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@elseif($type === 'voucher')
    @php
        $selectedCampaignIds = $data['campaign_ids'] ?? [];
        $blockCampaigns = $campaigns ?? collect();
        if (!empty($selectedCampaignIds) && is_array($selectedCampaignIds)) {
            $blockCampaigns = $blockCampaigns->whereIn('id', array_map('intval', $selectedCampaignIds));
        }
    @endphp
    @if($blockCampaigns->isNotEmpty())
        <div class="w-full p-4 rounded-2xl bg-surface-container border border-outline-variant/60">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-[18px] text-primary">confirmation_number</span>
                <span class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white">Kupon &amp; Voucher Toko</span>
                <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-extrabold">{{ $blockCampaigns->count() }} Tersedia</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($blockCampaigns as $campaign)
                    <x-voucher-card :campaign="$campaign" mode="browse" />
                @endforeach
            </div>
        </div>
    @endif

@elseif($type === 'products')
    @php 
        $count = (int)($data['count'] ?? 8);
        $displayProducts = $products->take($count);
    @endphp
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white border-l-4 border-primary pl-2.5">
                {{ ($data['type'] ?? 'latest') === 'bestseller' ? 'Produk Terlaris' : 'Pilihan Produk' }}
            </h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3.5">
            @forelse($displayProducts as $product)
                @php
                    $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
                @endphp
                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white dark:bg-[#161b22] border border-slate-200/80 dark:border-[#30363d] hover:border-primary rounded-xl overflow-hidden hover:shadow-lg transition-all flex flex-col">
                    <div class="aspect-square w-full bg-slate-50 dark:bg-[#0d1117] relative overflow-hidden">
                        @if($product->images->count() > 0)
                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-3xl">inventory_2</span>
                            </div>
                        @endif
                        @if($hasDiscount)
                            <div class="absolute top-1.5 right-1.5 bg-rose-500 text-white font-black text-[10px] px-1.5 py-0.5 rounded-md shadow-xs">
                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </div>
                        @endif
                    </div>
                    <div class="p-3 flex flex-col flex-1">
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-xs line-clamp-2 mb-1 group-hover:text-primary transition-colors leading-snug">{{ $product->name }}</h4>
                        <div class="mt-auto pt-1.5 border-t border-slate-100 dark:border-slate-800">
                            @if($hasDiscount)
                                <div class="text-[10px] text-slate-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="font-black text-primary text-xs sm:text-sm">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                            @else
                                <div class="font-black text-primary text-xs sm:text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-6 text-center text-xs text-slate-400">Belum ada produk untuk ditampilkan.</div>
            @endforelse
        </div>
    </div>

@endif
