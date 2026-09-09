@extends('layouts.admin')
@section('title', 'Tambah Iklan Pop-up')

@section('content')
<div class="container-fluid px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.popup_ads.index') }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-1 font-medium text-sm" wire:navigate>
            <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar
        </a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">Tambah Iklan Baru</h1>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-md  border border-gray-100 dark:border-slate-700 overflow-hidden max-w-3xl">
        <form action="{{ route('admin.popup_ads.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Iklan <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-900  focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                <textarea name="description" id="description" rows="4" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-900  focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="link_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">URL Tujuan (Tautan)</label>
                    <input type="url" name="link_url" id="link_url" value="{{ old('link_url') }}" placeholder="https://..." class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-900  focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('link_url') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="link_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Teks Tombol</label>
                    <input type="text" name="link_text" id="link_text" value="{{ old('link_text', 'Selengkapnya') }}" class="w-full rounded-md border-gray-300 dark:border-slate-600 dark:bg-slate-900  focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('link_text') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-2">
                    Target Sasaran (Tujuan Muncul) <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- Option: Semua Pengguna -->
                    <label class="relative flex items-start p-3.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 cursor-pointer hover:border-indigo-500 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40 dark:has-[:checked]:bg-indigo-950/20 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                        <input type="radio" name="target_audience" value="all" {{ old('target_audience', 'all') === 'all' ? 'checked' : '' }} class="mt-1 text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-blue-500">public</span>
                                Semua Pengguna (Umum)
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">
                                Tampil untuk siapa saja (publik, calon member, dan pengguna terdaftar).
                            </span>
                        </div>
                    </label>

                    <!-- Option: Calon Member -->
                    <label class="relative flex items-start p-3.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 cursor-pointer hover:border-indigo-500 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40 dark:has-[:checked]:bg-indigo-950/20 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                        <input type="radio" name="target_audience" value="guest" {{ old('target_audience') === 'guest' ? 'checked' : '' }} class="mt-1 text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-amber-500">person_add</span>
                                Calon Member (Belum Login / Publik)
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">
                                Khusus pengunjung yang belum login. Bagus untuk ajakan daftar & promo new member.
                            </span>
                        </div>
                    </label>

                    <!-- Option: Member / Pembeli -->
                    <label class="relative flex items-start p-3.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 cursor-pointer hover:border-indigo-500 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40 dark:has-[:checked]:bg-indigo-950/20 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                        <input type="radio" name="target_audience" value="customer" {{ old('target_audience') === 'customer' ? 'checked' : '' }} class="mt-1 text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-emerald-500">shopping_bag</span>
                                Member / Pembeli Terdaftar
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">
                                Khusus member yang sudah login. Bagus untuk info promo belanja & saldo.
                            </span>
                        </div>
                    </label>

                    <!-- Option: Khusus Toko / Tenant -->
                    <label class="relative flex items-start p-3.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 cursor-pointer hover:border-indigo-500 transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/40 dark:has-[:checked]:bg-indigo-950/20 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                        <input type="radio" name="target_audience" value="tenant" {{ old('target_audience') === 'tenant' ? 'checked' : '' }} class="mt-1 text-indigo-600 focus:ring-indigo-500">
                        <div class="ml-3">
                            <span class="text-sm font-bold text-gray-800 dark:text-gray-200 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-purple-500">storefront</span>
                                Khusus Mitra Toko / Tenant
                            </span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">
                                Khusus pemilik toko. Tampil di Beranda & Dashboard Toko (Seller Center).
                            </span>
                        </div>
                    </label>
                </div>
                @error('target_audience') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gambar (Bisa pilih lebih dari 1)</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks: 2MB per gambar.</p>
                @error('images.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-start">
                <div class="flex h-5 items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                </div>
                <div class="ml-3 text-sm">
                    <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">Aktifkan Iklan Ini</label>
                    <p class="text-gray-500 dark:text-gray-400">Jika dicentang, pop-up ini berpotensi tampil di beranda.</p>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-lg shadow transition-colors">
                    Simpan Iklan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
