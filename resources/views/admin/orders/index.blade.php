@extends('layouts.admin')
@section('title', 'Sales Orders')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-7xl mx-auto w-full">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="font-headline-md font-bold text-on-surface">Sales Orders</h2>
            <p class="font-body-md text-on-surface-variant">Monitor and manage digital product sales and transactions.</p>
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

    <div class="bg-surface rounded-md border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-md whitespace-nowrap">
                <thead class="bg-surface-container-lowest border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="p-4 font-medium">Invoice</th>
                        <th class="p-4 font-medium">Customer</th>
                        <th class="p-4 font-medium">Product</th>
                        <th class="p-4 font-medium">Amount</th>
                        <th class="p-4 font-medium">Status</th>
                        <th class="p-4 font-medium">Date</th>
                        <th class="p-4 font-medium text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($orders as $order)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="p-4 font-bold text-on-surface font-mono text-sm">{{ $order->invoice_number }}</td>
                        <td class="p-4">
                            <div class="font-bold text-on-surface">{{ $order->customer_name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $order->customer_email }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="p-4">
                            @if($order->orderItems && $order->orderItems->count() > 0)
                                <div class="flex flex-col gap-1 max-w-[220px]">
                                    @foreach($order->orderItems as $item)
                                        <div class="text-xs font-semibold text-on-surface truncate" title="{{ $item->product->name ?? 'Produk' }}">
                                            • {{ $item->product->name ?? 'Produk' }} (x{{ $item->quantity }})
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="line-clamp-1 max-w-[200px]" title="{{ $order->product->name ?? 'Deleted Product' }}">
                                    {{ $order->product->name ?? 'Deleted Product' }}
                                </div>
                            @endif
                        </td>
                        <td class="p-4 font-bold text-on-surface">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                        <td class="p-4">
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
                        <td class="p-4 text-xs text-on-surface-variant whitespace-nowrap">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="p-4 text-right pr-6">
                            <div class="flex items-center justify-end gap-2">
                                @if($order->status !== 'paid' && $order->status !== 'downloaded')
                                <form action="{{ route('admin.orders.sync_status', $order) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 bg-sky-600 hover:bg-sky-700 text-white rounded text-xs font-bold flex items-center gap-1 shadow-sm transition-colors cursor-pointer" title="Cek status realtime dari Midtrans">
                                        <span class="material-symbols-outlined text-[14px]">sync</span> Sync Midtrans
                                    </button>
                                </form>

                                <form action="{{ route('admin.orders.approve', $order) }}" method="POST" onsubmit="return confirm('Setujui pesanan {{ $order->invoice_number }} menjadi PAID & kirim email ke pelanggan?');">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-bold flex items-center gap-1 shadow-sm transition-colors cursor-pointer" title="Approve / Tandai Lunas">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Approve (Paid)
                                    </button>
                                </form>
                                @endif

                                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Hapus pesanan {{ $order->invoice_number }} secara permanen?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-error/10 hover:bg-error hover:text-white text-error rounded text-xs font-bold flex items-center gap-1 transition-colors cursor-pointer" title="Hapus Pesanan">
                                        <span class="material-symbols-outlined text-[14px]">delete</span> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">receipt_long</span>
                            <p>No sales orders found yet.</p>
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
