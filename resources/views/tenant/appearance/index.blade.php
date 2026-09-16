@extends('layouts.tenant')

@section('title', 'Desain Tampilan Halaman')

@section('content')
<div class="flex flex-col h-full bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200" x-data="appearanceEditor()">
    
    <!-- Floating Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-[-12px] scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-[-12px] scale-95"
         class="fixed top-5 right-5 z-50 flex items-center gap-2.5 px-4 py-3 rounded-2xl bg-sky-600/95 text-white border border-sky-500/80 shadow-2xl backdrop-blur-md text-xs font-bold"
         style="display: none;">
        <span class="material-symbols-outlined text-[20px] text-emerald-400">check_circle</span>
        <span x-text="toast.message"></span>
    </div>

    <!-- Top Action Header -->
    <div class="sticky top-0 bg-white/95 dark:bg-[#111726]/95 backdrop-blur-md border-b border-slate-200/80 dark:border-[#222f49] px-4 sm:px-6 py-3 sm:py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 shadow-sm z-40 shrink-0">
        <div class="min-w-0">
            <h1 class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2">
                Desain Tampilan Halaman
                <span x-show="hasUnsavedChanges" x-cloak class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                    Belum Disimpan
                </span>
            </h1>
            <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Kustomisasi tampilan profil Bio Link, susunan widget, tema warna, dan etalase digital Anda.
            </p>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 flex-wrap sm:flex-nowrap shrink-0">
            <button type="button" @click="resetLayout()" class="px-3 sm:px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm cursor-pointer" title="Kembalikan susunan widget etalase ke tata letak awal default">
                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                <span class="hidden sm:inline">Reset Tata Letak</span>
                <span class="sm:hidden">Reset</span>
            </button>
            @if($store && $store->slug)
            <button type="button" @click="openPreviewModal()" class="flex-1 sm:flex-initial justify-center px-3 sm:px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer" title="Buka live preview interaktif (Mobile & Desktop)">
                <span class="material-symbols-outlined text-[16px] text-slate-500 dark:text-slate-400">visibility</span> <span>Preview Web</span>
            </button>
            @endif
            <button @click="save()" :disabled="isSaving" :class="hasUnsavedChanges ? 'ring-2 ring-amber-400 dark:ring-amber-500' : ''" class="flex-1 sm:flex-initial justify-center px-4 sm:px-5 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs font-bold transition-all flex items-center gap-1.5 disabled:opacity-60 cursor-pointer whitespace-nowrap active:scale-95">
                <span class="material-symbols-outlined text-[16px]" x-text="isSaving ? 'hourglass_empty' : 'save'">save</span>
                <span x-text="isSaving ? 'Menyimpan...' : (hasUnsavedChanges ? 'Simpan Perubahan *' : 'Simpan Perubahan')">Simpan Perubahan</span>
            </button>
        </div>
    </div>

    <!-- Global Mode Switcher Bar (Tampil di semua perangkat: Mobile, Tablet & Desktop) -->
    <div class="bg-slate-50/95 dark:bg-[#0c1220]/95 backdrop-blur-sm border-b border-slate-200/80 dark:border-[#222f49] px-4 sm:px-6 py-2 sm:py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 shrink-0 z-30 shadow-2xs">
        <div class="flex items-center justify-between sm:justify-start gap-2">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full animate-pulse"
                      :class="storeMode === 'profile' ? 'bg-purple-500' : (storeMode === 'hybrid' ? 'bg-emerald-500' : 'bg-sky-500')"></span>
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px] text-slate-400">tune</span>
                    <span>Pilihan Mode Halaman:</span>
                </span>
            </div>
            <span class="text-[10px] font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider"
                  :class="storeMode === 'profile' ? 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20' : (storeMode === 'hybrid' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20')">
                <span x-text="storeMode === 'profile' ? 'Bio Link' : (storeMode === 'hybrid' ? 'Hybrid' : 'Toko Digital')"></span>
            </span>
        </div>

        <div class="grid grid-cols-3 gap-1 p-1 bg-white dark:bg-[#111726] rounded-xl border border-slate-200 dark:border-[#222f49] w-full sm:w-auto">
            <!-- 1. Toko Digital -->
            <button type="button" @click="setStoreMode('store')" 
                    :class="storeMode === 'store' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" 
                    class="px-2.5 py-1.5 rounded-lg text-xs transition-all flex items-center justify-center gap-1 cursor-pointer" 
                    title="Mode Toko Digital (E-Commerce Katalog Penuh)">
                <span class="material-symbols-outlined text-[15px]">storefront</span>
                <span class="truncate">Toko Digital</span>
            </button>

            <!-- 2. Bio Link -->
            <button type="button" @click="setStoreMode('profile')" 
                    :class="storeMode === 'profile' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" 
                    class="px-2.5 py-1.5 rounded-lg text-xs transition-all flex items-center justify-center gap-1 cursor-pointer" 
                    title="Mode Bio Link (Profil Personal ala Linktree / Lynk.id)">
                <span class="material-symbols-outlined text-[15px]">contact_page</span>
                <span class="truncate">Bio Link</span>
            </button>

            <!-- 3. Hybrid -->
            <button type="button" @click="setStoreMode('hybrid')" 
                    :class="storeMode === 'hybrid' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" 
                    class="px-2.5 py-1.5 rounded-lg text-xs transition-all flex items-center justify-center gap-1 cursor-pointer" 
                    title="Mode Hybrid (Kombinasi Bio Link + Toko Digital)">
                <span class="material-symbols-outlined text-[15px]">layers</span>
                <span class="truncate">Hybrid</span>
            </button>
        </div>
    </div>

    <!-- Mobile Tab Switcher (Visible only on screens < lg) -->
    <div class="lg:hidden bg-white dark:bg-[#111726] border-b border-slate-200/80 dark:border-[#222f49] px-4 py-2 flex items-center justify-center gap-1.5 shrink-0 z-20 overflow-x-auto">
        <button type="button" @click="mobileTab = 'palette'; sidebarTab = 'widgets'" :class="mobileTab === 'palette' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold'" class="flex-1 py-2 px-2.5 rounded-xl text-xs transition-all flex items-center justify-center gap-1 cursor-pointer whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">widgets</span>
            <span>Widget</span>
        </button>
        <button type="button" @click="mobileTab = 'voucher'; sidebarTab = 'voucher'" :class="mobileTab === 'voucher' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold'" class="flex-1 py-2 px-2.5 rounded-xl text-xs transition-all flex items-center justify-center gap-1 cursor-pointer whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">confirmation_number</span>
            <span>Voucher</span>
        </button>
        <button type="button" @click="mobileTab = 'biolink'; sidebarTab = 'biolink'" :class="mobileTab === 'biolink' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold'" class="flex-1 py-2 px-2.5 rounded-xl text-xs transition-all flex items-center justify-center gap-1 cursor-pointer whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">link</span>
            <span>Bio Link</span>
        </button>
        <button type="button" @click="mobileTab = 'canvas'" :class="mobileTab === 'canvas' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold'" class="flex-1 py-2 px-2.5 rounded-xl text-xs transition-all flex items-center justify-center gap-1 cursor-pointer whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">devices</span>
            <span>Kanvas</span>
        </button>
    </div>

    <!-- Workspace Container (Sidebar + Interactive Canvas) -->
    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden min-h-0 relative">
        
        <!-- LEFT SIDEBAR: WIDGET COMPONENT PALETTE -->
        <div :class="(mobileTab === 'palette' || mobileTab === 'voucher' || mobileTab === 'biolink') ? 'flex' : 'hidden lg:flex'" class="w-full lg:w-[320px] border-b lg:border-b-0 lg:border-r border-slate-200/80 dark:border-[#222f49] bg-white dark:bg-[#111726] flex-col h-full z-10 shrink-0 shadow-sm overflow-hidden">

            <!-- Sidebar Tab Toggle (Desktop) -->
            <div class="hidden lg:flex border-b border-slate-100 dark:border-[#222f49]">
                <button type="button" @click="sidebarTab = 'widgets'; mobileTab = 'palette'" :class="sidebarTab === 'widgets' ? 'border-b-2 border-sky-500 text-sky-600 dark:border-sky-400 dark:text-sky-400 font-black' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="flex-1 py-2.5 text-xs font-bold flex items-center justify-center gap-1.5 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[15px]">widgets</span> Widget
                </button>
                <button type="button" @click="sidebarTab = 'voucher'; mobileTab = 'voucher'" :class="sidebarTab === 'voucher' ? 'border-b-2 border-sky-500 text-sky-600 dark:border-sky-400 dark:text-sky-400 font-black' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="flex-1 py-2.5 text-xs font-bold flex items-center justify-center gap-1.5 transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[15px]">confirmation_number</span> Kupon
                </button>
                <button type="button" @click="sidebarTab = 'biolink'; mobileTab = 'biolink'" :class="sidebarTab === 'biolink' ? 'border-b-2 border-sky-500 text-sky-600 dark:border-sky-400 dark:text-sky-400 font-black' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="flex-1 py-2.5 text-xs font-bold flex items-center justify-center gap-1.5 transition-colors cursor-pointer" title="Kelola Tombol Bio Link (Linktree / Lynk.id)">
                    <span class="material-symbols-outlined text-[15px]">link</span> Bio Link
                </button>
            </div>

            <!-- PANEL: Widget Palette -->
            <div x-show="sidebarTab === 'widgets'" class="flex flex-col flex-1 overflow-hidden">
            <div class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-[#222f49]">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                    Pilihan Blok Widget
                </h2>
                <p class="text-[11px] text-slate-400 mt-0.5">Klik blok di bawah untuk menambahkannya ke kanvas:</p>
            </div>

            <!-- Components List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-5 custom-scrollbar">
                
                <!-- Notice if Store Mode is currently 'profile' -->
                <div x-show="storeMode === 'profile'" class="p-3 rounded-xl bg-purple-50 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-800/50 text-xs text-purple-800 dark:text-purple-300 flex items-start gap-2">
                    <span class="material-symbols-outlined text-[18px] text-purple-500 shrink-0 mt-0.5">info</span>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold">Mode aktif: Bio Link</p>
                        <p class="text-[11px] text-purple-700/90 dark:text-purple-400/90 mt-0.5">Halaman publik saat ini difokuskan pada tautan profil personal. Agar widget katalog produk ini tampil, ganti mode ke <strong>Toko Digital</strong> atau <strong>Hybrid</strong>.</p>
                        <div class="flex items-center gap-1.5 mt-2">
                            <button type="button" @click="setStoreMode('store')" class="px-2.5 py-1 rounded-lg bg-sky-500 hover:bg-sky-400 text-white text-[10px] font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer">
                                <span class="material-symbols-outlined text-[13px]">storefront</span> Aktifkan Toko
                            </button>
                            <button type="button" @click="setStoreMode('hybrid')" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer">
                                <span class="material-symbols-outlined text-[13px]">layers</span> Aktifkan Hybrid
                            </button>
                        </div>
                    </div>
                </div>

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

            <!-- PANEL: Penempatan Voucher -->
            <div x-show="sidebarTab === 'voucher'" class="flex flex-col flex-1 overflow-hidden">
                <div class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-[#222f49]">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-rose-500 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px]">confirmation_number</span>
                        Penempatan Kupon & Voucher
                    </h2>
                    <p class="text-[11px] text-slate-400 mt-1">Pilih voucher/diskon yang sudah dibuat untuk ditampilkan di setiap lokasi toko. Jika tidak dipilih, sistem akan menampilkan otomatis.</p>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">

                    @php
                        $voucherPlacement = is_array($store->appearance_data ?? null)
                            ? ($store->appearance_data['voucher_placement'] ?? [])
                            : [];
                        $activeCampaigns = \App\Models\Campaign::where('store_id', $store->id)
                            ->whereIn('status', ['active', 'scheduled'])
                            ->orderBy('name')
                            ->get();
                    @endphp

                    @if($activeCampaigns->isEmpty())
                        <div class="text-center py-8">
                            <span class="material-symbols-outlined text-3xl text-slate-300 dark:text-slate-600">confirmation_number</span>
                            <p class="text-xs text-slate-400 mt-2">Belum ada diskon/voucher aktif.</p>
                            <a href="{{ route('tenant.campaigns.create') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-bold text-rose-500 hover:text-rose-600">
                                <span class="material-symbols-outlined text-[15px]">add_circle</span>
                                Buat Diskon / Voucher
                            </a>
                        </div>
                    @else
                        <!-- Info posisi -->
                        <div class="bg-slate-50 dark:bg-[#0c1220] rounded-xl p-3 text-[11px] text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-[#222f49] space-y-2">
                            <p class="font-bold text-slate-700 dark:text-slate-300">📍 3 Lokasi Penempatan Otomatis (Global):</p>
                            <ul class="space-y-1 pl-2">
                                <li>• <b>Header Toko</b> — tampil sebagai banner promo di atas katalog etalase</li>
                                <li>• <b>Pop-up Halaman Produk</b> — muncul saat pembeli buka produk</li>
                                <li>• <b>Halaman Checkout</b> — tampil saat pembeli selesai beli</li>
                            </ul>
                            <div class="pt-2 border-t border-slate-200 dark:border-slate-800 text-[10.5px] text-amber-600 dark:text-amber-400 bg-amber-500/10 p-2 rounded-lg leading-relaxed">
                                💡 <b>Tips:</b> Jika Anda memasang <b>"Blok Kupon Voucher"</b> manual di susunan <b>Widget</b>, sistem otomatis memakai blok widget tersebut agar kupon tidak tampil dobel di etalase toko.
                            </div>
                        </div>

                        <!-- Slot 1: Header Toko -->
                        <div class="space-y-1.5">
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-[15px] text-rose-500">store</span>
                                Header Toko
                            </label>
                            <select id="vp_header" x-model="voucherPlacement.header" class="w-full px-3 py-2 rounded-xl text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-rose-500 font-medium">
                                <option value="">— Otomatis (sistem) —</option>
                                <option value="none">🚫 Nonaktifkan / Sembunyikan</option>
                                @foreach($activeCampaigns as $c)
                                    <option value="{{ $c->id }}" {{ ($voucherPlacement['header'] ?? '') == (string)$c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                        @if($c->code) [{{ $c->code }}] @endif
                                        ({{ $c->discount_type === 'percentage' ? $c->discount_value.'%' : 'Rp '.number_format($c->discount_value,0,',','.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Slot 2: Pop-up Produk -->
                        <div class="space-y-1.5">
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-[15px] text-amber-500">inventory_2</span>
                                Pop-up Halaman Produk
                            </label>
                            <select id="vp_product" x-model="voucherPlacement.product_page" class="w-full px-3 py-2 rounded-xl text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-rose-500 font-medium">
                                <option value="">— Otomatis (sistem) —</option>
                                <option value="none">🚫 Nonaktifkan / Sembunyikan</option>
                                @foreach($activeCampaigns as $c)
                                    <option value="{{ $c->id }}" {{ ($voucherPlacement['product_page'] ?? '') == (string)$c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                        @if($c->code) [{{ $c->code }}] @endif
                                        ({{ $c->discount_type === 'percentage' ? $c->discount_value.'%' : 'Rp '.number_format($c->discount_value,0,',','.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Slot 3: Checkout -->
                        <div class="space-y-1.5">
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                                <span class="material-symbols-outlined text-[15px] text-emerald-500">shopping_cart_checkout</span>
                                Halaman Checkout
                            </label>
                            <select id="vp_checkout" x-model="voucherPlacement.checkout" class="w-full px-3 py-2 rounded-xl text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-rose-500 font-medium">
                                <option value="">— Otomatis (sistem) —</option>
                                <option value="none">🚫 Nonaktifkan / Sembunyikan</option>
                                @foreach($activeCampaigns as $c)
                                    <option value="{{ $c->id }}" {{ ($voucherPlacement['checkout'] ?? '') == (string)$c->id ? 'selected' : '' }}>
                                        {{ $c->name }}
                                        @if($c->code) [{{ $c->code }}] @endif
                                        ({{ $c->discount_type === 'percentage' ? $c->discount_value.'%' : 'Rp '.number_format($c->discount_value,0,',','.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Save Button -->
                        <div class="pt-2">
                            <button type="button" @click="saveVoucherPlacement()" :disabled="isSavingVoucher" class="w-full py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs font-bold transition-all active:scale-95 flex items-center justify-center gap-1.5 cursor-pointer disabled:opacity-60">
                                <span class="material-symbols-outlined text-[15px]" x-text="isSavingVoucher ? 'hourglass_empty' : 'save'">save</span>
                                <span x-text="isSavingVoucher ? 'Menyimpan...' : 'Simpan Penempatan'">Simpan Penempatan</span>
                            </button>
                            <p x-show="voucherSaveMsg" x-text="voucherSaveMsg" class="text-center text-[11px] mt-2 text-emerald-600 dark:text-emerald-400 font-bold"></p>
                        </div>

                        <!-- Link ke modul diskon -->
                        <div class="pt-1">
                            <a href="{{ route('tenant.campaigns.index') }}" class="flex items-center justify-center gap-1.5 text-[11px] text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 transition-colors">
                                <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                Kelola Diskon & Voucher
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- PANEL: Bio Link Editor -->
            <div x-show="sidebarTab === 'biolink'" class="flex flex-col flex-1 overflow-hidden" style="display: none;">
                <div class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-[#222f49] flex items-center justify-between gap-2">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">link</span> Tombol Tautan Bio Link
                        </h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">Tampil di mode <b>Bio Link</b> dan <b>Hybrid</b>.</p>
                    </div>
                    <button type="button" @click="saveProfileLinks()" :disabled="isSavingLinks" class="px-3 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-[11px] font-bold transition-all active:scale-95 flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[14px]">save</span>
                        <span x-text="isSavingLinks ? '...' : 'Simpan'">Simpan</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">
                    <!-- Status Notice if currently in Toko Digital Mode -->
                    <div x-show="storeMode === 'store'" class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/50 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2">
                        <span class="material-symbols-outlined text-[18px] text-amber-500 shrink-0 mt-0.5">info</span>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold">Mode aktif: Toko Digital</p>
                            <p class="text-[11px] text-amber-700/90 dark:text-amber-400/90 mt-0.5">Tombol Bio Link ini hanya akan tampil di halaman publik jika mode diubah ke <strong>Bio Link</strong> atau <strong>Hybrid</strong>.</p>
                            <div class="flex items-center gap-1.5 mt-2">
                                <button type="button" @click="setStoreMode('profile')" class="px-2.5 py-1 rounded-lg bg-purple-600 hover:bg-purple-500 text-white text-[10px] font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer">
                                    <span class="material-symbols-outlined text-[13px]">contact_page</span> Aktifkan Bio Link
                                </button>
                                <button type="button" @click="setStoreMode('hybrid')" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold transition-all shadow-xs flex items-center gap-1 cursor-pointer">
                                    <span class="material-symbols-outlined text-[13px]">layers</span> Aktifkan Hybrid
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Presets -->
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Tambah Cepat:</span>
                        <div class="grid grid-cols-2 gap-1.5">
                            <button type="button" @click="addProfileLink({ title: 'Chat WhatsApp', url: 'https://wa.me/', icon: 'chat', color: '#059669' })" class="px-2 py-1.5 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition-colors flex items-center gap-1.5 text-left cursor-pointer">
                                <span class="material-symbols-outlined text-[14px]">chat</span> WhatsApp
                            </button>
                            <button type="button" @click="addProfileLink({ title: 'Channel Telegram', url: 'https://t.me/', icon: 'send', color: '#0284c7' })" class="px-2 py-1.5 rounded-lg text-xs font-semibold bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800 hover:bg-sky-100 transition-colors flex items-center gap-1.5 text-left cursor-pointer">
                                <span class="material-symbols-outlined text-[14px]">send</span> Telegram
                            </button>
                            <button type="button" @click="addProfileLink({ title: 'Website Portofolio', url: 'https://', icon: 'language', color: '#7c3aed' })" class="px-2 py-1.5 rounded-lg text-xs font-semibold bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-800 hover:bg-violet-100 transition-colors flex items-center gap-1.5 text-left cursor-pointer">
                                <span class="material-symbols-outlined text-[14px]">language</span> Portofolio
                            </button>
                            <button type="button" @click="addProfileLink({ title: 'YouTube Channel', url: 'https://youtube.com/', icon: 'smart_display', color: '#e11d48' })" class="px-2 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800 hover:bg-rose-100 transition-colors flex items-center gap-1.5 text-left cursor-pointer">
                                <span class="material-symbols-outlined text-[14px]">smart_display</span> YouTube
                            </button>
                        </div>
                    </div>

                    <!-- Links List (Accordion: Collapsed by Default) -->
                    <div class="space-y-2.5">
                        <template x-for="(link, lIdx) in profileLinks" :key="lIdx">
                            <div class="rounded-2xl bg-slate-50 dark:bg-[#0e1526] border border-slate-200 dark:border-[#222f49] shadow-xs overflow-hidden transition-all duration-200">
                                <!-- Collapsed Header Bar (Always visible) -->
                                <div @click="expandedLinkIndex = (expandedLinkIndex === lIdx ? null : lIdx)"
                                     class="p-3 flex items-center justify-between gap-2 cursor-pointer hover:bg-slate-100/70 dark:hover:bg-slate-800/50 transition-colors select-none">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <span class="w-5 h-5 rounded-md bg-teal-500/10 text-teal-600 dark:text-teal-400 text-[10px] font-black flex items-center justify-center shrink-0" x-text="lIdx + 1"></span>
                                        
                                        <!-- Mini Image or Icon Preview -->
                                        <template x-if="link.image">
                                            <img :src="link.image" class="w-6 h-6 rounded-md object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                                        </template>
                                        <template x-if="!link.image">
                                            <div class="w-6 h-6 rounded-md flex items-center justify-center text-white shrink-0 text-[11px]" :style="'background:' + (link.color || '#0284c7')">
                                                <span class="material-symbols-outlined text-[13px]" x-text="link.icon || 'link'"></span>
                                            </div>
                                        </template>

                                        <!-- Title & Layout Badge -->
                                        <div class="min-w-0 flex-1">
                                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate block leading-tight" x-text="link.title || 'Tautan Baru'"></span>
                                            <span class="text-[9px] text-slate-400 font-medium" x-text="link.layout === 'grid' ? '2 Kolom' : (link.layout === 'card' ? 'Gambar Besar' : '1 Baris')"></span>
                                        </div>
                                    </div>

                                    <!-- Right: Action Buttons & Expand Icon -->
                                    <div class="flex items-center gap-0.5 shrink-0">
                                        <button type="button" @click.stop="moveProfileLinkUp(lIdx)" :disabled="lIdx === 0" :class="lIdx === 0 ? 'opacity-30' : 'hover:bg-slate-200 dark:hover:bg-slate-700'" class="p-1 rounded text-slate-400 hover:text-slate-600 cursor-pointer" title="Naikkan urutan">
                                            <span class="material-symbols-outlined text-[15px]">arrow_upward</span>
                                        </button>
                                        <button type="button" @click.stop="moveProfileLinkDown(lIdx)" :disabled="lIdx === profileLinks.length - 1" :class="lIdx === profileLinks.length - 1 ? 'opacity-30' : 'hover:bg-slate-200 dark:hover:bg-slate-700'" class="p-1 rounded text-slate-400 hover:text-slate-600 cursor-pointer" title="Turunkan urutan">
                                            <span class="material-symbols-outlined text-[15px]">arrow_downward</span>
                                        </button>
                                        <button type="button" @click.stop="removeProfileLink(lIdx)" class="p-1 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded ml-0.5 cursor-pointer" title="Hapus tombol ini">
                                            <span class="material-symbols-outlined text-[15px]">delete</span>
                                        </button>
                                        <div class="w-px h-3.5 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                                        <span class="material-symbols-outlined text-[18px] text-slate-400 transition-transform duration-200" :class="expandedLinkIndex === lIdx ? 'rotate-180 text-teal-500 font-bold' : ''">expand_more</span>
                                    </div>
                                </div>

                                <!-- Collapsible Form Body (Only open for expandedLinkIndex === lIdx) -->
                                <div x-show="expandedLinkIndex === lIdx" class="p-3.5 pt-2 border-t border-slate-200/60 dark:border-slate-800 space-y-3">
                                    <!-- Bentuk Tampilan (Layout Selector) -->
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 block mb-1">Pilihan Layout Tampilan:</span>
                                        <div class="grid grid-cols-3 gap-1 bg-slate-200/80 dark:bg-[#161f33] p-1 rounded-xl text-center">
                                            <button type="button" @click="link.layout = 'list'; hasUnsavedChanges = true" 
                                                    :class="(!link.layout || link.layout === 'list') ? 'bg-white dark:bg-slate-800 text-sky-600 dark:text-sky-400 shadow-xs font-black' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 font-semibold'" 
                                                    class="py-1.5 px-1 rounded-lg text-[10px] transition-all flex flex-col items-center justify-center gap-0.5 cursor-pointer">
                                                <span class="material-symbols-outlined text-[15px]">view_stream</span>
                                                <span>1 Baris</span>
                                            </button>
                                            <button type="button" @click="link.layout = 'grid'; hasUnsavedChanges = true" 
                                                    :class="link.layout === 'grid' ? 'bg-white dark:bg-slate-800 text-sky-600 dark:text-sky-400 shadow-xs font-black' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 font-semibold'" 
                                                    class="py-1.5 px-1 rounded-lg text-[10px] transition-all flex flex-col items-center justify-center gap-0.5 cursor-pointer">
                                                <span class="material-symbols-outlined text-[15px]">grid_view</span>
                                                <span>2 Kolom</span>
                                            </button>
                                            <button type="button" @click="link.layout = 'card'; hasUnsavedChanges = true" 
                                                    :class="link.layout === 'card' ? 'bg-white dark:bg-slate-800 text-sky-600 dark:text-sky-400 shadow-xs font-black' : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 font-semibold'" 
                                                    class="py-1.5 px-1 rounded-lg text-[10px] transition-all flex flex-col items-center justify-center gap-0.5 cursor-pointer">
                                                <span class="material-symbols-outlined text-[15px]">featured_play_list</span>
                                                <span>Gambar Besar</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Input Judul & Subtitle -->
                                    <div class="space-y-1.5">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-1">Judul Teks Tombol *</label>
                                            <input type="text" x-model="link.title" @input="hasUnsavedChanges = true" placeholder="Judul Teks Tombol / Nama Penawaran *" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg font-bold text-slate-900 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-1">Sub-judul / Harga (Opsional)</label>
                                            <input type="text" x-model="link.subtitle" @input="hasUnsavedChanges = true" placeholder="Sub-judul / Harga (misal: Rp 150.000 / Diskon 50%)" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-600 dark:text-slate-300">
                                        </div>
                                    </div>

                                    <!-- Deskripsi Lengkap (Teks Panjang) -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400">Deskripsi Lengkap (Teks Panjang):</label>
                                            <span class="text-[9px] text-slate-400 font-normal">Bisa diisi banyak teks</span>
                                        </div>
                                        <textarea x-model="link.description" @input="hasUnsavedChanges = true" rows="3" placeholder="Tuliskan keterangan lengkap detail produk/jasa, fitur, penjelasan teks panjang, panduan, dll..." class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-700 dark:text-slate-200 focus:border-teal-500 outline-none leading-relaxed custom-scrollbar"></textarea>
                                    </div>

                                    <!-- Input URL Link & Teks Tombol Aksi (CTA) -->
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-1">URL Link Tujuan *</label>
                                            <input type="text" x-model="link.url" @input="hasUnsavedChanges = true" placeholder="URL Link: https://wa.me/... atau https://..." class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg font-mono text-slate-900 dark:text-white">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 mb-1">Teks Tombol Aksi di Halaman Detail (CTA)</label>
                                            <input type="text" x-model="link.button_text" @input="hasUnsavedChanges = true" placeholder="Default: Buka Tautan / Pesan (Bisa: BOOK NOW, Hubungi WA, dll)" class="w-full px-2.5 py-1.5 text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white font-bold">
                                        </div>
                                    </div>

                                    <!-- Foto / Thumbnail Image (Opsional) -->
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 block mb-1">Foto / Sampul Thumbnail (Opsional):</span>
                                        <template x-if="link.image">
                                            <div class="flex items-center gap-2 w-full bg-white dark:bg-[#0c1220] p-1.5 rounded-xl border border-slate-200 dark:border-[#222f49]">
                                                <img :src="link.image" class="w-10 h-10 object-cover rounded-lg border border-slate-200 dark:border-slate-700 shrink-0">
                                                <span class="text-[10px] text-slate-500 truncate flex-1 font-mono" x-text="link.image"></span>
                                                <button type="button" @click="link.image = null; hasUnsavedChanges = true" class="text-rose-500 hover:text-rose-700 p-1 cursor-pointer" title="Hapus Gambar">
                                                    <span class="material-symbols-outlined text-[16px]">close</span>
                                                </button>
                                            </div>
                                        </template>
                                        <template x-if="!link.image">
                                            <div class="flex items-center gap-1.5 w-full">
                                                <label class="cursor-pointer px-2.5 py-1.5 rounded-lg bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800 hover:bg-sky-100 transition-colors text-[11px] font-bold flex items-center gap-1 shrink-0">
                                                    <span class="material-symbols-outlined text-[14px]">add_photo_alternate</span>
                                                    <span>Upload Foto</span>
                                                    <input type="file" class="hidden" accept="image/*" @change="openCropper($event, link, 'image', false, (link.layout === 'card' ? 16/9 : 1/1))">
                                                </label>
                                                <input type="text" x-model="link.image" @input="hasUnsavedChanges = true" placeholder="atau paste URL foto..." class="flex-1 px-2 py-1.5 text-[11px] bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg font-mono text-slate-700 dark:text-slate-300">
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Badge & Icon & Color Picker -->
                                    <div class="grid grid-cols-12 gap-1.5 items-center">
                                        <div class="col-span-5">
                                            <input type="text" x-model="link.badge" @input="hasUnsavedChanges = true" placeholder="Badge (HOT/NEW)" class="w-full px-2 py-1.5 text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white uppercase font-bold text-[10px]">
                                        </div>
                                        <div class="col-span-5">
                                            <select x-model="link.icon" @change="hasUnsavedChanges = true" class="w-full py-1.5 px-1.5 text-xs bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-lg text-slate-900 dark:text-white cursor-pointer font-semibold text-[11px]">
                                                <option value="link">Ikon: Tautan</option>
                                                <option value="chat">Ikon: WhatsApp</option>
                                                <option value="send">Ikon: Telegram</option>
                                                <option value="language">Ikon: Website</option>
                                                <option value="smart_display">Ikon: YouTube</option>
                                                <option value="shopping_bag">Ikon: Belanja</option>
                                                <option value="school">Ikon: Kelas/Kursus</option>
                                                <option value="star">Ikon: Bintang</option>
                                                <option value="bolt">Ikon: Flash Promo</option>
                                                <option value="workspace_premium">Ikon: VIP / PRO</option>
                                            </select>
                                        </div>
                                        <div class="col-span-2 flex justify-end">
                                            <input type="color" x-model="link.color" @input="hasUnsavedChanges = true" class="w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer p-0 bg-transparent" title="Pilih warna tombol">
                                        </div>
                                    </div>

                                    <!-- Button Selesai Tutup -->
                                    <button type="button" @click="expandedLinkIndex = null" class="w-full py-1.5 text-center text-[11px] font-bold text-teal-600 dark:text-teal-400 bg-teal-50/60 dark:bg-teal-950/20 hover:bg-teal-100/60 rounded-lg flex items-center justify-center gap-1 transition-colors cursor-pointer mt-2">
                                        <span class="material-symbols-outlined text-[13px]">check</span> Selesai Edit (Tutup)
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="profileLinks.length === 0" class="p-6 text-center text-slate-400 text-xs border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                            Belum ada tombol link. Klik tombol tambah di bawah atau gunakan template di atas.
                        </div>

                        <button type="button" @click="addProfileLink()" class="w-full py-2 rounded-xl text-xs font-bold text-teal-600 dark:text-teal-400 bg-teal-50 dark:bg-teal-950/30 border border-teal-200 dark:border-teal-800 hover:bg-teal-100 transition-colors flex items-center justify-center gap-1.5 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">add_circle</span> Tambah Tombol Link Baru
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- CENTER CANVAS: LIVE INTERACTIVE STORE BUILDER -->
        <div :class="mobileTab === 'canvas' ? 'flex' : 'hidden lg:flex'" class="flex-1 bg-slate-100/60 dark:bg-[#070a12] flex-col items-center py-4 sm:py-6 px-2 sm:px-4 overflow-auto relative custom-scrollbar w-full min-w-0">
            
            <!-- Control Bar: Mode Tampilan Switcher & Viewport (Desktop/Mobile) -->
            <div class="w-full max-w-[960px] flex flex-col sm:flex-row items-center justify-between gap-3 mb-4 sm:mb-6 shrink-0">
                <!-- Mode Tampilan Switcher -->
                <div class="flex items-center gap-1 bg-white dark:bg-[#111726] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#222f49] overflow-x-auto max-w-full">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 pl-2 pr-1 shrink-0 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">tune</span> Mode:
                    </span>
                    <button type="button" @click="setStoreMode('store')" 
                            :class="storeMode === 'store' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" 
                            class="px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5 cursor-pointer shrink-0" title="Mode Toko Digital (E-Commerce Katalog)">
                        <span class="material-symbols-outlined text-[16px]">storefront</span>
                        <span>Toko Digital</span>
                    </button>
                    <button type="button" @click="setStoreMode('profile')" 
                            :class="storeMode === 'profile' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" 
                            class="px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5 cursor-pointer shrink-0" title="Mode Bio Link (Linktree / Lynk.id)">
                        <span class="material-symbols-outlined text-[16px]">contact_page</span>
                        <span>Bio Link</span>
                    </button>
                    <button type="button" @click="setStoreMode('hybrid')" 
                            :class="storeMode === 'hybrid' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" 
                            class="px-3 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5 cursor-pointer shrink-0" title="Mode Hybrid (Bio Link + Toko Digital)">
                        <span class="material-symbols-outlined text-[16px]">layers</span>
                        <span>Hybrid</span>
                    </button>
                </div>

                <!-- Viewport Switcher (Desktop / Mobile) -->
                <div class="flex items-center gap-1 bg-white dark:bg-[#111726] p-1.5 rounded-2xl border border-slate-200/80 dark:border-[#222f49] shrink-0">
                    <button @click="device = 'desktop'" :class="device === 'desktop' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="px-3.5 sm:px-4 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">desktop_windows</span> Desktop
                    </button>
                    <button @click="device = 'mobile'" :class="device === 'mobile' ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 font-semibold'" class="px-3.5 sm:px-4 py-1.5 rounded-xl text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">smartphone</span> Mobile
                    </button>
                </div>
            </div>

            <!-- DESKTOP CANVAS VIEWPORT -->
            <div x-show="device === 'desktop'" class="w-full max-w-[960px] bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-2xl overflow-hidden flex flex-col relative min-h-[600px] shrink-0 mb-12">
                
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
                            <span>rhantech.com/{{ $store->slug ?? 'toko-anda' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Desktop Store Header Banner -->
                <div class="w-full h-[220px] bg-gradient-to-r from-sky-700 via-sky-800 to-slate-900 text-white relative overflow-hidden flex items-end p-6 border-b border-slate-200 dark:border-[#222f49] group/header">
                    <div class="absolute inset-0 bg-cover bg-center transition-all duration-300" :style="getBannerStyle(headerBanner)" :class="headerBanner ? 'opacity-90' : 'opacity-20'"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent pointer-events-none"></div>
                    
                    <!-- Header Banner Action Buttons -->
                    <div class="absolute top-4 right-4 z-20 flex items-center gap-2">
                        <button type="button" @click="openBannerModal()" class="cursor-pointer bg-sky-600/80 hover:bg-sky-500 backdrop-blur-md text-white px-3.5 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 border border-sky-400/40 transition-all shadow-lg hover:scale-105" title="Atur Tampilan Banner (Gradien, Warna Biasa, Gambar)">
                            <span class="material-symbols-outlined text-[16px] text-white">palette</span>
                            <span x-text="headerBanner ? 'Atur / Ganti Banner' : 'Pasang Banner Toko'"></span>
                        </button>
                        <button type="button" x-show="headerBanner" @click="clearHeaderBanner()" class="bg-rose-500/80 hover:bg-rose-600 text-white p-1.5 rounded-xl text-xs font-bold flex items-center justify-center border border-white/20 transition-all shadow-lg hover:scale-105" title="Hapus Settingan Banner (Reset ke Default)">
                            <span class="material-symbols-outlined text-[16px]">delete</span>
                        </button>
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

                <!-- Interactive Bio Link Preview Container (Visible in profile & hybrid modes) -->
                <div x-show="storeMode === 'profile' || storeMode === 'hybrid'" class="p-6 pb-2">
                    <div class="w-full max-w-md mx-auto space-y-2.5 bg-slate-50/70 dark:bg-[#111726]/70 p-4 rounded-2xl border border-slate-200/80 dark:border-[#222f49]">
                        <div class="flex items-center justify-between px-1 mb-1">
                            <span class="text-[11px] font-bold text-teal-600 dark:text-teal-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px]">link</span> Tombol Tautan Bio Link
                            </span>
                            <button type="button" @click="sidebarTab = 'biolink'; mobileTab = 'biolink'" class="text-[11px] font-semibold text-slate-500 hover:text-teal-600 flex items-center gap-0.5 cursor-pointer">
                                <span class="material-symbols-outlined text-[14px]">edit</span> Edit Tombol
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2.5">
                            <template x-for="(link, lIdx) in profileLinks" :key="lIdx">
                                <div :class="link.layout === 'grid' ? 'col-span-1' : 'col-span-2'">
                                    <!-- 1. GRID (2 Kolom) -->
                                    <template x-if="link.layout === 'grid'">
                                        <div class="flex flex-col bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all cursor-pointer text-left h-full"
                                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'">
                                            <div class="w-full aspect-square bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                                <template x-if="link.image">
                                                    <img :src="link.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!link.image">
                                                    <div class="w-full h-full flex items-center justify-center text-white" :style="`background: ${link.color || '#0284c7'};`">
                                                        <span class="material-symbols-outlined text-3xl opacity-90" x-text="link.icon || 'link'"></span>
                                                    </div>
                                                </template>
                                                <template x-if="link.badge">
                                                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-white text-[8px] font-black uppercase tracking-wider" x-text="link.badge"></span>
                                                </template>
                                            </div>
                                            <div class="p-2.5 flex flex-col flex-1 justify-between bg-white dark:bg-[#161b22]">
                                                <div>
                                                    <h4 class="font-bold text-[11px] text-slate-900 dark:text-white line-clamp-2 leading-tight" x-text="link.title || 'Judul Link'"></h4>
                                                    <p x-show="link.subtitle" class="text-[10px] font-bold mt-0.5 truncate" :style="`color: ${link.color || '#0284c7'};`" x-text="link.subtitle"></p>
                                                    <p x-show="link.description" class="text-[9px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2 leading-tight" x-text="link.description"></p>
                                                </div>
                                                <div class="mt-2 pt-1.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[9px] font-bold text-slate-400">
                                                    <span>Buka Link</span>
                                                    <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- 2. CARD (Gambar Besar 1 Kolom Penuh) -->
                                    <template x-if="link.layout === 'card'">
                                        <div class="flex flex-col bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-2xl overflow-hidden shadow-xs hover:shadow-md transition-all cursor-pointer text-left"
                                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'">
                                            <div class="w-full aspect-[16/9] bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                                <template x-if="link.image">
                                                    <img :src="link.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!link.image">
                                                    <div class="w-full h-full flex items-center justify-center text-white" :style="`background: ${link.color || '#0284c7'};`">
                                                        <span class="material-symbols-outlined text-4xl opacity-90" x-text="link.icon || 'link'"></span>
                                                    </div>
                                                </template>
                                                <template x-if="link.badge">
                                                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-lg bg-black/60 backdrop-blur-md text-white text-[9px] font-black uppercase tracking-wider" x-text="link.badge"></span>
                                                </template>
                                            </div>
                                            <div class="p-3 bg-white dark:bg-[#161b22]">
                                                <h4 class="font-extrabold text-xs text-slate-900 dark:text-white leading-snug truncate" x-text="link.title || 'Judul Link'"></h4>
                                                <p x-show="link.subtitle" class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1" x-text="link.subtitle"></p>
                                                <p x-show="link.description" class="text-[10px] text-slate-600 dark:text-slate-300 mt-1 line-clamp-2 leading-relaxed" x-text="link.description"></p>
                                                <div class="mt-2.5 w-full py-1.5 px-3 rounded-xl text-white font-bold text-[10px] text-center flex items-center justify-center gap-1 shadow-xs"
                                                     :style="`background: ${link.color || '#0284c7'};`">
                                                    <span>Buka Tautan</span>
                                                    <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- 3. LIST (Default 1 Baris) -->
                                    <template x-if="!link.layout || link.layout === 'list'">
                                        <div class="flex items-center gap-2.5 w-full p-2.5 rounded-2xl text-white font-bold text-xs shadow-xs hover:shadow-md hover:scale-[1.01] transition-all cursor-pointer relative overflow-hidden"
                                             :style="`background: ${link.color || '#0284c7'};`"
                                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'">
                                            <template x-if="link.badge">
                                                <span class="absolute top-1 right-2 px-1.5 py-0.2 rounded-full bg-white/25 backdrop-blur-md text-[8px] font-black uppercase tracking-wider" x-text="link.badge"></span>
                                            </template>
                                            <template x-if="link.image">
                                                <img :src="link.image" class="w-10 h-10 rounded-xl object-cover shrink-0 border border-white/20">
                                            </template>
                                            <template x-if="!link.image">
                                                <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[18px] opacity-90" x-text="link.icon || 'link'"></span>
                                                </div>
                                            </template>
                                            <div class="flex-1 min-w-0 pr-1 text-left">
                                                <span class="block truncate leading-tight" x-text="link.title || link.url || 'Tombol Link'"></span>
                                                <span x-show="link.subtitle" class="block text-[10px] opacity-80 truncate font-normal mt-0.5" x-text="link.subtitle"></span>
                                                <span x-show="link.description" class="block text-[9px] opacity-75 truncate font-normal mt-0.5" x-text="link.description"></span>
                                            </div>
                                            <span class="material-symbols-outlined text-[15px] opacity-70 shrink-0">arrow_forward</span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div x-show="profileLinks.length === 0" 
                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'"
                             class="p-4 rounded-xl border-2 border-dashed border-teal-300 dark:border-teal-800/60 bg-teal-50/40 dark:bg-teal-950/20 text-center cursor-pointer hover:bg-teal-50 transition-colors">
                            <span class="material-symbols-outlined text-teal-500 text-[22px] mb-1">add_link</span>
                            <p class="text-xs font-bold text-teal-700 dark:text-teal-300">Belum ada tombol link</p>
                            <p class="text-[11px] text-teal-600/70 dark:text-teal-400/70 mt-0.5">Klik di sini untuk menambahkan tombol ke Bio Link Anda.</p>
                        </div>
                    </div>

                    <!-- Pure Profile Mode Notice -->
                    <div x-show="storeMode === 'profile'" class="p-3.5 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-3 max-w-md mx-auto mt-3">
                        <span class="material-symbols-outlined text-[20px] text-amber-500 shrink-0">info</span>
                        <div class="leading-tight">
                            <p class="font-bold">Mode Bio Link Aktif</p>
                            <p class="text-[11px] opacity-80 mt-0.5">Halaman publik hanya menampilkan bio &amp; tombol tautan. Ubah ke mode <b>Hybrid</b> jika ingin menampilkan widget etalase produk.</p>
                        </div>
                    </div>
                </div>

                <!-- Desktop Droppable Canvas Area -->
                <div id="desktop-canvas" x-show="storeMode !== 'profile'" class="p-6 space-y-4 min-h-[300px]">
                    <template x-for="(comp, index) in activeComponents" :key="comp.id">
                        <div class="canvas-widget-item relative transition-all rounded-2xl" :data-id="comp.id">
                            <!-- Action Buttons / Toolbar (Always visible) -->
                            <div class="absolute top-3 right-3 z-30 flex items-center gap-1 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border border-slate-200 dark:border-[#222f49] rounded-xl px-1.5 py-1 shadow-md">
                                <button type="button" @click.stop="moveUp(index)" :disabled="index === 0" :class="index === 0 ? 'opacity-30 cursor-not-allowed text-slate-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-sky-500'" class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors" title="Pindah ke Atas">
                                    <span class="material-symbols-outlined text-[16px]">arrow_upward</span>
                                </button>
                                <button type="button" @click.stop="moveDown(index)" :disabled="index === activeComponents.length - 1" :class="index === activeComponents.length - 1 ? 'opacity-30 cursor-not-allowed text-slate-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-sky-500'" class="w-7 h-7 rounded-lg flex items-center justify-center transition-colors" title="Pindah ke Bawah">
                                    <span class="material-symbols-outlined text-[16px]">arrow_downward</span>
                                </button>
                                <div class="drag-handle w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 cursor-grab active:cursor-grabbing transition-colors" title="Geser Urutan (Tahan & Tarik)">
                                    <span class="material-symbols-outlined text-[18px]">drag_indicator</span>
                                </div>
                                <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-0.5"></div>
                                <button type="button" @click.stop="openSettings(index)" class="w-7 h-7 rounded-lg flex items-center justify-center text-sky-600 dark:text-sky-400 hover:bg-sky-50 dark:hover:bg-sky-950/50 hover:text-sky-700 transition-colors" title="Pengaturan Blok (Gir)">
                                    <span class="material-symbols-outlined text-[17px]">settings</span>
                                </button>
                                <button type="button" @click.stop="removeComponent(index)" class="w-7 h-7 rounded-lg flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50 hover:text-rose-600 transition-colors" title="Hapus Blok">
                                    <span class="material-symbols-outlined text-[17px]">delete</span>
                                </button>
                            </div>

                            <!-- BANNER HERO -->
                            <template x-if="comp.type === 'banner'">
                                <div @click="openSettings(index)" class="relative bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl flex flex-col items-center justify-center text-center shadow-xs transition-all h-48 overflow-hidden group/banner cursor-pointer">
                                    <template x-if="comp.data?.images && comp.data.images.length > 0 && comp.data.images[0].image_url">
                                        <div class="absolute inset-0 w-full h-full">
                                            <img :src="comp.data.images[0].image_url" class="w-full h-full object-cover opacity-90 group-hover/banner:opacity-40 transition-opacity">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 text-white opacity-0 group-hover/banner:opacity-100 transition-opacity">
                                                <span class="material-symbols-outlined text-3xl mb-1">edit</span>
                                                <span class="text-sm font-bold" x-text="comp.data.images.length + ' Slide Gambar (Klik untuk Edit)'"></span>
                                            </div>
                                            <!-- Badge Link Info -->
                                            <div class="absolute bottom-2 left-2 z-10 flex items-center gap-1.5 bg-black/60 backdrop-blur-md text-white px-2.5 py-1 rounded-lg text-[11px]">
                                                <span class="material-symbols-outlined text-[14px] text-sky-400">link</span>
                                                <span class="font-mono truncate max-w-[200px]" x-text="comp.data.images[0].link ? comp.data.images[0].link : 'Belum ada link'"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!comp.data?.images || comp.data.images.length === 0 || !comp.data.images[0].image_url">
                                        <div class="p-8 flex flex-col items-center justify-center w-full h-full">
                                            <span class="material-symbols-outlined text-3xl text-sky-500 mb-1">view_carousel</span>
                                            <span class="text-sm font-bold text-slate-800 dark:text-white">Blok Banner Slide Utama</span>
                                            <span class="text-xs text-slate-400 mt-0.5">Klik untuk upload gambar dan atur link tujuan banner</span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- SINGLE IMAGE -->
                            <template x-if="comp.type === 'single_image'">
                                <div @click="openSettings(index)" class="relative bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl flex flex-col items-center justify-center text-center shadow-xs transition-all h-56 overflow-hidden group/single cursor-pointer">
                                    <template x-if="comp.data?.image_url">
                                        <div class="absolute inset-0 w-full h-full">
                                            <img :src="comp.data.image_url" class="w-full h-full object-cover opacity-90 group-hover/single:opacity-40 transition-opacity">
                                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/40 text-white opacity-0 group-hover/single:opacity-100 transition-opacity">
                                                <span class="material-symbols-outlined text-3xl mb-1">edit</span>
                                                <span class="text-sm font-bold">Klik untuk Edit Gambar & Link</span>
                                            </div>
                                            <!-- Badge Link Info -->
                                            <div class="absolute bottom-2 left-2 z-10 flex items-center gap-1.5 bg-black/60 backdrop-blur-md text-white px-2.5 py-1 rounded-lg text-[11px]">
                                                <span class="material-symbols-outlined text-[14px] text-emerald-400">link</span>
                                                <span class="font-mono truncate max-w-[200px]" x-text="comp.data.link ? comp.data.link : 'Belum ada link'"></span>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!comp.data?.image_url">
                                        <div class="p-8 flex flex-col items-center justify-center w-full h-full">
                                            <span class="material-symbols-outlined text-3xl text-emerald-500 mb-1">image</span>
                                            <span class="text-sm font-bold text-slate-800 dark:text-white">Blok Banner Gambar Penuh</span>
                                            <span class="text-xs text-slate-400 mt-0.5">Klik untuk upload gambar dan atur link tujuan banner</span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <!-- VOUCHER -->
                            <template x-if="comp.type === 'voucher'">
                                <div @click="openSettings(index)" class="bg-slate-50 dark:bg-[#111726] border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-sky-500 dark:hover:border-sky-500 rounded-2xl p-6 flex items-center gap-4 shadow-xs transition-colors cursor-pointer group">
                                    <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                        <span class="material-symbols-outlined text-2xl">confirmation_number</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <h4 class="text-sm font-bold text-slate-900 dark:text-white">Blok Kupon Voucher Toko</h4>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold"
                                                  :class="(comp.data?.campaign_ids && comp.data.campaign_ids.length > 0) ? 'bg-rose-100 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400' : 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                                                  x-text="(comp.data?.campaign_ids && comp.data.campaign_ids.length > 0) ? comp.data.campaign_ids.length + ' Kupon Terpilih (Klik utk Edit)' : 'Semua Kupon Aktif (Klik utk Edit)'"></span>
                                        </div>
                                        <p class="text-xs text-slate-400 mt-0.5">Menampilkan voucher diskon pilihan pembeli. Klik blok ini untuk memilih kupon mana saja yang tampil.</p>
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
            <div x-show="device === 'mobile'" style="display: none;" class="w-full max-w-[375px] bg-white dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] shadow-2xl rounded-3xl sm:rounded-[2.5rem] overflow-hidden flex flex-col relative min-h-[650px] sm:min-h-[750px] shrink-0 mb-12 outline sm:outline-8 outline-slate-200/60 dark:outline-slate-800">
                
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
                <div class="h-32 bg-gradient-to-r from-sky-700 via-sky-800 to-slate-900 text-white p-4 flex items-end relative overflow-hidden group/mheader">
                    <div class="absolute inset-0 bg-cover bg-center transition-all duration-300" :style="getBannerStyle(headerBanner)" :class="headerBanner ? 'opacity-90' : 'opacity-20'"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent pointer-events-none"></div>
                    
                    <!-- Mobile Header Banner Action Buttons -->
                    <div class="absolute top-3 right-3 z-20 flex items-center gap-1.5">
                        <button type="button" @click="openBannerModal()" class="cursor-pointer bg-sky-600/80 hover:bg-sky-500 backdrop-blur-md text-white px-2.5 py-1 rounded-lg text-[11px] font-bold flex items-center gap-1 border border-sky-400/40 transition-all shadow-md" title="Atur Banner Toko">
                            <span class="material-symbols-outlined text-[14px] text-white">palette</span>
                            <span>Banner</span>
                        </button>
                        <button type="button" x-show="headerBanner" @click="clearHeaderBanner()" class="bg-rose-500/80 hover:bg-rose-600 text-white p-1 rounded-lg text-xs font-bold flex items-center justify-center border border-white/20 transition-all shadow-md" title="Hapus Settingan Banner">
                            <span class="material-symbols-outlined text-[14px]">delete</span>
                        </button>
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

                <!-- Mobile Bio Link Preview Container (Visible in profile & hybrid modes) -->
                <div x-show="storeMode === 'profile' || storeMode === 'hybrid'" class="p-3 pb-1">
                    <div class="w-full space-y-2 bg-slate-50/70 dark:bg-[#111726]/70 p-3 rounded-2xl border border-slate-200/80 dark:border-[#222f49]">
                        <div class="flex items-center justify-between px-1 mb-0.5">
                            <span class="text-[10px] font-bold text-teal-600 dark:text-teal-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">link</span> Tombol Bio Link
                            </span>
                            <button type="button" @click="sidebarTab = 'biolink'; mobileTab = 'biolink'" class="text-[10px] font-semibold text-slate-500 hover:text-teal-600 flex items-center gap-0.5 cursor-pointer">
                                <span class="material-symbols-outlined text-[13px]">edit</span> Edit
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="(link, lIdx) in profileLinks" :key="lIdx">
                                <div :class="link.layout === 'grid' ? 'col-span-1' : 'col-span-2'">
                                    <!-- 1. GRID (2 Kolom) -->
                                    <template x-if="link.layout === 'grid'">
                                        <div class="flex flex-col bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-all cursor-pointer text-left h-full"
                                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'">
                                            <div class="w-full aspect-square bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                                <template x-if="link.image">
                                                    <img :src="link.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!link.image">
                                                    <div class="w-full h-full flex items-center justify-center text-white" :style="`background: ${link.color || '#0284c7'};`">
                                                        <span class="material-symbols-outlined text-2xl opacity-90" x-text="link.icon || 'link'"></span>
                                                    </div>
                                                </template>
                                                <template x-if="link.badge">
                                                    <span class="absolute top-1 left-1 px-1 py-0.2 rounded bg-black/60 backdrop-blur-md text-white text-[7px] font-black uppercase tracking-wider" x-text="link.badge"></span>
                                                </template>
                                            </div>
                                            <div class="p-2 flex flex-col flex-1 justify-between bg-white dark:bg-[#161b22]">
                                                <div>
                                                    <h4 class="font-bold text-[10px] text-slate-900 dark:text-white line-clamp-2 leading-tight" x-text="link.title || 'Judul Link'"></h4>
                                                    <p x-show="link.subtitle" class="text-[9px] font-bold mt-0.5 truncate" :style="`color: ${link.color || '#0284c7'};`" x-text="link.subtitle"></p>
                                                    <p x-show="link.description" class="text-[8px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2 leading-tight" x-text="link.description"></p>
                                                </div>
                                                <div class="mt-1.5 pt-1 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-[8px] font-bold text-slate-400">
                                                    <span>Buka</span>
                                                    <span class="material-symbols-outlined text-[10px]">arrow_forward</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- 2. CARD (Gambar Besar 1 Kolom Penuh) -->
                                    <template x-if="link.layout === 'card'">
                                        <div class="flex flex-col bg-white dark:bg-[#161b22] border border-slate-200 dark:border-[#30363d] rounded-xl overflow-hidden shadow-xs hover:shadow-md transition-all cursor-pointer text-left"
                                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'">
                                            <div class="w-full aspect-[16/9] bg-slate-100 dark:bg-slate-800 relative overflow-hidden flex items-center justify-center">
                                                <template x-if="link.image">
                                                    <img :src="link.image" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!link.image">
                                                    <div class="w-full h-full flex items-center justify-center text-white" :style="`background: ${link.color || '#0284c7'};`">
                                                        <span class="material-symbols-outlined text-3xl opacity-90" x-text="link.icon || 'link'"></span>
                                                    </div>
                                                </template>
                                                <template x-if="link.badge">
                                                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-black/60 backdrop-blur-md text-white text-[8px] font-black uppercase tracking-wider" x-text="link.badge"></span>
                                                </template>
                                            </div>
                                            <div class="p-2.5 bg-white dark:bg-[#161b22]">
                                                <h4 class="font-extrabold text-[11px] text-slate-900 dark:text-white leading-snug truncate" x-text="link.title || 'Judul Link'"></h4>
                                                <p x-show="link.subtitle" class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1" x-text="link.subtitle"></p>
                                                <p x-show="link.description" class="text-[9px] text-slate-600 dark:text-slate-300 mt-0.5 line-clamp-2 leading-tight" x-text="link.description"></p>
                                                <div class="mt-2 w-full py-1 px-2.5 rounded-lg text-white font-bold text-[9px] text-center flex items-center justify-center gap-1 shadow-xs"
                                                     :style="`background: ${link.color || '#0284c7'};`">
                                                    <span>Buka Tautan</span>
                                                    <span class="material-symbols-outlined text-[11px]">arrow_forward</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- 3. LIST (Default 1 Baris) -->
                                    <template x-if="!link.layout || link.layout === 'list'">
                                        <div class="flex items-center gap-2 w-full px-3 py-2 rounded-xl text-white font-bold text-[11px] shadow-xs cursor-pointer relative overflow-hidden"
                                             :style="`background: ${link.color || '#0284c7'};`"
                                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'">
                                            <template x-if="link.badge">
                                                <span class="absolute top-0.5 right-1.5 px-1 py-0.2 rounded-full bg-white/25 backdrop-blur-md text-[7px] font-black uppercase tracking-wider" x-text="link.badge"></span>
                                            </template>
                                            <template x-if="link.image">
                                                <img :src="link.image" class="w-8 h-8 rounded-lg object-cover shrink-0 border border-white/20">
                                            </template>
                                            <template x-if="!link.image">
                                                <div class="w-7 h-7 rounded-lg bg-white/15 flex items-center justify-center shrink-0">
                                                    <span class="material-symbols-outlined text-[15px] opacity-90" x-text="link.icon || 'link'"></span>
                                                </div>
                                            </template>
                                            <div class="flex-1 min-w-0 pr-1 text-left">
                                                <span class="block truncate leading-tight" x-text="link.title || link.url || 'Tombol Link'"></span>
                                                <span x-show="link.subtitle" class="block text-[9px] opacity-80 truncate font-normal mt-0.5" x-text="link.subtitle"></span>
                                                <span x-show="link.description" class="block text-[8px] opacity-75 truncate font-normal mt-0.5" x-text="link.description"></span>
                                            </div>
                                            <span class="material-symbols-outlined text-[13px] opacity-60 shrink-0">arrow_forward</span>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div x-show="profileLinks.length === 0" 
                             @click="sidebarTab = 'biolink'; mobileTab = 'biolink'"
                             class="p-3 rounded-xl border border-dashed border-teal-300 dark:border-teal-800/60 bg-teal-50/40 dark:bg-teal-950/20 text-center cursor-pointer">
                            <span class="material-symbols-outlined text-teal-500 text-[18px] mb-0.5">add_link</span>
                            <p class="text-[11px] font-bold text-teal-700 dark:text-teal-300">Belum ada tombol link</p>
                            <p class="text-[10px] text-teal-600/70 dark:text-teal-400/70 mt-0.5">Ketuk untuk menambah link</p>
                        </div>
                    </div>

                    <!-- Pure Profile Mode Notice -->
                    <div x-show="storeMode === 'profile'" class="p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 text-[11px] flex items-center gap-2 mt-2">
                        <span class="material-symbols-outlined text-[16px] text-amber-500 shrink-0">info</span>
                        <p class="leading-tight">Mode Bio Link: Widget etalase toko di bawah tidak tampil di publik.</p>
                    </div>
                </div>

                <!-- Mobile Canvas Droppable Area -->
                <div id="mobile-canvas" x-show="storeMode !== 'profile'" class="p-3 space-y-3 pb-16 min-h-[300px] overflow-y-auto">
                    <template x-for="(comp, index) in activeComponents" :key="comp.id">
                        <div class="canvas-widget-item relative transition-all rounded-xl" :data-id="comp.id">
                            <!-- Action Buttons Mobile (Always visible) -->
                            <div class="absolute top-2 right-2 z-30 flex items-center gap-0.5 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border border-slate-200 dark:border-[#222f49] rounded-lg px-1 py-0.5 shadow-sm">
                                <button type="button" @click.stop="moveUp(index)" :disabled="index === 0" :class="index === 0 ? 'opacity-30 cursor-not-allowed text-slate-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" class="w-5 h-5 rounded flex items-center justify-center transition-colors" title="Naik">
                                    <span class="material-symbols-outlined text-[13px]">arrow_upward</span>
                                </button>
                                <button type="button" @click.stop="moveDown(index)" :disabled="index === activeComponents.length - 1" :class="index === activeComponents.length - 1 ? 'opacity-30 cursor-not-allowed text-slate-400' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'" class="w-5 h-5 rounded flex items-center justify-center transition-colors" title="Turun">
                                    <span class="material-symbols-outlined text-[13px]">arrow_downward</span>
                                </button>
                                <div class="drag-handle w-5 h-5 rounded flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 cursor-grab active:cursor-grabbing transition-colors" title="Geser Urutan (Tahan & Tarik)">
                                    <span class="material-symbols-outlined text-[14px]">drag_indicator</span>
                                </div>
                                <div class="w-px h-3 bg-slate-200 dark:bg-slate-700 mx-0.5"></div>
                                <button type="button" @click.stop="openSettings(index)" class="w-5 h-5 rounded flex items-center justify-center text-sky-600 dark:text-sky-400 hover:bg-sky-50 dark:hover:bg-sky-950/50" title="Pengaturan Blok (Gir)">
                                    <span class="material-symbols-outlined text-[13px]">settings</span>
                                </button>
                                <button type="button" @click.stop="removeComponent(index)" class="w-5 h-5 rounded flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/50" title="Hapus Blok">
                                    <span class="material-symbols-outlined text-[13px]">delete</span>
                                </button>
                            </div>

                            <template x-if="comp.type === 'banner'">
                                <div @click="openSettings(index)" class="relative bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl overflow-hidden text-center h-28 flex flex-col items-center justify-center cursor-pointer group/mbanner">
                                    <template x-if="comp.data?.images && comp.data.images.length > 0 && comp.data.images[0].image_url">
                                        <div class="absolute inset-0 w-full h-full">
                                            <img :src="comp.data.images[0].image_url" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover/mbanner:opacity-100 transition-opacity">
                                                <span class="text-[11px] font-bold text-white flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">edit</span> Edit Slide
                                                </span>
                                            </div>
                                            <div class="absolute bottom-1 left-1 bg-black/60 text-[9px] text-white px-1.5 py-0.5 rounded font-mono truncate max-w-[140px]" x-text="comp.data.images[0].link ? comp.data.images[0].link : 'Belum ada link'"></div>
                                        </div>
                                    </template>
                                    <template x-if="!comp.data?.images || comp.data.images.length === 0 || !comp.data.images[0].image_url">
                                        <div class="p-2">
                                            <span class="material-symbols-outlined text-sky-500 text-[20px] mb-0.5">view_carousel</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-white block">Banner Slide</span>
                                            <span class="text-[9px] text-slate-400">Klik untuk upload & atur link</span>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="comp.type === 'single_image'">
                                <div @click="openSettings(index)" class="relative bg-slate-50 dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-xl overflow-hidden text-center h-36 flex flex-col items-center justify-center cursor-pointer group/msingle">
                                    <template x-if="comp.data?.image_url">
                                        <div class="absolute inset-0 w-full h-full">
                                            <img :src="comp.data.image_url" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover/msingle:opacity-100 transition-opacity">
                                                <span class="text-[11px] font-bold text-white flex items-center gap-1">
                                                    <span class="material-symbols-outlined text-[14px]">edit</span> Edit Banner
                                                </span>
                                            </div>
                                            <div class="absolute bottom-1 left-1 bg-black/60 text-[9px] text-white px-1.5 py-0.5 rounded font-mono truncate max-w-[140px]" x-text="comp.data.link ? comp.data.link : 'Belum ada link'"></div>
                                        </div>
                                    </template>
                                    <template x-if="!comp.data?.image_url">
                                        <div class="p-2">
                                            <span class="material-symbols-outlined text-emerald-500 text-[20px] mb-0.5">image</span>
                                            <span class="text-xs font-bold text-slate-800 dark:text-white block">Banner Gambar</span>
                                            <span class="text-[9px] text-slate-400">Klik untuk upload & atur link</span>
                                        </div>
                                    </template>
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
                    <button @click="applyCrop()" type="button" class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white rounded-xl text-sm font-bold transition-all active:scale-95 flex items-center gap-2 cursor-pointer" :class="{'opacity-50 cursor-not-allowed': isCropping}" :disabled="isCropping">
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
                            <label class="cursor-pointer bg-sky-100 hover:bg-sky-200 text-sky-600 px-4 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center transition-colors" title="Upload Gambar dari Komputer">
                                <span class="material-symbols-outlined text-[18px]">upload</span>
                                <input type="file" class="hidden" accept="image/*" @change="openCropper($event, editingData.data, 'image_url', false, 0)">
                            </label>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">Masukkan URL gambar atau upload dari perangkat Anda (Maks 2MB).</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Link Tujuan Ketika Gambar Diklik</span>
                            <span class="text-[10px] text-slate-400 font-normal">Opsional</span>
                        </label>
                        <div class="relative">
                            <input type="text" x-model="editingData?.data?.link" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl pl-9 pr-4 py-2.5 text-sm text-slate-800 dark:text-slate-200 focus:outline-none focus:border-sky-500" placeholder="https://... atau /products/nama-produk">
                            <span class="material-symbols-outlined absolute left-2.5 top-3 text-[18px] text-slate-400">link</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1">
                            Bisa berupa link eksternal (misal: <code class="text-sky-600 font-mono">https://wa.me/...</code>) atau link halaman produk toko.
                        </p>
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

                    <!-- Judul -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Judul Flash Sale</label>
                        <input type="text" x-model="editingData.data.title" placeholder="Contoh: Flash Sale Hari Ini!" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                    </div>

                    <!-- Batas Waktu -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Batas Waktu (Countdown Timer)</label>
                        <input type="datetime-local" x-model="editingData.data.end_date" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-4 py-2.5 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                    </div>

                    <!-- Diskon % -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Persentase Diskon yang Ditampilkan (%)</label>
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="editingData.data.discount" min="1" max="99" class="w-24 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-amber-500">
                            <span class="text-xs text-slate-500">% (badge diskon pada kartu produk)</span>
                        </div>
                    </div>

                    <!-- Pilih Produk -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Produk yang Masuk Flash Sale</label>
                            <span class="text-[10px] text-slate-400">Centang produk yang ingin ditampilkan</span>
                        </div>

                        @php
                            $flashProducts = \App\Models\Product::where('store_id', $store->id)
                                ->where('is_active', true)
                                ->orderBy('name')
                                ->with(['images' => fn($q) => $q->where('is_main', true)->limit(1)])
                                ->get(['id', 'name', 'price']);
                        @endphp

                        @if($flashProducts->isEmpty())
                            <div class="p-4 bg-slate-50 dark:bg-[#0d1117] rounded-xl border border-dashed border-slate-200 dark:border-[#222f49] text-center">
                                <span class="material-symbols-outlined text-2xl text-slate-300">inventory_2</span>
                                <p class="text-xs text-slate-400 mt-1">Belum ada produk aktif.</p>
                            </div>
                        @else
                            <div class="space-y-1.5 max-h-56 overflow-y-auto pr-1 custom-scrollbar">
                                @foreach($flashProducts as $fp)
                                <label class="flex items-center gap-3 p-2.5 rounded-xl border cursor-pointer transition-all"
                                    :class="(editingData.data.product_ids || []).includes({{ $fp->id }}) ? 'border-amber-400 bg-amber-50 dark:bg-amber-950/30' : 'border-slate-200 dark:border-[#222f49] hover:border-amber-300'">
                                    <input type="checkbox" class="sr-only"
                                        :checked="(editingData.data.product_ids || []).includes({{ $fp->id }})"
                                        @change="
                                            if (!editingData.data.product_ids) editingData.data.product_ids = [];
                                            const i = editingData.data.product_ids.indexOf({{ $fp->id }});
                                            if (i > -1) { editingData.data.product_ids.splice(i, 1); }
                                            else { editingData.data.product_ids.push({{ $fp->id }}); }
                                        ">
                                    <div class="w-4 h-4 rounded border-2 flex-shrink-0 flex items-center justify-center transition-colors"
                                        :class="(editingData.data.product_ids || []).includes({{ $fp->id }}) ? 'bg-amber-500 border-amber-500 text-white' : 'border-slate-300 dark:border-slate-600'">
                                        <span class="material-symbols-outlined text-[11px]" x-show="(editingData.data.product_ids || []).includes({{ $fp->id }})">check</span>
                                    </div>
                                    @if($fp->images->first())
                                        <img src="{{ asset('storage/'.$fp->images->first()->image_path) }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-slate-200 dark:border-[#222f49]">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-[14px] text-slate-400">image</span>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $fp->name }}</p>
                                        <p class="text-[11px] text-slate-500">Rp {{ number_format($fp->price, 0, ',', '.') }}</p>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1.5 text-center"
                                x-text="'Dipilih: ' + (editingData.data.product_ids || []).length + ' dari {{ $flashProducts->count() }} produk'"></p>
                        @endif
                    </div>

                </div>
                
                <!-- Banner Settings -->
                <div x-show="editingData && editingData.type === 'banner'" class="space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Daftar Gambar Slide Banner</label>
                            <p class="text-[10px] text-slate-500">Tambahkan gambar slide dan link tujuannya</p>
                        </div>
                        <button type="button" @click="if(!editingData.data.images) editingData.data.images = []; editingData.data.images.push({image_url: '', link: ''})" class="text-[11px] bg-sky-100 text-sky-600 hover:bg-sky-200 px-2.5 py-1.5 rounded-lg font-bold flex items-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[14px]">add</span> Tambah Slide
                        </button>
                    </div>
                    
                    <template x-if="!editingData?.data?.images || editingData.data.images.length === 0">
                        <div class="p-6 bg-slate-50 dark:bg-[#0d1117] rounded-xl border border-dashed border-slate-200 dark:border-[#222f49] text-center text-xs text-slate-400">
                            <span class="material-symbols-outlined text-2xl text-slate-300 block mb-1">collections</span>
                            Belum ada slide gambar. Klik tombol <b>Tambah Slide</b> di atas.
                        </div>
                    </template>
                    
                    <div class="space-y-3 max-h-72 overflow-y-auto pr-2 custom-scrollbar">
                        <template x-for="(img, imgIdx) in editingData?.data?.images" :key="imgIdx">
                            <div class="p-3 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl relative group">
                                <button type="button" @click="editingData.data.images.splice(imgIdx, 1)" class="absolute top-2 right-2 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900/30 p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus Slide">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                                <div class="space-y-2.5 pr-6">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-1" x-text="'Slide #' + (imgIdx + 1) + ' Gambar'"></label>
                                        <div class="flex gap-2">
                                            <input type="text" x-model="img.image_url" class="flex-1 bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 focus:border-sky-500 focus:outline-none" placeholder="https://contoh.com/gambar.jpg">
                                            <label class="cursor-pointer bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 px-2.5 rounded-lg flex items-center justify-center transition-colors" title="Upload Gambar Slide">
                                                <span class="material-symbols-outlined text-[15px]">upload</span>
                                                <input type="file" class="hidden" accept="image/*" @change="openCropper($event, img, 'image_url', false, 2.5/1)">
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-500 mb-1">Link Tujuan Ketika Slide Ini Diklik (Opsional)</label>
                                        <div class="relative">
                                            <input type="text" x-model="img.link" class="w-full bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg pl-7 pr-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 focus:border-sky-500 focus:outline-none" placeholder="https://... atau /products/nama-produk">
                                            <span class="material-symbols-outlined absolute left-2 top-2 text-[14px] text-slate-400">link</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Voucher Settings — linked to Diskon/Voucher module -->
                <div x-show="editingData && editingData.type === 'voucher'" class="space-y-4">

                    <!-- Info box -->
                    <div class="bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800/50 rounded-xl p-3 text-[11px] text-rose-700 dark:text-rose-300">
                        <p class="font-bold mb-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[14px]">link</span>
                            Terhubung ke Modul Diskon &amp; Voucher
                        </p>
                        <p>Centang diskon/voucher dari daftar yang sudah Anda buat. Kode dan detail otomatis ditampilkan dari data tersebut.</p>
                    </div>

                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Voucher yang Ditampilkan</label>
                        <a href="{{ route('tenant.campaigns.create') }}" target="_blank" class="text-[10px] bg-rose-100 text-rose-600 hover:bg-rose-200 px-2 py-1 rounded font-bold flex items-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[12px]">add</span> Buat Baru
                        </a>
                    </div>

                    @php
                        $widgetCampaigns = \App\Models\Campaign::where('store_id', $store->id)
                            ->whereIn('status', ['active', 'scheduled'])
                            ->orderBy('name')->get();
                    @endphp

                    @if($widgetCampaigns->isEmpty())
                        <div class="p-4 bg-slate-50 dark:bg-[#0d1117] rounded-xl border border-dashed border-slate-200 dark:border-[#222f49] text-center">
                            <span class="material-symbols-outlined text-2xl text-slate-300 dark:text-slate-600">confirmation_number</span>
                            <p class="text-xs text-slate-400 mt-1">Belum ada voucher/diskon aktif.</p>
                            <a href="{{ route('tenant.campaigns.create') }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-xs font-bold text-rose-500">
                                <span class="material-symbols-outlined text-[14px]">add_circle</span> Buat Diskon / Voucher
                            </a>
                        </div>
                    @else
                        <div class="space-y-2 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($widgetCampaigns as $wc)
                            <label class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                :class="(editingData.data.campaign_ids || []).includes({{ $wc->id }}) ? 'border-rose-400 bg-rose-50 dark:bg-rose-950/30' : 'border-slate-200 dark:border-[#222f49] hover:border-rose-300'">
                                <input type="checkbox" class="sr-only"
                                    :checked="(editingData.data.campaign_ids || []).includes({{ $wc->id }})"
                                    @change="
                                        if (!editingData.data.campaign_ids) editingData.data.campaign_ids = [];
                                        const idx = editingData.data.campaign_ids.indexOf({{ $wc->id }});
                                        if (idx > -1) { editingData.data.campaign_ids.splice(idx, 1); }
                                        else { editingData.data.campaign_ids.push({{ $wc->id }}); }
                                    ">
                                <div class="mt-0.5 w-4 h-4 rounded border-2 flex-shrink-0 flex items-center justify-center transition-colors"
                                    :class="(editingData.data.campaign_ids || []).includes({{ $wc->id }}) ? 'bg-rose-500 border-rose-500 text-white' : 'border-slate-300 dark:border-slate-600'">
                                    <span class="material-symbols-outlined text-[11px]" x-show="(editingData.data.campaign_ids || []).includes({{ $wc->id }})">check</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $wc->name }}</span>
                                        @if($wc->code)
                                            <span class="text-[10px] font-mono bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded">{{ $wc->code }}</span>
                                        @endif
                                        <span class="text-[10px] font-bold {{ $wc->type === 'voucher' ? 'text-rose-600 bg-rose-100' : 'text-amber-600 bg-amber-100' }} px-1.5 py-0.5 rounded">
                                            {{ $wc->type === 'voucher' ? 'Voucher' : 'Diskon' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        Potongan: <b>{{ $wc->discount_type === 'percentage' ? $wc->discount_value.'%' : 'Rp '.number_format($wc->discount_value,0,',','.') }}</b>
                                        @if($wc->minimum_spend > 0)· Min. Rp {{ number_format($wc->minimum_spend,0,',','.') }}@endif
                                        · s/d {{ \Carbon\Carbon::parse($wc->end_date)->format('d M Y') }}
                                    </p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-slate-400 text-center">
                            <a href="{{ route('tenant.campaigns.index') }}" target="_blank" class="text-rose-500 hover:underline font-bold">Kelola semua Diskon &amp; Voucher →</a>
                        </p>
                    @endif
                </div>

            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#161f33] flex items-center justify-end gap-3 shrink-0 rounded-b-2xl">
                <button @click="closeSettings()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                <button @click="saveSettings()" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs font-bold transition-all active:scale-95 cursor-pointer">Simpan Pengaturan</button>
            </div>
        </div>
    </div>

    <!-- Store Header Banner Modal (Warna Gradien, Warna Biasa, Gambar & Reset) -->
    <div x-show="isBannerModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div x-show="isBannerModalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeBannerModal()"></div>
        
        <!-- Modal Content -->
        <div x-show="isBannerModalOpen" x-transition.scale.95 class="bg-white dark:bg-[#111726] rounded-2xl shadow-2xl w-full max-w-xl relative z-10 overflow-hidden flex flex-col max-h-[92vh]">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-[#222f49] flex items-center justify-between shrink-0 bg-slate-50/50 dark:bg-[#161f33]">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">palette</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white">Pengaturan Banner Header Toko</h3>
                        <p class="text-[11px] text-slate-400">Pilih warna gradien, warna biasa, upload foto, atau hapus settingan</p>
                    </div>
                </div>
                <button type="button" @click="closeBannerModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Tab Switcher -->
            <div class="flex border-b border-slate-200 dark:border-[#222f49] px-6 bg-slate-50/30 dark:bg-[#131b2e] shrink-0">
                <button type="button" @click="bannerTab = 'gradient'" :class="bannerTab === 'gradient' ? 'border-sky-500 text-sky-600 dark:border-sky-400 dark:text-sky-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'" class="px-4 py-3 border-b-2 text-xs flex items-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-[16px]">gradient</span> Warna Gradien
                </button>
                <button type="button" @click="bannerTab = 'solid'" :class="bannerTab === 'solid' ? 'border-sky-500 text-sky-600 dark:border-sky-400 dark:text-sky-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'" class="px-4 py-3 border-b-2 text-xs flex items-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-[16px]">format_color_fill</span> Warna Biasa
                </button>
                <button type="button" @click="bannerTab = 'image'" :class="bannerTab === 'image' ? 'border-sky-500 text-sky-600 dark:border-sky-400 dark:text-sky-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'" class="px-4 py-3 border-b-2 text-xs flex items-center gap-1.5 transition-all">
                    <span class="material-symbols-outlined text-[16px]">image</span> Gambar Foto
                </button>
            </div>

            <!-- Body (Scrollable) -->
            <div class="p-6 space-y-5 overflow-y-auto flex-1 custom-scrollbar">
                
                <!-- Live Mini Preview -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-300 mb-1.5">Live Preview Banner Toko</label>
                    <div class="w-full h-28 rounded-2xl relative overflow-hidden flex items-end p-4 border border-slate-200 dark:border-[#222f49] transition-all duration-300" :style="getBannerStyle(previewBanner)">
                        <div class="absolute inset-0 bg-slate-950/40 pointer-events-none"></div>
                        <div class="relative z-10 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center font-bold text-white text-sm shrink-0">
                                {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-extrabold text-sm text-white truncate">{{ $store->name ?? 'Toko Anda' }}</h4>
                                <span class="text-[10px] text-slate-200">Toko Resmi Terverifikasi</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 1: Warna Gradien -->
                <div x-show="bannerTab === 'gradient'" class="space-y-4 relative">
                    @if(!$store->isPro())
                    <div class="absolute inset-0 z-20 bg-white/70 dark:bg-[#111726]/80 backdrop-blur-[2px] flex flex-col items-center justify-center rounded-2xl p-4 text-center border border-slate-200 dark:border-slate-800">
                        <span class="material-symbols-outlined text-4xl text-amber-500 mb-2">stars</span>
                        <h4 class="font-bold text-slate-800 dark:text-white mb-1">Fitur Toko PRO</h4>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mb-4 max-w-xs">Gunakan dekorasi banner gradien eksklusif untuk tampilan toko yang lebih premium.</p>
                        <a href="{{ route('tenant.pro.index') }}" class="px-4 py-2 bg-sky-500 hover:bg-sky-600 text-white rounded-xl text-xs font-bold transition-all active:scale-95">Upgrade ke PRO</a>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Pilihan Gradien Populer</label>
                        <span class="text-[10px] text-slate-400">Klik untuk memilih gradien</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        <template x-for="(grad, gIdx) in presetGradients" :key="gIdx">
                            <button type="button" @click="selectPreset(grad.value)" :class="previewBanner === grad.value ? 'ring-2 ring-sky-500 ring-offset-2 dark:ring-offset-[#111726]' : 'border border-slate-200/60 dark:border-[#222f49]'" class="h-16 rounded-xl relative overflow-hidden flex flex-col justify-end p-2 text-left group transition-all hover:scale-[1.02]" :style="`background: ${grad.value};`">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                                <span class="relative z-10 text-[11px] font-bold text-white drop-shadow-sm truncate" x-text="grad.name"></span>
                                <span x-show="previewBanner === grad.value" class="absolute top-1.5 right-1.5 w-5 h-5 rounded-full bg-white text-sky-600 flex items-center justify-center shadow-md">
                                    <span class="material-symbols-outlined text-[13px] font-black">check</span>
                                </span>
                            </button>
                        </template>
                    </div>

                    <!-- Custom Gradient Generator -->
                    <div class="pt-3 border-t border-slate-200 dark:border-[#222f49] space-y-2.5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Kombinasi Gradien Sendiri</label>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <span class="block text-[10px] text-slate-400 mb-1">Warna Awal</span>
                                <div class="flex items-center gap-2 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-2.5 py-1.5">
                                    <input type="color" x-model="customGradStart" @input="updateCustomGradient()" class="w-6 h-6 rounded cursor-pointer border-0 bg-transparent">
                                    <input type="text" x-model="customGradStart" @input="updateCustomGradient()" class="w-full text-xs font-mono text-slate-800 dark:text-slate-200 bg-transparent outline-none">
                                </div>
                            </div>
                            <div class="flex-1">
                                <span class="block text-[10px] text-slate-400 mb-1">Warna Akhir</span>
                                <div class="flex items-center gap-2 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-2.5 py-1.5">
                                    <input type="color" x-model="customGradEnd" @input="updateCustomGradient()" class="w-6 h-6 rounded cursor-pointer border-0 bg-transparent">
                                    <input type="text" x-model="customGradEnd" @input="updateCustomGradient()" class="w-full text-xs font-mono text-slate-800 dark:text-slate-200 bg-transparent outline-none">
                                </div>
                            </div>
                            <div class="w-28">
                                <span class="block text-[10px] text-slate-400 mb-1">Arah Sudut</span>
                                <select x-model="customGradDeg" @change="updateCustomGradient()" class="w-full bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-2 py-2 text-xs text-slate-800 dark:text-slate-200 outline-none">
                                    <option value="90deg">90° (Kiri ke Kanan)</option>
                                    <option value="135deg">135° (Diagonal)</option>
                                    <option value="180deg">180° (Atas ke Bawah)</option>
                                    <option value="45deg">45° (Diagonal Balik)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Warna Biasa (Solid) -->
                <div x-show="bannerTab === 'solid'" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-700 dark:text-slate-300">Pilihan Warna Biasa (Solid)</label>
                        <span class="text-[10px] text-slate-400">Pilihan warna minimalis elegan</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                        <template x-for="(col, cIdx) in presetSolidColors" :key="cIdx">
                            <button type="button" @click="selectPreset(col.value)" :class="previewBanner === col.value ? 'ring-2 ring-sky-500 ring-offset-2 dark:ring-offset-[#111726]' : 'border border-slate-200/60 dark:border-[#222f49]'" class="h-14 rounded-xl relative overflow-hidden flex flex-col justify-end p-2 text-left group transition-all hover:scale-[1.02]" :style="`background-color: ${col.value};`">
                                <span class="relative z-10 text-[10px] font-bold text-white drop-shadow-sm truncate" x-text="col.name"></span>
                                <span x-show="previewBanner === col.value" class="absolute top-1 right-1 w-4 h-4 rounded-full bg-white text-sky-600 flex items-center justify-center shadow-md">
                                    <span class="material-symbols-outlined text-[12px] font-black">check</span>
                                </span>
                            </button>
                        </template>
                    </div>

                    <!-- Custom Solid Color -->
                    <div class="pt-3 border-t border-slate-200 dark:border-[#222f49] space-y-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Pilih Warna Bebas (Color Picker)</label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2.5 bg-slate-50 dark:bg-[#0d1117] border border-slate-200 dark:border-[#222f49] rounded-xl px-3.5 py-2 flex-1">
                                <input type="color" x-model="customSolidColor" @input="previewBanner = customSolidColor" class="w-8 h-8 rounded-lg cursor-pointer border-0 bg-transparent">
                                <input type="text" x-model="customSolidColor" @input="previewBanner = customSolidColor" class="w-full text-xs font-mono font-bold text-slate-800 dark:text-slate-200 bg-transparent outline-none uppercase" placeholder="#0f172a">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Upload Gambar -->
                <div x-show="bannerTab === 'image'" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Upload Gambar dari Perangkat</label>
                        <label class="cursor-pointer bg-sky-50 dark:bg-sky-950/30 hover:bg-sky-100 dark:hover:bg-sky-900/40 text-sky-600 dark:text-sky-300 border-2 border-dashed border-sky-300 dark:border-sky-800 rounded-xl p-5 flex flex-col items-center justify-center gap-1.5 transition-all text-center">
                            <span class="material-symbols-outlined text-3xl text-sky-500">cloud_upload</span>
                            <span class="text-xs font-bold">Pilih File Foto Banner (Maks 5MB)</span>
                            <span class="text-[10px] text-slate-400">Rasio rekomendasi 3:1 (misal 1200x400 px) dengan fitur crop & potong rapi</span>
                            <input type="file" class="hidden" accept="image/*" @change="uploadHeaderBannerFromModal($event)">
                        </label>
                    </div>

                    <!-- Preset Images -->
                    <div class="pt-3 border-t border-slate-200 dark:border-[#222f49]">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Atau Gunakan Gambar Wallpaper Bawaan</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                            <template x-for="(img, iIdx) in presetImages" :key="iIdx">
                                <button type="button" @click="selectPreset(img.url)" :class="previewBanner === img.url ? 'ring-2 ring-sky-500 ring-offset-2 dark:ring-offset-[#111726]' : 'border border-slate-200 dark:border-[#222f49]'" class="h-16 rounded-xl relative overflow-hidden group transition-all text-left">
                                    <img :src="img.url" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                    <div class="absolute inset-0 bg-black/40 flex items-end p-2">
                                        <span class="text-[10px] font-bold text-white drop-shadow-sm truncate" x-text="img.name"></span>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="px-6 py-3.5 border-t border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#161f33] flex items-center justify-between gap-3 shrink-0">
                <!-- Tombol Hapus Settingan Banner -->
                <button type="button" @click="clearHeaderBanner()" class="px-3.5 py-2 rounded-xl text-rose-500 hover:bg-rose-100/70 dark:hover:bg-rose-950/40 text-xs font-bold transition-colors flex items-center gap-1.5 cursor-pointer" title="Hapus settingan banner dan kembali ke default">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                    <span>Hapus Settingan Banner</span>
                </button>

                <div class="flex items-center gap-2">
                    <button type="button" @click="closeBannerModal()" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Batal</button>
                    <button type="button" @click="applyBannerModal()" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs font-bold transition-all active:scale-95 cursor-pointer">Terapkan Banner</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- INTERACTIVE LIVE PREVIEW MODAL (IN-APP OVERLAY SIMULATOR)                   -->
    <!-- ========================================================================= -->
    <div x-show="isPreviewModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-98"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-98"
         @keydown.escape.window="closePreviewModal()"
         class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex flex-col overflow-hidden"
         style="display: none;">
        
        <!-- Modal Top Control Bar -->
        <div class="h-14 bg-slate-900 border-b border-slate-800 px-3 sm:px-6 flex items-center justify-between gap-3 shrink-0 text-white select-none">
            
            <!-- Left: Info & Mode Badge -->
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-xl bg-slate-800 text-slate-300 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm font-bold text-white flex items-center gap-2 truncate">
                        <span>Live Preview Halaman</span>
                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full uppercase tracking-wider shrink-0"
                              :class="storeMode === 'profile' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : (storeMode === 'hybrid' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-slate-700 text-slate-200 border border-slate-600')"
                              x-text="storeMode === 'profile' ? 'Bio Link' : (storeMode === 'hybrid' ? 'Hybrid' : 'Toko Digital')">
                        </span>
                    </h3>
                    <p class="text-[10px] text-slate-400 truncate hidden sm:block font-mono">
                        {{ url('/' . ($store->slug ?? '')) }}
                    </p>
                </div>
            </div>

            <!-- Center: Device Switcher & Refresh Button -->
            <div class="flex items-center gap-1 p-1 bg-slate-800/90 rounded-xl border border-slate-700">
                <button type="button" @click="previewDevice = 'mobile'" 
                        :class="previewDevice === 'mobile' ? 'bg-white text-slate-900 font-bold' : 'text-slate-400 hover:text-white font-medium'" 
                        class="px-2.5 sm:px-3 py-1 rounded-lg text-xs transition-all flex items-center gap-1 cursor-pointer">
                    <span class="material-symbols-outlined text-[15px]">smartphone</span>
                    <span class="hidden sm:inline">Mobile</span>
                </button>
                <button type="button" @click="previewDevice = 'desktop'" 
                        :class="previewDevice === 'desktop' ? 'bg-white text-slate-900 font-bold' : 'text-slate-400 hover:text-white font-medium'" 
                        class="px-2.5 sm:px-3 py-1 rounded-lg text-xs transition-all flex items-center gap-1 cursor-pointer">
                    <span class="material-symbols-outlined text-[15px]">desktop_windows</span>
                    <span class="hidden sm:inline">Desktop</span>
                </button>
                <div class="h-4 w-px bg-slate-700 mx-0.5"></div>
                <button type="button" @click="refreshPreview()" 
                        class="p-1 rounded-lg text-slate-400 hover:text-white transition-colors cursor-pointer" 
                        title="Muat Ulang / Refresh Preview">
                    <span class="material-symbols-outlined text-[16px]">refresh</span>
                </button>
            </div>

            <!-- Right: Tab Baru & Close Button -->
            <div class="flex items-center gap-2">
                @if($store && $store->slug)
                <a href="{{ route('store.show', $store->slug) }}" target="_blank" 
                   class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold border border-slate-700 transition-all"
                   title="Buka di tab browser baru">
                    <span>Tab Baru</span>
                    <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                </a>
                @endif
                <button type="button" @click="closePreviewModal()" 
                        class="px-3 py-1.5 rounded-xl bg-rose-500/20 hover:bg-rose-500 text-rose-300 hover:text-white text-xs font-bold transition-all flex items-center gap-1 border border-rose-500/30 cursor-pointer"
                        title="Tutup Preview (Tekan ESC)">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                    <span class="hidden sm:inline">Tutup</span>
                </button>
            </div>

        </div>

        <!-- Modal Body (Simulation Canvas) -->
        <div class="flex-1 overflow-auto p-3 sm:p-6 flex items-center justify-center relative custom-scrollbar">
            
            <!-- Mobile Frame Viewport (390px iPhone Frame) -->
            <div x-show="previewDevice === 'mobile'" 
                 class="w-full max-w-[390px] h-[820px] max-h-[88vh] bg-slate-950 rounded-[44px] p-3 shadow-2xl ring-1 ring-white/15 flex flex-col relative shrink-0 transition-all duration-300">
                <!-- Dynamic Island / Speaker Notch -->
                <div class="w-28 h-4 bg-black rounded-full mx-auto mb-2 shrink-0 flex items-center justify-center">
                    <div class="w-2.5 h-2.5 rounded-full bg-slate-900 border border-slate-800"></div>
                </div>
                <!-- Iframe Container -->
                <div class="flex-1 w-full bg-white dark:bg-[#090d16] rounded-[32px] overflow-hidden relative shadow-inner">
                    <iframe id="preview-iframe-mobile" :src="isPreviewModalOpen ? previewUrl : 'about:blank'" class="w-full h-full border-0"></iframe>
                </div>
                <!-- Bottom Bar Indicator -->
                <div class="w-32 h-1 bg-slate-700 rounded-full mx-auto mt-2 shrink-0"></div>
            </div>

            <!-- Desktop Frame Viewport (Browser Frame) -->
            <div x-show="previewDevice === 'desktop'" 
                 class="w-full max-w-5xl h-[820px] max-h-[88vh] bg-slate-900 rounded-2xl shadow-2xl ring-1 ring-white/15 flex flex-col overflow-hidden transition-all duration-300">
                <!-- Browser Window Header -->
                <div class="h-9 bg-slate-800/90 border-b border-slate-700 px-4 flex items-center gap-3 shrink-0">
                    <div class="flex gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                    </div>
                    <div class="flex-1 flex justify-center">
                        <div class="bg-slate-950/80 border border-slate-700 text-[11px] px-4 py-1 rounded-lg text-slate-400 w-[60%] flex items-center justify-center gap-1.5 font-mono truncate">
                            <span class="material-symbols-outlined text-[13px] text-emerald-400">lock</span>
                            <span x-text="previewUrl"></span>
                        </div>
                    </div>
                </div>
                <!-- Iframe Container -->
                <div class="flex-1 w-full bg-white dark:bg-[#090d16] overflow-hidden relative">
                    <iframe id="preview-iframe-desktop" :src="isPreviewModalOpen ? previewUrl : 'about:blank'" class="w-full h-full border-0"></iframe>
                </div>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('appearanceEditor', () => ({
            device: 'mobile',
            mobileTab: 'biolink',
            sidebarTab: 'biolink',
            expandedLinkIndex: null,
            activeComponents: {!! json_encode(
                is_array($store->appearance_data)
                    ? array_values(array_filter($store->appearance_data, fn($v, $k) => is_numeric($k) && is_array($v) && !empty($v['type']), ARRAY_FILTER_USE_BOTH))
                    : []
            ) !!},
            hasSavedAppearance: {{ $store->appearance_data !== null ? 'true' : 'false' }},
            headerBanner: {!! json_encode($store->banner ?? '') !!},
            isSaving: false,
            hasUnsavedChanges: false,
            draggedItem: null,
            sortableMobile: null,
            sortableDesktop: null,

            // Store Mode & Bio Link state
            storeMode: '{{ $store->store_mode ?? 'store' }}',
            profileLinks: {!! json_encode(!empty($store->profile_links) && is_array($store->profile_links) ? array_values($store->profile_links) : []) !!},
            isSavingLinks: false,
            toast: { show: false, message: '' },

            // Live Preview Modal state
            isPreviewModalOpen: false,
            previewDevice: 'mobile',
            previewUrl: '{{ route('store.show', $store->slug ?? '') }}',

            // Voucher Placement state
            voucherPlacement: {!! json_encode((is_array($store->appearance_data ?? null) ? ($store->appearance_data['voucher_placement'] ?? []) : []) + ['header' => '', 'product_page' => '', 'checkout' => '']) !!},
            isSavingVoucher: false,
            voucherSaveMsg: '',
            
            // Cropper state
            isCropperModalOpen: false,
            cropperInstance: null,
            cropperTarget: null,
            cropperAspectRatio: 3 / 1,
            isCropping: false,
            
            isSettingsModalOpen: false,
            editingIndex: -1,
            editingData: null,

            // Header Banner Settings Modal
            isBannerModalOpen: false,
            bannerTab: 'gradient',
            previewBanner: '',
            customGradStart: '#0284c7',
            customGradEnd: '#06b6d4',
            customGradDeg: '135deg',
            customSolidColor: '#0f172a',
            
            presetGradients: [
                { name: 'Midnight Sky', value: 'linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%)' },
                { name: 'Cyber Blue', value: 'linear-gradient(135deg, #022c43 0%, #0284c7 50%, #06b6d4 100%)' },
                { name: 'Royal Purple', value: 'linear-gradient(135deg, #1e1b4b 0%, #581c87 50%, #7c3aed 100%)' },
                { name: 'Sunset Crimson', value: 'linear-gradient(135deg, #450a0a 0%, #991b1b 50%, #f97316 100%)' },
                { name: 'Emerald Tech', value: 'linear-gradient(135deg, #022c22 0%, #065f46 50%, #10b981 100%)' },
                { name: 'Neon Pink', value: 'linear-gradient(135deg, #18093c 0%, #701a75 50%, #ec4899 100%)' },
                { name: 'Golden Warmth', value: 'linear-gradient(135deg, #451a03 0%, #b45309 50%, #f59e0b 100%)' },
                { name: 'Dark Titanium', value: 'linear-gradient(135deg, #111827 0%, #1f2937 50%, #374151 100%)' },
                { name: 'Deep Sapphire', value: 'linear-gradient(135deg, #0a192f 0%, #172a45 50%, #1e3a8a 100%)' }
            ],
            
            presetSolidColors: [
                { name: 'Dark Slate', value: '#0f172a' },
                { name: 'Deep Indigo', value: '#1e1b4b' },
                { name: 'Midnight Navy', value: '#172554' },
                { name: 'Dark Emerald', value: '#022c22' },
                { name: 'Dark Maroon', value: '#450a0a' },
                { name: 'Deep Violet', value: '#2e1065' },
                { name: 'Dark Neutral', value: '#18181b' },
                { name: 'Pitch Black', value: '#090d16' }
            ],
            
            presetImages: [
                { name: 'Abstract Mesh', url: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=1200&h=400&fit=crop' },
                { name: 'Tech Waves', url: 'https://images.unsplash.com/photo-1557683316-973673baf926?w=1200&h=400&fit=crop' },
                { name: 'Neon City', url: 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1200&h=400&fit=crop' },
                { name: 'Minimalist Soft', url: 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?w=1200&h=400&fit=crop' }
            ],
            
            init() {
                if (window.innerWidth < 768) {
                    this.device = 'mobile';
                }
                
                if (!this.hasSavedAppearance && (!Array.isArray(this.activeComponents) || this.activeComponents.length === 0)) {
                    this.activeComponents = [
                        { id: this.generateId(), type: 'products', data: this.getDefaultData('products') },
                        { id: this.generateId(), type: 'text', data: this.getDefaultData('text') }
                    ];
                } else if (Array.isArray(this.activeComponents)) {
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
                    case 'flash_sale': return { title: 'Flash Sale Hari Ini!', end_date: '', discount: 20, product_ids: [] };
                    case 'voucher': return {};
                    default: return {};
                }
            },
            
            addComponent(type) {
                const newComp = { id: this.generateId(), type: type, data: this.getDefaultData(type) };
                this.activeComponents.push(newComp);
                this.hasUnsavedChanges = true;
                const newIndex = this.activeComponents.length - 1;
                
                // If on mobile/tablet view, automatically switch to canvas tab to see the newly added widget
                if (window.innerWidth < 1024) {
                    this.mobileTab = 'canvas';
                }
                
                this.reInitSortable();
                
                this.$nextTick(() => {
                    const canvas = this.device === 'mobile' ? document.getElementById('mobile-canvas') : document.getElementById('desktop-canvas');
                    if(canvas) canvas.scrollIntoView({ behavior: 'smooth', block: 'end' });
                    
                    // Auto-open settings for banner, single_image, text, etc.
                    if (['banner', 'single_image', 'text'].includes(type)) {
                        this.openSettings(newIndex);
                    }
                });
            },
            
            moveUp(index) {
                if (index <= 0) return;
                const item = this.activeComponents.splice(index, 1)[0];
                this.activeComponents.splice(index - 1, 0, item);
                this.hasUnsavedChanges = true;
                this.reInitSortable();
            },
            
            moveDown(index) {
                if (index >= this.activeComponents.length - 1) return;
                const item = this.activeComponents.splice(index, 1)[0];
                this.activeComponents.splice(index + 1, 0, item);
                this.hasUnsavedChanges = true;
                this.reInitSortable();
            },
            
            reInitSortable() {
                this.$nextTick(() => {
                    this.initSortable('desktop-canvas');
                    this.initSortable('mobile-canvas');
                });
            },
            
            removeComponent(index) {
                if (confirm('Apakah Anda yakin ingin menghapus blok widget ini dari kanvas?')) {
                    this.activeComponents.splice(index, 1);
                    this.hasUnsavedChanges = true;
                    this.reInitSortable();
                }
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
                    this.hasUnsavedChanges = true;
                }
                this.closeSettings();
            },
            
            closeSettings() {
                this.isSettingsModalOpen = false;
                this.editingIndex = -1;
                this.editingData = null;
            },

            // Header Banner Methods
            openBannerModal() {
                this.previewBanner = this.headerBanner || '';
                if (this.previewBanner.startsWith('linear-gradient')) {
                    this.bannerTab = 'gradient';
                } else if (this.previewBanner.startsWith('#')) {
                    this.bannerTab = 'solid';
                    this.customSolidColor = this.previewBanner;
                } else if (this.previewBanner) {
                    this.bannerTab = 'image';
                } else {
                    this.bannerTab = 'gradient';
                    this.previewBanner = this.presetGradients[0].value;
                }
                this.isBannerModalOpen = true;
            },

            closeBannerModal() {
                this.isBannerModalOpen = false;
            },

            selectPreset(val) {
                this.previewBanner = val;
            },

            updateCustomGradient() {
                this.previewBanner = `linear-gradient(${this.customGradDeg}, ${this.customGradStart} 0%, ${this.customGradEnd} 100%)`;
            },

            applyBannerModal() {
                this.headerBanner = this.previewBanner;
                this.hasUnsavedChanges = true;
                this.closeBannerModal();
            },

            clearHeaderBanner() {
                this.headerBanner = '';
                this.previewBanner = '';
                this.hasUnsavedChanges = true;
                this.closeBannerModal();
                this.showToast('Banner gambar dihapus (kembali ke warna polos default).');
            },

            resetLayout() {
                if (confirm('Kembalikan susunan widget etalase ke tata letak awal default?')) {
                    this.activeComponents = [
                        { id: this.generateId(), type: 'products', data: this.getDefaultData('products') },
                        { id: this.generateId(), type: 'text', data: this.getDefaultData('text') }
                    ];
                    this.hasUnsavedChanges = true;
                    this.reInitSortable();
                }
            },

            uploadHeaderBannerFromModal(event) {
                this.closeBannerModal();
                this.openCropper(event, null, null, true, 3/1);
            },

            getBannerStyle(banner) {
                if (!banner) return '';
                if (banner.startsWith('linear-gradient') || banner.startsWith('radial-gradient') || banner.startsWith('#') || banner.startsWith('rgb')) {
                    return `background: ${banner};`;
                }
                return `background-image: url('${banner}'); background-size: cover; background-position: center;`;
            },
            
            initSortable(refId) {
                const el = document.getElementById(refId);
                if (!el) return;
                
                if(refId === 'mobile-canvas' && this.sortableMobile) {
                    this.sortableMobile.destroy();
                    this.sortableMobile = null;
                }
                if(refId === 'desktop-canvas' && this.sortableDesktop) {
                    this.sortableDesktop.destroy();
                    this.sortableDesktop = null;
                }
                
                const sortable = new Sortable(el, {
                    animation: 200,
                    draggable: '.canvas-widget-item',
                    handle: '.drag-handle',
                    ghostClass: 'opacity-40',
                    chosenClass: 'ring-2 ring-sky-500 rounded-2xl',
                    onEnd: () => {
                        const items = Array.from(el.querySelectorAll('.canvas-widget-item'));
                        const newIds = items.map(node => node.getAttribute('data-id')).filter(Boolean);
                        const compMap = new Map(this.activeComponents.map(c => [String(c.id), c]));
                        const reordered = newIds.map(id => compMap.get(String(id))).filter(Boolean);
                        
                        if (reordered.length === this.activeComponents.length) {
                            this.activeComponents = reordered;
                            this.hasUnsavedChanges = true;
                        }
                    }
                });
                
                if(refId === 'mobile-canvas') this.sortableMobile = sortable;
                if(refId === 'desktop-canvas') this.sortableDesktop = sortable;
            },
            
            showToast(message) {
                this.toast.message = message;
                this.toast.show = true;
                setTimeout(() => {
                    this.toast.show = false;
                }, 3000);
            },

            setStoreMode(mode) {
                if (this.storeMode === mode) return;
                this.storeMode = mode;
                
                // If switching to profile, switch sidebar tab to biolink
                if (mode === 'profile') {
                    this.sidebarTab = 'biolink';
                    this.mobileTab = 'biolink';
                }
                
                // Instantly save mode to backend
                fetch('{{ route('tenant.appearance.mode') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ store_mode: mode })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.showToast(data.message || 'Mode berhasil diubah');
                    }
                })
                .catch(err => {
                    console.error(err);
                });
            },

            addProfileLink(preset = null) {
                const link = preset ? {
                    title: preset.title || 'Tautan Baru',
                    subtitle: preset.subtitle || '',
                    description: preset.description || '',
                    button_text: preset.button_text || 'Buka Tautan / Pesan',
                    has_detail: preset.has_detail !== undefined ? preset.has_detail : true,
                    url: preset.url || 'https://',
                    image: preset.image || null,
                    icon: preset.icon || 'link',
                    color: preset.color || '#0284c7',
                    layout: preset.layout || 'list',
                    badge: preset.badge || '',
                    is_active: true
                } : {
                    title: 'Tautan Baru',
                    subtitle: '',
                    description: '',
                    button_text: 'Buka Tautan / Pesan',
                    has_detail: true,
                    url: 'https://',
                    image: null,
                    icon: 'link',
                    color: '#0284c7',
                    layout: 'list',
                    badge: '',
                    is_active: true
                };
                this.profileLinks.push(link);
                this.hasUnsavedChanges = true;
                this.expandedLinkIndex = this.profileLinks.length - 1;
            },

            removeProfileLink(index) {
                if (confirm('Hapus tombol tautan link ini?')) {
                    this.profileLinks.splice(index, 1);
                    this.hasUnsavedChanges = true;
                    if (this.expandedLinkIndex === index) {
                        this.expandedLinkIndex = null;
                    } else if (this.expandedLinkIndex > index) {
                        this.expandedLinkIndex--;
                    }
                }
            },

            moveProfileLinkUp(index) {
                if (index <= 0) return;
                const item = this.profileLinks.splice(index, 1)[0];
                this.profileLinks.splice(index - 1, 0, item);
                this.hasUnsavedChanges = true;
                if (this.expandedLinkIndex === index) {
                    this.expandedLinkIndex = index - 1;
                } else if (this.expandedLinkIndex === index - 1) {
                    this.expandedLinkIndex = index;
                }
            },

            moveProfileLinkDown(index) {
                if (index >= this.profileLinks.length - 1) return;
                const item = this.profileLinks.splice(index, 1)[0];
                this.profileLinks.splice(index + 1, 0, item);
                this.hasUnsavedChanges = true;
                if (this.expandedLinkIndex === index) {
                    this.expandedLinkIndex = index + 1;
                } else if (this.expandedLinkIndex === index + 1) {
                    this.expandedLinkIndex = index;
                }
            },

            saveProfileLinks() {
                this.isSavingLinks = true;
                fetch('{{ route('tenant.appearance.links') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ profile_links: this.profileLinks })
                })
                .then(res => res.json())
                .then(data => {
                    this.isSavingLinks = false;
                    if (data.success) {
                        this.hasUnsavedChanges = false;
                        this.showToast('✓ Tombol Bio Link berhasil disimpan!');
                    } else {
                        alert(data.message || 'Gagal menyimpan bio link.');
                    }
                })
                .catch(err => {
                    this.isSavingLinks = false;
                    alert('Terjadi kesalahan koneksi.');
                    console.error(err);
                });
            },
            
            save() {
                if (this.isSaving) return;
                this.isSaving = true;

                const cleanComponents = (this.activeComponents || []).map(c => ({
                    id: c.id,
                    type: c.type,
                    data: c.data || {}
                }));

                fetch('{{ route('tenant.appearance.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ 
                        components: cleanComponents,
                        header_banner: this.headerBanner,
                        store_mode: this.storeMode,
                        profile_links: this.profileLinks,
                        voucher_placement: this.voucherPlacement
                    })
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => { throw new Error(err.message || 'Error ' + res.status); });
                    }
                    return res.json();
                })
                .then(data => {
                    this.isSaving = false;
                    if (data.success) {
                        this.hasUnsavedChanges = false;
                        this.hasSavedAppearance = true;
                        this.showToast(data.message || '✓ Pengaturan toko berhasil disimpan!');
                    } else {
                        alert('Gagal menyimpan: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(err => {
                    this.isSaving = false;
                    alert('Gagal menyimpan: ' + (err.message || 'Terjadi kesalahan koneksi'));
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
                            this.hasUnsavedChanges = true;
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
            },

            // Save voucher placement config
            saveVoucherPlacement() {
                this.isSavingVoucher = true;
                this.voucherSaveMsg = '';
                fetch('{{ route('tenant.appearance.voucher-placement') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ voucher_placement: this.voucherPlacement })
                })
                .then(r => r.json())
                .then(data => {
                    this.isSavingVoucher = false;
                    if (data.success) {
                        this.hasUnsavedChanges = false;
                        this.voucherSaveMsg = '✓ Penempatan berhasil disimpan!';
                    } else {
                        this.voucherSaveMsg = data.message || 'Gagal menyimpan.';
                    }
                    setTimeout(() => this.voucherSaveMsg = '', 3000);
                })
                .catch(() => {
                    this.isSavingVoucher = false;
                    this.voucherSaveMsg = 'Kesalahan koneksi.';
                });
            },

            // Live Preview Modal methods
            openPreviewModal() {
                if (this.hasUnsavedChanges) {
                    this.save();
                }
                this.isPreviewModalOpen = true;
                this.refreshPreview();
            },

            closePreviewModal() {
                this.isPreviewModalOpen = false;
            },

            refreshPreview() {
                const timestamp = new Date().getTime();
                const base = '{{ route('store.show', $store->slug ?? '') }}';
                const url = base + (base.includes('?') ? '&' : '?') + '_t=' + timestamp;
                this.previewUrl = url;
                this.$nextTick(() => {
                    const mIframe = document.getElementById('preview-iframe-mobile');
                    const dIframe = document.getElementById('preview-iframe-desktop');
                    if (mIframe) mIframe.src = url;
                    if (dIframe) dIframe.src = url;
                });
            }
        }));
    });
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
@endsection
