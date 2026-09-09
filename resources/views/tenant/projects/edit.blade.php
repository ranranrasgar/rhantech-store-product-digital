@extends('layouts.tenant')

@section('title', 'Edit Proyek Portofolio')

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-900 dark:text-white">Edit Portofolio Proyek</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Perbarui informasi dan tampilan proyek digital Anda</p>
        </div>
        <a href="{{ route('tenant.projects.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 flex items-center gap-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span> Kembali
        </a>
    </div>

    <form action="{{ route('tenant.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-3xl p-6 sm:p-8 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Judul Proyek / Aplikasi <span class="text-rose-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $project->title) }}" required class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-primary">
            @error('title') <p class="text-[11px] text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Deskripsi Singkat</label>
            <input type="text" name="short_description" value="{{ old('short_description', $project->short_description) }}" class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-primary">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Link Demo / URL Proyek</label>
                <input type="url" name="project_url" value="{{ old('project_url', $project->project_url) }}" class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-primary">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Teknologi yang Digunakan (Pisahkan koma)</label>
                <input type="text" name="technologies" value="{{ old('technologies', is_array($project->technologies) ? implode(', ', $project->technologies) : '') }}" class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-primary">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Tanggal Selesai Proyek</label>
                <input type="date" name="completed_at" value="{{ old('completed_at', $project->completed_at ? $project->completed_at->format('Y-m-d') : '') }}" class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-primary">
            </div>

            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Foto Thumbnail / Preview Proyek</label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full px-3 py-2 rounded-xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none">
                @if($project->thumbnail)
                    <div class="mt-2 w-24 h-16 rounded-lg overflow-hidden border border-slate-200">
                        <img src="{{ asset('storage/' . $project->thumbnail) }}" class="w-full h-full object-cover">
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">Detail & Cerita Proyek</label>
            <textarea name="description" rows="6" class="w-full px-4 py-3 rounded-2xl text-xs bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] text-slate-900 dark:text-white outline-none focus:border-primary leading-relaxed">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="pt-4 border-t border-slate-100 dark:border-[#1e2a42] flex justify-end gap-3">
            <a href="{{ route('tenant.projects.index') }}" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white font-bold text-xs shadow-md shadow-primary/25 hover:bg-primary/90 transition-all cursor-pointer">
                Perbarui Proyek
            </button>
        </div>
    </form>
</div>
@endsection
