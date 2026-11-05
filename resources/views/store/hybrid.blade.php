@extends('layouts.store')

@section('title', $store->name)
@section('meta_description', $store->description ?? 'Kunjungi toko ' . $store->name)

@section('content')
@php
    $banner = $store->banner;
    if (!$banner) {
        $coverStyle = 'background: linear-gradient(120deg, var(--brand), color-mix(in srgb, var(--brand) 55%, #0f172a));';
    } elseif (preg_match('/^(linear-gradient|radial-gradient|#|rgb)/', $banner)) {
        $coverStyle = "background: {$banner};";
    } else {
        $coverStyle = "background-image: url('{$banner}');";
    }

    $socialLinks = is_array($store->social_links) ? $store->social_links : [];
    $catalogShown = false;

    $hasVoucherBlock = collect($appearance)->contains(fn ($block) => ($block['type'] ?? '') === 'voucher');
    $voucherPlacement = is_array($store->appearance_data ?? null) ? ($store->appearance_data['voucher_placement'] ?? []) : [];
    $vpHeader = $voucherPlacement['header'] ?? '';
    $headerCampaigns = $campaigns ?? collect();
    if ($vpHeader !== '' && $vpHeader !== 'none') {
        $filtered = $campaigns->where('id', (int) $vpHeader);
        if ($filtered->isNotEmpty()) {
            $headerCampaigns = $filtered;
        }
    }
@endphp

@include('store._partials._brand_styles')

