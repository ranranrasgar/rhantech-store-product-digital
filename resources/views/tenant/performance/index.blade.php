@extends('layouts.tenant')

@section('title', 'Tinjauan')

@section('content')
<div class="flex-1 overflow-y-auto bg-surface-container-lowest dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md" x-data="{ tab: 'tinjauan' }">
    
    <!-- Top Advertisement Banner -->
    <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-b border-orange-200 dark:border-orange-900/50 flex flex-col md:flex-row items-center justify-between p-3 px-6 gap-4">
        <div class="flex items-center gap-4 flex-1">
            <div class="w-12 h-12 flex-shrink-0 bg-white/50 dark:bg-black/20 rounded p-1">
                <span class="material-symbols-outlined text-error text-[2rem] w-full h-full flex items-center justify-center">campaign</span>
            </div>
            <div>
                <h3 class="font-bold text-on-surface dark:text-white text-base">Buat Iklan, Tingkatkan Kunjungan dan Dapatkan Voucher!</h3>
            </div>
        </div>
        <div class="flex items-center gap-6 text-sm">
            <div class="flex items-center gap-2">
                <span class="text-on-surface-variant dark:text-gray-400">Atur Modal</span>
                <span class="font-bold text-error">Rp250.000 >></span>
            </div>
            <div class="hidden lg:block border-l border-orange-300 dark:border-orange-700 h-6"></div>
            <div class="hidden lg:block">
                <span class="text-on-surface-variant dark:text-gray-400">Tingkatkan Kunjungan + Dapatkan Voucher Toko Spesial</span>
            </div>
            <div class="hidden lg:block border-l border-orange-300 dark:border-orange-700 h-6"></div>
            <div class="hidden lg:flex items-center gap-2">
                <span class="text-on-surface-variant dark:text-gray-400">Penjualan Meningkat</span>
                <span class="font-bold text-error">>> Rp1.350.000-Rp2.425.000</span>
            </div>
            <button class="bg-error hover:bg-[#d73f22] text-white px-4 py-1.5 font-bold rounded shadow-sm text-sm transition-colors whitespace-nowrap">Buat Iklan</button>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d] px-6 flex items-center gap-6 text-sm font-semibold overflow-x-auto whitespace-nowrap">
        <button @click="tab = 'tinjauan'" :class="tab === 'tinjauan' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-on-surface hover:text-error'" class="py-4 px-2 transition-colors">Tinjauan</button>
        <button @click="tab = 'produk'" :class="tab === 'produk' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-on-surface hover:text-error'" class="py-4 px-2 transition-colors">Produk</button>
        <button @click="tab = 'penjualan'" :class="tab === 'penjualan' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-on-surface hover:text-error'" class="py-4 px-2 transition-colors">Penjualan</button>
        <button @click="tab = 'kunjungan'" :class="tab === 'kunjungan' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-on-surface hover:text-error'" class="py-4 px-2 transition-colors">Tingkat Kunjungan</button>
        
        <!-- Other generic tabs as requested -->
        <button class="py-4 px-2 border-b-2 border-transparent text-on-surface-variant hover:text-error transition-colors">Layanan</button>
        <button class="py-4 px-2 border-b-2 border-transparent text-on-surface-variant hover:text-error transition-colors">Promosi</button>
        <button class="py-4 px-2 border-b-2 border-transparent text-on-surface-variant hover:text-error transition-colors">Panduan Penjualan</button>

        <div class="flex-1"></div>
        <div class="flex items-center gap-4 text-xs font-normal text-[#0055aa] dark:text-[#38bdf8]">
            <a href="#" class="flex items-center gap-1 hover:underline"><span class="material-symbols-outlined text-[14px]">info</span> Pelajari Lebih Lanjut</a>
            <a href="#" class="flex items-center gap-1 hover:underline"><span class="material-symbols-outlined text-[14px]">open_in_new</span> Data Real-time</a>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="p-6 bg-surface-container dark:bg-[#0a0c10] min-h-[calc(100vh-140px)]">
        
        <!-- Controls Row -->
        <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-surface-container-lowest dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded flex text-sm overflow-hidden shadow-sm">
                    <span class="px-3 py-1.5 bg-surface-variant/30 text-on-surface-variant border-r border-outline-variant dark:border-[#30363d]">Periode Data</span>
                    <button class="px-3 py-1.5 font-semibold text-on-surface flex items-center gap-2 hover:bg-surface-variant/30">
                        Real-time <span class="font-normal text-on-surface-variant">Hari Ini - Pk {{ date('H:i') }} (GMT+07)</span> <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    </button>
                </div>
                
                <div x-show="tab === 'tinjauan'" class="bg-surface-container-lowest dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded flex text-sm overflow-hidden shadow-sm">
                    <span class="px-3 py-1.5 bg-surface-variant/30 text-on-surface-variant border-r border-outline-variant dark:border-[#30363d]">Status Pesanan</span>
                    <button class="px-3 py-1.5 font-semibold text-on-surface flex items-center gap-2 hover:bg-surface-variant/30">
                        Pesanan Siap Dikirim <span class="material-symbols-outlined text-[16px]">expand_more</span>
                    </button>
                </div>
            </div>

            <button class="px-4 py-1.5 border border-outline-variant dark:border-[#30363d] rounded text-sm font-semibold hover:bg-surface-variant/30 flex items-center gap-2 shadow-sm bg-surface-container-lowest dark:bg-[#161b22]">
                <span class="material-symbols-outlined text-[16px]">download</span> Download Data
            </button>
        </div>

        <!-- TAB 1: TINJAUAN -->
        <div x-show="tab === 'tinjauan'" class="space-y-6">
            <!-- Grid 1: Kriteria Utama & Metrik Real-time -->
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <!-- Kriteria Utama (Left, spans 3 cols) -->
                <div class="xl:col-span-3 bg-surface-container-lowest dark:bg-[#161b22] rounded-lg shadow-sm border border-outline-variant dark:border-[#30363d] p-6">
                    <h3 class="font-bold text-base mb-4">Kriteria Utama</h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 relative">
                        <!-- Navigation Arrows (Mock) -->
                        <button class="absolute -right-3 top-1/2 -translate-y-1/2 w-8 h-8 bg-surface dark:bg-[#30363d] rounded-full shadow-md border border-outline-variant dark:border-gray-600 flex items-center justify-center text-on-surface-variant hover:text-on-surface z-10 hidden md:flex"><span class="material-symbols-outlined">chevron_right</span></button>

                        <!-- Card 1 -->
                        <div class="border border-error dark:border-error/50 rounded p-4 relative bg-error/5 cursor-pointer">
                            <div class="absolute -top-3 left-4 bg-surface-container-lowest dark:bg-[#161b22] px-1 text-[10px] text-[#0055aa] dark:text-[#38bdf8]">Definition updated 2026</div>
                            <div class="text-sm font-bold text-on-surface mb-2 flex items-center gap-1">Penjualan <span class="material-symbols-outlined text-[14px] text-on-surface-variant">help</span></div>
                            <div class="text-xl font-bold text-error mb-2">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant dark:text-gray-400">
                                <span>vs Kemarin pada 00:00-{{ date('H:00') }}</span>
                                <span class="font-semibold text-on-surface">0,00%</span>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-4 cursor-pointer hover:border-error transition-colors group">
                            <div class="text-sm font-semibold text-on-surface-variant group-hover:text-on-surface mb-2 flex items-center gap-1">Pesanan <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-xl font-bold text-on-surface dark:text-white mb-2">{{ $totalOrders }}</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant dark:text-gray-400">
                                <span>vs Kemarin pada 00:00-{{ date('H:00') }}</span>
                                <span class="font-semibold text-on-surface">0,00%</span>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-4 cursor-pointer hover:border-error transition-colors group">
                            <div class="text-sm font-semibold text-on-surface-variant group-hover:text-on-surface mb-2 flex items-center gap-1">Tingkat Konversi Pesanan <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-xl font-bold text-on-surface dark:text-white mb-2">0,00%</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant dark:text-gray-400">
                                <span>vs Kemarin pada 00:00-{{ date('H:00') }}</span>
                                <span class="font-semibold text-on-surface">0,00%</span>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-4 cursor-pointer hover:border-error transition-colors group">
                            <div class="text-sm font-semibold text-on-surface-variant group-hover:text-on-surface mb-2 flex items-center gap-1">Total Pengunjung <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-xl font-bold text-on-surface dark:text-white mb-2">0</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant dark:text-gray-400">
                                <span>vs Kemarin pada 00:00-{{ date('H:00') }}</span>
                                <span class="font-semibold text-on-surface">0,00%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Area (Mock) -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-sm font-bold">Grafik setiap Kriteria</h4>
                            <div class="flex items-center gap-4 text-xs">
                                <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#1e88e5]"></span> Penjualan</div>
                                <span class="text-on-surface-variant">Kriteria Dipilih 1/4</span>
                            </div>
                        </div>
                        <div class="h-[180px] w-full relative pt-4">
                            <!-- Horizontal Lines -->
                            <div class="absolute left-0 right-0 top-4 border-b border-dashed border-outline-variant/30"></div>
                            <div class="absolute left-0 right-0 top-16 border-b border-dashed border-outline-variant/30"></div>
                            <div class="absolute left-0 right-0 top-28 border-b border-dashed border-outline-variant/30"></div>
                            <div class="absolute left-0 right-0 bottom-6 border-b border-outline-variant dark:border-gray-500"></div>

                            <!-- X Axis Labels -->
                            <div class="absolute left-0 right-0 bottom-0 flex justify-between text-[10px] text-on-surface-variant">
                                <span>00:00</span>
                                <span>06:00</span>
                                <span>12:00</span>
                                <span>18:00</span>
                                <span>24:00</span>
                            </div>

                            <!-- Line (Mock SVG) -->
                            <svg class="w-full h-full absolute inset-0 pt-4 pb-6 overflow-visible" preserveAspectRatio="none">
                                <polyline fill="none" stroke="#1e88e5" stroke-width="2" points="0,150 100,150 200,150 300,150 400,150 500,150 600,150" />
                                <circle cx="0" cy="150" r="3" fill="#1e88e5" />
                                <circle cx="100" cy="150" r="3" fill="#1e88e5" />
                                <circle cx="200" cy="150" r="3" fill="#1e88e5" />
                                <circle cx="300" cy="150" r="3" fill="#1e88e5" />
                                <circle cx="400" cy="150" r="3" fill="#1e88e5" />
                                <circle cx="500" cy="150" r="3" fill="#1e88e5" />
                                <circle cx="600" cy="150" r="3" fill="#1e88e5" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Metrik Real-time (Right, spans 1 col) -->
                <div class="bg-surface-container-lowest dark:bg-[#161b22] rounded-lg shadow-sm border border-outline-variant dark:border-[#30363d] p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="font-bold text-base">Metrik Real-time</h3>
                        <a href="#" class="text-[#0055aa] dark:text-[#38bdf8] text-xs hover:underline flex items-center">Lainnya <span class="material-symbols-outlined text-[14px]">chevron_right</span></a>
                    </div>
                    <div class="text-[10px] text-green-600 dark:text-green-400 flex items-center gap-1 mb-6"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Update secara real-time</div>

                    <div class="mb-4">
                        <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Penjualan Hari Ini <span class="material-symbols-outlined text-[14px]">help</span></div>
                        <div class="text-xl font-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                    </div>
                    
                    <!-- Progress Bar style -->
                    <div class="relative pt-6 pb-2 mb-6">
                        <div class="h-[2px] bg-outline-variant/30 w-full relative">
                            <div class="absolute -top-1 left-0 w-2 h-2 rounded-full bg-error"></div>
                            <div class="absolute -top-1 right-0 w-2 h-2 rounded-full bg-error"></div>
                            <div class="absolute top-2 left-0 text-[10px] text-on-surface-variant">00:00</div>
                            <div class="absolute top-2 left-1/2 -translate-x-1/2 text-[10px] text-on-surface-variant">12:00</div>
                            <div class="absolute top-2 right-0 text-[10px] text-on-surface-variant">24:00</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-y-6 mt-4">
                        <div>
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Total Pengunjung <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold">0</div>
                        </div>
                        <div>
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Produk Diklik <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold">0</div>
                        </div>
                        <div>
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Pesanan <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold">{{ $totalOrders }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Tingkat Konversi Pesanan <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold">0,00%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sumber Kunjungan Row -->
            <div class="bg-surface-container-lowest dark:bg-[#161b22] rounded-lg shadow-sm border border-outline-variant dark:border-[#30363d] p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-base">Sumber Kunjungan</h3>
                    <a href="#" class="text-[#0055aa] dark:text-[#38bdf8] text-xs hover:underline flex items-center">Lihat Kunjungan Produk <span class="material-symbols-outlined text-[14px]">chevron_right</span></a>
                </div>

                <div class="flex gap-6 items-stretch">
                    <!-- Left Breakdown -->
                    <div class="flex-1">
                        <h4 class="text-sm font-bold mb-4">Rincian Kontribusi Penjualan Toko</h4>
                        <div class="flex bg-surface dark:bg-[#0d1117] p-4 rounded border border-outline-variant dark:border-[#30363d] gap-2 items-center overflow-x-auto min-h-[110px]">
                            <!-- Item -->
                            <div class="min-w-[150px]">
                                <div class="text-xs text-on-surface-variant mb-1 flex items-center gap-1">Total Penjualan (100%) <span class="material-symbols-outlined text-[12px]">help</span></div>
                                <div class="text-base font-bold mb-1">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-on-surface-variant flex justify-between"><span>vs Kemarin</span> <span>0,00%</span></div>
                            </div>
                            <div class="text-on-surface-variant text-xl font-light">=</div>
                            <!-- Item Active -->
                            <div class="min-w-[150px] border border-[#1e88e5] rounded p-2 relative bg-[#1e88e5]/5">
                                <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-surface-container-lowest px-1"><span class="material-symbols-outlined text-[#1e88e5] text-[16px]">arrow_drop_down</span></div>
                                <div class="text-xs font-bold text-on-surface mb-1 flex items-center gap-1">Halaman Produk <span class="material-symbols-outlined text-[12px] text-on-surface-variant">help</span></div>
                                <div class="text-base font-bold mb-1">Rp 0</div>
                                <div class="text-[10px] text-on-surface-variant flex justify-between"><span>vs Kemarin</span> <span>0,00%</span></div>
                            </div>
                            <div class="text-on-surface-variant text-xl font-light">+</div>
                            <!-- Item -->
                            <div class="min-w-[150px] p-2 hover:bg-surface-container-lowest transition-colors cursor-pointer rounded">
                                <div class="text-xs text-on-surface-variant mb-1 flex items-center gap-1">Live Penjual <span class="material-symbols-outlined text-[12px]">help</span></div>
                                <div class="text-base font-bold mb-1">Rp 0</div>
                                <div class="text-[10px] text-on-surface-variant flex justify-between"><span>vs Kemarin</span> <span>0,00%</span></div>
                            </div>
                            <div class="text-on-surface-variant text-xl font-light">+</div>
                            <!-- Item -->
                            <div class="min-w-[150px] p-2 hover:bg-surface-container-lowest transition-colors cursor-pointer rounded">
                                <div class="text-xs text-on-surface-variant mb-1 flex items-center gap-1">Video Penjual <span class="material-symbols-outlined text-[12px]">help</span></div>
                                <div class="text-base font-bold mb-1">Rp 0</div>
                                <div class="text-[10px] text-on-surface-variant flex justify-between"><span>vs Kemarin</span> <span>0,00%</span></div>
                            </div>
                            <div class="text-on-surface-variant text-xl font-light">+</div>
                            <!-- Item -->
                            <div class="min-w-[150px] p-2 hover:bg-surface-container-lowest transition-colors cursor-pointer rounded">
                                <div class="text-xs text-on-surface-variant mb-1 flex items-center gap-1">Affiliate <span class="material-symbols-outlined text-[12px]">help</span></div>
                                <div class="text-base font-bold mb-1">Rp 0</div>
                                <div class="text-[10px] text-on-surface-variant flex justify-between"><span>vs Kemarin</span> <span>0,00%</span></div>
                            </div>
                        </div>
                    </div>
                    <!-- Right Breakdown -->
                    <div class="w-[250px]">
                        <h4 class="text-sm font-bold mb-4">Kontribusi Penjualan Iklan</h4>
                        <div class="flex flex-col h-[110px] bg-surface dark:bg-[#0d1117] rounded border border-outline-variant dark:border-[#30363d] overflow-hidden">
                            <div class="p-3 flex-1">
                                <div class="text-xs text-on-surface-variant mb-1 flex items-center gap-1">Iklan <span class="material-symbols-outlined text-[12px]">help</span></div>
                                <div class="text-base font-bold mb-1">Rp 0</div>
                                <div class="text-[10px] text-on-surface-variant flex justify-between"><span>vs Kemarin</span> <span>0,00%</span></div>
                            </div>
                            <div class="flex text-xs font-semibold border-t border-outline-variant dark:border-[#30363d]">
                                <button class="flex-1 py-1.5 border-b-2 border-error text-error">Asal Penjualan</button>
                                <button class="flex-1 py-1.5 border-b-2 border-transparent text-on-surface-variant hover:text-on-surface">Kontribusi Produk</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: PRODUK -->
        <div x-show="tab === 'produk'" class="bg-surface-container-lowest dark:bg-[#161b22] rounded-lg shadow-sm border border-outline-variant dark:border-[#30363d] p-6 space-y-6" style="display: none;">
            
            <div class="flex items-center gap-6 border-b border-outline-variant dark:border-[#30363d] pb-0 text-sm font-semibold">
                <button class="border-b-2 border-error text-error py-2 px-1">Tinjauan Produk</button>
                <button class="border-b-2 border-transparent text-on-surface-variant hover:text-on-surface py-2 px-1">Kunjungan Produk</button>
                <button class="border-b-2 border-transparent text-on-surface-variant hover:text-on-surface py-2 px-1">Performa Produk</button>
                <button class="border-b-2 border-transparent text-on-surface-variant hover:text-on-surface py-2 px-1">Analisis Produk</button>
            </div>

            <h3 class="font-bold text-base mt-6 mb-4">Tinjauan Produk</h3>
            
            <div class="border border-outline-variant dark:border-[#30363d] rounded divide-y divide-outline-variant dark:divide-[#30363d]">
                
                <!-- Row 1: Kunjungan -->
                <div class="flex bg-surface-container-lowest dark:bg-[#0d1117]">
                    <div class="w-32 flex-shrink-0 p-4 border-r border-outline-variant dark:border-[#30363d] font-semibold text-sm flex items-center">
                        Kunjungan
                    </div>
                    <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 divide-x divide-outline-variant dark:divide-[#30363d]">
                        <!-- Metric -->
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Pengunjung Produk <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="text-[10px] text-on-surface-variant">vs Kemarin pada 00:00-{{ date('H:00') }}</div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Halaman Produk Dilihat <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="text-[10px] text-on-surface-variant">vs Kemarin pada 00:00-{{ date('H:00') }}</div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Produk Dikunjungi <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="text-[10px] text-on-surface-variant">vs Kemarin pada 00:00-{{ date('H:00') }}</div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Pengunjung Melihat Tanpa Membeli <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="text-[10px] text-on-surface-variant">vs Kemarin pada 00:00-{{ date('H:00') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Tambah Keranjang (Mock) -->
                <div class="flex bg-surface-container-lowest dark:bg-[#0d1117]">
                    <div class="w-32 flex-shrink-0 p-4 border-r border-outline-variant dark:border-[#30363d] font-semibold text-sm flex items-center">
                        Tambah ke<br>Keranjang
                    </div>
                    <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 divide-x divide-outline-variant dark:divide-[#30363d]">
                        <!-- Metric -->
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Pengunjung Produk <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant"><span>vs Kemarin</span> <span>0,00%</span></div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Produk <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant"><span>vs Kemarin</span> <span>0,00%</span></div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Tingkat Konversi <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0,00%</div>
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant"><span>vs Kemarin</span> <span>0,00%</span></div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]"></div>
                    </div>
                </div>

                <!-- Row 3: Pesanan Dibuat -->
                <div class="flex bg-surface-container-lowest dark:bg-[#0d1117]">
                    <div class="w-32 flex-shrink-0 p-4 border-r border-outline-variant dark:border-[#30363d] font-semibold text-sm flex items-center">
                        Pesanan<br>Dibuat
                    </div>
                    <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 divide-x divide-outline-variant dark:divide-[#30363d]">
                        <!-- Metric -->
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Total Pembeli <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant"><span>vs Kemarin</span> <span>0,00%</span></div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Produk <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">0</div>
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant"><span>vs Kemarin</span> <span>0,00%</span></div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Produk Dipesan <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">{{ $totalOrders }}</div>
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant"><span>vs Kemarin</span> <span>0,00%</span></div>
                        </div>
                        <div class="p-4 bg-surface dark:bg-[#161b22]">
                            <div class="text-xs text-on-surface-variant flex items-center gap-1 mb-1">Penjualan <span class="material-symbols-outlined text-[14px]">help</span></div>
                            <div class="text-base font-bold mb-1">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                            <div class="flex justify-between items-center text-[10px] text-on-surface-variant"><span>vs Kemarin</span> <span>0,00%</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: PENJUALAN (Funnel) -->
        <div x-show="tab === 'penjualan'" class="bg-surface-container-lowest dark:bg-[#161b22] rounded-lg shadow-sm border border-outline-variant dark:border-[#30363d] p-6 space-y-6" style="display: none;">
            
            <div class="flex items-center gap-6 border-b border-outline-variant dark:border-[#30363d] pb-0 text-sm font-semibold">
                <button class="border-b-2 border-error text-error py-2 px-1">Tinjauan Penjualan</button>
                <button class="border-b-2 border-transparent text-on-surface-variant hover:text-on-surface py-2 px-1">Komposisi Penjualan</button>
            </div>

            <h3 class="font-bold text-base mt-6 mb-4">Tinjauan Penjualan</h3>

            <div class="flex flex-col lg:flex-row border border-outline-variant dark:border-[#30363d] rounded divide-y lg:divide-y-0 lg:divide-x divide-outline-variant dark:divide-[#30363d] overflow-hidden bg-surface dark:bg-[#161b22]">
                <!-- Metric List Left -->
                <div class="flex-1 divide-y divide-outline-variant dark:divide-[#30363d]">
                    <!-- Row 1 Kunjungan -->
                    <div class="p-6 h-[100px] flex items-center">
                        <div class="flex-1">
                            <div class="text-sm font-bold flex items-center gap-1 mb-1">Total Pengunjung <span class="material-symbols-outlined text-[14px] text-on-surface-variant">help</span></div>
                            <div class="text-xl font-bold">0</div>
                            <div class="text-xs text-on-surface-variant">vs Kemarin pada 00:00 {{ date('H:00') }}</div>
                        </div>
                    </div>
                    <!-- Row 2 Pesanan Dibuat -->
                    <div class="p-6 h-[100px] flex items-center bg-surface-container-lowest dark:bg-[#0d1117]">
                        <div class="flex-1">
                            <div class="text-sm font-bold flex items-center gap-1 mb-1">Total Pembeli <span class="material-symbols-outlined text-[14px] text-on-surface-variant">help</span></div>
                            <div class="text-xl font-bold">0</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant"><span>vs Kemarin pada 00:00 {{ date('H:00') }}</span> <span>0,00%</span></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold flex items-center gap-1 mb-1">Penjualan <span class="material-symbols-outlined text-[14px] text-on-surface-variant">help</span></div>
                            <div class="text-xl font-bold">Rp 0</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant"><span>vs Kemarin pada 00:00 {{ date('H:00') }}</span> <span>0,00%</span></div>
                        </div>
                    </div>
                    <!-- Row 3 Siap Dikirim -->
                    <div class="p-6 h-[100px] flex items-center">
                        <div class="flex-1">
                            <div class="text-sm font-bold flex items-center gap-1 mb-1">Total Pembeli <span class="material-symbols-outlined text-[14px] text-on-surface-variant">help</span></div>
                            <div class="text-xl font-bold">{{ $totalOrders }}</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant"><span>vs Kemarin pada 00:00 {{ date('H:00') }}</span> <span>0,00%</span></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold flex items-center gap-1 mb-1">Penjualan <span class="material-symbols-outlined text-[14px] text-on-surface-variant">help</span></div>
                            <div class="text-xl font-bold">Rp {{ number_format($totalSales, 0, ',', '.') }}</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant"><span>vs Kemarin pada 00:00 {{ date('H:00') }}</span> <span>0,00%</span></div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-bold flex items-center gap-1 mb-1">Penjualan per Pembeli <span class="material-symbols-outlined text-[14px] text-on-surface-variant">help</span></div>
                            <div class="text-xl font-bold">Rp {{ $totalOrders > 0 ? number_format($totalSales / $totalOrders, 0, ',', '.') : 0 }}</div>
                            <div class="flex justify-between items-center text-xs text-on-surface-variant"><span>vs Kemarin pada 00:00 {{ date('H:00') }}</span> <span>0,00%</span></div>
                        </div>
                    </div>
                </div>

                <!-- Funnel Visual Right -->
                <div class="w-full lg:w-[350px] bg-surface dark:bg-[#161b22] p-8 flex items-center justify-center relative">
                    <div class="w-full max-w-[200px] flex flex-col gap-[2px]">
                        <!-- Block 1 -->
                        <div class="bg-[#4383eb] h-[80px] flex flex-col items-center justify-center text-white text-xs font-bold" style="clip-path: polygon(0 0, 100% 0, 90% 100%, 10% 100%);">
                            <span class="material-symbols-outlined mb-1">person</span> Kunjungan
                        </div>
                        <!-- Block 2 -->
                        <div class="bg-[#6b9fed] h-[80px] flex flex-col items-center justify-center text-white text-xs font-bold" style="clip-path: polygon(10% 0, 90% 0, 80% 100%, 20% 100%);">
                            <span class="material-symbols-outlined mb-1">receipt_long</span> Pesanan Dibuat
                        </div>
                        <!-- Block 3 -->
                        <div class="bg-[#f06e4b] h-[80px] flex flex-col items-center justify-center text-white text-xs font-bold" style="clip-path: polygon(20% 0, 80% 0, 70% 100%, 30% 100%);">
                            <span class="material-symbols-outlined mb-1">account_balance_wallet</span> Pesanan Siap Dikirim
                        </div>
                    </div>
                    
                    <!-- Side Arrows for Funnel -->
                    <div class="absolute right-4 top-0 bottom-0 py-8 flex flex-col text-[10px] text-on-surface-variant justify-around w-[100px]">
                        <div class="border-l-2 border-b-2 border-outline-variant dark:border-[#30363d] h-[80px] w-4 absolute right-[100px] top-[40px]"></div>
                        <div class="text-right">
                            <span class="text-error font-bold block mt-4">0,00%</span>
                            Tingkat Konversi<br>(Pesanan Dibuat dibagi<br>Kunjungan) <span class="material-symbols-outlined text-[10px]">help</span>
                        </div>
                        <div class="text-right mt-10">
                            <span class="text-error font-bold block">0,00%</span>
                            Tingkat Konversi<br>(Pesanan Siap Dikirim<br>dibagi Pesanan Dibuat) <span class="material-symbols-outlined text-[10px]">help</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Kriteria Bawah (Mock Checkboxes) -->
            <div class="border border-outline-variant dark:border-[#30363d] rounded p-6 bg-surface dark:bg-[#161b22] mt-6">
                <h4 class="text-sm font-bold mb-4">Grafik Kriteria</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-on-surface-variant">
                    <label class="flex items-center gap-2"><input type="checkbox" class="accent-error"> Total Pengunjung <span class="material-symbols-outlined text-[14px]">help</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" class="accent-error"> Total Pembeli <span class="material-symbols-outlined text-[14px]">help</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" class="accent-error"> Produk <span class="material-symbols-outlined text-[14px]">help</span></label>
                    <label class="flex items-center gap-2"><input type="checkbox" class="accent-error"> Pesanan <span class="material-symbols-outlined text-[14px]">help</span></label>
                </div>
            </div>
        </div>

        <!-- TAB 4: TINGKAT KUNJUNGAN -->
        <div x-show="tab === 'kunjungan'" class="space-y-6" style="display: none;">
            
            <div class="bg-surface-container-lowest dark:bg-[#161b22] rounded-lg shadow-sm border border-outline-variant dark:border-[#30363d] p-6 space-y-6">
                
                <h3 class="font-bold text-base mb-1">Tinjauan</h3>
                <p class="text-xs text-on-surface-variant mb-6">Analisa performa tingkat kunjungan toko dan halaman rincian produkmu di aplikasi dan situs Toko.</p>

                <div class="border border-outline-variant dark:border-[#30363d] rounded overflow-hidden">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-[#4383eb] text-white">
                            <tr>
                                <th class="p-3 w-[200px]"></th>
                                <th class="p-3 font-semibold text-center">Semua</th>
                                <th class="p-3 font-semibold text-center">Aplikasi</th>
                                <th class="p-3 font-semibold text-center">Situs Toko</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant dark:divide-[#30363d]">
                            <!-- Section 1 -->
                            <tr>
                                <td class="p-0 bg-[#4383eb]/10 dark:bg-[#4383eb]/20 text-[#4383eb] dark:text-[#58a6ff] font-bold border-r border-outline-variant dark:border-[#30363d]" rowspan="4">
                                    <div class="flex flex-col items-center justify-center p-4">
                                        <span class="material-symbols-outlined text-2xl mb-1">visibility</span>
                                        Statistik Dilihat
                                    </div>
                                </td>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] bg-surface dark:bg-[#161b22] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Produk Dilihat <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">0</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] bg-surface dark:bg-[#161b22] font-semibold">0</td>
                                <td class="p-3 text-center bg-surface dark:bg-[#161b22] font-semibold flex justify-center items-center gap-2">0 <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>
                            <tr>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Rata-rata Dilihat <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">0,00</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] font-semibold">0,00</td>
                                <td class="p-3 text-center bg-surface-container-lowest dark:bg-[#0d1117] font-semibold flex justify-center items-center gap-2">0,00 <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>
                            <tr>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] bg-surface dark:bg-[#161b22] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Rata-rata Waktu Dihabiskan <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">00:00:00</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] bg-surface dark:bg-[#161b22] font-semibold">00:00:00</td>
                                <td class="p-3 text-center bg-surface dark:bg-[#161b22] font-semibold flex justify-center items-center gap-2">00:00:00 <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>
                            <tr>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Tingkat Pengunjung Melihat <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">0,00%</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] font-semibold">0,00%</td>
                                <td class="p-3 text-center bg-surface-container-lowest dark:bg-[#0d1117] font-semibold flex justify-center items-center gap-2">0,00% <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>

                            <!-- Section 2 -->
                            <tr>
                                <td class="p-0 bg-[#4383eb]/10 dark:bg-[#4383eb]/20 text-[#4383eb] dark:text-[#58a6ff] font-bold border-r border-outline-variant dark:border-[#30363d] border-t-8 border-t-surface-container dark:border-t-black" rowspan="4">
                                    <div class="flex flex-col items-center justify-center p-4">
                                        <span class="material-symbols-outlined text-2xl mb-1">person</span>
                                        Statistik Kunjungan
                                    </div>
                                </td>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] border-t-8 border-t-surface-container dark:border-t-black bg-surface dark:bg-[#161b22] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Total Pengunjung <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">0</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] border-t-8 border-t-surface-container dark:border-t-black bg-surface dark:bg-[#161b22] font-semibold">0</td>
                                <td class="p-3 text-center border-t-8 border-t-surface-container dark:border-t-black bg-surface dark:bg-[#161b22] font-semibold flex justify-center items-center gap-2">0 <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>
                            <tr>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Pengunjung Baru <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">0</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] font-semibold">0</td>
                                <td class="p-3 text-center bg-surface-container-lowest dark:bg-[#0d1117] font-semibold flex justify-center items-center gap-2">0 <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>
                            <tr>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] bg-surface dark:bg-[#161b22] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Pengunjung Lama <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">0</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] bg-surface dark:bg-[#161b22] font-semibold">0</td>
                                <td class="p-3 text-center bg-surface dark:bg-[#161b22] font-semibold flex justify-center items-center gap-2">0 <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>
                            <tr>
                                <td class="p-3 border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] flex items-center justify-between">
                                    <span class="text-on-surface-variant flex items-center gap-1">Jumlah Pengikut Baru <span class="material-symbols-outlined text-[14px]">help</span></span>
                                    <span class="font-bold">0</span>
                                </td>
                                <td class="p-3 text-center border-r border-outline-variant dark:border-[#30363d] bg-surface-container-lowest dark:bg-[#0d1117] font-semibold">-</td>
                                <td class="p-3 text-center bg-surface-container-lowest dark:bg-[#0d1117] font-semibold flex justify-center items-center gap-2">- <span class="text-[10px] text-on-surface-variant font-normal">0,00%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
