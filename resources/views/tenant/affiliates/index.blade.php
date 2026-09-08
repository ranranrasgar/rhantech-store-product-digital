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
                <a href="{{ route('tenant.affiliates.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs md:text-sm font-bold shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all duration-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah Mitra Affiliate
                </a>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 shadow-sm space-y-5">
            <form method="GET" action="{{ route('tenant.affiliates.index') }}" id="filterForm" class="space-y-4">
                <!-- Search -->
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
                    <div class="flex-1 relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama affiliate atau username..." class="w-full pl-10 pr-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all">
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="px-5 py-2.5 text-xs md:text-sm font-bold bg-sky-500 hover:bg-sky-400 text-white rounded-xl shadow-sm transition-colors">
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
                            <button type="button" onclick="document.getElementById('platformInput').value='{{ $plat }}'; document.getElementById('filterForm').submit();" class="px-3.5 py-1.5 rounded-full text-xs font-semibold transition-all {{ $reqPlatform === $plat ? 'bg-sky-500 text-white shadow-sm shadow-sky-500/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                                {{ $plat }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
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
                            <th class="p-4 md:px-6 min-w-[220px]">Link Referral Toko</th>
                            <th class="p-4 md:px-6">Bagi Hasil</th>
                            <th class="p-4 md:px-6">Jumlah Klik</th>
                            <th class="p-4 md:px-6">Pesanan Sukses</th>
                            <th class="p-4 md:px-6 text-right pr-8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($affiliates as $aff)
                        @php
                            $refLink = route('public.store.show', $store->slug ?? 'store') . '?ref=' . ($aff->referral_code ?? $aff->id);
                        @endphp
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                            
                            <!-- Affiliate Profile -->
                            <td class="p-4 md:px-6 whitespace-normal min-w-[260px]">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0">
                                        @if($aff->avatar_url)
                                            <img src="{{ $aff->avatar_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center font-black text-sky-500 bg-sky-50 dark:bg-sky-950/40 text-base">
                                                {{ substr($aff->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 dark:text-white flex items-center gap-1 leading-snug">
                                            {{ $aff->name }}
                                            @if($aff->is_golden_tick)
                                                <span class="material-symbols-outlined text-[15px] text-sky-500" title="Mitra Terverifikasi">verified</span>
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
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-mono font-bold bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 border border-sky-200/60 dark:border-sky-800/40">
                                        {{ $aff->referral_code ?? 'AFF' . $aff->id }}
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <input type="text" readonly value="{{ $refLink }}" class="text-[11px] font-mono bg-slate-100 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] px-2 py-1 rounded-lg text-slate-600 dark:text-slate-400 w-36 truncate focus:outline-none" id="ref_{{ $aff->id }}">
                                        <button type="button" onclick="navigator.clipboard.writeText('{{ $refLink }}'); alert('Link referral berhasil disalin!');" class="p-1 text-slate-500 hover:text-sky-500 bg-white dark:bg-[#161f33] border border-slate-200 dark:border-[#222f49] rounded-lg transition" title="Salin Link">
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
                                    <div class="w-16 h-16 rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-[32px]">handshake</span>
                                    </div>
                                    <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada mitra affiliate</h3>
                                    <p class="text-xs text-slate-400 mb-5">Daftarkan kreator atau teman promotor untuk membantu menjualkan produk toko Anda.</p>
                                    <a href="{{ route('tenant.affiliates.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs shadow-lg shadow-sky-500/25 transition-all">
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

    </div>
</div>
@endsection
