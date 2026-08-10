@extends('layouts.tenant')

@section('title', 'Produk Saya')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-6 bg-surface-container-lowest dark:bg-[#0d1117]">
    <div class="max-w-7xl mx-auto space-y-4">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-on-surface dark:text-white">Produk Saya</h2>
            <div class="flex flex-wrap items-center gap-3">
                <button class="px-4 py-2 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded bg-surface dark:bg-[#161b22] text-on-surface dark:text-white hover:bg-surface-container-lowest transition-colors flex items-center gap-1">
                    Pengaturan Produk <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                <button class="px-4 py-2 text-sm font-semibold border border-outline-variant dark:border-[#30363d] rounded bg-surface dark:bg-[#161b22] text-on-surface dark:text-white hover:bg-surface-container-lowest transition-colors flex items-center gap-1">
                    Pengaturan Massal <span class="material-symbols-outlined text-[16px]">expand_more</span>
                </button>
                <a href="{{ route('tenant.products.create') }}" class="px-4 py-2 text-sm font-bold bg-primary text-white rounded hover:bg-primary/90 transition-colors flex items-center gap-1" wire:navigate>
                    <span class="material-symbols-outlined text-[16px]">add</span> Tambah Produk Baru
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-surface dark:bg-[#161b22] border border-outline-variant dark:border-[#30363d] rounded-md overflow-hidden">
            
            <!-- Tabs -->
            <div class="border-b border-outline-variant dark:border-[#30363d] flex overflow-x-auto hide-scrollbar">
                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'all'])) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $tab === 'all' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-gray-400 hover:text-on-surface transition-colors' }}">Semua ({{ $allCount }})</a>
                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'active'])) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $tab === 'active' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-gray-400 hover:text-on-surface transition-colors' }}">Aktif ({{ $activeCount }})</a>
                <a href="{{ route('tenant.products.index', array_merge(request()->query(), ['tab' => 'inactive'])) }}" class="px-6 py-4 text-sm font-bold whitespace-nowrap {{ $tab === 'inactive' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant dark:text-gray-400 hover:text-on-surface transition-colors' }}">Non-Aktif ({{ $inactiveCount }})</a>
            </div>

            <div class="p-4 md:p-6 space-y-6">
                <!-- Info Banner -->
                <div class="bg-[#E0F2FE] dark:bg-[#0369A1]/20 border border-[#BAE6FD] dark:border-[#0369A1] rounded-md p-3 flex items-start gap-3">
                    <span class="material-symbols-outlined text-[#0284C7] dark:text-[#38BDF8] text-lg mt-0.5">info</span>
                    <div>
                        <p class="text-sm text-[#0369A1] dark:text-[#E0F2FE]">Tips untuk penjual produk digital: Selalu pastikan tautan unduhan Anda valid. <a href="#" class="font-bold hover:underline">Pelajari lebih lanjut tentang pedoman produk digital</a>.</p>
                    </div>
                    <button class="ml-auto text-[#0284C7] dark:text-[#38BDF8] hover:bg-[#BAE6FD] dark:hover:bg-[#0369A1]/50 p-1 rounded transition-colors flex-shrink-0">
                        <span class="material-symbols-outlined text-lg">close</span>
                    </button>
                </div>

                <!-- Promo Banner -->
                <div class="bg-gradient-to-r from-error/5 to-error/10 dark:from-error/10 dark:to-error/5 border border-primary/20 dark:border-primary/30 rounded-md p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white dark:bg-black rounded-lg shadow-sm border border-outline-variant flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-primary text-3xl">campaign</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-on-surface dark:text-white">Buat Iklan, Tingkatkan Kunjungan dan Dapatkan Voucher!</h4>
                            <div class="flex flex-wrap items-center gap-x-6 gap-y-1 mt-1 text-sm">
                                <span class="text-on-surface-variant dark:text-gray-400 flex items-center gap-1">Atur Modal <span class="font-bold text-primary">Rp250.000 >></span></span>
                                <span class="text-on-surface-variant dark:text-gray-400 flex items-center gap-1">Penjualan Meningkat <span class="font-bold text-primary">>> Rp1.350.000-Rp2.425.000</span></span>
                            </div>
                        </div>
                    </div>
                    <button class="px-5 py-2 text-sm font-bold bg-primary text-white rounded hover:bg-primary/90 transition-colors whitespace-nowrap">Buat Iklan</button>
                </div>

                <!-- Search & Filters -->
                <div class="space-y-4">
                    <form method="GET" action="{{ route('tenant.products.index') }}">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <!-- Filter Row -->
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex-1 min-w-[250px] relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Produk, ID Produk" class="w-full pl-10 pr-4 py-2 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] rounded focus:outline-none focus:border-primary text-on-surface dark:text-white">
                            </div>
                            <select name="category" class="px-4 py-2 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] text-on-surface dark:text-white rounded focus:outline-none focus:border-primary">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <select name="sort" class="px-4 py-2 text-sm bg-surface-container-lowest dark:bg-[#0d1117] border border-outline-variant dark:border-[#30363d] text-on-surface dark:text-white rounded focus:outline-none focus:border-primary">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                            <button type="submit" class="px-4 py-2 text-sm font-semibold border border-primary text-primary rounded hover:bg-primary/5 transition-colors">Terapkan</button>
                            <a href="{{ route('tenant.products.index', ['tab' => $tab]) }}" class="px-4 py-2 text-sm font-semibold border border-outline-variant dark:border-[#30363d] text-on-surface dark:text-white rounded hover:bg-surface-container-lowest transition-colors">Atur ulang</a>
                        </div>
                    </form>

                    <!-- Action Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-4 mt-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-bold mr-2 text-on-surface dark:text-white">{{ $products->count() }} Products</span>
                            <span class="px-3 py-1 text-xs border border-outline-variant dark:border-[#30363d] rounded-full text-on-surface-variant cursor-pointer hover:bg-surface-container-lowest">Perlu Diiklankan</span>
                            <span class="px-3 py-1 text-xs border border-outline-variant dark:border-[#30363d] rounded-full text-on-surface-variant cursor-pointer hover:bg-surface-container-lowest">Berpotensi Memiliki Standar Produk Baru</span>
                            <span class="px-3 py-1 text-xs border border-outline-variant dark:border-[#30363d] rounded-full text-on-surface-variant cursor-pointer hover:bg-surface-container-lowest">Stok Menipis</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 text-xs border border-outline-variant dark:border-[#30363d] rounded text-on-surface-variant hover:bg-surface-container-lowest flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">swap_vert</span> Urutkan berdasarkan Rekomendasi</button>
                            <button class="px-3 py-1.5 border border-outline-variant dark:border-[#30363d] rounded text-on-surface-variant hover:bg-surface-container-lowest"><span class="material-symbols-outlined text-[16px]">grid_view</span></button>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="overflow-x-auto border border-outline-variant dark:border-[#30363d] rounded-md">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-surface-container-lowest dark:bg-[#0d1117] border-b border-outline-variant dark:border-[#30363d] text-on-surface-variant dark:text-gray-400">
                            <tr>
                                <th class="p-4 w-10 font-normal"><input type="checkbox" class="rounded border-outline-variant accent-primary"></th>
                                <th class="p-4 font-normal min-w-[300px]">Produk</th>
                                <th class="p-4 font-normal">
                                    <div class="flex items-center gap-1 cursor-pointer">Harga <span class="material-symbols-outlined text-[14px]">unfold_more</span></div>
                                </th>
                                <th class="p-4 font-normal">
                                    <div class="flex items-center gap-1 cursor-pointer">Stok <span class="material-symbols-outlined text-[14px]">help</span> <span class="material-symbols-outlined text-[14px]">unfold_more</span></div>
                                </th>
                                <th class="p-4 font-normal">
                                    <div class="flex items-center gap-1 cursor-pointer">Performa <span class="material-symbols-outlined text-[14px]">unfold_more</span></div>
                                </th>
                                <th class="p-4 font-normal">
                                    <div class="flex items-center gap-1">Analisis Produk <span class="material-symbols-outlined text-[14px]">help</span></div>
                                </th>
                                <th class="p-4 font-normal text-right pr-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant dark:divide-[#30363d]">
                            @forelse($products as $product)
                            <tr class="hover:bg-surface-container-lowest/50 dark:hover:bg-[#0d1117]/50 group transition-colors">
                                <td class="p-4"><input type="checkbox" class="rounded border-outline-variant accent-primary"></td>
                                <td class="p-4 whitespace-normal min-w-[300px]">
                                    <div class="flex gap-3">
                                        <!-- Image -->
                                        <div class="w-16 h-16 flex-shrink-0 bg-surface-container-high border border-outline-variant rounded overflow-hidden relative">
                                            @if($product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant"><span class="material-symbols-outlined">code</span></div>
                                            @endif
                                            @if(!$product->is_active)
                                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-[10px] font-bold">Non-Aktif</div>
                                            @endif
                                        </div>
                                        <!-- Info -->
                                        <div>
                                            <a href="#" class="font-bold text-on-surface dark:text-white hover:text-primary dark:hover:text-primary transition-colors line-clamp-2 leading-tight">{{ $product->name }}</a>
                                            <div class="text-[11px] text-on-surface-variant dark:text-gray-400 mt-1">ID Produk: {{ $product->id }}</div>
                                            @if($product->slug)
                                            <div class="text-[11px] text-on-surface-variant dark:text-gray-400">SKU: {{ $product->slug }}</div>
                                            @endif
                                            <div class="mt-1">
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 text-[10px] font-bold text-[#0284C7] bg-[#E0F2FE] border border-[#BAE6FD] rounded cursor-pointer hover:bg-[#BAE6FD] transition-colors">Komisi AMS <span class="material-symbols-outlined text-[10px]">chevron_right</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 align-top pt-5">
                                    <div class="font-bold text-on-surface dark:text-white">Rp{{ number_format($product->discount_price ?? $product->price, 0, ',', '.') }}</div>
                                    @if($product->discount_price)
                                        <div class="text-xs text-on-surface-variant dark:text-gray-500 line-through">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                                    @endif
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant mt-1 cursor-pointer hover:text-primary">edit</span>
                                </td>
                                <td class="p-4 align-top pt-5">
                                    <div class="font-bold text-on-surface dark:text-white">99+</div>
                                    <span class="material-symbols-outlined text-[14px] text-on-surface-variant mt-1 cursor-pointer hover:text-primary">edit</span>
                                </td>
                                <td class="p-4 align-top pt-5">
                                    <div class="text-xs text-on-surface dark:text-white font-semibold">Penjualan 0</div>
                                    <div class="text-[10px] text-on-surface-variant dark:text-gray-500 mt-0.5">Penjualan 30 Hari Terakhir 0</div>
                                    <div class="text-[10px] text-on-surface-variant dark:text-gray-500">Kunjungan 30 Hari Terakhir 0</div>
                                </td>
                                <td class="p-4 align-top pt-5 text-center text-on-surface-variant">-</td>
                                <td class="p-4 align-top pt-5 text-right pr-6">
                                    <div class="flex flex-col items-end gap-1.5 text-sm font-semibold">
                                        <a href="{{ route('tenant.products.edit', $product) }}" class="text-[#0055aa] dark:text-[#58a6ff] font-normal hover:underline" wire:navigate>Ubah</a>
                                        <a href="#" class="text-[#0055aa] dark:text-[#58a6ff] font-normal hover:underline">Iklankan</a>
                                        
                                        <div class="relative group/dropdown">
                                            <button class="text-[#0055aa] dark:text-[#58a6ff] font-normal hover:underline flex items-center">Lainnya <span class="material-symbols-outlined text-[14px]">expand_more</span></button>
                                            <div class="absolute right-0 top-full mt-1 bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded shadow-lg opacity-0 invisible group-hover/dropdown:opacity-100 group-hover/dropdown:visible transition-all z-50 w-56 py-1 text-left">
                                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">Rincian Iklan Produk Otomatis</a>
                                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">Salin</a>
                                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">Tampilan Produk</a>
                                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">Arsipkan</a>
                                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">Naikkan Produk</a>
                                                <a href="#" class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">Atur Komisi Affiliate</a>
                                                <div class="border-t border-gray-100 dark:border-gray-800 my-1"></div>
                                                <form action="{{ route('tenant.products.toggle_active', $product) }}" method="POST" class="w-full text-left">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#0d1117] transition-colors">{{ $product->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                                </form>
                                                <form action="{{ route('tenant.products.destroy', $product) }}" method="POST" class="w-full text-left" onsubmit="return confirm('Hapus produk ini secara permanen?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-full text-left px-4 py-2 text-xs text-error hover:bg-error/5 transition-colors">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-10 text-center">
                                    <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                        <span class="material-symbols-outlined text-6xl mb-2 opacity-50">inventory_2</span>
                                        <p class="font-semibold text-lg text-on-surface">Tidak ada produk ditemukan</p>
                                        <p class="text-sm mt-1">Anda belum mengunggah produk apa pun.</p>
                                        <a href="{{ route('tenant.products.create') }}" class="mt-4 px-6 py-2 bg-primary text-white font-bold rounded hover:bg-primary/90 transition-colors" wire:navigate>Tambah Produk Baru</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination Space -->
                <div class="flex justify-between items-center py-2 text-sm text-on-surface-variant">
                @if($products->hasPages())
                <div class="mt-4">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
