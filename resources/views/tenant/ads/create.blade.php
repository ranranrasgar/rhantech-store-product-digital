@extends('layouts.tenant')

@section('title', 'Buat Iklan Baru - Rhantech Seller Center')

@section('content')
@php
    $allProdList = [];
    foreach($ownProducts as $p) {
        $allProdList[] = [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float)$p->price,
            'image' => $p->images->first() ? asset('storage/' . $p->images->first()->image_path) : null,
            'type' => 'own',
            'type_label' => 'Milik Toko',
            'store_name' => $store->name,
        ];
    }
    foreach($showcaseProducts as $p) {
        $allProdList[] = [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float)$p->price,
            'image' => $p->images->first() ? asset('storage/' . $p->images->first()->image_path) : null,
            'type' => 'affiliate',
            'type_label' => 'Afiliasi',
            'store_name' => $p->store->name ?? 'Mitra Platform',
        ];
    }
    $allProductIds = array_map(fn($item) => $item['id'], $allProdList);
    $initialSelected = old('product_ids', !empty($allProductIds) ? [$allProductIds[0]] : []);
@endphp

<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f6f8] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
        allProducts: {{ json_encode($allProdList) }},
        allProductIds: {{ json_encode($allProductIds) }},
        selectedProducts: {{ json_encode($initialSelected) }},
        productSearch: '',
        adBalance: {{ (float) ($adBalance ?? 0) }},
        biddingMode: '{{ old('bidding_mode', 'auto') }}',
        bidPrice: {{ old('bid_price', 500) }},
        budgetType: '{{ old('budget_type', 'unlimited') }}',
        dailyBudget: {{ old('daily_budget', 25000) }},
        periodType: '{{ old('period_type', 'unlimited') }}',
        startDate: '{{ old('start_date', date('Y-m-d')) }}',
        endDate: '{{ old('end_date', date('Y-m-d', strtotime('+30 days'))) }}',
        today: '{{ date('Y-m-d') }}',
        displayMode: '{{ old('display_mode', 'auto') }}',
        keywords: {{ json_encode(old('target_keywords', [])) }},
        newKeyword: '',
        selectAll() {
            this.selectedProducts = this.allProductIds.slice();
        },
        deselectAll() {
            this.selectedProducts = [];
        },
        toggleProduct(id) {
            const idx = this.selectedProducts.indexOf(id);
            if (idx > -1) {
                this.selectedProducts.splice(idx, 1);
            } else {
                this.selectedProducts.push(id);
            }
        },
        isProductSelected(id) {
            return this.selectedProducts.includes(id);
        },
        get filteredProducts() {
            if (!this.productSearch.trim()) return this.allProducts;
            const q = this.productSearch.toLowerCase();
            return this.allProducts.filter(p => p.name.toLowerCase().includes(q) || p.store_name.toLowerCase().includes(q));
        },
        get currentCpc() {
            return this.biddingMode === 'manual' ? (parseFloat(this.bidPrice) || 500) : 500;
        },
        get totalDailyBudget() {
            return this.budgetType === 'daily' ? (this.selectedProducts.length * (parseFloat(this.dailyBudget) || 0)) : 0;
        },
        get estDailyClicks() {
            const cpc = this.currentCpc;
            if (this.budgetType === 'daily') {
                return Math.floor(this.totalDailyBudget / cpc);
            }
            return Math.floor(this.adBalance / cpc);
        },
        setPeriodPreset(days) {
            this.periodType = 'custom';
            const now = new Date();
            const yyyy = now.getFullYear();
            const mm = String(now.getMonth() + 1).padStart(2, '0');
            const dd = String(now.getDate()).padStart(2, '0');
            this.startDate = `${yyyy}-${mm}-${dd}`;

            const target = new Date();
            target.setDate(target.getDate() + days);
            const tYyyy = target.getFullYear();
            const tMm = String(target.getMonth() + 1).padStart(2, '0');
            const tDd = String(target.getDate()).padStart(2, '0');
            this.endDate = `${tYyyy}-${tMm}-${tDd}`;
        },
        addKeyword(kw) {
            kw = (kw || '').trim();
            if (kw && !this.keywords.includes(kw)) {
                this.keywords.push(kw);
            }
            this.newKeyword = '';
        },
        removeKeyword(index) {
            this.keywords.splice(index, 1);
        }
     }">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Breadcrumb & Title Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('tenant.dashboard') }}" class="hover:text-[#0284c7] transition-colors">Beranda</a>
                <span>&gt;</span>
                <a href="{{ route('tenant.ads.index') }}" class="hover:text-[#0284c7] transition-colors">Iklan Toko</a>
                <span>&gt;</span>
                <span class="text-slate-800 dark:text-slate-200 font-semibold">Buat Iklan Baru</span>
            </div>
            
            <a href="{{ route('tenant.ads.index') }}" class="text-xs font-semibold text-slate-500 hover:text-[#0284c7] flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali ke Daftar Iklan
            </a>
        </div>

        @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-sm">
            <div class="font-bold mb-1">Terjadi kesalahan input:</div>
            <ul class="list-disc list-inside space-y-1 text-xs">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Banner Visual Promosi Biru Langit -->
        <div class="rounded-2xl bg-gradient-to-r from-sky-500/10 via-blue-500/10 to-cyan-500/10 dark:from-sky-950/20 dark:to-slate-800/40 border border-sky-200/80 dark:border-slate-800 p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-base md:text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-[#0284c7] text-[22px]">rocket_launch</span>
                    Promosikan tokomu di halaman pencarian untuk menjangkau lebih banyak Pembeli
                </h2>
                <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-slate-600 dark:text-slate-300 mt-2">
                    <span class="inline-flex items-center gap-1 text-[#0284c7]">
                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                        Jumlah Iklan Dilihat Naik 3X
                    </span>
                    <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                        <span class="material-symbols-outlined text-[16px]">ads_click</span>
                        Persentase Klik Naik +15%
                    </span>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-white dark:bg-slate-800 text-[11px] font-bold text-[#0284c7] border border-sky-200 dark:border-slate-700 shadow-sm shrink-0">
                Rhantech Smart Ads
            </span>
        </div>

        <form action="{{ route('tenant.ads.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- 1. PENGATURAN DASAR (Persis Screenshot 4) -->
            <div class="bg-white dark:bg-[#161b22] rounded-2xl border border-slate-200/90 dark:border-slate-800 p-6 md:p-7 space-y-6 shadow-sm">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between">
                    <h3 class="text-sm md:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-4 bg-[#0284c7] rounded-full"></span>
                        Pengaturan Dasar
                    </h3>
                </div>

                <!-- Pilih Produk yang Diiklankan (Bisa Lebih Dari 1 Produk Sekaligus) -->
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Pilih Produk yang Diiklankan <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                Anda dapat mencentang 1 atau banyak produk sekaligus untuk diiklankan secara serentak.
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-200 dark:border-sky-800"
                                  x-text="selectedProducts.length + ' Produk Dipilih'"></span>
                            <button type="button" 
                                    @click="selectAll()"
                                    class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-sky-50 dark:hover:bg-sky-950 hover:text-sky-600 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer">
                                Pilih Semua
                            </button>
                            <button type="button" 
                                    @click="deselectAll()"
                                    x-show="selectedProducts.length > 0"
                                    class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-slate-100 dark:bg-slate-800 hover:bg-rose-50 dark:hover:bg-rose-950 hover:text-rose-600 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700 transition-colors cursor-pointer">
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Hidden Inputs for Form Submission -->
                    <template x-for="id in selectedProducts" :key="id">
                        <input type="hidden" name="product_ids[]" :value="id">
                    </template>
                    <input type="hidden" name="type" value="product">

                    <!-- Search Filter Box -->
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[18px] text-slate-400 pointer-events-none">search</span>
                        <input type="text" 
                               x-model="productSearch" 
                               placeholder="Cari nama produk atau toko afiliasi..." 
                               class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-[#0c1220] text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>

                    <!-- Interactive Product Checklist Cards -->
                    <div class="max-h-72 overflow-y-auto pr-1 space-y-2 border border-slate-200 dark:border-slate-800 rounded-xl p-2 bg-slate-50/50 dark:bg-[#0c1220]/50">
                        <template x-for="prod in filteredProducts" :key="prod.id">
                            <div @click="toggleProduct(prod.id)"
                                 :class="isProductSelected(prod.id) ? 'border-sky-500 bg-sky-50/60 dark:bg-sky-950/30 ring-1 ring-sky-500/50' : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-[#111726] hover:border-slate-300 dark:hover:border-slate-700'"
                                 class="p-2.5 rounded-xl border flex items-center justify-between gap-3 transition-all cursor-pointer select-none">
                                
                                <div class="flex items-center gap-3 min-w-0">
                                    <!-- Checkbox Visual -->
                                    <div class="w-5 h-5 rounded-md border flex items-center justify-center shrink-0 transition-colors"
                                         :class="isProductSelected(prod.id) ? 'bg-sky-500 border-sky-500 text-white' : 'border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800'">
                                        <span x-show="isProductSelected(prod.id)" class="material-symbols-outlined text-[15px] font-black">check</span>
                                    </div>

                                    <!-- Thumbnail -->
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                        <template x-if="prod.image">
                                            <img :src="prod.image" :alt="prod.name" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!prod.image">
                                            <span class="material-symbols-outlined text-[20px] text-slate-400">inventory_2</span>
                                        </template>
                                    </div>

                                    <!-- Details -->
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-900 dark:text-white truncate" x-text="prod.name"></div>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="text-[10px] font-extrabold text-sky-600 dark:text-sky-400 font-mono" x-text="'Rp' + Number(prod.price).toLocaleString('id-ID')"></span>
                                            <span class="text-[9px] px-1.5 py-0.5 rounded font-bold uppercase tracking-wider"
                                                  :class="prod.type === 'own' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-400'"
                                                  x-text="prod.type_label + (prod.type === 'affiliate' ? ' (' + prod.store_name + ')' : '')"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 text-right">
                                    <span class="text-[10px] font-semibold" 
                                          :class="isProductSelected(prod.id) ? 'text-sky-600 dark:text-sky-400 font-bold' : 'text-slate-400'">
                                        <span x-text="isProductSelected(prod.id) ? 'Terpilih' : 'Klik utk pilih'"></span>
                                    </span>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredProducts.length === 0" class="p-6 text-center text-xs text-slate-400">
                            Tidak ada produk yang cocok dengan pencarian "<span x-text="productSearch"></span>".
                        </div>
                    </div>

                    @error('product_ids')
                        <span class="text-rose-500 text-xs mt-1 block font-semibold">{{ $message }}</span>
                    @enderror

                    <p class="text-[11px] text-slate-400">
                        Anda dapat mengiklankan produk toko sendiri maupun <strong>produk afiliasi</strong> yang telah dipasang di menu <a href="{{ route('tenant.showcase.index') }}" target="_blank" class="text-[#0284c7] font-semibold underline">Etalase Afiliasi</a> untuk meningkatkan komisi penjualan.
                    </p>
                </div>

                <!-- Nama Iklan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Nama Iklan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', 'Iklan Produk ' . date('d-m-Y')) }}"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs md:text-sm font-semibold focus:ring-2 focus:ring-[#0284c7] focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1">Berikan nama untuk memudahkan pemantauan performa iklan tokomu.</p>
                </div>

                <!-- Modal / Budget -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
                        Modal Iklan
                    </label>
                    <div class="flex flex-wrap items-center gap-6">
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="radio" name="budget_type" value="unlimited" x-model="budgetType" class="text-[#0284c7] focus:ring-[#0284c7]">
                            <span>Tak Terbatas</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="radio" name="budget_type" value="daily" x-model="budgetType" class="text-[#0284c7] focus:ring-[#0284c7]">
                            <span>Atur Modal Harian</span>
                        </label>
                    </div>

                    <div x-show="budgetType === 'daily'" x-transition class="mt-3 max-w-xs">
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                            <input type="number"
                                   name="daily_budget"
                                   x-model="dailyBudget"
                                   min="5000"
                                   step="1000"
                                   class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold focus:ring-2 focus:ring-[#0284c7] focus:outline-none">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Minimal modal harian Rp5.000</p>
                    </div>
                </div>

                <!-- Periode Iklan -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                        Periode Iklan
                    </label>
                    <div class="flex flex-wrap items-center gap-6">
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="radio" name="period_type" value="unlimited" x-model="periodType" class="text-[#0284c7] focus:ring-[#0284c7]">
                            <span>Tidak Terbatas (Berjalan terus sampai saldo habis)</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs font-semibold text-slate-700 dark:text-slate-300 cursor-pointer">
                            <input type="radio" name="period_type" value="custom" x-model="periodType" class="text-[#0284c7] focus:ring-[#0284c7]">
                            <span>Atur Periode Tanggal</span>
                        </label>
                    </div>

                    <!-- Date Range Selection Container -->
                    <div x-show="periodType === 'custom'" x-transition class="p-4 rounded-xl bg-slate-50 dark:bg-[#0e1526] border border-slate-200 dark:border-[#222f49] space-y-3.5 max-w-lg shadow-xs">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Preset Durasi:</span>
                            <button type="button" @click="setPeriodPreset(7)" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#263554] hover:border-sky-500 hover:text-sky-500 text-slate-700 dark:text-slate-300 transition-all cursor-pointer shadow-2xs">
                                7 Hari
                            </button>
                            <button type="button" @click="setPeriodPreset(14)" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#263554] hover:border-sky-500 hover:text-sky-500 text-slate-700 dark:text-slate-300 transition-all cursor-pointer shadow-2xs">
                                14 Hari
                            </button>
                            <button type="button" @click="setPeriodPreset(30)" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#263554] hover:border-sky-500 hover:text-sky-500 text-slate-700 dark:text-slate-300 transition-all cursor-pointer shadow-2xs">
                                30 Hari
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold uppercase text-slate-600 dark:text-slate-400 mb-1">
                                    Mulai Tanggal <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-2.5 text-[17px] text-sky-500 pointer-events-none">calendar_today</span>
                                    <input type="date" 
                                           name="start_date" 
                                           x-model="startDate"
                                           :min="today"
                                           @change="if(endDate && endDate < startDate) endDate = startDate"
                                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#0c1220] text-slate-800 dark:text-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
                                </div>
                                @error('start_date')
                                    <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold uppercase text-slate-600 dark:text-slate-400 mb-1">
                                    Selesai Tanggal <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-2.5 text-[17px] text-sky-500 pointer-events-none">event</span>
                                    <input type="date" 
                                           name="end_date" 
                                           x-model="endDate"
                                           :min="startDate || today"
                                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-[#0c1220] text-slate-800 dark:text-slate-200 font-semibold focus:outline-none focus:ring-2 focus:ring-sky-500">
                                </div>
                                @error('end_date')
                                    <span class="text-rose-500 text-[11px] mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5 pt-1">
                            <span class="material-symbols-outlined text-[15px] text-sky-500 shrink-0">info</span>
                            <span>Iklan akan tayang mulai <strong class="text-slate-800 dark:text-slate-200" x-text="startDate"></strong> sampai pukul 23:59 WIB pada <strong class="text-slate-800 dark:text-slate-200" x-text="endDate"></strong>.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. PENGATURAN BIDDING & KATA KUNCI (Persis Screenshot 4 & 5) -->
            <div class="bg-white dark:bg-[#161b22] rounded-2xl border border-slate-200/90 dark:border-slate-800 p-6 md:p-7 space-y-6 shadow-sm">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-sm md:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-4 bg-[#0284c7] rounded-full"></span>
                        Pengaturan Bidding & Kata Pencarian
                    </h3>
                </div>

                <!-- Mode Bidding Selector -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Mode Bidding</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        
                        <!-- Manual Card -->
                        <label :class="biddingMode === 'manual' ? 'border-[#0284c7] bg-sky-50/60 dark:bg-sky-950/30 ring-2 ring-[#0284c7]' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/40'"
                               class="p-4 rounded-xl border cursor-pointer flex items-start gap-3 transition-all relative">
                            <input type="radio" name="bidding_mode" value="manual" x-model="biddingMode" class="mt-1 text-[#0284c7] focus:ring-[#0284c7]">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">Manual</span>
                                    <span class="px-2 py-0.5 rounded-full bg-[#0284c7] text-white text-[9px] font-black uppercase">Rekomendasi</span>
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                    Pilih kata pencarian dan atur biaya per klik untuk iklanmu secara presisi.
                                </div>
                            </div>
                        </label>

                        <!-- Otomatis Card -->
                        <label :class="biddingMode === 'auto' ? 'border-[#0284c7] bg-sky-50/60 dark:bg-sky-950/30 ring-2 ring-[#0284c7]' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/40'"
                               class="p-4 rounded-xl border cursor-pointer flex items-start gap-3 transition-all relative">
                            <input type="radio" name="bidding_mode" value="auto" x-model="biddingMode" class="mt-1 text-[#0284c7] focus:ring-[#0284c7]">
                            <div>
                                <div class="text-xs font-bold text-slate-900 dark:text-white">Otomatis</div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                    Sistem platform otomatis memilih kata kunci terbaik berdasarkan perilaku pembeli.
                                </div>
                            </div>
                        </label>

                    </div>
                </div>

                <!-- Biaya Bid Per Klik -->
                <div x-show="biddingMode === 'manual'" x-transition class="max-w-xs">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Harga Bid Per Klik
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number"
                               name="bid_price"
                               x-model="bidPrice"
                               min="100"
                               step="50"
                               class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs font-bold focus:ring-2 focus:ring-[#0284c7] focus:outline-none">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Disarankan: Rp500 / klik untuk peringkat atas</p>
                </div>

                <!-- Tetapkan Kata Pencarian (Persis Screenshot 5) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Tetapkan Kata Pencarian
                    </label>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">
                        Iklanmu akan tampil ketika pembeli mencari kata-kata berikut di platform:
                    </p>

                    <!-- Input Tambah Kata Kunci -->
                    <div class="flex items-center gap-2 max-w-lg mb-3">
                        <input type="text"
                               x-model="newKeyword"
                               @keydown.enter.prevent="addKeyword(newKeyword)"
                               placeholder="Ketik kata pencarian lalu tekan Enter..."
                               class="flex-1 px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-900 dark:text-white focus:ring-2 focus:ring-[#0284c7] focus:outline-none">
                        <button type="button"
                                @click="addKeyword(newKeyword)"
                                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 transition-colors flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">add</span> Tambah
                        </button>
                    </div>

                    <!-- Quick Suggestions dari Database Tren Pencarian Pembeli -->
                    @if(isset($trendingSearches) && $trendingSearches->isNotEmpty())
                    <div class="mb-4">
                        <div class="text-[11px] font-semibold text-slate-500 mb-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px] text-sky-500">local_fire_department</span>
                            Saran Kata Kunci Populer (Klik untuk menambahkan):
                        </div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($trendingSearches as $ts)
                            <button type="button"
                                    @click="addKeyword('{{ $ts->keyword }}')"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/30 dark:hover:bg-sky-950/60 border border-sky-200 dark:border-sky-800/60 text-[#0284c7] text-[11px] font-medium transition-colors">
                                <span>+ {{ $ts->keyword }}</span>
                                <span class="text-[9px] text-slate-400">({{ $ts->hits }}x)</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- List Kata Kunci Terpilih -->
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 min-h-[50px] flex flex-wrap gap-2 items-center">
                        <template x-if="keywords.length === 0">
                            <span class="text-xs text-slate-400 italic">Belum ada kata kunci spesifik. (Jika kosong, iklan akan otomatis dicocokkan dengan judul produk).</span>
                        </template>
                        <template x-for="(kw, idx) in keywords" :key="idx">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-xs">
                                <span x-text="kw"></span>
                                <input type="hidden" name="target_keywords[]" :value="kw">
                                <button type="button" @click="removeKeyword(idx)" class="text-slate-400 hover:text-rose-500">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </button>
                            </span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- 3. PENGATURAN TAMPILAN IKLAN -->
            <div class="bg-white dark:bg-[#161b22] rounded-2xl border border-slate-200/90 dark:border-slate-800 p-6 md:p-7 space-y-4 shadow-sm">
                <div class="border-b border-slate-100 dark:border-slate-800 pb-3">
                    <h3 class="text-sm md:text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="w-2 h-4 bg-[#0284c7] rounded-full"></span>
                        Pengaturan Tampilan Iklan
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label :class="displayMode === 'auto' ? 'border-[#0284c7] bg-sky-50/60 dark:bg-sky-950/30 ring-2 ring-[#0284c7]' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/40'"
                           class="p-4 rounded-xl border cursor-pointer flex items-start gap-3 transition-all">
                        <input type="radio" name="display_mode" value="auto" x-model="displayMode" class="mt-1 text-[#0284c7] focus:ring-[#0284c7]">
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-white">Otomatis</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                Platform otomatis menggunakan foto utama, judul, dan rating tokomu untuk ditampilkan pada iklan pencarian.
                            </div>
                        </div>
                    </label>

                    <label :class="displayMode === 'manual' ? 'border-[#0284c7] bg-sky-50/60 dark:bg-sky-950/30 ring-2 ring-[#0284c7]' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/40'"
                           class="p-4 rounded-xl border cursor-pointer flex items-start gap-3 transition-all">
                        <input type="radio" name="display_mode" value="manual" x-model="displayMode" class="mt-1 text-[#0284c7] focus:ring-[#0284c7]">
                        <div>
                            <div class="text-xs font-bold text-slate-900 dark:text-white">Manual</div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">
                                Atur penyesuaian khusus dan tagline promosi untuk menarik perhatian pembeli.
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 4. RINGKASAN ESTIMASI BIAYA & KLIK (Kalkulator CPC) -->
            <div class="rounded-2xl bg-gradient-to-br from-slate-900 via-[#0c1322] to-slate-950 text-white p-6 md:p-7 shadow-lg border border-slate-800 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/10 pb-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[20px]">calculate</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Ringkasan & Estimasi Biaya Iklan</h4>
                            <p class="text-[11px] text-slate-400">Sistem CPC (Cost Per Click) — Hanya dipotong saat produk Anda diklik calon pembeli.</p>
                        </div>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-sky-500/20 text-sky-300 font-bold border border-sky-500/30 self-start sm:self-center"
                          x-text="selectedProducts.length + ' Produk Terpilih'"></span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <!-- Stat 1: Biaya per Klik -->
                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Biaya Per Klik (CPC)</span>
                        <div class="text-base font-black text-sky-400 font-mono" x-text="'Rp' + Number(currentCpc).toLocaleString('id-ID') + ' / klik'"></div>
                        <p class="text-[10px] text-slate-400">Tampilan/impresi 100% gratis</p>
                    </div>

                    <!-- Stat 2: Alokasi Modal -->
                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Batas Modal Harian</span>
                        <div class="text-base font-black text-white font-mono" 
                             x-text="budgetType === 'daily' ? ('Rp' + Number(totalDailyBudget).toLocaleString('id-ID') + ' / hari') : 'Tak Terbatas'"></div>
                        <p class="text-[10px] text-slate-400" 
                           x-text="budgetType === 'daily' ? ('(Rp' + Number(dailyBudget).toLocaleString('id-ID') + ' × ' + selectedProducts.length + ' produk)') : 'Berjalan sampai saldo habis'"></p>
                    </div>

                    <!-- Stat 3: Estimasi Klik -->
                    <div class="p-3.5 rounded-xl bg-white/5 border border-white/10 space-y-1">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Potensi Trafik Klik</span>
                        <div class="text-base font-black text-emerald-400 font-mono" 
                             x-text="'~' + estDailyClicks.toLocaleString('id-ID') + ' Klik' + (budgetType === 'daily' ? ' / hari' : '')"></div>
                        <p class="text-[10px] text-slate-400">Pengunjung potensial ke produk Anda</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 text-[11px] text-slate-400 bg-white/5 p-3 rounded-xl border border-white/5">
                    <span class="material-symbols-outlined text-[16px] text-amber-400 shrink-0">verified_user</span>
                    <span><strong>Proteksi Saldo:</strong> Dilengkapi proteksi anti-spam klik. Jika saldo iklan toko mencapai Rp0, seluruh iklan otomatis dijeda sehingga saldo Anda tidak akan pernah minus.</span>
                </div>
            </div>

            <!-- Tombol Aksi Bawah -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('tenant.ads.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs md:text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batalkan
                </a>
                <button type="submit" 
                        :disabled="selectedProducts.length === 0"
                        :class="selectedProducts.length === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#0369a1]'"
                        class="px-8 py-2.5 rounded-xl bg-[#0284c7] text-white text-xs md:text-sm font-bold shadow-md transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">publish</span>
                    <span>Tampilkan & Buat Iklan (<span x-text="selectedProducts.length"></span>)</span>
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
