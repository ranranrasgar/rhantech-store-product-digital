@extends('layouts.store')

@section('title', ($link['title'] ?? 'Tautan') . ' — ' . $store->name)
@section('meta_description', Str::limit(strip_tags($link['description'] ?? $link['subtitle'] ?? $store->description), 150))
@section('canonical_url', url()->current())
@section('meta_image', !empty($link['image']) ? $link['image'] : ($store->logo ? asset('storage/'.$store->logo) : ''))

@section('content')
@php
    $color = !empty($link['color']) ? $link['color'] : '#0284c7';
    $targetUrl = !empty($link['url']) ? $link['url'] : '#';
    $rawBtnText = !empty($link['button_text']) ? $link['button_text'] : null;
    $isExternal = str_starts_with($targetUrl, 'http://') || str_starts_with($targetUrl, 'https://');
    $currentUrl = url()->current();

    // Deteksi Platform
    $lowerUrl = strtolower($targetUrl);
    $lowerTitle = strtolower($link['title'] ?? '');
    $iconName = $link['icon'] ?? 'link';

    $isWhatsApp = str_contains($lowerUrl, 'wa.me') 
        || str_contains($lowerUrl, 'whatsapp.com') 
        || $iconName === 'chat' 
        || str_contains($lowerTitle, 'whatsapp');

    $isTelegram = str_contains($lowerUrl, 't.me') 
        || str_contains($lowerUrl, 'telegram.me') 
        || $iconName === 'send' 
        || str_contains($lowerTitle, 'telegram');

    $isYouTube = str_contains($lowerUrl, 'youtube.com') 
        || str_contains($lowerUrl, 'youtu.be') 
        || $iconName === 'smart_display' 
        || str_contains($lowerTitle, 'youtube');

    $isInstagram = str_contains($lowerUrl, 'instagram.com') 
        || str_contains($lowerUrl, 'ig.me') 
        || str_contains($lowerTitle, 'instagram');

    $isGitHub = str_contains($lowerUrl, 'github.com') 
        || str_contains($lowerTitle, 'github');

    if ($isWhatsApp) {
        $platformKey = 'whatsapp';
        $defaultBtnText = 'Mulai Chat di WhatsApp';
        $brandColor = '#25D366';
    } elseif ($isTelegram) {
        $platformKey = 'telegram';
        $defaultBtnText = 'Buka di Telegram';
        $brandColor = '#229ED9';
    } elseif ($isYouTube) {
        $platformKey = 'youtube';
        $defaultBtnText = 'Buka di YouTube';
        $brandColor = '#FF0000';
    } elseif ($isInstagram) {
        $platformKey = 'instagram';
        $defaultBtnText = 'Buka di Instagram';
        $brandColor = '#E1306C';
    } elseif ($isGitHub) {
        $platformKey = 'github';
        $defaultBtnText = 'Buka di GitHub';
        $brandColor = '#24292e';
    } else {
        $platformKey = 'website';
        $defaultBtnText = 'Buka Tautan';
        $brandColor = $color;
    }

    $finalBtnText = (!empty($rawBtnText) && $rawBtnText !== 'Buka Tautan / Pesan') 
        ? $rawBtnText 
        : $defaultBtnText;

    // Format nomor WhatsApp jika ada
    $extractedPhone = null;
    $formattedPhone = null;
    if ($isWhatsApp) {
        if (preg_match('/wa\.me\/(?:\+)?([0-9]+)/i', $targetUrl, $matches)) {
            $extractedPhone = $matches[1];
        } elseif (preg_match('/phone=([0-9]+)/i', $targetUrl, $matches)) {
            $extractedPhone = $matches[1];
        } elseif (!empty($link['subtitle']) && preg_match('/(?:(?:\+|0)[0-9\s\-]+)/', $link['subtitle'], $matches)) {
            $extractedPhone = preg_replace('/[^0-9]/', '', $matches[0]);
        }

        if ($extractedPhone) {
            $raw = $extractedPhone;
            if (str_starts_with($raw, '62')) {
                $pfx = '+62 ';
                $body = substr($raw, 2);
            } elseif (str_starts_with($raw, '0')) {
                $pfx = '+62 ';
                $body = substr($raw, 1);
            } else {
                $pfx = '+';
                $body = $raw;
            }
            if (strlen($body) >= 9) {
                $formattedPhone = $pfx . substr($body, 0, 3) . '-' . substr($body, 3, 4) . '-' . substr($body, 7);
            } else {
                $formattedPhone = $pfx . $body;
            }
        }
    }
@endphp

