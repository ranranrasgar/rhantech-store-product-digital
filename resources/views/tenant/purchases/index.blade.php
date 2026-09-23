@extends('layouts.tenant')

@section('title', 'Pembelian Saya')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#fafafa] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] transition-colors duration-200 pb-28 md:pb-12 relative"
     id="purchases-container"
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
                    let newContent = doc.getElementById('purchases-container').innerHTML;
                    document.getElementById('purchases-container').innerHTML = newContent;
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
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-black tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-slate-700 dark:text-slate-200 flex items-center justify-center shrink-0 border border-slate-200/60 dark:border-slate-700/60">
                        <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                    </span>
                    Pembelian Saya
                </h1>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    Kelola riwayat pesanan dan download file produk digital Anda.
                </p>
            </div>
            <div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-[#000000] hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all active:scale-95 shadow-2xs">
                    <span class="material-symbols-outlined text-[16px] text-slate-400">storefront</span>
                    <span>Jelajahi Produk</span>
                </a>
            </div>
        </div>

        <!-- Main Unified Card Container -->
        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
            
            <!-- Modern Tab Filter (Underline Style matching Products) -->
            <div class="border-b border-zinc-100 dark:border-zinc-800 px-4 md:px-6 flex items-center gap-6 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50" style="-ms-overflow-style: none; scrollbar-width: none;">
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'all' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Semua <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'all' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $counts['all'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'completed', 'page' => null])) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'completed' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Sukses <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'completed' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $counts['completed'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'pending' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Menunggu Pembayaran <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'pending' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $counts['pending'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'cancelled', 'page' => null])) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'cancelled' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Gagal / Batal <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'cancelled' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}">{{ $counts['cancelled'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Sleek Search Input -->
            <div class="p-4 md:p-6 border-b border-zinc-100 dark:border-zinc-800">
                <form method="GET" action="{{ route('tenant.purchases.index') }}" class="flex items-center gap-3">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari invoice atau nama produk digital..." 
                               class="w-full pl-10 pr-8 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-zinc-900 dark:text-zinc-100 transition-all placeholder-slate-400"
                               onkeydown="if(event.key === 'Enter'){this.form.submit();}">
                        @if(request('search'))
                            <a href="{{ route('tenant.purchases.index', ['tab' => $tab]) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="hidden md:flex px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs rounded-xl transition-all active:scale-95 items-center gap-1.5 shrink-0">
                        <span>Cari</span>
                    </button>
                </form>
            </div>

            <!-- Purchase List: Mobile Cards (block md:hidden) -->
            <div class="block md:hidden p-4 space-y-3.5 bg-slate-50 dark:bg-transparent">
            @forelse($purchases as $order)
                @php
                    $firstItem = $order->orderItems->first();
                    $firstProduct = $firstItem ? $firstItem->product : $order->product;
                    $itemCount = $order->orderItems->count();
                @endphp
                <div x-data="{ copied: false }" class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 space-y-3.5">
                    
                    <!-- Top Row: Invoice, Date & Status -->
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-slate-100 dark:border-[#1d273d]">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="font-mono font-bold text-xs text-zinc-800 dark:text-zinc-200 truncate">
                                {{ $order->invoice_number }}
                            </span>
                            <button type="button" 
                                    @click="navigator.clipboard.writeText('{{ $order->invoice_number }}'); copied = true; setTimeout(() => copied = false, 1500)" 
                                    class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                                    title="Salin Nomor Invoice">
                                <span class="material-symbols-outlined text-[14px]" x-show="!copied">content_copy</span>
                                <span class="material-symbols-outlined text-[14px] text-emerald-500" x-show="copied" x-cloak>check</span>
                            </button>
                        </div>

                        <!-- Status Badge (Minimal Modern) -->
                        <div>
                            @if($order->status === 'paid' || $order->status === 'downloaded')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                </span>
                            @elseif($order->status === 'failed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Middle: Product Info -->
                    <div class="flex items-start gap-3">
                        <div class="w-13 h-13 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0 flex items-center justify-center">
                            @if($firstProduct && $firstProduct->images->count() > 0)
                                @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $firstProduct->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-slate-400 text-[22px]">inventory_2</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-zinc-900 dark:text-zinc-100 text-xs leading-snug line-clamp-2">
                                {{ $firstProduct->name ?? 'Produk Digital' }}
                            </h3>
                            @if($itemCount > 1)
                                <div class="text-[11px] font-semibold text-zinc-500 dark:text-zinc-400 mt-0.5">
                                    +{{ $itemCount - 1 }} produk lainnya
                                </div>
                            @endif
                            <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">storefront</span>
                                <span class="truncate">{{ $firstProduct->store->store_name ?? 'Official Store' }}</span>
                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                <span>{{ $order->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom: Price & Modern Action Button -->
                    <div class="pt-3 border-t border-slate-100 dark:border-[#1d273d] flex items-center justify-between gap-3">
                        <div>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold block">Total Bayar</span>
                            <div class="font-black text-zinc-900 dark:text-zinc-100 text-sm">
                                Rp {{ number_format($order->amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Action Button (Modern Dark/Solid Pill) -->
                        <div>
                            @if($order->status === 'paid' || $order->status === 'downloaded')
                                <a href="{{ route('products.download', $order->download_token) }}" 
                                   target="_blank" 
                                   class="inline-flex items-center justify-center gap-1.5 py-2 px-3.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-bold active:scale-95 transition-all shadow-2xs">
                                    <span class="material-symbols-outlined text-[16px]">cloud_download</span>
                                    <span>Download File</span>
                                </a>
                            @elseif($order->status === 'pending')
                                <a href="{{ route('checkout.payment', $order->invoice_number) }}" 
                                   class="inline-flex items-center justify-center gap-1.5 py-2 px-3.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold active:scale-95 transition-all shadow-2xs">
                                    <span class="material-symbols-outlined text-[16px]">payment</span>
                                    <span>Bayar Sekarang</span>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 italic px-2 py-1">Dibatalkan</span>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-10 text-center text-slate-400">
                    <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-slate-400 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-[24px]">shopping_bag</span>
                    </div>
                    <h3 class="font-bold text-sm text-zinc-800 dark:text-zinc-100 mb-1">Belum ada riwayat pembelian</h3>
                    <p class="text-xs text-slate-400 mb-4">Anda belum pernah membeli produk digital apapun pada tab ini.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs active:scale-95 transition-all shadow-2xs">
                        <span class="material-symbols-outlined text-[16px]">explore</span>
                        Lihat Katalog Produk
                    </a>
                </div>
            @endforelse
            </div>

            <!-- Purchase List: Desktop Table (hidden md:block) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="p-4 md:px-6">No. Invoice & Tanggal</th>
                            <th class="p-4 md:px-6 min-w-[280px]">Produk Dipesan</th>
                            <th class="p-4 md:px-6">Total Harga</th>
                            <th class="p-4 md:px-6 text-center">Status</th>
                            <th class="p-4 md:px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($purchases as $order)
                        @php
                            $firstItem = $order->orderItems->first();
                            $firstProduct = $firstItem ? $firstItem->product : $order->product;
                            $itemCount = $order->orderItems->count();
                        @endphp
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                            
                            <!-- Invoice & Date -->
                            <td class="p-4 md:px-6">
                                <div class="font-mono font-bold text-zinc-800 dark:text-zinc-200 text-xs flex items-center gap-1.5" x-data="{ copied: false }">
                                    <span>{{ $order->invoice_number }}</span>
                                    <button type="button" 
                                            @click="navigator.clipboard.writeText('{{ $order->invoice_number }}'); copied = true; setTimeout(() => copied = false, 1500)" 
                                            class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                                            title="Salin Nomor Invoice">
                                        <span class="material-symbols-outlined text-[14px]" x-show="!copied">content_copy</span>
                                        <span class="material-symbols-outlined text-[14px] text-emerald-500" x-show="copied" x-cloak>check</span>
                                    </button>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1">
                                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </td>

                            <!-- Products -->
                            <td class="p-4 md:px-6 whitespace-normal min-w-[280px]">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($firstProduct && $firstProduct->images->count() > 0)
                                            @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $firstProduct->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-slate-400 text-[18px]">inventory_2</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-zinc-900 dark:text-zinc-100 line-clamp-1 leading-snug text-xs md:text-sm">
                                            @if($itemCount > 1)
                                                {{ $firstProduct->name ?? 'Produk Digital' }} <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">(+{{ $itemCount - 1 }} lainnya)</span>
                                            @else
                                                {{ $firstProduct->name ?? 'Produk Digital' }}
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">storefront</span>
                                            {{ $firstProduct->store->store_name ?? 'Official Store' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="p-4 md:px-6">
                                <div class="font-black text-zinc-900 dark:text-zinc-100 text-sm">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </div>
                                @if($order->payment_type)
                                <div class="text-[10px] text-slate-400 font-semibold mt-0.5 uppercase tracking-wider">
                                    {{ str_replace('_', ' ', $order->payment_type) }}
                                </div>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="p-4 md:px-6 text-center">
                                @if($order->status === 'paid' || $order->status === 'downloaded')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                    </span>
                                @elseif($order->status === 'failed')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="p-4 md:px-6 text-center">
                                @if($order->status === 'paid' || $order->status === 'downloaded')
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('products.download', $order->download_token) }}" 
                                           target="_blank" 
                                           class="inline-flex justify-center items-center gap-1.5 px-3 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold transition-all text-xs active:scale-95 shadow-2xs" 
                                           title="Unduh File Produk">
                                            <span class="material-symbols-outlined text-[15px]">cloud_download</span>
                                            Download File
                                        </a>
                                    </div>
                                @elseif($order->status === 'pending')
                                    <a href="{{ route('checkout.payment', $order->invoice_number) }}" 
                                       class="inline-flex justify-center items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 transition-all text-xs active:scale-95 shadow-2xs">
                                        <span class="material-symbols-outlined text-[15px]">payment</span>
                                        Bayar
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-slate-400 flex items-center justify-center mb-3">
                                        <span class="material-symbols-outlined text-[24px]">shopping_bag</span>
                                    </div>
                                    <h3 class="font-bold text-sm text-zinc-800 dark:text-zinc-100 mb-1">Belum ada riwayat pembelian</h3>
                                    <p class="text-xs text-slate-400 mb-4">Anda belum pernah membeli produk digital apapun.</p>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs active:scale-95 transition-all shadow-2xs">
                                        <span class="material-symbols-outlined text-[16px]">explore</span>
                                        Lihat Katalog Produk
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Consolidated Pagination -->
            @if($purchases->hasPages())
            <div class="p-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-center bg-white dark:bg-[#000000]">
                {{ $purchases->links() }}
            </div>
            @endif
        </div> <!-- End of Main Unified Card Container -->

    </div>
</div>
@endsection
