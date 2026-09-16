@extends('layouts.admin')
@section('title', 'Paket Toko PRO & Transaksi')

@section('content')
<div class="p-lg md:p-xl flex-1 flex flex-col gap-lg max-w-container-max mx-auto w-full"
     x-data="{
        activeTab: '{{ (request()->filled('search_sub') || request()->filled('status_sub') || request()->filled('sub_page')) ? 'transactions' : 'plans' }}'
     }">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-headline-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500 text-[28px]">workspace_premium</span>
                Manajemen Paket &amp; Transaksi Toko PRO
            </h2>
            <p class="text-sm text-on-surface-variant mt-1">
                Atur paket langganan dan pantau riwayat transaksi pembelian paket PRO oleh pemilik toko.
            </p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('help.show', 'panduan-admin-manajemen-paket-langganan-toko-pro') }}" target="_blank" class="px-3.5 py-2 rounded-lg border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 font-bold text-xs transition flex items-center gap-1.5 hover:bg-amber-100">
                <span class="material-symbols-outlined text-[16px]">menu_book</span>
                Panduan Paket PRO
            </a>
            <a href="{{ route('tenant.pro.index') }}" target="_blank" class="px-3.5 py-2 rounded-lg border border-outline-variant bg-surface-container-low hover:bg-surface-variant text-on-surface font-bold text-xs transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">visibility</span>
                Lihat di Dashboard Tenant
            </a>
            <a href="{{ route('admin.pro_plans.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold text-xs hover:bg-primary/90 transition-colors flex items-center gap-1.5 shadow-sm" wire:navigate>
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Paket PRO
            </a>
        </div>
    </div>

    {{-- Metric Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">verified</span>
            </div>
            <div>
                <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Toko PRO Aktif</div>
                <div class="text-xl font-black text-on-surface mt-0.5">{{ number_format($activeProStoresCount ?? 0) }} <span class="text-xs font-normal text-on-surface-variant">Toko</span></div>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-100 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">receipt_long</span>
            </div>
            <div>
                <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Transaksi Berhasil</div>
                <div class="text-xl font-black text-on-surface mt-0.5">{{ number_format($totalPaidSubCount ?? 0) }} <span class="text-xs font-normal text-on-surface-variant">Transaksi</span></div>
            </div>
        </div>

        <div class="p-4 rounded-xl bg-surface-container-lowest border border-outline-variant shadow-sm flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[24px]">payments</span>
            </div>
            <div>
                <div class="text-[11px] font-bold text-on-surface-variant uppercase tracking-wider">Total Omset Langganan</div>
                <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">Rp {{ number_format($totalProRevenue ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 flex items-center gap-2 shadow-xs">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 flex items-center gap-2 shadow-xs">
            <span class="material-symbols-outlined text-rose-600">error</span>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Tabs Navigation --}}
    <div class="flex items-center gap-2 border-b border-outline-variant/60 pb-px">
        <button type="button" 
                @click="activeTab = 'plans'" 
                :class="activeTab === 'plans' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-4 py-2.5 border-b-2 text-sm transition flex items-center gap-2 cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">format_list_bulleted</span>
            Daftar Paket PRO (Master Data)
            <span class="px-2 py-0.5 rounded-full text-[11px] bg-surface-container font-semibold" :class="activeTab === 'plans' ? 'bg-primary/20 text-primary' : ''">{{ $plans->count() }}</span>
        </button>

        <button type="button" 
                @click="activeTab = 'transactions'" 
                :class="activeTab === 'transactions' ? 'border-primary text-primary font-bold bg-primary/5' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline-variant'"
                class="px-4 py-2.5 border-b-2 text-sm transition flex items-center gap-2 cursor-pointer">
            <span class="material-symbols-outlined text-[18px]">point_of_sale</span>
            Riwayat Transaksi Langganan PRO
            @if(isset($subscriptions))
            <span class="px-2 py-0.5 rounded-full text-[11px] bg-surface-container font-semibold" :class="activeTab === 'transactions' ? 'bg-primary/20 text-primary' : ''">{{ $subscriptions->total() }}</span>
            @endif
        </button>
    </div>

    {{-- TAB 1: DAFTAR PAKET PRO --}}
    <div x-show="activeTab === 'plans'" x-cloak class="space-y-4">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/50">
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Urutan</th>
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Nama Paket</th>
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Kode / Slug</th>
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Harga</th>
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Masa Aktif</th>
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Badge / Label</th>
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Status</th>
                            <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs text-right pr-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30">
                        @forelse($plans as $plan)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                <td class="p-4 font-bold text-on-surface-variant text-center w-12">
                                    {{ $plan->sort_order }}
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-on-surface flex items-center gap-2">
                                        {{ $plan->name }}
                                        @if($plan->is_popular)
                                            <span class="bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-300 text-[10px] font-black px-2 py-0.5 rounded-full uppercase">
                                                Rekomendasi
                                            </span>
                                        @endif
                                    </div>
                                    @if($plan->description)
                                        <div class="text-xs text-on-surface-variant mt-0.5 line-clamp-1 max-w-xs">
                                            {{ $plan->description }}
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 font-mono text-xs">
                                    <span class="bg-surface-variant px-2.5 py-1 rounded-md text-on-surface font-semibold">
                                        {{ $plan->slug }}
                                    </span>
                                </td>
                                <td class="p-4 font-bold text-on-surface">
                                    Rp {{ number_format($plan->price, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-on-surface">
                                    @if($plan->duration_days)
                                        <span class="font-semibold">{{ $plan->duration_days }} Hari</span>
                                        <span class="text-xs text-on-surface-variant block">{{ $plan->duration_label }}</span>
                                    @else
                                        <span class="font-bold text-purple-600 dark:text-purple-400 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">all_inclusive</span>
                                            Lifetime
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-xs">
                                    @if($plan->badge)
                                        <span class="inline-block bg-surface-variant px-2.5 py-1 rounded-md font-medium text-on-surface">
                                            {{ $plan->badge }}
                                        </span>
                                    @else
                                        <span class="text-on-surface-variant/60">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <form action="{{ route('admin.pro_plans.toggle-active', $plan->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="cursor-pointer">
                                            @if($plan->is_active)
                                                <span class="bg-[#e6f4ea] text-[#137333] px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 hover:opacity-80 transition" title="Klik untuk nonaktifkan">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Aktif
                                                </span>
                                            @else
                                                <span class="bg-[#fce8e6] text-[#c5221f] px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 hover:opacity-80 transition" title="Klik untuk aktifkan">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#c5221f]"></span> Nonaktif
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4 text-right pr-6">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.pro_plans.edit', $plan->id) }}" class="text-secondary hover:bg-secondary/10 p-1.5 rounded-lg transition-colors" title="Edit Paket" wire:navigate>
                                            <span class="material-symbols-outlined text-[1.25rem]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.pro_plans.destroy', $plan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket PRO {{ $plan->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-error hover:bg-error/10 p-1.5 rounded-lg transition-colors cursor-pointer" title="Hapus Paket">
                                                <span class="material-symbols-outlined text-[1.25rem]">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-on-surface-variant">
                                    Belum ada paket Toko PRO. Klik tombol "Tambah Paket PRO" untuk mulai membuat paket.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB 2: RIWAYAT TRANSAKSI LANGGANAN PRO --}}
    <div x-show="activeTab === 'transactions'" x-cloak class="space-y-4">
        {{-- Filter Box Transaksi --}}
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.pro_plans.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                <input type="hidden" name="tab" value="transactions">

                <div class="relative flex-1 min-w-[240px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 text-[18px]">search</span>
                    <input type="text" 
                           name="search_sub" 
                           value="{{ request('search_sub') }}" 
                           placeholder="Cari no invoice, nama toko, pemilik, atau paket..." 
                           class="w-full pl-9 pr-4 py-2 rounded-lg bg-surface-container-low border border-outline-variant text-on-surface text-xs focus:outline-none focus:border-primary">
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <select name="status_sub" class="py-2 px-3 rounded-lg bg-surface-container-low border border-outline-variant text-on-surface text-xs font-medium">
                        <option value="all" {{ request('status_sub') === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="paid" {{ request('status_sub') === 'paid' ? 'selected' : '' }}>Paid (Berhasil)</option>
                        <option value="pending" {{ request('status_sub') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status_sub') === 'failed' ? 'selected' : '' }}>Failed / Batal</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-bold text-xs hover:bg-primary/90 transition flex items-center gap-1 shadow-xs cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span>
                        Cari Transaksi
                    </button>

                    @if(request()->filled('search_sub') || request()->filled('status_sub'))
                    <a href="{{ route('admin.pro_plans.index', ['tab' => 'transactions']) }}" class="px-3 py-2 bg-surface-container text-on-surface-variant hover:text-on-surface rounded-lg font-semibold text-xs border border-outline-variant transition">
                        Reset
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Riwayat Transaksi --}}
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant/50 text-xs uppercase tracking-wider text-on-surface-variant font-bold">
                            <th class="p-4">No. Invoice / Ref</th>
                            <th class="p-4">Toko &amp; Pemilik</th>
                            <th class="p-4">Paket Dipilih</th>
                            <th class="p-4">Nominal</th>
                            <th class="p-4">Metode Bayar</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4">Waktu Transaksi</th>
                            <th class="p-4">Masa Berlaku</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/30 text-xs">
                        @forelse($subscriptions as $sub)
                            <tr class="hover:bg-surface-container-low/50 transition-colors">
                                {{-- Invoice / Ref --}}
                                <td class="p-4 whitespace-nowrap">
                                    <div class="font-mono font-bold text-primary">{{ $sub->reference_no }}</div>
                                    <div class="text-[10px] text-on-surface-variant/70 mt-0.5">ID: #{{ $sub->id }}</div>
                                </td>

                                {{-- Store & Owner --}}
                                <td class="p-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-surface-variant border border-outline-variant overflow-hidden shrink-0 flex items-center justify-center">
                                            @if($sub->store && $sub->store->logo)
                                                <img src="{{ asset('storage/' . $sub->store->logo) }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant">storefront</span>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            @if($sub->store)
                                                <a href="{{ route('store.show', $sub->store->slug) }}" target="_blank" class="font-bold text-on-surface hover:text-primary transition flex items-center gap-1 truncate max-w-[160px]">
                                                    <span>{{ $sub->store->name }}</span>
                                                    <span class="material-symbols-outlined text-[13px] text-on-surface-variant">open_in_new</span>
                                                </a>
                                            @else
                                                <span class="font-bold text-on-surface-variant italic">Toko Dihapus</span>
                                            @endif
                                            <div class="text-[11px] text-on-surface-variant truncate max-w-[160px]">
                                                {{ $sub->user->name ?? 'User #' . $sub->user_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Paket --}}
                                <td class="p-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 font-bold border border-amber-200 dark:border-amber-800 uppercase text-[11px]">
                                        <span class="material-symbols-outlined text-[14px]">workspace_premium</span>
                                        {{ $sub->plan }}
                                    </span>
                                </td>

                                {{-- Nominal --}}
                                <td class="p-4 font-bold text-on-surface whitespace-nowrap">
                                    Rp {{ number_format($sub->amount, 0, ',', '.') }}
                                </td>

                                {{-- Metode Bayar --}}
                                <td class="p-4 whitespace-nowrap">
                                    @if(strtolower($sub->payment_method) === 'saldo toko')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-sky-50 dark:bg-sky-950 text-sky-700 dark:text-sky-300 font-semibold text-[11px]">
                                            <span class="material-symbols-outlined text-[13px]">account_balance_wallet</span>
                                            Potong Saldo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-semibold text-[11px]">
                                            <span class="material-symbols-outlined text-[13px]">qr_code_2</span>
                                            {{ $sub->payment_method ?: 'QRIS / Midtrans' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if($sub->payment_status === 'paid')
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 text-[11px] font-bold border border-emerald-300 dark:border-emerald-800">
                                            ✓ Berhasil (Paid)
                                        </span>
                                    @elseif($sub->payment_status === 'pending')
                                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 text-[11px] font-bold border border-amber-300 dark:border-amber-800">
                                            ⏳ Menunggu (Pending)
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-rose-100 text-rose-800 dark:bg-rose-950/80 dark:text-rose-300 text-[11px] font-bold border border-rose-300 dark:border-rose-800">
                                            ✕ Gagal / Expired
                                        </span>
                                    @endif
                                </td>

                                {{-- Waktu Transaksi --}}
                                <td class="p-4 whitespace-nowrap">
                                    <div class="font-bold text-on-surface">{{ $sub->created_at->format('d M Y H:i') }}</div>
                                    <div class="text-[10px] text-on-surface-variant/70">{{ $sub->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Masa Berlaku --}}
                                <td class="p-4 whitespace-nowrap">
                                    @if($sub->expires_at)
                                        <div class="font-bold text-on-surface">s/d {{ $sub->expires_at->format('d M Y') }}</div>
                                        <div class="text-[10px] {{ $sub->expires_at->isPast() ? 'text-rose-600 font-bold' : 'text-emerald-600' }}">
                                            {{ $sub->expires_at->isPast() ? 'Sudah Berakhir' : 'Aktif (' . $sub->expires_at->diffForHumans() . ')' }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center gap-1 font-bold text-purple-600 dark:text-purple-400">
                                            <span class="material-symbols-outlined text-[14px]">all_inclusive</span>
                                            Seumur Hidup (Lifetime)
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-on-surface-variant">
                                    Belum ada riwayat transaksi langganan Toko PRO.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(isset($subscriptions) && $subscriptions->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low">
                {{ $subscriptions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