<div class="min-h-screen bg-[#f8fafc] dark:bg-[#070b14] py-6 sm:py-12 px-3 sm:px-6"
     x-data="{
         copied: false,
         phoneCopied: false,
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
         },
         copyPhone(number) {
             if (navigator.clipboard) {
                 navigator.clipboard.writeText(number);
                 this.phoneCopied = true;
                 setTimeout(() => this.phoneCopied = false, 2500);
             }
         }
     }">

    <div class="max-w-lg mx-auto pb-12">

        {{-- TOP NAVIGATION: Tombol Kembali & Tombol Bagikan --}}
        <div class="flex items-center justify-between mb-4 px-1">
            <a href="{{ route('store.show', $store->slug) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white dark:bg-[#131b2e] border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors active:scale-95">
                <span class="material-symbols-outlined text-[17px]">arrow_back</span>
                <span>Kembali ke Toko</span>
            </a>

            <button type="button" @click="copyLink()"
                    class="w-8 h-8 rounded-full bg-white dark:bg-[#131b2e] border border-slate-200 dark:border-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:text-primary transition-colors active:scale-95 cursor-pointer"
                    title="Salin Tautan">
                <span class="material-symbols-outlined text-[17px]" x-text="copied ? 'check' : 'share'">share</span>
            </button>
        </div>

        {{-- Toast Notifikasi Copy Link --}}
        <div x-show="copied" x-transition
             class="mb-3 p-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold text-center shadow-md flex items-center justify-center gap-1.5"
             style="display: none;">
            <span class="material-symbols-outlined text-[16px] text-emerald-400">check_circle</span>
            <span>Tautan berhasil disalin ke clipboard</span>
        </div>

        {{-- KARTU UTAMA --}}
        <div class="bg-white dark:bg-[#0e1626] border border-slate-200/90 dark:border-[#1e293b] rounded-2xl overflow-hidden shadow-sm">

            {{-- 1. FOTO / BANNER JIKA ADA GAMBAR (rounded-none untuk menjaga screenshot aplikasi tidak terpotong) --}}
            @if(!empty($link['image']))
                <div class="w-full bg-slate-900 relative overflow-hidden flex items-center justify-center border-b border-slate-200 dark:border-slate-800">
                    <img src="{{ $link['image'] }}"
                         alt="{{ $link['title'] }}"
                         class="w-full h-auto max-h-[460px] object-cover sm:object-contain bg-slate-950">
                    
                    @if(!empty($link['badge']))
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-black/75 text-white text-[10px] font-black uppercase tracking-wider">
                            {{ $link['badge'] }}
                        </span>
                    @endif
                </div>
            @endif

            {{-- 2. KONTEN KARTU --}}
            <div class="p-5 sm:p-7 space-y-5">

                {{-- Header Ikon & Badge (Jika tidak ada gambar banner) --}}
                @if(empty($link['image']))
                    <div class="flex items-center justify-between">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white shadow-xs"
                             style="background: {{ $brandColor }};">
                            @if(in_array($platformKey, ['whatsapp', 'telegram', 'youtube', 'instagram', 'github']))
                                <x-store-social-icon :platform="$platformKey" class="w-7 h-7 fill-white" />
                            @else
                                <span class="material-symbols-outlined text-2xl text-white">
                                    {{ $link['icon'] ?? 'link' }}
                                </span>
                            @endif
                        </div>

                        @if(!empty($link['badge']))
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-[10px] font-bold uppercase tracking-wider">
                                {{ $link['badge'] }}
                            </span>
                        @endif
                    </div>
                @endif

                {{-- Judul & Subtitle --}}
                <div class="space-y-1.5">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white leading-tight">
                        {{ $link['title'] }}
                    </h1>

                    @if($isWhatsApp && ($formattedPhone || !empty($link['subtitle'])))
                        <div class="flex items-center gap-2 pt-1">
                            <span class="text-xs font-mono font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 px-2.5 py-1 rounded-lg">
                                {{ $formattedPhone ?? $link['subtitle'] }}
                            </span>
                            @if($extractedPhone)
                                <button type="button" @click="copyPhone('{{ $extractedPhone }}')"
                                        class="text-xs text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 underline font-semibold cursor-pointer">
                                    <span x-text="phoneCopied ? 'Tersalin!' : 'Salin'"></span>
                                </button>
                            @endif
                        </div>
                    @elseif(!empty($link['subtitle']))
                        <p class="text-xs sm:text-sm font-semibold" style="color: {{ $color }};">
                            {{ $link['subtitle'] }}
                        </p>
                    @endif
                </div>

                {{-- Deskripsi (Jika ada) --}}
                @if(!empty($link['description']))
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {!! nl2br(e($link['description'])) !!}
                    </div>
                @endif

                {{-- TOMBOL AKSI UTAMA DI DALAM KARTU --}}
                <div class="pt-2">
                    <a href="{{ $targetUrl }}"
                       @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                       class="w-full py-3.5 px-5 rounded-xl text-white font-extrabold text-sm shadow-sm hover:opacity-95 active:scale-[0.99] flex items-center justify-center gap-2 transition-all"
                       style="background: {{ $brandColor }};">
                        @if(in_array($platformKey, ['whatsapp', 'telegram', 'youtube', 'instagram', 'github']))
                            <x-store-social-icon :platform="$platformKey" class="w-4 h-4 fill-white shrink-0" />
                        @endif
                        <span>{{ $finalBtnText }}</span>
                        <span class="material-symbols-outlined text-[18px]">
                            {{ $isWhatsApp ? 'arrow_forward' : 'open_in_new' }}
                        </span>
                    </a>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
