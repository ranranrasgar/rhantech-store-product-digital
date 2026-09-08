@extends('layouts.tenant')

@section('title', 'Pusat Keuangan & Penghasilan')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Pusat Keuangan & Penghasilan
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pantau arus pendapatan dari penjualan produk sendiri, komisi etalase afiliasi, serta kelola penarikan saldo ke rekening.
                </p>
            </div>
            
            <div class="flex items-center gap-2.5">
                <a href="{{ route('tenant.orders.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-sky-500">receipt_long</span>
                    Operasional Pesanan
                </a>
            </div>
        </div>

        <!-- Session Alerts -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Main Top KPI & Balance Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            
            <!-- Saldo Tersedia Card -->
            <div class="md:col-span-2 relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 md:p-8 border border-slate-800 shadow-xl flex flex-col justify-between">
                <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
                
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur-md border border-white/20 text-sky-300 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Saldo Siap Ditarik
                        </span>
                        <span class="material-symbols-outlined text-white/30 text-[28px]">account_balance_wallet</span>
                    </div>

                    <div class="space-y-1">
                        <div class="text-xs font-medium text-slate-300">Total Saldo Dompet Toko</div>
                        <div class="text-3xl md:text-5xl font-black tracking-tight text-white">
                            Rp {{ number_format($store->balance, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Payout Form Row inside Balance Card -->
                <div class="mt-8 pt-6 border-t border-white/10">
                    <form action="{{ route('tenant.payouts.store') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        @csrf
                        <div class="relative flex-1">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">Rp</span>
                            <input type="number" name="amount" min="10000" max="{{ $store->balance }}" placeholder="Nominal Penarikan (Min. 10.000)" class="w-full pl-10 pr-4 py-2.5 text-xs md:text-sm bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-400 text-white placeholder-slate-400 transition-all backdrop-blur-sm" required {{ $store->balance < 10000 ? 'disabled' : '' }}>
                        </div>
                        <button type="submit" class="px-6 py-2.5 text-xs md:text-sm font-bold bg-sky-500 hover:bg-sky-400 text-white rounded-xl shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 transition-all flex items-center justify-center gap-1.5 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" {{ $store->balance < 10000 ? 'disabled' : '' }}>
                            <span>Ajukan Penarikan</span>
                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </button>
                    </form>
                    
                    @error('amount')
                        <p class="text-rose-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Rekening Bank Status Card -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rekening Pencairan</span>
                        <div class="w-8 h-8 rounded-xl bg-sky-500/10 text-sky-500 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">account_balance</span>
                        </div>
                    </div>

                    @if(!empty($store->bank_account_info))
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49]">
                            <div class="text-xs font-mono text-slate-800 dark:text-slate-200 whitespace-pre-line leading-relaxed">{{ $store->bank_account_info }}</div>
                        </div>
                    @else
                        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400">
                            <div class="flex items-center gap-1.5 font-bold text-xs">
                                <span class="material-symbols-outlined text-[16px]">warning</span> Belum Diatur
                            </div>
                            <p class="text-[11px] mt-1 text-slate-500 dark:text-slate-400">Silakan atur info rekening bank Anda agar dapat mencairkan saldo penghasilan.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-[#1d273d]">
                    <a href="{{ route('tenant.store.index') }}" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center justify-between">
                        <span>Atur Nomor Rekening Bank</span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- 4 KPI Summary Cards (Multi-Source Revenue) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            <!-- 1. Omset Produk Sendiri -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Produk Sendiri</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">inventory_2</span>
                    </div>
                </div>
                <div class="mt-3 text-xl font-black text-slate-900 dark:text-white">
                    Rp {{ number_format($totalOwnRevenue, 0, ',', '.') }}
                </div>
                <div class="mt-1 text-[11px] text-slate-400">
                    {{ $pagedOwnOrders->total() }} transaksi sukses
                </div>
            </div>

            <!-- 2. Etalase Afiliasi (Showcase) -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Etalase Afiliasi</span>
                    <div class="w-8 h-8 rounded-xl bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">shopping_basket</span>
                    </div>
                </div>
                <div class="mt-3 text-xl font-black text-slate-900 dark:text-white">
                    {{ $myShowcaseCount }} Produk
                </div>
                <div class="mt-1 text-[11px] text-slate-400">
                    Dipajang di etalase toko Anda
                </div>
            </div>

            <!-- 3. Mitra & Referral Toko -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Performa Mitra</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">handshake</span>
                    </div>
                </div>
                <div class="mt-3 text-xl font-black text-slate-900 dark:text-white">
                    {{ number_format($totalAffiliateOrders) }} Penjualan
                </div>
                <div class="mt-1 text-[11px] text-slate-400">
                    Dari {{ number_format($totalAffiliateClicks) }} kunjungan referral
                </div>
            </div>

            <!-- 4. Total Sudah Ditarik -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sudah Dicairkan</span>
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
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Navigation Tabs -->
            <div class="border-b border-slate-100 dark:border-[#222f49] px-6 flex items-center gap-6 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                <a href="{{ route('tenant.payouts.index', ['tab' => 'semua']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'semua' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Semua Riwayat Penarikan Dana ({{ $pagedPayouts->total() }})
                </a>
                <a href="{{ route('tenant.payouts.index', ['tab' => 'produk_sendiri']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'produk_sendiri' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Penjualan Produk Sendiri ({{ $pagedOwnOrders->total() }})
                </a>
                <a href="{{ route('tenant.payouts.index', ['tab' => 'afiliasi_showcase']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'afiliasi_showcase' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Etalase Afiliasi ({{ $myShowcaseCount }})
                </a>
                <a href="{{ route('tenant.payouts.index', ['tab' => 'mitra_referral']) }}" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'mitra_referral' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200' }}">
                    Mitra Afiliasi Toko ({{ $myAffiliateMitra->count() }})
                </a>
            </div>

            <!-- TAB 1: RIWAYAT PENARIKAN DANA (PAYOUTS) -->
            @if($tab === 'semua')
            <div class="overflow-x-auto pb-8">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
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
                                <div class="font-bold text-slate-900 dark:text-white text-xs">
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
                            <td class="p-4 md:px-6 text-slate-600 dark:text-slate-400 text-xs">
                                {{ $payout->notes ?: '-' }}
                            </td>
                            <td class="p-4 md:px-6 text-right pr-8">
                                <div class="font-extrabold text-slate-900 dark:text-white text-sm md:text-base">
                                    Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                    <div class="w-16 h-16 rounded-2xl bg-sky-500/10 text-sky-500 flex items-center justify-center mb-4">
                                        <span class="material-symbols-outlined text-[32px]">payments</span>
                                    </div>
                                    <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada penarikan dana</h3>
                                    <p class="text-xs text-slate-400">Gunakan formulir penarikan di atas jika saldo Anda telah mencapai minimal Rp 10.000.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pagedPayouts->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                    {{ $pagedPayouts->links() }}
                </div>
            @endif
            @endif

            <!-- TAB 2: PENJUALAN PRODUK SENDIRI -->
            @if($tab === 'produk_sendiri')
            <div class="overflow-x-auto pb-8">
                <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
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
                                <div class="font-mono font-bold text-slate-900 dark:text-white text-xs">
                                    {{ $ord->invoice_number }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    {{ $ord->created_at->format('d M Y, H:i') }} WIB
                                </div>
                            </td>
                            <td class="p-4 md:px-6">
                                <div class="font-bold text-slate-900 dark:text-white line-clamp-1">
                                    {{ $firstOwn->product->name ?? 'Produk Digital' }}
                                    @if($ownItems->count() > 1)
                                        <span class="text-xs text-sky-500 font-normal">(+{{ $ownItems->count() - 1 }} lainnya)</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">
                                    {{ $firstOwn ? 'Qty: ' . $firstOwn->quantity . 'x' : '1 item' }}
                                </div>
                            </td>
                            <td class="p-4 md:px-6">
                                <div class="font-bold text-slate-800 dark:text-white text-xs">
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
                                <span class="material-symbols-outlined text-4xl mb-2 block">inventory_2</span>
                                Belum ada transaksi pesanan produk sendiri yang selesai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pagedOwnOrders->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                    {{ $pagedOwnOrders->links() }}
                </div>
            @endif
            @endif

            <!-- TAB 3: ETALASE AFILIASI (SHOWCASE) -->
            @if($tab === 'afiliasi_showcase')
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">Katalog Produk yang Anda Pajang di Etalase</h3>
                        <p class="text-xs text-slate-500">Setiap produk ini dibeli oleh pengunjung melalui toko Anda, komisi penjualan akan langsung mengalir ke saldo toko.</p>
                    </div>
                    <a href="{{ route('tenant.showcase.index') }}" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
                        Kelola Etalase Afiliasi
                    </a>
                </div>

                @php
                    $showcaseList = $store->showcaseProducts()->published()->with(['store', 'category', 'images'])->get();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($showcaseList as $p)
                    <div class="p-4 rounded-2xl border border-slate-200 dark:border-[#222f49] bg-slate-50/50 dark:bg-[#0c1220]/50 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-slate-200 dark:bg-slate-700 overflow-hidden shrink-0">
                            @php $img = $p->images->first(); @endphp
                            @if($img)
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400">
                                    <span class="material-symbols-outlined text-[20px]">inventory_2</span>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white truncate">{{ $p->name }}</h4>
                            <div class="text-[11px] text-slate-400 mt-0.5">
                                {{ $p->store ? $p->store->name : 'Platform Official' }}
                            </div>
                            <div class="text-xs font-extrabold text-sky-600 dark:text-sky-400 mt-1">
                                Rp {{ number_format($p->price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full p-12 text-center text-slate-400">
                        <span class="material-symbols-outlined text-4xl mb-2 block">shopping_basket</span>
                        Anda belum memajang produk afiliasi di etalase toko Anda.
                        <div class="mt-3">
                            <a href="{{ route('tenant.showcase.index') }}" class="text-xs font-bold text-sky-500 hover:underline">
                                Buka Katalog & Pasang Produk Sekarang →
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
            @endif

            <!-- TAB 4: MITRA AFILIASI TOKO -->
            @if($tab === 'mitra_referral')
            <div class="p-6 space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-base text-slate-900 dark:text-white">Mitra yang Membantu Menjualkan Produk Toko Anda</h3>
                        <p class="text-xs text-slate-500">Daftar pengguna dan toko lain yang memiliki link referral toko Anda dan mempromosikannya.</p>
                    </div>
                    <a href="{{ route('tenant.affiliates.index') }}" class="px-4 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">person_add</span>
                        Kelola Mitra Afiliasi
                    </a>
                </div>

                <div class="overflow-x-auto pb-4">
                    <table class="w-full text-left text-xs md:text-sm whitespace-nowrap">
                        <thead class="bg-slate-50/80 dark:bg-[#0c1220]/80 border-b border-slate-100 dark:border-[#222f49] text-slate-500 dark:text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
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
                                <td class="p-4 md:px-6 font-bold text-slate-900 dark:text-white">
                                    {{ $mitra->name }}
                                </td>
                                <td class="p-4 md:px-6 font-mono text-sky-600 dark:text-sky-400 font-bold">
                                    {{ $mitra->referral_code }}
                                </td>
                                <td class="p-4 md:px-6 text-emerald-600 font-bold">
                                    {{ $mitra->commission_rate }}%
                                </td>
                                <td class="p-4 md:px-6">
                                    {{ number_format((int)$mitra->clicks_count) }} kali
                                </td>
                                <td class="p-4 md:px-6 text-right pr-8 font-bold text-slate-900 dark:text-white">
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
@endsection
