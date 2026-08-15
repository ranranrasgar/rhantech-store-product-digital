@extends('layouts.tenant')

@section('title', 'Dekorasi Toko Saat Ini')

@section('content')
<!-- Alpine Scope Wrapper -->
<div class="flex flex-col h-full" x-data="appearanceEditor()">
    <!-- Header (PPOB Style) -->
<div class="bg-surface dark:bg-[#161b22] border-b border-outline-variant dark:border-[#30363d] p-4 flex items-center justify-between shadow-sm">
    <div class="flex items-center gap-4">
        <h2 class="font-bold text-lg text-on-surface dark:text-white">Dekorasi Toko Saat Ini</h2>
        <span class="text-xs text-on-surface-variant dark:text-gray-400">Waktu Terakhir Disimpan: {{ date('d-m-Y H:i') }}</span>
    </div>
    <div class="flex items-center gap-3">
        <button class="px-4 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded text-on-surface hover:bg-surface-variant/50 transition-colors">Ganti dengan Template Lain</button>
        <button class="px-4 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded text-on-surface hover:bg-surface-variant/50 transition-colors">Preview</button>
        <button @click="save()" class="px-4 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded text-on-surface hover:bg-surface-variant/50 transition-colors">
            <span x-show="!isSaving">Simpan</span>
            <span x-show="isSaving">Menyimpan...</span>
        </button>
        <button class="px-4 py-1.5 text-sm font-bold border border-error bg-error text-white rounded hover:bg-[#d73f22] transition-colors">Tampilkan</button>
    </div>
</div>

