@extends('layouts.admin')

@section('title', 'Pendapatan Iklan & Saldo Tenant')

@section('content')
<div class="p-lg space-y-lg">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-headline-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[28px]">campaign</span>
                Pendapatan Iklan & Saldo Tenant
            </h2>
            <p class="font-body-md text-on-surface-variant mt-1">
                Pantau seluruh arus kas masuk dari pengisian (top-up) saldo iklan tenant, subsidi voucher promosi, serta performa kampanye iklan aktif.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.stores.index') }}" class="px-3.5 py-2 rounded-lg border border-outline-variant hover:bg-surface-variant/40 text-on-surface text-sm font-bold flex items-center gap-1.5 transition">
                <span class="material-symbols-outlined text-[18px]">storefront</span>
                Daftar Toko Tenant
            </a>
        </div>
    </div>

    <!-- 4 KPI Metrics Card -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-md">
        <!-- 1. Real Cash In (Pemasukan Uang Riil) -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">paid</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-[10px] font-black uppercase">Real Cash</span>
            </div>
            <div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Pemasukan Top-Up Iklan</p>
                <h3 class="text-2xl font-black text-emerald-600">Rp {{ number_format($totalPaidAdRevenue, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-on-surface-variant mt-1">Uang riil via Midtrans QRIS/VA & potong saldo</p>
            </div>
        </div>

        <!-- 2. Saldo Iklan Aktif Beredar -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-[#ea580c] flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-orange-500/10 text-[#ea580c] text-[10px] font-black uppercase">Saldo Beredar</span>
            </div>
            <div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Total Saldo di Dompet Toko</p>
                <h3 class="text-2xl font-black text-[#ea580c]">Rp {{ number_format($totalAdBalanceInCirculation, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-on-surface-variant mt-1">Saldo aktif siap dibelanjakan untuk iklan</p>
            </div>
        </div>

        <!-- 3. Total Subsidi Voucher Platform -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">card_giftcard</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-purple-500/10 text-purple-600 text-[10px] font-black uppercase">Subsidi Promo</span>
            </div>
            <div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Subsidi Voucher 500rb</p>
                <h3 class="text-2xl font-black text-purple-600">Rp {{ number_format($totalVoucherSubsidies, 0, ',', '.') }}</h3>
                <p class="text-[11px] text-on-surface-variant mt-1">Kredit modal promosi gratis pembukaan toko</p>
            </div>
        </div>

        <!-- 4. Kampanye Iklan Berjalan -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-5 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[22px]">trending_up</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-600 text-[10px] font-black uppercase">Traffic Toko</span>
            </div>
            <div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-wider mb-1">Kampanye Iklan Aktif</p>
                <h3 class="text-2xl font-black text-amber-600">{{ number_format($activeAdsCount) }} Kampanye</h3>
                <p class="text-[11px] text-on-surface-variant mt-1">{{ number_format($totalAdClicks) }} klik • {{ number_format($totalAdImpressions) }} tayangan</p>
            </div>
        </div>
    </div>

    <!-- Main Container with Tabs -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-xs overflow-hidden">
        
        <!-- Tab Headers -->
        <div class="border-b border-outline-variant/50 px-6 flex items-center justify-between flex-wrap gap-4 bg-surface-container-low/40">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.ads.index', ['tab' => 'transaksi']) }}" class="py-4 text-sm font-bold border-b-2 transition-colors flex items-center gap-2 {{ $tab === 'transaksi' ? 'text-primary border-primary' : 'text-on-surface-variant border-transparent hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    <span>Riwayat Top-Up Saldo ({{ $transactions->total() }})</span>
                </a>
                <a href="{{ route('admin.ads.index', ['tab' => 'kampanye']) }}" class="py-4 text-sm font-bold border-b-2 transition-colors flex items-center gap-2 {{ $tab === 'kampanye' ? 'text-primary border-primary' : 'text-on-surface-variant border-transparent hover:text-on-surface' }}">
                    <span class="material-symbols-outlined text-[18px]">campaign</span>
                    <span>Monitoring Iklan Tenant ({{ $campaigns->total() }})</span>
                </a>
            </div>

            <!-- Search & Filter Form -->
            <form action="{{ route('admin.ads.index') }}" method="GET" class="flex items-center gap-2 py-2">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[16px] leading-none">search</span>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari invoice/toko..." class="pl-8 pr-3 py-1.5 text-xs bg-surface border border-outline-variant rounded-lg text-on-surface focus:outline-none focus:border-primary">
                </div>
                @if($tab === 'transaksi')
                <select name="status" onchange="this.form.submit()" class="text-xs bg-surface border border-outline-variant rounded-lg px-2.5 py-1.5 text-on-surface focus:outline-none focus:border-primary">
                    <option value="">Semua Status</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @endif
                <button type="submit" class="px-3 py-1.5 bg-primary text-on-primary rounded-lg text-xs font-bold hover:opacity-90 transition">
                    Filter
                </button>
            </form>
        </div>

        <!-- TAB 1: TRANSAKSI TOP-UP SALDO IKLAN -->
        @if($tab === 'transaksi')
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/50 text-xs text-on-surface-variant uppercase font-bold">
                        <th class="p-4">Tanggal & Ref</th>
                        <th class="p-4">Toko (Tenant)</th>
                        <th class="p-4">Metode Pembayaran</th>
                        <th class="p-4 text-right">Nominal Saldo</th>
                        <th class="p-4 text-right">Total Bayar</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-surface-container-low/30 transition-colors">
                            <td class="p-4">
                                <span class="font-mono text-xs font-bold text-on-surface block">{{ $tx->reference_no }}</span>
                                <span class="text-[11px] text-on-surface-variant">{{ $tx->created_at->format('d M Y, H:i') }} WIB</span>
                            </td>
                            <td class="p-4">
                                @if($tx->store)
                                    <div class="font-bold text-on-surface">{{ $tx->store->name }}</div>
                                    <div class="text-[11px] text-on-surface-variant font-mono">{{ $tx->store->slug }}</div>
                                @else
                                    <span class="text-on-surface-variant italic">Toko Dihapus</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($tx->payment_method === 'midtrans')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-600 text-xs font-bold">
                                        <span class="material-symbols-outlined text-[14px]">credit_card</span>
                                        Midtrans (QRIS / VA)
                                    </span>
                                @elseif($tx->payment_method === 'store_balance')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-orange-500/10 text-orange-600 text-xs font-bold">
                                        <span class="material-symbols-outlined text-[14px]">account_balance_wallet</span>
                                        Potong Saldo Penjualan
                                    </span>
                                @elseif($tx->payment_method === 'promo_voucher')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-purple-500/10 text-purple-600 text-xs font-bold">
                                        <span class="material-symbols-outlined text-[14px]">redeem</span>
                                        Subsidi Voucher Toko
                                    </span>
                                @else
                                    <span class="text-xs text-on-surface-variant">{{ ucfirst($tx->payment_method) }}</span>
                                @endif
                                <div class="text-[11px] text-on-surface-variant mt-0.5 line-clamp-1">{{ $tx->description }}</div>
                            </td>
                            <td class="p-4 text-right font-mono font-bold text-on-surface">
                                Rp {{ number_format($tx->amount, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right font-mono font-extrabold text-emerald-600">
                                Rp {{ number_format($tx->total_amount ?? $tx->amount, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                @if($tx->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-xs font-bold">
                                        Lunas
                                    </span>
                                @elseif($tx->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 text-xs font-bold">
                                        Pending
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-rose-500/10 text-rose-600 text-xs font-bold">
                                        {{ ucfirst($tx->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-on-surface-variant text-sm">
                                Belum ada riwayat transaksi pengisian saldo iklan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="p-4 border-t border-outline-variant/50">
            {{ $transactions->links() }}
        </div>
        @endif
        @endif

        <!-- TAB 2: MONITORING IKLAN TENANT -->
        @if($tab === 'kampanye')
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/50 text-xs text-on-surface-variant uppercase font-bold">
                        <th class="p-4">Nama Iklan & Toko</th>
                        <th class="p-4">Produk yang Diiklankan</th>
                        <th class="p-4">Mode & Biaya Klik (CPC)</th>
                        <th class="p-4">Modal / Anggaran</th>
                        <th class="p-4 text-center">Performa</th>
                        <th class="p-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($campaigns as $ad)
                        <tr class="hover:bg-surface-container-low/30 transition-colors">
                            <td class="p-4">
                                <div class="font-bold text-on-surface">{{ $ad->name }}</div>
                                <div class="text-xs text-on-surface-variant flex items-center gap-1 mt-0.5">
                                    <span class="material-symbols-outlined text-[14px] text-primary">storefront</span>
                                    <span>{{ $ad->store ? $ad->store->name : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                @if($ad->product)
                                    <div class="flex items-center gap-2">
                                        @php $pImg = $ad->product->images->where('is_main', true)->first() ?? $ad->product->images->first(); @endphp
                                        <div class="w-9 h-9 rounded-lg overflow-hidden bg-surface-container shrink-0 border border-outline-variant">
                                            @if($pImg)
                                                <img src="{{ asset('storage/' . $pImg->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                                                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('products.show', $ad->product->slug) }}" target="_blank" class="text-xs font-bold text-primary hover:underline line-clamp-1">
                                                {{ $ad->product->name }}
                                            </a>
                                            <span class="text-[11px] font-mono font-bold text-on-surface">Rp {{ number_format($ad->product->price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-on-surface-variant italic">Produk Toko Umum</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ad->bidding_mode === 'manual' ? 'bg-indigo-500/10 text-indigo-600' : 'bg-orange-500/10 text-orange-600' }}">
                                    {{ ucfirst($ad->bidding_mode) }}
                                </span>
                                <div class="text-xs font-bold text-on-surface mt-1">
                                    Rp {{ number_format($ad->cost_per_click, 0, ',', '.') }} <span class="text-[10px] text-on-surface-variant font-normal">/ klik</span>
                                </div>
                            </td>
                            <td class="p-4">
                                @if($ad->budget_type === 'unlimited')
                                    <span class="text-xs text-on-surface-variant font-semibold">Tidak Terbatas</span>
                                @else
                                    <div class="text-xs font-bold text-on-surface">
                                        Rp {{ number_format($ad->daily_budget, 0, ',', '.') }} <span class="text-[10px] text-on-surface-variant font-normal">/ hari</span>
                                    </div>
                                @endif
                                <div class="text-[11px] text-on-surface-variant mt-0.5">
                                    Keluar: <span class="font-bold text-rose-600">Rp {{ number_format($ad->spent_amount, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <div class="inline-flex items-center gap-3 text-xs">
                                    <span title="Impresi / Dilihat">
                                        <strong class="text-on-surface">{{ number_format($ad->views_count) }}</strong> <span class="text-on-surface-variant text-[10px]">View</span>
                                    </span>
                                    <span>•</span>
                                    <span title="Klik Pembeli">
                                        <strong class="text-primary">{{ number_format($ad->clicks_count) }}</strong> <span class="text-on-surface-variant text-[10px]">Klik</span>
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                @if($ad->status === 'active')
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 text-xs font-bold flex items-center justify-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @elseif($ad->status === 'paused')
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-500/10 text-amber-600 text-xs font-bold">
                                        Dijeda
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-slate-500/10 text-slate-600 text-xs font-bold">
                                        {{ ucfirst($ad->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-on-surface-variant text-sm">
                                Belum ada kampanye iklan yang dibuat oleh tenant.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($campaigns->hasPages())
        <div class="p-4 border-t border-outline-variant/50">
            {{ $campaigns->links() }}
        </div>
        @endif
        @endif

    </div>
</div>
@endsection
