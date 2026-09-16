@extends("layouts.onboarding")
@section("title", "Pilih Mode Profil")

@section("content")
<div x-data="{
    selected: null,
    preview: null,
    selectMode(mode) {
        this.selected = mode;
    },
    openPreview(mode) {
        this.preview = mode;
    }
}" class="max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-10 animate-fade-up">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-500/10 text-[#00838f] text-xs font-bold mb-4 border border-teal-500/20">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-500 animate-pulse"></span>
            Langkah 1 dari 3 — Pilih Tipe Profil
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
            Mau pakai Rhantech buat apa? 🚀
        </h1>
        <p class="mt-3 text-slate-500 dark:text-slate-400 text-sm sm:text-base max-w-xl mx-auto">
            Pilih tipe profil yang sesuai tujuanmu. Kamu bisa ubah lagi kapan saja setelah setup.
        </p>
    </div>

    {{-- Mode Cards --}}
    <form action="{{ route("onboarding.mode") }}" method="POST">
        @csrf
        <input type="hidden" name="store_mode" :value="selected" id="store_mode_input">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 animate-fade-up-delay-1">

            {{-- Card 1: Toko Digital --}}
            <div @click="selectMode('store')"
                 :class="selected === 'store' ? 'ring-2 ring-[#00838f] border-[#00838f] dark:border-teal-400 bg-teal-50/50 dark:bg-teal-950/20' : 'border-slate-200 dark:border-slate-700/60 hover:border-teal-400/60 dark:hover:border-teal-500/40 hover:shadow-lg'"
                 class="relative cursor-pointer rounded-2xl border bg-white dark:bg-[#111726] p-5 transition-all duration-200 group select-none">

                {{-- Selected badge --}}
                <div x-show="selected === 'store'" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-[#00838f] flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined text-white text-[14px]">check</span>
                </div>

                {{-- Preview image --}}
                <div class="relative rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-800" style="aspect-ratio: 9/16; max-height: 220px;">
                    <img src="{{ asset("images/onboarding/toko-digital.jpg") }}" alt="Preview Toko Digital"
                         class="w-full h-full object-cover object-top transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <button type="button" @click.stop="openPreview('store')"
                            class="absolute bottom-2 right-2 px-2 py-1 rounded-lg bg-white/90 dark:bg-slate-800/90 text-[10px] font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1 hover:bg-white transition-all">
                        <span class="material-symbols-outlined text-[13px]">zoom_in</span>
                        Lihat Contoh
                    </button>
                </div>

                {{-- Icon + Label --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-500/10 text-[#00838f] dark:text-teal-400 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[22px]">storefront</span>
                    </div>
                    <div>
                        <div class="font-black text-slate-900 dark:text-white text-base">Toko Digital</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Jual source code, template, e-book, atau produk digital lainnya. Terima pembayaran otomatis!
                        </p>
                    </div>
                </div>

                {{-- Fitur list --}}
                <ul class="mt-3 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-teal-500 text-[14px]">check_circle</span>Upload & jual produk digital</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-teal-500 text-[14px]">check_circle</span>Pembayaran otomatis (QRIS, Transfer)</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-teal-500 text-[14px]">check_circle</span>Katalog & etalase toko sendiri</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-teal-500 text-[14px]">check_circle</span>Dashboard penjualan & analitik</li>
                </ul>

                <div class="mt-3 px-3 py-1.5 rounded-lg bg-teal-500/10 text-[10px] font-bold text-[#00838f] dark:text-teal-400 text-center">
                    💡 Cocok untuk: Developer, Desainer, Kreator Digital
                </div>
            </div>

            {{-- Card 2: Bio Link --}}
            <div @click="selectMode('profile')"
                 :class="selected === 'profile' ? 'ring-2 ring-purple-500 border-purple-500 bg-purple-50/50 dark:bg-purple-950/20' : 'border-slate-200 dark:border-slate-700/60 hover:border-purple-400/60 dark:hover:border-purple-500/40 hover:shadow-lg'"
                 class="relative cursor-pointer rounded-2xl border bg-white dark:bg-[#111726] p-5 transition-all duration-200 group select-none">

                <div x-show="selected === 'profile'" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-purple-500 flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined text-white text-[14px]">check</span>
                </div>

                <div class="relative rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-800" style="aspect-ratio: 9/16; max-height: 220px;">
                    <img src="{{ asset("images/onboarding/bio-link.jpg") }}" alt="Preview Bio Link"
                         class="w-full h-full object-cover object-top transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <button type="button" @click.stop="openPreview('profile')"
                            class="absolute bottom-2 right-2 px-2 py-1 rounded-lg bg-white/90 dark:bg-slate-800/90 text-[10px] font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1 hover:bg-white transition-all">
                        <span class="material-symbols-outlined text-[13px]">zoom_in</span>
                        Lihat Contoh
                    </button>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[22px]">link</span>
                    </div>
                    <div>
                        <div class="font-black text-slate-900 dark:text-white text-base">Bio Link</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Buat halaman profil dengan kumpulan link. Cocok dipasang di bio Instagram & TikTok.
                        </p>
                    </div>
                </div>

                <ul class="mt-3 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-purple-500 text-[14px]">check_circle</span>Halaman profil personal</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-purple-500 text-[14px]">check_circle</span>Kumpulan link sosial media</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-purple-500 text-[14px]">check_circle</span>Link WhatsApp, Instagram, dll.</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-purple-500 text-[14px]">check_circle</span>Gratis selamanya</li>
                </ul>

                <div class="mt-3 px-3 py-1.5 rounded-lg bg-purple-500/10 text-[10px] font-bold text-purple-600 dark:text-purple-400 text-center">
                    💡 Cocok untuk: Freelancer, Influencer, Brand Pribadi
                </div>
            </div>

            {{-- Card 3: Hybrid --}}
            <div @click="selectMode('hybrid')"
                 :class="selected === 'hybrid' ? 'ring-2 ring-amber-500 border-amber-500 bg-amber-50/50 dark:bg-amber-950/20' : 'border-slate-200 dark:border-slate-700/60 hover:border-amber-400/60 dark:hover:border-amber-500/40 hover:shadow-lg'"
                 class="relative cursor-pointer rounded-2xl border bg-white dark:bg-[#111726] p-5 transition-all duration-200 group select-none">

                <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 px-2.5 py-0.5 rounded-full bg-amber-500 text-white text-[9px] font-black uppercase tracking-wide whitespace-nowrap shadow-md">
                    ⚡ Paling Lengkap
                </div>
                <div x-show="selected === 'hybrid'" class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-amber-500 flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined text-white text-[14px]">check</span>
                </div>

                <div class="relative rounded-xl overflow-hidden mb-4 bg-slate-100 dark:bg-slate-800" style="aspect-ratio: 9/16; max-height: 220px;">
                    <img src="{{ asset("images/onboarding/hybrid.jpg") }}" alt="Preview Hybrid"
                         class="w-full h-full object-cover object-top transition-transform duration-300 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <button type="button" @click.stop="openPreview('hybrid')"
                            class="absolute bottom-2 right-2 px-2 py-1 rounded-lg bg-white/90 dark:bg-slate-800/90 text-[10px] font-bold text-slate-700 dark:text-slate-200 flex items-center gap-1 hover:bg-white transition-all">
                        <span class="material-symbols-outlined text-[13px]">zoom_in</span>
                        Lihat Contoh
                    </button>
                </div>

                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-[22px]">auto_awesome</span>
                    </div>
                    <div>
                        <div class="font-black text-slate-900 dark:text-white text-base">Hybrid</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">
                            Gabungan Toko Digital + Bio Link dalam satu halaman. Maksimalkan semua potensi!
                        </p>
                    </div>
                </div>

                <ul class="mt-3 space-y-1.5 text-xs text-slate-600 dark:text-slate-300">
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500 text-[14px]">check_circle</span>Semua fitur Toko Digital</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500 text-[14px]">check_circle</span>Semua fitur Bio Link</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500 text-[14px]">check_circle</span>Profil + Etalase dalam satu URL</li>
                    <li class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500 text-[14px]">check_circle</span>Konversi lebih tinggi</li>
                </ul>

                <div class="mt-3 px-3 py-1.5 rounded-lg bg-amber-500/10 text-[10px] font-bold text-amber-600 dark:text-amber-400 text-center">
                    💡 Cocok untuk: Semua jenis kreator & penjual
                </div>
            </div>
        </div>

        {{-- CTA Button --}}
        <div class="mt-8 flex flex-col items-center gap-3 animate-fade-up-delay-2">
            <button type="submit"
                    :disabled="!selected"
                    :class="selected ? 'bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 cursor-pointer shadow-lg hover:shadow-xl scale-100 hover:scale-[1.02]' : 'bg-slate-300 dark:bg-slate-700 text-slate-400 dark:text-slate-500 cursor-not-allowed'"
                    class="px-10 py-3.5 rounded-2xl font-bold text-white text-sm transition-all duration-200 flex items-center gap-2">
                <template x-if="selected">
                    <span>Lanjut Setup Profil</span>
                </template>
                <template x-if="!selected">
                    <span>Pilih salah satu dulu</span>
                </template>
                <span class="material-symbols-outlined text-[18px]" x-show="selected">arrow_forward</span>
            </button>
            <p class="text-xs text-slate-400">Bisa diubah kapan saja di pengaturan toko</p>
        </div>
    </form>

    {{-- Preview Modal --}}
    <div x-show="preview !== null" x-cloak
         @click="preview = null"
         style="display:none"
         class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4">
        <div @click.stop class="bg-white dark:bg-[#111726] rounded-2xl p-4 max-w-sm w-full shadow-2xl relative">
            <button @click="preview = null" class="absolute top-3 right-3 text-slate-400 hover:text-slate-700 dark:hover:text-white">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
            <h3 class="font-black text-slate-900 dark:text-white text-base mb-3">
                <template x-if="preview === 'store'">Contoh Tampilan — Toko Digital</template>
                <template x-if="preview === 'profile'">Contoh Tampilan — Bio Link</template>
                <template x-if="preview === 'hybrid'">Contoh Tampilan — Hybrid</template>
            </h3>
            <div class="rounded-xl overflow-hidden">
                <template x-if="preview === 'store'">
                    <img src="{{ asset("images/onboarding/toko-digital.jpg") }}" alt="Preview Toko" class="w-full rounded-xl">
                </template>
                <template x-if="preview === 'profile'">
                    <img src="{{ asset("images/onboarding/bio-link.jpg") }}" alt="Preview Bio Link" class="w-full rounded-xl">
                </template>
                <template x-if="preview === 'hybrid'">
                    <img src="{{ asset("images/onboarding/hybrid.jpg") }}" alt="Preview Hybrid" class="w-full rounded-xl">
                </template>
            </div>
            <p class="mt-3 text-xs text-slate-400 text-center">Ini adalah contoh tampilan publik profilmu nantinya</p>
        </div>
    </div>
</div>
@endsection