<div class="flex-1 flex overflow-hidden bg-surface-container-lowest dark:bg-[#0d1117] h-[calc(100vh-120px)]">
    
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
                    <div @click="addComponent('banner')" class="cursor-pointer group flex flex-col items-center">
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-2 mb-2 group-hover:border-error transition-colors w-full h-16 flex items-center justify-center bg-surface-container-lowest">
                            <div class="flex gap-1 w-full px-1">
                                <div class="w-1/2 h-10 bg-[#e0f2fe] rounded"></div>
                                <div class="w-1/2 h-10 bg-[#fee2e2] rounded"></div>
                            </div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center">Banner Toko <span class="material-symbols-outlined text-[10px]">help</span></span>
                    </div>
                    <!-- Item 2 -->
                    <div @click="addComponent('single_image')" class="cursor-pointer group flex flex-col items-center">
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-2 mb-2 group-hover:border-error transition-colors w-full h-16 flex items-center justify-center bg-surface-container-lowest">
                            <div class="w-full h-10 bg-gradient-to-r from-teal-400 to-emerald-400 rounded"></div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center">Satu Foto <span class="material-symbols-outlined text-[10px]">help</span></span>
                    </div>
                    <!-- Item 3 -->
                    <div @click="addComponent('text')" class="cursor-pointer group flex flex-col items-center">
                        <div class="border border-outline-variant dark:border-[#30363d] rounded p-2 mb-2 group-hover:border-error transition-colors w-full h-16 flex items-center justify-center bg-surface-container-lowest text-[8px] text-on-surface-variant">
                            Abcdefg hijklm nopqrstu vwxyz.
                        </div>
                        <span class="text-xs text-on-surface-variant text-center">Teks <span class="material-symbols-outlined text-[10px]">help</span></span>
                    </div>
                </div>
            </div>

            <!-- Category: Produk & Kategori -->
            <div>
                <h3 class="font-bold text-sm mb-4 flex items-center justify-between">Produk & Kategori <span class="material-symbols-outlined text-[16px]">expand_less</span></h3>
                <div class="grid grid-cols-2 gap-4">
                    <!-- Item 1 -->
                    <div @click="addComponent('products')" class="cursor-pointer group flex flex-col items-center relative">
                        <div class="border border-outline-variant dark:border-[#30363d] group-hover:border-error transition-colors rounded p-2 mb-2 bg-surface-container-lowest w-full h-20 flex flex-col items-center">
                            <div class="grid grid-cols-2 gap-1 w-full px-2 mt-1">
                                <div class="w-full h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                                <div class="w-full h-10 bg-gray-200 dark:bg-gray-700 rounded"></div>
                            </div>
                            <div class="absolute bottom-[28px] bg-[#1e88e5] text-white text-[10px] px-2 rounded-t font-bold">Produk</div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center mt-2">Produk Pilihan <span class="material-symbols-outlined text-[10px]">help</span></span>
                    </div>
                    <!-- Item 2 -->
                    <div @click="addComponent('flash_sale')" class="cursor-pointer group flex flex-col items-center relative">
                        <div class="border border-outline-variant dark:border-[#30363d] group-hover:border-error transition-colors rounded p-2 mb-2 bg-surface-container-lowest w-full h-20 flex flex-col items-center">
                            <div class="grid grid-cols-2 gap-1 w-full px-2 mt-1">
                                <div class="w-full h-10 bg-yellow-200 dark:bg-yellow-700 rounded"></div>
                                <div class="w-full h-10 bg-yellow-200 dark:bg-yellow-700 rounded"></div>
                            </div>
                            <div class="absolute bottom-[28px] bg-error text-white text-[10px] px-2 rounded-t font-bold">Flash Sale</div>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center mt-2">Flash Sale <span class="material-symbols-outlined text-[10px]">help</span></span>
                    </div>
                    <!-- Item 3 -->
                    <div @click="addComponent('voucher')" class="cursor-pointer group flex flex-col items-center relative">
                        <div class="border border-outline-variant dark:border-[#30363d] group-hover:border-error transition-colors rounded p-2 mb-2 bg-surface-container-lowest w-full h-20 flex flex-col items-center justify-center text-error">
                            <span class="material-symbols-outlined text-[32px]">local_activity</span>
                        </div>
                        <span class="text-xs text-on-surface-variant text-center mt-2">Voucher <span class="material-symbols-outlined text-[10px]">help</span></span>
                    </div>
                </div>
            </div>

        </div>

        <button class="absolute -right-4 top-1/2 -translate-y-1/2 bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] w-8 h-8 rounded-full shadow-md flex items-center justify-center text-on-surface-variant hover:text-on-surface z-20"><span class="material-symbols-outlined text-[16px]">chevron_left</span></button>
    </div>

    <!-- CENTER CANVAS (Store Preview) -->
    <div class="flex-1 bg-surface-container-lowest dark:bg-[#0d1117] flex flex-col items-center py-8 overflow-auto relative custom-scrollbar">
        
        <!-- Device Toggle -->
        <div class="flex items-center gap-2 mb-6 bg-surface dark:bg-[#161b22] p-1 rounded-lg border border-outline-variant dark:border-[#30363d] shrink-0 shadow-sm">
            <button @click="device = 'mobile'" :class="device === 'mobile' ? 'bg-[#00b3cc] dark:bg-[#2f81f7] text-white' : 'text-on-surface hover:bg-surface-variant/50'" class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">smartphone</span> Mobile
            </button>
            <button @click="device = 'desktop'" :class="device === 'desktop' ? 'bg-[#00b3cc] dark:bg-[#2f81f7] text-white' : 'text-on-surface hover:bg-surface-variant/50'" class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">computer</span> Desktop
            </button>
        </div>

        <!-- Mobile Frame Wrapper -->
        <div x-show="device === 'mobile'" class="w-[375px] bg-white border border-outline-variant shadow-lg rounded-[2.5rem] overflow-hidden flex flex-col relative h-[812px] shrink-0 outline outline-[12px] outline-[#f0f0f0] dark:outline-[#1a1a1a]">
            
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

                <div id="mobile-canvas" class="p-3 space-y-3 pb-20 min-h-[200px]">
                    <template x-for="(comp, index) in activeComponents" :key="comp.id">
                        <div class="relative group cursor-move drag-handle">
                            <!-- Overlay Delete Button -->
                            <button @click="removeComponent(index)" class="absolute -right-2 -top-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hidden group-hover:flex z-50 shadow-md hover:bg-red-600 transition-colors">
                                <span class="material-symbols-outlined text-[14px]">close</span>
                            </button>

                            <!-- VOUCHER -->
                            <template x-if="comp.type === 'voucher'">
                                <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded p-4 flex gap-4 items-center shadow-sm">
                                    <div class="w-10 h-10 border border-gray-300 border-dashed rounded flex items-center justify-center text-gray-300 shrink-0"><span class="material-symbols-outlined text-[20px]">local_activity</span></div>
                                    <p class="text-[11px] text-gray-400">Kamu belum membuat Voucher. Komponen ini akan disembunyikan otomatis.</p>
                                </div>
                            </template>

                            <!-- FLASH SALE -->
                            <template x-if="comp.type === 'flash_sale'">
                                <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded p-4 flex gap-4 items-center shadow-sm">
                                    <div class="w-10 h-10 border border-gray-300 border-dashed rounded flex items-center justify-center text-gray-300 shrink-0"><span class="material-symbols-outlined text-[20px]">bolt</span></div>
                                    <p class="text-[11px] text-gray-400">Tidak ada produk Flash Sale yang aktif.</p>
                                </div>
                            </template>

                            <!-- BANNER -->
                            <template x-if="comp.type === 'banner'">
                                <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded overflow-hidden shadow-sm h-40 flex items-center justify-center bg-gray-100">
                                    <span class="text-gray-400 text-sm font-semibold">Banner Toko (Pilih Gambar)</span>
                                </div>
                            </template>
                            
                            <!-- SINGLE IMAGE -->
                            <template x-if="comp.type === 'single_image'">
                                <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded overflow-hidden shadow-sm h-60 flex items-center justify-center bg-gray-100">
                                    <span class="text-gray-400 text-sm font-semibold">Satu Foto (Pilih Gambar)</span>
                                </div>
                            </template>

                            <!-- TEKS -->
                            <template x-if="comp.type === 'text'">
                                <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded p-4 shadow-sm">
                                    <p class="text-xs text-gray-600 leading-relaxed font-serif">Tambahkan teks deksripsi atau promosi toko Anda di sini. Anda dapat mengedit teks ini di panel pengaturan komponen.</p>
                                </div>
                            </template>
                            
                            <!-- PRODUK PILIHAN -->
                            <template x-if="comp.type === 'products'">
                                <div class="bg-white border-2 border-transparent hover:border-error transition-all rounded p-4 shadow-sm">
                                    <h4 class="font-bold text-sm mb-3 text-center">Produk Pilihan</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="h-24 bg-gray-100 rounded"></div>
                                        <div class="h-24 bg-gray-100 rounded"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    
                    <div x-show="activeComponents.length === 0" class="text-center py-10 text-gray-400 text-sm border-2 border-dashed border-gray-300 rounded-lg">
                        Tarik komponen dari sidebar ke sini
                    </div>
                </div>

            </div>
        </div>

        <!-- Desktop Frame Wrapper -->
        <div x-show="device === 'desktop'" style="display: none;" class="w-[1024px] bg-white dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] shadow-lg rounded-xl overflow-hidden flex flex-col relative min-h-[768px] shrink-0">
            
            <!-- Desktop Browser Mock -->
            <div class="h-10 bg-gray-100 dark:bg-[#161b22] border-b border-gray-200 dark:border-[#30363d] flex items-center px-4 gap-4 shrink-0">
                <div class="flex gap-2">
                    <div class="w-3.5 h-3.5 rounded-full bg-red-400"></div>
                    <div class="w-3.5 h-3.5 rounded-full bg-yellow-400"></div>
                    <div class="w-3.5 h-3.5 rounded-full bg-green-400"></div>
                </div>
                <div class="flex-1 flex justify-center">
                    <div class="bg-white dark:bg-[#0d1117] border border-gray-200 dark:border-[#30363d] text-xs px-4 py-1.5 rounded-md text-gray-500 w-[60%] flex items-center gap-2">
                        <span class="material-symbols-outlined text-[14px]">lock</span>
                        <span class="truncate">https://rhantech.com/toko/{{ strtolower(str_replace(' ', '-', $store->name ?? 'toko-anda')) }}</span>
                    </div>
                </div>
                <div class="w-[54px]"></div>
            </div>

            <!-- Canvas Content (Desktop) -->
            <div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] relative flex flex-col items-center custom-scrollbar">
                
                <!-- Desktop Header -->
                <div class="w-full h-[300px] bg-[#1a1a1a] relative group border-2 border-transparent hover:border-error transition-all cursor-pointer">
                    <!-- Edit overlay placeholder -->
                    <div class="absolute inset-0 border border-error bg-error/10 hidden group-hover:block z-20"></div>

                    <!-- Background Image (Mock) -->
                    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1557683316-973673baf926?w=1200&h=400&fit=crop')] bg-cover bg-center opacity-80"></div>
                    <div class="absolute inset-0 bg-black/50"></div>

                    <div class="absolute bottom-8 left-0 right-0 flex items-end justify-between z-10 max-w-[960px] mx-auto w-full px-6">
                        <div class="flex items-center gap-6">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name ?? 'Store') }}&background=0D8ABC&color=fff" class="w-[120px] h-[120px] rounded-full border-[5px] border-white object-cover shadow-lg">
                            <div class="pb-3">
                                <h1 class="text-white font-bold text-4xl drop-shadow-md mb-2">{{ $store->name ?? 'Toko Anda' }}</h1>
                                <div class="text-white text-sm drop-shadow-md flex items-center gap-3">
                                    <span class="flex items-center gap-1 text-yellow-400 font-bold"><span class="material-symbols-outlined text-[18px]">star</span> 4.8</span>
                                    <span class="text-white/50">•</span>
                                    <span>1.6K Pengikut</span>
                                    <span class="text-white/50">•</span>
                                    <span>89 Produk</span>
                                    <span class="text-white/50">•</span>
                                    <span>Aktif 2 menit lalu</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3 pb-3">
                            <button class="bg-[#00b3cc] hover:bg-[#00838f] dark:bg-[#2f81f7] dark:hover:bg-[#1f6feb] text-white font-bold px-6 py-2.5 rounded shadow transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add</span> Mengikuti
                            </button>
                            <button class="bg-white/10 backdrop-blur text-white border border-white/50 font-bold px-6 py-2.5 rounded hover:bg-white/20 transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">chat</span> Chat
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabs (Desktop) -->
                <div class="w-full bg-white dark:bg-[#161b22] border-b border-gray-200 dark:border-[#30363d] sticky top-0 z-30 shadow-sm flex justify-center">
                    <div class="max-w-[960px] w-full flex text-base font-semibold px-6">
                        <button class="px-6 py-4 text-[#00b3cc] dark:text-[#2f81f7] border-b-[3px] border-[#00b3cc] dark:border-[#2f81f7]">Halaman Utama</button>
                        <button class="px-6 py-4 text-gray-500 hover:text-[#00b3cc] dark:hover:text-[#2f81f7] border-b-[3px] border-transparent transition-colors">Semua Produk</button>
                        <button class="px-6 py-4 text-gray-500 hover:text-[#00b3cc] dark:hover:text-[#2f81f7] border-b-[3px] border-transparent transition-colors">Kategori Profil</button>
                    </div>
                </div>

                <!-- Desktop Content Area -->
                <div id="desktop-canvas" class="w-full max-w-[960px] p-6 space-y-6 pb-20 min-h-[300px]">
                    <template x-for="(comp, index) in activeComponents" :key="comp.id">
                        <div class="relative group cursor-move drag-handle">
                            <!-- Overlay Delete Button -->
                            <button @click="removeComponent(index)" class="absolute -right-3 -top-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center hidden group-hover:flex z-50 shadow-md hover:bg-red-600 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>

                            <!-- VOUCHER -->
                            <template x-if="comp.type === 'voucher'">
                                <div class="bg-white dark:bg-[#161b22] border-2 border-transparent hover:border-error transition-all rounded-lg p-6 flex gap-6 items-center shadow-sm">
                                    <div class="w-16 h-16 border-2 border-gray-300 border-dashed rounded flex items-center justify-center text-gray-300 shrink-0"><span class="material-symbols-outlined text-[32px]">local_activity</span></div>
                                    <div>
                                        <h4 class="font-bold text-gray-700 dark:text-gray-300 text-lg mb-1">Voucher Toko</h4>
                                        <p class="text-sm text-gray-400">Kamu belum membuat Voucher. Komponen ini akan disembunyikan otomatis.</p>
                                    </div>
                                </div>
                            </template>

                            <!-- FLASH SALE -->
                            <template x-if="comp.type === 'flash_sale'">
                                <div class="bg-white dark:bg-[#161b22] border-2 border-transparent hover:border-error transition-all rounded-lg p-6 flex gap-6 items-center shadow-sm">
                                    <div class="w-16 h-16 border-2 border-gray-300 border-dashed rounded flex items-center justify-center text-gray-300 shrink-0"><span class="material-symbols-outlined text-[32px]">bolt</span></div>
                                    <div>
                                        <h4 class="font-bold text-gray-700 dark:text-gray-300 text-lg mb-1">Flash Sale</h4>
                                        <p class="text-sm text-gray-400">Tidak ada produk Flash Sale yang aktif.</p>
                                    </div>
                                </div>
                            </template>

                            <!-- BANNER -->
                            <template x-if="comp.type === 'banner'">
                                <div class="bg-white dark:bg-[#161b22] border-2 border-transparent hover:border-error transition-all rounded-lg overflow-hidden shadow-sm h-80 flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                    <span class="text-gray-400 text-lg font-semibold">Banner Toko (Pilih Gambar)</span>
                                </div>
                            </template>
                            
                            <!-- SINGLE IMAGE -->
                            <template x-if="comp.type === 'single_image'">
                                <div class="bg-white dark:bg-[#161b22] border-2 border-transparent hover:border-error transition-all rounded-lg overflow-hidden shadow-sm h-[400px] flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                                    <span class="text-gray-400 text-lg font-semibold">Satu Foto (Pilih Gambar)</span>
                                </div>
                            </template>

                            <!-- TEKS -->
                            <template x-if="comp.type === 'text'">
                                <div class="bg-white dark:bg-[#161b22] border-2 border-transparent hover:border-error transition-all rounded-lg p-6 shadow-sm">
                                    <h3 class="text-xl font-bold mb-3 text-on-surface dark:text-white">Informasi</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed font-serif">Tambahkan teks deksripsi atau promosi toko Anda di sini. Anda dapat mengedit teks ini di panel pengaturan komponen.</p>
                                </div>
                            </template>
                            
                            <!-- PRODUK PILIHAN -->
                            <template x-if="comp.type === 'products'">
                                <div class="bg-white dark:bg-[#161b22] border-2 border-transparent hover:border-error transition-all rounded-lg p-6 shadow-sm">
                                    <h3 class="text-xl font-bold mb-6 text-on-surface dark:text-white text-center">Produk Pilihan</h3>
                                    <div class="grid grid-cols-4 gap-6">
                                        <div class="h-48 bg-gray-100 dark:bg-gray-800 rounded-lg"></div>
                                        <div class="h-48 bg-gray-100 dark:bg-gray-800 rounded-lg"></div>
                                        <div class="h-48 bg-gray-100 dark:bg-gray-800 rounded-lg"></div>
                                        <div class="h-48 bg-gray-100 dark:bg-gray-800 rounded-lg"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    
                    <div x-show="activeComponents.length === 0" class="text-center py-16 text-gray-400 text-lg border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                        Tarik komponen dari sidebar ke sini
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
</div>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('appearanceEditor', () => ({
            tab: 'komponen',
            device: 'mobile',
            activeComponents: @json($store->appearance_data ?? []),
            isSaving: false,
            sortableMobile: null,
            sortableDesktop: null,
            
            init() {
                if (!Array.isArray(this.activeComponents) || this.activeComponents.length === 0) {
                    // Default components if empty
                    this.activeComponents = [
                        { id: this.generateId(), type: 'voucher' },
                        { id: this.generateId(), type: 'banner' },
                        { id: this.generateId(), type: 'text' },
                        { id: this.generateId(), type: 'flash_sale' }
                    ];
                }
                
                this.$nextTick(() => {
                    this.initSortable('mobile-canvas');
                    this.initSortable('desktop-canvas');
                });
                
                // Re-init sortable on device change just in case
                this.$watch('device', () => {
                    this.$nextTick(() => {
                        this.initSortable('mobile-canvas');
                        this.initSortable('desktop-canvas');
                    });
                });
            },
            
            generateId() {
                return Math.random().toString(36).substr(2, 9);
            },
            
            addComponent(type) {
                this.activeComponents.push({ id: this.generateId(), type: type });
                // scroll to bottom
                this.$nextTick(() => {
                    const canvas = this.device === 'mobile' ? document.getElementById('mobile-canvas') : document.getElementById('desktop-canvas');
                    if(canvas) canvas.scrollIntoView({ behavior: 'smooth', block: 'end' });
                });
            },
            
            removeComponent(index) {
                this.activeComponents.splice(index, 1);
            },
            
            initSortable(refId) {
                const el = document.getElementById(refId);
                if (!el) return;
                
                // destroy previous instance if exists to prevent duplicates
                if(refId === 'mobile-canvas' && this.sortableMobile) this.sortableMobile.destroy();
                if(refId === 'desktop-canvas' && this.sortableDesktop) this.sortableDesktop.destroy();
                
                const sortable = new Sortable(el, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'opacity-50',
                    onEnd: (evt) => {
                        if (evt.oldIndex !== evt.newIndex) {
                            const item = this.activeComponents.splice(evt.oldIndex, 1)[0];
                            this.activeComponents.splice(evt.newIndex, 0, item);
                        }
                    }
                });
                
                if(refId === 'mobile-canvas') this.sortableMobile = sortable;
                if(refId === 'desktop-canvas') this.sortableDesktop = sortable;
            },
            
            save() {
                this.isSaving = true;
                fetch('{{ url('/tenant/appearance') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ components: this.activeComponents })
                })
                .then(res => res.json())
                .then(data => {
                    this.isSaving = false;
                    if(data.success) {
                        alert('Dekorasi toko berhasil disimpan!');
                    } else {
                        alert('Gagal menyimpan dekorasi: ' + (data.message || 'Error'));
                    }
                })
                .catch(err => {
                    this.isSaving = false;
                    alert('Terjadi kesalahan koneksi saat menyimpan.');
                    console.error(err);
                });
            }
        }));
    });
</script>
@endsection
