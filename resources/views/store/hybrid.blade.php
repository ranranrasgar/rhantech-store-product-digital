@extends('layouts.store')

@section('title', $store->name)
@section('meta_description', $store->description ?? 'Kunjungi toko ' . $store->name)

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

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 py-5 sm:py-8 space-y-6 sm:space-y-8">

        {{-- 1. BAGIAN PROFIL TOKO & BIO LINKS (Full Width Card) --}}
        <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-3xl shadow-xs overflow-hidden">
            {{-- Header Profile (Banner, Logo, Nama, Sosmed, Chat/Follow) --}}
            @include('store._partials._profile_header')

            {{-- Bio Links / Portofolio Showcase (ala Lynk.id) --}}
            @if(isset($profileLinks) && $profileLinks->isNotEmpty())
                <div class="p-4 sm:p-6 border-t border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center justify-between mb-4 px-1">
                        <span class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px] text-primary">widgets</span>
                            Portofolio &amp; Layanan Pilihan
                        </span>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500">
                            {{ $profileLinks->count() }} Link
                        </span>
                    </div>
                    @include('store._partials._profile_links')
                </div>
            @endif
        </div>

        {{-- 2. PEMISAH ETALASE TOKO --}}
        <div class="flex items-center gap-4 my-6">
            <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800"></div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-slate-400 bg-white dark:bg-[#111726] px-4 py-1.5 rounded-full border border-slate-200 dark:border-[#222f49] shadow-xs">
                <span class="material-symbols-outlined text-[16px] text-primary">storefront</span>
                Katalog &amp; Etalase Produk
            </div>
            <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800"></div>
        </div>

        {{-- 3. BAGIAN ETALASE TOKO (Full Width) --}}
        <div class="space-y-6">

            {{-- Voucher Header Otomatis (Hanya tampil jika belum ada blok widget voucher di kanvas) --}}
            @php
                $hasVoucherBlock = collect($appearance)->contains(fn($b) => ($b['type'] ?? '') === 'voucher');
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
            @if(!$hasVoucherBlock && $vpHeader !== 'none' && $headerCampaigns->isNotEmpty())
            <div class="p-4 sm:p-5 rounded-2xl bg-surface-container border border-outline-variant/60">
                <div class="flex items-center gap-2 mb-3 text-sm font-black text-on-surface">
                    <span class="material-symbols-outlined text-[18px]">confirmation_number</span>
                    Kupon &amp; Voucher Toko
                    <span class="px-2 py-0.5 rounded-full bg-primary/10 text-primary text-[10px] font-extrabold">{{ $headerCampaigns->count() }} Tersedia</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5">
                    @foreach($headerCampaigns as $campaign)
                        <x-voucher-card :campaign="$campaign" mode="browse" />
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Appearance Blocks --}}
            @if(!empty($appearance))
                @foreach($appearance as $block)
                    @php $data = $block['data'] ?? []; @endphp
                    @include('store._partials._appearance_block', ['block' => $block, 'data' => $data])
                @endforeach
            @else
                {{-- Fallback: grid produk --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white border-l-4 border-primary pl-3">Semua Produk</h2>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @forelse($products as $product)
                        @php
                            $hasDiscount = $product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price;
                        @endphp
                        <a href="{{ route('products.show', $product->slug) }}" class="group bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] hover:border-primary rounded-xl overflow-hidden hover:shadow-xl transition-all flex flex-col">
                            <div class="aspect-square w-full bg-slate-50 dark:bg-[#0d1117] relative overflow-hidden">
                                @if($product->images->count() > 0)
                                    @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $mainImg->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300"><span class="material-symbols-outlined text-4xl">inventory_2</span></div>
                                @endif
                                @if($hasDiscount)
                                    <div class="absolute top-2 right-2 bg-rose-500 text-white font-black text-[10px] px-2 py-0.5 rounded-md shadow-sm">
                                        -{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                                    </div>
                                @endif
                            </div>
                            <div class="p-3.5 flex flex-col flex-1">
                                <h3 class="font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm line-clamp-2 mb-1.5 group-hover:text-primary transition-colors">{{ $product->name }}</h3>
                                <div class="mt-auto">
                                    @if($hasDiscount)
                                        <div class="text-[10px] text-slate-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                        <div class="font-black text-primary text-sm sm:text-base">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                    @else
                                        <div class="font-black text-primary text-sm sm:text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="col-span-full text-center py-10 text-slate-400">Belum ada produk aktif.</div>
                        @endforelse
                    </div>
                    <div class="mt-6">{{ $products->links() }}</div>
                </div>
            @endif
        </div>

    </div>

    {{-- Footer --}}
    <div class="text-center py-8 text-[11px] text-slate-400 dark:text-slate-600">
        <a href="{{ route('home') }}" class="hover:text-primary transition-colors font-semibold">⚡ Powered by Rhantech</a>
    </div>
</div>
@endsection
