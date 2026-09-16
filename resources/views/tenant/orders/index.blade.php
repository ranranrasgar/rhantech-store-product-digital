@extends('layouts.tenant')

@section('title', 'Riwayat Penjualan')

@section('content')
<div class="flex-1 overflow-y-auto p-3.5 sm:p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-4 md:space-y-6">
        
        <!-- Flash Notification Alerts -->
        @if(session('success'))
            <div class="p-3.5 md:p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs md:text-sm flex items-center gap-2.5 shadow-xs">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 md:p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs md:text-sm flex items-center gap-2.5 shadow-xs">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-[20px]">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- 1. DEDICATED NATIVE MOBILE EXPERIENCE (Visible on Mobile < 768px Only)   -->
        <!-- ========================================================================= -->
        <div class="block md:hidden space-y-4 pb-12">
            
            <!-- Mobile Header -->
            <div class="flex items-center justify-between gap-3 pt-1">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                        Riwayat Pesanan
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200">
                            {{ $counts['all'] ?? $orders->total() }}
                        </span>
                    </h1>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                        Pantau transaksi & verifikasi pesanan produk digital
                    </p>
                </div>
                <a href="{{ route('tenant.payouts.index') }}" class="shrink-0 px-3 py-2 rounded-xl bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center gap-1.5 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[16px] text-emerald-500">account_balance_wallet</span>
                    <span>Pencairan</span>
                </a>
            </div>

            <!-- Horizontal Swipeable Pill Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-3.5 px-3.5 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden" style="scrollbar-width: none; -ms-overflow-style: none;">
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'all' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95' }}">
                    <span>Semua</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'all' ? 'bg-white/20 text-white dark:bg-black/20 dark:text-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">{{ $counts['all'] ?? 0 }}</span>
                </a>

                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'completed', 'page' => null])) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'completed' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95' }}">
                    <span>Sukses</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'completed' ? 'bg-white/20 text-white' : 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400' }}">{{ $counts['completed'] ?? 0 }}</span>
                </a>

                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'pending' ? 'bg-amber-500 text-white shadow-2xs' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95' }}">
                    <span>Pending</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400' }}">{{ $counts['pending'] ?? 0 }}</span>
                </a>

                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'cancelled', 'page' => null])) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'cancelled' ? 'bg-rose-500 text-white shadow-2xs' : 'bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] text-slate-600 dark:text-slate-300 active:scale-95' }}">
                    <span>Gagal</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'cancelled' ? 'bg-white/20 text-white' : 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400' }}">{{ $counts['cancelled'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Mobile Search & Filter Card (Clean 2-row layout, No Overflow) -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-3 shadow-xs space-y-2.5">
                <form method="GET" action="{{ route('tenant.orders.index') }}" class="space-y-2.5">
                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <!-- Row 1: Search Input (Full Width) -->
                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                        </div>
                        <input type="text" 
                               name="search_query" 
                               value="{{ request('search_query') }}" 
                               placeholder="Cari nomor invoice atau nama pembeli..." 
                               class="w-full pl-9 pr-9 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00838f]/20 focus:border-[#00838f] text-slate-900 dark:text-white placeholder-slate-400">
                        @if(request('search_query'))
                            <a href="{{ route('tenant.orders.index', ['tab' => $tab, 'product_id' => request('product_id'), 'search_type' => request('search_type')]) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>

                    <!-- Row 2: Type Filter & Product Filter (2 Equal Columns Grid) -->
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <select name="search_type" onchange="this.form.submit()" class="w-full px-2.5 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-[#00838f] truncate">
                                <option value="invoice" {{ request('search_type', 'invoice') === 'invoice' ? 'selected' : '' }}>No. Invoice</option>
                                <option value="customer" {{ request('search_type') === 'customer' ? 'selected' : '' }}>Nama / Email</option>
                            </select>
                        </div>
                        <div>
                            <select name="product_id" onchange="this.form.submit()" class="w-full px-2.5 py-2 text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-[#00838f] truncate">
                                <option value="">Semua Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(request('search_query') || request('product_id'))
                        <div class="flex items-center justify-between pt-0.5 text-[11px]">
                            <span class="text-slate-400">Filter aktif</span>
                            <a href="{{ route('tenant.orders.index', ['tab' => $tab]) }}" class="font-bold text-[#00838f] hover:underline inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">filter_alt_off</span> Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Mobile Native Order Feed Cards -->
            <div class="space-y-3">
                @forelse($orders as $order)
                    @php
                        $tenantItems = $order->orderItems->filter(function($item) use ($store) {
                            return $item->product && $item->product->store_id == $store->id;
                        });
                        $firstItem = $tenantItems->first() ?? $order->orderItems->first();
                        $firstProduct = $firstItem ? $firstItem->product : $order->product;
                        $tenantTotal = $tenantItems->isNotEmpty() ? $tenantItems->sum(function($item) { return $item->price * $item->quantity; }) : $order->amount;
                    @endphp

                    <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-4 shadow-xs space-y-3">
                        
                        <!-- Top Row: Invoice & Status Badge -->
                        <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-slate-100 dark:border-[#222f49]">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="material-symbols-outlined text-[16px] text-[#00838f]">receipt</span>
                                <span class="font-mono font-black text-xs text-slate-900 dark:text-white truncate">
                                    {{ $order->invoice_number }}
                                </span>
                            </div>
                            <div>
                                @if($order->status === 'paid' || $order->status === 'downloaded')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                    </span>
                                @elseif($order->status === 'failed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Middle Row: Product Image & Details -->
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                @if($firstProduct && $firstProduct->images->count() > 0)
                                    @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-slate-400 text-[20px]">inventory_2</span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white line-clamp-1 leading-snug">
                                    {{ $firstProduct->name ?? 'Produk Digital' }}
                                    @if($tenantItems->count() > 1)
                                        <span class="text-[10px] font-semibold text-[#00838f] dark:text-teal-400">(+{{ $tenantItems->count() - 1 }} lainnya)</span>
                                    @endif
                                </h4>
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-1">
                                    <span>{{ $firstItem ? 'Qty: ' . $firstItem->quantity . 'x' : '1 item' }}</span>
                                    <span>•</span>
                                    <span>{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Buyer Info & Store Earnings Box -->
                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1 text-xs font-bold text-slate-800 dark:text-slate-200 truncate">
                                    <span class="material-symbols-outlined text-[15px] text-slate-400">person</span>
                                    <span class="truncate">{{ $order->customer_name }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400 truncate mt-0.5">
                                    {{ $order->customer_email }}
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] text-slate-400 block font-medium">Pendapatan Toko</span>
                                <span class="text-xs sm:text-sm font-black text-emerald-600 dark:text-emerald-400">
                                    Rp{{ number_format($tenantTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons (Only Platform-Approved Actions) -->
                        @if($order->status === 'pending' || in_array($order->status, ['paid', 'downloaded']))
                        <div class="pt-2 border-t border-slate-100 dark:border-[#222f49] flex items-center justify-end gap-2">
                            @if($order->status === 'pending')
                                <form action="{{ route('tenant.orders.mark_paid', $order) }}" method="POST" class="w-full sm:w-auto" onsubmit="if(!confirm('Tandai pesanan ini sebagai Lunas? Email dan notifikasi link produk akan otomatis dikirim ke pembeli.')) return false; const btn = this.querySelector('button'); btn.disabled = true; btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.innerHTML = '<span class=\'material-symbols-outlined text-[16px] animate-spin\'>progress_activity</span><span>Memproses...</span>';">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-full sm:w-auto py-2 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-2xs active:scale-95 transition-all">
                                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                                        <span>Tandai Lunas</span>
                                    </button>
                                </form>
                            @elseif(in_array($order->status, ['paid', 'downloaded']))
                                <form action="{{ route('tenant.orders.resend_email', $order) }}" method="POST" class="w-full sm:w-auto" onsubmit="const btn = this.querySelector('button'); btn.disabled = true; btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.innerHTML = '<span class=\'material-symbols-outlined text-[16px] animate-spin\'>progress_activity</span><span>Mengirim...</span>';">
                                    @csrf
                                    <button type="submit" class="w-full sm:w-auto py-2 px-3.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs font-bold flex items-center justify-center gap-1.5 active:scale-95 transition-all">
                                        <span class="material-symbols-outlined text-[16px]">send</span>
                                        <span>Kirim Link Produk</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                        @endif

                    </div>
                @empty
                    <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-8 text-center shadow-xs">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-[#00838f] flex items-center justify-center mx-auto mb-3">
                            <span class="material-symbols-outlined text-[28px]">receipt_long</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Belum Ada Riwayat Pesanan</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Pesanan dari pembeli akan otomatis tercatat dan masuk ke daftar ini.</p>
                        @if(request('search_query') || request('product_id') || $tab !== 'all')
                            <a href="{{ route('tenant.orders.index') }}" class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-xl">
                                <span class="material-symbols-outlined text-[16px]">refresh</span> Reset Filter
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <!-- Mobile Pagination -->
            @if($orders->hasPages())
                <div class="pt-2 flex justify-center">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

        <!-- ========================================================================= -->
        <!-- 2. PRESERVED DESKTOP VIEW (Visible on Desktop >= 768px Only)              -->
        <!-- ========================================================================= -->
        <div class="hidden md:block space-y-6">
            
            <!-- Desktop Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                        Riwayat Penjualan
                    </h1>
                    <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Pantau transaksi pelanggan, status verifikasi pembayaran, dan pesanan produk digital.
                    </p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('tenant.payouts.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold transition-all shadow-xs flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-emerald-500">account_balance_wallet</span>
                        Pencairan Saldo
                    </a>
                </div>
            </div>

            <!-- Main Card Container -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-xs overflow-hidden">
                
                <!-- Filter Tabs -->
                <div class="border-b border-slate-100 dark:border-[#222f49] px-6 flex items-center gap-8 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                    <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'all' ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Semua Transaksi ({{ $counts['all'] ?? 0 }})
                    </a>
                    <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'completed', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'completed' ? 'text-emerald-600 dark:text-emerald-400 border-emerald-600 dark:border-emerald-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Sukses ({{ $counts['completed'] ?? 0 }})
                    </a>
                    <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'pending' ? 'text-amber-600 dark:text-amber-400 border-amber-600 dark:border-amber-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Menunggu Pembayaran ({{ $counts['pending'] ?? 0 }})
                    </a>
                    <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'cancelled', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'cancelled' ? 'text-rose-600 dark:text-rose-400 border-rose-600 dark:border-rose-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Dibatalkan / Kedaluwarsa ({{ $counts['cancelled'] ?? 0 }})
                    </a>
                </div>

                <!-- Search & Filters Row -->
                <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#222f49]">
                    <form method="GET" action="{{ route('tenant.orders.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        
                        <div class="flex items-center flex-1">
                            <select name="search_type" class="px-3.5 py-2.5 text-xs md:text-sm bg-slate-100 dark:bg-[#161f33] border border-r-0 border-slate-200 dark:border-[#222f49] text-slate-700 dark:text-slate-200 rounded-l-xl focus:outline-none focus:border-[#00838f] shrink-0">
                                <option value="invoice" {{ request('search_type') === 'invoice' ? 'selected' : '' }}>No. Invoice</option>
                                <option value="customer" {{ request('search_type') === 'customer' ? 'selected' : '' }}>Nama / Email Pembeli</option>
                            </select>
                            <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Ketik kata kunci pencarian..." class="flex-1 px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-r-xl focus:outline-none focus:ring-2 focus:ring-[#00838f]/20 focus:border-[#00838f] text-slate-900 dark:text-white transition-all">
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select name="product_id" onchange="this.form.submit()" class="px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:border-[#00838f] transition-all">
                                <option value="">Semua Produk</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>

                            <button type="submit" class="px-4 py-2.5 text-xs md:text-sm font-bold bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white rounded-xl transition-colors active:scale-95">
                                Terapkan
                            </button>
                            <a href="{{ route('tenant.orders.index', ['tab' => $tab]) }}" class="px-4 py-2.5 text-xs md:text-sm font-semibold border border-slate-200 dark:border-[#222f49] text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Orders Table -->
                <div class="overflow-x-auto pb-12">
                    <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                        <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="p-4 md:px-6">No. Invoice & Tanggal</th>
                                <th class="p-4 md:px-6 min-w-[280px]">Produk Dipesan</th>
                                <th class="p-4 md:px-6">Pelanggan</th>
                                <th class="p-4 md:px-6 text-right">Pendapatan Toko</th>
                                <th class="p-4 md:px-6 text-center">Status</th>
                                <th class="p-4 md:px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                            @forelse($orders as $order)
                            @php
                                $tenantItems = $order->orderItems->filter(function($item) use ($store) {
                                    return $item->product && $item->product->store_id == $store->id;
                                });
                                $firstItem = $tenantItems->first() ?? $order->orderItems->first();
                                $firstProduct = $firstItem ? $firstItem->product : $order->product;
                                $tenantTotal = $tenantItems->isNotEmpty() ? $tenantItems->sum(function($item) { return $item->price * $item->quantity; }) : $order->amount;
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                                
                                <!-- Invoice & Date -->
                                <td class="p-4 md:px-6">
                                    <div class="font-mono font-bold text-slate-900 dark:text-white text-xs">
                                        {{ $order->invoice_number }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-1">
                                        {{ $order->created_at->format('d M Y, H:i') }} WIB
                                    </div>
                                </td>

                                <!-- Products -->
                                <td class="p-4 md:px-6 whitespace-normal min-w-[280px]">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($firstProduct && $firstProduct->images->count() > 0)
                                                @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="material-symbols-outlined text-slate-400 text-[20px]">inventory_2</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-slate-900 dark:text-white line-clamp-1 leading-snug text-xs md:text-sm">
                                                @if($tenantItems->count() > 1)
                                                    {{ $firstProduct->name ?? 'Produk Digital' }} <span class="text-xs font-semibold text-[#00838f] dark:text-teal-400">(+{{ $tenantItems->count() - 1 }} lainnya)</span>
                                                @else
                                                    {{ $firstProduct->name ?? 'Produk Digital' }}
                                                @endif
                                            </div>
                                            <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                                {{ $firstItem ? 'Qty: ' . $firstItem->quantity . 'x' : '1 item' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Customer -->
                                <td class="p-4 md:px-6">
                                    <div class="font-bold text-slate-900 dark:text-white text-xs md:text-sm flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[15px] text-slate-400">person</span>
                                        {{ $order->customer_name }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $order->customer_email }}
                                    </div>
                                </td>

                                <!-- Tenant Earning Amount -->
                                <td class="p-4 md:px-6 text-right">
                                    <div class="font-extrabold text-slate-900 dark:text-white text-sm">
                                        Rp {{ number_format($tenantTotal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-semibold">
                                        Saldo Masuk
                                    </div>
                                </td>

                                <!-- Status -->
                                <td class="p-4 md:px-6 text-center">
                                    @if($order->status === 'paid' || $order->status === 'downloaded')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                        </span>
                                    @elseif($order->status === 'failed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 md:px-6 text-center whitespace-nowrap">
                                    @if($order->status === 'pending')
                                        <form action="{{ route('tenant.orders.mark_paid', $order) }}" method="POST" class="inline-block" onsubmit="if(!confirm('Tandai pesanan ini sebagai Lunas? Email berisi link produk akan otomatis dikirim ke pembeli.')) return false; const btn = this.querySelector('button'); btn.disabled = true; btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.innerHTML = '<span class=\'material-symbols-outlined text-[15px] animate-spin\'>progress_activity</span> Memproses...';">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/60 transition-colors">
                                                <span class="material-symbols-outlined text-[15px]">check_circle</span>
                                                Tandai Lunas
                                            </button>
                                        </form>
                                    @elseif(in_array($order->status, ['paid', 'downloaded']))
                                        <form action="{{ route('tenant.orders.resend_email', $order) }}" method="POST" class="inline-block" onsubmit="const btn = this.querySelector('button'); btn.disabled = true; btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.innerHTML = '<span class=\'material-symbols-outlined text-[15px] animate-spin\'>progress_activity</span> Mengirim...';">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-colors">
                                                <span class="material-symbols-outlined text-[15px]">forward_to_inbox</span>
                                                <span>Kirim Link</span>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-[32px]">receipt_long</span>
                                        </div>
                                        <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada riwayat pesanan</h3>
                                        <p class="text-xs text-slate-400">Pesanan dari pembeli akan otomatis tercatat dan masuk ke daftar ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                @if($orders->hasPages())
                <div class="p-5 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                    {{ $orders->links() }}
                </div>
                @endif

            </div>

        </div>

    </div>
</div>

@endsection
