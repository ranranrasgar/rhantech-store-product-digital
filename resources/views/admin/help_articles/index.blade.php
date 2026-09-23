@extends('layouts.admin')

@section('title', 'Artikel Bantuan')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">Artikel Bantuan</h1>
            <p class="text-sm text-gray-500">Kelola artikel atau panduan untuk pengguna.</p>
        </div>
        <a href="{{ route('admin.help_articles.create') }}" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">add</span> Tulis Artikel
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-surface border border-outline-variant rounded-xl shadow-none overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-variant/30 text-xs uppercase text-on-surface-variant font-bold border-b border-outline-variant">
                    <th class="p-4">Judul Artikel</th>
                    <th class="p-4">Kategori</th>
                    <th class="p-4 text-center">Dilihat</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($articles as $article)
                <tr class="hover:bg-surface-variant/10 transition-colors">
                    <td class="p-4">
                        <p class="font-bold text-on-surface">{{ $article->title }}</p>
                        <p class="text-xs text-gray-500">/help/article/{{ $article->slug }}</p>
                    </td>
                    <td class="p-4 text-sm font-semibold">
                        <span class="px-2 py-1 bg-surface-variant text-on-surface-variant rounded-md text-xs">
                            {{ $article->category->name ?? 'Tanpa Kategori' }}
                        </span>
                    </td>
                    <td class="p-4 text-center text-sm font-semibold text-gray-500">
                        {{ number_format($article->views) }}x
                    </td>
                    <td class="p-4 text-center">
                        @if($article->is_published)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Dipublikasikan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Draf
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.help_articles.edit', $article->id) }}" class="p-2 text-primary hover:bg-primary/10 rounded transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            <form action="{{ route('admin.help_articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-error hover:bg-error/10 rounded transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-gray-500">
                        <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">article</span>
                        <p>Belum ada artikel bantuan yang ditulis.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
