@extends('layouts.tenant')

@section('title', 'Pusat Keuangan & Penghasilan')

@section('content')
<div class="flex-1 overflow-y-auto p-3.5 sm:p-4 md:p-8 bg-[#fafafa] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-4 md:space-y-6">

        <!-- Flash Session Alerts -->
        @if (session('success'))
            <div class="p-3.5 md:p-4 rounded-xl md:rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 text-xs md:text-sm font-semibold flex items-center gap-2.5">
                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="p-3.5 md:p-4 rounded-xl md:rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-300 text-xs md:text-sm font-semibold flex items-center gap-2.5">
                <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-[20px]">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- 1. DEDICATED NATIVE MOBILE EXPERIENCE (Visible on Mobile < 768px Only)   -->
        <!-- ========================================================================= -->
        <div class="block md:hidden space-y-4 pb-12">
            
            <!-- Mobile Header -->
            <div class="flex items-center justify-between gap-3 pt-1">
                <div>
                    <h1 class="text-xl font-black tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                        Keuangan Toko
                    </h1>
                    <p class="text-[11px] text-zinc-500 dark:text-zinc-400 mt-0.5">
                        Kelola saldo, pendapatan & pencairan dana
                    </p>
                </div>
                <a href="{{ route('tenant.orders.index') }}" class="shrink-0 px-3 py-2 rounded-xl bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center gap-1.5 active:scale-95 transition-all">
                    <span class="material-symbols-outlined text-[16px] text-zinc-600 dark:text-zinc-300">receipt_long</span>
                    <span>Pesanan</span>
                </a>
            </div>

            <!-- Mobile Saldo Dompet & Tarik Dana Card -->
            <div class="bg-white dark:bg-[#000000] rounded-2xl p-4 border border-zinc-200 dark:border-zinc-800 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Saldo Siap Ditarik
                    </span>
                    <span class="text-[11px] font-bold text-slate-400">Dompet Toko</span>
                </div>

                <div>
                    <div class="text-[11px] text-zinc-500 dark:text-zinc-400">Total Saldo Tersedia</div>
                    <div class="text-3xl font-black tracking-tight text-zinc-900 dark:text-zinc-100 mt-0.5">
                        Rp {{ number_format($store->balance, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Form Tarik Dana Mobile -->
                <form action="{{ route('tenant.payouts.store') }}" method="POST" class="space-y-2.5 pt-2 border-t border-slate-100 dark:border-[#1d273d]" onsubmit="if(!confirm('Ajukan penarikan saldo sebesar Rp ' + this.amount.value + '?')) return false; const btn = this.querySelector('button'); btn.disabled = true; btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.innerHTML = '<span class=\'material-symbols-outlined text-[16px] animate-spin\'>progress_activity</span><span>Memproses...</span>';">
                    @csrf
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="amount" min="10000" max="{{ $store->balance }}" placeholder="Nominal Tarik (Min 10.000)" class="w-full pl-10 pr-3.5 py-2.5 text-xs bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 dark:focus:border-white text-zinc-900 dark:text-zinc-100 placeholder-slate-400 transition-all" required {{ $store->balance < 10000 ? 'disabled' : '' }}>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 text-xs font-bold bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white rounded-xl active:scale-95 transition-all flex items-center justify-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" {{ $store->balance < 10000 ? 'disabled' : '' }}>
                        <span class="material-symbols-outlined text-[16px]">account_balance_wallet</span>
                        <span>Ajukan Penarikan Dana</span>
                    </button>
                </form>

                <!-- Syarat & Fee Info Mobile -->
                <div class="pt-2 border-t border-slate-100 dark:border-[#1d273d] flex flex-wrap items-center justify-between gap-2 text-[10px] text-slate-400">
                    <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-emerald-500">check_circle</span> Min Rp10.000</span>
                    <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-amber-500">schedule</span> 1x24 jam kerja</span>
                    <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[12px] text-slate-500">percent</span> Fee {{ $store->getPayoutFeePercentage() }}%</span>
                </div>
            </div>

            <!-- Mobile Rekening Pencairan Card -->
            <div class="bg-white dark:bg-[#000000] rounded-2xl p-3.5 border border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-400">
                        <span class="material-symbols-outlined text-[15px] text-slate-500">account_balance</span>
                        <span>Rekening Tujuan Transfer</span>
                    </div>
                    @if(!empty($store->bank_account_info))
                        <div class="text-xs font-bold text-zinc-800 dark:text-zinc-200 truncate mt-1">
                            {{ Str::limit(str_replace("\n", " • ", $store->bank_account_info), 45) }}
                        </div>
                    @else
                        <div class="text-xs font-bold text-amber-600 dark:text-amber-400 mt-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">warning</span> Belum diatur
                        </div>
                    @endif
                </div>
                <a href="{{ route('tenant.store.index') }}" class="shrink-0 px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-900 hover:bg-orange-500 hover:text-white dark:hover:bg-orange-500 dark:hover:text-white text-zinc-700 dark:text-zinc-300 text-[11px] font-bold transition-all active:scale-95">
                    {{ empty($store->bank_account_info) ? 'Atur Bank' : 'Ubah' }}
                </a>
            </div>

            <!-- Mobile 4 KPI Compact Cards (2x2 Grid) -->
            <div class="grid grid-cols-2 gap-2.5">
                <!-- 1. Produk Sendiri -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Produk Sendiri</span>
                        <span class="material-symbols-outlined text-[16px] text-indigo-500">inventory_2</span>
                    </div>
                    <div class="mt-2 text-sm font-black text-zinc-900 dark:text-zinc-100">
                        Rp {{ number_format($totalOwnRevenue, 0, ',', '.') }}
                    </div>
                    <div class="mt-0.5 text-[10px] text-slate-400">
                        {{ $pagedOwnOrders->total() }} pesanan sukses
                    </div>
                </div>

                <!-- 2. Komisi Afiliasi -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Komisi Afiliasi</span>
                        <span class="material-symbols-outlined text-[16px] text-slate-500">monetization_on</span>
                    </div>
                    <div class="mt-2 text-sm font-black text-zinc-900 dark:text-zinc-100">
                        Rp {{ number_format($totalAffiliateCommission, 0, ',', '.') }}
                    </div>
                    <div class="mt-0.5 text-[10px] text-slate-400">
                        {{ $totalAffiliateSoldOrdersCount }} pesanan terjual
                    </div>
                </div>

                <!-- 3. Performa Mitra -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mitra Toko</span>
                        <span class="material-symbols-outlined text-[16px] text-amber-500">handshake</span>
                    </div>
                    <div class="mt-2 text-sm font-black text-zinc-900 dark:text-zinc-100">
                        {{ number_format($totalAffiliateOrders) }} Penjualan
                    </div>
                    <div class="mt-0.5 text-[10px] text-slate-400">
                        {{ number_format($totalAffiliateClicks) }} klik referral
                    </div>
                </div>

                <!-- 4. Sudah Dicairkan -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Telah Dicairkan</span>
                        <span class="material-symbols-outlined text-[16px] text-emerald-500">paid</span>
                    </div>
                    <div class="mt-2 text-sm font-black text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}
                    </div>
                    <div class="mt-0.5 text-[10px] text-slate-400">
                        {{ $totalPendingPayout > 0 ? 'Rp '.number_format($totalPendingPayout, 0, ',', '.').' pending' : 'Semua transfer beres' }}
                    </div>
                </div>
            </div>

            <!-- Horizontal Swipeable Pill Tabs Mobile -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 -mx-3.5 px-3.5 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden" style="scrollbar-width: none; -ms-overflow-style: none;">
                <a href="{{ route('tenant.payouts.index', ['tab' => 'semua']) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'semua' ? 'bg-orange-500 text-white dark:bg-orange-600 dark:text-white' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Penarikan</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'semua' ? 'bg-white/20 text-white dark:bg-black/20 dark:text-slate-900' : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400' }}">{{ $pagedPayouts->total() }}</span>
                </a>

                <a href="{{ route('tenant.payouts.index', ['tab' => 'produk_sendiri']) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'produk_sendiri' ? 'bg-orange-500 text-white dark:bg-orange-600 dark:text-white' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Produk Sendiri</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'produk_sendiri' ? 'bg-white/20 text-white dark:bg-black/20 dark:text-slate-900' : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400' }}">{{ $pagedOwnOrders->total() }}</span>
                </a>

                <a href="{{ route('tenant.payouts.index', ['tab' => 'afiliasi_showcase']) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'afiliasi_showcase' ? 'bg-orange-500 text-white dark:bg-orange-600 dark:text-white' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Komisi Afiliasi</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'afiliasi_showcase' ? 'bg-white/20 text-white dark:bg-black/20 dark:text-slate-900' : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400' }}">{{ $pagedAffiliateSoldOrders->total() }}</span>
                </a>

                <a href="{{ route('tenant.payouts.index', ['tab' => 'mitra_referral']) }}" 
                   class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $tab === 'mitra_referral' ? 'bg-orange-500 text-white dark:bg-orange-600 dark:text-white' : 'bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-300 active:scale-95' }}">
                    <span>Mitra Afiliasi</span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $tab === 'mitra_referral' ? 'bg-white/20 text-white dark:bg-black/20 dark:text-slate-900' : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400' }}">{{ $myAffiliateMitra->count() }}</span>
                </a>
            </div>

            <!-- Mobile Feed Cards per Tab (Bukan Tabel) -->
            <div class="space-y-3">
                
                <!-- TAB 1: RIWAYAT PENARIKAN (MOBILE CARDS) -->
                @if($tab === 'semua')
                    @forelse($pagedPayouts as $payout)
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 space-y-3">
                            <div class="flex items-center justify-between gap-2 pb-2.5 border-b border-zinc-100 dark:border-zinc-800">
                                <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400">calendar_today</span>
                                    <span>{{ $payout->created_at->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div>
                                    @if($payout->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Diproses
                                        </span>
                                    @elseif($payout->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Berhasil
                                        </span>
                                    @elseif($payout->status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[10px] text-slate-400 block font-medium">Nominal Penarikan</span>
                                    <span class="text-base font-black text-zinc-900 dark:text-zinc-100">
                                        Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 block font-medium">Fee ({{ $payout->fee_percentage ?? 0 }}%)</span>
                                    <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400">
                                        Rp {{ number_format($payout->fee_amount ?? 0, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            @if($payout->notes)
                                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-100 dark:border-slate-800 text-[11px] text-zinc-600 dark:text-zinc-300">
                                    <span class="font-bold text-slate-500 block text-[10px] mb-0.5">Catatan Admin:</span>
                                    {{ $payout->notes }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-slate-500 flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-[26px]">payments</span>
                            </div>
                            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200">Belum Ada Penarikan Dana</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Ajukan penarikan di atas jika saldo toko Anda telah mencapai minimal Rp10.000.</p>
                        </div>
                    @endforelse

                    @if($pagedPayouts->hasPages())
                        <div class="pt-2 flex justify-center">
                            {{ $pagedPayouts->links() }}
                        </div>
                    @endif
                @endif

                <!-- TAB 2: PRODUK SENDIRI (MOBILE CARDS) -->
                @if($tab === 'produk_sendiri')
                    @forelse($pagedOwnOrders as $ord)
                        @php
                            $ownItems = $ord->orderItems->filter(fn($it) => $it->product && $it->product->store_id == $store->id);
                            $firstOwn = $ownItems->first() ?? $ord->orderItems->first();
                            $ownTotal = $ownItems->isNotEmpty() ? $ownItems->sum(fn($it) => $it->price * $it->quantity) : $ord->amount;
                        @endphp
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 space-y-3">
                            <div class="flex items-center justify-between gap-2 pb-2 border-b border-zinc-100 dark:border-zinc-800">
                                <div class="font-mono font-black text-xs text-zinc-900 dark:text-zinc-100">
                                    {{ $ord->invoice_number }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $ord->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="text-xs font-bold text-zinc-800 dark:text-zinc-200 line-clamp-1">
                                    {{ $firstOwn->product->name ?? 'Produk Digital' }}
                                    @if($ownItems->count() > 1)
                                        <span class="text-[10px] text-slate-500 font-normal">(+{{ $ownItems->count() - 1 }} lainnya)</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Pembeli: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $ord->customer_name }}</span> ({{ $ord->customer_email }})
                                </div>
                            </div>

                            <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                <span class="text-[10px] text-slate-400 font-medium">Pendapatan Bersih</span>
                                <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">
                                    + Rp {{ number_format($ownTotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-slate-500 flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-[26px]">inventory_2</span>
                            </div>
                            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200">Belum Ada Penjualan</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Transaksi produk toko Anda yang berhasil akan otomatis tercatat di sini.</p>
                        </div>
                    @endforelse

                    @if($pagedOwnOrders->hasPages())
                        <div class="pt-2 flex justify-center">
                            {{ $pagedOwnOrders->links() }}
                        </div>
                    @endif
                @endif

                <!-- TAB 3: AFILIASI SHOWCASE (MOBILE CARDS) -->
                @if($tab === 'afiliasi_showcase')
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 flex items-center justify-between gap-3">
                        <div>
                            <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-zinc-700 dark:text-zinc-300">storefront</span>
                                <span>{{ $myShowcaseCount }} Produk Dipajang</span>
                            </div>
                            <div class="text-[10px] text-slate-500 mt-0.5">Komisi masuk otomatis saat terjual via link Anda.</div>
                        </div>
                        <a href="{{ route('tenant.showcase.index') }}" class="shrink-0 px-3 py-1.5 rounded-lg bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-[10px] font-bold active:scale-95 transition-all">
                            Kelola
                        </a>
                    </div>

                    @forelse($pagedAffiliateSoldOrders as $affOrder)
                        @php
                            $firstItem = $affOrder->orderItems->first();
                            $productOwner = $firstItem?->product?->store?->name ?? ($affOrder->product?->store?->name ?? 'Platform Official');
                            $productName = $firstItem?->product?->name ?? ($affOrder->product?->name ?? 'Produk Afiliasi');
                        @endphp
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 space-y-3">
                            <div class="flex items-center justify-between gap-2 pb-2 border-b border-zinc-100 dark:border-zinc-800">
                                <div class="font-mono font-black text-xs text-zinc-900 dark:text-zinc-100">
                                    {{ $affOrder->invoice_number }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $affOrder->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </div>

                            <div class="space-y-1">
                                <div class="text-xs font-bold text-zinc-800 dark:text-zinc-200 line-clamp-1">
                                    {{ $productName }}
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    Pemilik: <span class="font-semibold text-zinc-700 dark:text-zinc-300">{{ $productOwner }}</span>
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    Pembeli: {{ $affOrder->customer_name }}
                                </div>
                            </div>

                            <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                                <span class="text-[10px] text-slate-500 font-bold">Komisi Afiliasi Cair</span>
                                <span class="text-sm font-black text-emerald-600 dark:text-emerald-400">
                                    + Rp {{ number_format($affOrder->affiliate_commission, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-[26px]">monetization_on</span>
                            </div>
                            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200">Belum Ada Komisi Afiliasi</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Pajang produk toko lain di etalase Anda untuk mendapatkan komisi penjualan.</p>
                            <a href="{{ route('tenant.showcase.index') }}" class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-bold rounded-xl active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[15px]">storefront</span>
                                <span>Pilih Produk Etalase</span>
                            </a>
                        </div>
                    @endforelse

                    @if($pagedAffiliateSoldOrders->hasPages())
                        <div class="pt-2 flex justify-center">
                            {{ $pagedAffiliateSoldOrders->links() }}
                        </div>
                    @endif
                @endif

                <!-- TAB 4: MITRA AFILIASI TOKO (MOBILE CARDS) -->
                @if($tab === 'mitra_referral')
                    <div class="flex items-center justify-between pb-1">
                        <span class="text-xs font-bold text-zinc-700 dark:text-zinc-300">Mitra Promosi Toko</span>
                        <a href="{{ route('tenant.affiliates.index') }}" class="text-[11px] font-bold text-zinc-700 dark:text-zinc-300 hover:underline flex items-center gap-1">
                            <span>Kelola Mitra</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>

                    @forelse($myAffiliateMitra as $mitra)
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 space-y-2.5">
                            <div class="flex items-center justify-between">
                                <div class="font-bold text-xs text-zinc-900 dark:text-zinc-100">
                                    {{ $mitra->name }}
                                </div>
                                <span class="px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-900 font-mono text-[10px] font-bold text-zinc-800 dark:text-zinc-200">
                                    {{ $mitra->referral_code }}
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center py-2 bg-slate-50 dark:bg-[#0c1220] rounded-xl text-zinc-700 dark:text-zinc-300">
                                <div>
                                    <div class="text-[9px] text-slate-400">Bagi Hasil</div>
                                    <div class="text-xs font-bold text-emerald-600">{{ $mitra->commission_rate }}%</div>
                                </div>
                                <div>
                                    <div class="text-[9px] text-slate-400">Kunjungan</div>
                                    <div class="text-xs font-bold">{{ number_format((int)$mitra->clicks_count) }}</div>
                                </div>
                                <div>
                                    <div class="text-[9px] text-slate-400">Penjualan</div>
                                    <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100">{{ number_format((int)$mitra->orders_count) }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-[26px]">handshake</span>
                            </div>
                            <h3 class="text-sm font-bold text-zinc-800 dark:text-zinc-200">Belum Ada Mitra Terhubung</h3>
                            <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">Ajak kreator atau marketer untuk menjadi mitra afiliasi dan promosikan toko Anda.</p>
                            <a href="{{ route('tenant.affiliates.index') }}" class="inline-flex items-center gap-1.5 mt-3 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-bold rounded-xl active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[15px]">person_add</span>
                                <span>Undang Mitra</span>
                            </a>
                        </div>
                    @endforelse
                @endif

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 2. PRESERVED DESKTOP VIEW (Visible on Desktop >= 768px Only)              -->
        <!-- ========================================================================= -->
        <div class="hidden md:block space-y-6">

            <!-- Desktop Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2.5">
                        Pusat Keuangan & Penghasilan
                    </h1>
                    <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                        Pantau arus pendapatan dari penjualan produk sendiri, komisi etalase afiliasi, serta kelola penarikan saldo ke rekening.
                    </p>
                </div>
                
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('tenant.orders.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold transition-all flex items-center gap-2 active:scale-95">
                        <span class="material-symbols-outlined text-[18px] text-slate-500">receipt_long</span>
                        Operasional Pesanan
                    </a>
                </div>
            </div>

            <!-- Main Top KPI & Balance Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                
                <!-- Saldo Tersedia Card -->
                <div class="md:col-span-2 bg-white dark:bg-[#000000] rounded-3xl p-6 md:p-8 border border-zinc-200 dark:border-zinc-800 flex flex-col justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/50 text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Saldo Siap Ditarik
                            </span>
                            <div class="w-10 h-10 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[20px]">account_balance_wallet</span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400">Total Saldo Dompet Toko</div>
                            <div class="text-3xl md:text-5xl font-black tracking-tight text-zinc-900 dark:text-zinc-100">
                                Rp {{ number_format($store->balance, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <!-- Payout Form Row inside Balance Card -->
                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-[#1d273d] relative z-10">
                        <form action="{{ route('tenant.payouts.store') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3" onsubmit="if(!confirm('Ajukan penarikan saldo sebesar Rp ' + this.amount.value + '?')) return false; const btn = this.querySelector('button'); btn.disabled = true; btn.classList.add('opacity-75', 'cursor-not-allowed'); btn.innerHTML = '<span class=\'material-symbols-outlined text-[16px] animate-spin\'>progress_activity</span><span>Memproses...</span>';">
                            @csrf
                            <div class="relative flex-1">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                                <input type="number" name="amount" min="10000" max="{{ $store->balance }}" placeholder="Nominal Penarikan (Min. 10.000)" class="w-full pl-12 pr-4 py-3 text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-900 dark:focus:border-white text-zinc-900 dark:text-zinc-100 placeholder-slate-400 transition-all" required {{ $store->balance < 10000 ? 'disabled' : '' }}>
                            </div>
                            <button type="submit" class="px-6 py-3 text-sm font-bold bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white rounded-xl active:scale-95 transition-all flex items-center justify-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed" {{ $store->balance < 10000 ? 'disabled' : '' }}>
                                <span>Tarik Dana</span>
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </button>
                        </form>
                        
                        @error('amount')
                            <p class="text-rose-500 text-xs mt-2 font-medium">{{ $message }}</p>
                        @enderror

                        <!-- Summary Rules Note -->
                        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-[#1d273d] flex flex-wrap items-center justify-between gap-3 text-[11px] text-zinc-500 dark:text-zinc-400">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-emerald-500">check_circle</span> Min. Rp10.000</span>
                                <span class="hidden sm:block opacity-30">•</span>
                                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-amber-500">schedule</span> Maks. 1x24 jam kerja</span>
                                <span class="hidden sm:block opacity-30">•</span>
                                <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-[14px] text-slate-500">percent</span> Fee platform {{ $store->getPayoutFeePercentage() }}% 
                                    @if($store->isPro())
                                        <span class="ml-1 px-1.5 py-0.5 rounded text-[9px] bg-amber-500 text-white font-bold flex items-center gap-0.5"><span class="material-symbols-outlined text-[10px]">star</span> VIP</span>
                                    @else
                                        <a href="{{ route('tenant.pro.index') }}" class="ml-1 text-[10px] text-amber-500 hover:text-amber-600 dark:text-amber-400 underline">(Diskon 1% dgn PRO)</a>
                                    @endif
                                </span>
                            </div>
                            <a href="{{ route('help.show', 'panduan-aturan-resmi-penarikan-dana-payout-hasil-penjualan-tenant') }}" target="_blank" class="text-zinc-600 dark:text-zinc-300 hover:underline flex items-center gap-1 font-medium">
                                <span>Info Penarikan</span>
                                <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Rekening Bank Status Card -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">Rekening Pencairan</span>
                            <div class="w-8 h-8 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">account_balance</span>
                            </div>
                        </div>

                        @if(!empty($store->bank_account_info))
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800">
                                <div class="text-xs font-mono text-zinc-800 dark:text-zinc-200 whitespace-pre-line leading-relaxed">{{ $store->bank_account_info }}</div>
                            </div>
                        @else
                            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400">
                                <div class="flex items-center gap-1.5 font-bold text-xs">
                                    <span class="material-symbols-outlined text-[16px]">warning</span> Belum Diatur
                                </div>
                                <p class="text-[11px] mt-1 text-zinc-500 dark:text-zinc-400">Silakan atur info rekening bank Anda agar dapat mencairkan saldo penghasilan.</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-[#1d273d]">
                        <a href="{{ route('tenant.store.index') }}" class="text-xs font-bold text-zinc-700 dark:text-zinc-300 hover:underline flex items-center justify-between">
                            <span>Atur Nomor Rekening Bank</span>
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- 4 KPI Summary Cards (Multi-Source Revenue) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- 1. Omset Produk Sendiri -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Produk Sendiri</span>
                        <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                        </div>
                    </div>
                    <div class="mt-3 text-xl font-black text-zinc-900 dark:text-zinc-100">
                        Rp {{ number_format($totalOwnRevenue, 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-[11px] text-slate-400">
                        {{ $pagedOwnOrders->total() }} transaksi sukses
                    </div>
                </div>

                <!-- 2. Komisi Afiliasi (Terjual via Etalase / Referral) -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Komisi Afiliasi</span>
                        <div class="w-8 h-8 rounded-xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">monetization_on</span>
                        </div>
                    </div>
                    <div class="mt-3 text-xl font-black text-zinc-900 dark:text-zinc-100">
                        Rp {{ number_format($totalAffiliateCommission, 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-[11px] text-slate-400 flex items-center justify-between">
                        <span>{{ $totalAffiliateSoldOrdersCount }} pesanan terjual</span>
                        <span class="text-zinc-600 dark:text-zinc-400 font-medium">({{ $myShowcaseCount }} dipajang)</span>
                    </div>
                </div>

                <!-- 3. Mitra & Referral Toko -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Performa Mitra</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">handshake</span>
                        </div>
                    </div>
                    <div class="mt-3 text-xl font-black text-zinc-900 dark:text-zinc-100">
                        {{ number_format($totalAffiliateOrders) }} Penjualan
                    </div>
                    <div class="mt-1 text-[11px] text-slate-400">
                        Dari {{ number_format($totalAffiliateClicks) }} kunjungan referral
                    </div>
                </div>

                <!-- 4. Total Sudah Ditarik -->
                <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Sudah Dicairkan</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">paid</span>
                        </div>
                    </div>
                    <div class="mt-3 text-xl font-black text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($totalWithdrawn, 0, ',', '.') }}
                    </div>
                    <div class="mt-1 text-[11px] text-slate-400">
                        @if($totalPendingPayout > 0)
                            <span class="text-amber-500 font-bold">Rp {{ number_format($totalPendingPayout, 0, ',', '.') }} pending</span>
                        @else
                            Semua selesai ditransfer
                        @endif
                    </div>
                </div>

            </div>

            <!-- Main Multi-Source Revenue Card & Tabs -->
            <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
                
                <!-- Navigation Tabs -->
                <div class="border-b border-zinc-100 dark:border-zinc-800 px-6 flex items-center gap-6 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                    <a href="{{ route('tenant.payouts.index', ['tab' => 'semua']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'semua' ? 'text-zinc-900 dark:text-zinc-100 border-slate-900 dark:border-white' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Semua Riwayat Penarikan Dana ({{ $pagedPayouts->total() }})
                    </a>
                    <a href="{{ route('tenant.payouts.index', ['tab' => 'produk_sendiri']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'produk_sendiri' ? 'text-zinc-900 dark:text-zinc-100 border-slate-900 dark:border-white' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Penjualan Produk Sendiri ({{ $pagedOwnOrders->total() }})
                    </a>
                    <a href="{{ route('tenant.payouts.index', ['tab' => 'afiliasi_showcase']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'afiliasi_showcase' ? 'text-zinc-900 dark:text-zinc-100 border-slate-900 dark:border-white' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Komisi Penjualan Afiliasi ({{ $pagedAffiliateSoldOrders->total() }})
                    </a>
                    <a href="{{ route('tenant.payouts.index', ['tab' => 'mitra_referral']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'mitra_referral' ? 'text-zinc-900 dark:text-zinc-100 border-slate-900 dark:border-white' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                        Mitra Afiliasi Toko ({{ $myAffiliateMitra->count() }})
                    </a>
                </div>

                <!-- TAB 1: RIWAYAT PENARIKAN DANA (PAYOUTS) -->
                @if($tab === 'semua')
                <div class="overflow-x-auto pb-8">
                    <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                        <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="p-4 md:px-6">Waktu Pengajuan</th>
                                <th class="p-4 md:px-6">Status Transfer</th>
                                <th class="p-4 md:px-6">Catatan Admin</th>
                                <th class="p-4 md:px-6 text-right pr-8">Nominal Penarikan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                            @forelse($pagedPayouts as $payout)
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                                <td class="p-4 md:px-6">
                                    <div class="font-bold text-zinc-900 dark:text-zinc-100 text-xs">
                                        {{ $payout->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                        {{ $payout->created_at->format('H:i') }} WIB
                                    </div>
                                </td>
                                <td class="p-4 md:px-6">
                                    @if($payout->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Sedang Diproses Admin
                                        </span>
                                    @elseif($payout->status === 'approved')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Berhasil Ditransfer
                                        </span>
                                    @elseif($payout->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 md:px-6 text-zinc-600 dark:text-zinc-400 text-xs">
                                    {{ $payout->notes ?: '-' }}
                                </td>
                                <td class="p-4 md:px-6 text-right pr-8">
                                    <div class="font-extrabold text-zinc-900 dark:text-zinc-100 text-sm md:text-base">
                                        Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-16 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                        <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mb-4">
                                            <span class="material-symbols-outlined text-[32px]">payments</span>
                                        </div>
                                        <h3 class="font-bold text-base text-zinc-800 dark:text-zinc-100 mb-1">Belum ada penarikan dana</h3>
                                        <p class="text-xs text-slate-400">Gunakan formulir penarikan di atas jika saldo Anda telah mencapai minimal Rp 10.000.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pagedPayouts->hasPages())
                    <div class="p-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
                        {{ $pagedPayouts->links() }}
                    </div>
                @endif
                @endif

                <!-- TAB 2: PENJUALAN PRODUK SENDIRI -->
                @if($tab === 'produk_sendiri')
                <div class="overflow-x-auto pb-8">
                    <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                        <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
                            <tr>
                                <th class="p-4 md:px-6">Invoice & Tanggal</th>
                                <th class="p-4 md:px-6 min-w-[240px]">Produk Toko</th>
                                <th class="p-4 md:px-6">Pembeli</th>
                                <th class="p-4 md:px-6 text-right pr-8">Pendapatan Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                            @forelse($pagedOwnOrders as $ord)
                            @php
                                $ownItems = $ord->orderItems->filter(fn($it) => $it->product && $it->product->store_id == $store->id);
                                $firstOwn = $ownItems->first() ?? $ord->orderItems->first();
                                $ownTotal = $ownItems->isNotEmpty() ? $ownItems->sum(fn($it) => $it->price * $it->quantity) : $ord->amount;
                            @endphp
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                                <td class="p-4 md:px-6">
                                    <div class="font-mono font-bold text-zinc-900 dark:text-zinc-100 text-xs">
                                        {{ $ord->invoice_number }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">
                                        {{ $ord->created_at->format('d M Y, H:i') }} WIB
                                    </div>
                                </td>
                                <td class="p-4 md:px-6">
                                    <div class="font-bold text-zinc-900 dark:text-zinc-100 line-clamp-1">
                                        {{ $firstOwn->product->name ?? 'Produk Digital' }}
                                        @if($ownItems->count() > 1)
                                            <span class="text-xs text-slate-500 font-normal">(+{{ $ownItems->count() - 1 }} lainnya)</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">
                                        {{ $firstOwn ? 'Qty: ' . $firstOwn->quantity . 'x' : '1 item' }}
                                    </div>
                                </td>
                                <td class="p-4 md:px-6">
                                    <div class="font-bold text-zinc-800 dark:text-zinc-100 text-xs">
                                        {{ $ord->customer_name }}
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $ord->customer_email }}
                                    </div>
                                </td>
                                <td class="p-4 md:px-6 text-right pr-8">
                                    <div class="font-extrabold text-emerald-600 dark:text-emerald-400 text-sm md:text-base">
                                        + Rp {{ number_format($ownTotal, 0, ',', '.') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400">Masuk ke Saldo</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-16 text-center text-slate-400">
                                    <span class="material-symbols-outlined text-4xl mb-2 block text-slate-400">inventory_2</span>
                                    Belum ada transaksi pesanan produk sendiri yang selesai.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pagedOwnOrders->hasPages())
                    <div class="p-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
                        {{ $pagedOwnOrders->links() }}
                    </div>
                @endif
                @endif

                <!-- TAB 3: KOMISI PENJUALAN AFILIASI (TERJUAL) & ETALASE -->
                @if($tab === 'afiliasi_showcase')
                <div class="space-y-6">
                    <!-- Info Banner Etalase -->
                    <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50/50 dark:bg-[#0c1220]/50">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-500 text-white dark:bg-orange-600 dark:text-white text-xs font-black">
                                    {{ $myShowcaseCount }}
                                </span>
                                <h3 class="font-bold text-sm md:text-base text-zinc-900 dark:text-zinc-100">Produk Aktif Dipajang di Etalase Toko</h3>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Setiap kali pembeli checkout produk etalase atau lewat link referral toko Anda, komisi penjualan otomatis masuk ke saldo.</p>
                        </div>
                        <a href="{{ route('tenant.showcase.index') }}" class="px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shrink-0 active:scale-95">
                            <span class="material-symbols-outlined text-[16px]">storefront</span>
                            Kelola Etalase Produk Afiliasi ({{ $myShowcaseCount }})
                        </a>
                    </div>

                    <!-- Tabel Riwayat Komisi Barang Terjual -->
                    <div class="overflow-x-auto pb-8">
                        <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                            <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th class="p-4 md:px-6">Invoice & Tanggal</th>
                                    <th class="p-4 md:px-6 min-w-[220px]">Produk yang Terjual</th>
                                    <th class="p-4 md:px-6">Pemilik Produk / Toko</th>
                                    <th class="p-4 md:px-6">Pembeli</th>
                                    <th class="p-4 md:px-6 text-right pr-8">Komisi Anda</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                                @forelse($pagedAffiliateSoldOrders as $affOrder)
                                @php
                                    $firstItem = $affOrder->orderItems->first();
                                    $productOwner = $firstItem?->product?->store?->name ?? ($affOrder->product?->store?->name ?? 'Platform Official');
                                    $productName = $firstItem?->product?->name ?? ($affOrder->product?->name ?? 'Produk Afiliasi');
                                @endphp
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                                    <td class="p-4 md:px-6">
                                        <div class="font-mono font-bold text-zinc-900 dark:text-zinc-100 text-xs">
                                            {{ $affOrder->invoice_number }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $affOrder->created_at->format('d M Y, H:i') }} WIB
                                        </div>
                                    </td>
                                    <td class="p-4 md:px-6">
                                        <div class="font-bold text-zinc-900 dark:text-zinc-100 line-clamp-1">
                                            {{ $productName }}
                                            @if($affOrder->orderItems->count() > 1)
                                                <span class="text-xs text-slate-500 font-normal">(+{{ $affOrder->orderItems->count() - 1 }} item lainnya)</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 font-mono mt-0.5">
                                            Total Belanja: Rp {{ number_format($affOrder->amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="p-4 md:px-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300">
                                            <span class="material-symbols-outlined text-[14px] text-slate-400">store</span>
                                            {{ $productOwner }}
                                        </span>
                                    </td>
                                    <td class="p-4 md:px-6">
                                        <div class="font-bold text-zinc-800 dark:text-zinc-100 text-xs">
                                            {{ $affOrder->customer_name }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $affOrder->customer_email }}
                                        </div>
                                    </td>
                                    <td class="p-4 md:px-6 text-right pr-8">
                                        <div class="font-extrabold text-emerald-600 dark:text-emerald-400 text-sm md:text-base">
                                            + Rp {{ number_format($affOrder->affiliate_commission, 0, ',', '.') }}
                                        </div>
                                        <div class="text-[10px] text-slate-500 font-bold">Komisi Afiliasi Cair</div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-16 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-16 h-16 rounded-2xl bg-zinc-100 dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 flex items-center justify-center mb-4">
                                                <span class="material-symbols-outlined text-[32px]">monetization_on</span>
                                            </div>
                                            <h3 class="font-bold text-base text-zinc-800 dark:text-zinc-100 mb-1">Belum Ada Produk Afiliasi yang Terjual</h3>
                                            <p class="text-xs text-slate-400 mb-4">Pajang produk menarik dari toko lain di etalase Anda atau bagikan link toko Anda untuk mulai menghasilkan komisi setiap penjualan!</p>
                                            <a href="{{ route('tenant.showcase.index') }}" class="px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-bold transition flex items-center gap-1.5 active:scale-95">
                                                <span class="material-symbols-outlined text-[16px]">shopping_cart_checkout</span>
                                                Pilih Produk untuk Dipajang Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pagedAffiliateSoldOrders->hasPages())
                        <div class="p-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-center">
                            {{ $pagedAffiliateSoldOrders->links() }}
                        </div>
                    @endif
                </div>
                @endif

                <!-- TAB 4: MITRA AFILIASI TOKO -->
                @if($tab === 'mitra_referral')
                <div class="p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base text-zinc-900 dark:text-zinc-100">Mitra yang Membantu Menjualkan Produk Toko Anda</h3>
                            <p class="text-xs text-slate-500">Daftar pengguna dan toko lain yang memiliki link referral toko Anda dan mempromosikannya.</p>
                        </div>
                        <a href="{{ route('tenant.affiliates.index') }}" class="px-4 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-bold transition flex items-center gap-1.5 active:scale-95">
                            <span class="material-symbols-outlined text-[16px]">person_add</span>
                            Kelola Mitra Afiliasi
                        </a>
                    </div>

                    <div class="overflow-x-auto pb-4">
                        <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                            <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-zinc-100 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-semibold uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th class="p-4 md:px-6">Nama Mitra</th>
                                    <th class="p-4 md:px-6">Kode Referral</th>
                                    <th class="p-4 md:px-6">Bagi Hasil</th>
                                    <th class="p-4 md:px-6">Jumlah Klik</th>
                                    <th class="p-4 md:px-6 text-right pr-8">Pesanan Sukses</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                                @forelse($myAffiliateMitra as $mitra)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50">
                                    <td class="p-4 md:px-6 font-bold text-zinc-900 dark:text-zinc-100">
                                        {{ $mitra->name }}
                                    </td>
                                    <td class="p-4 md:px-6 font-mono text-zinc-800 dark:text-zinc-200 font-bold">
                                        {{ $mitra->referral_code }}
                                    </td>
                                    <td class="p-4 md:px-6 text-emerald-600 font-bold">
                                        {{ $mitra->commission_rate }}%
                                    </td>
                                    <td class="p-4 md:px-6">
                                        {{ number_format((int)$mitra->clicks_count) }} kali
                                    </td>
                                    <td class="p-4 md:px-6 text-right pr-8 font-bold text-zinc-900 dark:text-zinc-100">
                                        {{ number_format((int)$mitra->orders_count) }} pesanan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-400">
                                        Belum ada mitra afiliasi yang terhubung dengan toko Anda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>

        </div>

    </div>
</div>
@endsection
