@extends('layouts.admin')

@section('title', 'Tambah Kategori Bantuan')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.help_categories.index') }}" class="p-2 rounded-lg hover:bg-surface-variant transition-colors text-on-surface-variant">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Kategori</h1>
            <p class="text-sm text-gray-500">Buat kategori baru untuk pusat bantuan.</p>
        </div>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl shadow-none p-6">
        <form action="{{ route('admin.help_categories.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-2">Nama Kategori <span class="text-error">*</span></label>
                    <input type="text" name="name" id="name" required value="{{ old('name') }}" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">
                    @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Slug (URL) <span class="text-error">*</span></label>
                    <input type="text" name="slug" id="slug" required value="{{ old('slug') }}" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Hanya huruf kecil, angka, dan strip (-).</p>
                    @error('slug') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-2">Ikon (Material Symbols)</label>
                    <input type="text" name="icon" value="{{ old('icon', 'article') }}" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Contoh: <code>article</code>, <code>lock</code>, <code>payments</code>.</p>
                    @error('icon') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">
                    @error('sort_order') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Deskripsi Singkat</label>
                <textarea name="description" rows="3" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">{{ old('description') }}</textarea>
                @error('description') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-outline-variant">
                <a href="{{ route('admin.help_categories.index') }}" class="px-4 py-2 border border-outline-variant rounded-lg text-sm font-semibold hover:bg-surface-variant transition-colors">Batal</a>
                <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('name').addEventListener('input', function(e) {
        let slug = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        document.getElementById('slug').value = slug;
    });
</script>
@endpush
@endsection
