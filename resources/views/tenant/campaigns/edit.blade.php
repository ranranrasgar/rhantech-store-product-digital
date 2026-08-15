@extends('layouts.tenant')

@section('title', 'Edit Promo')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f6f6f6] dark:bg-[#0d1117] text-on-surface dark:text-white font-body-md p-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('tenant.campaigns.index') }}" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <span class="material-symbols-outlined text-2xl">arrow_back</span>
        </a>
        <h1 class="text-xl font-bold text-[#333] dark:text-white">Edit Campaign / Promo</h1>
    </div>

    <div class="bg-white dark:bg-[#161b22] border border-gray-200 dark:border-[#30363d] rounded-lg p-6 shadow-sm max-w-4xl" x-data="{ type: '{{ old('type', $campaign->type) }}' }">
        <form action="{{ route('tenant.campaigns.update', $campaign->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nama Promo -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-2">Nama Promo <span class="text-error">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $campaign->name) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
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
                    <input type="text" name="code" value="{{ old('code', $campaign->code) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc] uppercase" :required="type === 'voucher'">
                    @error('code') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Tipe Potongan -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Tipe Potongan Diskon <span class="text-error">*</span></label>
                    <select name="discount_type" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                        <option value="percentage" {{ old('discount_type', $campaign->discount_type) == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('discount_type', $campaign->discount_type) == 'fixed' ? 'selected' : '' }}>Nominal Rupiah (Rp)</option>
                    </select>
                    @error('discount_type') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Nilai Diskon -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Besar Potongan <span class="text-error">*</span></label>
                    <input type="number" name="discount_value" value="{{ old('discount_value', rtrim(rtrim($campaign->discount_value, '0'), '.')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" min="0" step="0.01" required>
                    @error('discount_value') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Waktu Mulai -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Waktu Mulai <span class="text-error">*</span></label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', $campaign->start_date->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                    @error('start_date') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Waktu Selesai -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Waktu Selesai <span class="text-error">*</span></label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date', $campaign->end_date->format('Y-m-d\TH:i')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                    @error('end_date') <span class="text-error text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Minimum Belanja -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Minimal Belanja (Rp)</label>
                    <input type="number" name="minimum_spend" value="{{ old('minimum_spend', rtrim(rtrim($campaign->minimum_spend, '0'), '.')) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" min="0">
                </div>

                <!-- Batas Penggunaan -->
                <div x-show="type === 'voucher'">
                    <label class="block text-sm font-semibold mb-2">Kuota Penggunaan Kupon</label>
                    <input type="number" name="usage_limit" value="{{ old('usage_limit', $campaign->usage_limit) }}" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" min="1">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold mb-2">Status Promo <span class="text-error">*</span></label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]" required>
                        <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="scheduled" {{ old('status', $campaign->status) == 'scheduled' ? 'selected' : '' }}>Terjadwal (Menunggu Waktu)</option>
                        <option value="inactive" {{ old('status', $campaign->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Syarat & Ketentuan (Opsional)</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm bg-white dark:bg-[#0d1117] focus:outline-none focus:border-[#00b3cc]">{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-[#30363d]">
                <a href="{{ route('tenant.campaigns.index') }}" class="px-4 py-2 border border-gray-300 dark:border-[#30363d] rounded text-sm font-medium hover:bg-gray-50 dark:hover:bg-[#30363d]">Batal</a>
                <button type="submit" class="px-4 py-2 bg-[#00b3cc] dark:bg-[#2f81f7] text-white rounded text-sm font-bold hover:bg-[#00838f] dark:hover:bg-[#1f6feb]">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
