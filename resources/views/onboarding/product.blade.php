@extends("layouts.onboarding")
@section("title", "Buat Produk Pertama")

@section("content")
<div x-data="{
    imagePreview: null,
    priceRaw: @js(old('price', '')),
    priceFormatted: @js(old('price') ? 'Rp ' . number_format((float) old('price'), 0, ',', '.') : ''),
    submitting: false,
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

    @include("onboarding._progress", ["step" => 3, "total" => 3])

    <div class="mb-5 animate-fade-up">
        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Upload produk pertamamu</h1>
        <p class="mt-1.5 text-slate-500 dark:text-slate-400 text-sm">Boleh dilewati. Produk tampil setelah disetujui admin.</p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-[#111726] rounded-2xl border border-slate-200 dark:border-slate-700/60 p-4 sm:p-6 animate-fade-up-delay-1">
        <form action="{{ route("onboarding.product.save") }}" method="POST" enctype="multipart/form-data" @submit="submitting = true" class="space-y-5">
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
                                <p class="text-xs text-slate-400 mt-2">Ketuk untuk pilih gambar produk</p>
                                <p class="text-[10px] text-slate-300 dark:text-slate-600 mt-1">JPG, PNG, WebP · Maks 5MB</p>
                            </div>
                        </template>
                    </div>
                </label>
                <input id="image" type="file" name="image" class="sr-only" accept="image/*" @change="onImageChange($event)">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                    <input type="text" id="price_display" inputmode="numeric"
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

            {{-- Actions: menempel di bawah layar pada mobile --}}
            <div class="fixed md:static bottom-0 inset-x-0 z-30 px-4 pt-3 pb-[max(0.75rem,env(safe-area-inset-bottom))] md:p-0 bg-white/95 dark:bg-[#090d16]/95 md:bg-transparent backdrop-blur md:backdrop-blur-none border-t md:border-0 border-slate-200 dark:border-slate-800 flex gap-2">
                <a href="{{ route("onboarding.complete") }}"
                   class="flex-1 py-3.5 px-3 rounded-2xl border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 font-semibold text-sm text-center">
                    Lewati dulu
                </a>
                <button type="submit" :disabled="submitting"
                        class="flex-[1.4] py-3.5 px-3 rounded-2xl bg-slate-900 hover:bg-slate-800 dark:bg-white dark:text-slate-900 text-white font-bold text-sm flex items-center justify-center gap-2">
                    <i x-show="submitting" x-cloak class="ph ph-spinner-gap text-[18px] animate-spin"></i>
                    <span x-text="submitting ? 'Mengunggah...' : 'Upload dan selesai'">Upload dan selesai</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
