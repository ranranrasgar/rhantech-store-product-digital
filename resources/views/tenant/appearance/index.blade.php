@extends('layouts.tenant')

@section('title', 'Dekorasi Etalase Toko')

@section('content')
<div class="flex flex-col h-full bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200" x-data="appearanceEditor()">
    
    <!-- Top Action Header -->
    <div class="bg-white dark:bg-[#111726] border-b border-slate-200/80 dark:border-[#222f49] px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm z-20 shrink-0">
        <div>
            <h1 class="text-xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                Dekorasi Etalase Toko
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Kustomisasi susunan visual dan tata letak halaman toko digital Anda.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($store && $store->slug)
            <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm">
                <span class="material-symbols-outlined text-[16px]">visibility</span> Preview Web
            </a>
            @endif
            <button @click="save()" :disabled="isSaving" class="px-5 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all flex items-center gap-1.5 disabled:opacity-60 cursor-pointer">
                <span class="material-symbols-outlined text-[16px]" x-text="isSaving ? 'hourglass_empty' : 'save'">save</span>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'">Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <!-- Workspace Container (Sidebar + Interactive Canvas) -->
    <div class="flex-1 flex overflow-hidden h-[calc(100vh-130px)]">
        
        <!-- LEFT SIDEBAR: WIDGET COMPONENT PALETTE -->
        <div class="w-[300px] border-r border-slate-200/80 dark:border-[#222f49] bg-white dark:bg-[#111726] flex flex-col h-full z-10 shrink-0 shadow-sm overflow-hidden">
            
            <div class="p-4 border-b border-slate-100 dark:border-[#222f49]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Pilihan Blok Widget
                </h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Klik blok di bawah untuk menambahkannya ke kanvas:</p>
            </div>

            <!-- Components List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-5 custom-scrollbar">
                
                <!-- Group 1: Media & Konten -->
                <div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white mb-2.5 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-sky-500">photo_library</span> Media & Tampilan
                    </h3>
                    <div class="grid grid-cols-2 gap-2.5">
                        <!-- Banner Carousel -->
                        <div @click="addComponent('banner')" class="p-3 rounded-xl border border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 hover:bg-sky-50/50 dark:hover:bg-sky-950/20 cursor-pointer transition-all text-center group">
                            <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">view_carousel</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Banner Hero</span>
                            <span class="text-[10px] text-slate-400">Slide Gambar</span>
                        </div>

                        <!-- Single Highlight Image -->
                        <div @click="addComponent('single_image')" class="p-3 rounded-xl border border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 hover:bg-sky-50/50 dark:hover:bg-sky-950/20 cursor-pointer transition-all text-center group">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">image</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Banner Tunggal</span>
                            <span class="text-[10px] text-slate-400">Gambar Penuh</span>
                        </div>

                        <!-- Text Block -->
                        <div @click="addComponent('text')" class="p-3 rounded-xl border border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 hover:bg-sky-50/50 dark:hover:bg-sky-950/20 cursor-pointer transition-all text-center group col-span-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">format_quote</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Teks / Pengumuman</span>
                            <span class="text-[10px] text-slate-400">Blok deskripsi & informasi toko</span>
                        </div>
                    </div>
                </div>

                <!-- Group 2: Produk & Penjualan -->
                <div>
                    <h3 class="text-xs font-bold text-slate-900 dark:text-white mb-2.5 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-amber-500">storefront</span> Produk & Promosi
                    </h3>
                    <div class="grid grid-cols-2 gap-2.5">
                        <!-- Featured Products -->
                        <div @click="addComponent('products')" class="p-3 rounded-xl border border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 hover:bg-sky-50/50 dark:hover:bg-sky-950/20 cursor-pointer transition-all text-center group">
                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">grid_view</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Produk Pilihan</span>
                            <span class="text-[10px] text-slate-400">Grid Katalog</span>
                        </div>

                        <!-- Flash Sale -->
                        <div @click="addComponent('flash_sale')" class="p-3 rounded-xl border border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 hover:bg-sky-50/50 dark:hover:bg-sky-950/20 cursor-pointer transition-all text-center group">
                            <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">bolt</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Flash Sale</span>
                            <span class="text-[10px] text-slate-400">Promo Terbatas</span>
                        </div>

                        <!-- Voucher -->
                        <div @click="addComponent('voucher')" class="p-3 rounded-xl border border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 hover:bg-sky-50/50 dark:hover:bg-sky-950/20 cursor-pointer transition-all text-center group col-span-2">
                            <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-500 flex items-center justify-center mx-auto mb-2 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                            </div>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">Kupon Voucher</span>
                            <span class="text-[10px] text-slate-400">Daftar kode kupon potongan belanja</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- CENTER CANVAS: LIVE INTERACTIVE STORE BUILDER -->
        <div class="flex-1 bg-slate-100/60 dark:bg-[#070a12] flex flex-col items-center py-6 overflow-auto relative custom-scrollbar">
            
            <!-- Viewport Switcher (Desktop / Mobile) -->
            <div class="flex items-center gap-1.5 mb-6 bg-white dark:bg-[#111726] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#222f49] shrink-0 shadow-sm">
                <button @click="device = 'desktop'" :class="device === 'desktop' ? 'bg-sky-500 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">desktop_windows</span> Desktop
                </button>
                <button @click="device = 'mobile'" :class="device === 'mobile' ? 'bg-sky-500 text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-4 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">smartphone</span> Mobile
                </button>
            </div>

            <!-- DESKTOP CANVAS VIEWPORT -->
            <div x-show="device === 'desktop'" class="w-[960px] bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] shadow-2xl rounded-2xl overflow-hidden flex flex-col relative min-h-[600px] shrink-0 mb-12">
                
                <!-- Browser Bar -->
                <div class="h-9 bg-slate-100 dark:bg-[#161f33] border-b border-slate-200 dark:border-[#222f49] flex items-center px-4 gap-3 shrink-0">
                    <div class="flex gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                    </div>
                    <div class="flex-1 flex justify-center">
                        <div class="bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] text-[11px] px-4 py-1 rounded-lg text-slate-500 w-[50%] flex items-center justify-center gap-1.5 font-mono truncate">
                            <span class="material-symbols-outlined text-[13px] text-emerald-500">lock</span>
                            <span>rhantech.com/toko/{{ $store->slug ?? 'toko-anda' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Desktop Store Header Banner -->
                <div class="w-full h-[220px] bg-slate-900 text-white relative overflow-hidden flex items-end p-6 border-b border-slate-200 dark:border-[#222f49] group/header">
                    <div class="absolute inset-0 bg-cover bg-center opacity-40 mix-blend-overlay transition-all" :style="headerBanner ? `background-image: url('${headerBanner}')` : ''"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/80 to-transparent pointer-events-none"></div>
                    
                    <!-- Upload Header Banner Button -->
                    <div class="absolute top-4 right-4 z-20 opacity-0 group-hover/header:opacity-100 transition-opacity">
                        <label class="cursor-pointer bg-white/20 hover:bg-white/30 backdrop-blur-md text-white px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 border border-white/30 transition-colors shadow-lg">
                            <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                            Ganti Background
                            <input type="file" class="hidden" accept="image/*" @change="uploadHeaderBanner($event)">
                        </label>
                    </div>

                    <div class="relative z-10 flex items-center justify-between w-full">
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-1 overflow-hidden shrink-0 shadow-lg">
                                @if($store && $store->logo)
                                    <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-xl">
                                @else
                                    <div class="w-full h-full bg-sky-500 rounded-xl flex items-center justify-center font-bold text-xl text-white">
                                        {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-2xl font-black text-white drop-shadow-sm">{{ $store->name ?? 'Toko Anda' }}</h2>
                                <p class="text-xs text-slate-300 mt-1 max-w-md line-clamp-1">{{ $store->description ?: 'Platform penyedia produk digital terpercaya.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Desktop Droppable Canvas Area -->
                <div id="desktop-canvas" class="p-6 space-y-4 min-h-[300px]">
                    <template x-for="(comp, index) in activeComponents" :key="comp.id">
                        <div class="relative group cursor-move drag-handle transition-all">
                            <!-- Action Buttons -->
                            <div class="absolute -right-2 -top-2 hidden group-hover:flex items-center gap-1 z-30">
                                <button @click="openSettings(index)" class="bg-sky-500 hover:bg-sky-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow-lg transition-transform hover:scale-110">
                                    <span class="material-symbols-outlined text-[15px]">settings</span>
                                </button>
                                <button @click="removeComponent(index)" class="bg-rose-500 hover:bg-rose-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow-lg transition-transform hover:scale-110">
                                    <span class="material-symbols-outlined text-[15px]">close</span>
                                </button>
                            </div>

                            <!-- BANNER HERO -->
                            <template x-if="comp.type === 'banner'">
                                <div class="relative bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl flex flex-col items-center justify-center text-center shadow-xs transition-colors h-48 overflow-hidden group/banner">
                                    <template x-if="comp.data?.images && comp.data.images.length > 0 && comp.data.images[0].image_url">
                                        <div class="absolute inset-0 w-full h-full">
                                            <img :src="comp.data.images[0].image_url" class="w-full h-full object-cover opacity-90 group-hover/banner:opacity-40 transition-opacity">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 text-white opacity-0 group-hover/banner:opacity-100 transition-opacity">
                                                <span class="material-symbols-outlined text-3xl mb-1">view_carousel</span>
                                                <span class="text-sm font-bold" x-text="comp.data.images.length + ' Slide Gambar'"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!comp.data?.images || comp.data.images.length === 0 || !comp.data.images[0].image_url">
                                        <div class="p-8 flex flex-col items-center justify-center w-full h-full">
                                            <span class="material-symbols-outlined text-3xl text-sky-500 mb-1">view_carousel</span>
                                            <span class="text-sm font-bold text-slate-800 dark:text-white">Blok Banner Slide Utama</span>
                                            <span class="text-xs text-slate-400 mt-0.5">Menampilkan gambar sorotan campaign toko</span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- SINGLE IMAGE -->
                            <template x-if="comp.type === 'single_image'">
                                <div class="relative bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl flex flex-col items-center justify-center text-center shadow-xs transition-colors h-56 overflow-hidden group/single">
                                    <template x-if="comp.data?.image_url">
                                        <div class="absolute inset-0 w-full h-full">
                                            <img :src="comp.data.image_url" class="w-full h-full object-cover opacity-90 group-hover/single:opacity-40 transition-opacity">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 text-white opacity-0 group-hover/single:opacity-100 transition-opacity">
                                                <span class="material-symbols-outlined text-3xl mb-1">image</span>
                                                <span class="text-sm font-bold">Preview Banner</span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!comp.data?.image_url">
                                        <div class="p-8 flex flex-col items-center justify-center w-full h-full">
                                            <span class="material-symbols-outlined text-3xl text-emerald-500 mb-1">image</span>
                                            <span class="text-sm font-bold text-slate-800 dark:text-white">Blok Banner Gambar Penuh</span>
                                            <span class="text-xs text-slate-400 mt-0.5">Gambar promo spesial etalase toko</span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- VOUCHER -->
                            <template x-if="comp.type === 'voucher'">
                                <div class="bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl p-6 flex items-center gap-4 shadow-xs transition-colors">
                                    <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-2xl">confirmation_number</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Blok Kupon Voucher Toko</h4>
                                        <p class="text-xs text-slate-400 mt-0.5">Menampilkan daftar voucher diskon aktif yang siap diklaim pembeli.</p>
                                    </div>
                                </div>
                            </template>

                            <!-- FLASH SALE -->
                            <template x-if="comp.type === 'flash_sale'">
                                <div class="bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl p-6 flex items-center gap-4 shadow-xs transition-colors">
                                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-2xl">bolt</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Blok Flash Sale Terbatas</h4>
                                        <p class="text-xs text-slate-400 mt-0.5">Menampilkan produk promo diskon kilat dengan countdown timer.</p>
                                    </div>
                                </div>
                            </template>

                            <!-- PRODUCTS -->
                            <template x-if="comp.type === 'products'">
                                <div class="bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl p-6 shadow-xs transition-colors">
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-3">Grid Produk Unggulan</h4>
                                    <div class="grid grid-cols-4 gap-3">
                                        <div class="h-28 bg-slate-200/60 dark:bg-slate-800 rounded-xl"></div>
                                        <div class="h-28 bg-slate-200/60 dark:bg-slate-800 rounded-xl"></div>
                                        <div class="h-28 bg-slate-200/60 dark:bg-slate-800 rounded-xl"></div>
                                        <div class="h-28 bg-slate-200/60 dark:bg-slate-800 rounded-xl"></div>
                                    </div>
                                </div>
                            </template>

                            <!-- TEXT -->
                            <template x-if="comp.type === 'text'">
                                <div class="bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl p-6 shadow-xs transition-colors">
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Blok Informasi / Pengumuman</h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                        Selamat datang di toko digital kami! Temukan beragam source code, template website, dan aplikasi siap pakai terbaik untuk kebutuhan proyek Anda.
                                    </p>
                                </div>
                            </template>
                        </div>
                    </template>

                    <div x-show="activeComponents.length === 0" class="text-center py-16 text-slate-400 text-sm border-2 border-dashed border-slate-200 dark:border-[#222f49] rounded-2xl">
                        Kanvas masih kosong. Klik blok widget di sidebar kiri untuk menambahkan.
                    </div>
                </div>
            </div>

            <!-- MOBILE CANVAS VIEWPORT -->
            <div x-show="device === 'mobile'" style="display: none;" class="w-[375px] bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] shadow-2xl rounded-[2.5rem] overflow-hidden flex flex-col relative min-h-[750px] shrink-0 mb-12 outline outline-8 outline-slate-200/60 dark:outline-slate-800">
                
                <!-- Mobile Status Bar -->
                <div class="h-7 bg-slate-900 text-white text-[10px] flex justify-between items-center px-5 shrink-0">
                    <span>09:41</span>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[12px]">signal_cellular_alt</span>
                        <span class="material-symbols-outlined text-[12px]">wifi</span>
                        <span class="material-symbols-outlined text-[12px]">battery_full</span>
                    </div>
                </div>

                <!-- Mobile Header -->
                <div class="h-32 bg-slate-900 text-white p-4 flex items-end relative overflow-hidden group/mheader">
                    <div class="absolute inset-0 bg-cover bg-center opacity-40 mix-blend-overlay transition-all" :style="headerBanner ? `background-image: url('${headerBanner}')` : ''"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent pointer-events-none"></div>
                    
                    <!-- Mobile Upload Header Banner Button -->
                    <div class="absolute top-3 right-3 z-20 opacity-0 group-hover/mheader:opacity-100 transition-opacity">
                        <label class="cursor-pointer bg-white/20 hover:bg-white/30 backdrop-blur-md text-white p-1.5 rounded-lg flex items-center justify-center border border-white/30 transition-colors shadow-lg" title="Ganti Background">
                            <span class="material-symbols-outlined text-[16px]">photo_camera</span>
                            <input type="file" class="hidden" accept="image/*" @change="uploadHeaderBanner($event)">
                        </label>
                    </div>

                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 p-0.5 overflow-hidden shrink-0">
                            @if($store && $store->logo)
                                <img src="{{ asset('storage/' . $store->logo) }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <div class="w-full h-full bg-sky-500 rounded-lg flex items-center justify-center font-bold text-sm text-white">
                                    {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-white leading-tight">{{ $store->name ?? 'Toko Anda' }}</h3>
                            <p class="text-[10px] text-slate-300">Toko Resmi</p>
                        </div>
                    </div>
                </div>

                <!-- Mobile Canvas Droppable Area -->
                <div id="mobile-canvas" class="p-3 space-y-3 pb-16 min-h-[300px] overflow-y-auto">
                    <template x-for="(comp, index) in activeComponents" :key="comp.id">
                        <div class="relative group cursor-move drag-handle">
                            <!-- Action Buttons -->
                            <div class="absolute -right-2 -top-2 hidden group-hover:flex items-center gap-1 z-30">
                                <button @click="openSettings(index)" class="bg-sky-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md">
                                    <span class="material-symbols-outlined text-[14px]">settings</span>
                                </button>
                                <button @click="removeComponent(index)" class="bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </button>
                            </div>

                            <template x-if="comp.type === 'banner'">
                                <div class="bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl p-4 text-center h-28 flex flex-col items-center justify-center">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">Banner Slide</span>
                                </div>
                            </template>

                            <template x-if="comp.type === 'single_image'">
                                <div class="bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl p-4 text-center h-36 flex flex-col items-center justify-center">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">Banner Gambar</span>
                                </div>
                            </template>

                            <template x-if="comp.type === 'voucher'">
                                <div class="bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl p-3 flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-rose-500 text-[18px]">confirmation_number</span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">Kupon Voucher</span>
                                </div>
                            </template>

                            <template x-if="comp.type === 'flash_sale'">
                                <div class="bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl p-3 flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-amber-500 text-[18px]">bolt</span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">Flash Sale</span>
                                </div>
                            </template>

                            <template x-if="comp.type === 'products'">
                                <div class="bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl p-3">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white mb-2 block">Produk Pilihan</span>
                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="h-16 bg-slate-200 dark:bg-slate-800 rounded-lg"></div>
                                        <div class="h-16 bg-slate-200 dark:bg-slate-800 rounded-lg"></div>
                                    </div>
                                </div>
                            </template>

                            <template x-if="comp.type === 'text'">
                                <div class="bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl p-3">
                                    <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2">Deskripsi etalase toko digital...</p>
                                </div>
                            </template>
                        </div>
                    </template>

                    <div x-show="activeComponents.length === 0" class="text-center py-10 text-slate-400 text-xs border border-dashed border-slate-200 dark:border-[#222f49] rounded-xl">
                        Belum ada blok widget.
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Cropper Modal -->
    <div x-show="isCropperModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div x-show="isCropperModalOpen" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm" @click="closeCropper()"></div>
        
        <div x-show="isCropperModalOpen" x-transition.scale.95 class="bg-white dark:bg-[#0d1117] rounded-3xl shadow-2xl w-full max-w-4xl relative z-10 overflow-hidden flex flex-col border border-slate-200 dark:border-[#222f49]">
            <div class="px-6 py-4 border-b border-slate-100 dark:border-[#222f49] flex justify-between items-center bg-slate-50/50 dark:bg-[#111726]">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-sky-500">crop</span>
                    Potong Gambar (<span x-text="cropperTarget?.isHeader ? 'Banner Kepala Toko' : (cropperAspectRatio === 0 ? 'Bebas' : 'Banner Slide')"></span>)
                </h3>
                <button @click="closeCropper()" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-6 bg-slate-100 dark:bg-[#161f33] flex justify-center items-center h-[50vh] overflow-hidden">
                <div class="w-full h-full flex items-center justify-center">
                    <img id="cropper-image" class="max-w-full max-h-full block" src="">
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 dark:border-[#222f49] flex justify-between items-center bg-slate-50/50 dark:bg-[#111726]">
                <div class="text-xs text-slate-500 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">info</span>
                    Geser atau perbesar gambar agar sesuai dengan area kotak.
                </div>
                <div class="flex gap-3">
                    <button @click="closeCropper()" type="button" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-[#222f49] transition-colors">Batal</button>
                    <button @click="applyCrop()" type="button" class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-sky-500/30 transition-colors flex items-center gap-2" :class="{'opacity-50 cursor-not-allowed': isCropping}" :disabled="isCropping">
                        <span x-show="!isCropping" class="material-symbols-outlined text-[18px]">check</span>
                        <span x-show="isCropping" class="material-symbols-outlined text-[18px] animate-spin">refresh</span>
                        <span x-text="isCropping ? 'Memproses...' : 'Potong & Simpan'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div x-show="isSettingsModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="isSettingsModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeSettings()"></div>
        
        <!-- Modal Content -->
        <div x-show="isSettingsModalOpen" x-transition.scale.95 class="bg-white dark:bg-[#111726] rounded-2xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-[#222f49] flex items-center justify-between shrink-0">
                <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-sky-500 text-[20px]">settings</span>
                    Pengaturan Blok
                </h3>
                <button @click="closeSettings()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-1 custom-scrollbar" x-if="editingData">
                
                <!-- Text Block Settings -->
                <div x-show="editingData && editingData.type === 'text'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Teks Pengumuman</label>
                        <textarea x-model="editingData?.data?.text" rows="4" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500" placeholder="Ketik pengumuman atau deskripsi di sini..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Posisi Teks</label>
                            <select x-model="editingData?.data?.align" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500">
                                <option value="left">Kiri</option>
                                <option value="center">Tengah</option>
                                <option value="right">Kanan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Ukuran Huruf</label>
                            <select x-model="editingData?.data?.size" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500">
                                <option value="sm">Kecil</option>
                                <option value="md">Sedang</option>
                                <option value="lg">Besar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Single Image Settings -->
                <div x-show="editingData && editingData.type === 'single_image'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Gambar Banner</label>
                        <div class="flex gap-2">
                            <input type="text" x-model="editingData?.data?.image_url" class="flex-1 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500" placeholder="https://contoh.com/gambar.jpg">
                            <label class="cursor-pointer bg-sky-100 hover:bg-sky-200 text-sky-600 px-4 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-[18px]">upload</span>
                                <input type="file" class="hidden" accept="image/*" @change="openCropper($event, editingData.data, 'image_url', false, 0)">
                            </label>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">Masukkan URL gambar atau upload dari perangkat Anda (Maks 2MB).</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Link Tujuan (Opsional)</label>
                        <input type="text" x-model="editingData?.data?.link" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500" placeholder="https://...">
                    </div>
                </div>
                
                <!-- Products Settings -->
                <div x-show="editingData && editingData.type === 'products'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pilih Mode Produk</label>
                        <select x-model="editingData?.data?.type" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500">
                            <option value="latest">Otomatis (Produk Terbaru)</option>
                            <option value="bestseller">Otomatis (Terlaris)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jumlah Maksimal Ditampilkan</label>
                        <select x-model="editingData?.data?.count" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500">
                            <option value="4">4 Produk</option>
                            <option value="8">8 Produk</option>
                            <option value="12">12 Produk</option>
                        </select>
                    </div>
                </div>

                <!-- Flash Sale Settings -->
                <div x-show="editingData && editingData.type === 'flash_sale'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Batas Waktu Flash Sale</label>
                        <input type="datetime-local" x-model="editingData?.data?.end_date" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500">
                    </div>
                </div>
                
                <!-- Banner Settings -->
                <div x-show="editingData && editingData.type === 'banner'" class="space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Daftar Gambar Slide</label>
                        <button type="button" @click="if(!editingData.data.images) editingData.data.images = []; editingData.data.images.push({image_url: '', link: ''})" class="text-[10px] bg-sky-100 text-sky-600 hover:bg-sky-200 px-2 py-1 rounded font-bold flex items-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[12px]">add</span> Tambah Slide
                        </button>
                    </div>
                    
                    <template x-if="!editingData?.data?.images || editingData.data.images.length === 0">
                        <div class="p-4 bg-slate-50 dark:bg-[#0d1117] rounded-xl border border-dashed border-slate-200 dark:border-[#222f49] text-center text-xs text-slate-400">Belum ada slide gambar.</div>
                    </template>
                    
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(img, imgIdx) in editingData?.data?.images" :key="imgIdx">
                            <div class="p-3 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl relative group">
                                <button type="button" @click="editingData.data.images.splice(imgIdx, 1)" class="absolute top-2 right-2 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900/30 p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                </button>
                                <div class="space-y-3 pr-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-1">Gambar Slide</label>
                                        <div class="flex gap-2">
                                            <input type="text" x-model="img.image_url" class="flex-1 bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 focus:border-sky-500 focus:outline-none" placeholder="https://...">
                                            <label class="cursor-pointer bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 px-2 rounded-lg flex items-center justify-center transition-colors" title="Upload Gambar">
                                                <span class="material-symbols-outlined text-[14px]">upload</span>
                                                <input type="file" class="hidden" accept="image/*" @change="openCropper($event, img, 'image_url', false, 2.5/1)">
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-1">Link Tujuan (Opsional)</label>
                                        <input type="text" x-model="img.link" class="w-full bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 focus:border-sky-500 focus:outline-none" placeholder="https://...">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Voucher Settings -->
                <div x-show="editingData && editingData.type === 'voucher'" class="space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Daftar Kupon</label>
                        <button type="button" @click="if(!editingData.data.vouchers) editingData.data.vouchers = []; editingData.data.vouchers.push({title: 'Diskon Baru', subtitle: 'Min. Belanja Rp 0', theme: 'rose'})" class="text-[10px] bg-sky-100 text-sky-600 hover:bg-sky-200 px-2 py-1 rounded font-bold flex items-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[12px]">add</span> Tambah Kupon
                        </button>
                    </div>
                    
                    <template x-if="!editingData?.data?.vouchers || editingData.data.vouchers.length === 0">
                        <div class="p-4 bg-slate-50 dark:bg-[#0d1117] rounded-xl border border-dashed border-slate-200 dark:border-[#222f49] text-center text-xs text-slate-400">Belum ada kupon yang ditambahkan.</div>
                    </template>
                    
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(v, vIdx) in editingData?.data?.vouchers" :key="vIdx">
                            <div class="p-3 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl relative group">
                                <button type="button" @click="editingData.data.vouchers.splice(vIdx, 1)" class="absolute top-2 right-2 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900/30 p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="material-symbols-outlined text-[14px]">delete</span>
                                </button>
                                <div class="space-y-3 pr-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-1">Judul Diskon</label>
                                        <input type="text" x-model="v.title" class="w-full bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 focus:border-sky-500 focus:outline-none" placeholder="Misal: Diskon Spesial 50%">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-1">Syarat / Subtitle</label>
                                        <input type="text" x-model="v.subtitle" class="w-full bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 focus:border-sky-500 focus:outline-none" placeholder="Misal: Min. Belanja Rp 100.000">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-1">Tema Warna</label>
                                        <select x-model="v.theme" class="w-full bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 focus:border-sky-500 focus:outline-none">
                                            <option value="rose">Merah Muda (Rose)</option>
                                            <option value="emerald">Hijau (Emerald)</option>
                                            <option value="amber">Kuning (Amber)</option>
                                            <option value="sky">Biru (Sky)</option>
                                            <option value="violet">Ungu (Violet)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#161f33] flex items-center justify-end gap-3 shrink-0 rounded-b-2xl">
                <button @click="closeSettings()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button @click="saveSettings()" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold shadow-lg shadow-sky-500/25 transition-all">Simpan Pengaturan</button>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('appearanceEditor', () => ({
            device: 'desktop',
            activeComponents: {!! json_encode($store->appearance_data ?? []) !!},
            headerBanner: '{{ $store->banner }}' || '',
            isSaving: false,
            draggedItem: null,
            sortableMobile: null,
            sortableDesktop: null,
            
            // Cropper state
            isCropperModalOpen: false,
            cropperInstance: null,
            cropperTarget: null,
            cropperAspectRatio: 3 / 1,
            isCropping: false,
            
            isSettingsModalOpen: false,
            editingIndex: -1,
            editingData: null,
            
            init() {
                if (!Array.isArray(this.activeComponents) || this.activeComponents.length === 0) {
                    this.activeComponents = [
                        { id: this.generateId(), type: 'banner', data: this.getDefaultData('banner') },
                        { id: this.generateId(), type: 'voucher', data: this.getDefaultData('voucher') },
                        { id: this.generateId(), type: 'products', data: this.getDefaultData('products') },
                        { id: this.generateId(), type: 'text', data: this.getDefaultData('text') }
                    ];
                } else {
                    // Ensure all existing components have data objects
                    this.activeComponents.forEach(comp => {
                        if (!comp.data) comp.data = this.getDefaultData(comp.type);
                    });
                }
                
                this.$nextTick(() => {
                    this.initSortable('mobile-canvas');
                    this.initSortable('desktop-canvas');
                });
                
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
            
            getDefaultData(type) {
                switch(type) {
                    case 'text': return { text: '', align: 'center', size: 'md' };
                    case 'banner': return { images: [] };
                    case 'single_image': return { image_url: '', link: '' };
                    case 'products': return { type: 'latest', count: 8 };
                    case 'flash_sale': return { end_date: '', discount: 10 };
                    case 'voucher': return {};
                    default: return {};
                }
            },
            
            addComponent(type) {
                this.activeComponents.push({ id: this.generateId(), type: type, data: this.getDefaultData(type) });
                this.$nextTick(() => {
                    const canvas = this.device === 'mobile' ? document.getElementById('mobile-canvas') : document.getElementById('desktop-canvas');
                    if(canvas) canvas.scrollIntoView({ behavior: 'smooth', block: 'end' });
                });
            },
            
            removeComponent(index) {
                this.activeComponents.splice(index, 1);
            },
            
            openSettings(index) {
                this.editingIndex = index;
                const comp = this.activeComponents[index];
                this.editingData = JSON.parse(JSON.stringify(comp)); // Deep clone
                if (!this.editingData.data) {
                    this.editingData.data = this.getDefaultData(comp.type);
                }
                this.isSettingsModalOpen = true;
            },
            
            saveSettings() {
                if (this.editingIndex >= 0 && this.editingData) {
                    this.activeComponents[this.editingIndex] = JSON.parse(JSON.stringify(this.editingData));
                }
                this.closeSettings();
            },
            
            closeSettings() {
                this.isSettingsModalOpen = false;
                this.editingIndex = -1;
                this.editingData = null;
            },
            
            initSortable(refId) {
                const el = document.getElementById(refId);
                if (!el) return;
                
                if(refId === 'mobile-canvas' && this.sortableMobile) this.sortableMobile.destroy();
                if(refId === 'desktop-canvas' && this.sortableDesktop) this.sortableDesktop.destroy();
                
                const sortable = new Sortable(el, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'opacity-40',
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
                fetch('{{ route('tenant.appearance.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        components: this.activeComponents,
                        header_banner: this.headerBanner 
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.isSaving = false;
                    if(data.success) {
                        alert('Dekorasi etalase toko berhasil disimpan!');
                    } else {
                        alert('Gagal menyimpan dekorasi: ' + (data.message || 'Error'));
                    }
                })
                .catch(err => {
                    this.isSaving = false;
                    alert('Terjadi kesalahan koneksi saat menyimpan.');
                    console.error(err);
                });
            },
            
            uploadImage(event, targetObject, propertyName) {
                // Not used directly anymore, replaced by openCropper
            },

            uploadHeaderBanner(event) {
                this.openCropper(event, null, null, true, 3/1);
            },
            
            openCropper(event, targetObject, propertyName, isHeader = false, aspectRatio = 0) {
                const file = event.target.files[0];
                if (!file) return;
                
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB!');
                    return;
                }

                this.cropperAspectRatio = aspectRatio;
                this.cropperTarget = { object: targetObject, property: propertyName, isHeader: isHeader };
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    const imgElement = document.getElementById('cropper-image');
                    imgElement.src = e.target.result;
                    this.isCropperModalOpen = true;
                    
                    this.$nextTick(() => {
                        if (this.cropperInstance) {
                            this.cropperInstance.destroy();
                        }
                        this.cropperInstance = new Cropper(imgElement, {
                            aspectRatio: this.cropperAspectRatio > 0 ? this.cropperAspectRatio : NaN,
                            viewMode: 1,
                            dragMode: 'move',
                            autoCropArea: 1,
                            restore: false,
                            guides: true,
                            center: true,
                            highlight: false,
                            cropBoxMovable: true,
                            cropBoxResizable: true,
                            toggleDragModeOnDblclick: false,
                        });
                    });
                };
                reader.readAsDataURL(file);
                event.target.value = '';
            },
            
            closeCropper() {
                this.isCropperModalOpen = false;
                if (this.cropperInstance) {
                    this.cropperInstance.destroy();
                    this.cropperInstance = null;
                }
                this.cropperTarget = null;
                this.isCropping = false;
            },
            
            applyCrop() {
                if (!this.cropperInstance || !this.cropperTarget) return;
                
                this.isCropping = true;
                const canvas = this.cropperInstance.getCroppedCanvas({
                    maxWidth: 1600,
                    maxHeight: 1600,
                });
                
                canvas.toBlob((blob) => {
                    if (!blob) {
                        alert('Gagal memotong gambar.');
                        this.isCropping = false;
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('image', blob, 'cropped.jpg');
                    
                    const originalUrl = this.cropperTarget.isHeader ? this.headerBanner : this.cropperTarget.object[this.cropperTarget.property];
                    
                    if (this.cropperTarget.isHeader) {
                        this.headerBanner = 'Mengunggah...';
                    } else {
                        this.cropperTarget.object[this.cropperTarget.property] = 'Mengunggah...';
                    }
                    
                    const uploadTarget = this.cropperTarget; 
                    this.closeCropper(); 
                    
                    fetch('{{ route('tenant.appearance.upload') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (uploadTarget.isHeader) {
                                this.headerBanner = data.url;
                            } else {
                                uploadTarget.object[uploadTarget.property] = data.url;
                            }
                        } else {
                            if (uploadTarget.isHeader) {
                                this.headerBanner = originalUrl;
                            } else {
                                uploadTarget.object[uploadTarget.property] = originalUrl;
                            }
                            alert(data.message || 'Gagal mengunggah gambar');
                        }
                    })
                    .catch(err => {
                        if (uploadTarget.isHeader) {
                            this.headerBanner = originalUrl;
                        } else {
                            uploadTarget.object[uploadTarget.property] = originalUrl;
                        }
                        alert('Terjadi kesalahan koneksi saat mengunggah.');
                        console.error(err);
                    });
                }, 'image/jpeg', 0.85);
            }
        }));
    });
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
@endsection
