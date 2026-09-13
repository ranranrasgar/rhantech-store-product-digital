{{-- Partial: Profile Header (Avatar, Nama, Bio, Sosmed) --}}
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
        $headerBgStyle = "background-image: url('{$banner}'); background-size: cover; background-position: center;";
    } else {
        $headerBgStyle = "background: linear-gradient(135deg, #1e3a5f 0%, #0f766e 50%, #1e40af 100%);";
    }
    $socialLinks = is_array($store->social_links) ? $store->social_links : [];
@endphp

{{-- Banner & Avatar Container --}}
<div class="relative w-full">
    {{-- Banner Background (overflow-hidden applies only to the background itself) --}}
    <div class="relative w-full h-44 sm:h-56 md:h-64 lg:h-72 overflow-hidden rounded-b-2xl shadow-sm transition-all duration-300"
         style="{{ $headerBgStyle }}">
        <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/45"></div>
    </div>

    {{-- Avatar (completely outside overflow-hidden, floats seamlessly over the banner bottom edge) --}}
    <div class="relative flex justify-center -mt-14 sm:-mt-16 md:-mt-20 z-20">
        <div class="relative">
            <div class="w-24 h-24 sm:w-28 sm:h-28 md:w-36 md:h-36 rounded-full border-4 {{ $store->isPro() ? 'border-amber-400 ring-4 ring-amber-500/30' : 'border-white dark:border-[#0d1117]' }} shadow-xl overflow-hidden bg-white dark:bg-[#0d1117] transition-all">
                @if($store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" class="w-full h-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($store->name) }}&background=0284c7&color=fff&size=140" alt="{{ $store->name }}" class="w-full h-full object-cover">
                @endif
            </div>
            @if($store->isPro())
                <span class="absolute -bottom-2.5 left-1/2 -translate-x-1/2 bg-slate-900 text-amber-400 font-black text-[11px] sm:text-xs px-2.5 sm:px-3 py-0.5 rounded-full shadow-lg border-2 border-amber-400 flex items-center gap-1 tracking-wider uppercase whitespace-nowrap z-30">
                    <span class="material-symbols-outlined text-[13px] sm:text-[15px] text-amber-400 font-bold">stars</span>
                    <span>PRO</span>
                </span>
            @endif
        </div>
    </div>
</div>

{{-- Info di bawah banner --}}
<div class="pt-4 pb-6 px-4 sm:px-8 text-center">
    <h1 class="text-xl sm:text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center justify-center gap-2 flex-wrap">
        <span>{{ $store->name }}</span>
        
        <span class="w-5 h-5 sm:w-6 sm:h-6 bg-sky-500 text-white rounded-full inline-flex items-center justify-center shadow-xs shrink-0" title="Terverifikasi">
            <span class="material-symbols-outlined text-[13px] sm:text-[15px]">verified</span>
        </span>
    </h1>

    @if(!empty($store->description))
        <p class="text-xs sm:text-sm md:text-base text-slate-500 dark:text-slate-400 mt-2 max-w-2xl mx-auto leading-relaxed">
            {{ $store->description }}
        </p>
    @endif

    {{-- Stats Pills --}}
    <div class="flex items-center justify-center gap-2 mt-3 flex-wrap text-[11px]">
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold">
            <span class="material-symbols-outlined text-[14px] text-amber-500">star</span>
            4.8
        </span>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold">
            <span class="material-symbols-outlined text-[14px]">inventory_2</span>
            {{ $products->total() }} Produk
        </span>
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold"
              x-text="followersCount + ' Pengikut'">
            {{ $store->followers()->count() }} Pengikut
        </span>
    </div>

    {{-- Sosmed Icons --}}
    @if(count($socialLinks) > 0)
    <div class="flex items-center justify-center gap-2 mt-4 flex-wrap">
        @foreach($socialLinks as $soc)
            @php
                $socPlatform = strtolower($soc['platform'] ?? 'custom');
                $socUrl = $soc['url'] ?? '#';
                $bgClass = match($socPlatform) {
                    'instagram' => 'bg-gradient-to-tr from-[#f09433] via-[#dc2743] to-[#bc1888] text-white',
                    'tiktok' => 'bg-black text-white',
                    'whatsapp' => 'bg-[#25D366] text-white',
                    'youtube' => 'bg-[#FF0000] text-white',
                    'facebook' => 'bg-[#1877F2] text-white',
                    'x', 'twitter' => 'bg-black text-white',
                    'telegram' => 'bg-[#229ED9] text-white',
                    'github' => 'bg-[#24292e] text-white',
                    default => 'bg-slate-700 text-white'
                };
            @endphp
            <a href="{{ $socUrl }}" target="_blank" rel="noopener noreferrer"
               class="w-9 h-9 rounded-full flex items-center justify-center shadow-md hover:scale-110 active:scale-95 transition-transform {{ $bgClass }}">
                <x-store-social-icon :platform="$socPlatform" class="w-4 h-4" />
            </a>
        @endforeach
    </div>
    @endif

    {{-- Action Buttons (Follow, Chat & Affiliate) --}}
    <div class="flex items-center justify-center gap-2.5 mt-4 flex-wrap">
        @if(!auth()->check() || auth()->id() !== $store->user_id)
            <button type="button"
                    @click="@auth window.dispatchEvent(new CustomEvent('open-chat-with-store', { detail: { store_id: {{ $store->id }}, store_name: '{{ addslashes($store->name) }}', store_slug: '{{ $store->slug }}', store_logo: '{{ $store->logo ? asset('storage/' . $store->logo) : '' }}' } })) @else window.location.href = '{{ route('login') }}' @endauth"
                    class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center gap-1.5 border border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined text-[17px]">chat</span> Chat
            </button>
            <button type="button" @click="toggleFollow()"
                    :class="isFollowing ? 'bg-slate-100 dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200' : 'bg-primary border-primary text-white'"
                    class="px-4 py-2 rounded-xl border text-xs font-bold transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[17px]" x-text="isFollowing ? 'check' : 'person_add'">person_add</span>
                <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti'">Ikuti</span>
            </button>
        @endif

        @if(isset($myAffiliateLink))
            <div x-data="{ copied: false }" class="inline-flex">
                <button type="button"
                        @click="navigator.clipboard.writeText('{{ $myAffiliateLink }}'); copied = true; setTimeout(() => copied = false, 2500)"
                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm"
                        title="Dapatkan komisi {{ (float)($store->default_affiliate_commission ?? 10) }}% per penjualan">
                    <span class="material-symbols-outlined text-[17px]" x-text="copied ? 'check_circle' : 'attach_money'">attach_money</span>
                    <span x-text="copied ? 'Link Disalin!' : 'Link Afiliasi ({{ (float)($store->default_affiliate_commission ?? 10) }}%)'">Link Afiliasi</span>
                </button>
            </div>
        @endif
    </div>
</div>
