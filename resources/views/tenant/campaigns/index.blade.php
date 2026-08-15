@extends('layouts.tenant')

@section('title', 'Campaign & Promo')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md p-6">
    
    <!-- Title -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-[#333] dark:text-white">Campaign & Promo</h1>
        <a href="{{ route('tenant.campaigns.create') }}" class="bg-[#00b3cc] dark:bg-[#2f81f7] text-white px-4 py-2 rounded font-bold text-sm hover:bg-[#00838f] dark:hover:bg-[#1f6feb] transition-colors flex items-center gap-1 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> Buat Promo Baru
        </a>
    </div>

    <!-- Info Banner -->
    <div class="bg-[#e0f7fa] dark:bg-[#0d2240] border border-[#b2ebf2] dark:border-[#1f4068] rounded-lg p-4 mb-6 flex items-start justify-between relative">
        <div class="flex gap-2">
            <span class="material-symbols-outlined text-[#00b3cc] dark:text-[#2f81f7] text-[18px] mt-0.5">info</span>
            <div>
                <h3 class="font-bold text-sm text-[#333] dark:text-white mb-1">Kelola Promosi Toko Anda</h3>
                <p class="text-xs text-gray-600 dark:text-gray-300">Tingkatkan penjualan dengan membuat voucher atau potongan harga langsung untuk produk Anda.</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 shadow-sm">
        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-[#f6f6f6] dark:bg-[#0d1117] border-y border-gray-200 dark:border-[#30363d]">
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400">Nama Promo</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400">Tipe & Diskon</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400">Periode</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400">Status</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400 text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($campaigns as $campaign)
                    <tr class="border-b border-gray-200 dark:border-[#30363d] hover:bg-gray-50 dark:hover:bg-[#0d1117]/50 transition-colors">
                        
                        <!-- Name & Code -->
                        <td class="py-4 px-4 align-top w-[250px]">
                            <div class="font-bold text-[#0055aa] dark:text-[#58a6ff]">{{ $campaign->name }}</div>
                            @if($campaign->type === 'voucher' && $campaign->code)
                                <div class="text-xs text-gray-500 mt-1">Kode: <span class="bg-gray-100 dark:bg-gray-800 px-1 py-0.5 rounded font-mono">{{ $campaign->code }}</span></div>
                            @endif
                        </td>

                        <!-- Type & Value -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium capitalize mb-1">{{ $campaign->type === 'discount' ? 'Diskon Langsung' : 'Voucher' }}</div>
                            <div class="text-[#00b3cc] dark:text-[#2f81f7] font-bold">
                                @if($campaign->discount_type === 'percentage')
                                    {{ rtrim(rtrim($campaign->discount_value, '0'), '.') }}% OFF
                                @else
                                    Rp{{ number_format($campaign->discount_value, 0, ',', '.') }} OFF
                                @endif
                            </div>
                        </td>

                        <!-- Period -->
                        <td class="py-4 px-4 align-top">
                            <div class="text-xs mb-1"><span class="font-semibold">Mulai:</span> {{ $campaign->start_date->format('d M Y, H:i') }}</div>
                            <div class="text-xs"><span class="font-semibold">Selesai:</span> {{ $campaign->end_date->format('d M Y, H:i') }}</div>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-4 align-top">
                            @if($campaign->status === 'active')
                                <span class="px-2 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs font-bold rounded-full">Aktif</span>
                            @elseif($campaign->status === 'scheduled')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 text-xs font-bold rounded-full">Terjadwal</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400 text-xs font-bold rounded-full">Tidak Aktif</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 align-top text-right pr-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('tenant.campaigns.edit', $campaign->id) }}" class="text-gray-500 hover:text-[#00b3cc] dark:hover:text-[#2f81f7] transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                <form action="{{ route('tenant.campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus promo ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-500">
                            Belum ada campaign atau promo yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $campaigns->links() }}
        </div>
    </div>
</div>
@endsection
