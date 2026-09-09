@extends('layouts.tenant')

@section('title', 'Buat Iklan Baru - Rhantech Seller Center')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f6f8] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
        biddingMode: 'auto',
        bidPrice: 500,
        budgetType: 'unlimited',
        dailyBudget: 25000,
        displayMode: 'auto',
        keywords: [],
        newKeyword: '',
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

                <!-- Pilih Produk / Tipe Iklan -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Pilih Produk yang Diiklankan <span class="text-rose-500">*</span>
                    </label>
                    <select name="product_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-900 dark:text-white text-xs md:text-sm font-semibold focus:ring-2 focus:ring-[#0284c7] focus:outline-none">
                        <option value="">-- Pilih Produk yang Ingin Dipromosikan --</option>
                        
                        @if($ownProducts->count() > 0)
                        <optgroup label="📦 Produk Milik Toko Anda ({{ $ownProducts->count() }})">
                            @foreach($ownProducts as $prod)
                                <option value="{{ $prod->id }}">
                                    {{ $prod->name }} — Rp{{ number_format($prod->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </optgroup>
                        @endif

                        @if($showcaseProducts->count() > 0)
                        <optgroup label="⭐ Produk Afiliasi di Etalase Toko Anda ({{ $showcaseProducts->count() }})">
                            @foreach($showcaseProducts as $prod)
                                <option value="{{ $prod->id }}">
                                    [Afiliasi] {{ $prod->name }} — Rp{{ number_format($prod->price, 0, ',', '.') }} (Toko: {{ $prod->store->name ?? 'Platform' }})
                                </option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                    <input type="hidden" name="type" value="product">
                    <p class="text-[11px] text-slate-400 mt-1">
                        Anda dapat mengiklankan produk toko sendiri maupun <strong>produk afiliasi</strong> yang telah Anda pasang di menu <a href="{{ route('tenant.showcase.index') }}" target="_blank" class="text-[#0284c7] font-semibold underline">Etalase Afiliasi</a> untuk meraup komisi penjualan.
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
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">
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

                    <div x-show="periodType === 'custom'" x-transition class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 max-w-md">
                        <div>
                            <label class="text-[11px] text-slate-500">Mulai Tanggal</label>
                            <input type="date" name="start_date" class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
                        </div>
                        <div>
                            <label class="text-[11px] text-slate-500">Selesai Tanggal</label>
                            <input type="date" name="end_date" class="w-full px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200">
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

            <!-- Tombol Aksi Bawah -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <a href="{{ route('tenant.ads.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-xs md:text-sm font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batalkan
                </a>
                <button type="submit" class="px-8 py-2.5 rounded-xl bg-[#0284c7] hover:bg-[#0369a1] text-white text-xs md:text-sm font-bold shadow-md transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">publish</span>
                    Tampilkan & Buat Iklan
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
