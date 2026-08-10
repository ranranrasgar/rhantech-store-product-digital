@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex-1 overflow-y-auto p-lg bg-background">
    <div class="max-w-container-max mx-auto space-y-lg">
        <!-- Page Header -->
        <div class="mb-lg">
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Overview</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">High-level metrics and agency health status.</p>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Total Projects -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg  flex flex-col justify-between">
                <div class="w-10 h-10 rounded-lg bg-[#E0F2FE] text-[#0284C7] flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined" style="font-size: 20px;">rocket_launch</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-2">Total Projects</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ $totalProjects }}</h3>
            </div>
            
            <!-- Active Services -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg  flex flex-col justify-between">
                <div class="w-10 h-10 rounded-lg bg-surface-container-high text-on-surface-variant flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined" style="font-size: 20px;">layers</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-2">Active Services</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ $activeServices }}</h3>
            </div>

            <!-- New Messages -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg  flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 right-0 w-20 h-20 bg-[#E0F2FE] rounded-bl-full opacity-50"></div>
                <div class="w-10 h-10 rounded-lg bg-surface-container-high text-on-surface-variant flex items-center justify-center mb-4 relative z-10">
                    <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-2 relative z-10">New Messages</p>
                <div class="flex items-baseline gap-2 relative z-10">
                    <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ $newMessages }}</h3>
                    @if($newMessages > 0)
                    <span class="px-2 py-0.5 bg-[#CCFBF1] text-[#0F766E] rounded-full text-xs font-bold">+{{ $newMessages }} today</span>
                    @endif
                </div>
            </div>

            <!-- Total Clients -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg  flex flex-col justify-between">
                <div class="w-10 h-10 rounded-lg bg-surface-container-high text-on-surface-variant flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined" style="font-size: 20px;">groups</span>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-2">Total Clients</p>
                <h3 class="font-display-md text-display-md font-bold text-on-surface">{{ number_format($totalClients) }}</h3>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Revenue Growth -->
            <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg ">
                <div class="flex justify-between items-center mb-lg">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Revenue Growth</h3>
                    <div class="flex gap-2 border border-outline-variant rounded p-0.5 bg-surface-container-lowest">
                        <button class="px-3 py-1 bg-[#22D3EE] text-[#164E63] font-label-sm font-bold rounded">6M</button>
                        <button class="px-3 py-1 text-on-surface-variant font-label-sm font-bold hover:bg-surface-container rounded transition">1Y</button>
                    </div>
                </div>
                <!-- Box border for chart area matching mockup -->
                <div class="border border-outline-variant rounded-lg p-4 relative h-72 w-full flex items-end gap-2 bg-surface">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Project Status -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg ">
                <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-lg">Project Status</h3>
                <div class="relative h-48 w-full flex justify-center items-center">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col justify-center items-center pointer-events-none mt-2">
                        <span class="font-display-md text-display-md font-bold text-on-surface leading-none">{{ $totalProjects > 0 ? $totalProjects : '42' }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider mt-1">Total</span>
                    </div>
                </div>
                <div class="mt-8 space-y-3 px-2">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-primary"></span>
                            <span class="font-body-md text-on-surface">In Progress</span>
                        </div>
                        <span class="font-body-md text-on-surface font-medium">55%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#0F172A]"></span>
                            <span class="font-body-md text-on-surface">Completed</span>
                        </div>
                        <span class="font-body-md text-on-surface font-medium">30%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-[#CBD5E1]"></span>
                            <span class="font-body-md text-on-surface">On Hold</span>
                        </div>
                        <span class="font-body-md text-on-surface font-medium">15%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Top Services -->
            <div class="lg:col-span-2 bg-surface rounded-md border border-outline-variant p-lg ">
                <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface mb-md">Top Performing Services</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Service Name</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-center">Projects</th>
                                <th class="pb-3 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider text-right pr-4">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/50">
                            @forelse($topServices as $service)
                            <tr class="hover:bg-surface-container-lowest transition-colors group">
                                <td class="py-4">
                                    <span class="font-body-md font-semibold text-on-surface">{{ $service->name }}</span>
                                </td>
                                <td class="py-4 text-center font-body-md text-on-surface">
                                    {{ rand(5, 25) }}
                                </td>
                                <td class="py-4 text-right pr-4 font-body-md text-on-surface font-semibold">
                                    ${{ number_format(rand(1000, 10000)) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-8 text-center text-on-surface-variant">
                                    No services found. Add some services to see them here!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="bg-surface rounded-md border border-outline-variant p-lg ">
                <div class="flex justify-between items-center mb-md">
                    <h3 class="font-headline-sm text-headline-sm font-bold text-on-surface">Activity Feed</h3>
                    <a href="#" class="font-label-sm text-primary hover:underline">View All</a>
                </div>
                <div class="space-y-5 mt-4">
                    <div class="flex gap-4 items-start relative pb-5 border-b border-outline-variant/30">
                        <div class="w-8 h-8 rounded-full border border-primary flex items-center justify-center text-primary flex-shrink-0 mt-0.5 bg-surface">
                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                        </div>
                        <div>
                            <p class="font-body-sm text-on-surface"><span class="font-bold">Elena</span> updated <span class="font-bold">Nexus Project</span></p>
                            <p class="font-label-sm text-on-surface-variant mt-1">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start relative pb-5 border-b border-outline-variant/30">
                        <div class="w-8 h-8 rounded-full border border-[#0F172A] flex items-center justify-center text-[#0F172A] flex-shrink-0 mt-0.5 bg-surface">
                            <span class="material-symbols-outlined" style="font-size: 16px;">check_circle</span>
                        </div>
                        <div>
                            <p class="font-body-sm text-on-surface"><span class="font-bold">Mark</span> completed <span class="font-bold">Website Redesign</span></p>
                            <p class="font-label-sm text-on-surface-variant mt-1">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start relative">
                        <div class="w-8 h-8 rounded-full border border-primary flex items-center justify-center text-primary flex-shrink-0 mt-0.5 bg-surface">
                            <span class="material-symbols-outlined" style="font-size: 16px;">mail</span>
                        </div>
                        <div>
                            <p class="font-body-sm text-on-surface">New message from <span class="font-bold">TechCorp</span></p>
                            <p class="font-label-sm text-on-surface-variant mt-1">Yesterday</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Chart (Bar)
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [25000, 32000, 20000, 42000, 52000, 75000],
                    backgroundColor: '#22D3EE',
                    borderRadius: 0,
                    borderSkipped: false,
                    barPercentage: 0.95,
                    categoryPercentage: 0.95
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
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: false,
                        grid: { display: false }
                    },
                    y: {
                        display: false,
                        grid: { display: false },
                        beginAtZero: true
                    }
                },
                layout: {
                    padding: { top: 0, bottom: 0, left: 0, right: 0 }
                }
            }
        });

        // Project Status Chart (Doughnut)
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['In Progress', 'Completed', 'On Hold'],
                datasets: [{
                    data: [55, 30, 15],
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
                        titleFont: { family: 'Geist', size: 13 },
                        bodyFont: { family: 'Geist', size: 14, weight: 'bold' },
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
