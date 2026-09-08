@extends('layouts.tenant')

@section('title', 'Edit Mitra Affiliate')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('tenant.affiliates.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] flex items-center justify-center text-slate-500 hover:text-slate-800 dark:hover:text-white transition">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                    Edit Mitra Affiliate
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Perbarui profil mitra, bagi hasil komisi, atau kontak afiliator.
                </p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6 md:p-8 shadow-sm">
            <form action="{{ route('tenant.affiliates.update', $affiliate->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Mitra -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Nama Mitra / Kreator <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $affiliate->name) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition" required>
                        @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Username / Media Sosial Handle -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Username / Akun Medsos
                        </label>
                        <input type="text" name="handle" value="{{ old('handle', $affiliate->handle) }}" placeholder="@username_kreator" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition">
                    </div>

                    <!-- No WhatsApp -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            No. WhatsApp (Kontak Mitra)
                        </label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $affiliate->whatsapp) }}" placeholder="081234567890" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition">
                    </div>

                    <!-- Kode Referral Unik -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Kode Referral Unik
                        </label>
                        <input type="text" name="referral_code" value="{{ old('referral_code', $affiliate->referral_code) }}" placeholder="Contoh: RIAN20" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm uppercase font-mono text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition">
                        @error('referral_code') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Persentase Komisi -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Bagi Hasil Komisi (%) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.5" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', $affiliate->commission_rate ?? 10) }}" class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm font-bold text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">%</span>
                        </div>
                    </div>

                    <!-- Upload Foto / Avatar -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Ganti Foto Profil (Avatar)
                        </label>
                        <div class="flex items-center gap-3">
                            @if($affiliate->avatar_url)
                                <img src="{{ $affiliate->avatar_url }}" class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shrink-0">
                            @endif
                            <input type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 dark:file:bg-sky-950/50 dark:file:text-sky-300 cursor-pointer border border-slate-200 dark:border-[#222f49] rounded-xl p-1.5 bg-slate-50 dark:bg-[#0c1220]">
                        </div>
                        @error('avatar') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Platform Utama Promosi -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Fokus Platform (Opsional)
                        </label>
                        @php $currentPlatform = strtolower(old('platform', $affiliate->platform)); @endphp
                        <select name="platform" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition">
                            <option value="Multi-Platform" {{ $currentPlatform == 'multi-platform' || empty($currentPlatform) ? 'selected' : '' }}>Multi-Platform (Semua Medsos / Bebas)</option>
                            <option value="Instagram" {{ $currentPlatform == 'instagram' ? 'selected' : '' }}>Instagram</option>
                            <option value="Tiktok" {{ $currentPlatform == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                            <option value="Youtube" {{ $currentPlatform == 'youtube' ? 'selected' : '' }}>YouTube</option>
                            <option value="Facebook" {{ $currentPlatform == 'facebook' ? 'selected' : '' }}>Facebook</option>
                            <option value="Twitter" {{ $currentPlatform == 'twitter' ? 'selected' : '' }}>X / Twitter</option>
                            <option value="WhatsApp" {{ $currentPlatform == 'whatsapp' ? 'selected' : '' }}>WhatsApp Group / Channel</option>
                        </select>
                    </div>

                    <!-- Estimasi Pengikut -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">
                            Estimasi Pengikut / Followers (Opsional)
                        </label>
                        <input type="text" name="followers_count" value="{{ old('followers_count', $affiliate->followers_count) }}" placeholder="Contoh: 15K" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] bg-slate-50 dark:bg-[#0c1220] text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition">
                    </div>
                </div>

                <!-- Verified Badge -->
                <div class="pt-4 border-t border-slate-100 dark:border-[#222f49]">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_golden_tick" value="1" class="w-4 h-4 rounded text-sky-500 focus:ring-sky-500/20" {{ old('is_golden_tick', $affiliate->is_golden_tick) ? 'checked' : '' }}>
                        <div>
                            <div class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-1">
                                Tandai Mitra Prioritas / Terverifikasi (Centang Biru)
                                <span class="material-symbols-outlined text-[16px] text-sky-500">verified</span>
                            </div>
                            <div class="text-xs text-slate-400">Mitra tepercaya yang mendapatkan prioritas kerja sama</div>
                        </div>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-[#222f49]">
                    <a href="{{ route('tenant.affiliates.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-[#222f49] text-xs md:text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-[#161f33] transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs md:text-sm font-bold shadow-lg shadow-sky-500/25 transition">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
