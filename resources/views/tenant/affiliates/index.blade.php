@extends('layouts.tenant')

@section('title', 'Program Affiliate')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Program Affiliate
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola mitra promotor dan kreator konten untuk memperluas jangkauan penjualan produk digital Anda.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('help.show', 'panduan-lengkap-cara-kerja-program-afiliasi-toko-mitra-toko-vs-etalase-afiliasi') }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold rounded-xl transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px] text-slate-700 dark:text-slate-300">menu_book</span>
                    Buku Panduan Afiliasi
                </a>
                <a href="{{ route('tenant.affiliates.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white text-xs md:text-sm font-bold transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah Mitra Manual
                </a>
            </div>
        </div>

        <!-- Card Pengaturan Default Komisi Afiliasi Toko -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-1 max-w-2xl">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700">Program Referral Toko</span>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">&bull; Otomatis Untuk Semua Mitra</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-900 dark:text-white text-[22px]">loyalty</span>
                    Persentase Komisi Referral Toko Anda
                </h3>
                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                    Setiap orang atau toko lain yang mengunjungi website toko Anda (<strong>{{ url('/' . $store->slug) }}</strong>) dapat langsung menyalin link referral mereka. Saat ada transaksi lewat link tersebut, mereka otomatis terdaftar di sini dan mendapatkan persentase komisi ini.
                </p>
            </div>
            
            <form action="{{ route('tenant.affiliates.default_commission') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0 w-full md:w-auto">
                @csrf
                <div class="relative w-full sm:w-40">
                    <input type="number" name="default_affiliate_commission" min="0" max="100" step="0.5" 
                           value="{{ old('default_affiliate_commission', $store->default_affiliate_commission ?? 10) }}" 
                           class="w-full pl-4 pr-9 py-2.5 bg-white dark:bg-[#0c1220] border border-slate-300 dark:border-slate-700 rounded-xl text-sm font-black text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 dark:focus:border-white">
                    <span class="absolute right-3.5 top-3 text-xs font-black text-slate-400">%</span>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white rounded-xl text-xs font-bold transition whitespace-nowrap flex items-center justify-center gap-1.5 cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    Simpan Komisi
                </button>
            </form>
        </div>

        <!-- Filter & Search Card -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 space-y-5">
            <form method="GET" action="{{ route('tenant.affiliates.index') }}" id="filterForm" class="space-y-4">
                <!-- Search -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                    <div class="flex-1 relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama affiliate atau username..." class="w-full pl-10 pr-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 text-slate-900 dark:text-white transition-all">
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="px-5 py-2.5 text-xs md:text-sm font-bold bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white rounded-xl transition cursor-pointer active:scale-95">
                            Cari
                        </button>
                        <a href="{{ route('tenant.affiliates.index') }}" class="px-4 py-2.5 text-xs md:text-sm font-semibold border border-slate-200 dark:border-[#222f49] text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors">
                            Reset
                        </a>
                    </div>
                </div>

                <!-- Filter Kategori & Platform Badges -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 pt-2 border-t border-slate-100 dark:border-[#1d273d]">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[80px]">Platform</span>
                    <div class="flex flex-wrap gap-2">
                        @php $reqPlatform = request('platform', 'Semua'); @endphp
                        <input type="hidden" name="platform" id="platformInput" value="{{ $reqPlatform }}">
                        @foreach(['Semua', 'Instagram', 'Tiktok', 'Facebook', 'Youtube', 'Twitter'] as $plat)
                            <button type="button" onclick="document.getElementById('platformInput').value='{{ $plat }}'; document.getElementById('filterForm').submit();" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ $reqPlatform === $plat ? 'bg-sky-500 text-white dark:bg-sky-600 dark:text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                {{ $plat }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl overflow-hidden">
            
            <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#222f49] flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Daftar Mitra Terdaftar</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Total {{ $affiliates->total() }} mitra affiliate aktif dalam program Anda.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto pb-12">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="p-4 md:px-6 min-w-[260px]">Profil Mitra</th>
                            <th class="p-4 md:px-6 min-w-[220px]">
                                <div class="flex items-center gap-1">
                                    <span>Link Referral Toko</span>
                                    <a href="{{ route('help.show', 'panduan-cara-merekrut-mitra-afiliasi-pengaturan-bagi-hasil-komisi') }}" target="_blank" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors" title="Buka Panduan Link Referral">
                                        <span class="material-symbols-outlined text-[15px]">help</span>
                                    </a>
                                </div>
                            </th>
                            <th class="p-4 md:px-6">
                                <div class="flex items-center gap-1">
                                    <span>Bagi Hasil</span>
                                    <a href="{{ route('help.show', 'panduan-cara-merekrut-mitra-afiliasi-pengaturan-bagi-hasil-komisi') }}" target="_blank" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors" title="Buka Panduan Aturan Bagi Hasil Komisi">
                                        <span class="material-symbols-outlined text-[15px]">help</span>
                                    </a>
                                </div>
                            </th>
                            <th class="p-4 md:px-6">
                                <div class="flex items-center gap-1">
                                    <span>Jumlah Klik</span>
                                    <a href="{{ route('help.show', 'panduan-teknis-pelacakan-klik-otomatisasi-saldo-komisi-penarikan-dana-mitra') }}" target="_blank" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors" title="Buka Panduan Pelacakan Kunjungan (Klik)">
                                        <span class="material-symbols-outlined text-[15px]">help</span>
                                    </a>
                                </div>
                            </th>
                            <th class="p-4 md:px-6">
                                <div class="flex items-center gap-1">
                                    <span>Pesanan Sukses</span>
                                    <a href="{{ route('help.show', 'panduan-teknis-pelacakan-klik-otomatisasi-saldo-komisi-penarikan-dana-mitra') }}" target="_blank" class="text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors" title="Buka Panduan Otomatisasi Saldo Komisi Penjualan">
                                        <span class="material-symbols-outlined text-[15px]">help</span>
                                    </a>
                                </div>
                            </th>
                            <th class="p-4 md:px-6 text-right pr-8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($affiliates as $aff)
                        @php
                            $refLink = route('store.show', $store->slug ?? 'store') . '?ref=' . ($aff->referral_code ?? $aff->id);
                        @endphp
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                            
                            <!-- Affiliate Profile -->
                            <td class="p-4 md:px-6 whitespace-normal min-w-[260px]">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0">
                                        @if($aff->avatar_url)
                                            <img src="{{ $aff->avatar_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-black text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 text-base">
                                                {{ substr($aff->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1 leading-snug">
                                            {{ $aff->name }}
                                            @if($aff->is_golden_tick)
                                                <span class="material-symbols-outlined text-[15px] text-slate-900 dark:text-white" title="Mitra Terverifikasi">verified</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5 font-mono flex items-center gap-2">
                                            <span>{{ $aff->handle }}</span>
                                            @if($aff->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $aff->whatsapp) }}" target="_blank" class="text-emerald-500 hover:underline flex items-center gap-0.5 font-sans text-[10px]">
                                                    <span class="material-symbols-outlined text-[12px]">chat</span> WA
                                                </a>
                                            @endif
                                        </div>
                                        <div class="mt-1 flex items-center gap-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                                {{ $aff->platform ?? 'Multi-Platform' }}
                                            </span>
                                            @if($aff->followers_count && $aff->followers_count !== '-')
                                                <span class="text-[10px] text-slate-400 font-medium">
                                                    {{ $aff->followers_count }} fans
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Referral Link & Code -->
                            <td class="p-4 md:px-6 min-w-[220px]">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-700">
                                        {{ $aff->referral_code ?? 'AFF' . $aff->id }}
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" readonly value="{{ $refLink }}" class="text-[11px] font-mono bg-slate-100 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] px-2 py-1 rounded-lg text-slate-600 dark:text-slate-400 w-36 truncate focus:outline-none" id="ref_{{ $aff->id }}">
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $refLink }}'); alert('Link referral berhasil disalin!');" class="p-1 text-slate-500 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg transition" title="Salin Link">
                                            <span class="material-symbols-outlined text-[14px]">content_copy</span>
                                        </button>
                                    </div>
                                </div>
                            </td>

                            <!-- Commission Rate -->
                            <td class="p-4 md:px-6">
                                <div class="font-bold text-emerald-600 dark:text-emerald-400 text-sm">
                                    {{ $aff->commission_rate ?? 10 }}%
                                </div>
                                <div class="text-[10px] text-slate-400">Komisi Penjualan</div>
                            </td>

                            <!-- Clicks -->
                            <td class="p-4 md:px-6">
                                <div class="font-semibold text-slate-700 dark:text-slate-300">
                                    {{ number_format((int)$aff->clicks_count) }}
                                </div>
                                <div class="text-[10px] text-slate-400">Total Kunjungan</div>
                            </td>

                            <!-- Orders -->
                            <td class="p-4 md:px-6">
                                <div class="font-semibold text-slate-700 dark:text-slate-300">
                                    {{ number_format((int)$aff->orders_count) }}
                                </div>
                                <div class="text-[10px] text-slate-400">Transaksi Berhasil</div>
                            </td>

                            <!-- Actions -->
                            <td class="p-4 md:px-6 text-right pr-8">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.affiliates.edit', $aff->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px]">edit</span> Edit
                                    </a>
                                    <form action="{{ route('tenant.affiliates.destroy', $aff->id) }}" method="POST" onsubmit="return confirm('Hapus mitra affiliate {{ $aff->name }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-bold transition-colors" title="Hapus">
                                            <span class="material-symbols-outlined text-[15px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-[32px]">handshake</span>
                                    </div>
                                    <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada mitra affiliate</h3>
                                    <p class="text-xs text-slate-400 mb-5">Daftarkan kreator atau teman promotor untuk membantu menjualkan produk toko Anda.</p>
                                    <a href="{{ route('tenant.affiliates.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white font-bold text-xs transition-all active:scale-95">
                                        Tambah Mitra Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            @if($affiliates->hasPages())
            <div class="p-5 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                {{ $affiliates->links() }}
            </div>
            @endif

        </div>

        <!-- Panduan & FAQ Singkat Cara Kerja Afiliasi -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 md:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 dark:border-[#222f49] pb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[22px]">help_outline</span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Panduan Cara Main & Alur Komisi Afiliasi</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pahami alur otomatisasi dari klik referral hingga uang komisi masuk ke saldo mitra.</p>
                    </div>
                </div>
                <a href="{{ route('help.show', 'panduan-lengkap-cara-kerja-program-afiliasi-toko-mitra-toko-vs-etalase-afiliasi') }}" target="_blank" class="text-xs font-bold text-slate-900 dark:text-white hover:underline inline-flex items-center gap-1">
                    Baca Buku Panduan Lengkap
                    <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                </a>
            </div>

            <!-- 3 Langkah Alur Kerja -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="p-5 rounded-xl border border-slate-200/80 dark:border-[#222f49] bg-slate-50/50 dark:bg-[#0c1220]/50 space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-sky-500 text-white font-black text-xs flex items-center justify-center shrink-0">1</span>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white">Hubungkan & Bagikan Link</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Pilih akun teman/influencer, tetapkan persen bagi hasil (misal: 10%), lalu salin link referral (<code class="text-slate-900 dark:text-slate-200">?ref=KODE</code>) untuk dibagikan ke WhatsApp atau medsos.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-slate-200/80 dark:border-[#222f49] bg-slate-50/50 dark:bg-[#0c1220]/50 space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-sky-500 text-white font-black text-xs flex items-center justify-center shrink-0">2</span>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white">Pelacakan Kunjungan Otomatis</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Saat pembeli membuka link tersebut, sistem otomatis menaikkan counter <strong>Jumlah Klik</strong> dan mengikat sesi belanja calon pembeli dengan ID mitra referral.
                    </p>
                </div>

                <div class="p-5 rounded-xl border border-slate-200/80 dark:border-[#222f49] bg-slate-50/50 dark:bg-[#0c1220]/50 space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-emerald-500 text-white font-black text-xs flex items-center justify-center shrink-0">3</span>
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white">Komisi Masuk & Bisa Ditarik</h3>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Saat pesanan berhasil dibayar, dana komisi seketika masuk ke <strong>Saldo Toko Mitra</strong> (<code class="text-emerald-600 dark:text-emerald-400">balance</code>) dan mitra bisa langsung mencairkannya ke rekening bank via menu Keuangan!
                    </p>
                </div>
            </div>

            <!-- Tanya Jawab Cepat -->
            <div class="border-t border-slate-100 dark:border-[#222f49] pt-5 space-y-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400">Pertanyaan Umum (FAQ)</h4>
                
                <details class="group rounded-xl border border-slate-200/80 dark:border-[#222f49] p-4 [&_summary::-webkit-details-marker]:hidden bg-white dark:bg-[#111726]">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-xs sm:text-sm text-slate-800 dark:text-slate-200">
                        <span>Apakah saldo toko yang terafiliasi akan otomatis bertambah saat ada transaksi?</span>
                        <span class="material-symbols-outlined text-[18px] text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                        <strong>Ya, 100% otomatis!</strong> Ketika pembeli membayar pesanan (baik via QRIS, Virtual Account, e-Wallet, maupun konfirmasi manual admin), sistem secara real-time menambahkan komisi ke saldo akun toko mitra dan mencatat kenaikan jumlah pesanan sukses.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200/80 dark:border-[#222f49] p-4 [&_summary::-webkit-details-marker]:hidden bg-white dark:bg-[#111726]">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-xs sm:text-sm text-slate-800 dark:text-slate-200">
                        <span>Apa bedanya "Mitra Affiliate" (/tenant/affiliates) dengan "Etalase Afiliasi" (/tenant/showcase)?</span>
                        <span class="material-symbols-outlined text-[18px] text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                        <strong>Mitra Affiliate:</strong> Tempat Anda mengajak orang lain menjadi tim penjual untuk produk toko Anda sendiri.<br>
                        <strong>Etalase Afiliasi (Showcase):</strong> Tempat Anda memilih produk milik orang lain/platform untuk dipajang di etalase toko Anda sendiri agar Anda mendapat komisi saat produk tersebut laku terjual.
                    </p>
                </details>

                <details class="group rounded-xl border border-slate-200/80 dark:border-[#222f49] p-4 [&_summary::-webkit-details-marker]:hidden bg-white dark:bg-[#111726]">
                    <summary class="flex items-center justify-between cursor-pointer font-bold text-xs sm:text-sm text-slate-800 dark:text-slate-200">
                        <span>Bagaimana cara mitra menarik (withdraw) uang komisinya?</span>
                        <span class="material-symbols-outlined text-[18px] text-slate-400 group-open:rotate-180 transition-transform">expand_more</span>
                    </summary>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                        Mitra cukup membuka menu <strong>Pusat Keuangan & Penghasilan</strong> (<code>/tenant/payouts</code>), lalu memasukkan nominal penarikan (minimal Rp 10.000). Dana akan ditransfer oleh tim platform ke rekening bank mitra dalam 1x24 jam kerja.
                    </p>
                </details>
            </div>
        </div>

    </div>
</div>
@endsection
