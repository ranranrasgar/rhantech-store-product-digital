@extends('layouts.tenant')

@section('title', 'Toko yang Saya Ikuti')

@section('content')
<div class="flex-1 overflow-y-auto p-4 md:p-8 bg-[#f8fafc] dark:bg-[#090d16] text-[#0f172a] dark:text-[#f1f5f9] transition-colors duration-200"
     x-data="{
         unfollow(storeId, storeName) {
             if (!confirm('Apakah Anda yakin ingin berhenti mengikuti toko ' + storeName + '?')) return;
             fetch('/toko/' + storeId + '/follow', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/json',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}',
                     'Accept': 'application/json'
                 }
             })
             .then(r => r.json())
             .then(data => {
                 window.location.reload();
             })
             .catch(err => console.error(err));
         }
     }">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-primary text-3xl">storefront</span>
                    <span>Toko yang Saya Ikuti</span>
                </h1>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Daftar toko digital favorit yang sedang Anda ikuti untuk update katalog dan promo terbaru.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('products.index') }}" class="px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-xl text-xs md:text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">explore</span>
                    <span>Jelajahi Toko Lain</span>
                </a>
            </div>
        </div>

        @if($stores->isNotEmpty())
            <!-- Store Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
                @foreach($stores as $s)
                <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-5 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow group">
                    <div>
                        <div class="flex items-start gap-3.5">
                            <!-- Logo Toko -->
                            <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shrink-0 p-0.5">
                                @if($s->logo)
                                    <img src="{{ asset('storage/' . $s->logo) }}" alt="{{ $s->name }}" class="w-full h-full object-cover rounded-[14px]">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($s->name) }}&background=0284c7&color=fff&size=100" alt="{{ $s->name }}" class="w-full h-full object-cover rounded-[14px]">
                                @endif
                            </div>

                            <!-- Info Toko -->
                            <div class="min-w-0 flex-1">
                                <h3 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-1.5 truncate">
                                    <a href="{{ url('/' . $s->slug) }}" target="_blank" class="hover:text-primary transition-colors truncate">
                                        {{ $s->name }}
                                    </a>
                                    @if($s->isPro())
                                        <span class="bg-amber-500/15 text-amber-500 text-[10px] font-black px-1.5 py-0.2 rounded uppercase shrink-0">PRO</span>
                                    @endif
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                                    {{ '@' . $s->slug }}
                                </p>

                                <!-- Meta badges -->
                                <div class="flex items-center gap-2 mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-primary">inventory_2</span>
                                        <span>{{ $s->products_count }} Produk</span>
                                    </span>
                                    <span>•</span>
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px] text-amber-500">group</span>
                                        <span>{{ $s->followers()->count() }} Pengikut</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if(!empty($s->description))
                            <p class="text-xs text-slate-600 dark:text-slate-300 mt-3.5 line-clamp-2 leading-relaxed">
                                {{ $s->description }}
                            </p>
                        @endif
                    </div>

                    <!-- Actions Footer -->
                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-2">
                        <a href="{{ url('/' . $s->slug) }}" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition-all flex items-center gap-1.5">
                            <span>Kunjungi Toko</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_outward</span>
                        </a>

                        <button type="button" 
                                @click="unfollow({{ $s->id }}, '{{ addslashes($s->name) }}')"
                                class="px-3 py-2 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl text-xs font-semibold transition-all flex items-center gap-1 cursor-pointer">
                            <span class="material-symbols-outlined text-[15px]">person_remove</span>
                            <span>Batal Ikuti</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $stores->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-[#111726] border border-slate-200/80 dark:border-[#222f49] rounded-2xl p-12 text-center max-w-lg mx-auto">
                <div class="w-16 h-16 rounded-2xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl">storefront</span>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1.5">
                    Belum Mengikuti Toko
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
                    Anda belum mengikuti toko digital manapun. Klik tombol <strong>+ Ikuti</strong> pada profil toko favorit Anda untuk menerima update katalog dan promo menarik.
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-xs sm:text-sm font-bold shadow-md hover:bg-primary/90 transition-all">
                    <span class="material-symbols-outlined text-[18px]">travel_explore</span>
                    <span>Jelajahi Toko &amp; Produk</span>
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
