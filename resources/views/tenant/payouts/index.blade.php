@extends('layouts.tenant')

@section('title', 'Penghasilan Saya')

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

        <!-- Promo Banner -->
        <div class="bg-error/5 dark:bg-error/10 border border-error/10 dark:border-error/20 rounded-md p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 relative overflow-hidden">
            <!-- Decorative Icon -->
            <div class="absolute -left-4 -bottom-4 text-error/10 dark:text-error/20">
                <span class="material-symbols-outlined text-[100px]">trending_up</span>
            </div>
            
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 flex items-center justify-center">
                    <span class="material-symbols-outlined text-error text-[40px]">monitoring</span>
                </div>
                <div>
                    <h4 class="font-bold text-on-surface dark:text-white">Ingin Meningkatkan Penjualanmu? Coba Iklan Produk!</h4>
                    <p class="text-xs text-on-surface-variant dark:text-gray-400 mt-1">Penjual yang menggunakan Iklan rata-rata mendapatkan <span class="font-bold text-error">50%</span> lebih banyak penjualan. Gunakan sekarang untuk mempromosikan produk, meningkatkan penjualan dan bisnismu!</p>
                </div>
            </div>
            <button class="px-5 py-2 text-sm font-bold bg-error text-white rounded hover:bg-error/90 transition-colors whitespace-nowrap relative z-10">Buat Iklan Sekarang</button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- KOLOM KIRI (Main Content) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informasi Penghasilan -->
                <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
                    <div class="border-b border-outline-variant dark:border-[#30363d] px-6 py-4 flex justify-between items-center bg-surface-container-lowest dark:bg-[#0d1117]">
                        <h3 class="font-bold text-lg text-on-surface dark:text-white">Informasi Penghasilan</h3>
                    </div>
                    
                    <div class="p-6">
                        <!-- Info Alert -->
                        <div class="bg-[#E0F2FE] dark:bg-[#0369A1]/20 border border-[#BAE6FD] dark:border-[#0369A1] rounded-md p-3 mb-6 flex items-start gap-2">
                            <span class="material-symbols-outlined text-[#0284C7] dark:text-[#38BDF8] text-sm mt-0.5">info</span>
                            <p class="text-xs text-[#0369A1] dark:text-[#E0F2FE]">Nominal "Pending" dan "Sudah Dilepas" ini belum termasuk biaya penyesuaian. Download Laporan Penghasilan atau Catatan Transaksi Penghasilan untuk melihat rincian penyesuaian.</p>
                        </div>

                        <!-- Balances Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-y-6 gap-x-4 mb-6">
                            <div class="col-span-2 md:col-span-1 border-r border-outline-variant dark:border-[#30363d] pr-4">
                                <h4 class="font-bold text-on-surface dark:text-white mb-2">Pending</h4>
                                <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1">Total</p>
                                <h2 class="text-2xl font-bold text-on-surface dark:text-white">Rp 0</h2>
                            </div>
                            
                            <div class="col-span-2 md:col-span-3">
                                <h4 class="font-bold text-on-surface dark:text-white mb-2">Sudah Dilepas (Saldo Tersedia)</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1">Minggu Ini</p>
                                        <h2 class="text-2xl font-bold text-on-surface dark:text-white">Rp 0</h2>
                                    </div>
                                    <div>
                                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1">Bulan Ini</p>
                                        <h2 class="text-2xl font-bold text-on-surface dark:text-white">Rp 0</h2>
                                    </div>
                                    <div>
                                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mb-1">Total</p>
                                        <h2 class="text-2xl font-bold text-on-surface dark:text-white">Rp {{ number_format($store->balance, 0, ',', '.') }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rekening & Tarik Dana -->
                        <div class="bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] rounded p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-on-surface-variant dark:text-gray-400">Rekening Bank Saya:</span>
                                @if(!empty($store->bank_account_info))
                                    <span class="font-bold text-on-surface dark:text-white flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">account_balance</span> **** {{ substr($store->bank_account_info, -4) ?: 'Tersimpan' }}</span>
                                @else
                                    <span class="text-warning-dark dark:text-yellow-400 font-bold flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">warning</span> Belum Diatur</span>
                                @endif
                            </div>

                            <!-- Payout Request Form -->
                            <form action="{{ route('tenant.payouts.store') }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="number" name="amount" min="10000" max="{{ $store->balance }}" placeholder="Nominal (Min. Rp 10.000)" class="px-3 py-1.5 text-sm bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded focus:outline-none focus:border-primary w-48" required {{ $store->balance < 10000 ? 'disabled' : '' }}>
                                <button type="submit" class="text-sm font-semibold text-primary hover:underline bg-transparent border-none cursor-pointer flex items-center gap-1" {{ $store->balance < 10000 ? 'disabled' : '' }}>
                                    Tarik Dana <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                                </button>
                            </form>
                        </div>
                        @error('amount')
                            <p class="text-error text-xs mt-1 text-right">{{ $message }}</p>
                        @enderror
                        @if(empty($store->bank_account_info))
                            <p class="text-warning-dark text-xs mt-2 text-right">Anda harus mengatur Info Rekening Bank di <a href="{{ route('tenant.store.index') }}" class="underline font-bold" wire:navigate>Profil Toko</a> terlebih dahulu.</p>
                        @endif
                    </div>
                </div>

                <!-- Rincian Penghasilan -->
                <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
                    <div class="px-6 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="font-bold text-lg text-on-surface dark:text-white">Rincian Penghasilan</h3>
                        <div class="relative w-full md:w-64">
                            <input type="text" placeholder="Cari Pesanan" class="w-full pl-3 pr-10 py-1.5 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] rounded focus:outline-none focus:border-primary">
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                        </div>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="border-b border-outline-variant dark:border-[#30363d] flex px-4">
                        <a href="#" class="px-4 py-3 text-sm font-bold text-on-surface-variant dark:text-gray-400 hover:text-on-surface">Pending</a>
                        <a href="#" class="px-4 py-3 text-sm font-bold text-error border-b-2 border-error">Sudah Dilepas / Ditarik</a>
                    </div>

                    <!-- Filters -->
                    <div class="p-4 border-b border-outline-variant dark:border-[#30363d] flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-on-surface-variant text-lg">calendar_today</span>
                            <span class="text-sm font-bold text-on-surface dark:text-white">Semua Waktu</span>
                            <span class="material-symbols-outlined text-on-surface-variant text-sm">expand_more</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded hover:bg-surface-container-lowest transition-colors">Export</button>
                            <button class="px-2 py-1.5 border border-outline-variant dark:border-[#30363d] rounded hover:bg-surface-container-lowest transition-colors"><span class="material-symbols-outlined text-sm">view_list</span></button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto min-h-[300px]">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d] text-on-surface-variant dark:text-gray-400">
                                <tr>
                                    <th class="p-4 font-normal">Tanggal Pengajuan</th>
                                    <th class="p-4 font-normal">Status</th>
                                    <th class="p-4 font-normal">Catatan</th>
                                    <th class="p-4 font-normal text-right">Total Penarikan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant dark:divide-[#30363d]">
                                @forelse($payouts as $payout)
                                <tr class="hover:bg-surface-container-lowest/50 dark:hover:bg-[#0d1117]/50 transition-colors">
                                    <td class="p-4">
                                        <div class="text-on-surface dark:text-white">{{ $payout->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-on-surface-variant dark:text-gray-400">{{ $payout->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="p-4">
                                        @if($payout->status === 'pending')
                                            <span class="text-yellow-600 dark:text-yellow-400 font-bold">Diproses</span>
                                        @elseif($payout->status === 'approved')
                                            <span class="text-green-600 dark:text-green-400 font-bold">Berhasil</span>
                                        @elseif($payout->status === 'rejected')
                                            <span class="text-error font-bold">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-on-surface-variant dark:text-gray-400">
                                        {{ $payout->notes ?? '-' }}
                                    </td>
                                    <td class="p-4 text-right font-bold text-on-surface dark:text-white">
                                        Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-16 text-center">
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
                </div>

            </div>

            <!-- KOLOM KANAN (Sidebar Widgets) -->
            <div class="space-y-6">
                
                <!-- Catatan Transaksi Penghasilan -->
                <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-sm text-on-surface dark:text-white">Catatan Transaksi Penghasilan</h4>
                        <a href="#" class="text-xs text-primary hover:underline">Lainnya ></a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm border-b border-outline-variant/30 dark:border-[#30363d]/30 pb-3">
                            <span class="text-on-surface-variant dark:text-gray-400">Bulan Ini ({{ date('M Y') }})</span>
                            <span class="material-symbols-outlined text-primary text-lg cursor-pointer hover:bg-surface-container rounded">download</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-outline-variant/30 dark:border-[#30363d]/30 pb-3">
                            <span class="text-on-surface-variant dark:text-gray-400">Bulan Lalu ({{ date('M Y', strtotime('-1 month')) }})</span>
                            <span class="material-symbols-outlined text-primary text-lg cursor-pointer hover:bg-surface-container rounded">download</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant dark:text-gray-400">2 Bulan Lalu ({{ date('M Y', strtotime('-2 months')) }})</span>
                            <span class="material-symbols-outlined text-primary text-lg cursor-pointer hover:bg-surface-container rounded">download</span>
                        </div>
                    </div>
                </div>

                <!-- Faktur Saya -->
                <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-sm text-on-surface dark:text-white">Faktur Saya</h4>
                        <a href="#" class="text-xs text-primary hover:underline">Lainnya ></a>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm border-b border-outline-variant/30 dark:border-[#30363d]/30 pb-3">
                            <span class="text-on-surface-variant dark:text-gray-400 truncate pr-2">INV-{{ date('Ymd') }}-001</span>
                            <span class="material-symbols-outlined text-primary text-lg cursor-pointer hover:bg-surface-container rounded">download</span>
                        </div>
                        <div class="flex justify-between items-center text-sm border-b border-outline-variant/30 dark:border-[#30363d]/30 pb-3">
                            <span class="text-on-surface-variant dark:text-gray-400 truncate pr-2">INV-{{ date('Ymd', strtotime('-2 days')) }}-004</span>
                            <span class="material-symbols-outlined text-primary text-lg cursor-pointer hover:bg-surface-container rounded">download</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant dark:text-gray-400 truncate pr-2">INV-{{ date('Ymd', strtotime('-5 days')) }}-012</span>
                            <span class="material-symbols-outlined text-primary text-lg cursor-pointer hover:bg-surface-container rounded">download</span>
                        </div>
                    </div>
                </div>

                <!-- Daftar Biaya -->
                <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden p-4">
                    <h4 class="font-bold text-sm text-on-surface dark:text-white mb-4">Daftar Biaya</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-sm border-b border-outline-variant/30 dark:border-[#30363d]/30 pb-3">
                            <span class="text-on-surface-variant dark:text-gray-400">Biaya Admin Platform</span>
                            <a href="#" class="text-xs text-primary hover:underline">Lihat Semua ></a>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-on-surface-variant dark:text-gray-400">Biaya Transaksi (Gateway)</span>
                            <a href="#" class="text-xs text-primary hover:underline">Lihat Semua ></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
