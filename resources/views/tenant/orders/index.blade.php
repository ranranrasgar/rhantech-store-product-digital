@extends('layouts.tenant')

@section('title', 'Pesanan Saya')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-6 bg-surface-container-lowest dark:bg-[#0d1117]">
    <div class="max-w-7xl mx-auto space-y-4">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-on-surface dark:text-white">Pesanan Saya</h2>
            <div class="flex flex-wrap items-center gap-3">
                <button onclick="alert('Fitur eksport data (CSV/Excel) sedang dalam pengembangan.')" class="px-4 py-2 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded bg-surface dark:bg-[#161b22] text-on-surface dark:text-white hover:bg-surface-container-lowest transition-colors flex items-center gap-1">
                    Export
                </button>
                <button onclick="alert('Riwayat aktivitas pelanggan akan segera hadir.')" class="px-4 py-2 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded bg-surface dark:bg-[#161b22] text-on-surface dark:text-white hover:bg-surface-container-lowest transition-colors flex items-center gap-1">
                    Riwayat Download
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
            
            <!-- Tabs -->
            <div class="border-b border-outline-variant dark:border-[#30363d] flex overflow-x-auto hide-scrollbar">
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $tab === 'all' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-gray-400 hover:text-on-surface transition-colors' }}">Semua</a>
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $tab === 'pending' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-gray-400 hover:text-on-surface transition-colors' }}">Belum Bayar (Pending)</a>
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'completed', 'page' => null])) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $tab === 'completed' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-gray-400 hover:text-on-surface transition-colors' }}">Selesai (Paid/Downloaded)</a>
                <a href="{{ route('tenant.orders.index', array_merge(request()->query(), ['tab' => 'cancelled', 'page' => null])) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $tab === 'cancelled' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-gray-400 hover:text-on-surface transition-colors' }}">Pengembalian/Pembatalan</a>
            </div>

            <div class="p-4 md:p-6 space-y-6">
                <!-- Search & Filters -->
                <form method="GET" action="{{ route('tenant.orders.index') }}" class="flex flex-wrap items-center gap-4">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="flex items-center w-full md:w-auto">
                        <select name="search_type" class="px-3 py-2 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] border-r-0 text-on-surface-variant rounded-l focus:outline-none focus:border-primary">
                            <option value="invoice" {{ request('search_type') === 'invoice' ? 'selected' : '' }}>No. Pesanan</option>
                            <option value="customer" {{ request('search_type') === 'customer' ? 'selected' : '' }}>Nama Pembeli</option>
                        </select>
                        <input type="text" name="search_query" value="{{ request('search_query') }}" placeholder="Cari..." class="flex-1 md:w-64 px-4 py-2 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] rounded-r focus:outline-none focus:border-primary">
                    </div>
                    <div class="flex items-center w-full md:w-auto">
                        <select name="product_id" onchange="this.form.submit()" class="flex-1 md:w-48 px-4 py-2 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] text-on-surface-variant rounded focus:outline-none focus:border-primary">
                            <option value="">Semua Produk</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 text-sm font-semibold border border-primary text-primary rounded hover:bg-primary/5 transition-colors">Terapkan</button>
                    <a href="{{ route('tenant.orders.index', ['tab' => $tab]) }}" class="px-4 py-2 text-sm font-semibold border border-outline-variant dark:border-[#30363d] text-on-surface dark:text-white rounded hover:bg-surface-container-lowest transition-colors">Atur Ulang</a>
                </form>

                <!-- Order Count -->
                <div class="text-sm font-bold text-on-surface dark:text-white">
                    {{ $orders->total() }} Pesanan
                </div>

                <!-- Orders Table -->
                <div class="overflow-x-auto rounded-md">
                    <table class="w-full text-left text-sm whitespace-nowrap border border-outline-variant dark:border-[#30363d]">
                        <thead class="bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d] text-on-surface-variant dark:text-gray-400">
                            <tr>
                                <th class="p-4 font-normal min-w-[300px]">Produk & Pembeli</th>
                                <th class="p-4 font-normal text-center">Dibayar Pembeli</th>
                                <th class="p-4 font-normal text-center">Status</th>
                                <th class="p-4 font-normal text-center">Waktu Transaksi</th>
                                <th class="p-4 font-normal text-center">Invoice</th>
                                <th class="p-4 font-normal text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant dark:divide-[#30363d] bg-surface dark:bg-[#161b22]">
                            @forelse($orders as $order)
                            <tr class="hover:bg-surface-container-lowest/50 dark:hover:bg-[#0d1117]/50 transition-colors">
                                <td class="p-4 whitespace-normal min-w-[300px]">
                                    <div class="flex items-start gap-3">
                                        <!-- Placeholder Image for product -->
                                        <div class="w-16 h-16 flex-shrink-0 bg-surface-container-high border border-outline-variant rounded overflow-hidden flex items-center justify-center text-on-surface-variant">
                                            @if($order->product && $order->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="material-symbols-outlined">code</span>
                                            @endif
                                        </div>
                                        <!-- Info -->
                                        <div>
                                            <div class="font-bold text-on-surface dark:text-white line-clamp-2 leading-tight">{{ $order->product->name ?? 'Produk Dihapus' }}</div>
                                            <div class="mt-2 text-xs">
                                                <span class="text-on-surface-variant dark:text-gray-400 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">person</span> {{ $order->customer_name }}</span>
                                            </div>
                                            <div class="text-[11px] text-on-surface-variant dark:text-gray-400 mt-0.5">
                                                {{ $order->customer_email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <div class="font-bold text-on-surface dark:text-white">Rp{{ number_format($order->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="p-4 align-middle text-center">
                                    @if($order->status === 'paid' || $order->status === 'downloaded')
                                        <div class="text-xs font-bold text-green-600 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded px-2 py-1 inline-block">Selesai</div>
                                    @elseif($order->status === 'failed')
                                        <div class="text-xs font-bold text-error bg-error/10 border border-error/20 rounded px-2 py-1 inline-block">Dibatalkan</div>
                                    @else
                                        <div class="text-xs font-bold text-yellow-600 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded px-2 py-1 inline-block">Belum Bayar</div>
                                    @endif
                                </td>
                                <td class="p-4 align-middle text-center text-on-surface-variant">
                                    <div class="text-xs">{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px]">{{ $order->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="p-4 align-middle text-center text-xs text-on-surface-variant font-mono">
                                    {{ $order->invoice_number }}
                                </td>
                                <td class="p-4 align-middle text-center">
                                    <button type="button" onclick="alert('Fitur detail pesanan akan segera hadir.')" class="text-sm font-semibold text-primary hover:underline bg-transparent border-none p-0 cursor-pointer">Lihat Detail</button>
                                </td>
                            </tr>
                            @empty
                            <!-- Empty State -->
                            <tr>
                                <td colspan="6" class="p-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                        <div class="w-16 h-20 border-2 border-outline-variant/30 rounded mb-4 relative flex items-center justify-center opacity-50">
                                            <div class="absolute top-0 left-1/2 -translate-x-1/2 -mt-2 w-8 h-3 border-2 border-outline-variant/30 rounded-full bg-surface-container-lowest"></div>
                                            <div class="w-8 border-b-2 border-outline-variant/30 mt-2"></div>
                                            <div class="w-6 border-b-2 border-outline-variant/30 absolute bottom-6 left-4"></div>
                                        </div>
                                        <p class="text-sm">Belum ada pesanan yang sesuai dengan filter Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="pt-2 w-full">
                    {{ $orders->links() }}
                </div>
                @endif
                
            </div>
        </div>
        
    </div>
</div>
@endsection
