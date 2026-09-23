@extends('layouts.admin')
@section('title', 'Sales Orders')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-7xl mx-auto w-full">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="font-headline-md font-bold text-on-surface">Sales Orders</h2>
            <p class="font-body-md text-on-surface-variant">Monitor and manage digital product sales, store origins, and customer transactions.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-lg bg-green-500/10 border border-green-500/30 text-green-600 dark:text-green-400 text-sm font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="mb-4 p-4 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-blue-400 text-sm font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">info</span>
        {{ session('info') }}
    </div>
    @endif

    <!-- FILTER & SEARCH PANEL -->
    <div class="bg-surface rounded-xl border border-outline-variant p-4 sm:p-5 mb-6 shadow-xs" x-data="{ customDate: '{{ request('period') === 'custom' ? 'true' : 'false' }}' === 'true' }">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="space-y-4">
            
            <!-- Quick Period Filters Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-semibold">
                <span class="text-on-surface-variant mr-1 flex items-center gap-1 shrink-0">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span> Periode:
                </span>
                <a href="{{ route('admin.orders.index', array_merge(request()->except(['period', 'start_date', 'end_date', 'page']), ['period' => ''])) }}" 
                   class="px-3 py-1.5 rounded-full border transition-all whitespace-nowrap {{ !request('period') ? 'bg-primary text-white border-primary shadow-xs' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-highest' }}">
                    Semua Waktu
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->except(['period', 'start_date', 'end_date', 'page']), ['period' => 'today'])) }}" 
                   class="px-3 py-1.5 rounded-full border transition-all whitespace-nowrap {{ request('period') === 'today' ? 'bg-primary text-white border-primary shadow-xs' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-highest' }}">
                    Hari Ini
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->except(['period', 'start_date', 'end_date', 'page']), ['period' => '7_days'])) }}" 
                   class="px-3 py-1.5 rounded-full border transition-all whitespace-nowrap {{ request('period') === '7_days' ? 'bg-primary text-white border-primary shadow-xs' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-highest' }}">
                    7 Hari Terakhir
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->except(['period', 'start_date', 'end_date', 'page']), ['period' => '28_days'])) }}" 
                   class="px-3 py-1.5 rounded-full border transition-all whitespace-nowrap {{ request('period') === '28_days' ? 'bg-primary text-white border-primary shadow-xs' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-highest' }}">
                    28 Hari Terakhir
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->except(['period', 'start_date', 'end_date', 'page']), ['period' => '30_days'])) }}" 
                   class="px-3 py-1.5 rounded-full border transition-all whitespace-nowrap {{ request('period') === '30_days' ? 'bg-primary text-white border-primary shadow-xs' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-highest' }}">
                    30 Hari
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->except(['period', 'start_date', 'end_date', 'page']), ['period' => 'this_month'])) }}" 
                   class="px-3 py-1.5 rounded-full border transition-all whitespace-nowrap {{ request('period') === 'this_month' ? 'bg-primary text-white border-primary shadow-xs' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-highest' }}">
                    Bulan Ini
                </a>
                <button type="button" @click="customDate = !customDate" 
                   class="px-3 py-1.5 rounded-full border transition-all whitespace-nowrap flex items-center gap-1 cursor-pointer {{ request('period') === 'custom' ? 'bg-primary text-white border-primary shadow-xs' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-highest' }}">
                    <span class="material-symbols-outlined text-[14px]">date_range</span> Custom
                </button>
            </div>

            <!-- Custom Date Range Picker (Shown when Custom is selected) -->
            <div x-show="customDate" x-transition class="p-3 bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-wrap items-center gap-3">
                <input type="hidden" name="period" value="custom">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-on-surface-variant font-medium">Dari:</span>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-1.5 text-xs bg-surface border border-outline-variant rounded-lg text-on-surface focus:outline-none focus:border-primary">
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-on-surface-variant font-medium">Sampai:</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-1.5 text-xs bg-surface border border-outline-variant rounded-lg text-on-surface focus:outline-none focus:border-primary">
                </div>
            </div>

            <!-- Input Fields Row: Customer, Toko, Product, Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2">
                <!-- Customer Filter -->
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">person_search</span> Customer / Invoice
                    </label>
                    <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Nama, email, telepon, invoice..." class="w-full px-3.5 py-2 text-xs bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                </div>

                <!-- Toko Filter -->
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">storefront</span> Toko Penjual
                    </label>
                    <select name="store_id" class="w-full px-3 py-2 text-xs bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="">Semua Toko</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Filter -->
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">inventory_2</span> Produk
                    </label>
                    <select name="product_id" class="w-full px-3 py-2 text-xs bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="">Semua Produk</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                                {{ $prod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px]">filter_list</span> Status
                    </label>
                    <select name="status" class="w-full px-3 py-2 text-xs bg-surface-container-lowest border border-outline-variant rounded-xl text-on-surface focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-all">
                        <option value="">Semua Status</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Paid / Downloaded</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed / Expired</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-1 border-t border-outline-variant/50">
                <span class="text-xs text-on-surface-variant">
                    Ditemukan <strong class="text-on-surface">{{ $orders->total() }}</strong> pesanan
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="px-3.5 py-1.5 rounded-xl border border-outline-variant text-xs font-semibold text-on-surface-variant hover:bg-surface-container-highest transition-colors">
                        Reset Filter
                    </a>
                    <button type="submit" class="px-4 py-1.5 rounded-xl bg-primary text-white text-xs font-bold shadow-none hover:opacity-90 transition-opacity flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[15px]">filter_alt</span> Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-md whitespace-nowrap">
                <thead class="bg-surface-container-lowest border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="p-4 font-medium">Invoice & Tanggal</th>
                        <th class="p-4 font-medium">Customer</th>
                        <th class="p-4 font-medium min-w-[280px]">Produk & Toko Penjual</th>
                        <th class="p-4 font-medium">Total Nominal</th>
                        <th class="p-4 font-medium text-center">Status</th>
                        <th class="p-4 font-medium text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($orders as $order)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <!-- Invoice & Date -->
                        <td class="p-4 align-top">
                            <div class="font-bold text-on-surface font-mono text-sm">{{ $order->invoice_number }}</div>
                            <div class="text-[11px] text-on-surface-variant flex items-center gap-1 mt-0.5">
                                <span class="material-symbols-outlined text-[13px]">schedule</span>
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </div>
                        </td>

                        <!-- Customer -->
                        <td class="p-4 align-top">
                            <div class="font-bold text-on-surface text-sm">{{ $order->customer_name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $order->customer_email }}</div>
                            @if($order->customer_phone)
                            <div class="text-[11px] text-on-surface-variant font-mono mt-0.5">{{ $order->customer_phone }}</div>
                            @endif
                        </td>

                        <!-- Product List with Store info underneath -->
                        <td class="p-4 align-top">
                            @if($order->orderItems && $order->orderItems->count() > 0)
                                <div class="flex flex-col gap-2.5 max-w-[320px]">
                                    @foreach($order->orderItems as $item)
                                        @php
                                            $itemStore = $item->product ? $item->product->store : null;
                                        @endphp
                                        <div class="space-y-0.5">
                                            <div class="text-xs font-bold text-on-surface truncate flex items-center gap-1.5" title="{{ $item->product->name ?? 'Produk' }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></span>
                                                <span class="truncate">{{ $item->product->name ?? 'Produk Dihapus' }}</span>
                                                <span class="text-[11px] text-on-surface-variant font-mono shrink-0">(x{{ $item->quantity }})</span>
                                            </div>
                                            
                                            <!-- Store info under product -->
                                            <div class="pl-3 flex items-center gap-1.5 text-[11px] text-on-surface-variant">
                                                <span class="material-symbols-outlined text-[13px] text-primary">storefront</span>
                                                @if($itemStore)
                                                    <a href="{{ route('store.show', $itemStore->slug) }}" target="_blank" class="font-semibold text-primary hover:underline truncate max-w-[180px]" title="{{ $itemStore->name }}">
                                                        {{ $itemStore->name }}
                                                    </a>
                                                    <span class="text-[10px] text-on-surface-variant/60">•</span>
                                                    <a href="{{ route('admin.stores.index', ['search' => $itemStore->name]) }}" class="text-[10px] hover:text-primary transition-colors">
                                                        Kelola
                                                    </a>
                                                @else
                                                    <span class="italic text-on-surface-variant/80">Toko Utama (Pusat)</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                @php
                                    $singleStore = $order->product ? $order->product->store : null;
                                @endphp
                                <div class="space-y-0.5 max-w-[320px]">
                                    <div class="text-xs font-bold text-on-surface truncate" title="{{ $order->product->name ?? 'Deleted Product' }}">
                                        {{ $order->product->name ?? 'Deleted Product' }}
                                    </div>
                                    <div class="flex items-center gap-1.5 text-[11px] text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[13px] text-primary">storefront</span>
                                        @if($singleStore)
                                            <a href="{{ route('store.show', $singleStore->slug) }}" target="_blank" class="font-semibold text-primary hover:underline truncate max-w-[180px]" title="{{ $singleStore->name }}">
                                                {{ $singleStore->name }}
                                            </a>
                                            <span class="text-[10px] text-on-surface-variant/60">•</span>
                                            <a href="{{ route('admin.stores.index', ['search' => $singleStore->name]) }}" class="text-[10px] hover:text-primary transition-colors">
                                                Kelola
                                            </a>
                                        @else
                                            <span class="italic text-on-surface-variant/80">Toko Utama (Pusat)</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </td>

                        <!-- Amount -->
                        <td class="p-4 align-top font-bold text-on-surface text-sm">
                            Rp {{ number_format($order->amount, 0, ',', '.') }}
                        </td>

                        <!-- Status -->
                        <td class="p-4 align-top text-center">
                            @if($order->status === 'paid')
                                <span class="px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-950/40 text-green-700 dark:text-green-400 text-xs font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Paid
                                </span>
                            @elseif($order->status === 'downloaded')
                                <span class="px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 text-xs font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Downloaded
                                </span>
                            @elseif($order->status === 'failed')
                                <span class="px-2.5 py-1 rounded-full bg-red-100 dark:bg-red-950/40 text-red-700 dark:text-red-400 text-xs font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Failed
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full bg-yellow-100 dark:bg-yellow-950/40 text-yellow-700 dark:text-yellow-400 text-xs font-bold inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="p-4 align-top text-right pr-6">
                            <div class="flex items-center justify-end gap-1.5">
                                @if($order->status !== 'paid' && $order->status !== 'downloaded')
                                <form action="{{ route('admin.orders.sync_status', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 bg-orange-600 hover:bg-orange-700 text-white rounded-lg text-xs font-bold flex items-center gap-1 shadow-none transition-colors cursor-pointer" title="Cek status realtime dari Midtrans">
                                        <span class="material-symbols-outlined text-[14px]">sync</span> Sync
                                    </button>
                                </form>

                                <form action="{{ route('admin.orders.approve', $order) }}" method="POST" onsubmit="return confirm('Setujui pesanan {{ $order->invoice_number }} menjadi PAID & kirim email ke pelanggan?');">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold flex items-center gap-1 shadow-none transition-colors cursor-pointer" title="Approve / Tandai Lunas">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Approve
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan {{ $order->invoice_number }} secara permanen?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-error/10 hover:bg-error hover:text-white text-error rounded-lg text-xs font-bold flex items-center gap-1 transition-colors cursor-pointer" title="Hapus Pesanan">
                                        <span class="material-symbols-outlined text-[14px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50 block">receipt_long</span>
                            <p class="font-semibold">Tidak ada data transaksi yang sesuai.</p>
                            <p class="text-xs text-on-surface-variant mt-1">Coba sesuaikan filter pencarian atau periode tanggal Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="p-4 border-t border-outline-variant">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

