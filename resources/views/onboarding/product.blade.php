@extends("layouts.onboarding")
@section("title", "Buat Produk Pertama")

@section("content")
<div x-data="{
    imagePreview: null,
    priceRaw: 0,
    priceFormatted: '',
    formatPrice(val) {
        let num = val.replace(/[^0-9]/g, '');
        this.priceRaw = num;
        this.priceFormatted = num ? 'Rp ' + parseInt(num).toLocaleString('id-ID') : '';
    },
    onImageChange(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => { this.imagePreview = ev.target.result; };
            reader.readAsDataURL(file);
        }
    }
}" class="max-w-xl mx-auto">

    {{-- Header --}}
    <div class="text-center mb-8 animate-fade-up">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-teal-500/10 text-[#00838f] text-xs font-bold mb-4 border border-teal-500/20">
            <span class="material-symbols-outlined text-[14px]">inventory_2</span>
            Langkah 3 dari 3 — Buat Produk Pertama
        </div>
        <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
            Upload produk pertamamu 📦
        </h1>
        <p class="mt-2 text-slate-500 dark:text-slate-400 text-sm max-w-md mx-auto">
            Isi info produk digital yang mau kamu jual. Tenang, produk perlu disetujui admin dulu sebelum tampil ke pembeli.
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-[#111726] rounded-2xl border border-slate-200/80 dark:border-slate-700/60 p-6 sm:p-8 shadow-sm animate-fade-up-delay-1">
        <form action="{{ route("onboarding.product.save") }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            @if ($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 text-sm">
                <ul class="space-y-1">@foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>
            </div>
            @endif

            {{-- Gambar Produk --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">
                    Gambar Thumbnail Produk
                    <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                </label>
                <label for="image" class="cursor-pointer block">
                    <div class="w-full rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 overflow-hidden flex items-center justify-center transition-all hover:border-teal-400 dark:hover:border-teal-500"
                         style="min-height: 140px;">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full max-h-52 object-contain rounded-xl p-2">
                        </template>
                        <template x-if="!imagePreview">
                            <div class="text-center py-8">
                                <span class="material-symbols-outlined text-slate-300 dark:text-slate-600 text-[40px]">add_photo_alternate</span>
                                <p class="text-xs text-slate-400 mt-2">Klik untuk pilih gambar produk</p>
                                <p class="text-[10px] text-slate-300 dark:text-slate-600 mt-1">JPG, PNG, WebP · Maks 5MB</p>
                            </div>
                        </template>
                    </div>
                </label>
                <input id="image" type="file" name="image" class="hidden" accept="image/*" @change="onImageChange($event)">
            </div>

            {{-- Nama Produk --}}
            <div>
                <label for="prod_name" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Nama Produk <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="prod_name" name="name"
                       placeholder="Contoh: Template Laravel E-Commerce, UI Kit Figma Premium"
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00838f]/40 focus:border-[#00838f] transition text-sm font-medium"
                       value="{{ old("name") }}">
            </div>

            {{-- Kategori + Harga --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Kategori
                    </label>
                    <select id="category" name="product_category_id"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-[#00838f]/40 focus:border-[#00838f] transition text-sm">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old("product_category_id") == $cat->id ? "selected" : "" }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="price_display" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                        Harga (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="price_display"
                           :value="priceFormatted"
                           @input="formatPrice($event.target.value)"
                           placeholder="Rp 50.000"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00838f]/40 focus:border-[#00838f] transition text-sm font-bold">
                    <input type="hidden" name="price" :value="priceRaw">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="prod_desc" class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-1.5">
                    Deskripsi Singkat <span class="text-slate-400 font-normal ml-1">(Opsional)</span>
                </label>
                <textarea id="prod_desc" name="description" rows="3"
                          placeholder="Jelaskan apa yang pembeli dapatkan setelah membeli produk ini..."
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#00838f]/40 focus:border-[#00838f] transition text-sm resize-none">{{ old("description") }}</textarea>
            </div>

            {{-- Info review --}}
            <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-800/40 flex items-start gap-2">
                <span class="material-symbols-outlined text-amber-500 text-[18px] shrink-0 mt-0.5">info</span>
                <p class="text-xs text-amber-700 dark:text-amber-300">
                    Produk akan <strong>menunggu review admin</strong> sebelum tampil di marketplace. Biasanya disetujui dalam 1x24 jam.
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route("onboarding.complete") }}"
                   class="flex-1 py-3 px-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-semibold text-sm transition-all hover:bg-slate-100 dark:hover:bg-slate-700 text-center active:scale-[0.98]">
                    Lewati, nanti saja
                </a>
                <button type="submit"
                        class="flex-1 py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 text-white font-bold text-sm transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-2 active:scale-[0.98]">
                    <span>Upload & Selesai</span>
                    <span class="material-symbols-outlined text-[18px]">rocket_launch</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Store info --}}
    <div class="mt-4 text-center text-xs text-slate-400">
        Produk untuk toko: <span class="font-bold text-slate-600 dark:text-slate-300">{{ $store->name }}</span>
        — <a href="{{ route("onboarding.setup") }}" class="underline hover:text-slate-600 dark:hover:text-slate-200">Edit profil toko</a>
    </div>
</div>
@endsection
