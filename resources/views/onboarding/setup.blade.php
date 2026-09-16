@extends("layouts.onboarding")
@section("title", "Setup Profil Toko")

@section("content")
@php
    $modeLabels = ["store" => "Toko Digital", "profile" => "Bio Link", "hybrid" => "Hybrid"];
    $modeColors = ["store" => "teal", "profile" => "purple", "hybrid" => "amber"];
    $modeIcons  = ["store" => "storefront", "profile" => "link", "hybrid" => "auto_awesome"];
    $modeStep   = $mode === "profile" ? "2 dari 2" : "2 dari 3";
@endphp

<div x-data="{
    name: '',
    slug: '',
    slugEdited: false,
    logoPreview: null,
    slugify(str) {
        return str.toLowerCase().replace(/[^a-z0-9\s\-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').substring(0,50);
    },
    onNameInput(val) {
        this.name = val;
        if (!this.slugEdited) {
            this.slug = this.slugify(val);
        }
    },
    onSlugInput(val) {
        this.slugEdited = true;
        this.slug = this.slugify(val);
    },
    onLogoChange(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => { this.logoPreview = ev.target.result; };
            reader.readAsDataURL(file);
        }
    }
}" class="max-w-xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-8 animate-fade-up">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold mb-4 border"
             :class="{
                'bg-teal-500/10 text-teal-700 dark:text-teal-300 border-teal-500/20': '{{ $mode }}' === 'store',
                'bg-purple-500/10 text-purple-700 dark:text-purple-300 border-purple-500/20': '{{ $mode }}' === 'profile',
                'bg-amber-500/10 text-amber-700 dark:text-amber-300 border-amber-500/20': '{{ $mode }}' === 'hybrid'
             }">
            <span class="material-symbols-outlined text-[14px]">{{ $modeIcons[$mode] }}</span>
            Langkah {{ $modeStep }} — Setup {{ $modeLabels[$mode] }}
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
            Atur profil kamu 🎨
        </h1>
        <p class="mt-2 text-slate-500 dark:text-slate-400 text-sm">
            Bisa diubah lagi kapan saja dari pengaturan.
        </p>
    </div>

    {{-- Form --}}
    <div class="bg-white dark:bg-[#111726] rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-6 sm:p-8 shadow-sm animate-fade-up-delay-1">
        <form action="{{ route("onboarding.store") }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @if ($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 text-sm">
                <ul class="space-y-1">@foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Logo Upload --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-3">
                    Logo / Foto Profil
                    <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                </label>
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded-2xl bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-300 dark:border-slate-600 overflow-hidden flex items-center justify-center shrink-0 relative">
                        <template x-if="logoPreview">
                            <img :src="logoPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!logoPreview">
                            <span class="material-symbols-outlined text-slate-400 text-[28px]">add_photo_alternate</span>
                        </template>
                    </div>
                    <div class="flex-1">
                        <label for="logo" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-sm font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">upload</span>
                            Pilih Gambar
                        </label>
                        <input id="logo" type="file" name="logo" class="hidden" accept="image/*" @change="onLogoChange($event)">
                        <p class="mt-1.5 text-xs text-slate-400">JPG, PNG, WebP. Maks 2MB. Disarankan 1:1 (kotak).</p>
                    </div>
                </div>
            </div>

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Nama {{ $mode === "profile" ? "Profil / Brand" : "Toko" }}
                    <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name"
                       :value="name"
                       @input="onNameInput($event.target.value)"
                       placeholder="{{ $mode === "profile" ? "Contoh: Budi Kreator, Studio Digital" : "Contoh: Toko Source Code, Template Pro" }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00838f]/40 focus:border-[#00838f] transition text-sm font-medium"
                       value="{{ old("name") }}" required>
            </div>

            {{-- Slug / URL --}}
            <div>
                <label for="slug" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    URL Profil Publik
                    <span class="text-rose-500">*</span>
                </label>
                <div class="flex items-center gap-0 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden focus-within:ring-2 focus-within:ring-[#00838f]/40 focus-within:border-[#00838f] bg-slate-50 dark:bg-slate-800/50">
                    <span class="px-3 py-3 text-xs font-mono text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 whitespace-nowrap shrink-0">
                        rhantech.com/
                    </span>
                    <input type="text" id="slug" name="slug"
                           :value="slug"
                           @input="onSlugInput($event.target.value)"
                           placeholder="nama-tokomu"
                           class="flex-1 px-3 py-3 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none text-sm font-mono font-bold min-w-0"
                           value="{{ old("slug") }}">
                </div>
                <p class="mt-1.5 text-xs text-slate-400 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px] text-[#00838f]">info</span>
                    Ini adalah URL publik toko/profilmu. Hanya huruf kecil, angka, dan tanda hubung (-).
                </p>
            </div>

            {{-- Deskripsi singkat --}}
            <div>
                <label for="description" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Tagline / Deskripsi Singkat
                    <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                </label>
                <textarea id="description" name="description" rows="2"
                          placeholder="{{ $mode === "profile" ? "Contoh: Kreator konten digital & freelancer UI/UX" : "Contoh: Toko source code terpercaya, kualitas premium harga terjangkau" }}"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00838f]/40 focus:border-[#00838f] transition text-sm resize-none">{{ old("description") }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3.5 px-6 rounded-2xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 text-white font-bold text-sm transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 active:scale-[0.98]">
                    @if($mode === "profile")
                        <span>Selesai, Lihat Profilku!</span>
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    @else
                        <span>Lanjut Tambah Produk</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    @endif
                </button>
            </div>
        </form>
    </div>

    {{-- Back --}}
    <div class="mt-4 text-center">
        <a href="{{ route("onboarding.index") }}" class="text-xs text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 flex items-center justify-center gap-1 transition-colors">
            <span class="material-symbols-outlined text-[14px]">arrow_back</span>
            Kembali pilih tipe profil
        </a>
    </div>
</div>
@endsection
