@extends('layouts.store')

@section('title', $store->name)

@php
    $banner = $store->banner;
    $isCustomColorOrGradient = $banner && (
        str_starts_with($banner, 'linear-gradient') || 
        str_starts_with($banner, 'radial-gradient') || 
        str_starts_with($banner, '#') || 
        str_starts_with($banner, 'rgb')
    );
    $headerBgStyle = '';
    if ($isCustomColorOrGradient) {
        $headerBgStyle = "background: {$banner};";
    } elseif (!empty($banner) && $banner !== 'none') {
        $headerBgStyle = "background-image: url('{$banner}'); background-size: cover; background-position: center;";
    } else {
        $headerBgStyle = "background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #075985 100%);";
    }
@endphp

@section('content')
<div class="min-h-screen bg-surface-container-lowest"
    x-data="{ 
        activeTab: '{{ (request('q') || request('search') || request('category')) ? 'produk' : 'beranda' }}',
        isFollowing: {{ $isFollowing ? 'true' : 'false' }},
        followersCount: {{ $store->followers_count ?? $store->followers()->count() }},
        mobileSearchOpen: false,
        shareModalOpen: false,
        shareCopied: false,
        followToastOpen: false,
        followToastMessage: '',
        toggleFollow() {
            @auth
            fetch('{{ route('store.follow', $store->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok && res.status === 401) {
                    window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(window.location.href);
                    return null;
                }
                return res.json();
            })
            .then(data => {
                if (!data) return;
                this.isFollowing = data.following;
                if (data.followers_count !== undefined) {
                    this.followersCount = data.followers_count;
                } else {
                    this.followersCount = this.isFollowing ? this.followersCount + 1 : Math.max(0, this.followersCount - 1);
                }
                if (data.message) {
                    this.followToastMessage = data.message;
                    this.followToastOpen = true;
                    setTimeout(() => { this.followToastOpen = false; }, 3000);
                }
            })
            .catch(err => console.error(err));
            @else
            window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(window.location.href);
            @endauth
        },
        copyStoreLink() {
            const url = '{{ url('/' . $store->slug) }}';
            navigator.clipboard.writeText(url);
            this.shareCopied = true;
            setTimeout(() => this.shareCopied = false, 2500);
        },
        nativeShare() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($store->name) }}',
                    text: 'Kunjungi toko resmi {{ addslashes($store->name) }}',
                    url: '{{ url('/' . $store->slug) }}'
                }).catch(() => {});
            } else {
                this.copyStoreLink();
            }
        },
        shareStore() {
            this.shareModalOpen = true;
        }
    }"
    @toggle-store-search.window="mobileSearchOpen = !mobileSearchOpen; if (mobileSearchOpen) { $nextTick(() => { $refs.mobileSearchInput && $refs.mobileSearchInput.focus() }) }">
    
    @if($store->isBanned() || $store->isSuspended())
        <div class="bg-red-600 text-white px-4 py-3 text-center text-sm font-semibold shadow-md flex items-center justify-center gap-2 sticky top-0 z-40">
            <span class="material-symbols-outlined text-lg">warning</span>
            <div>
                <span>
                    @if($store->isBanned())
                        Toko ini sedang dinonaktifkan / diblokir oleh Platform. Pembelian produk dinonaktifkan sementara.
                    @else
                        Toko ini sedang dalam peninjauan / ditangguhkan sementara oleh Platform.
                    @endif
                </span>
                @if($store->ban_reason)
                    <span class="block text-xs font-normal opacity-90 mt-0.5">Catatan Platform: {{ $store->ban_reason }}</span>
                @endif
            </div>
        </div>
    @endif

    <!-- Mobile Interactive Search Modal / Drawer (Muncul ketika tombol cari di klik) -->
    <div x-show="mobileSearchOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         x-cloak
         class="md:hidden fixed inset-x-0 top-0 z-50 bg-surface/98 dark:bg-slate-900/98 backdrop-blur-xl border-b border-outline-variant p-4 shadow-2xl">
        <div class="max-w-md mx-auto space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[22px]">search</span>
                    <h3 class="text-sm font-extrabold text-on-surface">Cari Produk di Toko</h3>
                </div>
                <button type="button" @click="mobileSearchOpen = false" class="p-1.5 rounded-lg text-on-surface-variant hover:bg-surface-container text-xs flex items-center justify-center cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form action="{{ route('store.show', $store->slug) }}" method="GET" class="relative flex items-center gap-2">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative flex-1 flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-surface-variant">
                        <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                    </div>
                    <input type="text" 
                           name="q" 
                           x-ref="mobileSearchInput"
                           value="{{ request('q', request('search', '')) }}"
                           placeholder="Ketik nama produk yang dicari..." 
                           class="w-full pl-9 pr-9 py-2.5 text-xs bg-surface-container border border-outline-variant rounded-xl text-on-surface placeholder:text-on-surface-variant/70 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all font-medium">
                    @if(request('q') || request('search'))
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <a href="{{ route('store.show', $store->slug) }}" class="text-on-surface-variant hover:text-error flex items-center justify-center" title="Reset pencarian">
                                <span class="material-symbols-outlined text-[16px] leading-none">cancel</span>
                            </a>
                        </div>
                    @endif
                </div>
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-primary text-white text-xs font-bold shadow-md shadow-primary/25 hover:bg-primary/90 transition-all cursor-pointer">
                    Cari
                </button>
            </form>
            @if($categories->count() > 0)
            <div class="pt-1 flex items-center gap-1.5 overflow-x-auto hide-scrollbar text-[11px]">
                <span class="text-on-surface-variant shrink-0 font-medium">Kategori:</span>
                @foreach($categories->take(5) as $cat)
                    <a href="{{ route('store.show', $store->slug) }}?category={{ $cat->id }}" class="shrink-0 px-2.5 py-1 rounded-lg bg-surface-container border border-outline-variant hover:border-primary text-on-surface font-semibold">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Share & Media Sosial Modal / Bottom Sheet -->
    @php $socialLinks = is_array($store->social_links) ? $store->social_links : []; @endphp
    <div x-show="shareModalOpen" 
         x-transition.opacity.duration.250ms
         class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
         @click="shareModalOpen = false"
         @keydown.escape.window="shareModalOpen = false"
         x-cloak>
        
        <div x-show="shareModalOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full sm:translate-y-4 sm:scale-95 opacity-0"
             x-transition:enter-end="translate-y-0 sm:scale-100 opacity-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0 sm:scale-100 opacity-100"
             x-transition:leave-end="translate-y-full sm:translate-y-4 sm:scale-95 opacity-0"
             @click.stop
             class="w-full sm:max-w-md bg-surface dark:bg-slate-900 rounded-t-[28px] sm:rounded-2xl border-t sm:border border-outline-variant/60 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            
            <!-- Mobile Drag Indicator -->
            <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mt-3 mb-1 sm:hidden"></div>

            <!-- Header -->
            <div class="px-5 py-4 border-b border-outline-variant/50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-outline-variant/60 shrink-0 p-0.5">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-[10px]">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0284c7&color=fff&size=80" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-[10px]">
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-on-surface flex items-center gap-1.5 truncate">
                            <span class="truncate">{{ $store->name }}</span>
                            @if($store->isPro())
                                <span class="bg-amber-500/20 text-amber-500 text-[10px] font-black px-1.5 py-0.2 rounded uppercase shrink-0">PRO</span>
                            @endif
                        </h3>
                        <p class="text-xs text-amber-600 dark:text-amber-400 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px]">loyalty</span> Program Kemitraan & Afiliasi
                        </p>
                    </div>
                </div>
                <button type="button" 
                        @click="shareModalOpen = false" 
                        class="w-8 h-8 rounded-full flex items-center justify-center text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-colors cursor-pointer shrink-0">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="p-5 space-y-4 overflow-y-auto hide-scrollbar">
                
                @if(isset($myAffiliateLink))
                <!-- 1. LINK REFERRAL AFILIASI AKTIF -->
                <div class="p-4 bg-surface-container border border-outline-variant/60 rounded-2xl space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">loyalty</span>
                            <span>Link Afiliasi Anda</span>
                        </span>
                        <span class="text-[10px] font-black px-2.5 py-0.5 rounded-full bg-emerald-500 text-white shadow-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">check_circle</span>
                            KOMISI {{ (float)($store->default_affiliate_commission ?? 10) }}% AKTIF
                        </span>
                    </div>

                    <p class="text-xs text-on-surface leading-relaxed">
                        Bagikan link khusus Anda di bawah ini. Anda otomatis mendapatkan komisi <strong class="text-emerald-600 dark:text-emerald-400 font-black">{{ (float)($store->default_affiliate_commission ?? 10) }}%</strong> dari setiap pembelian yang masuk melalui link ini!
                    </p>

                    <!-- Input Box & Tombol Salin -->
                    <div class="flex items-center gap-2 p-1.5 bg-surface dark:bg-slate-800 rounded-xl border border-amber-500/40 shadow-xs">
                        <input type="text" 
                               readonly 
                               value="{{ $myAffiliateLink }}" 
                               id="affiliate_ref_link"
                               class="bg-transparent border-0 text-xs font-mono font-bold text-amber-700 dark:text-amber-400 px-2.5 flex-1 focus:outline-none focus:ring-0 truncate select-all">
                        <button type="button" 
                                @click="navigator.clipboard.writeText('{{ $myAffiliateLink }}'); shareCopied = true; setTimeout(() => shareCopied = false, 2500)"
                                class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-xs font-bold transition-all flex items-center gap-1.5 shadow-xs active:scale-95 cursor-pointer shrink-0">
                            <span class="material-symbols-outlined text-[16px]" x-text="shareCopied ? 'check' : 'content_copy'">content_copy</span>
                            <span x-text="shareCopied ? 'Tersalin!' : 'Salin'">Salin</span>
                        </button>
                    </div>

                    <!-- Tombol Cepat Bagikan Link Afiliasi -->
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <a href="https://api.whatsapp.com/send?text={{ urlencode('Beli produk digital pilihan di ' . $store->name . ' melalui tautan rekomendasi saya: ' . $myAffiliateLink) }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-[#25D366]/15 hover:bg-[#25D366]/25 text-[#25D366] text-xs font-bold border border-[#25D366]/30 transition-all active:scale-95">
                            <x-store-social-icon platform="whatsapp" class="w-4 h-4" />
                            <span>Kirim WhatsApp</span>
                        </a>

                        <button type="button" 
                                @click="if (navigator.share) { navigator.share({ title: '{{ addslashes($store->name) }}', text: 'Beli produk digital di {{ addslashes($store->name) }} melalui link ini:', url: '{{ $myAffiliateLink }}' }); } else { navigator.clipboard.writeText('{{ $myAffiliateLink }}'); shareCopied = true; setTimeout(() => shareCopied = false, 2500); }" 
                                class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold border border-outline-variant transition-all active:scale-95 cursor-pointer">
                            <span class="material-symbols-outlined text-[16px]">share</span>
                            <span>Bagikan Lainnya</span>
                        </button>
                    </div>

                    <!-- Kode Referral & Link Cek Saldo Komisi -->
                    <div class="flex items-center justify-between text-[11px] text-on-surface-variant pt-2 border-t border-amber-500/20">
                        <span>Kode Referral: <strong class="font-mono text-on-surface text-xs font-bold">{{ $myAffiliateCode }}</strong></span>
                        <a href="{{ route('tenant.payouts.index') }}?tab=mitra_referral" class="text-primary hover:underline font-bold flex items-center gap-0.5">
                            <span>Cek Saldo Komisi</span>
                            <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                        </a>
                    </div>
                </div>

                <!-- 2. INFO CARA KERJA KOMISI AFILIASI -->
                <div class="p-3.5 bg-surface-container/60 dark:bg-slate-800/60 rounded-xl border border-outline-variant/60 space-y-2">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[15px] text-primary">info</span>
                        <span>Cara Kerja Afiliasi:</span>
                    </span>
                    <ul class="text-[11px] text-on-surface-variant space-y-1.5 pl-4 list-disc marker:text-primary">
                        <li>Bagikan link referral Anda ke media sosial, teman, grup WhatsApp, atau komunitas.</li>
                        <li>Ketika ada pembeli yang mengklik link Anda dan bertransaksi di toko ini, sistem mencatat komisi Anda secara otomatis.</li>
                        <li>Komisi <strong class="text-on-surface">{{ (float)($store->default_affiliate_commission ?? 10) }}%</strong> langsung masuk ke saldo akun Anda dan dapat dicairkan kapan saja.</li>
                    </ul>
                </div>

                @elseif(auth()->check() && auth()->id() === $store->user_id)
                <!-- TAMPILAN UNTUK PEMILIK TOKO SENDIRI -->
                <div class="p-4 bg-primary/10 border border-primary/20 rounded-2xl space-y-3">
                    <div class="flex items-center gap-2 text-primary font-bold text-sm">
                        <span class="material-symbols-outlined text-[20px]">store</span>
                        <span>Anda adalah Pemilik Toko Ini</span>
                    </div>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Pengunjung dan mitra yang membuka toko Anda otomatis mendapatkan link referral dengan bagi hasil komisi <strong class="text-primary font-bold">{{ (float)($store->default_affiliate_commission ?? 10) }}%</strong>.
                    </p>
                    <a href="{{ route('tenant.affiliates.index') }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs font-bold transition shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">settings</span>
                        <span>Kelola Mitra & Atur Komisi Toko</span>
                    </a>
                </div>

                @else
                <!-- PENGUNJUNG BELUM LOGIN -->
                <div class="p-5 bg-surface-container border border-outline-variant/60 rounded-2xl space-y-3.5 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-600 flex items-center justify-center mx-auto shadow-xs">
                        <span class="material-symbols-outlined text-2xl">monetization_on</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-on-surface">Dapatkan Komisi {{ (float)($store->default_affiliate_commission ?? 10) }}%</h4>
                        <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">
                            Jadilah mitra afiliasi toko ini! Login atau buat akun untuk mendapatkan tautan referral unik dan mulai raih penghasilan dari setiap penjualan.
                        </p>
                    </div>
                    <a href="{{ route('login') }}?redirect={{ urlencode(url('/' . $store->slug)) }}" class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">login</span>
                        <span>Login / Daftar Jadi Mitra</span>
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Toast Notifikasi Share -->
    <div x-show="shareCopied" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-8"
         x-cloak
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-4 py-2.5 rounded-2xl bg-slate-900/90 text-white text-xs font-bold backdrop-blur-md shadow-2xl border border-white/10 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px] text-emerald-400">check_circle</span>
        <span>Tautan toko berhasil disalin ke clipboard!</span>
    </div>

    <!-- Toast Notifikasi Follow -->
    <div x-show="followToastOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-8"
         x-cloak
         class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-4 py-2.5 rounded-2xl bg-slate-900/95 text-white text-xs font-bold backdrop-blur-md shadow-2xl border border-white/15 flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]" :class="isFollowing ? 'text-emerald-400' : 'text-amber-400'" x-text="isFollowing ? 'person_check' : 'person_remove'">person_check</span>
        <span x-text="followToastMessage"></span>
    </div>

    <!-- 1. DESKTOP STORE HEADER BANNER (Layout desktop dipertahankan utuh) -->
    <div class="hidden md:block relative w-full min-h-[350px] bg-[#1a1a1a]">
        <!-- Background Banner (Image / Gradient / Color) -->
        <div class="absolute inset-0 bg-cover bg-center opacity-85" style="{{ $headerBgStyle }}"></div>
        <div class="absolute inset-0 bg-black/55"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pt-24 pb-8 relative z-10">
            <div class="flex flex-row items-end gap-6">
                <!-- Avatar -->
                <div class="w-32 h-32 rounded-full border-4 {{ $store->isPro() ? 'border-amber-400 ring-4 ring-amber-500/30' : 'border-white' }} shadow-lg overflow-hidden bg-white shrink-0">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0D8ABC&color=fff&size=128" alt="{{ $store->name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                
                <!-- Info -->
                <div class="flex-1 text-white">
                    <h1 class="text-3xl font-bold mb-2 drop-shadow-md flex items-center gap-2 flex-wrap">
                        {{ $store->name }}
                        @if($store->isPro())
                            <span class="bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full font-black flex items-center gap-1 border border-amber-400">
                                <span class="material-symbols-outlined text-[14px]">stars</span> PRO
                            </span>
                        @endif
                    </h1>
                    <div class="flex items-center gap-4 text-sm drop-shadow-md opacity-90">
                        <span class="flex items-center gap-1"><span class="text-yellow-400 text-lg">★</span> 4.8</span>
                        <span>•</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">inventory_2</span> {{ $products->total() }} Produk</span>
                        <span>•</span>
                        <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">group</span> <span x-text="followersCount"></span> Pengikut</span>
                    </div>

                    {{-- Sosmed di Desktop (Monokrom / Netral Elegan) --}}
                    @php $socialLinks = is_array($store->social_links) ? $store->social_links : []; @endphp
                    @if(count($socialLinks) > 0)
                        <div class="flex items-center gap-2 mt-3 overflow-x-auto hide-scrollbar">
                            @foreach($socialLinks as $soc)
                                @php
                                    $socPlatform = strtolower($soc['platform'] ?? 'custom');
                                    $socName = $soc['name'] ?? ucfirst($socPlatform);
                                    $socUrl = $soc['url'] ?? '#';
                                @endphp
                                <a href="{{ $socUrl }}" target="_blank" rel="noopener noreferrer" 
                                   class="w-8 h-8 rounded-full flex items-center justify-center bg-white/15 hover:bg-white text-white hover:text-slate-900 border border-white/20 backdrop-blur-md shadow-xs hover:scale-110 active:scale-95 transition-all" 
                                   title="{{ $socName }}">
                                    <x-store-social-icon :platform="$socPlatform" class="w-4 h-4" />
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    @if(!auth()->check() || auth()->id() !== $store->user_id)
                    <button @click="@auth window.dispatchEvent(new CustomEvent('open-chat-with-store', { 
                        detail: { 
                            store_id: {{ $store->id }}, 
                            store_name: '{{ addslashes($store->name) }}',
                            store_slug: '{{ $store->slug }}',
                            store_logo: '{{ $store->logo ? asset('storage/' . $store->logo) : '' }}'
                        } 
                    })) @else window.location.href = '{{ route('login') }}' @endauth" class="px-6 py-2 bg-transparent border border-white text-white rounded font-bold hover:bg-white/20 transition-colors flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">chat</span> Chat
                    </button>
                    <button @click="toggleFollow()" 
                        :class="isFollowing ? 'bg-surface-container border-outline text-on-surface hover:bg-surface-container-high' : 'bg-primary border-primary text-white hover:bg-primary/90'"
                        class="px-6 py-2 border rounded font-bold transition-colors flex items-center gap-2 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]" x-text="isFollowing ? 'check' : 'add'">add</span> 
                        <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti'">Ikuti</span>
                    </button>
                    @endif

                    <button type="button" 
                            @click="shareModalOpen = true" 
                            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 border border-emerald-400 text-white rounded font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-md active:scale-95"
                            title="Program Afiliasi Toko (Komisi {{ (float)($store->default_affiliate_commission ?? 10) }}%)">
                        <span class="material-symbols-outlined text-[18px]">loyalty</span>
                        <span>Link Afiliasi ({{ (float)($store->default_affiliate_commission ?? 10) }}%)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MOBILE INTERACTIVE STORE HEADER (Khusus Versi Mobile) -->
    <div class="md:hidden relative w-full bg-[#0d1322] overflow-hidden text-white">
        <!-- Banner Background (Image / Gradient / Color) -->
        <div class="absolute inset-0 bg-cover bg-center opacity-85 scale-105" style="{{ $headerBgStyle }}"></div>
        <div class="absolute inset-0 bg-black/65"></div>
        
        <div class="relative z-10 px-4 pt-5 pb-5 space-y-3">
            <!-- Top Identity Row -->
            <div class="flex items-start gap-3.5">
                <!-- Avatar with Glossy Border -->
                <div class="relative shrink-0 w-[68px] h-[68px]">
                    <div class="w-[68px] h-[68px] rounded-2xl border-2 {{ $store->isPro() ? 'border-amber-400 ring-2 ring-amber-500/30' : 'border-white/80' }} shadow-xl overflow-hidden bg-white p-0.5 flex items-center justify-center">
                        @if($store->logo)
                            <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-[14px]">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0284c7&color=fff&size=100" alt="{{ $store->name }}" class="w-full h-full object-cover rounded-[14px]">
                        @endif
                    </div>
                    <span class="absolute -bottom-1 -right-1 bg-sky-500 text-white rounded-full p-0.5 shadow-md flex items-center justify-center" title="Verified Store">
                        <span class="material-symbols-outlined text-[13px]">verified</span>
                    </span>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0 pt-0.5">
                    <h1 class="text-base font-extrabold text-white leading-tight line-clamp-2 drop-shadow-md">
                        {{ $store->name }}
                        @if($store->isPro())
                            <span class="inline-flex bg-amber-500 text-white text-[9px] px-1.5 py-0.5 rounded-md font-black items-center gap-0.5 border border-amber-400 align-middle ml-1">
                                <span class="material-symbols-outlined text-[10px]">stars</span> PRO
                            </span>
                        @endif
                    </h1>
                    
                    @if(!empty($store->description))
                        <p class="text-[11px] text-white/80 line-clamp-1 mt-1 font-medium">
                            {{ $store->description }}
                        </p>
                    @endif

                    <!-- Interactive Stats Pills -->
                    <div class="flex items-center gap-1.5 mt-2 flex-wrap text-[10px]">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white/15 backdrop-blur-md border border-white/20 font-bold text-amber-300 shadow-2xs">
                            <span>★</span> 4.8
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white/15 backdrop-blur-md border border-white/20 font-semibold text-white/90 shadow-2xs">
                            <span class="material-symbols-outlined text-[13px]">inventory_2</span>
                            <span>{{ $products->total() }} Produk</span>
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white/15 backdrop-blur-md border border-white/20 font-semibold text-white/90 shadow-2xs">
                            <span class="material-symbols-outlined text-[13px]">group</span>
                            <span x-text="followersCount + ' Pengikut'"></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mobile Action Row (Chat, Ikuti, Share & Media Sosial) -->
            <div class="flex items-center gap-2 pt-1 pb-0.5">
                @if(!auth()->check() || auth()->id() !== $store->user_id)
                    <!-- Chat Button -->
                    <button type="button" 
                            @click="@auth window.dispatchEvent(new CustomEvent('open-chat-with-store', { 
                                detail: { 
                                    store_id: {{ $store->id }}, 
                                    store_name: '{{ addslashes($store->name) }}',
                                    store_slug: '{{ $store->slug }}',
                                    store_logo: '{{ $store->logo ? asset('storage/' . $store->logo) : '' }}'
                                } 
                            })) @else window.location.href = '{{ route('login') }}' @endauth" 
                            class="py-1.5 px-3.5 rounded-xl bg-white/15 hover:bg-white/25 active:scale-95 border border-white/25 backdrop-blur-md text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-[16px]">chat</span>
                        <span>Chat</span>
                    </button>

                    <!-- Follow Button -->
                    <button type="button" 
                            @click="toggleFollow()" 
                            :class="isFollowing 
                                ? 'bg-white/25 border-white/40 text-white' 
                                : 'bg-primary hover:bg-primary/90 text-white border-primary shadow-md shadow-primary/30'"
                            class="py-1.5 px-3.5 rounded-xl border active:scale-95 text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm shrink-0">
                        <span class="material-symbols-outlined text-[16px]" x-text="isFollowing ? 'check' : 'person_add'"></span>
                        <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti'"></span>
                    </button>
                @endif

                <!-- Affiliate Button -->
                <button type="button" 
                        @click="shareModalOpen = true" 
                        class="py-1.5 px-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 border border-emerald-400 text-white text-xs font-bold transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-sm shrink-0"
                        title="Program Afiliasi Toko (Komisi {{ (float)($store->default_affiliate_commission ?? 10) }}%)">
                    <span class="material-symbols-outlined text-[16px]">loyalty</span>
                    <span>Afiliasi {{ (float)($store->default_affiliate_commission ?? 10) }}%</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. DESKTOP STORE NAVIGATION (Layout desktop dipertahankan utuh) -->
    <div class="hidden md:block bg-surface dark:bg-slate-900 border-b border-outline-variant sticky top-[64px] sm:top-[68px] z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex overflow-x-auto hide-scrollbar">
            <button @click="activeTab = 'beranda'" :class="activeTab === 'beranda' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap cursor-pointer">Beranda Toko</button>
            <button @click="activeTab = 'produk'" :class="activeTab === 'produk' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap cursor-pointer">Semua Produk</button>
            <button @click="activeTab = 'kategori'" :class="activeTab === 'kategori' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap cursor-pointer">Kategori</button>
            @if($store->isPro())
            <button @click="activeTab = 'proyek'" :class="activeTab === 'proyek' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap cursor-pointer">Portofolio</button>
            @endif
            <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary'" class="px-6 py-4 font-bold border-b-2 border-transparent transition-colors whitespace-nowrap cursor-pointer">Profil Toko</button>
        </div>
    </div>

    <!-- 4. MOBILE INTERACTIVE SEGMENTED NAVIGATION (Khusus Versi Mobile) -->
    <div class="md:hidden sticky top-[64px] z-40 bg-surface/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-outline-variant/70 shadow-xs px-3 py-2 transition-colors">
        <div class="flex items-center gap-1.5 overflow-x-auto hide-scrollbar">
            <button @click="activeTab = 'beranda'" 
                    :class="activeTab === 'beranda' 
                        ? 'bg-primary text-white shadow-sm shadow-primary/30 font-bold' 
                        : 'bg-surface-container text-on-surface-variant hover:text-on-surface font-semibold'" 
                    class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all active:scale-95 cursor-pointer shrink-0">
                <span class="material-symbols-outlined text-[16px]">storefront</span>
                <span>Beranda Toko</span>
            </button>
            <button @click="activeTab = 'produk'" 
                    :class="activeTab === 'produk' 
                        ? 'bg-primary text-white shadow-sm shadow-primary/30 font-bold' 
                        : 'bg-surface-container text-on-surface-variant hover:text-on-surface font-semibold'" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all active:scale-95 cursor-pointer shrink-0">
                <span class="material-symbols-outlined text-[16px]">grid_view</span>
                <span>Semua Produk</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" 
                      :class="activeTab === 'produk' ? 'bg-white/25 text-white' : 'bg-surface-container-high text-on-surface-variant'">
                    {{ $products->total() }}
                </span>
            </button>
            <button @click="activeTab = 'kategori'" 
                    :class="activeTab === 'kategori' 
                        ? 'bg-primary text-white shadow-sm shadow-primary/30 font-bold' 
                        : 'bg-surface-container text-on-surface-variant hover:text-on-surface font-semibold'" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all active:scale-95 cursor-pointer shrink-0">
                <span class="material-symbols-outlined text-[16px]">category</span>
                <span>Kategori</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold" 
                      :class="activeTab === 'kategori' ? 'bg-white/25 text-white' : 'bg-surface-container-high text-on-surface-variant'">
                    {{ $categories->count() }}
                </span>
            </button>
            @if($store->isPro())
            <button @click="activeTab = 'proyek'" 
                    :class="activeTab === 'proyek' 
                        ? 'bg-primary text-white shadow-sm shadow-primary/30 font-bold' 
                        : 'bg-surface-container text-on-surface-variant hover:text-on-surface font-semibold'" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all active:scale-95 cursor-pointer shrink-0">
                <span class="material-symbols-outlined text-[16px]">work</span>
                <span>Portofolio</span>
            </button>
            @endif
            <button @click="activeTab = 'profil'" 
                    :class="activeTab === 'profil' 
                        ? 'bg-primary text-white shadow-sm shadow-primary/30 font-bold' 
                        : 'bg-surface-container text-on-surface-variant hover:text-on-surface font-semibold'" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs whitespace-nowrap transition-all active:scale-95 cursor-pointer shrink-0">
                <span class="material-symbols-outlined text-[16px]">info</span>
                <span>Profil</span>
            </button>
        </div>
    </div>

    <!-- Active Search Filter Banner -->
    @if(request('q') || request('search'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="p-3.5 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2 text-on-surface min-w-0">
                <span class="material-symbols-outlined text-primary text-[18px] shrink-0">filter_alt</span>
                <span class="truncate">Hasil pencarian: <strong class="text-primary font-bold">"{{ request('q', request('search')) }}"</strong> ({{ $products->total() }} produk)</span>
            </div>
            <a href="{{ route('store.show', $store->slug) }}" class="px-3 py-1 rounded-xl bg-surface border border-outline-variant font-bold text-on-surface hover:text-primary transition-all shrink-0">
                Reset
            </a>
        </div>
    </div>
    @endif

    <!-- Store Content (Dynamic Appearance) -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-5 md:py-8 space-y-6 md:space-y-8">
        
        <!-- Tab 1: Beranda -->
        <div x-show="activeTab === 'beranda'" class="space-y-6 md:space-y-8">
        
        @if(empty($appearance))
            @php
                $voucherPlacement = is_array($appearance['voucher_placement'] ?? null) 
                    ? $appearance['voucher_placement'] 
                    : (is_array($store->appearance_data ?? null) ? ($store->appearance_data['voucher_placement'] ?? []) : []);
                $vpHeader = $voucherPlacement['header'] ?? '';
                $headerCampaigns = $campaigns ?? collect();
                if ($vpHeader !== '' && $vpHeader !== 'none') {
                    $filtered = $campaigns->where('id', (int)$vpHeader);
                    if ($filtered->isNotEmpty()) {
                        $headerCampaigns = $filtered;
                    }
                }
            @endphp
            @if($vpHeader !== 'none' && $headerCampaigns->isNotEmpty())
                <!-- KUPON & VOUCHER TOKO (Fallback) -->
                <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-surface-container border border-outline-variant/60">
                    <div class="flex items-center justify-between gap-3 mb-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center shadow-xs">
                                <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                            </div>
                            <div>
                                <h2 class="text-sm sm:text-base font-black text-on-surface flex items-center gap-1.5">
                                    <span>Kupon & Voucher Toko</span>
                                    <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-extrabold">{{ $headerCampaigns->count() }} Tersedia</span>
                                </h2>
                                <p class="text-[11px] text-on-surface-variant font-medium">Salin kode voucher di bawah dan gunakan saat checkout untuk klaim potongan harga</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                        @foreach($headerCampaigns as $campaign)
                            <x-voucher-card :campaign="$campaign" mode="browse" />
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- FALLBACK DEFAULT VIEW -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-on-surface border-l-4 border-primary pl-3">Semua Produk</h2>
                    <div class="flex items-center gap-2">
                        <label for="sort-products" class="text-sm text-on-surface-variant">Urutkan:</label>
                        <select id="sort-products" aria-label="Urutkan produk" class="text-sm border border-outline-variant rounded px-3 py-1.5 focus:outline-none focus:border-primary">
                            <option>Terbaru</option>
                            <option>Terlaris</option>
                            <option>Harga Termurah</option>
                            <option>Harga Termahal</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @forelse($products as $product)
                    @php
                        $soldCount = (int)($product->sales_count ?? ($product->orders_count ?? 0));
                        $displaySold = $soldCount;
                        $avgRating = (float)$product->effective_rating;
                        $shortDesc = $product->short_description ?: Str::limit(strip_tags($product->description ?? ''), 55);
                        $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
                    @endphp
                    <a href="{{ route('products.show', $product->slug) }}" class="group bg-white dark:bg-surface-container border border-outline-variant hover:border-primary rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col relative">
                        <div class="aspect-square w-full bg-surface-container-high relative overflow-hidden">
                            @if($product->images->count() > 0)
                                @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                </div>
                            @endif
                            
                            @if($hasDiscount)
                                <div class="absolute top-2 right-2 bg-rose-500 text-white font-black text-[10px] px-2 py-0.5 rounded-md shadow-sm">
                                    -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                </div>
                            @endif

                            @if($product->category)
                                <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-xs text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                    {{ $product->category->name }}
                                </div>
                            @endif
                        </div>
                        <div class="p-3.5 flex flex-col flex-1">
                            <h3 class="font-bold text-on-surface text-xs md:text-sm line-clamp-2 mb-1.5 group-hover:text-primary transition-colors leading-snug">{{ $product->name }}</h3>
                            
                            @if(!empty($shortDesc))
                                <p class="text-[11px] text-slate-500 line-clamp-2 mb-3 leading-relaxed">
                                    {{ $shortDesc }}
                                </p>
                            @endif

                            <div class="mt-auto pt-2 border-t border-slate-100 dark:border-slate-800">
                                @if($hasDiscount)
                                    <div class="text-[10px] text-on-surface-variant line-through mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="font-black text-primary text-sm md:text-base">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                @else
                                    <div class="font-black text-primary text-sm md:text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                @endif
                                
                                <div class="flex items-center justify-between gap-1 mt-2 text-[10px] text-on-surface-variant font-medium">
                                    @if($avgRating > 0)
                                    <span class="flex items-center gap-0.5 text-amber-500 font-bold">
                                        <span class="material-symbols-outlined text-[13px] fill-current">star</span>
                                        {{ number_format($avgRating, 1) }}
                                    </span>
                                    @else
                                    <span class="text-[9px] px-1.5 py-0.5 bg-primary/10 text-primary font-bold rounded">
                                        Baru
                                    </span>
                                    @endif
                                    <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded text-[10px] font-bold">
                                        {{ $displaySold }} Terjual
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="col-span-full py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-5xl opacity-50 mb-4 block">inventory_2</span>
                        <p class="font-bold text-lg text-on-surface mb-1">Toko ini belum memiliki produk aktif.</p>
                        <p class="text-sm">Silakan kunjungi lagi nanti.</p>
                    </div>
                    @endforelse
                </div>
                
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            </div>

        @else
            <!-- DYNAMIC DECORATION VIEW -->
            @foreach($appearance as $block)
                @php $data = $block['data'] ?? []; @endphp
                
                @if($block['type'] === 'banner')
                    @php $images = $data['images'] ?? []; @endphp
                    @if(count($images) > 0)
                        <div class="w-full relative overflow-hidden rounded-2xl shadow-sm bg-slate-100" x-data="{ activeSlide: 0, slides: {{ count($images) }}, 
                            next() { this.activeSlide = this.activeSlide === this.slides - 1 ? 0 : this.activeSlide + 1 },
                            prev() { this.activeSlide = this.activeSlide === 0 ? this.slides - 1 : this.activeSlide - 1 },
                            init() { setInterval(() => this.next(), 4000); }
                        }">
                            <!-- Slides -->
                            <div class="relative w-full h-48 md:h-80 flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${activeSlide * 100}%)`">
                                @foreach($images as $img)
                                    <div class="w-full h-full flex-shrink-0 relative">
                                        @php
                                            $link = trim($img['link'] ?? '');
                                            $isExternal = !empty($link) && (str_starts_with($link, 'http://') || str_starts_with($link, 'https://'));
                                        @endphp
                                        @if(!empty($link))
                                            <a href="{{ $link }}" @if($isExternal) target="_blank" rel="noopener noreferrer" @endif class="block w-full h-full">
                                                <img src="{{ $img['image_url'] ?? '' }}" class="w-full h-full object-cover" alt="Banner Slide">
                                            </a>
                                        @else
                                            <img src="{{ $img['image_url'] ?? '' }}" class="w-full h-full object-cover" alt="Banner Slide">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Dots Navigation -->
                            @if(count($images) > 1)
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2">
                                <template x-for="i in slides">
                                    <button @click="activeSlide = i - 1" :class="activeSlide === i - 1 ? 'bg-white w-4' : 'bg-white/50 w-2'" class="h-2 rounded-full transition-all shadow-sm"></button>
                                </template>
                            </div>
                            <!-- Arrows -->
                            <button @click="prev()" class="absolute left-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-lg">chevron_left</span>
                            </button>
                            <button @click="next()" class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-black/30 hover:bg-black/50 text-white flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-lg">chevron_right</span>
                            </button>
                            @endif
                        </div>
                    @endif

                @elseif($block['type'] === 'single_image')
                    @php
                        $singleLink = trim($data['link'] ?? '');
                        $isSingleExternal = !empty($singleLink) && (str_starts_with($singleLink, 'http://') || str_starts_with($singleLink, 'https://'));
                    @endphp
                    @if(!empty($data['image_url']))
                        @if(!empty($singleLink))
                            <a href="{{ $singleLink }}" @if($isSingleExternal) target="_blank" rel="noopener noreferrer" @endif class="block w-full rounded-2xl overflow-hidden shadow-sm hover:opacity-95 transition-opacity">
                                <img src="{{ $data['image_url'] }}" alt="Promo Banner" class="w-full h-auto object-cover max-h-[400px]">
                            </a>
                        @else
                            <div class="w-full rounded-2xl overflow-hidden shadow-sm">
                                <img src="{{ $data['image_url'] }}" alt="Promo Banner" class="w-full h-auto object-cover max-h-[400px]">
                            </div>
                        @endif
                    @endif

                @elseif($block['type'] === 'text')
                    @php 
                        $textContent = trim($data['text'] ?? '');
                    @endphp
                    @if(!empty($textContent))
                        @php 
                            $alignClass = match($data['align'] ?? 'center') {
                                'left' => 'text-left',
                                'right' => 'text-right',
                                default => 'text-center'
                            };
                            $sizeClass = match($data['size'] ?? 'md') {
                                'sm' => 'text-sm',
                                'lg' => 'text-xl',
                                default => 'text-base'
                            };
                        @endphp
                        <div class="w-full px-4 py-2 {{ $alignClass }}">
                            <p class="text-slate-700 dark:text-slate-200 {{ $sizeClass }} font-medium whitespace-pre-line">{{ $textContent }}</p>
                        </div>
                    @endif

                @elseif($block['type'] === 'flash_sale')
                    @php 
                        $endDate = $data['end_date'] ?? '';
                        $selectedIds = $data['product_ids'] ?? [];

                        if (!isset($flashProducts) || $flashProducts->isEmpty()) {
                            if (!empty($selectedIds)) {
                                $flashProducts = \App\Models\Product::whereIn('id', $selectedIds)
                                    ->where('store_id', $store->id)
                                    ->where('is_active', true)
                                    ->with(['images' => fn($q) => $q->where('is_main', true)->limit(1)])
                                    ->get()
                                    ->sortBy(fn($p) => array_search($p->id, $selectedIds))
                                    ->take(8)
                                    ->values();
                            } else {
                                $flashProducts = \App\Models\Product::where('store_id', $store->id)
                                    ->where('is_active', true)
                                    ->whereNotNull('discount_price')
                                    ->where('discount_price', '>', 0)
                                    ->with(['images' => fn($q) => $q->where('is_main', true)->limit(1)])
                                    ->take(4)
                                    ->get();
                                if ($flashProducts->isEmpty()) {
                                    $flashProducts = \App\Models\Product::where('store_id', $store->id)
                                        ->where('is_active', true)
                                        ->with(['images' => fn($q) => $q->where('is_main', true)->limit(1)])
                                        ->latest()
                                        ->take(4)
                                        ->get();
                                }
                            }
                        }
                    @endphp
                    <div class="w-full relative" 
                         x-data="{
                            rawEnd: '{{ $endDate }}',
                            end: 0,
                            now: new Date().getTime(),
                            timeLeft: 0,
                            days: 0, hours: 0, minutes: 0, seconds: 0,
                            init() {
                                this.end = this.rawEnd ? new Date(this.rawEnd).getTime() : new Date().getTime() + 86400000;
                                if(isNaN(this.end)) this.end = new Date().getTime() + 86400000;
                                
                                this.update();
                                setInterval(() => this.update(), 1000);
                            },
                            update() {
                                this.now = new Date().getTime();
                                this.timeLeft = this.end - this.now;
                                if(this.timeLeft > 0) {
                                    this.days = Math.floor(this.timeLeft / (1000 * 60 * 60 * 24));
                                    this.hours = Math.floor((this.timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    this.minutes = Math.floor((this.timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                    this.seconds = Math.floor((this.timeLeft % (1000 * 60)) / 1000);
                                } else {
                                    this.days = 0; this.hours = 0; this.minutes = 0; this.seconds = 0;
                                }
                            }
                         }">

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <h3 class="font-extrabold italic text-xl tracking-wide text-rose-500 drop-shadow-sm flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[24px]">bolt</span> FLASH SALE
                                </h3>
                                
                                <div class="flex items-center gap-1 text-sm font-bold text-white">
                                    <span x-show="days > 0" x-text="days + 'h'" class="bg-slate-800 px-1.5 py-0.5 rounded"></span>
                                    <span x-text="String(hours).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                                    <span class="text-slate-800">:</span>
                                    <span x-text="String(minutes).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                                    <span class="text-slate-800">:</span>
                                    <span x-text="String(seconds).padStart(2, '0')" class="bg-slate-800 px-1.5 py-0.5 rounded">00</span>
                                </div>
                            </div>
                            
                            <a href="#" class="text-xs font-bold text-rose-500 hover:text-rose-600 hover:underline flex items-center gap-0.5">
                                Lihat Semua <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($flashProducts as $product)
                                <a href="{{ route('products.show', $product->slug) }}" class="bg-white rounded-xl overflow-hidden hover:shadow-xl hover:-translate-y-1 group border border-slate-100 transition-all">
                                    <div class="aspect-square w-full bg-slate-50 relative overflow-hidden">
                                        @if($product->images->count() > 0)
                                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                            </div>
                                        @endif
                                        @if($product->discount_price)
                                        <div class="absolute top-0 right-0 bg-rose-100 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded-bl-lg border-b border-l border-rose-200">
                                            -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                        </div>
                                        @endif
                                        <div class="absolute bottom-0 left-0 right-0 bg-slate-900/5 backdrop-blur-sm">
                                            <div class="bg-rose-600 text-white text-center text-[10px] font-bold py-1">
                                                🔥 STOK TERBATAS
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-3">
                                        <h3 class="text-slate-800 text-[11px] md:text-xs font-bold line-clamp-2 leading-tight group-hover:text-sky-500 transition-colors h-8">{{ $product->name }}</h3>
                                        @if($product->discount_price)
                                            <div class="font-black text-rose-600 text-sm md:text-base mt-2">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                            <div class="text-[9px] md:text-[10px] text-slate-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        @else
                                            <div class="font-black text-rose-600 text-sm md:text-base mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                @elseif($block['type'] === 'voucher')
                    @php 
                        $selectedCampaignIds = $data['campaign_ids'] ?? [];
                        $blockCampaigns = $campaigns ?? collect();
                        if (!empty($selectedCampaignIds) && is_array($selectedCampaignIds)) {
                            $blockCampaigns = $blockCampaigns->whereIn('id', array_map('intval', $selectedCampaignIds));
                        }
                    @endphp
                    @if($blockCampaigns->isNotEmpty())
                        <!-- REAL STORE CAMPAIGNS / VOUCHERS -->
                        <div class="w-full p-4 sm:p-5 rounded-2xl bg-surface-container border border-outline-variant/60">
                            <div class="flex items-center justify-between gap-3 mb-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-primary text-white flex items-center justify-center shadow-xs">
                                        <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                                    </div>
                                    <div>
                                        <h2 class="text-sm sm:text-base font-black text-on-surface flex items-center gap-1.5">
                                            <span>Kupon &amp; Voucher Toko</span>
                                            <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-extrabold">{{ $blockCampaigns->count() }} Tersedia</span>
                                        </h2>
                                        <p class="text-[11px] text-on-surface-variant font-medium">Salin kode voucher di bawah dan gunakan saat checkout untuk klaim potongan harga</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                                @foreach($blockCampaigns as $campaign)
                                    <x-voucher-card :campaign="$campaign" mode="browse" />
                                @endforeach
                            </div>
                        </div>
                    @elseif(count($vouchers) > 0)
                        <!-- CUSTOM CONFIGURED VOUCHERS -->
                        <div class="w-full">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-rose-500">confirmation_number</span>
                                <h2 class="text-lg font-bold text-slate-800 dark:text-white">Kupon Tersedia</h2>
                            </div>
                            <div class="flex gap-4 overflow-x-auto hide-scrollbar pb-2 snap-x snap-mandatory">
                                @foreach($vouchers as $v)
                                    @php
                                        $theme = $v['theme'] ?? 'rose';
                                        $themeColors = match($theme) {
                                            'emerald' => ['bg' => 'from-emerald-50 to-teal-50', 'border' => 'border-emerald-200', 'main' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'hover' => 'hover:bg-emerald-600'],
                                            'amber' => ['bg' => 'from-amber-50 to-yellow-50', 'border' => 'border-amber-200', 'main' => 'bg-amber-500', 'text' => 'text-amber-600', 'hover' => 'hover:bg-amber-600'],
                                            'sky' => ['bg' => 'from-sky-50 to-blue-50', 'border' => 'border-sky-200', 'main' => 'bg-sky-500', 'text' => 'text-sky-600', 'hover' => 'hover:bg-sky-600'],
                                            'violet' => ['bg' => 'from-violet-50 to-purple-50', 'border' => 'border-violet-200', 'main' => 'bg-violet-500', 'text' => 'text-violet-600', 'hover' => 'hover:bg-violet-600'],
                                            default => ['bg' => 'from-rose-50 to-sky-50', 'border' => 'border-rose-200', 'main' => 'bg-rose-500', 'text' => 'text-rose-600', 'hover' => 'hover:bg-rose-600']
                                        };
                                    @endphp
                                    <div class="snap-start shrink-0 w-[280px] h-24 bg-gradient-to-br {{ $themeColors['bg'] }} border {{ $themeColors['border'] }} rounded-xl flex items-center relative overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                        <div class="w-8 h-full {{ $themeColors['main'] }} border-r-2 border-dashed border-white/50 flex flex-col items-center justify-center text-white text-[8px] font-bold tracking-widest" style="writing-mode: vertical-rl; transform: rotate(180deg);">KUPON</div>
                                        <div class="p-4 flex-1">
                                            <h4 class="font-bold {{ $themeColors['text'] }} text-sm mb-0.5 line-clamp-1">{{ $v['title'] ?? 'Diskon' }}</h4>
                                            <p class="text-[10px] text-slate-500 line-clamp-1">{{ $v['subtitle'] ?? '' }}</p>
                                        </div>
                                        <div class="mr-3">
                                            <button class="px-4 py-1.5 {{ $themeColors['main'] }} text-white text-[10px] font-bold rounded-lg {{ $themeColors['hover'] }} shadow-sm transition-colors" onclick="alert('Kode Kupon Berhasil Diklaim!')">KLAIM</button>
                                        </div>
                                        <div class="absolute -top-3 -right-3 w-6 h-6 bg-[#f8fafc] rounded-full shadow-inner border border-slate-200/50"></div>
                                        <div class="absolute -bottom-3 -right-3 w-6 h-6 bg-[#f8fafc] rounded-full shadow-inner border border-slate-200/50"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- Placeholder when no coupons yet -->
                        <div class="w-full p-4 rounded-2xl bg-surface-container border border-outline-variant/60 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[20px]">confirmation_number</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-800 dark:text-white">Kupon & Voucher Belanja</h4>
                                <p class="text-[11px] text-slate-400">Nantikan voucher promo dan diskon spesial dari toko kami segera.</p>
                            </div>
                        </div>
                    @endif

                @elseif($block['type'] === 'products')
                    @php 
                        $count = (int)($data['count'] ?? 8);
                        $displayProducts = $products->take($count);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-xl font-bold text-slate-800 border-l-4 border-sky-500 pl-3">
                                {{ ($data['type'] ?? 'latest') === 'bestseller' ? 'Produk Terlaris' : 'Pilihan Produk' }}
                            </h2>
                            <a href="#" class="text-sm text-sky-500 font-bold hover:underline">Lihat Semua</a>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                            @forelse($displayProducts as $product)
                                @php
                                    $soldCount = (int)($product->sales_count ?? ($product->orders_count ?? 0));
                                    $displaySold = $soldCount;
                                    $avgRating = (float)$product->effective_rating;
                                    $shortDesc = Str::limit(strip_tags($product->description ?? ''), 55);
                                    $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
                                @endphp
                                <!-- Product Card -->
                                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-sky-500 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col relative">
                                    <div class="aspect-square w-full bg-slate-50 relative overflow-hidden">
                                        @if($product->images->count() > 0)
                                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
                                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                                            </div>
                                        @endif
                                        @if($hasDiscount)
                                            <div class="absolute top-2 right-2 bg-rose-500 text-white font-black text-[10px] px-2 py-0.5 rounded-md shadow-sm">
                                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                            </div>
                                        @endif

                                        @if($product->category)
                                            <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-xs text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                                {{ $product->category->name }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3.5 flex flex-col flex-1">
                                        <h3 class="font-bold text-slate-800 text-xs md:text-sm line-clamp-2 mb-1.5 group-hover:text-sky-500 transition-colors leading-snug">{{ $product->name }}</h3>
                                        
                                        @if(!empty($shortDesc))
                                            <p class="text-[11px] text-slate-500 line-clamp-2 mb-3 leading-relaxed">
                                                {{ $shortDesc }}
                                            </p>
                                        @endif

                                        <div class="mt-auto pt-2 border-t border-slate-100 dark:border-slate-800">
                                            @if($hasDiscount)
                                                <div class="text-[10px] text-slate-400 line-through mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                                <div class="font-black text-sky-500 text-sm md:text-base">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                            @else
                                                <div class="font-black text-sky-500 text-sm md:text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                            @endif
                                            
                                            <div class="flex items-center justify-between gap-1 mt-2 text-[10px] text-slate-500 font-medium">
                                                @if($avgRating > 0)
                                                <span class="flex items-center gap-0.5 text-amber-500 font-bold">
                                                    <span class="material-symbols-outlined text-[13px] fill-current">star</span>
                                                    {{ number_format($avgRating, 1) }}
                                                </span>
                                                @else
                                                <span class="text-[9px] px-1.5 py-0.5 bg-primary/10 text-primary font-bold rounded">
                                                    Baru
                                                </span>
                                                @endif
                                                <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded text-[10px] font-bold">
                                                    {{ $displaySold }} Terjual
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="col-span-full py-12 text-center text-slate-400 text-sm border-2 border-dashed border-slate-200 rounded-2xl">
                                    <span class="material-symbols-outlined text-4xl mb-2 opacity-50 block">inventory_2</span>
                                    Belum ada produk di toko ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
                
            @endforeach
        @endif
        </div>
        
        <!-- Tab 2: Semua Produk -->
        <div x-show="activeTab === 'produk'" x-cloak>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-on-surface border-l-4 border-primary pl-3">Semua Produk</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @forelse($products as $product)
                @php
                    $soldCount = (int)($product->sales_count ?? ($product->orders_count ?? 0));
                    $displaySold = $soldCount;
                    $avgRating = (float)$product->effective_rating;
                    $shortDesc = Str::limit(strip_tags($product->description ?? ''), 55);
                    $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
                @endphp
                <a href="{{ route('products.show', $product->slug) }}" class="group bg-white dark:bg-surface-container border border-outline-variant hover:border-primary rounded-xl overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col relative">
                    <div class="aspect-square w-full bg-slate-50 relative overflow-hidden">
                        @if($product->images->count() > 0)
                            @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                            <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                            </div>
                        @endif
                        
                        @if($hasDiscount)
                            <div class="absolute top-2 right-2 bg-rose-500 text-white font-black text-[10px] px-2 py-0.5 rounded-md shadow-sm">
                                -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                            </div>
                        @endif

                        @if($product->category)
                            <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-xs text-white text-[9px] font-semibold px-2 py-0.5 rounded">
                                {{ $product->category->name }}
                            </div>
                        @endif
                    </div>
                    <div class="p-3.5 flex flex-col flex-1">
                        <h3 class="font-bold text-slate-800 text-xs md:text-sm line-clamp-2 mb-1.5 group-hover:text-primary transition-colors leading-snug">{{ $product->name }}</h3>
                        
                        @if(!empty($shortDesc))
                            <p class="text-[11px] text-slate-500 line-clamp-2 mb-3 leading-relaxed">
                                {{ $shortDesc }}
                            </p>
                        @endif

                        <div class="mt-auto pt-2 border-t border-slate-100 dark:border-slate-800">
                            @if($hasDiscount)
                                <div class="text-[10px] text-slate-400 line-through mb-0.5">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                <div class="font-black text-primary text-sm md:text-base">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                            @else
                                <div class="font-black text-primary text-sm md:text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                            
                            <div class="flex items-center justify-between gap-1 mt-2 text-[10px] text-on-surface-variant font-medium">
                                @if($avgRating > 0)
                                <span class="flex items-center gap-0.5 text-amber-500 font-bold">
                                    <span class="material-symbols-outlined text-[13px] fill-current">star</span>
                                    {{ number_format($avgRating, 1) }}
                                </span>
                                @else
                                <span class="text-[9px] px-1.5 py-0.5 bg-primary/10 text-primary font-bold rounded">
                                    Baru
                                </span>
                                @endif
                                <span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-1.5 py-0.5 rounded text-[10px] font-bold">
                                    {{ $displaySold }} Terjual
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">Belum ada produk.</div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>


        
        <!-- Tab 3: Kategori -->
        <div x-show="activeTab === 'kategori'" x-cloak>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-on-surface border-l-4 border-primary pl-3">Kategori Produk</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($categories as $category)
                <a href="?category={{ $category->id }}" class="bg-white border border-slate-100 shadow-sm hover:shadow-md hover:border-primary transition-all p-4 rounded-xl flex flex-col items-center justify-center gap-3 group text-center h-32">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <span class="font-bold text-slate-700 text-sm group-hover:text-primary">{{ $category->name }}</span>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">Belum ada kategori.</div>
                @endforelse
            </div>
        </div>
        
        <!-- Tab: Portofolio Proyek -->
        @if($store->isPro())
        <div x-show="activeTab === 'proyek'" x-cloak class="space-y-6">
            <h2 class="text-xl font-bold text-on-surface border-l-4 border-amber-500 pl-3">Portofolio Proyek</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($store->projects()->latest()->get() as $project)
                <a href="{{ route('projects.show', $project->slug) }}" class="group block bg-surface-container rounded-2xl overflow-hidden border border-outline-variant hover:border-amber-400 transition-colors shadow-sm">
                    @if($project->thumbnail)
                    <img src="{{ asset('storage/'.$project->thumbnail) }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                    <div class="w-full h-48 bg-slate-100 flex items-center justify-center text-slate-300 group-hover:scale-105 transition-transform duration-300">
                        <span class="material-symbols-outlined text-5xl">image</span>
                    </div>
                    @endif
                    <div class="p-4 bg-surface-container relative z-10">
                        <h3 class="font-bold text-on-surface group-hover:text-amber-600 transition-colors line-clamp-1">{{ $project->title }}</h3>
                        <p class="text-xs text-on-surface-variant mt-1 line-clamp-2">{{ $project->short_description ?? 'Lihat detail proyek ini.' }}</p>
                    </div>
                </a>
                @empty
                <div class="col-span-full py-10 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl opacity-50 block mb-2">work_off</span>
                    <p>Toko ini belum menambahkan portofolio proyek.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Tab 4: Profil Toko -->
        <div x-show="activeTab === 'profil'" x-cloak>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Profil Toko</h2>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="md:col-span-2 space-y-6">
                        <div>
                            <h3 class="font-bold text-slate-700 mb-2 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-xl">storefront</span> Deskripsi Toko</h3>
                            <p class="text-slate-600 leading-relaxed">{{ $store->description ?? 'Toko ini belum menambahkan deskripsi.' }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-bold text-slate-700 mb-2 flex items-center gap-2"><span class="material-symbols-outlined text-primary text-xl">policy</span> Kebijakan Toko</h3>
                            <p class="text-slate-600 leading-relaxed">{{ $store->policy ?? 'Tidak ada kebijakan khusus yang ditetapkan.' }}</p>
                        </div>

                        {{-- Tautan Media Sosial Resmi Toko --}}
                        @php $socialLinks = is_array($store->social_links) ? $store->social_links : []; @endphp
                        @if(count($socialLinks) > 0)
                        <div>
                            <h3 class="font-bold text-slate-700 mb-3 flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-xl">share</span> Media Sosial & Kontak Resmi Toko
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                @foreach($socialLinks as $soc)
                                    @php
                                        $socPlatform = strtolower($soc['platform'] ?? 'custom');
                                        $socName = $soc['name'] ?? ucfirst($socPlatform);
                                        $socUrl = $soc['url'] ?? '#';
                                    @endphp
                                    <a href="{{ $socUrl }}" target="_blank" rel="noopener noreferrer" class="p-3 rounded-xl border border-slate-200 hover:border-primary bg-slate-50/50 hover:bg-white transition-all flex items-center gap-3 group">
                                        <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-primary flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-110 transition-transform">
                                            <x-store-social-icon :platform="$socPlatform" class="w-4 h-4" />
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-800 group-hover:text-primary transition-colors truncate">{{ $socName }}</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $socUrl }}</div>
                                        </div>
                                        <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-primary group-hover:translate-x-0.5 transition-all">open_in_new</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-100 h-fit space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-slate-400">calendar_month</span>
                            <div>
                                <div class="text-xs text-slate-500">Bergabung Sejak</div>
                                <div class="font-bold text-slate-700">{{ $store->created_at->translatedFormat('F Y') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-slate-400">inventory_2</span>
                            <div>
                                <div class="text-xs text-slate-500">Total Produk</div>
                                <div class="font-bold text-slate-700">{{ $products->total() }} Produk</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-slate-400">group</span>
                            <div>
                                <div class="text-xs text-slate-500">Total Pengikut</div>
                                <div class="font-bold text-slate-700"><span x-text="followersCount"></span> Orang</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</main>
@endsection
