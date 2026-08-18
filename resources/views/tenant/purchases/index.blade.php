@extends('layouts.tenant')

@section('title', 'Pembelian Saya')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Pembelian Saya
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Riwayat pesanan produk digital yang telah Anda beli.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('products.index') }}" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs md:text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                    Cari Produk Lain
                </a>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Filter Tabs -->
            <div class="border-b border-slate-100 dark:border-[#222f49] px-6 flex items-center gap-8 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'all', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'all' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Semua Transaksi
                </a>
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'completed', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'completed' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Sukses (Bisa Diunduh)
                </a>
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'pending', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'pending' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Menunggu Pembayaran
                </a>
                <a href="{{ route('tenant.purchases.index', array_merge(request()->query(), ['tab' => 'cancelled', 'page' => null])) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'cancelled' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Dibatalkan / Gagal
                </a>
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto pb-12">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="p-4 md:px-6">No. Invoice & Tanggal</th>
                            <th class="p-4 md:px-6 min-w-[280px]">Produk Dipesan</th>
                            <th class="p-4 md:px-6">Total Harga</th>
                            <th class="p-4 md:px-6 text-center">Status</th>
                            <th class="p-4 md:px-6 text-center">Aksi (Download)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($purchases as $order)
                        @php
                            $firstItem = $order->orderItems->first();
                            $firstProduct = $firstItem ? $firstItem->product : $order->product;
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
                                            @if($order->orderItems->count() > 1)
                                                {{ $firstProduct->name ?? 'Produk Digital' }} <span class="text-xs font-semibold text-sky-600 dark:text-sky-400">(+{{ $order->orderItems->count() - 1 }} lainnya)</span>
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
                                <div class="font-extrabold text-slate-900 dark:text-white text-sm">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </div>
                                @if($order->payment_type)
                                <div class="text-[10px] text-slate-400 font-semibold mt-0.5 uppercase">
                                    {{ str_replace('_', ' ', $order->payment_type) }}
                                </div>
                                @endif
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

                            <td class="p-4 md:px-6 text-center">
                                @if($order->status === 'paid' || $order->status === 'downloaded')
                                    <a href="{{ route('products.download', $order->download_token) }}" target="_blank" class="inline-flex justify-center items-center gap-1.5 px-4 py-1.5 rounded-xl bg-sky-50 dark:bg-sky-900/20 text-sky-600 dark:text-sky-400 font-bold hover:bg-sky-100 dark:hover:bg-sky-900/40 transition-colors text-xs border border-sky-200 dark:border-sky-800/30">
                                        <span class="material-symbols-outlined text-[16px]">cloud_download</span>
                                        Download
                                    </a>
                                @elseif($order->status === 'pending')
                                    <a href="{{ route('checkout.payment', $order->invoice_number) }}" class="inline-flex justify-center items-center gap-1.5 px-4 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 font-bold hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors text-xs border border-amber-200 dark:border-amber-800/30">
                                        <span class="material-symbols-outlined text-[16px]">payment</span>
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
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-[32px]">shopping_bag</span>
                                    </div>
                                    <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada riwayat pembelian</h3>
                                    <p class="text-xs text-slate-400 mb-4">Anda belum pernah membeli produk digital apapun.</p>
                                    <a href="{{ route('products.index') }}" class="px-5 py-2 rounded-xl bg-sky-600 text-white font-bold text-xs hover:bg-sky-700 transition-colors">Lihat Katalog Produk</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            @if($purchases->hasPages())
            <div class="p-5 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                {{ $purchases->links() }}
            </div>
            @endif

        </div>

    </div>
</div>
@endsection
