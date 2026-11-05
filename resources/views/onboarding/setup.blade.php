@extends("layouts.onboarding")
@section("title", "Setup Profil Toko")

@section("content")
@php
    $isProfile = $mode === "profile";
    $totalSteps = $isProfile ? 2 : 3;
    $pendingLogo = session("onboarding_logo");
@endphp

<div x-data="{
    name: @js(old('name', '')),
    slug: @js(old('slug', '')),
    description: @js(old('description', '')),
    slugEdited: @js(filled(old('slug'))),
    logoPreview: @js($pendingLogo ? asset('storage/' . $pendingLogo) : null),
    logoError: '',
    slugStatus: 'idle',
    slugMessage: @js($errors->first('slug')),
    slugSuggestion: '',
    slugTimer: null,
    slugRequest: 0,
    submitting: false,
    init() {
        if (this.slug) this.checkSlug();
    },
    slugify(str) {
        return str.toLowerCase().replace(/[^a-z0-9\s\-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').substring(0, 50);
    },
    onNameInput() {
        if (!this.slugEdited) {
            this.slug = this.slugify(this.name).replace(/^-|-$/g, '');
            this.queueCheck();
        }
    },
    onSlugInput() {
        this.slugEdited = true;
        this.slug = this.slugify(this.slug);
        this.queueCheck();
    },
    queueCheck() {
        clearTimeout(this.slugTimer);
        this.slugSuggestion = '';
        if (!this.slug) {
            this.slugStatus = 'idle';
            this.slugMessage = '';
            return;
        }
        this.slugStatus = 'checking';
        this.slugTimer = setTimeout(() => this.checkSlug(), 400);
    },
    async checkSlug() {
        const request = ++this.slugRequest;
        this.slugStatus = 'checking';
        try {
            const response = await fetch(@js(route('onboarding.check_slug')) + '?slug=' + encodeURIComponent(this.slug), { headers: { Accept: 'application/json' } });
            const data = await response.json();
            if (request !== this.slugRequest) return;
            this.slugStatus = data.available ? 'available' : 'taken';
            this.slugMessage = data.message;
            this.slugSuggestion = data.suggestion || '';
        } catch (error) {
            if (request === this.slugRequest) {
                this.slugStatus = 'idle';
                this.slugMessage = '';
            }
        }
    },
    useSuggestion() {
        this.slug = this.slugSuggestion;
        this.slugEdited = true;
        this.queueCheck();
    },
    onLogoChange(event) {
        const file = event.target.files[0];
        this.logoError = '';
        if (!file) return;
        if (file.size > 2 * 1024 * 1024) {
            this.logoError = 'Ukuran gambar maksimal 2 MB.';
            event.target.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (loaded) => { this.logoPreview = loaded.target.result; };
        reader.readAsDataURL(file);
    },
    get canSubmit() {
        return this.name.trim() !== '' && this.slugStatus !== 'taken' && !this.submitting;
    },
}" class="max-w-xl mx-auto">

    @include("onboarding._progress", ["step" => 2, "total" => $totalSteps])

    <div class="mb-5 animate-fade-up">
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Atur profil kamu</h1>
        <p class="mt-1.5 text-slate-500 dark:text-slate-400 text-sm">Lihat hasilnya langsung di bawah sambil mengisi.</p>
    </div>

    <form action="{{ route("onboarding.store") }}" method="POST" enctype="multipart/form-data" @submit="submitting = true" class="animate-fade-up-delay-1">
        @csrf

        {{-- Pratinjau langsung sekaligus tempat memilih foto --}}
        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700/60 bg-white dark:bg-[#111726] mb-4">
            <div class="h-16 bg-gradient-to-r from-brand to-slate-800"></div>
            <div class="px-4 pb-4 -mt-8">
                <label for="logo" class="relative block w-20 h-20 cursor-pointer" aria-label="Pilih foto profil">
                    <span class="block w-20 h-20 rounded-2xl border-4 border-white dark:border-[#111726] bg-slate-100 dark:bg-slate-800 overflow-hidden">
                        <img x-show="logoPreview" x-cloak :src="logoPreview" alt="Pratinjau foto profil" class="w-full h-full object-cover">
                        <span x-show="!logoPreview" class="w-full h-full flex items-center justify-center text-slate-400">
                            <i class="ph ph-image text-[30px]"></i>
                        </span>
                    </span>
                    <span class="absolute -bottom-0.5 -right-0.5 w-7 h-7 rounded-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 flex items-center justify-center border-2 border-white dark:border-[#111726]">
                        <i class="ph ph-camera text-[14px]"></i>
                    </span>
                </label>
                <input id="logo" type="file" name="logo" class="sr-only" accept="image/*" @change="onLogoChange($event)">

                <p class="mt-2 font-extrabold text-slate-900 dark:text-white text-lg leading-tight break-words"
                   x-text="name.trim() || @js($isProfile ? 'Nama kamu' : 'Nama tokomu')"></p>
                <p class="text-xs font-mono text-slate-500 dark:text-slate-400 break-all">rhantech.com/<span x-text="slug || 'nama-kamu'"></span></p>
                <p x-show="description.trim()" x-cloak class="mt-1.5 text-sm text-slate-600 dark:text-slate-300 break-words" x-text="description"></p>
            </div>
        </div>

        <p x-show="logoError" x-cloak class="mb-3 text-xs font-semibold text-rose-600" x-text="logoError"></p>
        @error("logo")
            <p x-show="!logoError" class="mb-3 text-xs font-semibold text-rose-600">{{ $message }}</p>
        @enderror

        <div class="bg-white dark:bg-[#111726] rounded-2xl border border-slate-200 dark:border-slate-700/60 p-4 sm:p-6 space-y-5">
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Nama {{ $isProfile ? "profil atau brand" : "toko" }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" required autocomplete="organization" maxlength="255"
                       x-model="name" @input="onNameInput()"
                       placeholder="{{ $isProfile ? "Contoh: Budi Kreator" : "Contoh: Toko Source Code" }}"
                       class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand focus:ring-brand text-base sm:text-sm">
                @error("name")
                    <p class="mt-1.5 text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    URL profil <span class="text-rose-500">*</span>
                </label>
                <div :class="slugStatus === 'taken' ? 'border-rose-400 ring-1 ring-rose-400' : (slugStatus === 'available' ? 'border-emerald-500' : 'border-slate-200 dark:border-slate-700')"
                     class="flex items-center rounded-xl border overflow-hidden bg-slate-50 dark:bg-slate-800/50 focus-within:ring-1 focus-within:ring-brand transition-colors">
                    <span class="pl-3 text-sm font-mono text-slate-400 whitespace-nowrap shrink-0">rhantech.com/</span>
                    <input type="text" id="slug" name="slug" required autocapitalize="none" autocorrect="off" spellcheck="false" maxlength="50"
                           x-model="slug" @input="onSlugInput()"
                           placeholder="nama-kamu"
                           class="flex-1 min-w-0 pl-0.5 pr-1 py-3 border-0 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:ring-0 text-base sm:text-sm font-mono font-bold">
                    <span class="pr-3 shrink-0" aria-hidden="true">
                        <i x-show="slugStatus === 'checking'" x-cloak class="ph ph-spinner-gap text-[18px] text-slate-400 animate-spin"></i>
                        <i x-show="slugStatus === 'available'" x-cloak class="ph ph-check-circle text-[20px] text-emerald-600"></i>
                        <i x-show="slugStatus === 'taken'" x-cloak class="ph ph-warning-circle text-[20px] text-rose-500"></i>
                    </span>
                </div>
                <div class="mt-1.5 min-h-[18px] text-xs" aria-live="polite">
                    <p x-show="slugStatus === 'available'" x-cloak class="font-semibold text-emerald-700 dark:text-emerald-400" x-text="slugMessage"></p>
                    <p x-show="slugStatus === 'checking'" x-cloak class="text-slate-400">Mengecek ketersediaan...</p>
                    <p x-show="slugStatus === 'idle' && slugMessage" x-cloak class="font-semibold text-rose-600" x-text="slugMessage"></p>
                    <p x-show="slugStatus === 'idle' && !slugMessage" class="text-slate-400">Huruf kecil, angka, dan tanda hubung.</p>
                    <div x-show="slugStatus === 'taken'" x-cloak class="flex flex-wrap items-center gap-x-2 gap-y-1">
                        <span class="font-semibold text-rose-600" x-text="slugMessage"></span>
                        <button type="button" x-show="slugSuggestion" @click="useSuggestion()" class="font-bold text-brand underline underline-offset-2">
                            Pakai <span x-text="slugSuggestion"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Tagline <span class="text-slate-400 font-normal">(opsional)</span>
                </label>
                <textarea id="description" name="description" rows="2" maxlength="160" x-model="description"
                          placeholder="{{ $isProfile ? "Contoh: Kreator konten dan freelancer UI/UX" : "Contoh: Source code berkualitas, harga terjangkau" }}"
                          class="w-full px-4 py-3 rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand focus:ring-brand text-base sm:text-sm resize-none"></textarea>
                <p class="mt-1 text-right text-[11px] text-slate-400"><span x-text="description.length"></span>/160</p>
            </div>
        </div>

        <div class="mt-3 text-center hidden md:block">
            <a href="{{ route("onboarding.index") }}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-slate-700 dark:hover:text-slate-200 py-2">
                <i class="ph ph-arrow-left text-[14px]"></i> Kembali pilih tipe profil
            </a>
        </div>

        {{-- CTA: menempel di bawah layar pada mobile --}}
        <div class="sticky-cta fixed md:static bottom-0 inset-x-0 z-30 md:mt-3 px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] md:p-0 bg-white/95 dark:bg-[#090d16]/95 md:bg-transparent backdrop-blur md:backdrop-blur-none border-t md:border-0 border-slate-200 dark:border-slate-800 flex gap-2">
            <a href="{{ route("onboarding.index") }}" aria-label="Kembali pilih tipe profil"
               class="md:hidden w-12 shrink-0 rounded-2xl border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300">
                <i class="ph ph-arrow-left text-[20px]"></i>
            </a>
            <button type="submit" :disabled="!canSubmit"
                    :class="canSubmit ? 'bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 cursor-not-allowed'"
                    class="flex-1 py-3.5 px-6 rounded-2xl font-bold text-sm transition-colors flex items-center justify-center gap-2">
                <i x-show="submitting" x-cloak class="ph ph-spinner-gap text-[18px] animate-spin"></i>
                <span x-show="submitting" x-cloak>Menyimpan...</span>
                <span x-show="!submitting">{{ $isProfile ? "Selesai, atur tautan" : "Lanjut tambah produk" }}</span>
                <i x-show="!submitting" class="ph ph-arrow-right text-[18px]"></i>
            </button>
        </div>
    </form>
</div>
@endsection
