@extends('layouts.tenant')

@section('title', 'Rekening & Metode Pembayaran')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200" x-data="{ showModal: false, bankName: '', accountNumber: '', accountHolder: '' }">
    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Rekening Bank Pencairan
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola data rekening bank utama untuk menerima pencairan saldo toko Anda.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <button type="button" @click="showModal = true; bankName = 'BCA'; accountNumber = ''; accountHolder = '';" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs md:text-sm font-bold active:scale-95 transition-all flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    {{ empty($store->bank_account_info) ? 'Tambah Rekening' : 'Ubah Rekening' }}
                </button>
            </div>
        </div>

        <!-- Session Alerts -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs md:text-sm font-semibold flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs md:text-sm font-semibold flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Card Section -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 md:p-8 space-y-6">
            
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white">Daftar Rekening Bank Terdaftar</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Rekening ini digunakan sebagai tujuan otomatis saat melakukan penarikan saldo toko.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                
                <!-- Saved Bank Debit Card Showcase -->
                @if(!empty($store->bank_account_info))
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-sky-700 via-sky-800 to-slate-900 text-white p-6 border border-sky-600/40 shadow-sm flex flex-col justify-between min-h-[200px] group">
                        <!-- Card Top Bar -->
                        <div class="flex items-center justify-between relative z-10">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-300 text-[26px]">account_balance</span>
                                <span class="text-xs font-black tracking-widest uppercase text-slate-300">REKENING BANK</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                UTAMA
                            </span>
                        </div>

                        <!-- Card Middle Info -->
                        <div class="my-4 relative z-10">
                            <div class="text-xs text-slate-400 mb-1 font-sans">Informasi Rekening</div>
                            <div class="font-mono text-sm text-slate-200 whitespace-pre-line leading-relaxed line-clamp-3">
                                {{ $store->bank_account_info }}
                            </div>
                        </div>

                        <!-- Card Bottom Bar -->
                        <div class="pt-3 border-t border-white/10 flex items-center justify-between relative z-10">
                            <div class="text-xs font-bold text-slate-300 uppercase truncate">
                                {{ $store->name }}
                            </div>
                            <button type="button" @click="showModal = true" class="text-xs font-bold text-slate-300 hover:text-white flex items-center gap-1 cursor-pointer">
                                <span class="material-symbols-outlined text-[15px]">edit</span> Edit Rekening
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Tambah / Ubah Rekening Modal Trigger Card -->
                <button type="button" @click="showModal = true" class="border-2 border-dashed border-slate-200 dark:border-[#222f49] hover:border-slate-400 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-[#0c1220] rounded-2xl p-6 flex flex-col items-center justify-center min-h-[200px] text-center transition-all group cursor-pointer w-full">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center mb-3 group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-2xl">add_circle</span>
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 block">
                        {{ empty($store->bank_account_info) ? 'Tambah Rekening Bank Baru' : 'Ganti Rekening Bank' }}
                    </span>
                    <span class="text-[11px] text-slate-400 mt-1">
                        Masukkan nama bank, nomor rekening, dan nama pemilik.
                    </span>
                </button>

            </div>

        </div>

    </div>

    <!-- MODAL FORM TAMBAH/UBAH REKENING -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
        <div @click.away="showModal = false" class="bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-3xl max-w-md w-full overflow-hidden transition-all transform scale-100">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-[#222f49] flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-700 dark:text-slate-300 text-[20px]">account_balance</span>
                    {{ empty($store->bank_account_info) ? 'Tambah Rekening Bank' : 'Perbarui Rekening Bank' }}
                </h3>
                <button type="button" @click="showModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('tenant.bank.update') }}" method="POST" class="p-6 space-y-4">
                @csrf
                
                <!-- Nama Bank (Bisa Ketik Manual / Pilih Rekomendasi) -->
                <div>
                    <label for="bank_name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        Nama Bank / E-Wallet <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input list="bank_options" name="bank_name" id="bank_name" x-model="bankName" required placeholder="Pilih atau ketik manual (Contoh: Bank BCA, DANA, dll)" class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 dark:focus:border-white transition-all">
                        <datalist id="bank_options">
                            <option value="Bank BCA"></option>
                            <option value="Bank Mandiri"></option>
                            <option value="Bank BRI"></option>
                            <option value="Bank BNI"></option>
                            <option value="Bank Syariah Indonesia (BSI)"></option>
                            <option value="Bank CIMB Niaga"></option>
                            <option value="Bank Permata"></option>
                            <option value="SeaBank"></option>
                            <option value="Bank Jago"></option>
                            <option value="Bank Danamon"></option>
                            <option value="Bank Tabungan Negara (BTN)"></option>
                            <option value="Bank BJB"></option>
                            <option value="Bank Jatim"></option>
                            <option value="Bank Jateng"></option>
                            <option value="DANA (E-Wallet)"></option>
                            <option value="GoPay (E-Wallet)"></option>
                            <option value="OVO (E-Wallet)"></option>
                            <option value="ShopeePay (E-Wallet)"></option>
                            <option value="LinkAja (E-Wallet)"></option>
                        </datalist>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Anda bisa memilih dari daftar saran atau mengetik langsung nama bank/e-wallet Anda.</p>
                </div>

                <!-- Nomor Rekening -->
                <div>
                    <label for="account_number" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        Nomor Rekening / No. HP E-Wallet <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="account_number" id="account_number" x-model="accountNumber" required placeholder="Contoh: 4370351509" class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 dark:focus:border-white font-mono transition-all">
                </div>

                <!-- Nama Pemilik Rekening -->
                <div>
                    <label for="account_holder" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">
                        Nama Pemilik Rekening (Sesuai Buku Tabungan) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="account_holder" id="account_holder" x-model="accountHolder" required placeholder="Contoh: RANRAN RAHAYU" class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 dark:focus:border-white uppercase transition-all">
                    <p class="text-[11px] text-slate-400 mt-1">Pastikan nama sama persis dengan rekening agar proses penarikan saldo tidak terhambat.</p>
                </div>

                <!-- Modal Actions -->
                <div class="pt-4 border-t border-slate-100 dark:border-[#222f49] flex items-center justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs md:text-sm font-semibold border border-slate-200 dark:border-[#222f49] text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs md:text-sm font-bold text-white bg-sky-500 hover:bg-sky-600 dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white rounded-xl active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">save</span>
                        Simpan Rekening
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
