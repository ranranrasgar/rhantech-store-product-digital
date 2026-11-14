{{-- Partial: Profile Header (Full-bleed Banner, Avatar, Info Toko, Sosmed, Action Buttons) --}}
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
    } elseif (!empty($banner)) {
        $bannerUrl = str_starts_with($banner, 'http') || str_starts_with($banner, '/') 
            ? $banner 
            : asset('storage/' . $banner);
        $headerBgStyle = "background-image: url('{$bannerUrl}'); background-size: cover; background-position: center;";
    } else {
        $headerBgStyle = "background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);";
    }
    $socialLinks = is_array($store->social_links) ? $store->social_links : [];
    $totalProducts = $products->total();
    $followersCount = $store->followers()->count();
@endphp

{{-- 1. Full-Bleed Banner (Edge-to-edge, nembus ke status bar HP, tanpa border radius kaku) --}}
<div class="relative w-full">
    <div class="relative w-full h-48 sm:h-60 md:h-72 lg:h-80 overflow-hidden bg-slate-900 transition-all duration-300"
         style="{{ $headerBgStyle }}">
        {{-- Subtle contrast overlay --}}
        <div class="absolute inset-0 bg-gradient-to-b from-black/25 via-transparent to-black/45"></div>

        {{-- Floating Share Button (Cukup Icon Share di Pojok Atas) --}}
        <div class="absolute top-3.5 left-3.5 sm:top-4 sm:left-4 z-30">
            <button type="button"
                    @click="copyProfileUrl()"
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-black/40 hover:bg-black/60 active:scale-90 backdrop-blur-md text-white flex items-center justify-center border border-white/20 shadow-sm transition-all cursor-pointer"
                    title="Bagikan Tautan Profil">
                <span class="material-symbols-outlined text-[18px] sm:text-[20px]" x-text="copiedLink ? 'check' : 'share'">share</span>
            </button>
        </div>
    </div>

    {{-- Avatar (Floating overlap di garis bawah banner, proporsional) --}}
    <div class="relative flex justify-center -mt-12 sm:-mt-16 md:-mt-20 z-20">
        <div class="relative">
            <div class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 rounded-full border-4 {{ $store->isPro() ? 'border-amber-400 ring-4 ring-amber-500/20' : 'border-white dark:border-[#0d1117]' }} shadow-xl overflow-hidden bg-white dark:bg-[#0d1117] transition-all">
                @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0284c7&color=fff&size=140" alt="{{ $store->name }}" class="w-full h-full object-cover">
                @endif
            </div>
            @if($store->isPro())
                <span class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-900 text-amber-400 font-black text-[10px] sm:text-[11px] px-2.5 py-0.5 rounded-full shadow-md border border-amber-400/80 flex items-center gap-1 tracking-wider uppercase whitespace-nowrap z-30">
                    <span class="material-symbols-outlined text-[12px] text-amber-400 font-bold">verified</span>
                    <span>PRO</span>
                </span>
            @endif
        </div>
    </div>
</div>

{{-- 2. Info Profil Toko (Clean & Authentic, Bebas AI Slop) --}}
<div class="w-full max-w-xl mx-auto px-4 pt-3 pb-6 text-center">
    <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center justify-center gap-1.5 flex-wrap">
        <span>{{ $store->name }}</span>
        <span class="w-5 h-5 bg-sky-500 text-white rounded-full inline-flex items-center justify-center shadow-xs shrink-0" title="Terverifikasi">
            <span class="material-symbols-outlined text-[13px]">verified</span>
        </span>
    </h1>

    @if(!empty($store->description))
        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1.5 max-w-md mx-auto leading-relaxed">
            {{ $store->description }}
        </p>
    @endif

    {{-- Stats: Real & Authentic (No Fake AI-Slop Stars) --}}
    @if($totalProducts > 0 || $followersCount > 0 || ($storeRating > 0 && $storeReviewCount > 0))
        <div class="flex items-center justify-center gap-2 mt-2.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
            @if($storeRating > 0 && $storeReviewCount > 0)
                <span class="inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px] text-amber-500">star</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200">{{ number_format($storeRating, 1) }}</span>
                    <span class="text-[10px] text-slate-400">({{ $storeReviewCount }})</span>
                </span>
                <span class="text-slate-300 dark:text-slate-700">•</span>
            @endif
            @if($totalProducts > 0)
                <span><strong class="font-bold text-slate-800 dark:text-slate-200">{{ $totalProducts }}</strong> Produk</span>
            @endif
            @if($totalProducts > 0 && $followersCount > 0)
                <span class="text-slate-300 dark:text-slate-700">•</span>
            @endif
            <span><strong class="font-bold text-slate-800 dark:text-slate-200" x-text="followersCount">{{ $followersCount }}</strong> Pengikut</span>
        </div>
    @endif

    {{-- Sosmed Icons (Elegan & Netral) --}}
    @if(count($socialLinks) > 0)
    <div class="flex items-center justify-center gap-2 mt-3.5 flex-wrap">
        @foreach($socialLinks as $soc)
            @php
                $socPlatform = strtolower($soc['platform'] ?? 'custom');
                $socName = $soc['name'] ?? ucfirst($socPlatform);
                $socUrl = $soc['url'] ?? '#';
            @endphp
            <a href="{{ $socUrl }}" target="_blank" rel="noopener noreferrer" title="{{ $socName }}"
               class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center bg-slate-100 hover:bg-slate-900 dark:bg-slate-800 dark:hover:bg-white text-slate-700 hover:text-white dark:text-slate-300 dark:hover:text-slate-900 border border-slate-200/80 dark:border-slate-700/80 shadow-2xs hover:scale-105 active:scale-95 transition-all">
                <x-store-social-icon :platform="$socPlatform" class="w-3.5 h-3.5 sm:w-4 sm:h-4" />
            </a>
        @endforeach
    </div>
    @endif

    {{-- Action Buttons (Chat & Follow untuk pengunjung, Affiliate jika aktif) --}}
    @if((!auth()->check() || auth()->id() !== $store->user_id) || isset($myAffiliateLink))
    <div class="flex items-center justify-center gap-2 mt-4 flex-wrap">
        @if(!auth()->check() || auth()->id() !== $store->user_id)
            <button type="button"
                    @click="@auth window.dispatchEvent(new CustomEvent('open-chat-with-store', { detail: { store_id: {{ $store->id }}, store_name: '{{ addslashes($store->name) }}', store_slug: '{{ $store->slug }}', store_logo: '{{ $store->logo ? asset('storage/' . $store->logo) : '' }}' } })) @else window.location.href = '{{ route('login') }}' @endauth"
                    class="px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold transition-all flex items-center gap-1.5 border border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined text-[16px]">chat</span>
                <span>Chat</span>
            </button>
            <button type="button" @click="toggleFollow()"
                    :class="isFollowing ? 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200' : 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 border-slate-900 dark:border-white hover:opacity-90'"
                    class="px-3.5 py-1.5 rounded-xl border text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]" x-text="isFollowing ? 'check' : 'person_add'">person_add</span>
                <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti'">Ikuti</span>
            </button>
        @endif

        @if(isset($myAffiliateLink))
            <div x-data="{ copied: false }" class="inline-flex">
                <button type="button"
                        @click="navigator.clipboard.writeText('{{ $myAffiliateLink }}'); copied = true; setTimeout(() => copied = false, 2500)"
                        class="px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-2xs"
                        title="Dapatkan komisi {{ (float)($store->default_affiliate_commission ?? 10) }}% per penjualan">
                    <span class="material-symbols-outlined text-[16px]" x-text="copied ? 'check_circle' : 'attach_money'">attach_money</span>
                    <span x-text="copied ? 'Link Disalin!' : 'Link Afiliasi ({{ (float)($store->default_affiliate_commission ?? 10) }}%)'">Link Afiliasi</span>
                </button>
            </div>
        @endif
    </div>
    @endif
</div>
