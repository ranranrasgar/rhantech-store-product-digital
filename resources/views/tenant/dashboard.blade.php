@extends('layouts.tenant')

@section('title', 'Dashboard Toko')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- Header Hero & Quick Info -->
        <div class="rounded-2xl bg-[#00838f] text-white p-6 md:p-7 border border-[#00727d] dark:border-teal-700">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl p-1 bg-white/15 border border-white/25 overflow-hidden shrink-0">
                        @if($store && $store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full bg-white/20 rounded-xl flex items-center justify-center font-black text-2xl text-white">
                                {{ strtoupper(substr($store->name ?? 'T', 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 border border-white/25 text-xs font-semibold text-teal-50 mb-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                            Merchant Partner
                        </div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white flex items-center gap-2">
                            {{ $store->name ?? 'Toko Saya' }}
                        </h1>
                        <p class="text-xs md:text-sm text-teal-100 mt-1 max-w-xl line-clamp-1">
                            {{ $store->description ?: 'Kelola produk digital, pantau penjualan, dan tingkatkan penghasilan Anda.' }}
                        </p>
                    </div>
                </div>

                <!-- Action Hub Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    @if($store && $store->slug)
                    <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/30 text-white text-xs md:text-sm font-semibold transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">storefront</span>
                        Lihat Toko Publik
                    </a>
                    @endif
                    <a href="{{ route('tenant.products.create') }}" class="px-5 py-2.5 rounded-xl bg-white text-[#00838f] hover:bg-teal-50 text-xs md:text-sm font-bold transition-colors flex items-center gap-2">
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
        }" class="bg-white dark:bg-[#161b22] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 md:p-6">

            <div class="space-y-5">
                
                <!-- Header: Title & Badges -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 md:w-11 md:h-11 rounded-2xl bg-teal-500/10 text-[#00838f] dark:text-teal-400 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[22px] md:text-[24px]">share</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h2 class="text-base md:text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                                    Promosikan & Bagikan Toko Anda
                                </h2>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800/60">
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
                       class="self-start sm:self-center inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-[#00838f] text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-[#00838f] dark:hover:text-teal-400 transition-colors"
                       title="Ubah URL / Slug Toko di Pengaturan">
                        <span class="material-symbols-outlined text-[16px] text-[#00838f] dark:text-teal-400">settings_suggest</span>
                        <span>Atur Slug Toko</span>
                    </a>
                </div>

                <!-- Main URL Box & Copy Actions -->
                <div class="bg-slate-50 dark:bg-slate-900/80 p-2 sm:p-2.5 rounded-xl border border-slate-200 dark:border-slate-700/80 flex flex-col md:flex-row items-stretch md:items-center gap-2.5">
                    <div class="flex items-center gap-2.5 flex-1 min-w-0 px-2.5 py-1">
                        <span class="material-symbols-outlined text-[20px] text-[#00838f] dark:text-teal-400 shrink-0">link</span>
                        <input type="text" id="store-link-input" readonly :value="storeUrl" 
                               @click="copyToClipboard()"
                               class="w-full bg-transparent border-none p-0 text-xs md:text-sm font-mono font-bold text-slate-900 dark:text-white focus:outline-none select-all cursor-pointer truncate"
                               title="Klik untuk salin tautan">
                    </div>

                    <!-- Action Buttons: Copy, Open, QR -->
                    <div class="flex items-center gap-2 shrink-0">
                        <!-- Copy Button -->
                        <button type="button" @click="copyToClipboard()"
                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl font-bold text-xs transition-colors cursor-pointer"
                                :class="copied ? 'bg-emerald-600 text-white' : 'bg-[#00838f] hover:bg-[#00727d] text-white'">
                            <span class="material-symbols-outlined text-[17px]" x-text="copied ? 'check_circle' : 'content_copy'"></span>
                            <span x-text="copied ? 'Tersalin! 🎉' : 'Salin Tautan'"></span>
                        </button>

                        <!-- Open Store Button -->
                        <a :href="storeUrl" target="_blank"
                           class="p-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-colors shrink-0"
                           title="Buka Toko di Tab Baru">
                            <span class="material-symbols-outlined text-[17px]">open_in_new</span>
                        </a>

                        <!-- QR Code Button -->
                        <button type="button" @click="showQrModal = true"
                                class="p-2 rounded-xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition-colors shrink-0 cursor-pointer"
                                title="Lihat & Download QR Code Toko">
                            <span class="material-symbols-outlined text-[17px]">qr_code_2</span>
                        </button>
                    </div>
                </div>

                <!-- Social Media Share Buttons -->
                <div class="pt-1 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="font-bold text-slate-500 dark:text-slate-400 text-[11px] uppercase tracking-wider mr-1">Bagikan Langsung:</span>

                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ $encodedMsg }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 font-semibold text-xs border border-emerald-200 dark:border-emerald-800/60 transition-colors"
                           title="Bagikan ke WhatsApp Chat / Status">
                            <svg class="w-3.5 h-3.5 fill-current text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>WhatsApp</span>
                        </a>

                        <!-- Telegram -->
                        <a href="https://t.me/share/url?url={{ $encodedUrl }}&text={{ $encodedMsg }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-sky-50 hover:bg-sky-100 dark:bg-sky-950/40 dark:hover:bg-sky-900/50 text-sky-700 dark:text-sky-300 font-semibold text-xs border border-sky-200 dark:border-sky-800/60 transition-colors"
                           title="Bagikan ke Telegram">
                            <svg class="w-3.5 h-3.5 fill-current text-sky-500" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.295-.6.295-.002 0-.003 0-.005 0l.213-3.054 5.56-5.022c.24-.213-.054-.334-.373-.121l-6.869 4.326-2.96-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.196 1.006.128.832.942z"/></svg>
                            <span>Telegram</span>
                        </a>

                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $encodedUrl }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-semibold text-xs border border-blue-200 dark:border-blue-800/60 transition-colors"
                           title="Bagikan ke Facebook">
                            <svg class="w-3.5 h-3.5 fill-current text-blue-600" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                            <span>Facebook</span>
                        </a>

                        <!-- X (Twitter) -->
                        <a href="https://twitter.com/intent/tweet?text={{ $encodedMsg }}&url={{ $encodedUrl }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold text-xs border border-slate-200 dark:border-slate-700 transition-colors"
                           title="Bagikan ke Twitter / X">
                            <svg class="w-3.5 h-3.5 fill-current text-slate-800 dark:text-white" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span>X</span>
                        </a>

                        <!-- Mobile Native Share Sheet -->
                        <button type="button" @click="shareNative()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-50 hover:bg-purple-100 dark:bg-purple-950/40 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 font-semibold text-xs border border-purple-200 dark:border-purple-800/60 transition-colors cursor-pointer"
                                title="Bagikan via Aplikasi Lain di HP">
                            <span class="material-symbols-outlined text-[15px] text-purple-600 dark:text-purple-400">send_to_mobile</span>
                            <span>Lainnya</span>
                        </button>
                    </div>

                    <!-- Guidance Note -->
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-[#00838f] dark:text-teal-400">info</span>
                        <span>Tautan otomatis mengarahkan pengunjung ke etalase tokomu.</span>
                    </div>
                </div>
            </div>

            <!-- Modal QR Code -->
            <div x-show="showQrModal" x-cloak style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto bg-black/60 flex items-center justify-center p-4">
                <div @click.outside="showQrModal = false"
                     class="bg-white dark:bg-[#111726] border border-slate-200 dark:border-[#222f49] rounded-2xl p-6 max-w-sm w-full text-center relative">
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

                    <div class="p-3 bg-white rounded-xl border border-slate-200 inline-block mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $encodedUrl }}" 
                             alt="QR Code Toko {{ $store->name }}"
                             class="w-48 h-48 rounded-lg object-contain mx-auto">
                    </div>

                    <div class="space-y-2">
                        <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ $encodedUrl }}&download=1"
                           target="_blank" download="qr-toko-{{ $storeSlug }}.png"
                           class="w-full py-2.5 px-4 bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs rounded-xl transition-colors flex items-center justify-center gap-1.5">
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
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
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
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
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
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
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
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5">
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
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl overflow-hidden">
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
                    <a href="{{ route('tenant.appearance.index') }}" class="group bg-slate-50 hover:bg-slate-100/80 dark:bg-[#131b2e] border border-slate-200 dark:border-[#263553] rounded-2xl p-5 hover:border-indigo-400 dark:hover:border-indigo-500 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined text-[20px]">palette</span>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors flex items-center gap-1">
                            Dekorasi Toko <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kustomisasi banner, tampilan beranda, dan tema etalase toko Anda.</p>
                    </a>

                    <a href="{{ route('tenant.bank.index') }}" class="group bg-slate-50 hover:bg-slate-100/80 dark:bg-[#101e33] border border-slate-200 dark:border-[#1d3559] rounded-2xl p-5 hover:border-sky-400 dark:hover:border-sky-500 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-sky-500 text-white flex items-center justify-center mb-3">
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
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-6">
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
                <div class="bg-slate-900 text-white rounded-2xl p-6 border border-slate-800">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">lightbulb</span>
                        </div>
                        <h3 class="font-bold text-sm text-white">Tips Penjualan Optimal</h3>
                    </div>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Lengkapi deskripsi produk digital Anda dengan informasi spesifikasi source code/aplikasi, panduan instalasi, dan link demo langsung untuk meningkatkan kepercayaan calon pembeli.
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
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
