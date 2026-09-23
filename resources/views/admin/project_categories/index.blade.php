@extends('layouts.admin')
@section('title', 'Project Categories')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-5xl mx-auto w-full" x-data="{ 
    editModalOpen: false, 
    editId: null, 
    editName: '',
    editAction: '',
    openEditModal(id, name, updateUrl) {
        this.editId = id;
        this.editName = name;
        this.editAction = updateUrl;
        this.editModalOpen = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-lg">
        <div>
            <h1 class="font-headline-sm font-bold text-on-surface">Project Categories</h1>
            <p class="font-body-md text-on-surface-variant text-sm mt-0.5">Kelola kategori untuk pengelompokan portofolio dan proyek.</p>
        </div>
        <form action="{{ route('admin.project_categories.store') }}" method="POST" class="flex gap-2 w-full sm:w-auto">
            @csrf
            <input type="text" name="name" required placeholder="Nama Kategori Baru" class="px-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary/20 text-sm flex-1 sm:w-64">
            <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg font-bold transition flex items-center gap-1.5 text-sm shrink-0 shadow-none">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 flex items-center gap-2 text-sm" role="alert">
            <span class="material-symbols-outlined text-[20px] text-green-600">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-surface rounded-md border border-outline-variant shadow-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-4 font-label-md font-bold text-on-surface-variant text-xs uppercase tracking-wider">Nama Kategori</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant text-xs uppercase tracking-wider">Slug</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant text-xs uppercase tracking-wider text-center">Jumlah Proyek</th>
                        <th class="p-4 font-label-md font-bold text-on-surface-variant text-xs uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant bg-surface">
                    @forelse($categories as $category)
                    <tr class="hover:bg-surface-container-low/50 transition-colors group">
                        <td class="p-4 font-medium text-on-surface">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">folder</span>
                                <span>{{ $category->name }}</span>
                            </div>
                        </td>
                        <td class="p-4 font-code-sm text-xs text-on-surface-variant">
                            <span class="bg-surface-container-low px-2 py-1 rounded border border-outline-variant/50">{{ $category->slug }}</span>
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-secondary-container/30 text-secondary">
                                {{ $category->projects_count ?? $category->projects()->count() }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <!-- Tombol Edit (Aksi Update) -->
                                <button type="button" 
                                    @click="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ route('admin.project_categories.update', $category) }}')" 
                                    class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" 
                                    title="Edit Kategori">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('admin.project_categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Proyek yang menggunakan kategori ini akan menjadi tidak berkategori.');" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Hapus Kategori">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[48px] text-outline-variant block mx-auto mb-2">folder_off</span>
                            Tidak ada kategori proyek ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div x-show="editModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" 
         style="display: none;" 
         @keydown.escape.window="editModalOpen = false">
        <div class="bg-surface border border-outline-variant rounded-xl shadow-none w-full max-w-md overflow-hidden" 
             @click.outside="editModalOpen = false">
            <div class="px-6 py-4 border-b border-outline-variant flex items-center justify-between">
                <h3 class="font-headline-sm text-base font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">edit</span>
                    Edit Kategori Proyek
                </h3>
                <button type="button" @click="editModalOpen = false" class="text-on-surface-variant hover:text-on-surface rounded p-1">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form :action="editAction" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block font-label-md text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">
                            Nama Kategori
                        </label>
                        <input type="text" 
                               name="name" 
                               x-model="editName" 
                               required 
                               class="w-full px-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md text-on-surface focus:border-primary focus:ring-1 focus:ring-primary/20 text-sm">
                    </div>
                </div>
                <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant flex justify-end gap-2">
                    <button type="button" 
                            @click="editModalOpen = false" 
                            class="px-4 py-2 rounded-lg border border-outline-variant hover:bg-surface-variant/50 text-sm font-semibold text-on-surface transition">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-primary hover:bg-primary/90 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-none transition flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px]">save</span> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
