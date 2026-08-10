@extends('layouts.tenant')

@section('title', 'Pengaturan Toko')

@section('content')
<div class="flex-1 overflow-y-auto bg-surface-container-lowest dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md" x-data="{ tab: 'profil' }">
    
    <!-- Top Navigation Tabs -->
    <div class="bg-surface dark:bg-[#010409] px-6 flex items-center gap-6 text-sm font-semibold overflow-x-auto whitespace-nowrap shadow-sm border-b border-outline-variant dark:border-[#30363d] sticky top-0 z-30">
        <button @click="tab = 'profil'" :class="tab === 'profil' ? 'border-b-2 border-primary text-primary dark:border-[#2f81f7] dark:text-[#2f81f7]' : 'border-b-2 border-transparent text-on-surface-variant dark:text-gray-400 hover:text-primary dark:hover:text-gray-200'" class="py-4 px-2 transition-colors">Profil Toko</button>
        <button @click="tab = 'rekening'" :class="tab === 'rekening' ? 'border-b-2 border-primary text-primary dark:border-[#2f81f7] dark:text-[#2f81f7]' : 'border-b-2 border-transparent text-on-surface-variant dark:text-gray-400 hover:text-primary dark:hover:text-gray-200'" class="py-4 px-2 transition-colors">Rekening Bank</button>
    </div>

    <div class="p-6 space-y-6 max-w-4xl mx-auto min-h-[calc(100vh-140px)]">

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800" role="alert">
                <span class="font-medium">Berhasil!</span> {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800" role="alert">
                <span class="font-medium">Perhatian!</span> {{ session('warning') }}
            </div>
        @endif

        <form action="{{ route('tenant.store.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- TAB 1: PROFIL TOKO -->
            <div x-show="tab === 'profil'" class="space-y-6">
                <div class="bg-surface dark:bg-[#161b22] rounded shadow-sm border border-outline-variant dark:border-[#30363d] overflow-hidden">
                    <div class="px-6 py-4 border-b border-outline-variant dark:border-[#30363d]">
                        <h3 class="font-bold text-base text-on-surface dark:text-white">Informasi Dasar Toko</h3>
                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mt-1">Lengkapi profil toko Anda agar lebih menarik dan mudah ditemukan pelanggan.</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        
                        <!-- Logo -->
                        <div>
                            <label class="block text-sm font-semibold text-on-surface dark:text-gray-200 mb-2">Logo Toko</label>
                            <div class="flex items-center gap-6">
                                <div class="w-24 h-24 rounded border-2 border-dashed border-outline-variant dark:border-[#30363d] flex items-center justify-center overflow-hidden bg-surface-variant/50 dark:bg-black/20 shrink-0">
                                    @if(isset($store) && $store->logo)
                                        <img id="logo-preview" src="{{ asset('storage/' . $store->logo) }}" alt="Logo" class="w-full h-full object-cover">
                                    @else
                                        <img id="logo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($store->name ?? 'Toko') }}&background=0a1628&color=00d4ff" alt="Logo" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" id="logo-input" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-primary/10 file:text-primary
                                      dark:file:bg-[#2f81f7]/10 dark:file:text-[#2f81f7]
                                      hover:file:bg-primary/20 dark:hover:file:bg-[#2f81f7]/20
                                      transition-all
                                    " onchange="previewImage(event)">
                                    <p class="text-xs text-on-surface-variant dark:text-gray-500 mt-2">Format: JPG, PNG, GIF. Maksimal ukuran file 2MB.</p>
                                    @error('logo') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nama Toko -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-on-surface dark:text-gray-200 mb-2">Nama Toko <span class="text-error">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $store->name ?? '') }}" required
                                class="w-full rounded-md border-outline-variant dark:border-[#30363d] bg-background dark:bg-black/20 text-on-surface dark:text-white px-3 py-2 text-sm focus:border-primary dark:focus:border-[#2f81f7] focus:ring focus:ring-primary/20 dark:focus:ring-[#2f81f7]/20 transition-all" placeholder="Masukkan nama toko Anda">
                            @error('name') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Deskripsi Toko -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-on-surface dark:text-gray-200 mb-2">Deskripsi Toko</label>
                            <textarea id="description" name="description" rows="4" 
                                class="w-full rounded-md border-outline-variant dark:border-[#30363d] bg-background dark:bg-black/20 text-on-surface dark:text-white px-3 py-2 text-sm focus:border-primary dark:focus:border-[#2f81f7] focus:ring focus:ring-primary/20 dark:focus:ring-[#2f81f7]/20 transition-all" placeholder="Ceritakan tentang toko Anda, produk yang dijual, dll.">{{ old('description', $store->description ?? '') }}</textarea>
                            @error('description') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <hr class="border-outline-variant dark:border-[#30363d]">

                        <h4 class="font-bold text-sm text-on-surface dark:text-white">Lokasi & Alamat</h4>

                        <!-- Alamat Fisik -->
                        <div>
                            <label for="address" class="block text-sm font-semibold text-on-surface dark:text-gray-200 mb-2">Alamat Fisik Lengkap</label>
                            <textarea id="address" name="address" rows="3" 
                                class="w-full rounded-md border-outline-variant dark:border-[#30363d] bg-background dark:bg-black/20 text-on-surface dark:text-white px-3 py-2 text-sm focus:border-primary dark:focus:border-[#2f81f7] focus:ring focus:ring-primary/20 dark:focus:ring-[#2f81f7]/20 transition-all" placeholder="Contoh: Jl. Sudirman No. 123, Kelurahan X, Kecamatan Y, Kota Z, Provinsi, Kode Pos">{{ old('address', $store->address ?? '') }}</textarea>
                            @error('address') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Lokasi Maps -->
                        <div>
                            <label for="maps_location" class="block text-sm font-semibold text-on-surface dark:text-gray-200 mb-2">Lokasi Google Maps (URL / Embed Code)</label>
                            <textarea id="maps_location" name="maps_location" rows="3" 
                                class="w-full rounded-md border-outline-variant dark:border-[#30363d] bg-background dark:bg-black/20 text-on-surface dark:text-white px-3 py-2 text-sm focus:border-primary dark:focus:border-[#2f81f7] focus:ring focus:ring-primary/20 dark:focus:ring-[#2f81f7]/20 transition-all font-mono text-xs" placeholder='https://goo.gl/maps/... atau <iframe src="..."></iframe>'>{{ old('maps_location', $store->maps_location ?? '') }}</textarea>
                            <p class="text-xs text-on-surface-variant dark:text-gray-500 mt-2">Paste URL Google Maps atau kode embed HTML dari Google Maps di sini.</p>
                            @error('maps_location') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- TAB 2: REKENING BANK -->
            <div x-show="tab === 'rekening'" class="space-y-6" style="display: none;">
                <div class="bg-surface dark:bg-[#161b22] rounded shadow-sm border border-outline-variant dark:border-[#30363d] overflow-hidden">
                    <div class="px-6 py-4 border-b border-outline-variant dark:border-[#30363d]">
                        <h3 class="font-bold text-base text-on-surface dark:text-white">Informasi Pencairan Dana</h3>
                        <p class="text-xs text-on-surface-variant dark:text-gray-400 mt-1">Data rekening bank ini digunakan untuk mencairkan saldo penghasilan Anda.</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Info Rekening -->
                        <div>
                            <label for="bank_account_info" class="block text-sm font-semibold text-on-surface dark:text-gray-200 mb-2">Informasi Rekening Bank Lengkap</label>
                            <textarea id="bank_account_info" name="bank_account_info" rows="5" 
                                class="w-full rounded-md border-outline-variant dark:border-[#30363d] bg-background dark:bg-black/20 text-on-surface dark:text-white px-3 py-2 text-sm focus:border-primary dark:focus:border-[#2f81f7] focus:ring focus:ring-primary/20 dark:focus:ring-[#2f81f7]/20 transition-all" placeholder="Contoh:&#10;Bank BCA&#10;No. Rekening: 1234567890&#10;Atas Nama: PT Toko Digital Sukses">{{ old('bank_account_info', $store->bank_account_info ?? '') }}</textarea>
                            <p class="text-xs text-on-surface-variant dark:text-gray-500 mt-2">Pastikan nama pemilik rekening sesuai dengan identitas Anda.</p>
                            @error('bank_account_info') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Action Bar -->
            <div class="fixed bottom-0 left-0 lg:left-60 right-0 p-4 bg-surface dark:bg-[#010409]/95 backdrop-blur-md border-t border-outline-variant dark:border-[#30363d] flex justify-end gap-3 z-40">
                <a href="{{ route('tenant.dashboard') }}" class="px-4 py-2 text-sm font-semibold text-on-surface-variant dark:text-gray-300 hover:bg-surface-variant dark:hover:bg-white/10 rounded transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2 text-sm font-bold text-white bg-primary dark:bg-[#2f81f7] dark:text-[#0a1628] rounded shadow-md hover:bg-primary/90 dark:hover:bg-[#1f6feb] transition-colors">Simpan Pengaturan</button>
            </div>
            
            <!-- Padding bottom for sticky action bar -->
            <div class="h-20"></div>
        </form>

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
</script>
@endsection
