@extends('layouts.admin')
@section('title', 'Digital Products')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-7xl mx-auto w-full" x-data="{
    rejectModalOpen: false,
    rejectProductId: null,
    rejectProductName: '',
    rejectReasonType: 'Link Download rusak/tidak bisa diakses',
    rejectCustomReason: '',
    openRejectModal(id, name) {
        this.rejectProductId = id;
        this.rejectProductName = name;
        this.rejectReasonType = 'Link Download rusak/tidak bisa diakses';
        this.rejectCustomReason = '';
        this.rejectModalOpen = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="font-headline-md font-bold text-on-surface flex items-center gap-2">
                <span>Digital Products</span>
                @if($pendingCount > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-500 text-white animate-pulse">
                        {{ $pendingCount }} Menunggu Review
                    </span>
                @endif
            </h2>
            <p class="font-body-md text-on-surface-variant">Kelola produk digital platform, filter per toko/tenant, dan verifikasi kelayakan produk sebelum tayang.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-xs px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow" wire:navigate>
            <span class="material-symbols-outlined text-[1.25rem]">add</span>
            Tambah Produk Platform
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-700 dark:text-emerald-400 text-sm font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="mb-4 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-700 dark:text-amber-400 text-sm font-semibold flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">info</span>
        {{ session('info') }}
    </div>
    @endif

    <!-- QUICK STATUS TABS & METRICS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <a href="{{ route('admin.products.index') }}" 
           class="p-4 rounded-xl border transition-all flex flex-col justify-between {{ !request('origin') && !request('approval_status') ? 'bg-primary/10 border-primary text-primary shadow-xs' : 'bg-surface border-outline-variant text-on-surface hover:bg-surface-container-highest' }}">
            <span class="text-xs font-semibold text-on-surface-variant">Semua Produk</span>
            <span class="text-2xl font-black mt-1">{{ $totalCount }}</span>
        </a>

        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['approval_status' => 'pending'])) }}" 
           class="p-4 rounded-xl border transition-all flex flex-col justify-between {{ request('approval_status') === 'pending' ? 'bg-amber-500/15 border-amber-500 text-amber-700 dark:text-amber-400 shadow-xs' : 'bg-surface border-outline-variant text-on-surface hover:bg-surface-container-highest' }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Menunggu Review</span>
                @if($pendingCount > 0)
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                @endif
            </div>
            <span class="text-2xl font-black mt-1 text-amber-600 dark:text-amber-400">{{ $pendingCount }}</span>
        </a>

        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['origin' => 'internal'])) }}" 
           class="p-4 rounded-xl border transition-all flex flex-col justify-between {{ request('origin') === 'internal' ? 'bg-sky-500/15 border-sky-500 text-sky-700 dark:text-sky-400 shadow-xs' : 'bg-surface border-outline-variant text-on-surface hover:bg-surface-container-highest' }}">
            <span class="text-xs font-semibold text-on-surface-variant">Milik Platform (Internal)</span>
            <span class="text-2xl font-black mt-1">{{ $internalCount }}</span>
        </a>

        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['origin' => 'tenant'])) }}" 
           class="p-4 rounded-xl border transition-all flex flex-col justify-between {{ request('origin') === 'tenant' ? 'bg-indigo-500/15 border-indigo-500 text-indigo-700 dark:text-indigo-400 shadow-xs' : 'bg-surface border-outline-variant text-on-surface hover:bg-surface-container-highest' }}">
            <span class="text-xs font-semibold text-on-surface-variant">Produk Tenant (Toko)</span>
            <span class="text-2xl font-black mt-1">{{ $tenantCount }}</span>
        </a>
    </div>

    <!-- FILTER & SEARCH PANEL -->
    <div class="bg-surface rounded-xl border border-outline-variant p-4 sm:p-5 mb-6 shadow-xs">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center gap-3">
            
            <!-- Search bar -->
            <div class="flex-1 relative">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk, slug, atau toko..." class="w-full pl-10 pr-4 py-2.5 text-xs md:text-sm bg-surface-container-lowest border border-outline-variant rounded-xl focus:outline-none focus:ring-1 focus:ring-primary text-on-surface">
            </div>

            <!-- Filter Origin / Kepemilikan -->
            <select name="origin" class="px-3.5 py-2.5 text-xs md:text-sm bg-surface-container-lowest border border-outline-variant text-on-surface rounded-xl focus:outline-none">
                <option value="">Semua Kepemilikan</option>
                <option value="internal" {{ request('origin') === 'internal' ? 'selected' : '' }}>🏢 Milik Platform (Internal)</option>
                <option value="tenant" {{ request('origin') === 'tenant' ? 'selected' : '' }}>🏪 Milik Tenant (Toko Luar)</option>
            </select>

            <!-- Filter Toko Tertentu -->
            <select name="store_id" class="px-3.5 py-2.5 text-xs md:text-sm bg-surface-container-lowest border border-outline-variant text-on-surface rounded-xl focus:outline-none">
                <option value="">Pilih Toko Spesifik</option>
                @foreach($stores as $s)
                    <option value="{{ $s->id }}" {{ request('store_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>

            <!-- Filter Status Approval -->
            <select name="approval_status" class="px-3.5 py-2.5 text-xs md:text-sm bg-surface-container-lowest border border-outline-variant text-on-surface rounded-xl focus:outline-none">
                <option value="">Semua Status Review</option>
                <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu Review</option>
                <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>✅ Disetujui (Approved)</option>
                <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak (Rejected)</option>
            </select>

            <button type="submit" class="px-4 py-2.5 text-xs md:text-sm font-bold bg-primary text-white rounded-xl hover:brightness-110 transition shadow flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-[16px]">filter_alt</span> Filter
            </button>

            @if(request()->anyFilled(['search', 'origin', 'store_id', 'approval_status', 'is_active']))
            <a href="{{ route('admin.products.index') }}" class="px-3 py-2.5 text-xs md:text-sm font-semibold border border-outline-variant text-on-surface-variant hover:bg-surface-container-highest rounded-xl transition text-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- TABLE PRODUK -->
    <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left font-body-md text-xs md:text-sm">
                <thead class="bg-surface-container-lowest border-b border-outline-variant text-on-surface-variant font-label-md">
                    <tr>
                        <th class="p-4 font-semibold min-w-[300px]">Produk & Sumber</th>
                        <th class="p-4 font-semibold">Kategori / Tipe</th>
                        <th class="p-4 font-semibold">Harga Jual</th>
                        <th class="p-4 font-semibold text-center">Status & Verifikasi</th>
                        <th class="p-4 font-semibold text-right pr-6">Aksi & Review</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($products as $product)
                    <tr class="hover:bg-surface-container-lowest/50 transition-colors {{ $product->approval_status === 'pending' ? 'bg-amber-500/5' : '' }}">
                        <!-- Product info & Store Origin -->
                        <td class="p-4">
                            <div class="flex items-start gap-3">
                                @if($product->images->count() > 0)
                                    @php $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first(); @endphp
                                    <img src="{{ asset('storage/' . $mainImg->image_path) }}" class="w-14 h-14 rounded-lg object-cover border border-outline-variant shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-surface-container-high border border-outline-variant flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-on-surface-variant">code</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="font-bold text-on-surface hover:text-primary transition line-clamp-1 text-sm">
                                        {{ $product->name }}
                                    </a>
                                    <div class="text-on-surface-variant text-[11px] font-mono mt-0.5 truncate max-w-xs">
                                        /{{ $product->slug }}
                                    </div>
                                    
                                    <!-- Store Origin Badge -->
                                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                        @if($product->store)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border border-indigo-500/20">
                                                <span class="material-symbols-outlined text-[12px]">storefront</span>
                                                {{ $product->store->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/10 text-sky-700 dark:text-sky-400 border border-sky-500/20">
                                                <span class="material-symbols-outlined text-[12px]">verified</span>
                                                Platform Official
                                            </span>
                                        @endif

                                        @if(!empty($product->download_links))
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300" title="{{ count($product->download_links) }} Tautan Unduhan Eksternal">
                                                <span class="material-symbols-outlined text-[12px]">link</span> {{ count($product->download_links) }} Link
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Category & Type -->
                        <td class="p-4">
                            <div class="font-semibold text-on-surface">
                                {{ $product->category->name ?? 'Tanpa Kategori' }}
                            </div>
                            <div class="text-[11px] text-on-surface-variant mt-0.5">
                                {{ $product->type->name ?? 'Umum' }}
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="p-4">
                            @if($product->discount_price && $product->discount_price > 0 && $product->discount_price < $product->price)
                                <div class="font-bold text-primary">Rp {{ number_format($product->discount_price, 0, ',', '.') }}</div>
                                <div class="text-[11px] text-on-surface-variant line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @else
                                <div class="font-bold text-on-surface">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            @endif
                        </td>

                        <!-- Unified Status & Verification Column -->
                        <td class="p-4 text-center">
                            <div class="inline-flex flex-col items-center gap-1">
                                <!-- Status Verifikasi -->
                                @if($product->approval_status === 'approved')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span> Approved
                                    </span>
                                @elseif($product->approval_status === 'pending')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-800 animate-pulse">
                                        <span class="material-symbols-outlined text-[14px]">hourglass_empty</span> In Review
                                    </span>
                                @elseif($product->approval_status === 'rejected')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 border border-rose-300 dark:border-rose-800">
                                        <span class="material-symbols-outlined text-[14px]">cancel</span> Ditolak
                                    </span>
                                @endif

                                <!-- Status Publikasi / Tayang -->
                                <div class="flex items-center gap-1 text-[11px] font-medium">
                                    @if($product->is_active && $product->approval_status === 'approved')
                                        <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tayang
                                        </span>
                                    @elseif(!$product->is_active)
                                        <span class="inline-flex items-center gap-1 text-slate-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Non-Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-amber-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Ditahan
                                        </span>
                                    @endif
                                </div>

                                @if($product->approval_status === 'rejected' && $product->rejection_reason)
                                    <span class="text-[10px] text-rose-600 dark:text-rose-400 max-w-[170px] truncate cursor-help mt-0.5" title="{{ $product->rejection_reason }}">
                                        Alasan: {{ $product->rejection_reason }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Actions & Review -->
                        <td class="p-4 text-right pr-6">
                            <div class="inline-flex items-center gap-1.5">
                                <!-- Action Buttons: Approve / Tolak -->
                                @if($product->approval_status === 'pending')
                                    <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="inline" onsubmit="return confirm('Setujui produk ini agar dapat tayang di platform?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 shadow-xs" title="Setujui Produk">
                                            <span class="material-symbols-outlined text-[15px]">check</span> Approve
                                        </button>
                                    </form>
                                    <button type="button" @click="openRejectModal({{ $product->id }}, '{{ addslashes($product->name) }}')" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-300 text-xs font-bold transition flex items-center gap-1 border border-rose-200 dark:border-rose-900" title="Tolak Produk">
                                        <span class="material-symbols-outlined text-[15px]">close</span> Tolak
                                    </button>
                                @elseif($product->approval_status === 'approved')
                                    <button type="button" @click="openRejectModal({{ $product->id }}, '{{ addslashes($product->name) }}')" class="px-2 py-1 rounded-lg text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 text-xs font-semibold transition border border-rose-200 dark:border-rose-900/60" title="Batalkan Persetujuan (Tolak)">
                                        Tolak
                                    </button>
                                @elseif($product->approval_status === 'rejected')
                                    <form action="{{ route('admin.products.approve', $product) }}" method="POST" class="inline" onsubmit="return confirm('Ubah status jadi Approve dan tayangkan?');">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-2 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-xs">
                                            Approve
                                        </button>
                                    </form>
                                @endif

                                <div class="h-4 w-px bg-outline-variant mx-1"></div>

                                <!-- Toggle Active Switch -->
                                <form action="{{ route('admin.products.toggle_active', $product) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="p-1.5 {{ $product->is_active ? 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30' : 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }} rounded-lg transition" title="Saklar On/Off Tayang">
                                        <span class="material-symbols-outlined text-[20px]">{{ $product->is_active ? 'toggle_on' : 'toggle_off' }}</span>
                                    </button>
                                </form>

                                <!-- Edit -->
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 text-on-surface-variant hover:text-primary hover:bg-surface-container-high rounded-lg transition" title="Edit Rincian">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>

                                <!-- Delete -->
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini secara permanen?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-on-surface-variant hover:text-error hover:bg-error-container rounded-lg transition" title="Hapus Produk">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inventory_2</span>
                            <p class="font-semibold">Tidak ada produk yang cocok dengan filter saat ini.</p>
                            <a href="{{ route('admin.products.index') }}" class="text-xs text-primary font-bold hover:underline mt-2 inline-block">Reset Filter</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-outline-variant flex justify-center">
            {{ $products->links() }}
        </div>
        @endif
    </div>

    <!-- MODAL PENOLAKAN PRODUK -->
    <div x-show="rejectModalOpen" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-surface rounded-2xl border border-outline-variant p-6 w-full max-w-md shadow-2xl" @click.away="rejectModalOpen = false">
            <div class="flex items-center gap-2.5 text-rose-600 mb-2">
                <span class="material-symbols-outlined text-2xl">warning</span>
                <h3 class="text-base font-bold text-on-surface">Tolak Publikasi Produk</h3>
            </div>
            
            <p class="text-xs text-on-surface-variant mb-4">
                Produk <span class="font-bold text-on-surface" x-text="rejectProductName"></span> akan ditolak dan statusnya menjadi <span class="font-semibold text-rose-600">Ditolak</span>. Tenant akan menerima info alasan berikut agar dapat memperbaikinya.
            </p>

            <form :action="`/admin/products/${rejectProductId}/reject`" method="POST">
                @csrf @method('PATCH')

                <div class="space-y-3 mb-4">
                    <label class="block text-xs font-bold text-on-surface">Pilih Alasan Utama Penolakan:</label>
                    <div class="space-y-2 text-xs">
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-outline-variant hover:bg-surface-container-highest cursor-pointer">
                            <input type="radio" name="reason_type" value="Link Download rusak, tidak valid, atau tidak bisa diakses" x-model="rejectReasonType" class="text-rose-600">
                            <span>Link Download rusak, tidak valid, atau tidak bisa diakses</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-outline-variant hover:bg-surface-container-highest cursor-pointer">
                            <input type="radio" name="reason_type" value="Deskripsi atau judul produk terindikasi manipulatif / tidak jelas" x-model="rejectReasonType" class="text-rose-600">
                            <span>Deskripsi atau judul terindikasi manipulatif / tidak jelas</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-outline-variant hover:bg-surface-container-highest cursor-pointer">
                            <input type="radio" name="reason_type" value="Foto / Banner produk tidak sesuai standar atau melanggar hak cipta" x-model="rejectReasonType" class="text-rose-600">
                            <span>Foto / Banner tidak sesuai standar atau melanggar hak cipta</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 rounded-lg border border-outline-variant hover:bg-surface-container-highest cursor-pointer">
                            <input type="radio" name="reason_type" value="other" x-model="rejectReasonType" class="text-rose-600">
                            <span>Alasan lainnya (Tulis catatan kustom)</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-on-surface mb-1">Catatan Tambahan untuk Tenant (Opsional / Wajib jika lainnya):</label>
                        <textarea name="custom_reason" x-model="rejectCustomReason" rows="3" placeholder="Jelaskan secara spesifik apa yang harus diperbaiki oleh tenant..." class="w-full px-3 py-2 text-xs bg-surface-container-lowest border border-outline-variant rounded-lg focus:outline-none focus:border-rose-500"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2 border-t border-outline-variant">
                    <button type="button" @click="rejectModalOpen = false" class="px-4 py-2 text-xs font-semibold text-on-surface-variant hover:bg-surface-container rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white rounded-lg shadow-sm transition flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">cancel</span> Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

