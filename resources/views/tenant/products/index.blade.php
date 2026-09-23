@extends('layouts.tenant')

@section('title', 'Katalog Produk')

@section('content')
<div class="flex-1 overflow-y-auto p-3 sm:p-4 md:p-8 bg-[#fafafa] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] transition-colors duration-200 relative"
     id="products-container"
     x-data="{
        loading: false,
        navigate(e) {
            let link = e.target.closest('a.ajax-tab, nav[role=\'navigation\'] a');
            if(!link) return;
            e.preventDefault();
            this.loading = true;
            fetch(link.href)
                .then(r => r.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newContent = doc.getElementById('products-container').innerHTML;
                    document.getElementById('products-container').innerHTML = newContent;
                    history.pushState(null, '', link.href);
                    this.loading = false;
                });
        }
     }"
     @click="navigate"
>
    <!-- Loading overlay -->
    <div x-show="loading" class="absolute inset-0 z-50 bg-white/50 dark:bg-black/50 backdrop-blur-sm flex items-center justify-center" style="display: none;">
        <div class="w-10 h-10 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
    <div class="max-w-7xl mx-auto">
        
        @if(session('success'))
            <div class="mb-4 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs md:text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs md:text-sm font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- MOBILE NATIVE APP VIEW (Visible on Mobile < 768px Only)                   -->
        <!-- ========================================================================= -->
        <div class="block md:hidden space-y-4 pb-20">
            
            <!-- Mobile Header (Clean, Add Button moved to Bottom-Right Floating Button) -->
            <div class="pt-1">
                <h1 class="text-xl font-black tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                    Katalog Produk
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200">
                        {{ $allCount }}
                    </span>
                </h1>
                <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                    Kelola etalase & status penjualan produk digital
                </p>
            </div>

            <!-- Horizontal Swipeable Pill Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-3 px-3 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden" style="scrollbar-width: none; -ms-overflow-style: none;">
                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" 
                   class="ajax-tab shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'all' ? 'bg-orange-500 text-white dark:bg-orange-600 shadow-none shadow-orange-500/30' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Semua</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'all' ? 'bg-white/20 text-white' : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400' }}">{{ $allCount }}</span>
                </a>

                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'active', 'page' => null])) }}" 
                   class="ajax-tab shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'active' ? 'bg-orange-500 text-white shadow-none shadow-orange-500/30' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Tayang</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'active' ? 'bg-white/20 text-white' : 'bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400' }}">{{ $activeCount }}</span>
                </a>

                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" 
                   class="ajax-tab shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'pending' ? 'bg-orange-500 text-white shadow-none shadow-orange-500/30' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>In Review</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'pending' ? 'bg-white/20 text-white' : 'bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400' }}">{{ $pendingCount }}</span>
                </a>

                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'rejected', 'page' => null])) }}" 
                   class="ajax-tab shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'rejected' ? 'bg-orange-500 text-white shadow-none shadow-orange-500/30' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Revisi</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'rejected' ? 'bg-white/20 text-white' : 'bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400' }}">{{ $rejectedCount }}</span>
                </a>

                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'inactive', 'page' => null])) }}" 
                   class="ajax-tab shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'inactive' ? 'bg-orange-500 text-white shadow-none shadow-orange-500/30' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Draft</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'inactive' ? 'bg-white/20 text-white' : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400' }}">{{ $inactiveCount }}</span>
                </a>
            </div>

            <!-- Search & Filters -->
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3 shadow-xs space-y-2.5">
                <form method="GET" action="{{ route('tenant.products.index') }}" class="space-y-2.5">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    
                    <!-- Search Input -->
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama produk atau SKU..." 
                               class="w-full pl-9 pr-8 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-zinc-900 dark:text-zinc-100 placeholder-slate-400">
                        @if(request('search'))
                            <a href="{{ route('tenant.products.index', ['tab' => $tab, 'category' => request('category'), 'sort' => request('sort')]) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>

                    <!-- Category & Sort Selectors -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <select name="category" onchange="this.form.submit()" class="w-full px-2.5 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-orange-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="sort" onchange="this.form.submit()" class="w-full px-2.5 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-orange-500">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Rendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tinggi</option>
                            </select>
                        </div>
                    </div>

                    @if(request('search') || request('category') || request('sort'))
                        <div class="flex justify-end pt-0.5">
                            <a href="{{ route('tenant.products.index', ['tab' => $tab]) }}" class="text-[11px] font-bold text-orange-600 dark:text-orange-400 hover:underline inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">filter_alt_off</span> Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Mobile Product Cards List -->
            <div class="space-y-3">
                @forelse($products as $product)
                    @php
                        $effectivePrice = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price 
                            ? $product->discount_price 
                            : $product->price;
                        $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
                        $discountPercent = $hasDiscount && $product->price > 0 
                            ? round((($product->price - $product->discount_price) / $product->price) * 100) 
                            : 0;
                        $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first();
                    @endphp

                    <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3.5 shadow-xs transition-all relative"
                         x-data="{ menuOpen: false }">
                        
                        <!-- Top Information Row -->
                        <div class="flex items-start gap-3">
                            <!-- Thumbnail -->
                            <div class="w-18 h-18 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0 flex items-center justify-center relative shadow-xs">
                                @if($mainImg)
                                    <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-slate-400 text-[26px]">inventory_2</span>
                                @endif
                                
                                @if(!$product->is_active)
                                    <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-[2px] flex items-center justify-center text-white text-[9px] font-extrabold tracking-wider uppercase">
                                        Draft
                                    </div>
                                @endif
                            </div>

                            <!-- Meta & Details -->
                            <div class="flex-1 min-w-0">
                                <!-- Category & Status Tag -->
                                <div class="flex items-center justify-between gap-1 mb-1">
                                    <span class="text-[10px] font-bold text-orange-600 dark:text-orange-400 truncate max-w-[150px]">
                                        {{ $product->category->name ?? 'Digital File' }}
                                    </span>

                                    <!-- Status Pill -->
                                    <div>
                                        @if($product->approval_status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-300/60 dark:border-amber-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> In Review
                                            </span>
                                        @elseif($product->approval_status === 'rejected')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-300/60 dark:border-rose-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                            </span>
                                        @elseif($product->is_active)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-300/60 dark:border-emerald-800/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tayang
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Non-Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Title -->
                                <a href="{{ route('tenant.products.edit', $product) }}" class="font-bold text-xs text-zinc-900 dark:text-zinc-100 line-clamp-2 leading-snug hover:text-orange-600 dark:hover:text-orange-400 transition-colors">
                                    {{ $product->name }}
                                </a>

                                <!-- Price & Discount -->
                                <div class="mt-1.5 flex items-baseline gap-2 flex-wrap">
                                    <span class="text-sm font-black text-zinc-900 dark:text-zinc-100">
                                        Rp {{ number_format($effectivePrice, 0, ',', '.') }}
                                    </span>
                                    @if($hasDiscount)
                                        <span class="text-[10px] text-slate-400 line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[9px] font-extrabold px-1.5 py-0.2 rounded-md bg-rose-500/10 text-rose-600 dark:text-rose-400">
                                            -{{ $discountPercent }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Notice if rejected -->
                        @if($product->approval_status === 'rejected' && $product->rejection_reason)
                            <div class="mt-2.5 p-2 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 text-[11px] text-rose-700 dark:text-rose-300 flex items-start gap-1.5">
                                <span class="material-symbols-outlined text-[15px] shrink-0 text-rose-500 mt-0.5">error</span>
                                <div class="flex-1 min-w-0">
                                    <span class="font-bold">Alasan Ditolak:</span> {{ $product->rejection_reason }}
                                </div>
                            </div>
                        @endif

                        <!-- Action Bar -->
                        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-[#1d273d] flex items-center justify-between gap-2">
                            <!-- Product ID -->
                            <span class="text-[10px] font-mono text-slate-400">
                                #{{ $product->id }}
                            </span>

                            <!-- Buttons -->
                            <div class="flex items-center gap-1.5">
                                <!-- Ubah -->
                                <a href="{{ route('tenant.products.edit', $product) }}" 
                                   class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all inline-flex items-center gap-1 active:scale-95">
                                    <span class="material-symbols-outlined text-[15px] text-orange-500">edit</span>
                                    <span>Ubah</span>
                                </a>

                                <!-- Preview Store Page -->
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   target="_blank" 
                                   class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-zinc-600 dark:text-zinc-300 transition-all inline-flex items-center justify-center active:scale-95" 
                                   title="Buka Halaman Produk di Web">
                                    <span class="material-symbols-outlined text-[16px] text-indigo-500">open_in_new</span>
                                </a>

                                <!-- More Actions Trigger -->
                                <div class="relative">
                                    <button type="button" 
                                            @click="menuOpen = !menuOpen" 
                                            @click.away="menuOpen = false"
                                            class="p-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-zinc-600 dark:text-zinc-300 transition-all inline-flex items-center justify-center active:scale-95 cursor-pointer">
                                        <span class="material-symbols-outlined text-[16px]">more_vert</span>
                                    </button>

                                    <!-- Dropdown Popover -->
                                    <div x-show="menuOpen" 
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 scale-95"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 scale-100"
                                         x-transition:leave-end="opacity-0 scale-95"
                                         class="absolute right-0 bottom-full mb-2 w-48 bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-none z-50 py-1.5 divide-y divide-slate-100 dark:divide-[#1d273d]" 
                                         style="display: none;">
                                        
                                        <div class="py-1">
                                            <!-- Salin / Duplicate -->
                                            <a href="{{ route('tenant.products.create', ['copy' => $product->id]) }}" 
                                               class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors">
                                                <span class="material-symbols-outlined text-[16px] text-orange-500">content_copy</span>
                                                <span>Salin Produk</span>
                                            </a>
                                        </div>

                                        <div class="py-1">
                                            <!-- Toggle Active -->
                                            <form action="{{ route('tenant.products.toggle_active', $product) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors text-left cursor-pointer">
                                                    <span class="material-symbols-outlined text-[16px] {{ $product->is_active ? 'text-amber-500' : 'text-emerald-500' }}">
                                                        {{ $product->is_active ? 'pause_circle' : 'play_circle' }}
                                                    </span> 
                                                    <span>{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="py-1">
                                            <!-- Hapus -->
                                            <form action="{{ route('tenant.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $product->name }} secara permanen?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors text-left cursor-pointer">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                    <span>Hapus Permanen</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 text-center text-slate-400">
                        <div class="w-14 h-14 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-[28px]">inventory_2</span>
                        </div>
                        <h3 class="font-bold text-sm text-zinc-800 dark:text-zinc-100 mb-1">Tidak ada produk ditemukan</h3>
                        <p class="text-xs text-slate-400 mb-4 max-w-xs mx-auto">
                            @if(request('search') || request('category') || $tab !== 'all')
                                Coba ubah filter atau kata kunci pencarian Anda.
                            @else
                                Mulai jual template, ebook, atau source code aplikasi Anda.
                            @endif
                        </p>
                        @if(request('search') || request('category') || $tab !== 'all')
                            <a href="{{ route('tenant.products.index') }}" class="px-4 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 text-xs font-bold inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">refresh</span> Reset Filter
                            </a>
                        @else
                            <a href="{{ route('tenant.products.create') }}" class="px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs inline-flex items-center gap-1.5 active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[16px]">add</span> Tambah Produk Pertama
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Mobile Pagination -->
            @if($products->hasPages())
                <div class="pt-2 flex justify-center">
                    {{ $products->links() }}
                </div>
            @endif

            <a href="{{ route('tenant.products.create') }}" 
               class="fixed bottom-20 right-4 z-40 md:hidden w-14 h-14 rounded-full bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white border-2 border-white dark:border-slate-800 flex items-center justify-center cursor-pointer select-none group active:scale-95 transition-all"
               title="Tambah Produk Baru"
               aria-label="Tambah Produk Baru">
                <span class="material-symbols-outlined text-[30px] font-bold transition-transform duration-300 group-hover:rotate-90">add</span>
            </a>
        </div>

        <!-- ========================================================================= -->
        <!-- DESKTOP VIEW (Visible on md: >= 768px Only)                               -->
        <!-- ========================================================================= -->
        <div class="hidden md:block space-y-6">
            
            <!-- Header & Action Hub -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2.5">
                        Katalog Produk
                    </h1>
                    <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                        Kelola etalase produk digital, pantau status publikasi, dan perbarui harga.
                    </p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('tenant.products.create') }}" class="px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs md:text-sm font-bold transition-all flex items-center gap-2 active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Tambah Produk Baru
                    </a>
                </div>
            </div>

            <!-- Main Card Container -->
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
                
                <!-- Filter Tabs -->
                <div class="border-b border-zinc-100 dark:border-zinc-800 px-6 flex items-center gap-6 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                    <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'all' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                        Semua Produk <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'all' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $allCount }}</span>
                    </a>
                    <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'active', 'page' => null])) }}" class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'active' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                        Aktif / Tayang <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'active' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $activeCount }}</span>
                    </a>
                    <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'pending' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                        Menunggu Review <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'pending' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $pendingCount }}</span>
                    </a>
                    <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'rejected', 'page' => null])) }}" class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'rejected' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                        Perlu Revisi (Ditolak) <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'rejected' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $rejectedCount }}</span>
                    </a>
                    <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'inactive', 'page' => null])) }}" class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'inactive' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                        Non-Aktif / Draft <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'inactive' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $inactiveCount }}</span>
                    </a>
                </div>

                <!-- Search, Filter & Bulk Actions Bar -->
                <div class="p-5 md:p-6 border-b border-zinc-100 dark:border-zinc-800 space-y-4">
                    <form method="GET" action="{{ route('tenant.products.index') }}" id="filterForm" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        
                        <div class="flex-1 relative flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk digital atau SKU..." class="w-full pl-10 pr-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-zinc-900 dark:text-zinc-100 transition-all" onkeydown="if(event.key === 'Enter'){this.form.submit();}">
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select name="category" onchange="this.form.submit()" class="px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-orange-500 transition-all">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <select name="sort" onchange="this.form.submit()" class="px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-orange-500 transition-all">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                            </select>

                            <a href="{{ route('tenant.products.index', ['tab' => $tab]) }}" class="px-4 py-2.5 text-xs md:text-sm font-semibold border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Products Clean Table -->
                <div class="overflow-x-auto pb-24">
                    <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                        <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="p-4 md:px-6 w-12 text-center">
                                    <input type="checkbox" id="selectAllCheckbox" onchange="toggleAllCheckboxes(this)" class="rounded border-zinc-300 dark:border-zinc-800 text-orange-500 focus:ring-0 cursor-pointer">
                                </th>
                                <th class="p-4 md:px-6 min-w-[320px]">Informasi Produk</th>
                                <th class="p-4 md:px-6">Kategori & Tipe</th>
                                <th class="p-4 md:px-6">Harga Jual</th>
                                <th class="p-4 md:px-6 text-center">Status</th>
                                <th class="p-4 md:px-6 text-right pr-8">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                            @forelse($products as $product)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                                <td class="p-4 md:px-6 text-center">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="product-checkbox rounded border-zinc-300 dark:border-zinc-800 text-orange-500 focus:ring-0 cursor-pointer">
                                </td>
                                
                                <!-- Product Name & Thumbnail -->
                                <td class="p-4 md:px-6 whitespace-normal min-w-[320px]">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0 flex items-center justify-center relative">
                                            @if($product->images->count() > 0)
                                                @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                                <img src="{{ asset('storage/' . $mainImg->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="material-symbols-outlined text-slate-400 text-[24px]">inventory_2</span>
                                            @endif
                                            
                                            @if(!$product->is_active)
                                                <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-xs flex items-center justify-center text-white text-[9px] font-bold tracking-wider uppercase">Draft</div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('tenant.products.edit', $product) }}" class="font-bold text-zinc-900 dark:text-zinc-100 hover:text-orange-600 dark:hover:text-orange-400 transition-colors line-clamp-1 leading-snug">
                                                {{ $product->name }}
                                            </a>
                                            <div class="text-[11px] text-slate-400 mt-1 font-mono flex items-center gap-2">
                                                <span>ID: #{{ $product->id }}</span>
                                                @if($product->slug)
                                                    <span>•</span>
                                                    <span class="truncate max-w-[140px]">{{ $product->slug }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Category & Type -->
                                <td class="p-4 md:px-6">
                                    <div class="font-semibold text-zinc-800 dark:text-zinc-200">
                                        {{ $product->category->name ?? 'Kategori Umum' }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $product->type->name ?? 'Digital File' }}
                                    </div>
                                </td>

                                <!-- Price -->
                                <td class="p-4 md:px-6">
                                    <div class="font-extrabold text-zinc-900 dark:text-zinc-100">
                                        Rp {{ number_format($product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price ? $product->discount_price : $product->price, 0, ',', '.') }}
                                    </div>
                                    @if($product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price)
                                        <div class="text-[11px] text-slate-400 line-through">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="p-4 md:px-6 text-center">
                                    @if($product->approval_status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-800 animate-pulse">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> In Review
                                        </span>
                                    @elseif($product->approval_status === 'rejected')
                                        <div class="inline-flex flex-col items-center">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-300 dark:border-rose-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                            </span>
                                            @if($product->rejection_reason)
                                                <span class="text-[11px] text-rose-600 dark:text-rose-400 font-medium max-w-[170px] truncate mt-1 cursor-help" title="{{ $product->rejection_reason }}">
                                                    ⚠️ {{ $product->rejection_reason }}
                                                </span>
                                            @endif
                                        </div>
                                    @elseif($product->is_active)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Tayang
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Non-Aktif
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions Dropdown & Buttons -->
                                <td class="p-4 md:px-6 text-right pr-8">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route('tenant.products.edit', $product) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-colors inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[15px]">edit</span> Ubah
                                        </a>

                                        <!-- Dropdown Menu -->
                                        <div class="relative group/dropdown">
                                            <button class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                                                <span class="material-symbols-outlined text-[18px]">more_vert</span>
                                            </button>
                                            <div class="absolute right-0 top-full mt-1 bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-none opacity-0 invisible group-hover/dropdown:opacity-100 group-hover/dropdown:visible transition-all duration-150 z-50 w-52 py-2 text-left">
                                                
                                                <!-- Salin / Copy -->
                                                <a href="{{ route('tenant.products.create', ['copy' => $product->id]) }}" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors">
                                                    <span class="material-symbols-outlined text-[16px] text-orange-500">content_copy</span> Salin Produk
                                                </a>

                                                <!-- Tampilan Toko Publik -->
                                                <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors">
                                                    <span class="material-symbols-outlined text-[16px] text-indigo-500">open_in_new</span> Lihat di Web
                                                </a>

                                                <div class="border-t border-slate-100 dark:border-[#1d273d] my-1"></div>

                                                <!-- Toggle Active -->
                                                <form action="{{ route('tenant.products.toggle_active', $product) }}" method="POST" class="w-full text-left">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors">
                                                        <span class="material-symbols-outlined text-[16px] {{ $product->is_active ? 'text-amber-500' : 'text-emerald-500' }}">
                                                            {{ $product->is_active ? 'pause_circle' : 'play_circle' }}
                                                        </span> 
                                                        {{ $product->is_active ? 'Nonaktifkan Produk' : 'Aktifkan Produk' }}
                                                    </button>
                                                </form>

                                                <!-- Hapus -->
                                                <form action="{{ route('tenant.products.destroy', $product) }}" method="POST" class="w-full text-left" onsubmit="return confirm('Hapus produk {{ $product->name }} secara permanen?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                                                        <span class="material-symbols-outlined text-[16px]">delete</span> Hapus Permanen
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-16 h-16 rounded-2xl bg-orange-500/10 text-orange-500 flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-[32px]">inventory_2</span>
                                        </div>
                                        <h3 class="font-bold text-base text-zinc-800 dark:text-zinc-100 mb-1">Belum ada produk digital</h3>
                                        <p class="text-xs text-slate-400 mb-5">Mulai tambahkan template, ebook, atau source code aplikasi Anda.</p>
                                        <a href="{{ route('tenant.products.create') }}" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs transition-all active:scale-95">
                                            Tambah Produk Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                @if($products->hasPages())
                <div class="p-5 border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
                    {{ $products->links() }}
                </div>
                @endif

            </div>

        </div>

    </div>
</div>

<script>
    function toggleAllCheckboxes(source) {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = source.checked;
        });
    }
</script>
@endsection
