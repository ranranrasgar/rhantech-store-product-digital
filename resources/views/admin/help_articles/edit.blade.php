@extends('layouts.admin')

@section('title', 'Edit Artikel Bantuan')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.help_articles.index') }}" class="p-2 rounded-lg hover:bg-surface-variant transition-colors text-on-surface-variant">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Artikel Bantuan</h1>
            <p class="text-sm text-gray-500">Perbarui panduan untuk pengguna platform.</p>
        </div>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl shadow-sm p-6">
        <form action="{{ route('admin.help_articles.update', $helpArticle->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-2">Judul Artikel <span class="text-error">*</span></label>
                    <input type="text" name="title" id="title" required value="{{ old('title', $helpArticle->title) }}" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">
                    @error('title') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Slug (URL) <span class="text-error">*</span></label>
                    <input type="text" name="slug" id="slug" required value="{{ old('slug', $helpArticle->slug) }}" class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">
                    <p class="text-xs text-gray-500 mt-1">URL ramah SEO. Hanya huruf kecil dan strip (-).</p>
                    @error('slug') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-semibold mb-2">Kategori <span class="text-error">*</span></label>
                    <select name="help_category_id" required class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary transition-colors">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('help_category_id', $helpArticle->help_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('help_category_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Status Publikasi</label>
                    <label class="flex items-center gap-3 mt-3 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $helpArticle->is_published) ? 'checked' : '' }} class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary">
                        <span class="text-sm text-on-surface">Terbitkan Artikel Ini</span>
                    </label>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Isi Artikel <span class="text-error">*</span></label>
                <div id="editor-container" class="w-full bg-background border border-outline-variant rounded-b-lg font-body-md text-on-surface" style="min-height: 400px;"></div>
                <input type="hidden" name="content" id="content" value="{{ old('content', $helpArticle->content) }}">
                @error('content') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-outline-variant">
                <a href="{{ route('admin.help_articles.index') }}" class="px-4 py-2 border border-outline-variant rounded-lg text-sm font-semibold hover:bg-surface-variant transition-colors">Batal</a>
                <button type="submit" class="bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Quill Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tulis panduan atau artikel di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['blockquote', 'code-block'],
                    ['clean']
                ]
            }
        });
        
        const oldContent = document.getElementById('content').value;
        if (oldContent) {
            quill.root.innerHTML = oldContent;
        }

        // Add custom styles to match theme
        document.querySelector('.ql-toolbar').classList.add('bg-surface-variant', 'border-outline-variant', 'rounded-t-lg');
        document.querySelector('.ql-container').classList.add('border-t-0', 'border-outline-variant', 'rounded-b-lg', 'bg-background', 'text-on-surface');

        document.querySelector('form').addEventListener('submit', function(e) {
            const contentInput = document.getElementById('content');
            if (quill.root.innerHTML === '<p><br></p>') {
                contentInput.value = '';
            } else {
                contentInput.value = quill.root.innerHTML;
            }
        });

        // Auto-slug
        document.getElementById('title').addEventListener('input', function(e) {
            let slug = e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            document.getElementById('slug').value = slug;
        });
    });
</script>
@endsection