<div class="bp"
     style="--brand: {{ $store->brandColor() }}; --brand-ink: {{ $store->brandInk() }}; --color-primary: {{ $store->brandColor() }};"
     x-data="{
         isFollowing: {{ $isFollowing ? 'true' : 'false' }},
         followersCount: {{ $store->followers()->count() }},
         copied: false,
         toggleFollow() {
             @auth
             fetch('{{ route('store.follow', $store->id) }}', {
                 method: 'POST',
                 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
             }).then(r => r.json()).then(data => {
                 if (!data) return;
                 this.isFollowing = data.following;
                 this.followersCount = data.followers_count !== undefined
                     ? data.followers_count
                     : (this.isFollowing ? this.followersCount + 1 : Math.max(0, this.followersCount - 1));
             });
             @else
             window.location.href = '{{ route('login') }}';
             @endauth
         },
         share() {
             const url = @js(route('store.show', $store->slug));
             if (navigator.share) {
                 navigator.share({ title: @js($store->name), url }).catch(() => {});
                 return;
             }
             navigator.clipboard.writeText(url).then(() => {
                 this.copied = true;
                 setTimeout(() => this.copied = false, 2000);
             });
         },
     }">

    <div class="bp-cover" style="{{ $coverStyle }}" role="img" aria-label="Sampul {{ $store->name }}"></div>

    <div class="bp-shell">
        {{-- Identitas brand: kolom menempel di desktop --}}
        <aside class="bp-side">
            <div class="bp-card">
                <div class="bp-avatar {{ $store->isPro() ? 'is-pro' : '' }}">
                    @if($store->logo)
                        <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" width="96" height="96">
                    @else
                        <span>{{ strtoupper(mb_substr($store->name, 0, 2)) }}</span>
                    @endif
                </div>

                <h1 class="bp-name">
                    {{ $store->name }}
                    <i class="ph-fill ph-seal-check" title="Terverifikasi"></i>
                    @if($store->isPro())
                        <span class="bp-pro">PRO</span>
                    @endif
                </h1>

                @if(!empty($store->description))
                    <p class="bp-bio">{{ $store->description }}</p>
                @endif

                <ul class="bp-meta">
                    @if($storeReviewCount > 0)
                        <li><i class="ph ph-star"></i> <b>{{ number_format($storeRating, 1) }}</b> ({{ $storeReviewCount }})</li>
                    @endif
                    <li><b>{{ $products->total() }}</b> produk</li>
                    <li><b x-text="followersCount">{{ $store->followers()->count() }}</b> pengikut</li>
                </ul>

                <div class="bp-actions">
                    @if(auth()->id() === $store->user_id)
                        <a href="{{ route('tenant.appearance.index') }}" class="bp-btn bp-btn-primary">
                            <i class="ph ph-paint-brush"></i> Atur tampilan
                        </a>
                    @else
                        <button type="button" @click="toggleFollow()" class="bp-btn" :class="isFollowing ? 'bp-btn-ghost' : 'bp-btn-primary'">
                            <i class="ph" :class="isFollowing ? 'ph-check' : 'ph-user-plus'"></i>
                            <span x-text="isFollowing ? 'Mengikuti' : 'Ikuti'">Ikuti</span>
                        </button>
                        <button type="button" class="bp-btn bp-btn-ghost"
                                @click="@auth window.dispatchEvent(new CustomEvent('open-chat-with-store', { detail: { store_id: {{ $store->id }}, store_name: '{{ addslashes($store->name) }}', store_slug: '{{ $store->slug }}', store_logo: '{{ $store->logo ? asset('storage/' . $store->logo) : '' }}' } })) @else window.location.href = '{{ route('login') }}' @endauth">
                            <i class="ph ph-chat-circle"></i> Chat
                        </button>
                    @endif
                    <button type="button" @click="share()" class="bp-btn bp-btn-ghost bp-btn-icon" aria-label="Bagikan halaman ini">
                        <i class="ph" :class="copied ? 'ph-check' : 'ph-share-network'"></i>
                    </button>
                </div>

                @if(isset($myAffiliateLink))
                    <div x-data="{ affiliateCopied: false }">
                        <button type="button" class="bp-btn bp-btn-ghost bp-btn-block"
                                @click="navigator.clipboard.writeText('{{ $myAffiliateLink }}'); affiliateCopied = true; setTimeout(() => affiliateCopied = false, 2500)">
                            <i class="ph" :class="affiliateCopied ? 'ph-check' : 'ph-coins'"></i>
                            <span x-text="affiliateCopied ? 'Link disalin' : 'Link afiliasi ({{ (float) ($store->default_affiliate_commission ?? 10) }}%)'">Link afiliasi</span>
                        </button>
                    </div>
                @endif

                @if(count($socialLinks) > 0)
                    <div class="bp-socials">
                        @foreach($socialLinks as $social)
                            @php $platform = strtolower($social['platform'] ?? 'custom'); @endphp
                            <a href="{{ $social['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['name'] ?? ucfirst($platform) }}">
                                <x-store-social-icon :platform="$platform" class="w-4 h-4" />
                            </a>
                        @endforeach
                    </div>
                @endif

                @if(isset($profileLinks) && $profileLinks->isNotEmpty())
                    @include('store._partials._brand_links')
                @endif
            </div>
        </aside>

        {{-- Etalase --}}
        <div class="bp-main">
            @if(!$hasVoucherBlock && $vpHeader !== 'none' && $headerCampaigns->isNotEmpty())
                <section class="bp-section">
                    <h2 class="bp-title">Voucher toko</h2>
                    <div class="bp-vouchers">
                        @foreach($headerCampaigns as $campaign)
                            <x-voucher-card :campaign="$campaign" mode="browse" />
                        @endforeach
                    </div>
                </section>
            @endif

            @foreach($appearance as $block)
                @if(($block['type'] ?? '') === 'products')
                    @if(!$catalogShown)
                        @php $catalogShown = true; @endphp
                        @include('store._partials._brand_catalog')
                    @endif
                @else
                    <section class="bp-section">
                        @include('store._partials._appearance_block', ['block' => $block, 'data' => $block['data'] ?? []])
                    </section>
                @endif
            @endforeach

            @unless($catalogShown)
                @include('store._partials._brand_catalog')
            @endunless

            <p class="bp-powered"><a href="{{ route('home') }}">Dibuat dengan Rhantech</a></p>
        </div>
    </div>
</div>
@endsection
