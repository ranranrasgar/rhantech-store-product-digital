@extends('layouts.admin')
@section('title', 'Paket Toko PRO')

@section('content')
<div class="p-lg">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-lg">
        <div>
            <h2 class="font-headline-sm font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">workspace_premium</span>
                Manajemen Paket Toko PRO
            </h2>
            <p class="text-sm text-on-surface-variant mt-1">
                Atur paket langganan, tarif biaya, masa aktif, dan keuntungan Toko PRO yang tampil di halaman seller (/dashboard/pro).
            </p>
        </div>
        <div class="flex items-center gap-2.5 flex-wrap">
            <a href="{{ route('help.show', 'panduan-admin-manajemen-paket-langganan-toko-pro') }}" target="_blank" class="px-3.5 py-2 rounded-lg border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 font-bold text-sm transition flex items-center gap-1.5 hover:bg-amber-100">
                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                Panduan Paket PRO
            </a>
            <a href="{{ route('tenant.pro.index') }}" target="_blank" class="px-3.5 py-2 rounded-lg border border-outline-variant bg-surface-container-low hover:bg-surface-variant text-on-surface font-bold text-sm transition flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                Lihat di Dashboard Tenant
            </a>
            <a href="{{ route('admin.pro_plans.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-bold hover:bg-primary/90 transition-colors flex items-center gap-2 shadow-sm" wire:navigate>
                <span class="material-symbols-outlined text-[20px]">add</span>
                Tambah Paket PRO
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-secondary-container text-on-secondary-container p-4 rounded-lg mb-lg border border-secondary/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/50">
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Urutan</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Nama Paket</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Kode / Slug</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Harga</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Masa Aktif</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Badge / Label</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs">Status</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant uppercase text-xs text-right pr-6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-4 font-bold text-on-surface-variant text-center w-12">
                                {{ $plan->sort_order }}
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-on-surface flex items-center gap-2">
                                    {{ $plan->name }}
                                    @if($plan->is_popular)
                                        <span class="bg-amber-100 text-amber-900 dark:bg-amber-950 dark:text-amber-300 text-[10px] font-black px-2 py-0.5 rounded-full uppercase">
                                            Rekomendasi
                                        </span>
                                    @endif
                                </div>
                                @if($plan->description)
                                    <div class="text-xs text-on-surface-variant mt-0.5 line-clamp-1 max-w-xs">
                                        {{ $plan->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 font-mono text-xs">
                                <span class="bg-surface-variant px-2.5 py-1 rounded-md text-on-surface font-semibold">
                                    {{ $plan->slug }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-on-surface">
                                Rp {{ number_format($plan->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-on-surface">
                                @if($plan->duration_days)
                                    <span class="font-semibold">{{ $plan->duration_days }} Hari</span>
                                    <span class="text-xs text-on-surface-variant block">{{ $plan->duration_label }}</span>
                                @else
                                    <span class="font-bold text-purple-600 dark:text-purple-400 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">all_inclusive</span>
                                        Lifetime
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-xs">
                                @if($plan->badge)
                                    <span class="inline-block bg-surface-variant px-2.5 py-1 rounded-md font-medium text-on-surface">
                                        {{ $plan->badge }}
                                    </span>
                                @else
                                    <span class="text-on-surface-variant/60">-</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <form action="{{ route('admin.pro_plans.toggle-active', $plan->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="cursor-pointer">
                                        @if($plan->is_active)
                                            <span class="bg-[#e6f4ea] text-[#137333] px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 hover:opacity-80 transition" title="Klik untuk nonaktifkan">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#137333]"></span> Aktif
                                            </span>
                                        @else
                                            <span class="bg-[#fce8e6] text-[#c5221f] px-2.5 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 hover:opacity-80 transition" title="Klik untuk aktifkan">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#c5221f]"></span> Nonaktif
                                            </span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="p-4 text-right pr-6">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.pro_plans.edit', $plan->id) }}" class="text-secondary hover:bg-secondary/10 p-1.5 rounded-lg transition-colors" title="Edit Paket" wire:navigate>
                                        <span class="material-symbols-outlined text-[1.25rem]">edit</span>
                                    </a>
                                    <form action="{{ route('admin.pro_plans.destroy', $plan->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket PRO {{ $plan->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-error hover:bg-error/10 p-1.5 rounded-lg transition-colors cursor-pointer" title="Hapus Paket">
                                            <span class="material-symbols-outlined text-[1.25rem]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-on-surface-variant">
                                Belum ada paket Toko PRO. Klik tombol "Tambah Paket PRO" untuk mulai membuat paket.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
