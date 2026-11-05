@extends("layouts.onboarding")
@section("title", "Pilih Mode Profil")

@section("content")
@php
    $modes = [
        [
            "key" => "store",
            "title" => "Toko Digital",
            "icon" => "ph-storefront",
            "summary" => "Jual source code, template, dan e-book dengan pembayaran otomatis.",
            "image" => "images/onboarding/toko-digital.jpg",
            "audience" => "Developer, desainer, kreator digital",
            "features" => ["Upload dan jual produk digital", "Pembayaran otomatis (QRIS, transfer)", "Katalog dan etalase sendiri", "Dashboard penjualan dan analitik"],
        ],
        [
            "key" => "profile",
            "title" => "Bio Link",
            "icon" => "ph-link",
            "summary" => "Satu halaman profil berisi semua link. Cocok untuk bio Instagram dan TikTok.",
            "image" => "images/onboarding/bio-link.jpg",
            "audience" => "Freelancer, influencer, brand pribadi",
            "features" => ["Halaman profil personal", "Kumpulan link sosial media", "Link WhatsApp, Instagram, dan lainnya", "Gratis selamanya"],
        ],
        [
            "key" => "hybrid",
            "title" => "Hybrid",
            "icon" => "ph-stack",
            "summary" => "Gabungan Toko Digital dan Bio Link dalam satu halaman.",
            "image" => "images/onboarding/hybrid.jpg",
            "audience" => "Semua jenis kreator dan penjual",
            "features" => ["Semua fitur Toko Digital", "Semua fitur Bio Link", "Profil dan etalase dalam satu URL"],
            "badge" => "Paling lengkap",
        ],
    ];
@endphp

