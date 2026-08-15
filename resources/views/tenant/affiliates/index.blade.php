@extends('layouts.tenant')

@section('title', 'Affiliate Marketplace')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md p-6">
    
    <!-- Top Warning Banner -->
    <div class="bg-[#e0f7fa] dark:bg-[#0d2240] border border-[#b2ebf2] dark:border-[#1f4068] rounded-lg p-4 mb-6 flex items-start justify-between relative">
        <div class="flex gap-2">
            <span class="material-symbols-outlined text-[#00b3cc] dark:text-[#2f81f7] text-[18px] mt-0.5">info</span>
            <div>
                <h3 class="font-bold text-sm text-[#333] dark:text-white mb-1">Update Fitur Baru</h3>
                <p class="text-xs text-gray-600 dark:text-gray-300">Affiliate yang kamu tambahkan dapat dilihat di halaman Kelola Affiliate. Kamu dapat menambahkan mereka sekaligus ke Komisi XTRA Khusus. <a href="#" class="text-[#00b3cc] dark:text-[#2f81f7] hover:underline">Cek Sekarang</a></p>
            </div>
        </div>
        <button class="text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined text-[16px]">close</span></button>
    </div>

    <!-- Title -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-[#333] dark:text-white">Affiliate Marketplace</h1>
        <a href="{{ route('tenant.affiliates.create') }}" class="bg-[#00b3cc] dark:bg-[#2f81f7] text-white px-4 py-2 rounded font-bold text-sm hover:bg-[#00838f] dark:hover:bg-[#1f6feb] transition-colors flex items-center gap-1 shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Affiliate
        </a>
    </div>

    <!-- Filters Container -->
    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 mb-6 shadow-sm">
        
        <form method="GET" action="{{ route('tenant.affiliates.index') }}" id="filterForm">
            <!-- Search -->
            <div class="mb-6 relative max-w-md flex items-center gap-2">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan Nama Affiliate" class="w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm focus:outline-none focus:border-[#00b3cc] dark:focus:border-[#2f81f7] transition-colors bg-white dark:bg-[#0d1117] text-gray-700 dark:text-gray-200">
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                </div>
                <button type="submit" class="px-4 py-2 bg-[#00b3cc] dark:bg-[#2f81f7] text-white rounded text-sm font-medium hover:bg-[#00838f] dark:hover:bg-[#1f6feb] transition-colors">Cari</button>
            </div>

            <!-- Filter Kategori -->
            <div class="flex items-start gap-4 mb-4">
                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 min-w-[100px] mt-1.5">Kategori</span>
                <div class="flex flex-wrap gap-3">
                    @php $reqCategory = request('category', 'Semua'); @endphp
                    <input type="hidden" name="category" id="categoryInput" value="{{ $reqCategory }}">
                    <button type="button" onclick="document.getElementById('categoryInput').value='Semua'; document.getElementById('filterForm').submit();" class="px-4 py-1.5 rounded-full border text-sm transition-colors {{ $reqCategory === 'Semua' ? 'border-[#00b3cc] dark:border-[#2f81f7] text-[#00b3cc] dark:text-[#2f81f7] bg-[#e0f7fa] dark:bg-[#2f81f7]/10' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:border-gray-400' }}">Semua</button>
                    @foreach($categories as $cat)
                        <button type="button" onclick="document.getElementById('categoryInput').value='{{ $cat }}'; document.getElementById('filterForm').submit();" class="px-4 py-1.5 rounded-full border text-sm transition-colors {{ $reqCategory === $cat ? 'border-[#00b3cc] dark:border-[#2f81f7] text-[#00b3cc] dark:text-[#2f81f7] bg-[#e0f7fa] dark:bg-[#2f81f7]/10' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:border-gray-400' }}">{{ $cat }}</button>
                    @endforeach
                </div>
            </div>

            <!-- Filter Media Sosial -->
            <div class="flex items-center gap-4 mb-6">
                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 min-w-[100px]">Media Sosial</span>
                <div class="flex flex-wrap gap-3">
                    @php $reqPlatform = request('platform', 'Semua'); @endphp
                    <input type="hidden" name="platform" id="platformInput" value="{{ $reqPlatform }}">
                    @foreach(['Semua', 'Instagram', 'Tiktok', 'Facebook', 'Youtube', 'Twitter'] as $plat)
                        <button type="button" onclick="document.getElementById('platformInput').value='{{ $plat }}'; document.getElementById('filterForm').submit();" class="px-4 py-1.5 rounded-full border text-sm transition-colors {{ $reqPlatform === $plat ? 'border-[#00b3cc] dark:border-[#2f81f7] text-[#00b3cc] dark:text-[#2f81f7] bg-[#e0f7fa] dark:bg-[#2f81f7]/10' : 'border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:border-gray-400' }}">{{ $plat }}</button>
                    @endforeach
                </div>
            </div>
            
            <div class="flex gap-4 items-center border-t border-gray-100 dark:border-[#30363d] pt-4">
                <a href="{{ route('tenant.affiliates.index') }}" class="px-4 py-2 text-gray-700 dark:text-gray-300 text-sm hover:underline font-medium">Atur Ulang Filter</a>
            </div>
        </form>

    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 shadow-sm">
        
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-[#333] dark:text-white">Daftar Affiliate ({{ $affiliates->total() }})</h2>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-[#f6f6f6] dark:bg-[#0d1117] border-y border-gray-200 dark:border-[#30363d]">
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400">Affiliate</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Pengikut <span class="material-symbols-outlined text-[14px]">unfold_more</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Jumlah Klik <span class="material-symbols-outlined text-[14px]">help</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Pesanan <span class="material-symbols-outlined text-[14px]">help</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Penjualan(Rp) <span class="material-symbols-outlined text-[14px]">help</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400 text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($affiliates as $idx => $aff)
                    <tr class="border-b border-gray-200 dark:border-[#30363d] hover:bg-gray-50 dark:hover:bg-[#0d1117]/50 transition-colors group">
                        
                        <!-- Affiliate Profile -->
                        <td class="py-4 px-4 align-top w-[280px]">
                            <div class="flex gap-3">
                                <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-gray-200">
                                    <img src="{{ $aff->avatar_url }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-[#0055aa] dark:text-[#58a6ff] font-bold text-sm leading-tight hover:underline cursor-pointer flex items-center gap-1">
                                        {{ $aff->name }}
                                        @if($aff->is_golden_tick)
                                        <span class="material-symbols-outlined text-[14px] text-[#00b3cc] dark:text-[#2f81f7]">check_circle</span>
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $aff->handle }}</p>
                                    
                                    <div class="flex items-center gap-1 mb-2">
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase">{{ $aff->platform }}</span>
                                    </div>
                                    
                                    @if($aff->categories && count($aff->categories) > 0)
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $aff->categories[0] }}</span>
                                        @if(isset($aff->categories[1]))
                                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $aff->categories[1] }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Followers -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium text-[#00b3cc] dark:text-[#2f81f7] flex items-center gap-1 mt-1">
                                {{ $aff->followers_count }}
                            </div>
                        </td>

                        <!-- Clicks -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium mt-1">{{ $aff->clicks_count }}</div>
                        </td>

                        <!-- Orders -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium mt-1">{{ $aff->orders_count }}</div>
                        </td>

                        <!-- Sales -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium mt-1">{{ $aff->sales_range }}</div>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 align-top text-right pr-6">
                            <div class="flex flex-col items-end gap-2 mt-1">
                                <a href="{{ route('tenant.affiliates.edit', $aff->id) }}" class="bg-[#00b3cc] dark:bg-[#2f81f7] hover:bg-[#00838f] dark:hover:bg-[#1f6feb] text-white px-4 py-1.5 rounded text-sm font-bold transition-colors w-24 text-center shadow-sm">Edit</a>
                                <form action="{{ route('tenant.affiliates.destroy', $aff->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus affiliate ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-1.5 rounded text-sm font-bold transition-colors w-24 text-center shadow-sm">Hapus</button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-10 text-center text-gray-500">
                            Tidak ada data affiliate yang sesuai dengan filter Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 w-full">
            {{ $affiliates->links() }}
        </div>
        
    </div>

</div>
@endsection
