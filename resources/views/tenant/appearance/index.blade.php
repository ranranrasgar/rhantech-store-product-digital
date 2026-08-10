@extends('layouts.tenant')

@section('title', 'Dekorasi Toko Saat Ini')

@section('content')
<!-- Header (PPOB Style) -->
<div class="bg-surface dark:bg-[#161b22] border-b border-outline-variant dark:border-[#30363d] p-4 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-4">
        <h2 class="font-bold text-lg text-on-surface dark:text-white">Dekorasi Toko Saat Ini</h2>
        <span class="text-xs text-on-surface-variant dark:text-gray-400">Waktu Terakhir Disimpan: {{ date('d-m-Y H:i') }}</span>
    </div>
    <div class="flex items-center gap-3">
        <button class="px-4 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded text-on-surface hover:bg-surface-variant/50 transition-colors">Ganti dengan Template Lain</button>
        <button class="px-4 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded text-on-surface hover:bg-surface-variant/50 transition-colors">Preview</button>
        <button class="px-4 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded text-on-surface hover:bg-surface-variant/50 transition-colors">Simpan</button>
        <button class="px-4 py-1.5 text-sm font-bold border border-error bg-error text-white rounded hover:bg-[#d73f22] transition-colors">Tampilkan</button>
    </div>
</div>

