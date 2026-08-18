@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-container-max mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Marketplace Overview</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Ringkasan performa penjualan produk digital, status toko, dan aktivitas transaksi.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-primary text-on-primary rounded-md font-label-md font-bold hover:opacity-90 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    Semua Transaksi
                </a>
            </div>
        </div>

        <!-- 4 Primary Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Total Revenue -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">payments</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600">Total</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Total Pendapatan</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>

            <!-- Total Sales Orders -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">receipt_long</span>
                    </div>
                    <span class="text-xs font-bold text-sky-600">Sukses</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Transaksi Berhasil</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ number_format($totalOrders) }}</h3>
            </div>
            
            <!-- Total Stores -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-purple-500/10 text-purple-600 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">storefront</span>
                    </div>
                    <span class="text-xs font-bold text-purple-600">{{ $totalProducts }} Produk</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Total Toko</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ number_format($totalStores) }}</h3>
            </div>

            <!-- New Messages -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#E0F2FE] rounded-bl-full opacity-30"></div>
                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="w-10 h-10 rounded-lg bg-surface-container-high text-on-surface-variant flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                    </div>
                    @if($newMessages > 0)
                        <span class="px-2 py-0.5 bg-[#CCFBF1] text-[#0F766E] rounded-full text-xs font-bold">+{{ $newMessages }} baru</span>
                    @endif
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1 relative z-10">Pesan Masuk</p>
                <div class="flex items-baseline gap-2 relative z-10">
                    <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ $newMessages }}</h3>
                    <span class="text-xs text-on-surface-variant">belum dibaca</span>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Revenue Growth -->
            <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex justify-between items-center mb-lg">
                    <div>
                        <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Tren Penjualan Produk</h3>
                        <p class="text-xs text-on-surface-variant">Grafik pendapatan riil 6 bulan terakhir</p>
                    </div>
                </div>
                <!-- Box border for chart area -->
                <div class="border border-outline-variant rounded-lg p-4 relative h-72 w-full flex items-end gap-2 bg-surface">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Order Status -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg">
                <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-1">Status Transaksi</h3>
                <p class="text-xs text-on-surface-variant mb-6">Distribusi status pesanan di sistem</p>
                <div class="relative h-44 w-full flex justify-center items-center">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col justify-center items-center pointer-events-none mt-2">
                        <span class="font-display-md text-display-md font-bold text-on-surface leading-none">{{ $allOrdersCount }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mt-1">Pesanan</span>
                    </div>
                </div>
                <div class="mt-6 space-y-2.5 px-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#10B981]"></span>
                            <span class="font-body-md text-on-surface">Sukses / Dibayar</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentSuccess }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#F59E0B]"></span>
                            <span class="font-body-md text-on-surface">Menunggu / Pending</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentPending }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#EF4444]"></span>
                            <span class="font-body-md text-on-surface">Gagal / Dibatalkan</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentFailed }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Recent Transactions List -->
            <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Transaksi Terbaru</h3>
                    <a href="{{ route('admin.orders.index') }}" class="font-label-sm text-primary hover:underline">Semua Transaksi</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Invoice / Pembeli</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Produk</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right pr-4">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50">
                            @forelse($recentTransactions as $order)
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="py-4">
                                    <div class="font-bold text-on-surface font-mono text-sm">{{ $order->invoice_number }}</div>
                                    <div class="text-xs text-on-surface-variant mt-0.5">{{ $order->customer_email }}</div>
                                </td>
                                <td class="py-4">
                                    @if($order->orderItems && $order->orderItems->count() > 0 && $order->orderItems->first()->product)
                                        <div class="font-body-md font-semibold text-on-surface max-w-[200px] truncate" title="{{ $order->orderItems->first()->product->name }}">
                                            {{ $order->orderItems->first()->product->name }}
                                        </div>
                                        <div class="text-xs text-on-surface-variant truncate max-w-[200px] flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[12px]">storefront</span> 
                                            {{ $order->orderItems->first()->product->store->store_name ?? 'Toko' }}
                                        </div>
                                    @else
                                        <div class="text-xs text-slate-400 italic">Produk dihapus/tidak diketahui</div>
                                    @endif
                                </td>
                                <td class="py-4 text-center font-body-md">
                                    @if($order->status === 'paid' || $order->status === 'downloaded')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Sukses</span>
                                    @elseif($order->status === 'pending')
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Pending</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">Batal</span>
                                    @endif
                                </td>
                                <td class="py-4 text-right pr-4 font-bold text-on-surface">
                                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-on-surface-variant">
                                    Belum ada transaksi di platform ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Feed (Messages) -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Pesan Terbaru</h3>
                    <a href="{{ route('admin.messages.index') }}" class="font-label-sm text-primary hover:underline">Semua Pesan</a>
                </div>
                <div class="space-y-4 mt-4">
                    @forelse($recentMessages as $msg)
                    <div class="flex gap-3 items-start relative pb-3 border-b border-outline-variant/30 last:border-0 last:pb-0">
                        <div class="w-8 h-8 rounded-full border border-sky-500 flex items-center justify-center text-sky-500 flex-shrink-0 mt-0.5 bg-surface">
                            <span class="material-symbols-outlined" style="font-size: 16px;">mail</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-body-sm text-on-surface truncate">Dari: <span class="font-bold">{{ $msg->name }}</span></p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ Str::limit($msg->message, 45) }}</p>
                        </div>
                    </div>
                    @empty
                        <p class="text-xs text-on-surface-variant py-4 text-center">Belum ada pesan masuk.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart (Bar)
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        const revLabels = @json($monthLabels);
        const revData = @json($monthlyRevenue);

        new Chart(ctxRev, {
            type: 'bar',
            data: {
                labels: revLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revData,
                    backgroundColor: '#0284c7', // Sky-600
                    borderRadius: 4,
                    borderSkipped: false,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        titleFont: { family: 'Geist', size: 13 },
                        bodyFont: { family: 'Geist', size: 14, weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: {
                            font: { family: 'Geist', size: 12 },
                            color: '#64748B' // slate-500
                        }
                    },
                    y: {
                        grid: {
                            color: function(context) {
                                return document.documentElement.classList.contains('dark') ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                            },
                            drawBorder: false,
                        },
                        ticks: {
                            font: { family: 'Geist', size: 11 },
                            color: '#64748B',
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + 'M';
                                if (value >= 1000) return (value / 1000) + 'K';
                                return value;
                            }
                        }
                    }
                }
            }
        });

        // Status Chart (Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Sukses', 'Pending', 'Gagal/Batal'],
                datasets: [{
                    data: [{{ $orderSuccess }}, {{ $orderPending }}, {{ $orderFailed }}],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444'], // Emerald, Amber, Red
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        padding: 12,
                        titleFont: { family: 'Geist', size: 13 },
                        bodyFont: { family: 'Geist', size: 14, weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' Pesanan';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
