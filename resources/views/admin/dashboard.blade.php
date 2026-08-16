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
                <h2 class="font-headline-lg text-headline-lg text-on-surface">Overview</h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">Ringkasan performa sistem, pesanan toko, proyek, dan aktivitas platform.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-primary text-on-primary rounded-md font-label-md font-bold hover:opacity-90 transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    Lihat Transaksi
                </a>
                <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-surface-variant text-on-surface hover:bg-surface-container-high rounded-md font-label-md font-bold transition flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Tambah Produk
                </a>
            </div>
        </div>

        <!-- Urgent Payout Alert Banner -->
        @if(isset($pendingPayoutsCount) && $pendingPayoutsCount > 0)
            <div class="bg-gradient-to-r from-amber-500/15 via-amber-500/10 to-transparent border-l-4 border-amber-500 rounded-r-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0 animate-bounce">
                        <span class="material-symbols-outlined text-[22px]">priority_high</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-base text-on-surface flex items-center gap-2">
                            Perhatian: {{ $pendingPayoutsCount }} Permintaan Pencairan Dana Perlu Segera Diproses!
                        </h4>
                        <p class="text-xs text-on-surface-variant mt-0.5">
                            Ada tenant yang sedang menunggu transfer dana saldo toko. Harap segera periksa dan konfirmasi.
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.payouts.index') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow transition flex items-center gap-1.5 whitespace-nowrap">
                    <span class="material-symbols-outlined text-[16px]">payments</span>
                    Proses Pencairan Sekarang →
                </a>
            </div>
        @endif

        <!-- 4 Primary Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Total Revenue -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">payments</span>
                    </div>
                    <span class="text-xs font-bold text-emerald-600">Paid Orders</span>
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
                    <span class="text-xs font-bold text-sky-600">{{ $totalProducts }} Produk</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Total Transaksi Selesai</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ number_format($totalOrders) }}</h3>
            </div>
            
            <!-- Total Projects & Clients -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-lg bg-surface-container-high text-on-surface-variant flex items-center justify-center">
                        <span class="material-symbols-outlined" style="font-size: 20px;">rocket_launch</span>
                    </div>
                    <span class="text-xs font-bold text-on-surface-variant">{{ $totalClients }} Klien</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-1">Total Projects</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ number_format($totalProjects) }}</h3>
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
                    <div class="flex gap-2 border border-outline-variant rounded p-0.5 bg-surface-container-lowest">
                        <button class="px-3 py-1 bg-primary text-on-primary font-label-sm font-bold rounded">6 Bulan</button>
                    </div>
                </div>
                <!-- Box border for chart area -->
                <div class="border border-outline-variant rounded-lg p-4 relative h-72 w-full flex items-end gap-2 bg-surface">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Project Status -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg">
                <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-1">Status Portofolio Project</h3>
                <p class="text-xs text-on-surface-variant mb-6">Distribusi progress pengerjaan proyek</p>
                <div class="relative h-44 w-full flex justify-center items-center">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col justify-center items-center pointer-events-none mt-2">
                        <span class="font-display-md text-display-md font-bold text-on-surface leading-none">{{ $totalProjects }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mt-1">Total</span>
                    </div>
                </div>
                <div class="mt-6 space-y-2.5 px-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#06B6D4]"></span>
                            <span class="font-body-md text-on-surface">In Progress</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentInProgress }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#0F172A] dark:bg-slate-400"></span>
                            <span class="font-body-md text-on-surface">Completed</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentCompleted }}%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#CBD5E1]"></span>
                            <span class="font-body-md text-on-surface">On Hold / Draft</span>
                        </div>
                        <span class="font-body-md text-on-surface font-semibold">{{ $percentOnHold }}%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Top Services List -->
            <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Layanan Agency Aktif</h3>
                    <a href="{{ route('admin.services.index') }}" class="font-label-sm text-primary hover:underline">Kelola Layanan</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Nama Layanan</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50">
                            @forelse($topServices as $service)
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded bg-surface-container-high flex items-center justify-center text-primary shrink-0">
                                            <span class="material-symbols-outlined text-[18px]">{{ $service->icon ?? 'layers' }}</span>
                                        </div>
                                        <span class="font-body-md font-semibold text-on-surface">{{ $service->title ?? $service->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 text-center font-body-md">
                                    @if($service->is_active)
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-400">Aktif</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-4 text-right pr-4 font-body-md">
                                    <a href="{{ route('admin.services.edit', $service) }}" class="text-primary hover:underline text-xs font-bold">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-on-surface-variant">
                                    Belum ada layanan yang ditambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Aktivitas Terbaru</h3>
                    <a href="{{ route('admin.messages.index') }}" class="font-label-sm text-primary hover:underline">Semua Pesan</a>
                </div>
                <div class="space-y-4 mt-4">
                    @forelse($recentOrders as $rOrder)
                    <div class="flex gap-3 items-start relative pb-3 border-b border-outline-variant/30">
                        <div class="w-8 h-8 rounded-full border border-primary flex items-center justify-center text-primary flex-shrink-0 mt-0.5 bg-surface">
                            <span class="material-symbols-outlined" style="font-size: 16px;">shopping_bag</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-body-sm text-on-surface truncate">
                                Pesanan <span class="font-bold font-mono">{{ $rOrder->invoice_number }}</span>
                            </p>
                            <p class="text-xs text-on-surface-variant mt-0.5">
                                Rp {{ number_format($rOrder->amount, 0, ',', '.') }} • {{ $rOrder->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @empty
                    @endforelse

                    @forelse($recentMessages as $msg)
                    <div class="flex gap-3 items-start relative pb-3 border-b border-outline-variant/30 last:border-0 last:pb-0">
                        <div class="w-8 h-8 rounded-full border border-sky-500 flex items-center justify-center text-sky-500 flex-shrink-0 mt-0.5 bg-surface">
                            <span class="material-symbols-outlined" style="font-size: 16px;">mail</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-body-sm text-on-surface truncate">Pesan dari <span class="font-bold">{{ $msg->name }}</span></p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ Str::limit($msg->message, 45) }}</p>
                        </div>
                    </div>
                    @empty
                    @endforelse

                    @if($recentOrders->isEmpty() && $recentMessages->isEmpty())
                        <p class="text-xs text-on-surface-variant py-4 text-center">Belum ada aktivitas transaksi atau pesan.</p>
                    @endif
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
                    backgroundColor: '#0284c7',
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
                        grid: { display: false }
                    },
                    y: {
                        grid: { color: 'rgba(150, 150, 150, 0.1)' },
                        beginAtZero: true
                    }
                }
            }
        });

        // Project Status Chart (Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        const pProg = {{ $percentInProgress }};
        const pComp = {{ $percentCompleted }};
        const pHold = {{ $percentOnHold }};

        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['In Progress', 'Completed', 'On Hold'],
                datasets: [{
                    data: [pProg, pComp, pHold],
                    backgroundColor: ['#06B6D4', '#0F172A', '#CBD5E1'],
                    borderWidth: 0,
                    hoverOffset: 0
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
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
