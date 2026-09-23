@extends('layouts.tenant')

@section('title', 'Isi Saldo Iklan - Rhantech Seller Center')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f5f6f8] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] transition-colors duration-200"
     x-data="{
        selectedAmount: 50000,
        customAmount: '',
        isCustom: false,
        paymentMethod: 'store_balance',
        storeBalance: {{ $storeBalance }},
        selectPackage(amt) {
            this.selectedAmount = amt;
            this.isCustom = false;
            this.customAmount = '';
        },
        enableCustom() {
            this.isCustom = true;
            this.selectedAmount = this.customAmount ? parseInt(this.customAmount) : 0;
        },
        updateCustom() {
            let val = parseInt(this.customAmount.replace(/[^0-9]/g, '')) || 0;
            this.selectedAmount = val;
        },
        get ppn() {
            return Math.round(this.selectedAmount * 0.11);
        },
        get total() {
            return this.selectedAmount + this.ppn;
        },
        formatRupiah(num) {
            return 'Rp' + (num || 0).toLocaleString('id-ID');
        }
     }">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Back Button & Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('tenant.ads.index') }}" class="hover:text-[#ea580c] flex items-center gap-1 font-semibold transition-colors">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                Kembali ke Iklan Toko
            </a>
            <span>/</span>
            <span class="text-zinc-800 dark:text-zinc-200 font-semibold">Isi Saldo Iklan</span>
        </div>

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-sm flex items-center gap-3">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Card Form Utama Saldo Iklan Rhantech -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-slate-200/90 dark:border-slate-800 p-6 md:p-8 space-y-8">
            
            <!-- Header Judul -->
            <div class="border-b border-slate-100 dark:border-slate-800 pb-5">
                <h1 class="text-xl md:text-2xl font-extrabold text-zinc-900 dark:text-zinc-100">
                    Isi Saldo Iklan
                </h1>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-1.5 leading-relaxed">
                    Saldo iklan dapat digunakan untuk membeli peringkat dan visibilitas iklan produk di hasil pencarian. Pengisian saldo iklan akan langsung ditambahkan ke tokomu.
                </p>
            </div>

            <!-- Banner Otomatisasi Saldo Toko -->
            <div class="p-4 rounded-2xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200/70 dark:border-amber-900/50 flex items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-[22px] mt-0.5">swap_horizontal_circle</span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs md:text-sm font-bold text-zinc-900 dark:text-zinc-100">Isi Saldo dari Penghasilan Toko</span>
                            <span class="px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-black uppercase tracking-wider">Praktis</span>
                        </div>
                        <p class="text-xs text-zinc-600 dark:text-zinc-300 mt-1">
                            Saldo Penjualan Toko Anda saat ini: <strong class="text-emerald-600 dark:text-emerald-400">Rp{{ number_format($storeBalance, 0, ',', '.') }}</strong>. Anda dapat mengisi saldo iklan secara instan tanpa perlu transfer manual.
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('tenant.ads.process-top-up') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="amount" :value="selectedAmount">

                <!-- 1. Grid Pilihan Nominal -->
                <div>
                    <label class="block text-sm font-bold text-zinc-900 dark:text-zinc-100 mb-1">
                        Pilih Jumlah Isi Ulang Saldo
                    </label>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">
                        Pilih salah satu nominal paket saldo iklan di bawah ini:
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($packages as $pkg)
                        <button type="button"
                                @click="selectPackage({{ $pkg }})"
                                :class="(!isCustom && selectedAmount === {{ $pkg }}) ? 'border-[#ea580c] bg-orange-50/60 dark:bg-orange-950/30 ring-2 ring-[#ea580c]' : 'border-zinc-200 dark:border-zinc-800 hover:border-slate-300 dark:hover:border-slate-600 bg-white dark:bg-slate-800/60'"
                                class="p-4 rounded-xl border text-center transition-all relative overflow-hidden group">
                            
                            <div class="text-[11px] text-zinc-500 dark:text-zinc-400 font-medium">Saldo Iklan</div>
                            <div class="text-sm md:text-base font-black text-zinc-900 dark:text-zinc-100 mt-1">
                                Rp{{ number_format($pkg, 0, ',', '.') }}
                            </div>

                            <!-- Checkmark Badge di Pojok Kanan Bawah -->
                            <div x-show="!isCustom && selectedAmount === {{ $pkg }}" class="absolute bottom-0 right-0 w-5 h-5 bg-[#ea580c] text-white flex items-center justify-center rounded-tl-lg">
                                <span class="material-symbols-outlined text-[12px] font-black">check</span>
                            </div>
                        </button>
                        @endforeach
                    </div>

                    <!-- Pilihan Nominal Lainnya / Custom -->
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="enableCustom()" class="text-xs font-bold text-[#ea580c] hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">edit</span>
                            Atau Masukkan Jumlah Isi Ulang Saldo Lainnya
                        </button>

                        <div x-show="isCustom" x-transition class="mt-3 max-w-sm">
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-xs">Rp</span>
                                <input type="number"
                                       x-model="customAmount"
                                       @input="updateCustom()"
                                       placeholder="Minimal 10.000"
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-[#ea580c] bg-white dark:bg-slate-900 text-zinc-900 dark:text-zinc-100 text-sm font-bold focus:ring-2 focus:ring-[#ea580c] focus:outline-none">
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">Nominal bebas, minimal Rp10.000</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Pilihan Sumber Pembayaran -->
                <div>
                    <label class="block text-sm font-bold text-zinc-900 dark:text-zinc-100 mb-2">
                        Metode Pembayaran
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- Option 1: Potong Saldo Toko -->
                        <label :class="paymentMethod === 'store_balance' ? 'border-[#ea580c] bg-orange-50/60 dark:bg-orange-950/30 ring-2 ring-[#ea580c]' : 'border-zinc-200 dark:border-zinc-800 bg-white dark:bg-slate-800/40'"
                               class="p-4 rounded-xl border cursor-pointer flex items-start gap-3 transition-all relative">
                            <input type="radio" name="payment_method" value="store_balance" x-model="paymentMethod" class="mt-1 text-[#ea580c] focus:ring-[#ea580c]">
                            <div>
                                <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100">Potong Saldo Penjualan Toko</div>
                                <div class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                                    Tersedia: Rp{{ number_format($storeBalance, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1">
                                    ✓ Proses Otomatis & Langsung Aktif
                                </div>
                            </div>
                        </label>

                        <!-- Option 2: Payment Gateway / Online Transfer -->
                        <label :class="paymentMethod === 'direct_gateway' ? 'border-[#ea580c] bg-orange-50/60 dark:bg-orange-950/30 ring-2 ring-[#ea580c]' : 'border-zinc-200 dark:border-zinc-800 bg-white dark:bg-slate-800/40'"
                               class="p-4 rounded-xl border cursor-pointer flex items-start gap-3 transition-all relative">
                            <input type="radio" name="payment_method" value="direct_gateway" x-model="paymentMethod" class="mt-1 text-[#ea580c] focus:ring-[#ea580c]">
                            <div>
                                <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100">Pembayaran Online / QRIS / Transfer</div>
                                <div class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                                    Top up saldo iklan tanpa memotong saldo toko
                                </div>
                                <div class="text-[10px] text-orange-600 dark:text-orange-400 font-semibold mt-1">
                                    ✓ Instan & Terverifikasi
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 3. Rincian & Tombol Checkout (Persis Screenshot 4) -->
                <div class="pt-6 border-t border-zinc-200 dark:border-zinc-800">
                    <div class="max-w-sm ml-auto space-y-2.5 text-xs text-zinc-600 dark:text-zinc-400">
                        <div class="flex justify-between items-center">
                            <span>Harga Saldo Iklan</span>
                            <span class="font-bold text-zinc-900 dark:text-zinc-100" x-text="formatRupiah(selectedAmount)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="flex items-center gap-1">
                                PPN (11%)
                                <span class="material-symbols-outlined text-[13px] text-slate-400" title="Pajak Pertambahan Nilai sesuai regulasi">info</span>
                            </span>
                            <span class="font-bold text-zinc-900 dark:text-zinc-100" x-text="formatRupiah(ppn)"></span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-zinc-200 dark:border-zinc-800 text-sm font-extrabold text-zinc-900 dark:text-zinc-100">
                            <span>Total Harga (Termasuk PPN)</span>
                            <span class="text-zinc-900 dark:text-zinc-100 font-black text-base" x-text="formatRupiah(total)"></span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                :disabled="selectedAmount < 10000"
                                class="px-8 py-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white disabled:opacity-50 font-bold text-sm transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                            <span class="material-symbols-outlined text-[18px]">shopping_cart_checkout</span>
                            Checkout & Bayar
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection
