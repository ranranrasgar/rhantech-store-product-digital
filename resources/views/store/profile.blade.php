@extends('layouts.store')

@section('title', $store->name . ' — Profile & Portofolio')
@section('meta_description', $store->description ?? 'Kunjungi profil ' . $store->name)

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-[#0d1117]"
     x-data="{
         isFollowing: {{ $isFollowing ? 'true' : 'false' }},
         followersCount: {{ $store->followers()->count() }},
         toggleFollow() {
             @auth
             fetch('{{ route('store.follow', $store->id) }}', {
                 method: 'POST',
                 headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
             }).then(r => r.json()).then(data => {
                 if (!data) return;
                 this.isFollowing = data.following;
                 if (data.followers_count !== undefined) {
                     this.followersCount = data.followers_count;
                 } else {
                     this.followersCount = this.isFollowing ? this.followersCount + 1 : Math.max(0, this.followersCount - 1);
                 }
             });
             @else window.location.href = '{{ route('login') }}?redirect=' + encodeURIComponent(window.location.href); @endauth
         }
     }">

    {{-- Responsive Container: Mobile (max-w-md), iPad & Desktop (max-w-6xl/7xl Luas & Responsif) --}}
    <div class="w-full max-w-md md:max-w-5xl lg:max-w-6xl xl:max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-5 sm:py-8 transition-all">

        {{-- Header Card: Banner + Avatar + Info Profil Toko --}}
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-3xl shadow-xs overflow-hidden mb-6 sm:mb-8">
            @include('store._partials._profile_header')
        </div>

        {{-- Link & Portofolio Showcase (Tampil luas & grid 3-4 kolom di iPad & Desktop) --}}
        @include('store._partials._profile_links', ['isSidebar' => false])

        {{-- Footer --}}
        <div class="text-center py-8 text-[11px] text-slate-400 dark:text-slate-600">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors font-semibold">
                ⚡ Powered by Rhantech
            </a>
        </div>
    </div>
</div>
@endsection
