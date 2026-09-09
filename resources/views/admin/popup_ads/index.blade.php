@extends('layouts.admin')
@section('title', 'Kelola Iklan Pop-up')

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Iklan Pop-up</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola pop-up promosi yang muncul di halaman utama.</p>
        </div>
        <a href="{{ route('admin.popup_ads.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow transition-colors inline-flex items-center gap-2" wire:navigate>
            <span class="material-symbols-outlined text-sm">add</span> Tambah Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded ">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-md  border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-700">
                        <th class="py-4 px-6 font-semibold text-sm text-gray-600 dark:text-gray-300">Judul</th>
                        <th class="py-4 px-6 font-semibold text-sm text-gray-600 dark:text-gray-300">Sasaran (Tujuan)</th>
                        <th class="py-4 px-6 font-semibold text-sm text-gray-600 dark:text-gray-300">Status</th>
                        <th class="py-4 px-6 font-semibold text-sm text-gray-600 dark:text-gray-300">Tautan</th>
                        <th class="py-4 px-6 font-semibold text-sm text-gray-600 dark:text-gray-300 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($popupAds as $ad)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $ad->title }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($ad->description, 50) }}</div>
                        </td>
                        <td class="py-4 px-6">
                            @if(($ad->target_audience ?? 'all') === 'guest')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    <span class="material-symbols-outlined text-[14px]">person_add</span> Calon Member
                                </span>
                            @elseif(($ad->target_audience ?? 'all') === 'customer')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="material-symbols-outlined text-[14px]">shopping_bag</span> Member
                                </span>
                            @elseif(($ad->target_audience ?? 'all') === 'tenant')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                    <span class="material-symbols-outlined text-[14px]">storefront</span> Mitra Toko
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    <span class="material-symbols-outlined text-[14px]">public</span> Semua Pengguna
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($ad->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-gray-400 border border-gray-200 dark:border-slate-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-300">
                            {{ $ad->link_url ?: '-' }}
                        </td>
                        <td class="py-4 px-6 text-right space-x-2">
                            <a href="{{ route('admin.popup_ads.edit', $ad->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium" wire:navigate>Edit</a>
                            <form action="{{ route('admin.popup_ads.destroy', $ad->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus iklan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 px-6 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data iklan pop-up.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($popupAds->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700">
            {{ $popupAds->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
