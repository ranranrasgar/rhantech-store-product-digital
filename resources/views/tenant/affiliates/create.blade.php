@extends('layouts.tenant')

@section('title', 'Tambah Affiliate')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md p-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('tenant.affiliates.index') }}" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <span class="material-symbols-outlined text-2xl">arrow_back</span>
        </a>
        <h1 class="text-xl font-bold text-[#333] dark:text-white">Tambah Affiliate Baru</h1>
    </div>

    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 shadow-sm max-w-4xl">
        <form action="{{ route('tenant.affiliates.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Nama Affiliate <span class="text-error">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                    @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>
                
                <!-- Handle / Username -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Username / Handle <span class="text-error">*</span></label>
                    <input type="text" name="handle" value="{{ old('handle') }}" placeholder="@username" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                    @error('handle') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Avatar URL -->
                <div>
                    <label class="block text-sm font-semibold mb-2">URL Foto Profil (Avatar)</label>
                    <input type="url" name="avatar_url" value="{{ old('avatar_url') }}" placeholder="https://..." class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]">
                    @error('avatar_url') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Platform -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Platform <span class="text-error">*</span></label>
                    <select name="platform" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                        <option value="">Pilih Platform</option>
                        <option value="Instagram" {{ old('platform') == 'Instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="Tiktok" {{ old('platform') == 'Tiktok' ? 'selected' : '' }}>Tiktok</option>
                        <option value="Facebook" {{ old('platform') == 'Facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="Youtube" {{ old('platform') == 'Youtube' ? 'selected' : '' }}>Youtube</option>
                        <option value="Twitter" {{ old('platform') == 'Twitter' ? 'selected' : '' }}>Twitter</option>
                    </select>
                    @error('platform') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Pengikut -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Jumlah Pengikut <span class="text-error">*</span></label>
                    <input type="text" name="followers_count" value="{{ old('followers_count') }}" placeholder="Contoh: 10.5K, 1M, dsb" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                </div>

                <!-- Clicks -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Jumlah Klik <span class="text-error">*</span></label>
                    <input type="text" name="clicks_count" value="{{ old('clicks_count') }}" placeholder="Contoh: 1.2K" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                </div>

                <!-- Orders -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Jumlah Pesanan <span class="text-error">*</span></label>
                    <input type="text" name="orders_count" value="{{ old('orders_count') }}" placeholder="Contoh: 500+" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                </div>

                <!-- Sales Range -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Range Penjualan (Rp) <span class="text-error">*</span></label>
                    <input type="text" name="sales_range" value="{{ old('sales_range') }}" placeholder="Contoh: Rp10JT - Rp50JT" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                </div>
            </div>

            <!-- Kategori (Multiple) -->
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Kategori Konten</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $cat }}" class="rounded text-[#00b3cc] focus:ring-[#00b3cc]" {{ is_array(old('categories')) && in_array($cat, old('categories')) ? 'checked' : '' }}>
                            <span class="text-sm">{{ $cat }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Golden Tick -->
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_golden_tick" value="1" class="rounded text-[#00b3cc] focus:ring-[#00b3cc]" {{ old('is_golden_tick') ? 'checked' : '' }}>
                    <span class="text-sm font-semibold">Tandai sebagai Affiliate Terverifikasi (Centang Biru)</span>
                </label>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-[#30363d]">
                <a href="{{ route('tenant.affiliates.index') }}" class="px-4 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm font-medium hover:bg-gray-50 dark:hover:bg-[#30363d]">Batal</a>
                <button type="submit" class="px-4 py-2 bg-[#00b3cc] dark:bg-[#2f81f7] text-white rounded text-sm font-bold hover:bg-[#00838f] dark:hover:bg-[#1f6feb]">Simpan Affiliate</button>
            </div>
        </form>
    </div>
</div>
@endsection
