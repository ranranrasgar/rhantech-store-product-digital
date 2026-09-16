@extends('layouts.tenant')

@section('title', 'Portofolio & Proyek Toko')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto space-y-6" x-data="{
    isProModalOpen: false,
    proModalFeature: 'Modul Portofolio & Proyek',
    openProModal(f = 'Modul Portofolio & Proyek') { this.proModalFeature = f; this.isProModalOpen = true; },
    closeProModal() { this.isProModalOpen = false; }
}">
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 flex items-center gap-3">
            <span class="material-symbols-outlined text-[24px]">check_circle</span>
            <div class="text-sm font-semibold">{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-600 dark:text-rose-400 flex items-center gap-3">
            <span class="material-symbols-outlined text-[24px]">error</span>
            <div class="text-sm font-semibold">{{ session('error') }}</div>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-xl font-black text-slate-900 dark:text-white">Portofolio & Proyek Toko</h1>
                @if($store->isPro())
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-black bg-amber-400 text-slate-950 shadow-xs border border-amber-300">
                        ★ TOKO PRO
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-500/10 text-amber-500 border border-amber-500/30">
                        <span class="material-symbols-outlined text-[13px]">lock</span> Khusus Akun PRO
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 dark:text-slate-400">Pamerkan karya terbaik, aplikasi, desain, dan portofolio proyek digital toko Anda kepada calon pembeli.</p>
        </div>

        <div>
            @if($store->isPro())
                <a href="{{ route('tenant.projects.create') }}" class="px-4 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-600 text-white dark:bg-sky-600 dark:hover:bg-sky-500 dark:text-white font-bold text-xs transition-all flex items-center gap-1.5 cursor-pointer active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    <span>Tambah Proyek Baru</span>
                </a>
            @else
                <button type="button" @click="openProModal()" class="px-4 py-2.5 rounded-xl font-black text-xs text-slate-950 bg-amber-400 hover:bg-amber-300 transition-all flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                    <span>Buka Modul Portofolio PRO</span>
                </button>
            @endif
        </div>
    </div>

    @if(!$store->isPro())
        <div class="p-6 rounded-3xl bg-white dark:bg-[#111726] border border-slate-200/90 dark:border-[#222f49] flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0 border border-amber-500/20">
                    <span class="material-symbols-outlined text-2xl">folder_special</span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-900 dark:text-white">Buka Etalase Portofolio dengan Akun PRO</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tingkatkan closing rate toko hingga 4x lipat dengan membuktikan kualitas karya digital Anda langsung di etalase toko publik.</p>
                </div>
            </div>
            <a href="{{ route('tenant.pro.index') }}" class="px-4 py-2 rounded-xl text-xs font-black text-slate-950 bg-amber-400 hover:bg-amber-300 shrink-0 cursor-pointer transition-colors">
                Upgrade ke PRO
            </a>
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $proj)
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-3xl overflow-hidden flex flex-col justify-between transition-all">
                <div>
                    <div class="h-44 w-full bg-slate-100 dark:bg-slate-800 relative overflow-hidden">
                        @if($proj->thumbnail)
                            <img src="{{ asset('storage/' . $proj->thumbnail) }}" alt="{{ $proj->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-5 space-y-2">
                        <h3 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-1">{{ $proj->title }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $proj->short_description ?? 'Tidak ada deskripsi singkat.' }}</p>
                        @if(!empty($proj->technologies) && is_array($proj->technologies))
                            <div class="flex flex-wrap gap-1 pt-1">
                                @foreach(array_slice($proj->technologies, 0, 3) as $tech)
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-[#1a2336] text-[10px] font-medium text-slate-600 dark:text-slate-300">
                                        {{ $tech }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="p-5 pt-0 border-t border-slate-100 dark:border-[#1e2a42] flex items-center justify-between gap-2 mt-2">
                    <div class="text-[11px] text-slate-400">
                        {{ $proj->created_at->format('d M Y') }}
                    </div>
                    <div class="flex items-center gap-1">
                        <a href="{{ route('tenant.projects.edit', $proj->id) }}" class="p-1.5 rounded-lg text-slate-500 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>
                        <form action="{{ route('tenant.projects.destroy', $proj->id) }}" method="POST" onsubmit="return confirm('Hapus proyek ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 cursor-pointer transition-colors">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white dark:bg-[#111726] border border-dashed border-slate-300 dark:border-[#222f49] rounded-3xl p-8 space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-3xl">folder_open</span>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Belum Ada Proyek Portofolio</h3>
                <p class="text-xs text-slate-400 max-w-sm mx-auto">Tambahkan karya digital, portofolio pembuatan web, aplikasi, atau desain untuk dipajang di profil toko Anda.</p>
            </div>
        @endforelse
    </div>

    @if($projects->hasPages())
        <div>{{ $projects->links() }}</div>
    @endif

    @include('components.pro-upgrade-modal')
</div>
@endsection
