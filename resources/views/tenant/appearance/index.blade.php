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
                <div class="w-full h-[220px] bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white relative overflow-hidden flex items-end p-6 border-b border-slate-200 dark:border-[#222f49]">
                    <div class="absolute inset-0 bg-radial from-sky-500/10 to-transparent pointer-events-none"></div>
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
                            <!-- Remove Button -->
                            <button @click="removeComponent(index)" class="absolute -right-2 -top-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-7 h-7 flex items-center justify-center hidden group-hover:flex z-30 shadow-lg transition-transform hover:scale-110">
                                <span class="material-symbols-outlined text-[15px]">close</span>
                            </button>

                            <!-- BANNER HERO -->
                            <template x-if="comp.type === 'banner'">
                                <div class="bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl p-8 flex flex-col items-center justify-center text-center shadow-xs transition-colors h-48">
                                    <span class="material-symbols-outlined text-3xl text-sky-500 mb-1">view_carousel</span>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white">Blok Banner Slide Utama</span>
                                    <span class="text-xs text-slate-400 mt-0.5">Menampilkan gambar sorotan campaign toko</span>
                                </div>
                            </template>

                            <!-- SINGLE IMAGE -->
                            <template x-if="comp.type === 'single_image'">
                                <div class="bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl p-8 flex flex-col items-center justify-center text-center shadow-xs transition-colors h-56">
                                    <span class="material-symbols-outlined text-3xl text-emerald-500 mb-1">image</span>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white">Blok Banner Gambar Penuh</span>
                                    <span class="text-xs text-slate-400 mt-0.5">Gambar promo spesial etalase toko</span>
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
                <div class="h-32 bg-slate-900 text-white p-4 flex items-end relative overflow-hidden">
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
                            <!-- Remove Button -->
                            <button @click="removeComponent(index)" class="absolute -right-2 -top-2 bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center hidden group-hover:flex z-30 shadow-md">
                                <span class="material-symbols-outlined text-[14px]">close</span>
                            </button>

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

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('appearanceEditor', () => ({
            device: 'desktop',
            activeComponents: @json($store->appearance_data ?? []),
            isSaving: false,
            sortableMobile: null,
            sortableDesktop: null,
            
            init() {
                if (!Array.isArray(this.activeComponents) || this.activeComponents.length === 0) {
                    this.activeComponents = [
                        { id: this.generateId(), type: 'banner' },
                        { id: this.generateId(), type: 'voucher' },
                        { id: this.generateId(), type: 'products' },
                        { id: this.generateId(), type: 'text' }
                    ];
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
            
            addComponent(type) {
                this.activeComponents.push({ id: this.generateId(), type: type });
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
            }
        }));
    });
</script>
@endsection
