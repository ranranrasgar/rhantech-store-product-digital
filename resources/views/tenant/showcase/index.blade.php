@extends('layouts.tenant')

@section('title', 'Etalase Produk Afiliasi')

@section('content')
<div class="flex-1 overflow-y-auto bg-[#fafafa] dark:bg-[#000000] text-[#09090b] dark:text-[#ededed] p-4 md:p-8 relative"
     id="showcase-container"
     x-data="{
        loading: false,
        navigate(e) {
            let link = e.target.closest('a.ajax-tab, nav[role=\'navigation\'] a');
            if(!link) return;
            e.preventDefault();
            this.loading = true;
            fetch(link.href)
                .then(r => r.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newContent = doc.getElementById('showcase-container').innerHTML;
                    document.getElementById('showcase-container').innerHTML = newContent;
                    history.pushState(null, '', link.href);
                    this.loading = false;
                });
        }
     }"
     @click="navigate"
>
    <!-- Loading overlay -->
    <div x-show="loading" class="absolute inset-0 z-50 bg-white/50 dark:bg-black/50 backdrop-blur-sm flex items-center justify-center" style="display: none;">
        <div class="w-10 h-10 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
    </div>
    
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight text-zinc-900 dark:text-zinc-100 flex items-center gap-2.5">
                    Etalase Produk Afiliasi (Showcase)
                </h1>
                <p class="text-xs md:text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                    Pilih produk digital dari platform atau toko tenant lain untuk dipajang langsung di etalase toko Anda.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('help.show', 'panduan-memasang-produk-toko-lain-di-etalase-toko-saya-showcase') }}" target="_blank" class="px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-zinc-700 dark:text-zinc-300">menu_book</span>
                    Panduan Etalase Afiliasi
                </a>
                <a href="{{ route('store.show', $store->slug) }}" target="_blank" class="px-4 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-[#000000] hover:bg-slate-50 dark:hover:bg-[#161f33] text-xs font-bold text-slate-700 dark:text-slate-200 transition flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                    Lihat Etalase Toko Saya
                </a>
            </div>
        </div>

        <!-- Main Unified Card Container -->
        <div class="bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-2xs">
            
            <!-- Modern Tab Filter (Underline Style matching Products) -->
            <div class="border-b border-zinc-100 dark:border-zinc-800 px-4 md:px-6 flex items-center gap-6 overflow-x-auto hide-scrollbar bg-slate-50/50 dark:bg-[#0c1220]/50" style="-ms-overflow-style: none; scrollbar-width: none;">
                <a href="{{ route('tenant.showcase.index', ['tab' => 'semua', 'search' => request('search')]) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'semua' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Semua Produk
                </a>
                
                <a href="{{ route('tenant.showcase.index', ['tab' => 'platform', 'search' => request('search')]) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'platform' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Produk Platform (Official)
                </a>
                
                <a href="{{ route('tenant.showcase.index', ['tab' => 'tenant', 'search' => request('search')]) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'tenant' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Produk Toko Tenant Lain
                </a>
                
                <a href="{{ route('tenant.showcase.index', ['tab' => 'terpasang', 'search' => request('search')]) }}" 
                   class="ajax-tab py-4 text-xs md:text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab === 'terpasang' ? 'text-orange-600 dark:text-orange-500 border-orange-600 dark:border-orange-500' : 'text-zinc-500 dark:text-zinc-400 border-transparent hover:text-orange-600 dark:hover:text-orange-500' }}">
                    Dipajang di Toko Saya <span class="ml-1.5 px-2 py-0.5 rounded-full text-[11px] {{ $tab === 'terpasang' ? 'bg-orange-100 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300' : 'bg-slate-200/60 dark:bg-slate-800 text-zinc-600 dark:text-zinc-400' }}" id="showcase-counter">{{ count($myShowcaseIds) }}</span>
                </a>
            </div>

            <!-- Sleek Search Input -->
            <div class="p-4 md:p-6 border-b border-zinc-100 dark:border-zinc-800">
                <form method="GET" action="{{ route('tenant.showcase.index') }}" class="flex items-center gap-3">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px] leading-none">search</span>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama produk / toko..." 
                               class="w-full pl-10 pr-8 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-zinc-900 dark:text-zinc-100 transition-all placeholder-slate-400"
                               onkeydown="if(event.key === 'Enter'){this.form.submit();}">
                        @if(request('search'))
                            <a href="{{ route('tenant.showcase.index', ['tab' => $tab]) }}" 
                               class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                            </a>
                        @endif
                    </div>
                    <button type="submit" class="hidden md:flex px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white font-bold text-xs rounded-xl transition-all active:scale-95 items-center gap-1.5 shrink-0">
                        <span>Cari</span>
                    </button>
                </form>
            </div>

            <!-- Product Grid -->
            <div class="p-4 md:p-6 bg-slate-50 dark:bg-transparent">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($products as $product)
                @php
                    $isInstalled = in_array($product->id, $myShowcaseIds);
                    $mainImage = $product->images->where('is_main', true)->first() ?? $product->images->first();
                @endphp
                <div id="showcase-card-{{ $product->id }}" class="showcase-card bg-white dark:bg-[#000000] border {{ $isInstalled ? 'border-emerald-500/70 ring-1 ring-emerald-500/20' : 'border-zinc-200 dark:border-zinc-800' }} rounded-2xl overflow-hidden flex flex-col transition-all duration-300">
                    
                    <!-- Image Box -->
                    <div class="aspect-video w-full bg-zinc-100 dark:bg-zinc-900 relative overflow-hidden group">
                        @if($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                <span class="material-symbols-outlined text-4xl">inventory_2</span>
                            </div>
                        @endif

                        <!-- Badge Origin -->
                        <div class="absolute top-3 left-3 flex flex-col gap-1">
                            @if($product->store)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-900/90 text-white backdrop-blur-md">
                                    <span class="material-symbols-outlined text-[12px]">storefront</span>
                                    {{ $product->store->name }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-900/90 text-white backdrop-blur-md">
                                    <span class="material-symbols-outlined text-[12px]">verified</span>
                                    Platform Official
                                </span>
                            @endif
                        </div>

                        <!-- Status Terpasang Indicator -->
                        <div id="installed-badge-{{ $product->id }}" class="absolute top-3 right-3 {{ $isInstalled ? '' : 'hidden' }}">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500 text-white">
                                <span class="material-symbols-outlined text-[13px]">check_circle</span> Terpasang di Toko
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-4 flex flex-col flex-1">
                        <div class="text-[11px] text-slate-400 mb-1">
                            {{ $product->category->name ?? 'Produk Digital' }}
                        </div>
                        <h3 class="font-bold text-sm text-zinc-900 dark:text-zinc-100 line-clamp-2 mb-3 leading-snug">
                            {{ $product->name }}
                        </h3>

                        @php
                            $effectivePrice = ($product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price) ? $product->discount_price : $product->price;
                            $commRate = (float)($product->affiliate_commission_rate ?? 10);
                            $commAmount = round(($effectivePrice * $commRate) / 100);
                        @endphp
                        <!-- Price & Commission -->
                        <div class="mt-auto pt-3 border-t border-slate-100 dark:border-[#1d273d] flex items-end justify-between mb-4">
                            <div>
                                <div class="text-[10px] uppercase font-bold text-slate-400">Harga Jual</div>
                                @if($product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price)
                                    <div class="text-xs line-through text-slate-400">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <div class="font-extrabold text-sm text-zinc-900 dark:text-zinc-100">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                @else
                                    <div class="font-extrabold text-sm text-zinc-900 dark:text-zinc-100">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                @endif
                            </div>

                            <!-- Perkiraan Komisi Riil -->
                            <div class="text-right">
                                <div class="text-[10px] uppercase font-bold text-amber-600 dark:text-amber-400 flex items-center justify-end gap-1" title="Bagi hasil komisi yang Anda peroleh per penjualan">
                                    <span class="material-symbols-outlined text-[12px]">monetization_on</span>
                                    <span>Komisi {{ $commRate + 0 }}%</span>
                                </div>
                                <div class="font-extrabold text-xs text-emerald-600 dark:text-emerald-400">
                                    +Rp {{ number_format($commAmount, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Action Button: Pasang / Copot (AJAX Seamless) -->
                        <form action="{{ route('tenant.showcase.toggle', $product->id) }}" method="POST" class="w-full" onsubmit="handleShowcaseToggle(event, {{ $product->id }}, '{{ route('tenant.showcase.toggle', $product->id) }}')">
                            @csrf
                            <button type="submit" id="btn-toggle-{{ $product->id }}"
                                    class="w-full py-2 px-3 rounded-xl {{ $isInstalled ? 'border border-rose-200 dark:border-rose-900/40 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400' : 'bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white active:scale-95' }} text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                                @if($isInstalled)
                                    <span class="material-symbols-outlined text-[16px]">remove_shopping_cart</span>
                                    <span>Copot dari Toko Saya</span>
                                @else
                                    <span class="material-symbols-outlined text-[16px]">add_shopping_cart</span>
                                    <span>+ Pasang di Etalase Toko</span>
                                @endif
                            </button>
                        </form>

                    </div>

                </div>
            @empty
                <div class="col-span-full p-16 text-center text-slate-400 bg-white dark:bg-[#000000] border border-zinc-200 dark:border-zinc-800 rounded-2xl">
                    <span class="material-symbols-outlined text-4xl mb-2 text-slate-400">inventory_2</span>
                    <h3 class="font-bold text-base text-zinc-800 dark:text-zinc-100">Tidak ada produk ditemukan</h3>
                    <p class="text-xs text-slate-400 mt-1">Coba gunakan kata kunci lain atau pilih tab produk yang berbeda.</p>
                </div>
            @endforelse
                </div> <!-- End Grid -->
                
                <!-- Consolidated Pagination -->
                @if($products->hasPages())
                <div class="mt-6 pt-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-center">
                    {{ $products->links() }}
                </div>
                @endif
            </div> <!-- End Grid Container inside card -->
        </div> <!-- End of Main Unified Card Container -->

    </div>
</div>

<!-- Floating Notification Toast -->
<div id="showcase-toast" class="fixed bottom-6 right-6 z-50 transform transition-all duration-300 translate-y-20 opacity-0 pointer-events-none flex items-center gap-3 px-4 py-3 rounded-2xl bg-orange-600/95 dark:bg-orange-500/95 text-white backdrop-blur-md border border-slate-700/50 dark:border-slate-200/50 text-xs font-bold max-w-sm">
    <div id="showcase-toast-icon" class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 dark:text-emerald-600 flex items-center justify-center shrink-0">
        <span class="material-symbols-outlined text-[18px]">check_circle</span>
    </div>
    <div id="showcase-toast-msg" class="leading-tight flex-1"></div>
</div>

@push('scripts')
<script>
let toastTimeout = null;
function showToast(message, isSuccess = true) {
    const toast = document.getElementById('showcase-toast');
    const toastMsg = document.getElementById('showcase-toast-msg');
    const toastIcon = document.getElementById('showcase-toast-icon');
    if (!toast || !toastMsg || !toastIcon) return;

    toastMsg.textContent = message;
    if (isSuccess) {
        toastIcon.className = 'w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 dark:text-emerald-600 flex items-center justify-center shrink-0';
        toastIcon.innerHTML = '<span class="material-symbols-outlined text-[18px]">check_circle</span>';
    } else {
        toastIcon.className = 'w-7 h-7 rounded-xl bg-rose-500/20 text-rose-500 dark:text-rose-600 flex items-center justify-center shrink-0';
        toastIcon.innerHTML = '<span class="material-symbols-outlined text-[18px]">error</span>';
    }

    toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
    toast.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');

    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
        toast.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
    }, 3000);
}

function handleShowcaseToggle(event, productId, url) {
    event.preventDefault();

    const btn = document.getElementById('btn-toggle-' + productId);
    const card = document.getElementById('showcase-card-' + productId);
    const badge = document.getElementById('installed-badge-' + productId);
    const counter = document.getElementById('showcase-counter');
    const currentTab = '{{ $tab }}';

    if (!btn) return;

    const originalBtnHtml = btn.innerHTML;
    const originalBtnClass = btn.className;

    // Loading state in-place
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[16px] animate-spin">sync</span> <span>Memproses...</span>';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
        || document.querySelector('input[name="_token"]')?.value;

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Terjadi kesalahan sistem.');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            // Update button & card state
            if (data.is_installed) {
                // Dipasang
                btn.className = 'w-full py-2 px-3 rounded-xl border border-rose-200 dark:border-rose-900/40 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/30 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer';
                btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">remove_shopping_cart</span> <span>Copot dari Toko Saya</span>';
                
                if (card) {
                    card.classList.remove('border-slate-200/80', 'dark:border-zinc-800');
                    card.classList.add('border-emerald-500/70', 'ring-1', 'ring-emerald-500/20');
                }
                if (badge) {
                    badge.classList.remove('hidden');
                }
            } else {
                // Dicopot
                btn.className = 'w-full py-2 px-3 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white active:scale-95 text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer';
                btn.innerHTML = '<span class="material-symbols-outlined text-[16px]">add_shopping_cart</span> <span>+ Pasang di Etalase Toko</span>';
                
                if (card) {
                    card.classList.remove('border-emerald-500/70', 'ring-1', 'ring-emerald-500/20');
                    card.classList.add('border-slate-200/80', 'dark:border-zinc-800');
                }
                if (badge) {
                    badge.classList.add('hidden');
                }

                // Jika di tab 'terpasang', kartu menghilang dengan transisi halus
                if (currentTab === 'terpasang' && card) {
                    card.style.transition = 'all 0.35s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        const remaining = document.querySelectorAll('.showcase-card');
                        if (remaining.length === 0) {
                            window.location.reload();
                        }
                    }, 350);
                }
            }

            // Update badge counter
            if (counter && typeof data.count !== 'undefined') {
                counter.textContent = data.count;
            }

            showToast(data.message, true);
        } else {
            showToast(data.message || 'Gagal mengubah etalase toko.', false);
            btn.innerHTML = originalBtnHtml;
            btn.className = originalBtnClass;
        }
    })
    .catch(err => {
        showToast(err.message || 'Koneksi bermasalah. Silakan coba lagi.', false);
        btn.innerHTML = originalBtnHtml;
        btn.className = originalBtnClass;
    })
    .finally(() => {
        btn.disabled = false;
    });
}
</script>
@endpush
@endsection
