@extends('layouts.tenant')

@section('title', 'Dashboard Toko')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header Hero & Quick Info -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#0f172a] via-[#1e293b] to-[#0284c7] dark:from-[#0b1329] dark:via-[#111c38] dark:to-[#0369a1] text-white p-6 md:p-8 shadow-xl border border-white/10">
            <!-- Background Glow Effects -->
            <div class="absolute -top-24 -right-24 w-72 h-72 bg-sky-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl p-1 bg-white/10 backdrop-blur-md border border-white/20 shadow-inner overflow-hidden shrink-0">
                        @if($store && $store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full bg-gradient-to-tr from-sky-500 to-indigo-600 rounded-xl flex items-center justify-center font-black text-2xl text-white">
                                {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-sky-200 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Merchant Partner
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2">
                            {{ $store->name ?? 'Toko Saya' }}
                        </h1>
                        <p class="text-xs md:text-sm text-slate-300 mt-1 max-w-xl line-clamp-1">
                            {{ $store->description ?: 'Kelola produk digital, pantau penjualan, dan tingkatkan penghasilan Anda.' }}
                        </p>
                    </div>
                </div>

                <!-- Action Hub Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    @if($store && $store->slug)
                    <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white text-xs md:text-sm font-semibold transition-all duration-200 flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">storefront</span>
                        Lihat Toko Publik
                    </a>
                    @endif
                    <a href="{{ route('tenant.products.create') }}" class="px-5 py-2.5 rounded-xl bg-sky-500 hover:bg-sky-400 text-white text-xs md:text-sm font-bold shadow-lg shadow-sky-500/30 hover:shadow-sky-500/50 transition-all duration-200 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        @if($store)
        @php
            $storeSlug = $store->slug ?: 'toko-' . $store->id;
            // Tautan resmi langsung sesuai setting Tautan URL / Slug Toko di dashboard/store
            $storeDirectUrl = url('/' . $storeSlug);
            $storeTokoUrl = route('store.show', $storeSlug);
            $encodedUrl = urlencode($storeDirectUrl);
            $storeTitle = $store->name;
            $shareMessage = "Kunjungi toko digital resmi {$storeTitle} di Rhantech untuk melihat berbagai produk digital, source code, dan template terbaik: {$storeDirectUrl}";
            $encodedMsg = urlencode($shareMessage);
        @endphp

        <!-- Modul Promosi & Bagikan Tautan Toko -->
        <div x-data="{
            copied: false,
            showQrModal: false,
            storeUrl: '{{ $storeDirectUrl }}',
            copyToClipboard() {
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(this.storeUrl).then(() => {
                        this.triggerCopied();
                    }).catch(() => {
                        this.fallbackCopy();
                    });
                } else {
                    this.fallbackCopy();
                }
            },
            fallbackCopy() {
                const input = document.getElementById('store-link-input');
                if (input) {
                    input.select();
                    document.execCommand('copy');
                    this.triggerCopied();
                }
            },
            triggerCopied() {
                this.copied = true;
                setTimeout(() => { this.copied = false; }, 2500);
            },
            shareNative() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ addslashes($storeTitle) }}',
                        text: '{{ addslashes($shareMessage) }}',
                        url: this.storeUrl
                    }).catch(() => {});
                } else {
                    this.copyToClipboard();
                }
            }
        }" class="bg-gradient-to-br from-white via-sky-50/40 to-white dark:from-[#111726] dark:via-[#131f38] dark:to-[#111726] border-2 border-sky-500/20 dark:border-sky-500/30 rounded-3xl p-5 md:p-7 shadow-lg relative overflow-hidden">

            <!-- Ambient decorative glow -->
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-gradient-to-br from-sky-400/20 to-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 space-y-5">
                
                <!-- Header: Title & Badges -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 md:w-11 md:h-11 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center shadow-md shadow-sky-500/25 shrink-0">
                            <span class="material-symbols-outlined text-[22px] md:text-[24px]">share</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-base md:text-lg font-black text-slate-900 dark:text-white tracking-tight">
                                    Promosikan & Bagikan Toko Anda
                                </h2>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-gradient-to-r from-pink-500/10 to-rose-500/10 text-pink-600 dark:text-pink-400 border border-pink-500/20">
                                    <span>✨</span> Siap Dipakai di Bio TikTok & Instagram
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                Tautan resmi toko berdasarkan slug di pengaturan toko. Bagikan ke medsos atau jadikan bio profil untuk menjaring calon pembeli.
                            </p>
                        </div>
                    </div>

                    <!-- Quick Action: Ganti Slug Toko -->
                    <a href="{{ route('tenant.store.index') }}" 
                       class="self-start sm:self-center inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-white dark:bg-[#0c1220] border border-slate-200 dark:border-[#222f49] hover:border-sky-500 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-sky-600 dark:hover:text-sky-400 transition-all shadow-xs"
                       title="Ubah URL / Slug Toko di Pengaturan">
                        <span class="material-symbols-outlined text-[16px] text-sky-500">settings_suggest</span>
                        <span>Atur Slug Toko</span>
                    </a>
                </div>

                <!-- Main URL Box & Copy Actions -->
                <div class="bg-white dark:bg-[#0c1220] p-2.5 sm:p-3 rounded-2xl border border-slate-200/80 dark:border-[#222f49] shadow-inner flex flex-col md:flex-row items-stretch md:items-center gap-2.5">
                    <div class="flex items-center gap-2.5 flex-1 min-w-0 px-2 py-1">
                        <span class="material-symbols-outlined text-[20px] text-sky-500 shrink-0">link</span>
                        <input type="text" id="store-link-input" readonly :value="storeUrl" 
                               @click="copyToClipboard()"
                               class="w-full bg-transparent border-none p-0 text-xs md:text-sm font-mono font-bold text-slate-900 dark:text-white focus:outline-none select-all cursor-pointer truncate"
                               title="Klik untuk salin tautan">
                    </div>

                    <!-- Action Buttons: Copy, Open, QR -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Copy Button -->
                        <button type="button" @click="copyToClipboard()"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl font-black text-xs transition-all duration-200 shadow-md cursor-pointer"
                                :class="copied ? 'bg-emerald-500 text-white shadow-emerald-500/30' : 'bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-sky-500/25'">
                            <span class="material-symbols-outlined text-[18px]" x-text="copied ? 'check_circle' : 'content_copy'"></span>
                            <span x-text="copied ? 'Tersalin! 🎉' : 'Salin Tautan'"></span>
                        </button>

                        <!-- Open Store Button -->
                        <a :href="storeUrl" target="_blank"
                           class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors shrink-0"
                           title="Buka Toko di Tab Baru">
                            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        </a>

                        <!-- QR Code Button -->
                        <button type="button" @click="showQrModal = true"
                                class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors shrink-0"
                                title="Lihat & Download QR Code Toko">
                            <span class="material-symbols-outlined text-[18px]">qr_code_2</span>
                        </button>
                    </div>
                </div>

                <!-- Social Media Share Buttons & Platform Suggestions -->
                <div class="pt-1 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-bold text-slate-500 dark:text-slate-400 text-[11px] uppercase tracking-wider mr-1">Bagikan Langsung:</span>

                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ $encodedMsg }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#128C7E] dark:text-[#25D366] font-bold text-xs border border-[#25D366]/30 transition-all hover:scale-105"
                           title="Bagikan ke WhatsApp Chat / Status">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>WhatsApp</span>
                        </a>

                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedMsg }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#229ED9]/10 hover:bg-[#229ED9]/20 text-[#229ED9] font-bold text-xs border border-[#229ED9]/30 transition-all hover:scale-105"
                           title="Bagikan ke Telegram">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.196 1.006.128.832.942z"/></svg>
                            <span>Telegram</span>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#1877F2]/10 hover:bg-[#1877F2]/20 text-[#1877F2] font-bold text-xs border border-[#1877F2]/30 transition-all hover:scale-105"
                           title="Bagikan ke Facebook">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                            <span>Facebook</span>
                        </a>

                        <!-- X (Twitter) -->
                        <a href="https://twitter.com/intent/tweet?text={{ $encodedMsg }}&url={{ $encodedUrl }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900/10 dark:bg-white/10 hover:bg-slate-900/20 text-slate-900 dark:text-white font-bold text-xs border border-slate-300 dark:border-slate-700 transition-all hover:scale-105"
                           title="Bagikan ke Twitter / X">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span>X</span>
                        </a>

                        <!-- Mobile Native Share Sheet -->
                        <button type="button" @click="shareNative()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-purple-500/10 to-indigo-500/10 hover:from-purple-500/20 hover:to-indigo-500/20 text-purple-600 dark:text-purple-400 font-bold text-xs border border-purple-500/30 transition-all hover:scale-105 cursor-pointer"
                                title="Bagikan via Aplikasi Lain di HP">
                            <span class="material-symbols-outlined text-[15px]">send_to_mobile</span>
                            <span>Lainnya</span>
                        </button>
                    </div>

                    <!-- Guidance Note -->
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-sky-500">info</span>
                        <span>Tautan otomatis mengarahkan pengunjung ke etalase tokomu.</span>
                    </div>
                </div>
            </div>

            <!-- Modal QR Code -->
            <div x-show="showQrModal" x-cloak style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
                <div @click.outside="showQrModal = false"
                     class="bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-3xl p-6 max-w-sm w-full shadow-2xl text-center relative">
                    <button type="button" @click="showQrModal = false" 
                            class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 dark:hover:text-white p-1 rounded-full">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>

                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-2xl">qr_code_2</span>
                    </div>

                    <h3 class="text-base font-black text-slate-900 dark:text-white mb-1">QR Code Toko Anda</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
                        Scan dengan kamera HP untuk langsung membuka toko: <span class="font-bold text-sky-600 dark:text-sky-400">{{ $store->name }}</span>
                    </p>

                    <div class="p-3 bg-white rounded-2xl border border-slate-200 inline-block shadow-inner mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $encodedUrl }}" 
                             alt="QR Code Toko {{ $store->name }}"
                             class="w-48 h-48 rounded-xl object-contain mx-auto">
                    </div>

                    <div class="space-y-2">
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ $encodedUrl }}&download=1"
                           target="_blank" download="qr-toko-{{ $storeSlug }}.png"
                           class="w-full py-2.5 px-4 bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs rounded-xl shadow-md transition-colors flex items-center justify-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">download</span>
                            <span>Download Gambar QR Code</span>
                        </a>
                        <button type="button" @click="copyToClipboard(); showQrModal = false;"
                                class="w-full py-2 px-4 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            Salin Tautan Saja
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- 4 Essential Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Metric 1: Total Revenue -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Saldo Penjual</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Siap ditarik</span>
                        <a href="{{ route('tenant.payouts.index') }}" class="font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-0.5">
                            Tarik Saldo <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric 2: Completed Orders -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pesanan Berhasil</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">verified</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($completedOrdersCount ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Dari {{ number_format($totalOrdersCount ?? 0) }} total order</span>
                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">
                            {{ $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100) : 100 }}% Sukses
                        </span>
                    </div>
                </div>
            </div>

            <!-- Metric 3: Pending Orders -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Menunggu Pembayaran</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">schedule</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($pendingOrdersCount ?? 0) }}
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Invoice tertunda</span>
                        <a href="{{ route('tenant.orders.index', ['tab' => 'pending']) }}" class="font-bold text-amber-600 dark:text-amber-400 hover:underline">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <!-- Metric 4: Active Products -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Katalog Produk</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[22px]">inventory_2</span>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                        {{ number_format($activeProducts ?? 0) }} <span class="text-xs font-semibold text-slate-400">/ {{ number_format($totalProducts ?? 0) }}</span>
                    </div>
                    <div class="mt-2 flex items-center justify-between text-xs">
                        <span class="text-slate-400">Produk berstatus aktif</span>
                        <a href="{{ route('tenant.products.index') }}" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Kelola Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area: 2 Columns (7:5) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Area: Recent Orders (7 cols) -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 md:p-6 border-b border-slate-100 dark:border-[#222f49] flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white">Transaksi Penjualan Terbaru</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Pesanan produk digital terkini dari pelanggan Anda.</p>
                        </div>
                        <a href="{{ route('tenant.orders.index') }}" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1">
                            Semua Pesanan <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100 dark:divide-[#1d273d]">
                        @forelse($recentOrders as $order)
                        @php
                            $tenantItems = $order->orderItems->filter(function($item) use ($store) {
                                return $item->product && $item->product->store_id == $store->id;
                            });
                            $firstItem = $tenantItems->first() ?? $order->orderItems->first();
                            $firstProduct = $firstItem ? $firstItem->product : $order->product;
                            $amountForTenant = $tenantItems->isNotEmpty() ? $tenantItems->sum(function($item){ return $item->price * $item->quantity; }) : $order->amount;
                        @endphp
                        <div class="p-4 md:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/70 dark:hover:bg-[#161f33]/60 transition-colors">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-11 h-11 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 overflow-hidden text-slate-500">
                                    @if($firstProduct && $firstProduct->images->count() > 0)
                                        @php $img = $firstProduct->images->where('is_main', true)->first() ?? $firstProduct->images->first(); @endphp
                                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-[20px]">code</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                        {{ $firstProduct->name ?? 'Produk Digital' }}
                                    </h3>
                                    <div class="flex items-center gap-2 text-xs text-slate-400 mt-0.5 font-mono">
                                        <span>{{ $order->invoice_number }}</span>
                                        <span>•</span>
                                        <span>{{ $order->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex sm:flex-col items-center sm:items-end justify-between gap-1 shrink-0">
                                <div class="text-sm font-extrabold text-slate-900 dark:text-white">
                                    Rp {{ number_format($amountForTenant, 0, ',', '.') }}
                                </div>
                                <div>
                                    @if($order->status === 'paid' || $order->status === 'downloaded')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                        </span>
                                    @elseif($order->status === 'failed')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">shopping_bag</span>
                            <p class="text-sm">Belum ada transaksi penjualan baru.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Feature Shortcuts Banner -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="{{ route('tenant.appearance.index') }}" class="group bg-gradient-to-br from-indigo-50 to-white dark:from-[#131b2e] dark:to-[#111726] border border-indigo-100 dark:border-[#263553] rounded-2xl p-5 hover:border-indigo-400 dark:hover:border-indigo-500 transition-all shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 mb-3 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[20px]">palette</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors flex items-center gap-1">
                            Dekorasi Toko <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kustomisasi banner, tampilan beranda, dan tema etalase toko Anda.</p>
                    </a>

                    <a href="{{ route('tenant.bank.index') }}" class="group bg-gradient-to-br from-sky-50 to-white dark:from-[#101e33] dark:to-[#111726] border border-sky-100 dark:border-[#1d3559] rounded-2xl p-5 hover:border-sky-400 dark:hover:border-sky-500 transition-all shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center shadow-md shadow-sky-500/20 mb-3 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-[20px]">credit_card</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors flex items-center gap-1">
                            Rekening Bank <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Atur data rekening bank tujuan untuk pencairan dana otomatis.</p>
                    </a>
                </div>
            </div>

            <!-- Right Area: Store Products & Performance Quick Guide (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Store Quick Glance -->
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base font-bold text-slate-900 dark:text-white">Koleksi Produk Anda</h2>
                        <a href="{{ route('tenant.products.index') }}" class="text-xs font-bold text-sky-600 dark:text-sky-400 hover:underline">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($topProducts as $prod)
                        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-[#161f33] transition-colors border border-transparent hover:border-slate-200/60 dark:hover:border-[#222f49]">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 overflow-hidden shrink-0 flex items-center justify-center">
                                @if($prod->images->count() > 0)
                                    @php $prodImg = $prod->images->where('is_main', true)->first() ?? $prod->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $prodImg->image_path) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-slate-400">inventory_2</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ $prod->name }}</h4>
                                <div class="text-xs font-bold text-sky-600 dark:text-sky-400 mt-0.5">
                                    Rp {{ number_format($prod->discount_price ?? $prod->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <a href="{{ route('tenant.products.edit', $prod) }}" class="p-1.5 text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 transition-colors" title="Edit Produk">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                        </div>
                        @empty
                        <div class="py-8 text-center text-slate-400">
                            <p class="text-xs">Belum ada produk yang diunggah.</p>
                            <a href="{{ route('tenant.products.create') }}" class="mt-2 inline-block text-xs font-bold text-sky-600 dark:text-sky-400 underline">
                                Tambah Sekarang
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Tips & Growth Guide -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-6 shadow-md border border-slate-700 relative overflow-hidden">
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-sky-500/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                        </div>
                        <h3 class="font-bold text-sm text-white">Tips Penjualan Optimal</h3>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Lengkapi deskripsi produk digital Anda dengan informasi spesifikasi source code/aplikasi, panduan instalasi, dan link demo langsung untuk meningkatkan kepercayaan calon pembeli.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-700/60 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Pusat Bantuan Mitra</span>
                        <a href="{{ route('contact') }}" class="text-xs font-bold text-sky-400 hover:text-sky-300 transition-colors flex items-center gap-0.5">
                            Hubungi Tim Support <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
