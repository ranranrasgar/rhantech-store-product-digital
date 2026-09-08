@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<!-- Include Chart.js & Leaflet Map -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<style>
.leaflet-popup-content-wrapper {
    background: #ffffff;
    color: #0f172a;
    border-radius: 12px;
    box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.15);
    padding: 0;
    overflow: hidden;
}
.dark .leaflet-popup-content-wrapper {
    background: #111726;
    color: #f1f5f9;
    border: 1px solid #222f49;
}
.leaflet-popup-content {
    margin: 0;
    line-height: 1.4;
}
.leaflet-popup-tip {
    background: #ffffff;
}
.dark .leaflet-popup-tip {
    background: #111726;
}
.custom-map-pin {
    background: transparent;
    border: none;
}
</style>

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

        <!-- Geographic Distribution Map Section -->
        <div class="bg-surface rounded-md border border-outline-variant p-lg space-y-md">
            <!-- Header with Title & Filter Controls -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-teal-500/10 text-teal-600 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                        </div>
                        <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Peta Persebaran Mitra Toko & Pelanggan</h3>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Visualisasi titik lokasi toko seller digital dan pelanggan/customer berdasarkan koordinat geografis di seluruh Indonesia.
                    </p>
                </div>

                <!-- Filter Controls & Legend -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="btnFilterAll" onclick="filterMapMarkers('all')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-primary text-on-primary border-primary">
                        Semua Titik ({{ $mapData['total_points'] }})
                    </button>
                    <button type="button" id="btnFilterStore" onclick="filterMapMarkers('store')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-surface border-outline-variant text-on-surface hover:border-teal-500 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#00838f]"></span>
                        Mitra Toko ({{ $mapData['total_stores'] }})
                    </button>
                    <button type="button" id="btnFilterCustomer" onclick="filterMapMarkers('customer')" 
                        class="px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-surface border-outline-variant text-on-surface hover:border-blue-500 flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#2563eb]"></span>
                        Pelanggan ({{ $mapData['total_customers'] }})
                    </button>
                    <button type="button" onclick="resetMapView()" title="Fokuskan Ulang Peta" 
                        class="p-1.5 rounded-lg border border-outline-variant text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">center_focus_strong</span>
                    </button>
                </div>
            </div>

            <!-- Leaflet Map Container -->
            <div class="relative w-full rounded-xl overflow-hidden border border-outline-variant bg-surface-container-low" style="height: 480px; z-index: 1;">
                <div id="adminGeoMap" class="w-full h-full"></div>
            </div>

            <!-- Summary Footnotes -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 border-t border-outline-variant/60 text-xs">
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Total Titik Terdata</span>
                    <span class="font-bold text-sm text-on-surface mt-0.5 block">{{ $mapData['total_points'] }} Lokasi</span>
                </div>
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Mitra Toko (Sellers)</span>
                    <span class="font-bold text-sm text-teal-600 dark:text-teal-400 mt-0.5 block">{{ $mapData['total_stores'] }} Toko Tersebar</span>
                </div>
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Pelanggan Aktif</span>
                    <span class="font-bold text-sm text-blue-600 dark:text-blue-400 mt-0.5 block">{{ $mapData['total_customers'] }} Pembeli Unik</span>
                </div>
                <div class="p-3 rounded-lg bg-surface-container-lowest border border-outline-variant/50">
                    <span class="text-on-surface-variant block text-[11px]">Cakupan Wilayah</span>
                    <span class="font-bold text-sm text-on-surface mt-0.5 block truncate" title="Jawa, Sumatera, Riau, dll">Jawa, Sumatera & Nasional</span>
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

        // -------------------------------------------------------------
        // Leaflet Interactive Geo Map for Stores & Customers
        // -------------------------------------------------------------
        const mapRawData = @json($mapData);
        let geoMap = null;
        let mapMarkersLayer = null;

        function initAdminGeoMap() {
            const mapContainer = document.getElementById('adminGeoMap');
            if (!mapContainer || typeof L === 'undefined') return;

            geoMap = L.map('adminGeoMap', {
                scrollWheelZoom: false
            }).setView([-6.92, 107.65], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors'
            }).addTo(geoMap);

            mapMarkersLayer = L.layerGroup().addTo(geoMap);

            renderMarkers('all');
        }

        function createPinIcon(type) {
            if (type === 'store') {
                return L.divIcon({
                    className: 'custom-map-pin',
                    html: `
                        <div style="position: relative; display: flex; flex-direction: column; align-items: center; cursor: pointer;">
                            <div style="width: 34px; height: 34px; border-radius: 50%; background: #00838f; color: white; display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); font-weight: bold;">
                                <span class="material-symbols-outlined" style="font-size: 18px;">storefront</span>
                            </div>
                            <div style="width: 0; height: 0; border-left: 6px solid transparent; border-right: 6px solid transparent; border-top: 8px solid #00838f; margin-top: -2px;"></div>
                        </div>
                    `,
                    iconSize: [34, 40],
                    iconAnchor: [17, 40],
                    popupAnchor: [0, -38]
                });
            } else {
                return L.divIcon({
                    className: 'custom-map-pin',
                    html: `
                        <div style="position: relative; display: flex; flex-direction: column; align-items: center; cursor: pointer;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #2563eb; color: white; display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); font-weight: bold;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">person</span>
                            </div>
                            <div style="width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 7px solid #2563eb; margin-top: -2px;"></div>
                        </div>
                    `,
                    iconSize: [32, 37],
                    iconAnchor: [16, 37],
                    popupAnchor: [0, -35]
                });
            }
        }

        function renderMarkers(filterType) {
            if (!geoMap || !mapMarkersLayer) return;
            mapMarkersLayer.clearLayers();

            let list = [];
            if (filterType === 'store') {
                list = mapRawData.stores || [];
            } else if (filterType === 'customer') {
                list = mapRawData.customers || [];
            } else {
                list = mapRawData.all || [];
            }

            const bounds = [];

            list.forEach(item => {
                if (!item.lat || !item.lng) return;

                const marker = L.marker([item.lat, item.lng], {
                    icon: createPinIcon(item.type)
                });

                let popupContent = '';
                if (item.type === 'store') {
                    popupContent = `
                        <div style="padding: 12px; min-width: 220px; max-width: 260px; font-family: inherit;">
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                <span style="background: #ccfbf1; color: #0f766e; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">🏪 MITRA TOKO</span>
                                <span style="font-size: 11px; color: #64748b;">• ${item.products_count} Produk</span>
                            </div>
                            <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #0f172a;">${item.name}</h4>
                            <p style="margin: 4px 0 0; font-size: 11px; color: #64748b; line-height: 1.3;">📍 ${item.address}</p>
                            <div style="margin-top: 6px; font-size: 11px; color: #475569;">
                                Pemilik: <b>${item.owner}</b>
                            </div>
                            <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e2e8f0; display: flex; gap: 6px;">
                                ${item.store_url ? `<a href="${item.store_url}" target="_blank" style="padding: 4px 8px; border-radius: 6px; background: #00838f; color: white; font-size: 11px; font-weight: 700; text-decoration: none;">Buka Toko</a>` : ''}
                                <a href="${item.maps_url}" target="_blank" style="padding: 4px 8px; border-radius: 6px; background: #f1f5f9; color: #334155; font-size: 11px; font-weight: 600; text-decoration: none;">Google Maps</a>
                            </div>
                        </div>
                    `;
                } else {
                    popupContent = `
                        <div style="padding: 12px; min-width: 220px; max-width: 260px; font-family: inherit;">
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 6px;">
                                <span style="background: #dbeafe; color: #1d4ed8; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 9999px;">👤 PELANGGAN</span>
                                <span style="font-size: 11px; color: #64748b;">• ${item.orders_count} Order</span>
                            </div>
                            <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #0f172a;">${item.name}</h4>
                            <p style="margin: 4px 0 0; font-size: 11px; color: #64748b; line-height: 1.3;">📍 ${item.address}</p>
                            <div style="margin-top: 6px; font-size: 11px; color: #166534;">
                                Total Belanja: <b>Rp ${Number(item.total_spent).toLocaleString('id-ID')}</b>
                            </div>
                            <div style="margin-top: 2px; font-size: 10px; color: #94a3b8;">
                                Terakhir order: ${item.last_order}
                            </div>
                            <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-size: 10px; color: #64748b; font-family: monospace;">${item.phone || item.email}</span>
                                <a href="${item.maps_url}" target="_blank" style="padding: 4px 8px; border-radius: 6px; background: #f1f5f9; color: #334155; font-size: 11px; font-weight: 600; text-decoration: none;">Maps</a>
                            </div>
                        </div>
                    `;
                }

                marker.bindPopup(popupContent);
                mapMarkersLayer.addLayer(marker);
                bounds.push([item.lat, item.lng]);
            });

            if (bounds.length > 0) {
                geoMap.fitBounds(bounds, { padding: [40, 40], maxZoom: 12 });
            }
        }

        window.filterMapMarkers = function(type) {
            const btnAll = document.getElementById('btnFilterAll');
            const btnStore = document.getElementById('btnFilterStore');
            const btnCust = document.getElementById('btnFilterCustomer');

            const resetClass = "px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-surface border-outline-variant text-on-surface hover:border-outline";
            if (btnAll) btnAll.className = resetClass;
            if (btnStore) btnStore.className = resetClass;
            if (btnCust) btnCust.className = resetClass;

            if (type === 'store') {
                if (btnStore) btnStore.className = "px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-[#00838f] text-white border-[#00838f]";
            } else if (type === 'customer') {
                if (btnCust) btnCust.className = "px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-[#2563eb] text-white border-[#2563eb]";
            } else {
                if (btnAll) btnAll.className = "px-3 py-1.5 rounded-lg text-xs font-bold border transition-colors cursor-pointer bg-primary text-on-primary border-primary";
            }

            renderMarkers(type);
        };

        window.resetMapView = function() {
            window.filterMapMarkers('all');
        };

        initAdminGeoMap();
    });
</script>
@endsection
