@extends('layouts.store')

@section('title', $store->name . ' — Profile')
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
                 this.isFollowing = data.following;
                 this.followersCount = this.isFollowing ? this.followersCount + 1 : this.followersCount - 1;
             });
             @else window.location.href = '{{ route('login') }}' @endauth
         }
     }">

    {{-- Profile Card (max-width dipersempit seperti Linktree) --}}
    <div class="max-w-md mx-auto">

        {{-- Header: Banner + Avatar + Info --}}
        @include('store._partials._profile_header')

        {{-- Link Buttons --}}
        @include('store._partials._profile_links')



        {{-- Footer --}}
        <div class="text-center py-6 text-[11px] text-slate-400 dark:text-slate-600">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors font-semibold">
                ⚡ Powered by Rhantech
            </a>
        </div>
    </div>
</div>
@endsection
