@extends('layouts.tenant')

@section('title', 'Pengaturan Profil Toko')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200" x-data="{ tab: 'profil' }">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    Pengaturan Toko
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kelola informasi profil toko, logo brand, dan data rekening pencairan saldo.
                </p>
            </div>
            
            @if(isset($store) && $store->slug)
            <div class="flex items-center gap-3">
                <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] hover:bg-slate-50 dark:hover:bg-[#161f33] text-slate-700 dark:text-slate-200 text-xs md:text-sm font-semibold transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-sky-500">storefront</span>
                    Halaman Toko Publik
                </a>
            </div>
            @endif
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 text-xs md:text-sm font-semibold flex items-center gap-2.5 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">warning</span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <!-- Main Card Form -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
            
            <!-- Navigation Tabs -->
            <div class="border-b border-slate-100 dark:border-[#222f49] px-6 flex items-center gap-8 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50">
                <button type="button" @click="tab = 'profil'" :class="tab === 'profil' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">store</span> Profil Toko
                </button>
                <button type="button" @click="tab = 'rekening'" :class="tab === 'rekening' ? 'text-sky-600 dark:text-sky-400 border-sky-600 dark:border-sky-400' : 'text-slate-500 dark:text-slate-400 border-transparent hover:text-slate-800 dark:hover:text-slate-200'" class="py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 flex items-center gap-2 cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">credit_card</span> Rekening Bank
                </button>
            </div>

            <form action="{{ route('tenant.store.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                @csrf

                <!-- TAB 1: PROFIL TOKO -->
                <div x-show="tab === 'profil'" class="space-y-6">
                    
                    <!-- Logo Toko -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                            Logo Brand Toko
                        </label>
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                            <div class="w-24 h-24 rounded-2xl bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 overflow-hidden flex items-center justify-center shrink-0 shadow-inner">
                                @if(isset($store) && $store->logo)
                                    <img id="logo-preview" src="{{ asset('storage/' . $store->logo) }}" alt="Logo" class="w-full h-full object-cover">
                                @else
                                    <img id="logo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($store->name ?? 'Toko') }}&background=0284c7&color=fff" alt="Logo" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <input type="file" name="logo" id="logo-input" accept="image/*" class="block w-full text-xs text-slate-500 dark:text-slate-400
                                  file:mr-4 file:py-2.5 file:px-4
                                  file:rounded-xl file:border-0
                                  file:text-xs file:font-bold
                                  file:bg-sky-500/10 file:text-sky-600
                                  dark:file:bg-sky-500/20 dark:file:text-sky-400
                                  hover:file:bg-sky-500/20
                                  transition-all cursor-pointer
                                " onchange="previewImage(event)">
                                <p class="text-[11px] text-slate-400 mt-2">Disarankan rasio 1:1 (persegi). Format: JPG, PNG, WEBP. Maks. 2MB.</p>
                                @error('logo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nama Toko -->
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Nama Toko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $store->name ?? '') }}" required
                            class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all" placeholder="Contoh: Digital Code Studio">
                        @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Custom URL / Slug Toko -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="slug" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Tautan URL / Slug Toko
                            </label>
                            <span class="text-[11px] text-slate-400">Bebas ditentukan sendiri (unik)</span>
                        </div>
                        <div class="flex items-center rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] overflow-hidden focus-within:ring-2 focus-within:ring-sky-500/20 focus-within:border-sky-500">
                            <span class="px-3.5 py-2.5 text-xs md:text-sm font-semibold text-slate-400 border-r border-slate-200 dark:border-[#222f49] bg-slate-100/60 dark:bg-[#111726] select-none whitespace-nowrap">
                                {{ url('/') }}/
                            </span>
                            <input type="text" id="slug" name="slug" value="{{ old('slug', $store->slug ?? '') }}" placeholder="gudang-aplikasi"
                                class="flex-1 px-3.5 py-2.5 text-xs md:text-sm bg-transparent border-0 focus:outline-none text-slate-900 dark:text-white font-mono">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">
                            Gunakan huruf kecil, angka, atau strip (-). Contoh: <strong class="text-sky-600 dark:text-sky-400">gudang-aplikasi</strong> sehingga alamat tokomu menjadi <span class="font-mono text-[11px]">{{ url('/') }}/gudang-aplikasi</span>
                        </p>
                        @error('slug') <span class="text-xs text-rose-500 mt-1 block font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <!-- Deskripsi Toko -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Deskripsi Singkat Toko
                        </label>
                        <textarea id="description" name="description" rows="3" 
                            class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all leading-relaxed" placeholder="Jelaskan spesialisasi produk digital toko Anda...">{{ old('description', $store->description ?? '') }}</textarea>
                        @error('description') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4 border-t border-slate-100 dark:border-[#1d273d]">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-sky-500">location_on</span> Lokasi & Alamat (Opsional)
                        </h3>

                        <!-- Alamat Fisik -->
                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                                    Alamat Domisili / Lokasi Toko
                                </label>
                                <textarea id="address" name="address" rows="2" 
                                    class="w-full px-4 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all" placeholder="Kota, Provinsi, Indonesia">{{ old('address', $store->address ?? '') }}</textarea>
                                @error('address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Hidden Geolocation Coordinates for Auto-Maps -->
                            <input type="hidden" name="latitude" id="geo-lat" value="">
                            <input type="hidden" name="longitude" id="geo-lng" value="">

                            <!-- Info Lokasi Otomatis (Readonly untuk Keamanan Superadmin) -->
                            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] flex items-start gap-3">
                                <span class="material-symbols-outlined text-[20px] text-sky-500 shrink-0 mt-0.5">my_location</span>
                                <div class="text-xs">
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block mb-0.5">Titik Lokasi Google Maps Otomatis</span>
                                    <p class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed">
                                        Koordinat dan tautan Google Maps akan otomatis digenerate oleh sistem saat formulir disimpan berdasarkan izin lokasi browser atau alamat yang Anda isi.
                                    </p>
                                    @if(isset($store) && $store->maps_location)
                                    <div class="mt-2">
                                        <a href="{{ $store->maps_location }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-sky-600 dark:text-sky-400 hover:underline text-[11px]">
                                            <span class="material-symbols-outlined text-[14px]">open_in_new</span> Lihat Titik Lokasi Tersimpan
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- TAB 2: REKENING BANK -->
                <div x-show="tab === 'rekening'" class="space-y-6" style="display: none;">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-emerald-500">account_balance</span> Rekening Pencairan Saldo Penjual
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                            Data rekening ini digunakan saat Anda mengajukan penarikan saldo penghasilan toko.
                        </p>
                    </div>

                    <div>
                        <label for="bank_account_info" class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-2">
                            Informasi Rekening Bank Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea id="bank_account_info" name="bank_account_info" rows="5" 
                            class="w-full px-4 py-3 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 text-slate-900 dark:text-white transition-all font-mono leading-relaxed" placeholder="Contoh:&#10;Bank BCA&#10;No. Rekening: 4370351509&#10;Atas Nama: RANRAN RAHAYU">{{ old('bank_account_info', $store->bank_account_info ?? '') }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-2">Pastikan nama pemilik rekening sesuai dengan nama identitas Anda untuk kelancaran verifikasi pencairan.</p>
                        @error('bank_account_info') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Action Submit Button -->
                <div class="pt-6 border-t border-slate-100 dark:border-[#1d273d] flex items-center justify-end gap-3">
                    <a href="{{ route('tenant.dashboard') }}" class="px-5 py-2.5 text-xs md:text-sm font-semibold border border-slate-200 dark:border-[#222f49] text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-[#161f33] transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-xs md:text-sm font-bold text-white bg-sky-500 hover:bg-sky-400 rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 transition-all flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Pengaturan
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('logo-preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

// Otomatis deteksi koordinat browser saat halaman dibuka
document.addEventListener('DOMContentLoaded', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const latInput = document.getElementById('geo-lat');
            const lngInput = document.getElementById('geo-lng');
            if (latInput && lngInput) {
                latInput.value = position.coords.latitude;
                lngInput.value = position.coords.longitude;
            }
        }, function(error) {
            // Geolocation fallback to address
            console.log('Geolocation permission skipped or unavailable:', error.message);
        });
    }
});
</script>
@endsection
