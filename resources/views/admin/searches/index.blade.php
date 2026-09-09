@extends('layouts.admin')

@section('title', 'Analitik Pencarian Pembeli')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-black text-gray-800 dark:text-white flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-2xl">insights</span>
                Analitik Pencarian Pembeli
            </h1>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pantau apa saja yang sering dicari oleh calon pembeli untuk mengetahui kebutuhan dan tren pasar.
            </p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">manage_search</span>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Kata Kunci Unik</p>
                <h3 class="text-lg md:text-xl font-black text-gray-800 dark:text-white">{{ number_format($totalSearches) }}</h3>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">trending_up</span>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total Frekuensi Pencarian</p>
                <h3 class="text-lg md:text-xl font-black text-gray-800 dark:text-white">{{ number_format($totalHits) }} <span class="text-xs font-normal text-gray-400">kali</span></h3>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-2xl">production_quantity_limits</span>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Permintaan Belum Ada Produk</p>
                <h3 class="text-lg md:text-xl font-black text-rose-500">{{ number_format($unmetDemandsCount) }} <span class="text-xs font-normal text-gray-400">kata kunci</span></h3>
            </div>
        </div>
    </div>

    {{-- Filter & Search Controls --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.searches.index') }}" class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2 flex-1 min-w-[240px]">
                <div class="relative flex-1 max-w-sm">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kata kunci..."
                           class="w-full pl-9 pr-3 py-1.5 text-xs bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-100 focus:ring-1 focus:ring-primary focus:border-primary">
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                </div>
                <select name="filter" onchange="this.form.submit()" class="text-xs py-1.5 px-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200">
                    <option value="">Semua Status</option>
                    <option value="not_found" {{ request('filter') === 'not_found' ? 'selected' : '' }}>Belum Ada Produk (Hasil = 0)</option>
                    <option value="found" {{ request('filter') === 'found' ? 'selected' : '' }}>Produk Ditemukan (> 0)</option>
                </select>
                <select name="sort" onchange="this.form.submit()" class="text-xs py-1.5 px-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200">
                    <option value="hits" {{ request('sort', 'hits') === 'hits' ? 'selected' : '' }}>Paling Sering Dicari</option>
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terakhir Dicari</option>
                    <option value="least" {{ request('sort') === 'least' ? 'selected' : '' }}>Paling Sedikit Dicari</option>
                </select>
            </div>
            @if(request('q') || request('filter') || request('sort'))
                <a href="{{ route('admin.searches.index') }}" class="text-xs text-rose-500 hover:underline flex items-center gap-1 font-semibold">
                    <span class="material-symbols-outlined text-sm">refresh</span> Reset Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Searches Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">
                        <th class="py-3 px-4">Kata Kunci Dicari</th>
                        <th class="py-3 px-4 text-center">Frekuensi Dicari</th>
                        <th class="py-3 px-4 text-center">Hasil Produk Tersedia</th>
                        <th class="py-3 px-4">Terakhir Dicari</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($searches as $search)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="py-3 px-4">
                                <div class="font-bold text-gray-800 dark:text-gray-100 text-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined text-base text-gray-400">search</span>
                                    <span>{{ $search->keyword }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-black bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-xs">trending_up</span>
                                    {{ number_format($search->hits) }}x
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($search->results_count > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                        {{ $search->results_count }} Produk
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-500/10 text-rose-500" title="Pembeli mencari ini, tapi belum ada toko/produk yang menjualnya!">
                                        <span class="material-symbols-outlined text-[13px]">warning</span>
                                        0 Produk (Peluang Pasar)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-500 dark:text-gray-400">
                                {{ $search->last_searched_at ? $search->last_searched_at->diffForHumans() : $search->updated_at->diffForHumans() }}
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('products.index', ['search' => $search->keyword]) }}" target="_blank"
                                       class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-primary transition-colors"
                                       title="Lihat hasil di toko">
                                        <span class="material-symbols-outlined text-base">open_in_new</span>
                                    </a>
                                    <form action="{{ route('admin.searches.destroy', $search->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pencarian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-rose-50 dark:hover:bg-rose-900/30 text-gray-400 hover:text-rose-500 transition-colors" title="Hapus kata kunci">
                                            <span class="material-symbols-outlined text-base">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                <span class="material-symbols-outlined text-4xl text-gray-300 dark:text-gray-600 mb-2">search_off</span>
                                <p>Belum ada riwayat pencarian yang tercatat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($searches->hasPages())
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 flex justify-center">
                {{ $searches->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
