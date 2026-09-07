@extends('layouts.tenant')

@section('title', 'Buat Promo Baru')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md p-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('tenant.campaigns.index') }}" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <span class="material-symbols-outlined text-2xl">arrow_back</span>
        </a>
        <h1 class="text-xl font-bold text-[#333] dark:text-white">Buat Campaign / Promo Baru</h1>
    </div>

    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 shadow-sm max-w-4xl" x-data="{ type: '{{ old('type', 'discount') }}' }">
        <form action="{{ route('tenant.campaigns.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama Promo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2">Nama Promo <span class="text-error">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Misal: Promo Kemerdekaan, Diskon Spesial..." class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                    @error('name') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Tipe Promo -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Tipe Promo <span class="text-error">*</span></label>
                    <select name="type" x-model="type" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                        <option value="discount">Diskon Langsung (Harga Coret)</option>
                        <option value="voucher">Voucher (Kode Kupon)</option>
                    </select>
                    @error('type') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Kode Voucher -->
                <div x-show="type === 'voucher'">
                    <label class="block text-sm font-semibold mb-2">Kode Voucher <span class="text-error">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="Misal: MERDEKA20" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc] uppercase" :required="type === 'voucher'">
                    @error('code') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Tipe Potongan -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Tipe Potongan Diskon <span class="text-error">*</span></label>
                    <select name="discount_type" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                        <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Nominal Rupiah (Rp)</option>
                    </select>
                    @error('discount_type') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Nilai Diskon -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Besar Potongan <span class="text-error">*</span></label>
                    <input type="number" name="discount_value" value="{{ old('discount_value') }}" placeholder="Misal: 20 atau 50000" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" min="0" step="0.01" required>
                    @error('discount_value') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Waktu Mulai <span class="text-error">*</span></label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                    @error('start_date') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Waktu Selesai <span class="text-error">*</span></label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                    @error('end_date') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Minimum Belanja -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Minimal Belanja (Rp)</label>
                    <input type="number" name="minimum_spend" value="{{ old('minimum_spend', 0) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" min="0">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan atau isi 0 jika tidak ada minimal belanja.</p>
                </div>

                <!-- Batas Penggunaan -->
                <div x-show="type === 'voucher'">
                    <label class="block text-sm font-semibold mb-2">Kuota Penggunaan Kupon</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="Misal: 100" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" min="1">
                    <p class="text-xs text-gray-500 mt-1">Batas maksimal kupon ini bisa dipakai.</p>
                </div>

                <!-- Target / Cakupan Promo -->
                <div class="md:col-span-2 p-4 rounded-xl bg-slate-50 dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49]" x-data="{ appliesTo: '{{ old('applies_to', 'all') }}' }">
                    <label class="block text-sm font-bold text-slate-800 dark:text-slate-100 mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-primary text-[18px]">target</span>
                        <span>Cakupan Target Promo <span class="text-error">*</span></span>
                    </label>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Tentukan apakah promo ini berlaku untuk seluruh produk di toko, atau hanya kategori & produk tertentu.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                        <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition-all"
                               :class="appliesTo === 'all' ? 'border-primary bg-primary/5 text-primary font-bold shadow-xs' : 'border-slate-200 dark:border-[#222f49] hover:bg-slate-100 dark:hover:bg-[#161f30] text-slate-700 dark:text-slate-300'">
                            <input type="radio" name="applies_to" value="all" x-model="appliesTo" class="text-primary focus:ring-primary">
                            <div>
                                <div class="text-xs font-bold">Semua Produk Toko</div>
                                <div class="text-[10px] opacity-75">Berlaku untuk semua katalog</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition-all"
                               :class="appliesTo === 'category' ? 'border-primary bg-primary/5 text-primary font-bold shadow-xs' : 'border-slate-200 dark:border-[#222f49] hover:bg-slate-100 dark:hover:bg-[#161f30] text-slate-700 dark:text-slate-300'">
                            <input type="radio" name="applies_to" value="category" x-model="appliesTo" class="text-primary focus:ring-primary">
                            <div>
                                <div class="text-xs font-bold">Kategori Tertentu</div>
                                <div class="text-[10px] opacity-75">Pilih kategori khusus</div>
                            </div>
                        </label>

                        <label class="flex items-center gap-2.5 p-3 rounded-lg border cursor-pointer transition-all"
                               :class="appliesTo === 'product' ? 'border-primary bg-primary/5 text-primary font-bold shadow-xs' : 'border-slate-200 dark:border-[#222f49] hover:bg-slate-100 dark:hover:bg-[#161f30] text-slate-700 dark:text-slate-300'">
                            <input type="radio" name="applies_to" value="product" x-model="appliesTo" class="text-primary focus:ring-primary">
                            <div>
                                <div class="text-xs font-bold">Produk Tertentu (Pilihan)</div>
                                <div class="text-[10px] opacity-75">Pilih produk spesifik</div>
                            </div>
                        </label>
                    </div>

                    <!-- Checklist Kategori (Jika Kategori Tertentu dipilih) -->
                    <div x-show="appliesTo === 'category'" x-cloak class="mt-3 pt-3 border-t border-slate-200 dark:border-[#222f49]">
                        <span class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-2">Pilih Kategori yang Dapat Promo:</span>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-48 overflow-y-auto p-2 bg-white dark:bg-[#0d1117] rounded-lg border border-slate-200 dark:border-[#30363d]">
                            @forelse($categories as $category)
                            <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer p-1.5 hover:bg-slate-50 dark:hover:bg-[#161b22] rounded">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="rounded text-primary focus:ring-primary" {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                                <span class="truncate">{{ $category->name }}</span>
                            </label>
                            @empty
                            <span class="col-span-full text-xs text-slate-400 italic">Belum ada kategori terdaftar.</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Checklist Produk (Jika Produk Tertentu dipilih) -->
                    <div x-show="appliesTo === 'product'" x-cloak class="mt-3 pt-3 border-t border-slate-200 dark:border-[#222f49]">
                        <span class="block text-xs font-bold text-slate-700 dark:text-slate-200 mb-2">Pilih Produk Toko yang Dapat Promo:</span>
                        <div class="space-y-1.5 max-h-56 overflow-y-auto p-2 bg-white dark:bg-[#0d1117] rounded-lg border border-slate-200 dark:border-[#30363d]">
                            @forelse($products as $prod)
                            <label class="flex items-center justify-between gap-3 text-xs text-slate-700 dark:text-slate-300 cursor-pointer p-2 hover:bg-slate-50 dark:hover:bg-[#161b22] rounded border border-transparent hover:border-slate-200 dark:hover:border-[#30363d] transition-all">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <input type="checkbox" name="product_ids[]" value="{{ $prod->id }}" class="rounded text-primary focus:ring-primary" {{ in_array($prod->id, old('product_ids', [])) ? 'checked' : '' }}>
                                    <span class="font-medium truncate">{{ $prod->name }}</span>
                                </div>
                                <span class="text-[11px] font-bold text-primary shrink-0">Rp{{ number_format($prod->price, 0, ',', '.') }}</span>
                            </label>
                            @empty
                            <span class="text-xs text-slate-400 italic p-2 block">Toko Anda belum memiliki produk aktif.</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Status Promo <span class="text-error">*</span></label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Terjadwal (Menunggu Waktu)</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Syarat & Ketentuan (Opsional)</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" placeholder="Tuliskan syarat dan ketentuan promo di sini...">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-[#30363d]">
                <a href="{{ route('tenant.campaigns.index') }}" class="px-4 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm font-medium hover:bg-gray-50 dark:hover:bg-[#30363d]">Batal</a>
                <button type="submit" class="px-4 py-2 bg-[#00b3cc] dark:bg-[#2f81f7] text-white rounded text-sm font-bold hover:bg-[#00838f] dark:hover:bg-[#1f6feb]">Simpan Promo</button>
            </div>
        </form>
    </div>
</div>
@endsection
