@extends('layouts.tenant')

@section('title', 'Saldo Penjual')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-6 bg-surface-container-lowest dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Top Alerts (Session Success/Error) -->
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 p-4 rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button class="opacity-50 hover:opacity-100" onclick="this.parentElement.style.display='none'"><span class="material-symbols-outlined text-sm">close</span></button>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 p-4 rounded-lg flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button class="opacity-50 hover:opacity-100" onclick="this.parentElement.style.display='none'"><span class="material-symbols-outlined text-sm">close</span></button>
            </div>
        @endif

        <!-- Informasi Saldo -->
        <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d]">
                <h3 class="font-bold text-lg text-on-surface dark:text-white">Informasi Saldo</h3>
            </div>
            
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <!-- Saldo Kiri -->
                <div>
                    <p class="text-sm text-on-surface-variant dark:text-gray-400 mb-2">Saldo</p>
                    <div class="flex items-center gap-4">
                        <h2 class="text-3xl font-bold text-on-surface dark:text-white">Rp{{ number_format($store->balance, 0, ',', '.') }}</h2>
                        <!-- Form Tarik Dana -->
                        <form action="{{ route('tenant.payouts.store') }}" method="POST" class="inline">
                            @csrf
                            <!-- Hidden input for max withdrawal -->
                            <input type="hidden" name="amount" value="{{ $store->balance }}">
                            <button type="submit" class="px-4 py-2 text-sm font-bold bg-[#ee4d2d] text-white rounded hover:bg-[#d73f22] transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ $store->balance < 10000 ? 'disabled' : '' }}>
                                Tarik Dana
                            </button>
                        </form>
                    </div>
                    @if($store->balance < 10000)
                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mt-2">Minimal penarikan Rp 10.000</p>
                    @endif
                </div>

                <!-- Rekening Kanan -->
                <div class="border border-outline-variant dark:border-[#30363d] rounded p-4 flex flex-col gap-2 min-w-[250px]">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-on-surface-variant dark:text-gray-400">Rekening Bank Saya</span>
                        <a href="{{ route('tenant.store.index') }}" class="text-xs font-semibold text-[#0055aa] dark:text-[#38bdf8] flex items-center hover:underline" wire:navigate>Lainnya <span class="material-symbols-outlined text-[14px]">chevron_right</span></a>
                    </div>
                    @if(!empty($store->bank_account_info))
                        <div class="flex items-center gap-3 mt-1">
                            <span class="material-symbols-outlined text-primary text-[24px]">account_balance</span>
                            <div>
                                <div class="text-sm font-bold text-on-surface dark:text-white flex items-center gap-2">
                                    BANK
                                    <span class="text-xs bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 px-1 rounded border border-green-200 dark:border-green-800">Utama</span>
                                </div>
                                <div class="text-xs text-on-surface-variant dark:text-gray-400 flex items-center gap-2 mt-0.5">
                                    **** {{ substr($store->bank_account_info, -4) ?: 'Tersimpan' }}
                                    <span class="text-xs text-on-surface-variant dark:text-gray-500">Telah Ditambahkan</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2 mt-1">
                            <span class="material-symbols-outlined text-warning-dark dark:text-yellow-400 text-[24px]">warning</span>
                            <span class="text-sm font-bold text-on-surface dark:text-white">Belum Diatur</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Banner Promo Modal Bisnis -->
        <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d]">
                <h3 class="font-bold text-lg text-on-surface dark:text-white">Kembangkan Modal Bisnis Anda</h3>
            </div>
            <div class="p-6">
                <div class="border border-outline-variant dark:border-[#30363d] rounded-md p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 flex-shrink-0 bg-error/10 text-error rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-[24px]">storefront</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-on-surface dark:text-white">SPinjam Untuk Penjual</h4>
                            <p class="text-xs text-on-surface-variant dark:text-gray-400 mt-1">Kembangkan toko Anda dengan modal usaha eksklusif!</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 text-sm font-bold border border-error text-error rounded hover:bg-error/5 transition-colors whitespace-nowrap">Lihat Rincian</button>
                </div>
            </div>
        </div>

        <!-- Transaksi Terakhir -->
        <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
            <div class="px-6 py-4 bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d]">
                <h3 class="font-bold text-lg text-on-surface dark:text-white">Transaksi Terakhir</h3>
            </div>
            
            <div class="p-6 space-y-6">
                
                <!-- Filters -->
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <span class="text-sm text-on-surface-variant dark:text-gray-400 min-w-[150px]">Tanggal Transaksi Dibuat</span>
                        <div class="flex items-center border border-outline-variant dark:border-[#30363d] rounded px-3 py-1.5 text-sm bg-surface-container-lowest dark:bg-[#0d1117] w-full md:w-auto">
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant mr-2">calendar_today</span>
                            <span class="text-on-surface-variant mr-4">Dalam bulan ini:</span>
                            <span class="font-semibold">{{ date('01/m/Y') }} - {{ date('t/m/Y') }}</span>
                            <span class="material-symbols-outlined text-[16px] text-on-surface-variant ml-2">expand_more</span>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <span class="text-sm text-on-surface-variant dark:text-gray-400 min-w-[150px]">Jenis Transaksi</span>
                        <div class="flex rounded overflow-hidden">
                            <button class="px-4 py-1.5 text-sm font-semibold border border-error bg-error text-white">Semua</button>
                            <button class="px-4 py-1.5 text-sm font-semibold border-y border-r border-error text-error bg-surface hover:bg-error/5">Transaksi Masuk</button>
                            <button class="px-4 py-1.5 text-sm font-semibold border-y border-r border-error text-error bg-surface hover:bg-error/5">Transaksi Keluar</button>
                        </div>
                    </div>
                    <!-- Detailed check filters (Mock) -->
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4 pt-2">
                        <span class="text-sm text-on-surface-variant dark:text-gray-400 min-w-[150px]">Tipe Transaksi</span>
                        <div class="flex flex-wrap gap-4 text-sm text-on-surface-variant">
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" checked class="accent-error"> Penghasilan dari Pesanan</label>
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" checked class="accent-error"> Penyesuaian</label>
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" checked class="accent-error"> Penarikan Dana</label>
                            <label class="flex items-center gap-1 cursor-pointer"><input type="checkbox" checked class="accent-error"> Pengembalian Dana</label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 border-t border-outline-variant dark:border-[#30363d] pt-4 mt-2">
                        <button class="px-4 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded hover:bg-surface-container-lowest transition-colors">Atur Ulang</button>
                        <button class="px-4 py-1.5 text-sm font-semibold border border-error text-error rounded hover:bg-error/5 transition-colors">Terapkan</button>
                    </div>
                </div>

                <!-- Table Header Actions -->
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 pt-4">
                    <span class="font-bold text-sm text-on-surface dark:text-white">{{ $payouts->count() }} Transaksi <span class="text-on-surface-variant font-normal">(Total: {{ $payouts->total() }})</span></span>
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <div class="relative flex-1 md:w-48">
                            <input type="text" placeholder="Cari No. Pesanan" class="w-full pl-3 pr-8 py-1.5 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] rounded focus:outline-none focus:border-primary">
                            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-on-surface-variant text-[16px]">search</span>
                        </div>
                        <button class="px-3 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded hover:bg-surface-container-lowest transition-colors bg-surface-container-lowest dark:bg-[#0d1117]">Export</button>
                        <button class="px-2 py-1.5 border border-outline-variant dark:border-[#30363d] rounded hover:bg-surface-container-lowest transition-colors bg-surface-container-lowest dark:bg-[#0d1117]"><span class="material-symbols-outlined text-sm">view_list</span></button>
                    </div>
                </div>

                <!-- Transaction Table -->
                <div class="overflow-x-auto min-h-[300px]">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d] text-on-surface-variant dark:text-gray-400">
                            <tr>
                                <th class="p-4 font-normal">Waktu Dibuat</th>
                                <th class="p-4 font-normal">Tipe Transaksi</th>
                                <th class="p-4 font-normal">Status</th>
                                <th class="p-4 font-normal text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant dark:divide-[#30363d]">
                            @forelse($payouts as $payout)
                            <tr class="hover:bg-surface-container-lowest/50 dark:hover:bg-[#0d1117]/50 transition-colors">
                                <td class="p-4">
                                    <div class="text-on-surface dark:text-white">{{ $payout->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-on-surface-variant dark:text-gray-400">{{ $payout->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="p-4">
                                    <div class="font-semibold text-on-surface dark:text-white">Penarikan Dana</div>
                                    <div class="text-xs text-on-surface-variant dark:text-gray-400">{{ $payout->notes ?? 'Penarikan ke Rekening' }}</div>
                                </td>
                                <td class="p-4">
                                    @if($payout->status === 'pending')
                                        <span class="text-yellow-600 dark:text-yellow-400 font-bold">Diproses</span>
                                    @elseif($payout->status === 'approved')
                                        <span class="text-green-600 dark:text-green-400 font-bold">Berhasil</span>
                                    @elseif($payout->status === 'rejected')
                                        <span class="text-error font-bold">Gagal/Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right font-bold text-error">
                                    -Rp{{ number_format($payout->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <!-- Empty State -->
                            <tr>
                                <td colspan="4" class="p-16 text-center border-b-0">
                                    <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                        <div class="w-16 h-20 border-2 border-outline-variant/30 rounded mb-4 relative flex items-center justify-center opacity-50 bg-surface-container-lowest">
                                            <div class="w-8 border-b-2 border-outline-variant/50 mt-2"></div>
                                            <div class="w-4 border-b-2 border-outline-variant/50 absolute bottom-6 left-4"></div>
                                        </div>
                                        <p class="text-sm font-semibold">Tidak Ada Data</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($payouts->hasPages())
                <div class="pt-2">
                    {{ $payouts->links() }}
                </div>
                @endif
                
            </div>
        </div>

    </div>
</div>
@endsection