<div x-data="{
    selected: null,
    preview: null,
    submitting: false,
    titles: { store: 'Toko Digital', profile: 'Bio Link', hybrid: 'Hybrid' },
    images: { store: '{{ asset($modes[0]['image']) }}', profile: '{{ asset($modes[1]['image']) }}', hybrid: '{{ asset($modes[2]['image']) }}' },
}" class="max-w-xl md:max-w-5xl mx-auto">

    <div class="max-w-xl md:mx-auto">
        @include("onboarding._progress", ["step" => 1, "total" => "selected === 'profile' ? 2 : 3"])
    </div>

    <div class="mb-5 sm:mb-8 md:text-center animate-fade-up">
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
            Mau pakai Rhantech buat apa?
        </h1>
        <p class="mt-1.5 text-slate-500 dark:text-slate-400 text-sm sm:text-base">
            Pilih satu dulu. Bisa diubah kapan saja setelah setup.
        </p>
    </div>

    <form action="{{ route("onboarding.mode") }}" method="POST" @submit="submitting = true">
        @csrf
        <input type="hidden" name="store_mode" :value="selected">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-5 md:items-start animate-fade-up-delay-1" role="radiogroup" aria-label="Tipe profil">
            @foreach($modes as $mode)
            <div role="radio" tabindex="0"
                 :aria-checked="selected === '{{ $mode['key'] }}'"
                 @click="selected = '{{ $mode['key'] }}'"
                 @keydown.enter.prevent="selected = '{{ $mode['key'] }}'"
                 @keydown.space.prevent="selected = '{{ $mode['key'] }}'"
                 :class="selected === '{{ $mode['key'] }}' ? 'border-brand ring-1 ring-brand bg-white dark:bg-[#111726]' : 'border-slate-200 dark:border-slate-700/60 bg-white dark:bg-[#111726] hover:border-slate-300'"
                 class="relative cursor-pointer select-none rounded-2xl border p-4 transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand active:scale-[0.99]">

                <div class="flex items-center gap-3">
                    <div :class="selected === '{{ $mode['key'] }}' ? 'bg-brand text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'"
                         class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 transition-colors">
                        <i class="ph {{ $mode['icon'] }} text-[24px]"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-extrabold text-slate-900 dark:text-white text-base">{{ $mode['title'] }}</span>
                            @isset($mode['badge'])
                                <span class="px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 text-[10px] font-bold whitespace-nowrap">{{ $mode['badge'] }}</span>
                            @endisset
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 leading-relaxed">{{ $mode['summary'] }}</p>
                    </div>
                    <span :class="selected === '{{ $mode['key'] }}' ? 'border-brand bg-brand text-white' : 'border-slate-300 dark:border-slate-600 text-transparent'"
                          class="w-6 h-6 rounded-full border-2 flex items-center justify-center shrink-0 transition-colors">
                        <i class="ph ph-check text-[13px]"></i>
                    </span>
                </div>

                {{-- Detail: terbuka saat dipilih di mobile, selalu terlihat di desktop --}}
                <div :class="selected === '{{ $mode['key'] }}' ? 'block' : 'hidden md:block'" class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <ul class="space-y-1.5 text-[13px] text-slate-600 dark:text-slate-300">
                        @foreach($mode['features'] as $feature)
                            <li class="flex items-start gap-2"><i class="ph ph-check-circle text-brand text-[16px] mt-px shrink-0"></i>{{ $feature }}</li>
                        @endforeach
                    </ul>
                    <p class="mt-2.5 text-xs text-slate-500 dark:text-slate-400"><span class="font-semibold">Cocok untuk:</span> {{ $mode['audience'] }}</p>
                    <button type="button" @click.stop="preview = '{{ $mode['key'] }}'"
                            class="mt-3 w-full py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 flex items-center justify-center gap-1.5 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <i class="ph ph-eye text-[16px]"></i> Lihat contoh tampilan
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CTA: menempel di bawah layar pada mobile --}}
        <div class="fixed md:static bottom-0 inset-x-0 z-30 md:mt-8 px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] md:p-0 bg-white/95 dark:bg-[#090d16]/95 md:bg-transparent backdrop-blur md:backdrop-blur-none border-t md:border-0 border-slate-200 dark:border-slate-800">
            <button type="submit" :disabled="!selected || submitting"
                    :class="selected ? 'bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-400 cursor-not-allowed'"
                    class="w-full md:w-auto md:min-w-72 md:mx-auto py-3.5 px-8 rounded-2xl font-bold text-sm transition-colors flex items-center justify-center gap-2">
                <i x-show="submitting" x-cloak class="ph ph-spinner-gap text-[18px] animate-spin"></i>
                <span x-text="selected ? 'Lanjut dengan ' + titles[selected] : 'Pilih salah satu dulu'">Pilih salah satu dulu</span>
                <i x-show="selected && !submitting" x-cloak class="ph ph-arrow-right text-[18px]"></i>
            </button>
        </div>
    </form>

    {{-- Preview: bottom sheet di mobile, modal di desktop --}}
    <div x-show="preview !== null" x-cloak x-transition.opacity
         @click="preview = null" @keydown.escape.window="preview = null"
         class="fixed inset-0 z-50 bg-black/60 flex items-end md:items-center justify-center md:p-4">
        <div @click.stop class="bg-white dark:bg-[#111726] rounded-t-3xl md:rounded-2xl p-4 pb-[max(1rem,env(safe-area-inset-bottom))] max-w-sm w-full max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-extrabold text-slate-900 dark:text-white text-base">
                    Contoh tampilan <span x-text="titles[preview]"></span>
                </h3>
                <button type="button" @click="preview = null" aria-label="Tutup" class="w-9 h-9 -mr-1 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800">
                    <i class="ph ph-x text-[20px]"></i>
                </button>
            </div>
            <img :src="preview ? images[preview] : ''" :alt="'Contoh tampilan ' + (titles[preview] || '')" class="w-full rounded-xl">
            <button type="button" @click="selected = preview; preview = null"
                    class="mt-3 w-full py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-sm">
                Pilih <span x-text="titles[preview]"></span>
            </button>
        </div>
    </div>
</div>
@endsection
