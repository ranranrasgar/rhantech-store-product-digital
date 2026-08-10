@extends('layouts.tenant')

@section('title', 'Tenant Dashboard')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-6 bg-surface-container-lowest dark:bg-transparent">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Banner Info (Pengembalian Pajak dll) -->
        <div class="bg-[#E0F2FE] dark:bg-[#0369A1]/20 border border-[#BAE6FD] dark:border-[#0369A1] rounded-md p-3 flex items-center gap-3">
            <span class="material-symbols-outlined text-[#0284C7] dark:text-[#38BDF8] text-xl">campaign</span>
            <p class="text-sm text-[#0369A1] dark:text-[#E0F2FE]">Selamat datang di Seller Center versi baru. Pantau dan tingkatkan performa toko Anda dari satu tempat!</p>
            <button class="ml-auto text-[#0284C7] dark:text-[#38BDF8] hover:bg-[#BAE6FD] dark:hover:bg-[#0369A1]/50 p-1 rounded transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- KOLOM KIRI & TENGAH (2/3 width on LG) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Pesanan (To-Do List) -->
                <div class="bg-surface dark:bg-white/5 border border-outline-variant dark:border-white/10 rounded-md overflow-hidden">
                    <div class="border-b border-outline-variant dark:border-white/10 px-5 py-3 flex justify-between items-center">
                        <h3 class="font-bold text-on-surface dark:text-white">Pesanan</h3>
                    </div>
                    <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <a href="#" class="block group">
                            <h4 class="text-3xl font-bold text-primary dark:text-[#38BDF8] group-hover:text-primary/80 transition-colors">0</h4>
                            <p class="text-xs mt-2 text-on-surface-variant dark:text-gray-400 group-hover:text-primary transition-colors">Pengiriman Perlu Diproses</p>
                        </a>
                        <a href="#" class="block group">
                            <h4 class="text-3xl font-bold text-primary dark:text-[#38BDF8] group-hover:text-primary/80 transition-colors">0</h4>
                            <p class="text-xs mt-2 text-on-surface-variant dark:text-gray-400 group-hover:text-primary transition-colors">Pengiriman Telah Diproses</p>
                        </a>
                        <a href="#" class="block group">
                            <h4 class="text-3xl font-bold text-primary dark:text-[#38BDF8] group-hover:text-primary/80 transition-colors">0</h4>
                            <p class="text-xs mt-2 text-on-surface-variant dark:text-gray-400 group-hover:text-primary transition-colors">Pengembalian/Pembatalan</p>
                        </a>
                        <a href="#" class="block group">
                            <h4 class="text-3xl font-bold text-primary dark:text-[#38BDF8] group-hover:text-primary/80 transition-colors">{{ $totalProducts ?? 0 }}</h4>
                            <p class="text-xs mt-2 text-on-surface-variant dark:text-gray-400 group-hover:text-primary transition-colors">Jumlah Produk Aktif</p>
                        </a>
                    </div>
                </div>

                <!-- Performa Toko -->
                <div class="bg-surface dark:bg-white/5 border border-outline-variant dark:border-white/10 rounded-md overflow-hidden">
                    <div class="border-b border-outline-variant dark:border-white/10 px-5 py-3 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <h3 class="font-bold text-on-surface dark:text-white">Performa Toko</h3>
                            <span class="text-xs text-on-surface-variant dark:text-gray-500">Waktu update terakhir: Hari Ini</span>
                        </div>
                        <a href="#" class="text-sm text-primary dark:text-[#38BDF8] hover:underline flex items-center">Lainnya <span class="material-symbols-outlined text-sm ml-1">chevron_right</span></a>
                    </div>
                    <div class="p-5 grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1 flex items-center gap-1">Penjualan <span class="material-symbols-outlined text-[14px]">help</span></p>
                            <h4 class="text-xl font-bold text-on-surface dark:text-white">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</h4>
                            <p class="text-xs text-gray-400 mt-1">- 0,00%</p>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1 flex items-center gap-1">Total Pengunjung <span class="material-symbols-outlined text-[14px]">help</span></p>
                            <h4 class="text-xl font-bold text-on-surface dark:text-white">0</h4>
                            <p class="text-xs text-gray-400 mt-1">- 0,00%</p>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1 flex items-center gap-1">Produk Diklik <span class="material-symbols-outlined text-[14px]">help</span></p>
                            <h4 class="text-xl font-bold text-on-surface dark:text-white">0</h4>
                            <p class="text-xs text-gray-400 mt-1">- 0,00%</p>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1 flex items-center gap-1">Pesanan <span class="material-symbols-outlined text-[14px]">help</span></p>
                            <h4 class="text-xl font-bold text-on-surface dark:text-white">0</h4>
                            <p class="text-xs text-gray-400 mt-1">- 0,00%</p>
                        </div>
                        <div>
                            <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1 flex items-center gap-1">Konversi <span class="material-symbols-outlined text-[14px]">help</span></p>
                            <h4 class="text-xl font-bold text-on-surface dark:text-white">0,00%</h4>
                            <p class="text-xs text-gray-400 mt-1">- 0,00%</p>
                        </div>
                    </div>
                </div>

                <!-- Iklan & Promosi -->
                <div class="bg-surface dark:bg-white/5 border border-outline-variant dark:border-white/10 rounded-md overflow-hidden">
                    <div class="border-b border-outline-variant dark:border-white/10 px-5 py-3 flex justify-between items-center">
                        <h3 class="font-bold text-on-surface dark:text-white">Promosi Toko</h3>
                        <a href="#" class="text-sm text-primary dark:text-[#38BDF8] hover:underline flex items-center">Lainnya <span class="material-symbols-outlined text-sm ml-1">chevron_right</span></a>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Promo 1 -->
                        <div class="border border-outline-variant dark:border-white/10 rounded bg-[#F8FAFC] dark:bg-white/5 p-4 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-error/10 text-error rounded-full flex-shrink-0">
                                        <span class="material-symbols-outlined text-lg">campaign</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-on-surface dark:text-white">Tingkatkan Penjualan Anda</h4>
                                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mt-1">Kirim pesan promo massal ke pengikut toko Anda dengan fitur Broadcast.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-right">
                                <a href="#" class="inline-block px-4 py-1.5 border border-primary text-primary text-sm font-semibold rounded hover:bg-primary/5 transition-colors">Coba Sekarang</a>
                            </div>
                        </div>
                        
                        <!-- Promo 2 -->
                        <div class="border border-outline-variant dark:border-white/10 rounded bg-[#F8FAFC] dark:bg-white/5 p-4 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-primary/10 text-primary rounded-full flex-shrink-0">
                                        <span class="material-symbols-outlined text-lg">trending_up</span>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-on-surface dark:text-white">Maksimalkan dengan Iklan</h4>
                                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mt-1">Pelajari lebih lanjut cara terbaik mengiklankan produk toko Anda agar lebih laris.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-right">
                                <a href="#" class="inline-block px-4 py-1.5 bg-primary text-white text-sm font-semibold rounded hover:bg-primary/90 transition-colors">Pelajari Lebih Lanjut</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom row: Affiliate & Livestream -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Affiliate -->
                    <div class="bg-surface dark:bg-white/5 border border-outline-variant dark:border-white/10 rounded-md overflow-hidden flex flex-col">
                        <div class="border-b border-outline-variant dark:border-white/10 px-5 py-3 flex justify-between items-center">
                            <h3 class="font-bold text-on-surface dark:text-white">Affiliate Marketing</h3>
                            <a href="#" class="text-sm text-primary dark:text-[#38BDF8] hover:underline flex items-center">Lainnya <span class="material-symbols-outlined text-sm ml-1">chevron_right</span></a>
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-center">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1">Penjualan</p>
                                    <h4 class="text-lg font-bold text-on-surface dark:text-white">Rp 0</h4>
                                </div>
                                <div>
                                    <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1">Pembeli Baru</p>
                                    <h4 class="text-lg font-bold text-on-surface dark:text-white">0</h4>
                                </div>
                                <div>
                                    <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1">ROI</p>
                                    <h4 class="text-lg font-bold text-on-surface dark:text-white">-</h4>
                                </div>
                            </div>
                            <div class="bg-[#FFFBEB] dark:bg-[#78350F]/20 border border-[#FDE68A] dark:border-[#78350F] rounded p-3">
                                <h5 class="text-xs font-bold text-[#92400E] dark:text-[#FDE68A] flex items-center gap-1 mb-1"><span class="material-symbols-outlined text-[14px]">lightbulb</span> Saran Optimasi</h5>
                                <p class="text-[10px] text-[#92400E] dark:text-[#FDE68A]/80">Daftarkan produk Anda ke program affiliate untuk menjangkau lebih banyak pembeli tanpa biaya di muka.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Livestream -->
                    <div class="bg-surface dark:bg-white/5 border border-outline-variant dark:border-white/10 rounded-md overflow-hidden flex flex-col relative">
                        <div class="border-b border-outline-variant dark:border-white/10 px-5 py-3 flex justify-between items-center z-10 relative">
                            <h3 class="font-bold text-on-surface dark:text-white">Livestream</h3>
                            <a href="#" class="text-sm text-primary dark:text-[#38BDF8] hover:underline flex items-center">Lainnya <span class="material-symbols-outlined text-sm ml-1">chevron_right</span></a>
                        </div>
                        <div class="p-5 flex-1 z-10 relative flex flex-col justify-center">
                            <h4 class="text-lg font-bold text-on-surface dark:text-white leading-tight mb-2">Buat Livestream<br>Sekarang dan<br>Tingkatkan Konversi!</h4>
                            <div class="mt-auto">
                                <a href="#" class="inline-block px-5 py-2 bg-error text-white text-sm font-bold rounded-full hover:bg-error/90 transition-colors">Buat Livestream</a>
                            </div>
                        </div>
                        <!-- Background Pattern/Illustration -->
                        <div class="absolute bottom-0 right-0 w-32 h-32 opacity-20 pointer-events-none">
                            <span class="material-symbols-outlined text-[120px] text-error">live_tv</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN (1/3 width on LG) -->
            <div class="space-y-6">
                
                <!-- Rekomendasi Bisnis -->
                <div class="bg-surface dark:bg-white/5 border border-outline-variant dark:border-white/10 rounded-md overflow-hidden">
                    <div class="border-b border-outline-variant dark:border-white/10 px-5 py-3 flex justify-between items-center">
                        <h3 class="font-bold text-on-surface dark:text-white">Rekomendasi Bisnis</h3>
                        <span class="text-xs text-on-surface-variant">3 rekomendasi</span>
                    </div>
                    <div class="p-0 divide-y divide-outline-variant/50 dark:divide-white/10">
                        
                        <!-- Item 1 -->
                        <div class="p-4 hover:bg-surface-container-lowest dark:hover:bg-white/10 transition-colors">
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-primary">upload_file</span>
                                <div>
                                    <h4 class="text-sm font-bold text-on-surface dark:text-white mb-1">Upload massal 10 produk</h4>
                                    <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-3">Dapatkan eksposur tambahan untuk produk-produk terbaru Anda.</p>
                                    <a href="#" class="text-xs font-semibold text-primary border border-primary rounded px-3 py-1 hover:bg-primary/5">Mulai</a>
                                </div>
                            </div>
                        </div>

                        <!-- Item 2 -->
                        <div class="p-4 hover:bg-surface-container-lowest dark:hover:bg-white/10 transition-colors">
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-primary">account_balance_wallet</span>
                                <div>
                                    <h4 class="text-sm font-bold text-on-surface dark:text-white mb-1">Aktifkan Saldo Toko</h4>
                                    <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-3">Kelola penghasilan Anda dengan lebih mudah dan cairkan kapan saja.</p>
                                    <a href="#" class="text-xs font-semibold text-primary border border-primary rounded px-3 py-1 hover:bg-primary/5">Aktifkan Sekarang</a>
                                </div>
                            </div>
                        </div>

                        <!-- Item 3 -->
                        <div class="p-4 hover:bg-surface-container-lowest dark:hover:bg-white/10 transition-colors">
                            <div class="flex gap-3">
                                <span class="material-symbols-outlined text-primary">local_offer</span>
                                <div>
                                    <h4 class="text-sm font-bold text-on-surface dark:text-white mb-1">Buat Voucher Toko</h4>
                                    <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-3">Tarik minat pembeli untuk melakukan checkout dengan potongan harga spesial.</p>
                                    <a href="#" class="text-xs font-semibold text-primary border border-primary rounded px-3 py-1 hover:bg-primary/5">Buat Voucher</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Berita / Informasi -->
                <div class="bg-surface dark:bg-white/5 border border-outline-variant dark:border-white/10 rounded-md overflow-hidden">
                    <div class="border-b border-outline-variant dark:border-white/10 px-5 py-3 flex justify-between items-center">
                        <h3 class="font-bold text-on-surface dark:text-white">Berita</h3>
                        <a href="#" class="text-sm text-primary dark:text-[#38BDF8] hover:underline flex items-center">Lainnya <span class="material-symbols-outlined text-sm ml-1">chevron_right</span></a>
                    </div>
                    <div class="p-8 flex flex-col items-center justify-center text-center">
                        <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 mb-3">article</span>
                        <p class="text-sm text-on-surface-variant dark:text-gray-400">Belum ada Informasi baru</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
