@extends('layouts.tenant')

@section('title', 'Campaign & Promo')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Campaign & Promo
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Tingkatkan konversi penjualan dengan kupon diskon, voucher potongan harga, dan promo produk.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('tenant.campaigns.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs md:text-sm font-bold shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all duration-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Buat Promo Baru
                </a>
            </div>
        </div>

        <!-- Main Card Table -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#222f49] flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Daftar Promosi Aktif & Terjadwal</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Kelola kupon diskon dan batas waktu periode campaign toko Anda.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto pb-12">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="p-4 md:px-6 min-w-[260px]">Nama Promo & Kode</th>
                            <th class="p-4 md:px-6">Tipe Promosi</th>
                            <th class="p-4 md:px-6">Besaran Diskon</th>
                            <th class="p-4 md:px-6">Periode Berlaku</th>
                            <th class="p-4 md:px-6 text-center">Status</th>
                            <th class="p-4 md:px-6 text-right pr-8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($campaigns as $campaign)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                            
                            <!-- Name & Code -->
                            <td class="p-4 md:px-6 whitespace-normal min-w-[260px]">
                                <div class="font-bold text-slate-900 dark:text-white text-xs md:text-sm">
                                    {{ $campaign->name }}
                                </div>
                                @if($campaign->type === 'voucher' && $campaign->code)
                                    <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1.5 font-mono">
                                        <span>Kode:</span>
                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-sky-600 dark:text-sky-400 font-bold border border-slate-200 dark:border-slate-700">
                                            {{ $campaign->code }}
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <!-- Type -->
                            <td class="p-4 md:px-6">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-[15px] {{ $campaign->type === 'discount' ? 'text-amber-500' : 'text-sky-500' }}">
                                        {{ $campaign->type === 'discount' ? 'local_offer' : 'confirmation_number' }}
                                    </span>
                                    {{ $campaign->type === 'discount' ? 'Diskon Langsung' : 'Kupon Voucher' }}
                                </span>
                            </td>

                            <!-- Discount Value -->
                            <td class="p-4 md:px-6">
                                <div class="font-extrabold text-sky-600 dark:text-sky-400 text-sm md:text-base">
                                    @if($campaign->discount_type === 'percentage')
                                        {{ rtrim(rtrim($campaign->discount_value, '0'), '.') }}% OFF
                                    @else
                                        Rp {{ number_format($campaign->discount_value, 0, ',', '.') }} OFF
                                    @endif
                                </div>
                            </td>

                            <!-- Period -->
                            <td class="p-4 md:px-6 text-xs text-slate-500 dark:text-slate-400 font-mono">
                                <div><span class="text-slate-400 font-sans">Mulai:</span> {{ $campaign->start_date->format('d M Y, H:i') }}</div>
                                <div class="mt-0.5"><span class="text-slate-400 font-sans">Selesai:</span> {{ $campaign->end_date->format('d M Y, H:i') }}</div>
                            </td>

                            <!-- Status -->
                            <td class="p-4 md:px-6 text-center">
                                @if($campaign->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                                    </span>
                                @elseif($campaign->status === 'scheduled')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-100 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 border border-sky-200 dark:border-sky-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span> Terjadwal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Berakhir
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="p-4 md:px-6 text-right pr-8">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('tenant.campaigns.edit', $campaign->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-colors inline-flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[15px]">edit</span> Edit
                                    </a>
                                    <form action="{{ route('tenant.campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Hapus promo {{ $campaign->name }}?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-400 text-xs font-bold transition-colors">
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
                                        <span class="material-symbols-outlined text-[32px]">campaign</span>
                                    </div>
                                    <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada promo atau voucher</h3>
                                    <p class="text-xs text-slate-400 mb-5">Buat voucher diskon spesial untuk menarik lebih banyak pembeli melakukan checkout.</p>
                                    <a href="{{ route('tenant.campaigns.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs shadow-lg shadow-sky-500/25 transition-all">
                                        Buat Promo Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            @if($campaigns->hasPages())
            <div class="p-5 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                {{ $campaigns->links() }}
            </div>
            @endif

        </div>

    </div>
</div>
@endsection
