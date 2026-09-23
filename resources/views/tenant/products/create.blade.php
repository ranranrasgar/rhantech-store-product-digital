@extends('layouts.tenant')
@section('title', isset($sourceProduct) ? 'Salin Produk' : 'Tambah Produk Digital')
@section('content')
<div class="p-3 sm:p-4 md:p-8 flex-1 max-w-4xl mx-auto w-full pb-8 md:pb-12"
     x-data="{ submitting: false, imageHasError: false }" 
     @image-validation-state.window="imageHasError = $event.detail.hasError">

    <!-- Mobile Native Top App Bar (Sticky on mobile) -->
    <div class="sticky top-0 z-30 -mx-3 -mt-3 sm:-mx-4 sm:-mt-4 md:hidden bg-white/95 dark:bg-[#000000]/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 px-4 py-3 flex items-center justify-between mb-4 shadow-xs">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('tenant.products.index') }}" class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center text-slate-700 dark:text-slate-200 active:scale-90 transition-transform shrink-0">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div class="min-w-0">
                <h1 class="font-extrabold text-sm text-zinc-900 dark:text-zinc-100 truncate leading-tight">
                    {{ isset($sourceProduct) ? 'Salin Produk' : 'Tambah Produk Baru' }}
                </h1>
                <p class="text-[10px] text-slate-400 truncate">
                    {{ isset($sourceProduct) ? 'Menyalin dari: ' . $sourceProduct->name : 'Katalog Produk Digital' }}
                </p>
            </div>
        </div>

        <button type="submit" form="product-form" :disabled="submitting || imageHasError" class="shrink-0 px-3.5 py-1.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white text-xs font-black active:scale-95 transition-all flex items-center gap-1 disabled:opacity-50">
            <span x-show="submitting" x-cloak class="material-symbols-outlined animate-spin text-[14px]">progress_activity</span>
            <span x-text="submitting ? '...' : 'Simpan'">Simpan</span>
        </button>
    </div>

    <!-- Desktop Header -->
    <div class="hidden md:flex items-center gap-4 mb-6">
        <a href="{{ route('tenant.products.index') }}" class="p-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-full transition">
            <span class="material-symbols-outlined text-[24px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-zinc-900 dark:text-zinc-100 tracking-tight">
                {{ isset($sourceProduct) ? 'Salin Produk Digital' : 'Tambah Produk Digital Baru' }}
            </h1>
            @if(isset($sourceProduct))
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                    Menyalin data dari: <span class="font-bold text-orange-600 dark:text-orange-400">{{ $sourceProduct->name }}</span>
                </p>
            @else
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                    Lengkapi informasi produk digital untuk mulai berjualan di etalase tokomu.
                </p>
            @endif
        </div>
    </div>

    <form action="{{ route('tenant.products.store') }}" 
          method="POST" 
          id="product-form"
          enctype="multipart/form-data" 
          class="space-y-4 md:space-y-6" 
          @submit="if(imageHasError) { $event.preventDefault(); alert('Mohon perbaiki foto yang melebihi batas 2 MB terlebih dahulu sebelum menyimpan.'); return false; } submitting = true">
        @csrf

        @if(isset($sourceProduct))
            <input type="hidden" name="copied_from" value="{{ $sourceProduct->id }}">
        @endif

        <!-- ========================================== -->
        <!-- CARD 1: INFORMASI UTAMA PRODUK             -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 md:p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[18px] text-orange-500">inventory_2</span>
                Informasi Utama
            </div>

            <div>
                <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                    Nama Produk <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       value="{{ old('name', isset($sourceProduct) ? $sourceProduct->name . ' (Salinan)' : '') }}" 
                       required 
                       placeholder="Contoh: Source Code Aplikasi Kasir Laravel 11 & POS"
                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                @error('name')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-data="{
                showAddCategoryModal: false,
                showAddTypeModal: false,
                newCategoryName: '',
                newTypeName: '',
                loadingCat: false,
                loadingType: false,

                async addCategory() {
                    if (!this.newCategoryName.trim()) return;
                    this.loadingCat = true;
                    try {
                        const res = await fetch('{{ route('tenant.categories.quick-store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ name: this.newCategoryName })
                        });
                        const data = await res.json();
                        if (data.success) {
                            const sel = document.getElementById('category-select');
                            const opt = document.createElement('option');
                            opt.value = data.category.id;
                            opt.text = data.category.name + ' (Toko Anda)';
                            opt.selected = true;
                            sel.appendChild(opt);
                            this.newCategoryName = '';
                            this.showAddCategoryModal = false;
                        } else {
                            alert(data.message || 'Gagal menambahkan kategori');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan');
                    } finally {
                        this.loadingCat = false;
                    }
                },

                async addType() {
                    if (!this.newTypeName.trim()) return;
                    this.loadingType = true;
                    try {
                        const res = await fetch('{{ route('tenant.types.quick-store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ name: this.newTypeName })
                        });
                        const data = await res.json();
                        if (data.success) {
                            const sel = document.getElementById('type-select');
                            const opt = document.createElement('option');
                            opt.value = data.type.id;
                            opt.text = data.type.name + ' (Toko Anda)';
                            opt.selected = true;
                            sel.appendChild(opt);
                            this.newTypeName = '';
                            this.showAddTypeModal = false;
                        } else {
                            alert(data.message || 'Gagal menambahkan tipe');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan');
                    } finally {
                        this.loadingType = false;
                    }
                }
            }">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200">
                            Kategori
                        </label>
                        <button type="button" @click="showAddCategoryModal = true" class="text-[11px] font-bold text-orange-600 dark:text-orange-400 hover:underline flex items-center gap-1 active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-[15px]">add_circle</span> + Buat Kategori
                        </button>
                    </div>
                    <select id="category-select" name="product_category_id" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        <option value="">-- Tanpa Kategori --</option>
                        <optgroup label="🌐 Kategori Platform">
                            @foreach($categories->whereNull('store_id') as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $sourceProduct->product_category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </optgroup>
                        @if($categories->whereNotNull('store_id')->count() > 0)
                        <optgroup label="🏪 Kategori Toko Anda">
                            @foreach($categories->whereNotNull('store_id') as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $sourceProduct->product_category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }} (Toko Anda)</option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                    @error('product_category_id')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200">
                            Tipe Produk
                        </label>
                        <button type="button" @click="showAddTypeModal = true" class="text-[11px] font-bold text-orange-600 dark:text-orange-400 hover:underline flex items-center gap-1 active:scale-95 transition-transform">
                            <span class="material-symbols-outlined text-[15px]">add_circle</span> + Buat Tipe
                        </button>
                    </div>
                    <select id="type-select" name="product_type_id" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        <option value="">-- Tanpa Tipe --</option>
                        <optgroup label="🌐 Tipe Platform">
                            @foreach($types->whereNull('store_id') as $type)
                            <option value="{{ $type->id }}" {{ old('product_type_id', $sourceProduct->product_type_id ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </optgroup>
                        @if($types->whereNotNull('store_id')->count() > 0)
                        <optgroup label="🏪 Tipe Toko Anda">
                            @foreach($types->whereNotNull('store_id') as $type)
                            <option value="{{ $type->id }}" {{ old('product_type_id', $sourceProduct->product_type_id ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }} (Toko Anda)</option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                    @error('product_type_id')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>

                <!-- Modal Buat Kategori Toko -->
                <div x-show="showAddCategoryModal" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 w-full max-w-sm shadow-none space-y-3" @click.away="showAddCategoryModal = false">
                        <div class="flex items-center gap-2 text-zinc-900 dark:text-zinc-100 font-bold text-sm">
                            <span class="material-symbols-outlined text-orange-500 text-[20px]">folder_open</span>
                            Tambah Kategori Toko Baru
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            Kategori ini khusus dibuat untuk toko Anda dan akan muncul di filter etalase.
                        </p>
                        <input type="text" x-model="newCategoryName" placeholder="Nama kategori baru..." class="w-full px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-100 focus:outline-none focus:border-orange-500">
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" @click="showAddCategoryModal = false" class="px-3.5 py-2 text-xs font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl">Batal</button>
                            <button type="button" @click="addCategory()" :disabled="loadingCat" class="px-4 py-2 text-xs font-bold bg-orange-500 hover:bg-orange-600 text-white rounded-xl flex items-center gap-1.5 shadow-none disabled:opacity-50">
                                <span x-show="loadingCat" class="material-symbols-outlined animate-spin text-[14px]">progress_activity</span>
                                Simpan Kategori
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Buat Tipe Toko -->
                <div x-show="showAddTypeModal" x-cloak class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-5 w-full max-w-sm shadow-none space-y-3" @click.away="showAddTypeModal = false">
                        <div class="flex items-center gap-2 text-zinc-900 dark:text-zinc-100 font-bold text-sm">
                            <span class="material-symbols-outlined text-orange-500 text-[20px]">devices</span>
                            Tambah Tipe/Platform Toko
                        </div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 leading-relaxed">
                            Tipe produk ini khusus dibuat untuk mengelompokkan aplikasi atau file digital toko Anda.
                        </p>
                        <input type="text" x-model="newTypeName" placeholder="Nama tipe baru (cth: Flutter, Excel Template)..." class="w-full px-3.5 py-2.5 text-xs md:text-sm bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-100 focus:outline-none focus:border-orange-500">
                        <div class="flex justify-end gap-2 pt-1">
                            <button type="button" @click="showAddTypeModal = false" class="px-3.5 py-2 text-xs font-semibold text-zinc-600 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl">Batal</button>
                            <button type="button" @click="addType()" :disabled="loadingType" class="px-4 py-2 text-xs font-bold bg-orange-500 hover:bg-orange-600 text-white rounded-xl flex items-center gap-1.5 shadow-none disabled:opacity-50">
                                <span x-show="loadingType" class="material-symbols-outlined animate-spin text-[14px]">progress_activity</span>
                                Simpan Tipe
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CARD 2: HARGA & AFILIASI                   -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 md:p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[18px] text-emerald-500">payments</span>
                Harga & Komisi Penjualan
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                        Harga Normal (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="price" 
                           required 
                           min="0" 
                           placeholder="Contoh: 150000"
                           value="{{ old('price', isset($sourceProduct) ? (int)$sourceProduct->price : '') }}" 
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-semibold">
                    @error('price')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                        Harga Diskon (Rp) <span class="text-xs font-normal text-slate-400">- Opsional</span>
                    </label>
                    <input type="number" 
                           name="discount_price" 
                           min="0" 
                           placeholder="Contoh: 99000 (kosongkan jika tidak ada diskon)"
                           value="{{ old('discount_price', isset($sourceProduct) && $sourceProduct->discount_price ? (int)$sourceProduct->discount_price : '') }}" 
                           class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-semibold">
                    @error('discount_price')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Bagi Hasil Komisi Afiliasi (Showcase) -->
            <div class="p-3.5 sm:p-4 rounded-xl border border-orange-500/30 bg-orange-500/[0.03] dark:bg-orange-500/[0.06] space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-500 text-[22px] shrink-0">storefront</span>
                        <div>
                            <h4 class="text-xs font-bold text-zinc-900 dark:text-zinc-100">Bagi Hasil Komisi Afiliasi (Etalase Showcase)</h4>
                            <p class="text-[11px] text-zinc-500 dark:text-zinc-400">Izinkan toko lain memajang produk ini di etalase mereka dan dapatkan komisi saat terjual.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="is_affiliate_enabled" value="1" {{ old('is_affiliate_enabled', $sourceProduct->is_affiliate_enabled ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2.5 border-t border-orange-500/20">
                    <div>
                        <label class="block text-xs font-semibold text-zinc-800 dark:text-zinc-200 mb-1">Persentase Komisi Afiliasi (%) *</label>
                        <div class="relative">
                            <input type="number" name="affiliate_commission_rate" min="0" max="100" step="0.5" 
                                   value="{{ old('affiliate_commission_rate', $sourceProduct->affiliate_commission_rate ?? 10) }}" 
                                   placeholder="10" 
                                   class="w-full pl-3.5 pr-8 py-2 bg-white dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs font-bold text-zinc-900 dark:text-zinc-100 focus:outline-none focus:border-orange-500">
                            <span class="absolute right-3 top-2 text-xs font-bold text-slate-400">%</span>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block">Default platform: 10%. Semakin tinggi komisi, semakin menarik bagi toko lain.</span>
                    </div>
                    <div class="bg-white/60 dark:bg-slate-800/40 rounded-xl p-2.5 flex flex-col justify-center border border-slate-200/50 dark:border-slate-800/50 text-[11px] text-zinc-500 dark:text-zinc-400">
                        <span class="font-bold text-zinc-800 dark:text-zinc-200 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[15px] text-amber-500">payments</span> Simulasi Etalase:
                        </span>
                        <span class="mt-0.5">Mitra afiliasi akan melihat nominal komisi ini saat memajang produk Anda di toko mereka.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CARD 3: FOTO PRODUK (MAKS 5)               -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 md:p-6 shadow-xs space-y-4" x-data="productImageValidator()">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 flex-wrap gap-1">
                <div class="flex items-center gap-2 text-xs font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[18px] text-indigo-500">photo_library</span>
                    Foto Produk (Maks 5) {{ isset($sourceProduct) && $sourceProduct->images->count() > 0 ? '(Opsional)' : '*' }}
                </div>
                <span class="text-[11px] text-zinc-500 dark:text-zinc-400 flex items-center gap-1 font-medium">
                    <span class="material-symbols-outlined text-[14px] text-orange-500">verified</span>
                    Maks. 2 MB per foto
                </span>
            </div>

            <!-- Ketentuan Info Box -->
            <div class="p-3 rounded-xl bg-teal-500/10 border border-teal-500/20 text-xs text-teal-800 dark:text-teal-300 flex items-center justify-between gap-2 flex-wrap">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-[#00838f] dark:text-teal-400 shrink-0">info</span>
                    <span><strong>Ketentuan:</strong> Maks <strong>2 MB</strong> / foto. Format: JPG, JPEG, PNG, WEBP, GIF.</span>
                </div>
                <span class="text-[11px] font-bold text-teal-700 dark:text-teal-300">
                    Maks. 5 file
                </span>
            </div>
            
            @if(isset($sourceProduct) && $sourceProduct->images->count() > 0)
                <div class="p-3 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2 font-semibold">Foto yang disalin dari produk asal (unggah foto baru jika ingin mengganti):</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sourceProduct->images as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-16 h-16 object-cover rounded-xl border border-zinc-200 dark:border-zinc-800">
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="relative">
                <input type="file" 
                       name="images[]" 
                       multiple 
                       accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" 
                       @change="validateFiles($event)" 
                       {{ isset($sourceProduct) && $sourceProduct->images->count() > 0 ? '' : 'required' }} 
                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:border-orange-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-orange-500/10 file:text-orange-600 dark:file:text-orange-400 hover:file:bg-orange-500/20 cursor-pointer" 
                       :class="hasOversized ? 'border-rose-500 ring-1 ring-rose-500/30' : ''">
            </div>

            <!-- Alert Error Validasi File Melebihi 2MB -->
            <div x-show="errorMessage" x-cloak class="p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 rounded-xl flex items-start gap-2.5 text-rose-700 dark:text-rose-300 text-xs">
                <span class="material-symbols-outlined text-[18px] shrink-0 text-rose-600 mt-0.5">warning</span>
                <div>
                    <strong class="font-bold block mb-0.5 text-rose-800 dark:text-rose-200">Peringatan Ukuran Foto:</strong>
                    <span x-text="errorMessage"></span>
                </div>
            </div>

            <!-- Pratinjau Gambar Terpilih -->
            <div x-show="files.length > 0" x-cloak class="p-3 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl">
                <div class="flex items-center justify-between flex-wrap gap-2 mb-2.5">
                    <p class="text-xs font-semibold text-zinc-800 dark:text-zinc-200 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm" :class="hasOversized ? 'text-rose-600' : 'text-emerald-600'" x-text="hasOversized ? 'error' : 'check_circle'"></span>
                        <span x-text="files.length + ' foto dipilih (Total: ' + totalSizeFormatted + '):'"></span>
                    </p>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full"
                          :class="hasOversized ? 'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' : 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300'"
                          x-text="hasOversized ? '⚠️ Ada foto > 2 MB' : '✓ Semua foto aman (< 2 MB)'"></span>
                </div>
                <div class="flex flex-wrap gap-2.5">
                    <template x-for="(f, i) in files" :key="i">
                        <div class="relative rounded-xl overflow-hidden w-20 h-20 bg-white dark:bg-slate-800 shadow-xs border"
                             :class="f.isOversized ? 'border-2 border-rose-500' : 'border-zinc-200 dark:border-zinc-800'">
                            <img :src="f.previewUrl" class="w-full h-full object-cover">
                            <div class="absolute bottom-0 inset-x-0 p-0.5 text-[9px] truncate text-center font-mono font-bold"
                                 :class="f.isOversized ? 'bg-rose-600 text-white' : 'bg-slate-900/80 text-white'">
                                <span x-text="f.size"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            @error('images')<span class="text-rose-500 text-xs block mt-1">{{ $message }}</span>@enderror
            @error('images.*')<span class="text-rose-500 text-xs block mt-1">{{ $message }}</span>@enderror
        </div>

        <!-- ========================================== -->
        <!-- CARD 4: DESKRIPSI PRODUK                   -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 md:p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[18px] text-amber-500">description</span>
                Deskripsi Produk
            </div>

            <div>
                <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                    Ringkasan Singkat (Short Description) <span class="text-xs font-normal text-slate-400">- Opsional</span>
                </label>
                <textarea name="short_description" rows="2" placeholder="Ringkasan singkat produk untuk tampilan kartu etalase..." class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">{{ old('short_description', $sourceProduct->short_description ?? '') }}</textarea>
                @error('short_description')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                    Deskripsi Lengkap <span class="text-rose-500">*</span>
                </label>
                <div id="editor-container" class="w-full bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-b-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100" style="min-height: 220px;"></div>
                <input type="hidden" name="description" id="description" value="{{ old('description', $sourceProduct->description ?? '') }}">
                @error('description')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CARD 5: TAUTAN UNDUHAN & DEMO              -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 md:p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[18px] text-cyan-500">cloud_download</span>
                Tautan File & Unduhan Pembeli
            </div>

            <div>
                <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-1.5">
                    Demo URL <span class="text-xs font-normal text-slate-400">- Opsional</span>
                </label>
                <input type="url" name="demo_url" value="{{ old('demo_url', $sourceProduct->demo_url ?? '') }}" placeholder="https://demo-aplikasi.com" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                @error('demo_url')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div class="p-3.5 md:p-4 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl space-y-3">
                <div class="flex justify-between items-center flex-wrap gap-2">
                    <div>
                        <label class="block text-xs md:text-sm font-bold text-zinc-800 dark:text-zinc-200">
                            Tautan Unduhan Eksternal <span class="text-rose-500">*</span>
                        </label>
                        <p class="text-[11px] text-slate-400">Tautan file produk (Google Drive, Dropbox, Mega) yang akan dikirim otomatis ke email pembeli.</p>
                    </div>
                    <button type="button" onclick="addLink()" class="text-xs bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded-xl font-bold transition-all shadow-xs flex items-center gap-1 active:scale-95">
                        <span class="material-symbols-outlined text-[16px]">add</span> Tambah Link
                    </button>
                </div>
                
                <div id="links-container" class="flex flex-col gap-2.5">
                    @php 
                        $downloadLinks = old('download_links', isset($sourceProduct) ? ($sourceProduct->download_links ?? []) : []); 
                    @endphp
                    @if(!empty($downloadLinks) && is_array($downloadLinks))
                        @foreach($downloadLinks as $idx => $link)
                        <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center p-2.5 sm:p-0 bg-white dark:bg-[#000000] sm:bg-transparent rounded-xl border sm:border-0 border-zinc-200 dark:border-zinc-800">
                            <div class="flex-1">
                                <input type="text" name="download_links[{{ $idx }}][name]" value="{{ $link['name'] ?? '' }}" placeholder="Nama Link (cth: Full Source Code)" required class="w-full px-3 py-2 bg-slate-50 sm:bg-white dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100">
                            </div>
                            <div class="flex-[2]">
                                <input type="url" name="download_links[{{ $idx }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="https://drive.google.com/..." required class="w-full px-3 py-2 bg-slate-50 sm:bg-white dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100">
                            </div>
                            <div class="flex justify-end sm:block">
                                <button type="button" onclick="if(document.querySelectorAll('#links-container > div').length > 1) this.closest('.flex').remove(); else alert('Minimal 1 tautan harus diisi.');" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors flex items-center gap-1 text-xs" title="Hapus Link">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                    <span class="sm:hidden text-xs font-semibold">Hapus</span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center p-2.5 sm:p-0 bg-white dark:bg-[#000000] sm:bg-transparent rounded-xl border sm:border-0 border-zinc-200 dark:border-zinc-800">
                            <div class="flex-1">
                                <input type="text" name="download_links[0][name]" placeholder="Nama Link (cth: Full Source Code ZIP)" required class="w-full px-3 py-2 bg-slate-50 sm:bg-white dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100">
                            </div>
                            <div class="flex-[2]">
                                <input type="url" name="download_links[0][url]" placeholder="https://drive.google.com/..." required class="w-full px-3 py-2 bg-slate-50 sm:bg-white dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100">
                            </div>
                            <div class="flex justify-end sm:block">
                                <button type="button" onclick="if(document.querySelectorAll('#links-container > div').length > 1) this.closest('.flex').remove(); else alert('Minimal 1 tautan harus diisi.');" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors flex items-center gap-1 text-xs" title="Hapus Link">
                                    <span class="material-symbols-outlined text-base">delete</span>
                                    <span class="sm:hidden text-xs font-semibold">Hapus</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CARD 6: TAGS & SEO                         -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 md:p-6 shadow-xs space-y-4"
             x-data="{
                tagsString: '{{ addslashes(old('tags', $sourceProduct->tags ?? '')) }}',
                tagsList: [],
                generating: false,
                init() {
                    this.updateList();
                    this.$watch('tagsString', () => this.updateList());
                },
                updateList() {
                    if (!this.tagsString) {
                        this.tagsList = [];
                        return;
                    }
                    this.tagsList = this.tagsString.split(',')
                        .map(t => t.trim())
                        .filter(t => t.length > 0);
                },
                removeTag(index) {
                    this.tagsList.splice(index, 1);
                    this.tagsString = this.tagsList.join(', ');
                },
                addTag(tag) {
                    tag = tag.trim().replace(/^#+/, '');
                    if (!tag) return;
                    if (!this.tagsList.map(t => t.toLowerCase()).includes(tag.toLowerCase())) {
                        this.tagsList.push(tag);
                        this.tagsString = this.tagsList.join(', ');
                    }
                },
                generateTags() {
                    const nameInput = document.querySelector('input[name=\'name\']')?.value || '';
                    const shortDesc = document.querySelector('textarea[name=\'short_description\']')?.value || '';
                    const quillElem = document.querySelector('#editor-container .ql-editor');
                    const descInput = (quillElem ? quillElem.innerText : '') || document.querySelector('input[name=\'description\']')?.value || document.querySelector('textarea[name=\'description\']')?.value || '';
                    const catSelect = document.getElementById('category-select');
                    const catText = catSelect && catSelect.selectedIndex > 0 ? catSelect.options[catSelect.selectedIndex].text.replace(/\(.*\)/, '').trim() : '';
                    const typeSelect = document.getElementById('type-select');
                    const typeText = typeSelect && typeSelect.selectedIndex > 0 ? typeSelect.options[typeSelect.selectedIndex].text.replace(/\(.*\)/, '').trim() : '';

                    if (!nameInput.trim() && !shortDesc.trim() && !descInput.trim()) {
                        alert('Silakan isi minimal Nama Produk, Ringkasan, atau Deskripsi terlebih dahulu untuk men-generate tags otomatis.');
                        return;
                    }

                    this.generating = true;
                    const combined = `${nameInput} ${catText} ${typeText} ${shortDesc} ${descInput}`;
                    
                    const techDictionary = [
                        'Laravel', 'PHP', 'CodeIgniter', 'Vue', 'React', 'React Native', 'Flutter',
                        'Tailwind CSS', 'Bootstrap', 'Node.js', 'Python', 'Django', 'Flask', 'WordPress',
                        'HTML5', 'CSS3', 'JavaScript', 'TypeScript', 'MySQL', 'PostgreSQL', 'SQLite',
                        'REST API', 'Inertia.js', 'Livewire', 'Alpine.js', 'Android', 'iOS', 'PWA'
                    ];

                    const businessDictionary = [
                        'Aplikasi Kasir', 'Point of Sale', 'POS', 'Toko Online', 'E-Commerce', 'Marketplace',
                        'Sistem Informasi', 'Sistem Informasi Sekolah', 'SIAKAD', 'Manajemen Sekolah',
                        'Aplikasi Keuangan', 'Akuntansi', 'Koperasi', 'Simpan Pinjam', 'Manajemen Kas',
                        'Aplikasi Bengkel', 'Aplikasi Dealer', 'Manajemen Inventaris', 'Stok Barang',
                        'Absensi Online', 'Presensi Pegawai', 'HRIS', 'Payroll', 'Penggajian',
                        'Aplikasi Rumah Sakit', 'Klinik', 'Apotek', 'Manajemen Rekam Medis',
                        'Aplikasi Restoran', 'Cafe', 'Pemesanan Menu', 'Food Ordering',
                        'Company Profile', 'Portofolio', 'Landing Page', 'Admin Template', 'Dashboard',
                        'CRM', 'ERP', 'Ticketing', 'Helpdesk', 'Rental Mobil', 'Sistem Pakar',
                        'Source Code Web', 'Source Code Mobile', 'Source Code'
                    ];

                    const stopwords = new Set([
                        'dan', 'atau', 'yang', 'untuk', 'dengan', 'pada', 'dari', 'dalam', 'bisa', 'akan',
                        'adalah', 'fitur', 'lengkap', 'gratis', 'terbaru', 'berbasis', 'sistem', 'aplikasi',
                        'source', 'code', 'web', 'jual', 'beli', 'murah', 'pro', 'v1', 'v2', 'v3', 'new',
                        'full', 'paket', 'cara', 'oleh', 'ke', 'di', 'ini', 'itu', 'juga', 'serta', 'bagi',
                        'tentang', 'seperti', 'kami', 'anda', 'kamu', 'saya', 'kita', 'mereka'
                    ]);

                    const extractedTags = new Set();
                    techDictionary.forEach(term => {
                        const regex = new RegExp('\\b' + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\b', 'i');
                        if (regex.test(combined)) extractedTags.add(term);
                    });

                    businessDictionary.forEach(term => {
                        const regex = new RegExp('\\b' + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\b', 'i');
                        if (regex.test(combined)) extractedTags.add(term);
                    });

                    if (nameInput.trim()) {
                        const words = nameInput
                            .replace(/[^\w\s-]/gi, ' ')
                            .split(/\s+/)
                            .map(w => w.trim())
                            .filter(w => w.length >= 3 && !stopwords.has(w.toLowerCase()) && !/^\d+$/.test(w));

                        words.forEach(w => {
                            const formatted = w.charAt(0).toUpperCase() + w.slice(1);
                            extractedTags.add(formatted);
                        });
                    }

                    if (catText && !stopwords.has(catText.toLowerCase())) extractedTags.add(catText);
                    if (typeText && !stopwords.has(typeText.toLowerCase())) extractedTags.add(typeText);

                    const finalTags = Array.from(extractedTags).slice(0, 10);
                    finalTags.forEach(t => this.addTag(t));

                    setTimeout(() => {
                        this.generating = false;
                    }, 350);
                }
             }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-2 text-xs font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[18px] text-purple-500">tag</span>
                    Kata Kunci Pencarian & SEO
                </div>

                <button type="button" 
                        @click="generateTags()" 
                        :disabled="generating"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-300 dark:border-amber-700/60 text-xs font-bold transition-all shrink-0 active:scale-95">
                    <span class="material-symbols-outlined text-[16px] text-amber-600 dark:text-amber-400" :class="{ 'animate-spin': generating }">
                        auto_awesome
                    </span>
                    <span x-text="generating ? 'Menganalisis...' : '⚡ Generate Tags Otomatis'"></span>
                </button>
            </div>

            <div>
                <input type="text" 
                       name="tags" 
                       x-model="tagsString"
                       placeholder="Contoh: Aplikasi Kasir, POS, Toko Online, Laravel 11, PHP MySQL" 
                       class="w-full px-4 py-2.5 bg-slate-50 dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                @error('tags')<span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span>@enderror

                <div class="mt-2.5 flex flex-wrap items-center gap-1.5 min-h-[28px]" x-show="tagsList.length > 0">
                    <span class="text-[11px] text-slate-400 font-medium mr-1">Preview Tag:</span>
                    <template x-for="(tag, index) in tagsList" :key="index">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-500/10 text-orange-700 dark:text-orange-300 border border-orange-500/20">
                            <span class="text-orange-500">#</span>
                            <span x-text="tag"></span>
                            <button type="button" @click="removeTag(index)" class="hover:text-rose-500 text-slate-400 ml-0.5">
                                <span class="material-symbols-outlined text-[14px]">close</span>
                            </button>
                        </span>
                    </template>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- CARD 7: KUSTOMISASI DETAIL (FAQ & BADGES)  -->
        <!-- ========================================== -->
        @include('products._custom_fields', ['productItem' => $sourceProduct ?? null])

        <!-- ========================================== -->
        <!-- CARD 8: STATUS PUBLIKASI & MODERASI        -->
        <!-- ========================================== -->
        <div class="bg-white dark:bg-[#000000] rounded-2xl border border-zinc-200 dark:border-zinc-800 p-4 md:p-6 shadow-xs space-y-4">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100 dark:border-slate-800 text-xs font-bold text-zinc-900 dark:text-zinc-100 uppercase tracking-wider">
                <span class="material-symbols-outlined text-[18px] text-teal-500">verified</span>
                Status Publikasi & Moderasi
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($sourceProduct) ? $sourceProduct->is_active : true) ? 'checked' : '' }} class="w-5 h-5 rounded-lg border-zinc-300 dark:border-zinc-800 text-orange-500 focus:ring-0">
                    <div>
                        <span class="text-xs md:text-sm font-bold text-zinc-900 dark:text-zinc-100 block">Aktifkan Produk di Toko</span>
                        <span class="text-[11px] text-slate-400">Tampil di etalase toko setelah diverifikasi dan disetujui platform.</span>
                    </div>
                </label>
            </div>

            <!-- Info Moderasi Admin -->
            <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-300 text-xs flex items-start gap-2.5 leading-relaxed">
                <span class="material-symbols-outlined text-[18px] text-amber-500 shrink-0 mt-0.5">verified_user</span>
                <div>
                    <span class="font-bold block mb-0.5">Verifikasi Kualitas & Link Aktif oleh Admin:</span>
                    Demi mencegah produk fiktif dan memastikan tautan unduhan benar-benar aktif untuk pembeli, produk baru akan otomatis berstatus <span class="font-bold text-amber-600 dark:text-amber-400">In Review</span> untuk ditinjau oleh tim moderator sebelum tampil publik.
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- FORM FOOTER ACTION BAR                     -->
        <!-- ========================================== -->
        <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-800 flex-wrap gap-3">
            <div x-show="imageHasError" x-cloak class="text-xs text-rose-600 font-semibold flex items-center gap-1 w-full sm:w-auto">
                <span class="material-symbols-outlined text-sm">error</span>
                <span>Ada foto yang melebihi batas 2 MB. Harap ganti foto sebelum menyimpan.</span>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto sm:ml-auto">
                <a href="{{ route('tenant.products.index') }}" class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300 text-xs sm:text-sm font-bold hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors text-center active:scale-95">
                    Batal
                </a>
                <button type="submit" :disabled="submitting || imageHasError" class="flex-1 sm:flex-none px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white dark:bg-orange-600 dark:hover:bg-orange-500 dark:text-white rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed active:scale-95" :class="imageHasError ? 'bg-slate-400 cursor-not-allowed' : ''">
                    <span x-show="submitting" x-cloak class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                    <span x-text="submitting ? 'Menyimpan Produk...' : '{{ isset($sourceProduct) ? 'Simpan Salinan Produk' : 'Simpan Produk' }}'"></span>
                </button>
            </div>
        </div>

    </form>

</div>

<script>
    function productImageValidator() {
        return {
            files: [],
            errorMessage: '',
            hasOversized: false,
            totalSizeFormatted: '0 KB',
            maxSizePerFile: 2 * 1024 * 1024, // 2MB
            validateFiles(event) {
                const input = event.target;
                const selectedFiles = Array.from(input.files);
                this.errorMessage = '';
                this.files = [];
                this.hasOversized = false;

                if (selectedFiles.length === 0) {
                    window.dispatchEvent(new CustomEvent('image-validation-state', { detail: { hasError: false } }));
                    return;
                }

                if (selectedFiles.length > 5) {
                    this.errorMessage = 'Maksimal hanya boleh memilih 5 foto produk.';
                    this.hasOversized = true;
                    input.value = '';
                    window.dispatchEvent(new CustomEvent('image-validation-state', { detail: { hasError: true } }));
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];
                const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp', '.gif'];
                let totalBytes = 0;
                let oversizedList = [];

                for (let file of selectedFiles) {
                    const ext = '.' + file.name.split('.').pop().toLowerCase();
                    const isTypeValid = (file.type && file.type.startsWith('image/') && allowedTypes.includes(file.type)) || allowedExtensions.includes(ext);

                    if (!isTypeValid) {
                        this.errorMessage = `File "${file.name}" bukan format gambar yang valid! Format yang diperbolehkan: JPG, JPEG, PNG, WEBP, GIF.`;
                        this.hasOversized = true;
                        input.value = '';
                        this.files = [];
                        window.dispatchEvent(new CustomEvent('image-validation-state', { detail: { hasError: true } }));
                        return;
                    }

                    totalBytes += file.size;
                    const isOver = file.size > this.maxSizePerFile;
                    const sizeFormatted = file.size >= 1024 * 1024 
                        ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' 
                        : (file.size / 1024).toFixed(1) + ' KB';

                    if (isOver) {
                        oversizedList.push(`"${file.name}" (${sizeFormatted})`);
                    }

                    this.files.push({
                        name: file.name,
                        size: sizeFormatted,
                        isOversized: isOver,
                        previewUrl: URL.createObjectURL(file)
                    });
                }

                this.totalSizeFormatted = totalBytes >= 1024 * 1024 
                    ? (totalBytes / (1024 * 1024)).toFixed(2) + ' MB' 
                    : (totalBytes / 1024).toFixed(1) + ' KB';

                if (oversizedList.length > 0) {
                    this.hasOversized = true;
                    this.errorMessage = `Ukuran foto melebihi batas 2 MB: ${oversizedList.join(', ')}. Harap kompres foto atau gunakan foto di bawah 2 MB.`;
                    input.value = '';
                    window.dispatchEvent(new CustomEvent('image-validation-state', { detail: { hasError: true } }));
                    return;
                }

                this.hasOversized = false;
                window.dispatchEvent(new CustomEvent('image-validation-state', { detail: { hasError: false } }));
            }
        };
    }

    function addLink() {
        const container = document.getElementById('links-container');
        const index = container.children.length;
        
        const row = document.createElement('div');
        row.className = 'flex flex-col sm:flex-row gap-2 items-stretch sm:items-center p-2.5 sm:p-0 bg-white dark:bg-[#000000] sm:bg-transparent rounded-xl border sm:border-0 border-zinc-200 dark:border-zinc-800';
        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="download_links[${index}][name]" placeholder="Nama Link (cth: Full Source Code)" required class="w-full px-3 py-2 bg-slate-50 sm:bg-white dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100">
            </div>
            <div class="flex-[2]">
                <input type="url" name="download_links[${index}][url]" placeholder="https://drive.google.com/..." required class="w-full px-3 py-2 bg-slate-50 sm:bg-white dark:bg-[#0c1220] border border-zinc-200 dark:border-zinc-800 rounded-xl text-xs md:text-sm text-zinc-900 dark:text-zinc-100">
            </div>
            <div class="flex justify-end sm:block">
                <button type="button" onclick="if(document.querySelectorAll('#links-container > div').length > 1) this.closest('.flex').remove(); else alert('Minimal 1 tautan harus diisi.');" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition-colors flex items-center gap-1 text-xs" title="Hapus Link">
                    <span class="material-symbols-outlined text-base">delete</span>
                    <span class="sm:hidden text-xs font-semibold">Hapus</span>
                </button>
            </div>
        `;
        container.appendChild(row);
    }
</script>

<!-- Quill Rich Text Editor -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.75rem;
        border-top-right-radius: 0.75rem;
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.75rem;
        border-bottom-right-radius: 0.75rem;
        background-color: #ffffff;
        border-color: #e2e8f0;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .dark .ql-toolbar.ql-snow {
        background-color: #0c1220;
        border-color: #222f49;
    }
    .dark .ql-container.ql-snow {
        background-color: #090d16;
        border-color: #222f49;
        color: #f1f5f9;
    }
    .dark .ql-snow .ql-stroke { stroke: #cbd5e1; }
    .dark .ql-snow .ql-fill { fill: #cbd5e1; }
    .dark .ql-snow .ql-picker { color: #cbd5e1; }
</style>
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    function initQuillProductCreate() {
        var editorElem = document.getElementById('editor-container');
        if (!editorElem || editorElem.__quill_initialized) return;

        editorElem.innerHTML = '';
        var prevToolbar = editorElem.previousElementSibling;
        if (prevToolbar && prevToolbar.classList.contains('ql-toolbar')) {
            prevToolbar.remove();
        }

        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tuliskan deskripsi lengkap, fitur utama, dan spesifikasi produk digital Anda di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
        editorElem.__quill_initialized = true;

        var descriptionInput = document.getElementById('description');
        if (descriptionInput && descriptionInput.value) {
            quill.root.innerHTML = descriptionInput.value;
        }

        quill.on('text-change', function() {
            var html = quill.root.innerHTML;
            descriptionInput.value = (html === '<p><br></p>' || quill.getText().trim().length === 0) ? '' : html;
        });

        var toolbar = editorElem.previousElementSibling;
        if (toolbar && toolbar.classList.contains('ql-toolbar')) {
            toolbar.classList.add('rounded-t-xl');
        }
        editorElem.classList.add('border-t-0', 'rounded-b-xl');

        var productForm = editorElem.closest('form');
        if (productForm) {
            productForm.addEventListener('submit', function(e) {
                var html = quill.root.innerHTML;
                descriptionInput.value = (html === '<p><br></p>' || quill.getText().trim().length === 0) ? '' : html;
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initQuillProductCreate);
    document.addEventListener('livewire:navigated', initQuillProductCreate);
</script>
@endsection