<div class="flex-1 flex overflow-hidden bg-surface-container-lowest dark:bg-[#0d1117] h-[calc(100vh-120px)]" x-data="{ tab: 'komponen' }">
    
    <!-- LEFT SIDEBAR: COMPONENTS -->
    <div class="w-[280px] border-r border-outline-variant dark:border-[#30363d] bg-surface dark:bg-[#161b22] flex flex-col h-full z-10 overflow-hidden shrink-0">
        <!-- Search bar -->
        <div class="p-4 border-b border-outline-variant dark:border-[#30363d]">
            <div class="relative">
                <input type="text" placeholder="Cari" class="w-full pl-3 pr-8 py-1.5 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] rounded focus:outline-none focus:border-error transition-colors">
                <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface-variant text-[16px]">search</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex text-sm font-semibold border-b border-outline-variant dark:border-[#30363d]">
            <button @click="tab = 'komponen'" :class="tab === 'komponen' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-on-surface hover:text-error'" class="flex-1 py-3 transition-colors text-center">Komponen<br>Dekorasi</button>
            <button @click="tab = 'template'" :class="tab === 'template' ? 'border-b-2 border-error text-error' : 'border-b-2 border-transparent text-on-surface hover:text-error'" class="flex-1 py-3 transition-colors text-center">Komponen<br>Template</button>
        </div>

        <!-- Tab Content: Komponen Dekorasi -->
        <div x-show="tab === 'komponen'" class="flex-1 overflow-y-auto p-4 space-y-6 custom-scrollbar">
            
            <!-- Category: Tampilan & Teks -->
            <div>
                <h3 class="font-bold text-sm mb-4 flex items-center justify-between">Tampilan & Teks <span class="material-symbols-outlined text-[16px]">expand_less</span></h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Item 1 -->
                    <div class="cursor-pointer group flex flex-col items-center">
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-2 mb-2 group-hover:border-error transition-colors w-full h-16 flex items-center justify-center bg-surface-container-lowest">
                            <div class="flex gap-1 w-full px-1">
                                <div class="w-1/2 h-10 bg-[#e0f2fe] rounded"></div>
                                <div class="w-1/2 h-10 bg-[#fee2e2] rounded"></div>
                            </div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center">Banner Toko <span class="material-symbols-outlined text-[10px]">help</span><br>1/10</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="cursor-pointer group flex flex-col items-center">
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-2 mb-2 group-hover:border-error transition-colors w-full h-16 flex items-center justify-center bg-surface-container-lowest">
                            <div class="w-full h-10 bg-gradient-to-r from-teal-400 to-emerald-400 rounded"></div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center">Satu Foto <span class="material-symbols-outlined text-[10px]">help</span><br>1/10</span>
                    </div>
                    <!-- Item 3 -->
                    <div class="cursor-pointer group flex flex-col items-center">
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-2 mb-2 group-hover:border-error transition-colors w-full h-16 flex items-center justify-center bg-surface-container-lowest text-[8px] text-on-surface-variant">
                            Abcdefg hijklm nopqrstu vwxyz.
                        </div>
                        <span class="text-xs text-on-surface-variant text-center">Teks <span class="material-symbols-outlined text-[10px]">help</span><br>1/10</span>
                    </div>
                </div>
            </div>

            <!-- Category: Produk & Kategori -->
            <div>
                <h3 class="font-bold text-sm mb-4 flex items-center justify-between">Produk & Kategori <span class="material-symbols-outlined text-[16px]">expand_less</span></h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Item 1 -->
                    <div class="cursor-pointer group flex flex-col items-center relative">
                        <div class="border border-error rounded p-2 mb-2 bg-surface-container-lowest w-full h-20 flex flex-col items-center">
                            <div class="grid grid-cols-2 gap-1 w-full px-2 mt-1">
                                <div class="w-full h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                <div class="w-full h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </div>
                            <div class="absolute bottom-[28px] bg-[#1e88e5] text-white text-[10px] px-2 rounded-t font-bold">Disesuaikan</div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center mt-2">Produk Pilihan <span class="material-symbols-outlined text-[10px]">help</span><br>1/10</span>
                    </div>
                    <!-- Item 2 -->
                    <div class="cursor-pointer group flex flex-col items-center relative">
                        <div class="border border-error rounded p-2 mb-2 bg-surface-container-lowest w-full h-20 flex flex-col items-center">
                            <div class="grid grid-cols-2 gap-1 w-full px-2 mt-1">
                                <div class="w-full h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                <div class="w-full h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </div>
                            <div class="absolute bottom-[28px] bg-[#1e88e5] text-white text-[10px] px-2 rounded-t font-bold">Disesuaikan</div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center mt-2">Produk Berdasarkan Kategori <span class="material-symbols-outlined text-[10px]">help</span><br>3/10</span>
                    </div>
                </div>
            </div>

        </div>

        <button class="absolute -right-4 top-1/2 -translate-y-1/2 bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] w-8 h-8 rounded-full shadow-md flex items-center justify-center text-on-surface-variant hover:text-on-surface z-20"><span class="material-symbols-outlined text-[16px]">chevron_left</span></button>
    </div>

    <!-- CENTER CANVAS (Store Preview) -->
    <div class="flex-1 bg-surface-container-lowest dark:bg-[#0d1117] flex justify-center py-8 overflow-y-auto relative custom-scrollbar">
        
        <!-- Mobile Frame Wrapper -->
        <div class="w-[375px] bg-white border border-outline-variant shadow-lg rounded-[2.5rem] overflow-hidden flex flex-col relative h-[812px] shrink-0 outline outline-[12px] outline-[#f0f0f0] dark:outline-[#1a1a1a]">
            
            <!-- Mobile Status Bar (Mock) -->
            <div class="h-6 bg-black text-white text-[10px] flex justify-between items-center px-4 shrink-0 absolute top-0 left-0 right-0 z-50">
                <span>12:30</span>
                <div class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[12px]">network_wifi</span>
                    <span class="material-symbols-outlined text-[12px]">battery_full</span>
                </div>
            </div>

            <!-- Canvas Content -->
            <div class="flex-1 overflow-y-auto bg-[#f6f6f6] relative">
                
                <!-- Store Header Banner -->
                <div class="h-[140px] bg-[#1a1a1a] relative group border-2 border-transparent hover:border-error transition-all">
                    <!-- Edit overlay placeholder -->
                    <div class="absolute inset-0 border border-error bg-error/10 hidden group-hover:block z-20 cursor-pointer"></div>

                    <!-- Background Image (Mock) -->
                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?w=600&h=300&fit=crop')] bg-cover bg-center opacity-80"></div>
                    <div class="absolute inset-0 bg-black/40"></div>

                    <!-- Top Bar -->
                    <div class="absolute top-8 left-4 right-4 flex justify-between text-white z-10">
                        <span class="material-symbols-outlined">arrow_back</span>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">search</span>
                            <span class="material-symbols-outlined text-[18px]">more_vert</span>
                        </div>
                    </div>

                    <!-- Profile Info -->
                    <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between z-10">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name ?? 'Store') }}&background=0D8ABC&color=fff" class="w-12 h-12 rounded-full border-2 border-white object-cover">
                            <div>
                                <h1 class="text-white font-bold text-sm drop-shadow-md">{{ $store->name ?? 'Toko Anda' }}</h1>
                                <div class="text-white text-[10px] drop-shadow-md flex items-center gap-1">
                                    <span class="text-yellow-400">★ 4.8</span> | 1.6K Pengikut
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <button class="bg-white/20 backdrop-blur text-white border border-white/50 text-[10px] font-bold px-3 py-1 rounded">+ Mengikuti</button>
                            <button class="bg-white/20 backdrop-blur text-white border border-white/50 text-[10px] font-bold px-3 py-1 rounded">Chat</button>
                        </div>
                    </div>
                </div>

                <!-- Tabs (Mock) -->
                <div class="bg-white flex text-sm font-semibold sticky top-0 z-30 shadow-sm border-b border-gray-200">
                    <button class="flex-1 py-3 text-error border-b-2 border-error">Toko</button>
                    <button class="flex-1 py-3 text-gray-500 hover:text-error hover:border-b-2 hover:border-error border-b-2 border-transparent">Produk</button>
                    <button class="flex-1 py-3 text-gray-500 hover:text-error hover:border-b-2 hover:border-error border-b-2 border-transparent">Kategori</button>
                </div>

                <div class="p-3 space-y-3">
                    
                    <!-- Component: Voucher (Empty State) -->
                    <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded p-4 flex gap-4 items-center group relative cursor-pointer shadow-sm">
                        <div class="absolute inset-0 border border-error bg-error/5 hidden group-hover:block z-10"></div>
                        <!-- Indicator -->
                        <div class="absolute -left-20 top-1/2 -translate-y-1/2 flex items-center justify-end w-16 opacity-0 group-hover:opacity-100">
                            <span class="text-[10px] bg-white px-2 py-1 rounded shadow-sm">Voucher</span>
                            <div class="w-4 h-[1px] bg-gray-300"></div>
                        </div>

                        <div class="w-10 h-10 border border-gray-300 border-dashed rounded flex items-center justify-center text-gray-300 shrink-0"><span class="material-symbols-outlined text-[20px]">local_activity</span></div>
                        <p class="text-[11px] text-gray-400">Kamu belum membuat Voucher. Komponen ini akan disembunyikan otomatis dari halaman utama tokomu.</p>
                    </div>

                    <!-- Component: Flash Sale (Empty State) -->
                    <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded p-4 flex gap-4 items-center group relative cursor-pointer shadow-sm">
                        <div class="absolute inset-0 border border-error bg-error/5 hidden group-hover:block z-10"></div>
                        <!-- Indicator -->
                        <div class="absolute -left-20 top-1/2 -translate-y-1/2 flex items-center justify-end w-16 opacity-0 group-hover:opacity-100">
                            <span class="text-[10px] bg-white px-2 py-1 rounded shadow-sm">Flash Sale</span>
                            <div class="w-4 h-[1px] bg-gray-300"></div>
                        </div>

                        <div class="w-10 h-10 border border-gray-300 border-dashed rounded flex items-center justify-center text-gray-300 shrink-0"><span class="material-symbols-outlined text-[20px]">bolt</span></div>
                        <p class="text-[11px] text-gray-400">Tidak ada produk yang dapat ditampilkan. Komponen ini akan tidak ditampilkan di halaman utama tokomu.</p>
                    </div>

                    <!-- Component: Banner Toko (Filled) -->
                    <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded overflow-hidden group relative cursor-pointer shadow-sm">
                        <div class="absolute inset-0 border border-error hidden group-hover:block z-10 pointer-events-none"></div>
                        <!-- Indicator -->
                        <div class="absolute -left-20 top-1/2 -translate-y-1/2 flex items-center justify-end w-16 opacity-0 group-hover:opacity-100 z-20">
                            <span class="text-[10px] bg-white px-2 py-1 rounded shadow-sm text-center">Banner Toko</span>
                            <div class="w-4 h-[1px] bg-gray-300"></div>
                        </div>

                        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=600&h=300&fit=crop" class="w-full h-40 object-cover">
                    </div>

                    <!-- Component: Teks (Filled) -->
                    <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded p-4 group relative cursor-pointer shadow-sm">
                        <div class="absolute inset-0 border border-error bg-error/5 hidden group-hover:block z-10 pointer-events-none"></div>
                        <!-- Indicator -->
                        <div class="absolute -left-20 top-1/2 -translate-y-1/2 flex items-center justify-end w-16 opacity-0 group-hover:opacity-100 z-20">
                            <span class="text-[10px] bg-white px-2 py-1 rounded shadow-sm">Teks</span>
                            <div class="w-4 h-[1px] bg-gray-300"></div>
                        </div>

                        <p class="text-xs text-gray-600 leading-relaxed font-serif">Merupakan perusahaan yang berbadan hukum yang bergerak dalam bidang jasa pengembangan sistem terpadu yang meliputi konsultan akuntansi, Jasa Pembukuan, Jasa Kompilasi Laporan Keuangan, Jasa Manajemen, Akuntansi Manajemen, Konsultasi LAINNYA.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Help Button (Absolute) -->
    <button class="absolute right-8 bottom-8 bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] w-10 h-10 rounded-full shadow-lg flex items-center justify-center text-on-surface hover:text-primary transition-colors">
        <span class="material-symbols-outlined text-[20px]">help_outline</span>
    </button>
</div>
@endsection
