@extends('layouts.tenant')

@section('title', 'Campaign & Promo')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#fafafa] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-6" x-data="{ viewMode: 'cards' }">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2.5">
                    Campaign & Promo
                </h1>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    Tingkatkan konversi penjualan dengan kupon diskon, voucher potongan harga, dan promo produk.
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('tenant.campaigns.create') }}" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs md:text-sm font-bold transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    Buat Promo Baru
                </a>
            </div>
        </div>

        <!-- View Switcher & Notification -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white dark:bg-[#000000] p-3 rounded-2xl border border-zinc-200 dark:border-zinc-800">
            <div class="flex items-center gap-2">
                <button type="button" 
                        @click="viewMode = 'cards'" 
                        :class="viewMode === 'cards' 
                            ? 'bg-orange-500 text-white dark:bg-orange-600 dark:text-white' 
                            : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[17px]">confirmation_number</span>
                    <span>Kartu Tiket Kupon (Gaya Tokopedia)</span>
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black" :class="viewMode === 'cards' ? 'bg-white/25 text-white dark:bg-slate-800 dark:text-white' : 'bg-slate-200 dark:bg-slate-700 text-zinc-700 dark:text-zinc-300'">
                        {{ $campaigns->count() }}
                    </span>
                </button>
                <button type="button" 
                        @click="viewMode = 'table'" 
                        :class="viewMode === 'table' 
                            ? 'bg-orange-500 text-white dark:bg-orange-600 dark:text-white' 
                            : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[17px]">table_rows</span>
                    <span>Tabel Data Rinci</span>
                </button>
            </div>
            <div class="text-[11px] text-zinc-500 dark:text-zinc-400 flex items-center gap-1.5 px-2">
                <span class="material-symbols-outlined text-slate-400 text-[16px]">verified</span>
                <span>Tiket otomatis tampil di halaman toko, produk & checkout pembeli</span>
            </div>
        </div>

        <!-- 1. TAMPILAN KARTU TIKET VOUCHER (GAYA TOKOPEDIA) -->
        <div x-show="viewMode === 'cards'" class="space-y-6">
            @if($campaigns->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($campaigns as $campaign)
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 transition-colors">
                            <!-- Tokopedia Ticket Card Component -->
                            <x-voucher-card :campaign="$campaign" mode="browse" />

                            <!-- Bottom Seller Actions & Stats -->
                            <div class="mt-3 pt-3 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between gap-3 text-xs">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-1 font-semibold {{ $campaign->status === 'active' ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400' }}">
                                        <span class="w-2 h-2 rounded-full {{ $campaign->status === 'active' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span class="capitalize">{{ $campaign->status }}</span>
                                    </span>
                                    <span class="text-slate-400">•</span>
                                    <span class="text-zinc-500 dark:text-zinc-400">
                                        Terpakai: <strong>{{ $campaign->used_count ?? 0 }}</strong>
                                        @if($campaign->usage_limit)
                                            / {{ $campaign->usage_limit }} kuota
                                        @else
                                            (tanpa batas)
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('tenant.campaigns.edit', $campaign->id) }}" class="px-2.5 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold text-xs flex items-center gap-1 transition-colors">
                                        <span class="material-symbols-outlined text-[14px]">edit</span>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('tenant.campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus voucher ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-100 font-bold text-xs flex items-center gap-1 transition-colors cursor-pointer">
                                            <span class="material-symbols-outlined text-[14px]">delete</span>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8">
                    <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-[32px]">confirmation_number</span>
                    </div>
                    <h3 class="font-bold text-base text-zinc-800 dark:text-zinc-100 mb-1">Belum Ada Kupon Toko</h3>
                    <p class="text-xs text-slate-400 mb-5 max-w-sm mx-auto">Buat kupon potongan harga atau kupon 100% gratis untuk memikat pembeli berbelanja di tokomu.</p>
                    <a href="{{ route('tenant.campaigns.create') }}" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs transition-all active:scale-95 inline-flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        <span>Buat Kupon Pertama</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- 2. TAMPILAN TABEL DATA RINCI -->
        <div x-show="viewMode === 'table'" class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
            
            <div class="p-5 md:p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Daftar Promosi Aktif & Terjadwal</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Kelola kupon diskon dan batas waktu periode campaign toko Anda.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto pb-12">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
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
                                <div class="font-bold text-zinc-900 dark:text-zinc-100 text-xs md:text-sm">
                                    {{ $campaign->name }}
                                </div>
                                @if($campaign->type === 'voucher' && $campaign->code)
                                    <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1.5 font-mono">
                                        <span>Kode:</span>
                                        <span class="px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 font-bold border border-zinc-200 dark:border-zinc-800">
                                            {{ $campaign->code }}
                                        </span>
                                    </div>
                                @endif
                            </td>

                            <!-- Type -->
                            <td class="p-4 md:px-6">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300">
                                        <span class="material-symbols-outlined text-[15px] {{ $campaign->type === 'discount' ? 'text-amber-500' : 'text-slate-500' }}">
                                            {{ $campaign->type === 'discount' ? 'local_offer' : 'confirmation_number' }}
                                        </span>
                                        {{ $campaign->type === 'discount' ? 'Diskon Langsung' : 'Kupon Voucher' }}
                                    </span>
                                    <div>
                                        @if(($campaign->applies_to ?? 'all') === 'all')
                                            <span class="inline-flex items-center gap-1 text-[10px] text-zinc-500 dark:text-zinc-400">
                                                <span class="material-symbols-outlined text-[12px]">storefront</span> Semua Produk
                                            </span>
                                        @elseif($campaign->applies_to === 'category')
                                            <span class="inline-flex items-center gap-1 text-[10px] text-zinc-600 dark:text-zinc-400 font-medium">
                                                <span class="material-symbols-outlined text-[12px]">folder</span> {{ count((array)$campaign->category_ids) }} Kategori
                                            </span>
                                        @elseif($campaign->applies_to === 'product')
                                            <span class="inline-flex items-center gap-1 text-[10px] text-zinc-600 dark:text-zinc-400 font-medium">
                                                <span class="material-symbols-outlined text-[12px]">inventory_2</span> {{ count((array)$campaign->product_ids) }} Produk
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Discount Value -->
                            <td class="p-4 md:px-6">
                                <div class="font-extrabold text-zinc-900 dark:text-zinc-100 text-sm md:text-base">
                                    @if($campaign->discount_type === 'percentage')
                                        {{ rtrim(rtrim($campaign->discount_value, '0'), '.') }}% OFF
                                    @else
                                        Rp {{ number_format($campaign->discount_value, 0, ',', '.') }} OFF
                                    @endif
                                </div>
                            </td>

                            <!-- Period -->
                            <td class="p-4 md:px-6 text-xs text-zinc-500 dark:text-zinc-400 font-mono">
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
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span> Terjadwal
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800">
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
                                    <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-[32px]">campaign</span>
                                    </div>
                                    <h3 class="font-bold text-base text-zinc-800 dark:text-zinc-100 mb-1">Belum ada promo atau voucher</h3>
                                    <p class="text-xs text-slate-400 mb-5">Buat voucher diskon spesial untuk menarik lebih banyak pembeli melakukan checkout.</p>
                                    <a href="{{ route('tenant.campaigns.create') }}" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs transition-all active:scale-95">
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
            <div class="p-5 border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
                {{ $campaigns->links() }}
            </div>
            @endif

        </div>

    </div>
</div>
@endsection
