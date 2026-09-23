@extends('layouts.tenant')

@section('title', 'Edit Mitra Affiliate')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#fafafa] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] p-4 md:p-8">
    <div class="max-w-4xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('tenant.affiliates.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 flex items-center justify-center text-slate-500 hover:text-slate-800 dark:hover:text-white transition">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-black tracking-tight text-zinc-900 dark:text-zinc-100">
                    Edit Mitra Affiliate
                </h1>
                <p class="text-xs text-zinc-500 dark:text-zinc-400">
                    Perbarui profil mitra, bagi hasil komisi, atau kontak afiliator.
                </p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8">
            <form action="{{ route('tenant.affiliates.update', $affiliate->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Mitra -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            Nama Mitra / Kreator <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $affiliate->name) }}" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-50 dark:bg-[#0c1220] text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition" required>
                        @error('name') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Username / Media Sosial Handle -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            Username / Akun Medsos
                        </label>
                        <input type="text" name="handle" value="{{ old('handle', $affiliate->handle) }}" placeholder="@username_kreator" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-50 dark:bg-[#0c1220] text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    </div>

                    <!-- No WhatsApp -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            No. WhatsApp (Kontak Mitra)
                        </label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $affiliate->whatsapp) }}" placeholder="081234567890" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-50 dark:bg-[#0c1220] text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                    </div>

                    <!-- Kode Referral Unik -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            Kode Referral Unik
                        </label>
                        <input type="text" name="referral_code" value="{{ old('referral_code', $affiliate->referral_code) }}" placeholder="Contoh: RIAN20" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-50 dark:bg-[#0c1220] text-sm uppercase font-mono text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition">
                        @error('referral_code') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Persentase Komisi -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            Bagi Hasil Komisi (%) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" step="0.5" min="0" max="100" name="commission_rate" value="{{ old('commission_rate', $affiliate->commission_rate ?? 10) }}" class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-50 dark:bg-[#0c1220] text-sm font-bold text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition" required>
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm">%</span>
                        </div>
                    </div>

                    <!-- Upload Foto / Avatar -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            Ganti Foto Profil (Avatar)
                        </label>
                        <div class="flex items-center gap-3">
                            @if($affiliate->avatar_url)
                                <img src="{{ $affiliate->avatar_url }}" class="w-10 h-10 rounded-xl object-cover border border-zinc-200 dark:border-zinc-800 shrink-0">
                            @endif
                            <input type="file" name="avatar" accept="image/png,image/jpeg,image/jpg,image/webp" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 dark:file:bg-slate-800 dark:file:text-slate-200 cursor-pointer border border-zinc-200 dark:border-zinc-800 rounded-xl p-1.5 bg-slate-50 dark:bg-[#0c1220]">
                        </div>
                        @error('avatar') <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Platform Utama Promosi -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            Fokus Platform (Opsional)
                        </label>
                        @php $currentPlatform = strtolower(old('platform', $affiliate->platform)); @endphp
                        <select name="platform" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-50 dark:bg-[#0c1220] text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 transition">
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
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-2">
                            Estimasi Pengikut / Followers (Opsional)
                        </label>
                        <input type="text" name="followers_count" value="{{ old('followers_count', $affiliate->followers_count) }}" placeholder="Contoh: 15K" class="w-full px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-50 dark:bg-[#0c1220] text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 transition">
                    </div>
                </div>

                <!-- Verified Badge -->
                <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_golden_tick" value="1" class="w-4 h-4 rounded text-slate-900 focus:ring-slate-900/20 dark:text-white" {{ old('is_golden_tick', $affiliate->is_golden_tick) ? 'checked' : '' }}>
                        <div>
                            <div class="text-sm font-bold text-zinc-800 dark:text-zinc-100 flex items-center gap-1">
                                Tandai Mitra Prioritas / Terverifikasi
                                <span class="material-symbols-outlined text-[16px] text-zinc-900 dark:text-zinc-100">verified</span>
                            </div>
                            <div class="text-xs text-slate-400">Mitra tepercaya yang mendapatkan prioritas kerja sama</div>
                        </div>
                    </label>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                    <a href="{{ route('tenant.affiliates.index') }}" class="px-5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-xs md:text-sm font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-[#161f33] transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs md:text-sm font-bold transition active:scale-95 cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection
