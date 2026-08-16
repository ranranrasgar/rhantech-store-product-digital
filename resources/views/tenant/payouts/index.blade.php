@extends('layouts.tenant')

@section('title', 'Saldo & Penarikan Dana')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Saldo & Pencairan Dana
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola saldo pendapatan dari penjualan produk digital dan ajukan penarikan ke rekening bank Anda.
                </p>
            </div>
            
            <a href="{{ route('tenant.orders.index') }}" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold transition-all shadow-sm flex items-center gap-2 self-start sm:self-auto">
                <span class="material-symbols-outlined text-[18px] text-sky-500">receipt_long</span>
                Riwayat Transaksi
            </a>
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
                        <div class="text-xs font-medium text-slate-300">Total Saldo Penghasilan</div>
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
                            <input type="number" name="amount" min="10000" max="{{ $store->balance }}" placeholder="Nominal (Min. 10.000)" class="w-full pl-10 pr-4 py-2.5 text-xs md:text-sm bg-white/10 border border-white/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-400 text-white placeholder-slate-400 transition-all backdrop-blur-sm" required {{ $store->balance < 10000 ? 'disabled' : '' }}>
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
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Rekening Tujuan</span>
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
                            <p class="text-[11px] mt-1 text-slate-500 dark:text-slate-400">Silakan atur info rekening bank Anda agar dapat melakukan pencairan saldo.</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-[#1d273d]">
                    <a href="{{ route('tenant.store.index') }}" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center justify-between">
                        <span>Perbarui Rekening Bank</span>
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </a>
                </div>
            </div>

        </div>

        <!-- History Table Section -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#222f49] flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white">Riwayat Pengajuan Penarikan Dana</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar transfer saldo penghasilan dari toko ke rekening pribadi Anda.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto pb-12">
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
                        @forelse($payouts as $payout)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-[#151e30]/50 transition-colors">
                            
                            <!-- Date -->
                            <td class="p-4 md:px-6">
                                <div class="font-bold text-slate-900 dark:text-white text-xs">
                                    {{ $payout->created_at->format('d M Y') }}
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 font-mono">
                                    {{ $payout->created_at->format('H:i') }} WIB
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="p-4 md:px-6">
                                @if($payout->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Diproses
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

                            <!-- Notes -->
                            <td class="p-4 md:px-6 text-slate-600 dark:text-slate-400 text-xs">
                                {{ $payout->notes ?: '-' }}
                            </td>

                            <!-- Amount -->
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
                                    <h3 class="font-bold text-base text-slate-800 dark:text-white mb-1">Belum ada pengajuan pencairan</h3>
                                    <p class="text-xs text-slate-400">Ketika saldo toko Anda mencukupi, Anda dapat mengajukan penarikan dana ke rekening bank melalui formulir di atas.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Pagination -->
            @if(method_exists($payouts, 'hasPages') && $payouts->hasPages())
            <div class="p-5 border-t border-slate-100 dark:border-[#222f49] flex justify-center">
                {{ $payouts->links() }}
            </div>
            @endif

        </div>

    </div>
</div>
@endsection
