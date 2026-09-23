@extends('layouts.admin')
@section('title', 'Edit Paket Toko PRO - ' . $proPlan->name)

@section('content')
<div class="p-lg max-w-4xl mx-auto">
    <div class="flex items-center justify-between gap-4 mb-lg">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.pro_plans.index') }}" class="p-2 bg-surface-container-low text-on-surface-variant hover:bg-surface-variant rounded-full transition-colors flex items-center justify-center" wire:navigate>
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline-sm font-bold text-on-surface">Edit Paket Toko PRO: {{ $proPlan->name }}</h2>
                <p class="text-sm text-on-surface-variant">Ubah informasi tarif, masa aktif, dan keuntungan paket PRO ini.</p>
            </div>
        </div>
        <a href="{{ route('help.show', 'panduan-admin-manajemen-paket-langganan-toko-pro') }}" target="_blank" class="px-3 py-1.5 rounded-lg border border-amber-300 dark:border-amber-800 bg-amber-50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-300 font-semibold text-xs transition flex items-center gap-1.5 hover:bg-amber-100 shrink-0">
            <span class="material-symbols-outlined text-[16px]">menu_book</span>
            Buku Panduan
        </a>
    </div>

    <form action="{{ route('admin.pro_plans.update', $proPlan->id) }}" method="POST" class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant flex flex-col gap-6 shadow-none">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Paket -->
            <div>
                <label for="name" class="block font-label-md font-bold text-on-surface mb-2">
                    Nama Paket <span class="text-error">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $proPlan->name) }}" placeholder="Contoh: Bulanan, 6 Bulan, Tahunan" class="w-full bg-surface-container-low border @error('name') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
                @error('name')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slug / Kode Unik -->
            <div>
                <label for="slug" class="block font-label-md font-bold text-on-surface mb-2">
                    Kode / Slug Unik <span class="text-error">*</span>
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $proPlan->slug) }}" placeholder="Contoh: monthly, 6-months, yearly" class="w-full bg-surface-container-low border @error('slug') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2.5 text-on-surface font-mono text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
                <span class="text-xs text-on-surface-variant mt-1 block">Digunakan sebagai identifier sistem dan pencocokan paket transaksi.</span>
                @error('slug')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Harga (Rp) -->
            <div>
                <label for="price" class="block font-label-md font-bold text-on-surface mb-2">
                    Harga (Rp) <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm font-bold">Rp</span>
                    <input type="number" name="price" id="price" value="{{ old('price', (int)$proPlan->price) }}" placeholder="49000" min="0" step="1000" class="w-full bg-surface-container-low border @error('price') border-error @else border-outline-variant @enderror rounded-lg pl-11 pr-4 py-2.5 text-on-surface font-bold focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required>
                </div>
                @error('price')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Durasi (Hari) -->
            <div>
                <label for="duration_days" class="block font-label-md font-bold text-on-surface mb-2">
                    Masa Aktif (Jumlah Hari)
                </label>
                <input type="number" name="duration_days" id="duration_days" value="{{ old('duration_days', $proPlan->duration_days) }}" placeholder="30 (kosongkan jika Lifetime)" min="1" class="w-full bg-surface-container-low border @error('duration_days') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <span class="text-xs text-on-surface-variant mt-1 block">Kosongkan jika paket ini berlaku selamanya (Lifetime).</span>
                @error('duration_days')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Label Durasi Teks -->
            <div>
                <label for="duration_label" class="block font-label-md font-bold text-on-surface mb-2">
                    Teks Label Durasi
                </label>
                <input type="text" name="duration_label" id="duration_label" value="{{ old('duration_label', $proPlan->duration_label) }}" placeholder="Contoh: / 30 hari, / 1 tahun" class="w-full bg-surface-container-low border @error('duration_label') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                @error('duration_label')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Badge / Tag Promo -->
            <div>
                <label for="badge" class="block font-label-md font-bold text-on-surface mb-2">
                    Badge / Tag Promosi
                </label>
                <input type="text" name="badge" id="badge" value="{{ old('badge', $proPlan->badge) }}" placeholder="Contoh: Paling Populer, Hemat 32%, Eksklusif" class="w-full bg-surface-container-low border @error('badge') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                @error('badge')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Urutan Tampil -->
            <div>
                <label for="sort_order" class="block font-label-md font-bold text-on-surface mb-2">
                    Urutan Tampil (Sort Order)
                </label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $proPlan->sort_order) }}" min="0" class="w-full bg-surface-container-low border @error('sort_order') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <span class="text-xs text-on-surface-variant mt-1 block">Angka lebih kecil tampil lebih dulu di urutan kartu.</span>
            </div>
        </div>

        <!-- Deskripsi Singkat -->
        <div>
            <label for="description" class="block font-label-md font-bold text-on-surface mb-2">
                Deskripsi Singkat Paket
            </label>
            <input type="text" name="description" id="description" value="{{ old('description', $proPlan->description) }}" placeholder="Contoh: Sangat hemat untuk pemilik toko aktif dengan omzet harian." class="w-full bg-surface-container-low border @error('description') border-error @else border-outline-variant @enderror rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            @error('description')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Fitur & Keuntungan (1 per baris) -->
        <div>
            <label for="features" class="block font-label-md font-bold text-on-surface mb-2">
                Daftar Fitur & Keuntungan (Ketik 1 per baris)
            </label>
            <textarea name="features" id="features" rows="5" placeholder="Fee penarikan saldo 1%&#10;Fitur WhatsApp Broadcast pelanggan&#10;Modul Portofolio & Proyek&#10;Badge Terverifikasi PRO" class="w-full bg-surface-container-low border @error('features') border-error @else border-outline-variant @enderror rounded-lg px-4 py-3 text-on-surface text-sm focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary leading-relaxed font-sans">{{ old('features', $proPlan->features) }}</textarea>
            <span class="text-xs text-on-surface-variant mt-1 block">Setiap baris baru akan otomatis ditampilkan dengan icon centang hijau pada kartu paket.</span>
            @error('features')
                <p class="text-error text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Checkbox Options -->
        <div class="flex flex-wrap gap-6 pt-2">
            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_popular" value="1" {{ old('is_popular', $proPlan->is_popular) ? 'checked' : '' }} class="w-5 h-5 text-primary focus:ring-primary rounded border-outline-variant cursor-pointer">
                <div>
                    <span class="font-label-md font-bold text-on-surface block">Jadikan Paket Rekomendasi (Highlight)</span>
                    <span class="text-xs text-on-surface-variant">Kartu paket akan disorot dengan border khusus dan terpilih default saat halaman dimuat.</span>
                </div>
            </label>

            <label class="flex items-center gap-2.5 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $proPlan->is_active) ? 'checked' : '' }} class="w-5 h-5 text-primary focus:ring-primary rounded border-outline-variant cursor-pointer">
                <div>
                    <span class="font-label-md font-bold text-on-surface block">Status Aktif</span>
                    <span class="text-xs text-on-surface-variant">Paket dapat langsung dilihat dan dibeli oleh seller di halaman /dashboard/pro.</span>
                </div>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-outline-variant/30">
            <a href="{{ route('admin.pro_plans.index') }}" class="px-5 py-2.5 rounded-lg border border-outline-variant text-on-surface font-semibold hover:bg-surface-variant transition" wire:navigate>
                Batal
            </a>
            <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg font-bold hover:bg-primary/90 transition-colors shadow-none">
                Perbarui Paket PRO
            </button>
        </div>
    </form>
</div>
@endsection
