@extends('layouts.tenant')

@section('title', 'Affiliate Marketplace')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md p-6">
    
    <!-- Top Warning Banner -->
    <div class="bg-[#fff9e6] dark:bg-[#332b00] border border-[#ffdb7a] dark:border-[#998200] rounded-lg p-4 mb-6 flex items-start justify-between relative">
        <div class="flex gap-2">
            <span class="material-symbols-outlined text-[#ee4d2d] text-[18px] mt-0.5">info</span>
            <div>
                <h3 class="font-bold text-sm text-[#333] dark:text-white mb-1">Update Fitur Baru</h3>
                <p class="text-xs text-gray-600 dark:text-gray-300">Affiliate yang kamu tambahkan dapat dilihat di halaman Kelola Affiliate. Kamu dapat menambahkan mereka sekaligus ke Komisi XTRA Khusus. <a href="#" class="text-[#ee4d2d] hover:underline">Cek Sekarang</a></p>
            </div>
        </div>
        <button class="text-gray-400 hover:text-gray-600"><span class="material-symbols-outlined text-[16px]">close</span></button>
    </div>

    <!-- Title -->
    <h1 class="text-xl font-bold mb-6 text-[#333] dark:text-white">Affiliate Marketplace</h1>

    <!-- Filters Container -->
    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 mb-6 shadow-sm">
        
        <!-- Search -->
        <div class="mb-6 relative max-w-md">
            <input type="text" placeholder="Urutkan berdasarkan Nama Affiliate" class="w-full pl-3 pr-10 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm focus:outline-none focus:border-[#ee4d2d] transition-colors bg-white dark:bg-[#0d1117] text-gray-700 dark:text-gray-200">
            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
        </div>

        <!-- Filter Kategori -->
        <div class="flex items-start gap-4 mb-4">
            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 min-w-[100px] mt-1.5">Kategori</span>
            <div class="flex flex-wrap gap-3">
                <button class="px-4 py-1.5 rounded-full border border-[#ee4d2d] text-[#ee4d2d] text-sm bg-[#fff5f3] dark:bg-[#ee4d2d]/10">Semua</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Komputer & Aksesoris</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Kesehatan</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Aksesoris Fashion</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Elektronik</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Pakaian Pria</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Sepatu Pria</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Handphone & Aksesoris</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Fashion Muslim</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Koper & Tas Travel</button>
                <button class="px-4 py-1.5 text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">Lebih Banyak <span class="material-symbols-outlined text-[14px]">expand_more</span></button>
            </div>
        </div>

        <!-- Filter Media Sosial -->
        <div class="flex items-center gap-4 mb-6">
            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 min-w-[100px]">Media Sosial</span>
            <div class="flex flex-wrap gap-3">
                <button class="px-4 py-1.5 rounded-full border border-[#ee4d2d] text-[#ee4d2d] text-sm bg-[#fff5f3] dark:bg-[#ee4d2d]/10">Semua</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Live</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Video</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Instagram</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Tiktok</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">Facebook</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">X (Twitter)</button>
                <button class="px-4 py-1.5 rounded-full border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 text-sm hover:border-gray-400 transition-colors">YouTube</button>
            </div>
        </div>

        <!-- Filter Kerja Sama -->
        <div class="flex items-center gap-4 mb-6">
            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 min-w-[100px]">Kerja Sama</span>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <div class="w-4 h-4 border border-[#ee4d2d] rounded flex items-center justify-center bg-[#ee4d2d]">
                        <span class="material-symbols-outlined text-white text-[12px] font-bold">check</span>
                    </div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Golden Tick</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <div class="w-4 h-4 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-[#0d1117]"></div>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Penyelesaian Sampel Gratis Baik</span>
                    <span class="material-symbols-outlined text-gray-400 text-[14px]">help</span>
                </label>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4 items-center">
            <button class="px-8 py-2 bg-white dark:bg-transparent border border-[#ee4d2d] text-[#ee4d2d] rounded text-sm hover:bg-[#fff5f3] dark:hover:bg-[#ee4d2d]/10 transition-colors font-medium">Ajukan</button>
            <button class="px-4 py-2 text-gray-700 dark:text-gray-300 text-sm hover:underline font-medium">Atur Ulang</button>
            <button class="px-4 py-2 text-[#0055aa] dark:text-[#58a6ff] text-sm flex items-center gap-1 font-medium">Tampilkan Semua Filter <span class="material-symbols-outlined text-[16px]">expand_more</span></button>
        </div>

    </div>

    <!-- Table Section -->
    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 shadow-sm">
        
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-[#333] dark:text-white">Daftar Affiliate</h2>
            <div class="flex gap-2">
                <button class="p-1.5 border border-gray-300 dark:border-gray-600 rounded bg-[#f6f6f6] dark:bg-[#0d1117] text-gray-600 dark:text-gray-400"><span class="material-symbols-outlined text-[18px]">grid_view</span></button>
                <button class="p-1.5 border border-[#ee4d2d] rounded bg-[#fff5f3] dark:bg-[#ee4d2d]/10 text-[#ee4d2d]"><span class="material-symbols-outlined text-[18px]">format_list_bulleted</span></button>
            </div>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-[#f6f6f6] dark:bg-[#0d1117] border-y border-gray-200 dark:border-[#30363d]">
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400">Affiliate</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Pengikut <span class="material-symbols-outlined text-[14px]">unfold_more</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Jumlah Klik <span class="material-symbols-outlined text-[14px]">help</span> <span class="material-symbols-outlined text-[14px]">unfold_more</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Pesanan <span class="material-symbols-outlined text-[14px]">help</span> <span class="material-symbols-outlined text-[14px]">unfold_more</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400"><div class="flex items-center gap-1">Penjualan(Rp) <span class="material-symbols-outlined text-[14px]">help</span> <span class="material-symbols-outlined text-[14px]">unfold_more</span></div></th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400">Contoh Konten</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-600 dark:text-gray-400 text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($affiliates as $idx => $aff)
                    <tr class="border-b border-gray-200 dark:border-[#30363d] hover:bg-gray-50 dark:hover:bg-[#0d1117]/50 transition-colors group">
                        
                        <!-- Affiliate Profile -->
                        <td class="py-4 px-4 align-top w-[250px]">
                            <div class="flex gap-3">
                                <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border border-gray-200">
                                    <img src="{{ $aff['avatar'] }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="text-[#0055aa] dark:text-[#58a6ff] font-bold text-sm leading-tight hover:underline cursor-pointer">{{ $aff['name'] }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">{{ $aff['handle'] }}</p>
                                    
                                    <div class="flex items-center gap-1 mb-2">
                                        <div class="bg-[#ee4d2d] rounded-full w-4 h-4 flex items-center justify-center text-white"><span class="material-symbols-outlined text-[10px]">play_arrow</span></div>
                                        @if($idx % 2 == 0)
                                            <div class="bg-blue-600 rounded-full w-4 h-4 flex items-center justify-center text-white"><span class="material-symbols-outlined text-[10px]">facebook</span></div>
                                        @else
                                            <div class="bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-500 rounded-full w-4 h-4 flex items-center justify-center text-white"><span class="material-symbols-outlined text-[10px]">camera_alt</span></div>
                                        @endif
                                    </div>
                                    
                                    @if(count($aff['categories']) > 0)
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $aff['categories'][0] }}</span>
                                        @if(isset($aff['categories'][1]))
                                        <span class="text-[10px] bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded border border-gray-200 dark:border-gray-700">{{ $aff['categories'][1] }}</span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Followers -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium text-[#ee4d2d] flex items-center gap-1 mt-1">
                                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/132px-Instagram_logo_2016.svg.png" class="w-3 h-3 grayscale"> 
                                {{ $aff['followers'] }}
                            </div>
                        </td>

                        <!-- Clicks -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium mt-1">{{ $aff['clicks'] }}</div>
                        </td>

                        <!-- Orders -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium mt-1">{{ $aff['orders'] }}</div>
                        </td>

                        <!-- Sales -->
                        <td class="py-4 px-4 align-top">
                            <div class="font-medium mt-1">{{ $aff['sales'] }}</div>
                        </td>

                        <!-- Content Examples -->
                        <td class="py-4 px-4 align-top w-[200px]">
                            <div class="flex gap-2 mb-2">
                                <div class="w-14 h-14 bg-black rounded overflow-hidden relative group/thumb cursor-pointer">
                                    <img src="https://images.unsplash.com/photo-1542393545-10f5cde2c810?w=100&h=100&fit=crop" class="w-full h-full object-cover opacity-80">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white opacity-80 text-[20px]">play_circle</span>
                                    </div>
                                </div>
                                <div class="w-14 h-14 bg-black rounded overflow-hidden relative group/thumb cursor-pointer">
                                    <img src="https://images.unsplash.com/photo-1526406915894-7bcd65f60845?w=100&h=100&fit=crop" class="w-full h-full object-cover opacity-80">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white opacity-80 text-[20px]">play_circle</span>
                                    </div>
                                </div>
                            </div>
                            @if($aff['audience'])
                            <div class="text-[10px] text-gray-500 dark:text-gray-400">Audiens: {{ $aff['audience'] }}</div>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 align-top text-right pr-6">
                            <div class="flex flex-col items-end gap-2 mt-1">
                                <button class="bg-[#ee4d2d] hover:bg-[#d73f22] text-white px-4 py-1.5 rounded text-sm font-bold transition-colors w-28 shadow-sm">Kerja Sama</button>
                                <div class="flex gap-2">
                                    <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 dark:border-gray-600 text-gray-500 hover:text-[#ee4d2d] hover:border-[#ee4d2d] transition-colors"><span class="material-symbols-outlined text-[16px]">chat</span></button>
                                    <button class="w-8 h-8 flex items-center justify-center rounded border border-gray-300 dark:border-gray-600 text-gray-500 hover:text-[#ee4d2d] hover:border-[#ee4d2d] transition-colors"><span class="material-symbols-outlined text-[16px]">star</span></button>
                                </div>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
    </div>

</div>
@endsection
