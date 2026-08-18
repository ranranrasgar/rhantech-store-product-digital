@extends('layouts.admin')

@section('title', 'Kategori Bantuan')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Kategori Bantuan</h1>
            <p class="text-sm text-gray-500">Kelola kategori untuk pusat bantuan.</p>
        </div>
        <a href="{{ route('admin.help_categories.create') }}" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">add</span> Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-variant/30 text-xs uppercase text-on-surface-variant font-bold border-b border-outline-variant">
                    <th class="p-4">Ikon & Kategori</th>
                    <th class="p-4">Deskripsi</th>
                    <th class="p-4 text-center">Urutan</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($categories as $category)
                <tr class="hover:bg-surface-variant/10 transition-colors">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-[24px]">{{ $category->icon ?: 'folder' }}</span>
                            <div>
                                <p class="font-bold text-on-surface">{{ $category->name }}</p>
                                <p class="text-xs text-gray-500">Slug: {{ $category->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-on-surface-variant max-w-xs truncate">
                        {{ $category->description }}
                    </td>
                    <td class="p-4 text-center text-sm font-semibold">
                        {{ $category->sort_order }}
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.help_categories.edit', $category->id) }}" class="p-2 text-primary hover:bg-primary/10 rounded transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form action="{{ route('admin.help_categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini? Semua artikel di dalamnya akan ikut terhapus!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-error hover:bg-error/10 rounded transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-500">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">inbox</span>
                        <p>Belum ada kategori bantuan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
