@extends('layouts.store')

@section('title', $store->name . ' — Profile & Portofolio')
@section('meta_description', Str::limit(strip_tags($store->description ?: 'Kunjungi profil resmi ' . $store->name), 160))
@section('canonical_url', url('/' . $store->slug))

{{-- Hilangkan top navbar (pencarian, icon shop, avatar profile) dan footer marketplace pada mode BioLink --}}
@section('hide_store_navbar', true)
@section('hide_store_footer', true)

@section('content')
<div class="min-h-screen bg-[#f8fafc] dark:bg-[#0d1117] text-slate-900 dark:text-slate-100 flex flex-col"
     x-data="{
         isFollowing: {{ $isFollowing ? 'true' : 'false' }},
         followersCount: {{ $store->followers()->count() }},
         copiedLink: false,
         copyProfileUrl() {
             if (navigator.share) {
                 navigator.share({
                     title: '{{ addslashes($store->name) }}',
                     text: '{{ addslashes(Str::limit(strip_tags($store->description ?: 'Kunjungi profil resmi ' . $store->name), 100)) }}',
                     url: window.location.href
                 }).catch(() => {});
             } else {
                 navigator.clipboard.writeText(window.location.href);
                 this.copiedLink = true;
                 setTimeout(() => this.copiedLink = false, 2500);
             }
         },
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

    {{-- 1. Full-Bleed Profile Header (Banner edge-to-edge nembus ke status bar HP, proporsional, tanpa border radius kaku) --}}
    @include('store._partials._profile_header')

    {{-- 2. Bio Links & Showcase Container --}}
    <div class="w-full max-w-xl md:max-w-2xl lg:max-w-3xl mx-auto px-3.5 sm:px-6 pb-12 flex-1">
        @include('store._partials._profile_links', ['isSidebar' => false])

        {{-- Minimal Footer Bebas AI Slop --}}
        <div class="text-center pt-10 pb-6 text-[11px] text-slate-400 dark:text-slate-600 font-medium">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors inline-flex items-center gap-1 font-semibold">
                <span>Ditenagai oleh {{ config('app.name', 'Rhantech') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection
