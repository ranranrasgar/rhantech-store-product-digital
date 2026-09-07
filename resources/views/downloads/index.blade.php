@extends('layouts.public')
@section('title', 'Your Downloads - ' . $order->invoice_number)
@section('content')
<main class="pt-24 pb-2xl min-h-screen bg-surface dark:bg-surface-container-lowest">
    <div class="max-w-4xl mx-auto px-lg">
        
        <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-lg mb-lg shadow-sm">
            <h1 class="font-headline-md font-black text-on-surface mb-2">Pusat Unduhan</h1>
            <p class="text-on-surface-variant font-body-sm mb-6">Faktur: <strong>{{ $order->invoice_number }}</strong></p>

            @if(session('success'))
            <div class="p-3.5 mb-5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-xs font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                    @php 
                        $existingReview = $order->reviews->where('product_id', $item->product_id)->first();
                    @endphp
                    <div class="border border-outline-variant rounded-lg p-4 flex flex-col gap-4">
                        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                            <div class="flex items-center gap-4">
                                @if($item->product && $item->product->images->count() > 0)
                                    @php $img = $item->product->images->where('is_main', true)->first() ?? $item->product->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-16 h-16 object-cover rounded">
                                @else
                                    <div class="w-16 h-16 rounded bg-surface-container-high flex items-center justify-center">
                                        <span class="material-symbols-outlined">inventory_2</span>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-on-surface text-lg">
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}" target="_blank" class="hover:underline hover:text-primary">
                                                {{ $item->product->name }}
                                            </a>
                                        @else
                                            Produk Digital
                                        @endif
                                    </h3>
                                    <p class="text-xs text-on-surface-variant">{{ $item->product->store->name ?? 'Admin Store' }}</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @if($item->product && !empty($item->product->download_links))
                                    @foreach($item->product->download_links as $link)
                                        <a href="{{ $link['url'] }}" target="_blank" class="bg-surface-variant border border-primary text-primary px-4 py-2 rounded text-sm font-bold flex items-center gap-1 hover:bg-primary/10">
                                            <span class="material-symbols-outlined text-[18px]">link</span> {{ $link['name'] }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Bagian Beri Ulasan / Testimoni Pembeli Riil -->
                        @if($item->product)
                        <div x-data="{ openReview: false, rating: 5 }" class="pt-3 border-t border-outline-variant/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-amber-500 text-sm">rate_review</span>
                                    <span class="text-xs font-bold text-on-surface">
                                        {{ $existingReview ? 'Ulasan Anda (' . $existingReview->rating . ' Bintang)' : 'Beri Ulasan Produk Ini' }}
                                    </span>
                                </div>
                                @if(!$existingReview)
                                    <button type="button" @click="openReview = !openReview" class="text-xs text-primary font-bold hover:underline">
                                        <span x-text="openReview ? 'Tutup Form' : '+ Tulis Ulasan'"></span>
                                    </button>
                                @endif
                            </div>

                            @if($existingReview)
                            <div class="mt-2 p-3 rounded-lg bg-surface-container-low border border-outline-variant/40">
                                <div class="flex items-center gap-1 text-amber-500 mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined text-xs {{ $i <= $existingReview->rating ? 'fill-current' : 'text-slate-300' }}">star</span>
                                    @endfor
                                    <span class="text-[10px] text-on-surface-variant ml-2">{{ $existingReview->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-on-surface-variant italic">
                                    "{{ $existingReview->comment }}"
                                </p>
                                <p class="text-[10px] text-slate-400 mt-1.5">* Ulasan yang telah terkirim bersifat permanen (hanya superadmin yang berhak mengelola/menghapus).</p>
                            </div>
                            @else
                            <form x-show="openReview" x-cloak action="{{ route('products.review.store', $item->product->id) }}" method="POST" class="mt-3 p-3.5 rounded-xl bg-surface-container-low border border-outline-variant/60 space-y-3">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="download_token" value="{{ $order->download_token }}">
                                <input type="hidden" name="rating" :value="rating">

                                <div>
                                    <label class="block text-xs font-semibold text-on-surface mb-1">Beri Bintang Kepuasan *</label>
                                    <div class="flex items-center gap-1 text-amber-500">
                                        <template x-for="i in 5" :key="i">
                                            <button type="button" @click="rating = i" class="focus:outline-none transition-transform hover:scale-110">
                                                <span class="material-symbols-outlined text-2xl" :class="i <= rating ? 'fill-current' : 'text-slate-300'">star</span>
                                            </button>
                                        </template>
                                        <span class="text-xs font-bold text-on-surface ml-2" x-text="rating + ' / 5 Bintang'"></span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-on-surface mb-1">Komentar / Pengalaman Anda *</label>
                                    <textarea name="comment" rows="2" required placeholder="Tuliskan pengalaman nyata Anda menggunakan produk ini..." class="w-full px-3 py-2 text-xs bg-surface border border-outline-variant rounded-lg focus:outline-none focus:border-primary"></textarea>
                                    <p class="text-[10px] text-slate-400 mt-1">Catatan: Ulasan yang sudah dikirim bersifat permanen.</p>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="openReview = false" class="px-3 py-1.5 text-xs text-on-surface-variant hover:bg-surface-container rounded-lg">Batal</button>
                                    <button type="submit" class="px-4 py-1.5 text-xs font-bold bg-primary text-white rounded-lg hover:opacity-90 transition shadow-xs">
                                        Kirim Ulasan
                                    </button>
                                </div>
                            </form>
                            @endif
                        </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</main>
@endsection
