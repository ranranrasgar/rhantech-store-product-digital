@extends('layouts.admin')
@section('title', 'Sales Orders')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-7xl mx-auto w-full">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="font-headline-md font-bold text-on-surface">Sales Orders</h2>
            <p class="font-body-md text-on-surface-variant">Monitor your digital product sales and transactions.</p>
        </div>
    </div>

    <div class="bg-surface rounded-xl border border-outline-variant shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-md">
                <thead class="bg-surface-container-lowest border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="p-4 font-medium">Invoice</th>
                        <th class="p-4 font-medium">Customer</th>
                        <th class="p-4 font-medium">Product</th>
                        <th class="p-4 font-medium">Amount</th>
                        <th class="p-4 font-medium">Status</th>
                        <th class="p-4 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($orders as $order)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors">
                        <td class="p-4 font-bold text-on-surface">{{ $order->invoice_number }}</td>
                        <td class="p-4">
                            <div class="font-bold text-on-surface">{{ $order->customer_name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $order->customer_email }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="p-4">
                            <div class="line-clamp-1 max-w-[200px]" title="{{ $order->product->name ?? 'Deleted Product' }}">
                                {{ $order->product->name ?? 'Deleted Product' }}
                            </div>
                        </td>
                        <td class="p-4 font-bold text-on-surface">Rp {{ number_format($order->amount, 0, ',', '.') }}</td>
                        <td class="p-4">
                            @if($order->status === 'paid')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">Paid</span>
                            @elseif($order->status === 'downloaded')
                                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">Downloaded</span>
                            @elseif($order->status === 'failed')
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Failed/Expired</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold">Pending</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm text-on-surface-variant">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">
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
