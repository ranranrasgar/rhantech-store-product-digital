@extends('layouts.store')

@section('title', ($link['title'] ?? 'Detail Portofolio & Layanan') . ' — ' . $store->name)
@section('meta_description', Str::limit(strip_tags($link['description'] ?? $link['subtitle'] ?? $store->description), 150))

@section('content')
@php
    $color = !empty($link['color']) ? $link['color'] : '#0284c7';
    $targetUrl = !empty($link['url']) ? $link['url'] : '#';
    $btnText = !empty($link['button_text']) ? $link['button_text'] : 'Buka Tautan / Pesan';
    $isExternal = str_starts_with($targetUrl, 'http://') || str_starts_with($targetUrl, 'https://');
    $currentUrl = url()->current();
@endphp

<div class="min-h-screen bg-[#f1f5f9] dark:bg-[#0b0f19] py-4 sm:py-8 px-3 sm:px-4"
     x-data="{
         copied: false,
         copyLink() {
             if (navigator.clipboard) {
                 navigator.clipboard.writeText('{{ $currentUrl }}');
                 this.copied = true;
                 setTimeout(() => this.copied = false, 2500);
             } else if (navigator.share) {
                 navigator.share({
                     title: '{{ addslashes($link['title'] ?? '') }}',
                     url: '{{ $currentUrl }}'
                 });
             }
         }
     }">

    <div class="max-w-xl mx-auto pb-28 sm:pb-32">

        {{-- 1. TOP HEADER NAV: Tombol Kembali & Identitas Toko --}}
        <div class="flex items-center justify-between mb-4 px-1">
            <a href="{{ route('store.show', $store->slug) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white dark:bg-[#161f30] border border-slate-200/80 dark:border-[#222f49] shadow-xs text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                <span>Kembali ke Toko</span>
            </a>

            <div class="flex items-center gap-2">
                {{-- Store Identity Avatar & Name --}}
                <a href="{{ route('store.show', $store->slug) }}" class="flex items-center gap-2 group">
                    <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 shadow-xs bg-white shrink-0">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-primary text-white font-black text-xs">
                                {{ strtoupper(substr($store->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="text-left hidden xs:block">
                        <span class="text-xs font-black text-slate-900 dark:text-white group-hover:text-primary transition-colors flex items-center gap-0.5">
                            {{ $store->name }}
                            <span class="material-symbols-outlined text-sky-500 text-[14px]">verified</span>
                        </span>
                    </div>
                </a>

                {{-- Share Button --}}
                <button type="button" @click="copyLink()"
                        class="w-8 h-8 rounded-full bg-white dark:bg-[#161f30] border border-slate-200/80 dark:border-[#222f49] shadow-xs flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-primary transition-colors active:scale-95"
                        title="Bagikan Tautan">
                    <span class="material-symbols-outlined text-[17px]">share</span>
                </button>
            </div>
        </div>

        {{-- Toast Notifikasi Copy Link --}}
        <div x-show="copied" x-transition
             class="mb-3 p-2.5 rounded-xl bg-emerald-500 text-white text-xs font-bold text-center shadow-lg flex items-center justify-center gap-1.5"
             style="display: none;">
            <span class="material-symbols-outlined text-[16px]">check_circle</span>
            <span>Tautan berhasil disalin ke clipboard!</span>
        </div>

        {{-- 2. KARTU DETAIL UTAMA (Ala Lynk.id Full Showcase) --}}
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-3xl overflow-hidden shadow-lg sm:shadow-xl">
            
            {{-- BANNER / GAMBAR PORTOFOLIO --}}
            @if(!empty($link['image']))
                <div class="w-full bg-slate-100 dark:bg-slate-900 relative overflow-hidden flex items-center justify-center">
                    <img src="{{ $link['image'] }}"
                         alt="{{ $link['title'] }}"
                         class="w-full h-auto max-h-[480px] object-cover sm:object-contain bg-slate-50 dark:bg-[#0b0f19]">
                    
                    @if(!empty($link['badge']))
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-xl bg-black/70 backdrop-blur-md text-white text-[11px] font-black uppercase tracking-wider shadow-md border border-white/20">
                            {{ $link['badge'] }}
                        </span>
                    @endif
                </div>
            @else
                <div class="w-full h-40 flex items-center justify-center text-white relative" style="background: {{ $color }};">
                    <span class="material-symbols-outlined text-6xl opacity-90">{{ $link['icon'] ?? 'link' }}</span>
                    @if(!empty($link['badge']))
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-xl bg-black/40 backdrop-blur-md text-white text-[11px] font-black uppercase tracking-wider border border-white/20">
                            {{ $link['badge'] }}
                        </span>
                    @endif
                </div>
            @endif

            {{-- KONTEN TEKS LENGKAP --}}
            <div class="p-5 sm:p-7 space-y-4">
                
                {{-- Judul & Sub-judul / Harga --}}
                <div class="space-y-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
                        {{ $link['title'] }}
                    </h1>

                    @if(!empty($link['subtitle']))
                        <div class="inline-block px-3 py-1 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-sm sm:text-base font-extrabold"
                             style="color: {{ $color }};">
                            {{ $link['subtitle'] }}
                        </div>
                    @endif
                </div>

                {{-- Garis Pemisah --}}
                <div class="h-px bg-slate-100 dark:bg-slate-800/80 my-2"></div>

                {{-- Deskripsi Detail (Teks Panjang, Multiline, Poin-poin) --}}
                @if(!empty($link['description']))
                    <div class="text-sm sm:text-[15px] text-slate-700 dark:text-slate-200 leading-relaxed space-y-2.5 whitespace-pre-line font-normal">
                        {!! nl2br(e($link['description'])) !!}
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">
                        Tidak ada deskripsi tambahan. Silakan klik tombol di bawah untuk melanjutkan.
                    </p>
                @endif
            </div>

            {{-- Store Card Footer / Identitas Singkat --}}
            <div class="p-4 sm:p-5 bg-slate-50 dark:bg-[#0c1220] border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-slate-700 shadow-xs bg-white shrink-0">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-primary text-white font-black text-sm">
                                {{ strtoupper(substr($store->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white leading-tight">{{ $store->name }}</h4>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400">Official Store &amp; Portofolio</p>
                    </div>
                </div>
                <a href="{{ route('store.show', $store->slug) }}" class="text-xs font-bold text-primary hover:underline">
                    Kunjungi Toko &rarr;
                </a>
            </div>

        </div>

    </div>

    {{-- 3. STICKY BOTTOM ACTION BAR (Ala Lynk.id: WhatsApp + Share + Button Link User) --}}
    <div class="fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-[#0d1117]/95 backdrop-blur-xl border-t border-slate-200/90 dark:border-[#222f49] py-3.5 px-4 sm:px-6 shadow-2xl">
        <div class="max-w-xl mx-auto flex items-center gap-2.5 sm:gap-3">
            
            {{-- Tombol WhatsApp --}}
            @if(!empty($waLink))
                <a href="{{ $waLink }}"
                   target="_blank" rel="noopener noreferrer"
                   class="w-12 h-12 rounded-2xl bg-[#25D366]/10 border border-[#25D366]/30 text-[#25D366] flex items-center justify-center shadow-xs hover:bg-[#25D366] hover:text-white active:scale-95 transition-all shrink-0"
                   title="Konsultasi / Tanya via WhatsApp">
                    <x-store-social-icon platform="whatsapp" class="w-5 h-5 fill-current" />
                </a>
            @endif

            {{-- Tombol Salin / Bagikan Link --}}
            <button type="button" @click="copyLink()"
                    class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center shadow-xs hover:bg-slate-200 dark:hover:bg-slate-700 active:scale-95 transition-all shrink-0"
                    title="Bagikan Tautan">
                <span class="material-symbols-outlined text-[20px]" x-text="copied ? 'check' : 'share'">share</span>
            </button>

            {{-- Tombol Aksi Utama (Membuka Link yang disetting oleh User) --}}
            <a href="{{ $targetUrl }}"
               @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
               class="flex-1 h-12 px-5 rounded-2xl font-black text-sm sm:text-base text-white shadow-lg flex items-center justify-center gap-2 hover:opacity-95 hover:shadow-xl active:scale-[0.98] transition-all"
               style="background: {{ $color }};">
                <span>{{ $btnText }}</span>
                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            </a>

        </div>
    </div>

</div>
@endsection
