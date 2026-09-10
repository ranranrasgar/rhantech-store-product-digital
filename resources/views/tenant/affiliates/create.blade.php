@extends('layouts.tenant')

@section('title', 'Hubungkan Akun Affiliate')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] p-4 md:p-8">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('tenant.affiliates.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] flex items-center justify-center text-slate-500 hover:text-slate-800 dark:hover:text-white transition">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-xl md:text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                        Hubungkan Akun Affiliate
                    </h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        Pilih akun pengguna yang sudah terdaftar di platform untuk diberikan link referral promosi produk toko Anda.
                    </p>
                </div>
            </div>
            
            <div>
                <a href="{{ route('help.show', 'panduan-cara-merekrut-mitra-afiliasi-pengaturan-bagi-hasil-komisi') }}" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl transition flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[16px] text-sky-500">menu_book</span>
                    Panduan Rekrut Mitra
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 md:p-8 shadow-sm">
            <form action="{{ route('tenant.affiliates.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Pilih Akun Pengguna Terdaftar -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                        Pilih Akun Terdaftar di Platform <span class="text-rose-500">*</span>
                    </label>
                    <select name="user_id" id="userSelect" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition font-medium" required>
                        <option value="">-- Cari & Pilih Akun Pengguna --</option>
                        @foreach($users as $usr)
                            @php
                                $storeInfo = $usr->store ? (' [Toko: ' . $usr->store->name . ']') : ' [User Pembeli]';
                                $alias = $usr->store ? $usr->store->slug : \Illuminate\Support\Str::slug($usr->name);
                            @endphp
                            <option value="{{ $usr->id }}" data-alias="{{ $alias }}" {{ old('user_id') == $usr->id ? 'selected' : '' }}>
                                {{ $usr->name }} ({{ $usr->email }}) {{ $storeInfo }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    <span class="text-[11px] text-slate-400 mt-1 block">Semua akun pengguna yang terdaftar di platform bisa dijadikan mitra afiliasi (baik yang sudah buka toko maupun pengguna biasa).</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Persentase Komisi -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                Bagi Hasil Komisi (%) <span class="text-rose-500">*</span>
                            </label>
                            <a href="{{ route('help.show', 'panduan-cara-merekrut-mitra-afiliasi-pengaturan-bagi-hasil-komisi') }}" target="_blank" class="text-slate-400 hover:text-sky-500 flex items-center gap-0.5 text-[11px]" title="Pelajari rekomendasi bagi hasil">
                                <span class="material-symbols-outlined text-[14px]">help</span> Panduan
                            </a>
                        </div>
                        <div class="relative">
                            <input type="number" step="0.5" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', '10') }}" class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">%</span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 block">Komisi yang didapatkan mitra dari harga produk pada setiap transaksi sukses (umumnya 10% - 20%).</span>
                    </div>

                    <!-- Kode Referral Otomatis Preview -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Kode Referral (Otomatis dari Nama Akun)
                        </label>
                        <div class="px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-100/70 dark:bg-[#0c1220]/70 text-sm font-mono font-bold text-sky-600 dark:text-sky-400 flex items-center justify-between">
                            <span id="previewRefCode">Pilih akun di atas...</span>
                            <span class="material-symbols-outlined text-[16px] text-slate-400">auto_awesome</span>
                        </div>
                        <span class="text-[11px] text-slate-400 mt-1 block">Kode dibuat otomatis dari nama akun saat disimpan.</span>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="p-4 rounded-xl bg-sky-50 dark:bg-sky-950/30 border border-sky-200 dark:border-sky-800/40 text-xs text-sky-800 dark:text-sky-300 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[20px] text-sky-500 shrink-0 mt-0.5">info</span>
                    <div class="space-y-1">
                        <div class="font-bold">Sistem Referral & Komisi Otomatis Terintegrasi</div>
                        <p class="text-slate-600 dark:text-slate-300 leading-relaxed">
                            Setelah dihubungkan, sistem otomatis membuatkan link referral unik toko Anda (<code class="font-mono font-bold text-sky-600 dark:text-sky-400">?ref=KODE</code>). Setiap pembeli yang checkout melalui link tersebut akan otomatis menghasilkan komisi yang langsung masuk ke <strong>Saldo Toko Mitra</strong> dan bisa dicairkan (withdraw) ke rekening bank pribadi mitra.
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-[#222f49]">
                    <a href="{{ route('tenant.affiliates.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#161f33] transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs md:text-sm font-bold shadow-lg shadow-sky-500/25 transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">handshake</span>
                        Hubungkan Akun Mitra
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>

<script>
    document.getElementById('userSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const alias = selected.getAttribute('data-alias');
        const preview = document.getElementById('previewRefCode');
        if (alias) {
            preview.innerText = alias.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 10);
        } else {
            preview.innerText = 'Pilih akun di atas...';
        }
    });
</script>
@endsection
