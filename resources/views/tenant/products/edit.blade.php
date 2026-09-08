@extends('layouts.tenant')
@section('title', 'Edit Digital Product')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-4xl mx-auto w-full">
    <div class="flex items-center gap-md mb-lg">
        <a href="{{ route('tenant.products.index') }}" class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-headline-md font-bold text-on-surface">Edit Digital Product</h2>
    </div>

    @if($product->approval_status === 'rejected')
    <div class="mb-4 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-300 dark:border-rose-900 text-rose-800 dark:text-rose-200">
        <div class="flex items-start gap-2.5">
            <span class="material-symbols-outlined text-rose-600 dark:text-rose-400 text-2xl shrink-0 mt-0.5">error</span>
            <div>
                <h4 class="font-bold text-sm">Produk ini sebelumnya Ditolak oleh Platform</h4>
                <p class="text-xs mt-1 text-rose-700 dark:text-rose-300">
                    <strong>Alasan penolakan:</strong> {{ $product->rejection_reason ?? 'Mohon periksa kesesuaian deskripsi, gambar, atau tautan unduhan.' }}
                </p>
                <p class="text-[11px] mt-1.5 text-rose-600 dark:text-rose-400">
                    💡 <em>Silakan perbaiki data yang belum sesuai lalu klik "Update Product". Status produk akan otomatis diajukan kembali untuk ditinjau oleh Admin Platform.</em>
                </p>
            </div>
        </div>
    </div>
    @elseif($product->approval_status === 'pending')
    <div class="mb-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-900 text-amber-800 dark:text-amber-200">
        <div class="flex items-start gap-2.5">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-2xl shrink-0 mt-0.5">hourglass_empty</span>
            <div>
                <h4 class="font-bold text-sm">Produk Sedang Dalam Peninjauan (In Review)</h4>
                <p class="text-xs mt-1 text-amber-700 dark:text-amber-300">
                    Tim platform sedang memverifikasi tautan unduhan, deskripsi, dan foto produk ini agar terjamin keaslian dan keamanannya sebelum tayang di publik.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-surface rounded-md border border-outline-variant  p-lg">
        <form action="{{ route('tenant.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg" x-data="{ submitting: false }" @submit="submitting = true">
            @csrf @method('PUT')

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Product Name *</label>
                <input type="text" name="name" required value="{{ old('name', $product->name) }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md" x-data="{
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
                    <div class="flex items-center justify-between mb-xs">
                        <label class="block font-label-md text-on-surface">Kategori</label>
                        <button type="button" @click="showAddCategoryModal = true" class="text-xs font-semibold text-primary hover:underline flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-sm">add_circle</span> Buat Kategori Toko
                        </button>
                    </div>
                    <select id="category-select" name="product_category_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- Tanpa Kategori --</option>
                        <optgroup label="🌐 Kategori Platform">
                            @foreach($categories->whereNull('store_id') as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </optgroup>
                        @if($categories->whereNotNull('store_id')->count() > 0)
                        <optgroup label="🏪 Kategori Toko Anda">
                            @foreach($categories->whereNotNull('store_id') as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $product->product_category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }} (Toko Anda)</option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                    @error('product_category_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <div class="flex items-center justify-between mb-xs">
                        <label class="block font-label-md text-on-surface">Tipe Produk</label>
                        <button type="button" @click="showAddTypeModal = true" class="text-xs font-semibold text-primary hover:underline flex items-center gap-0.5">
                            <span class="material-symbols-outlined text-sm">add_circle</span> Buat Tipe Toko
                        </button>
                    </div>
                    <select id="type-select" name="product_type_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- Tanpa Tipe --</option>
                        <optgroup label="🌐 Tipe Platform">
                            @foreach($types->whereNull('store_id') as $type)
                            <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                            @endforeach
                        </optgroup>
                        @if($types->whereNotNull('store_id')->count() > 0)
                        <optgroup label="🏪 Tipe Toko Anda">
                            @foreach($types->whereNotNull('store_id') as $type)
                            <option value="{{ $type->id }}" {{ old('product_type_id', $product->product_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }} (Toko Anda)</option>
                            @endforeach
                        </optgroup>
                        @endif
                    </select>
                    @error('product_type_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>

                <!-- Modal Buat Kategori Toko -->
                <div x-show="showAddCategoryModal" x-cloak class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
                    <div class="bg-surface rounded-xl border border-outline-variant p-5 w-full max-w-sm shadow-xl" @click.away="showAddCategoryModal = false">
                        <h3 class="text-sm font-bold text-on-surface mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-lg">folder_open</span>
                            Tambah Kategori Toko Anda
                        </h3>
                        <p class="text-xs text-on-surface-variant mb-3">Kategori ini khusus dibuat untuk toko Anda dan akan muncul di filter katalog.</p>
                        <input type="text" x-model="newCategoryName" placeholder="Nama kategori baru..." class="w-full px-3 py-2 text-sm bg-surface-container-lowest border border-outline-variant rounded-lg mb-3 focus:outline-none focus:border-primary">
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showAddCategoryModal = false" class="px-3 py-1.5 text-xs text-on-surface-variant hover:bg-surface-container rounded-lg">Batal</button>
                            <button type="button" @click="addCategory()" :disabled="loadingCat" class="px-3.5 py-1.5 text-xs font-bold bg-primary text-white rounded-lg hover:opacity-90 flex items-center gap-1">
                                <span x-show="loadingCat" class="material-symbols-outlined animate-spin text-xs">progress_activity</span>
                                Simpan Kategori
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Buat Tipe Toko -->
                <div x-show="showAddTypeModal" x-cloak class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
                    <div class="bg-surface rounded-xl border border-outline-variant p-5 w-full max-w-sm shadow-xl" @click.away="showAddTypeModal = false">
                        <h3 class="text-sm font-bold text-on-surface mb-2 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-lg">devices</span>
                            Tambah Tipe Toko Anda
                        </h3>
                        <p class="text-xs text-on-surface-variant mb-3">Tipe/platform ini khusus dibuat untuk produk toko Anda.</p>
                        <input type="text" x-model="newTypeName" placeholder="Nama tipe/platform baru..." class="w-full px-3 py-2 text-sm bg-surface-container-lowest border border-outline-variant rounded-lg mb-3 focus:outline-none focus:border-primary">
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showAddTypeModal = false" class="px-3 py-1.5 text-xs text-on-surface-variant hover:bg-surface-container rounded-lg">Batal</button>
                            <button type="button" @click="addType()" :disabled="loadingType" class="px-3.5 py-1.5 text-xs font-bold bg-primary text-white rounded-lg hover:opacity-90 flex items-center gap-1">
                                <span x-show="loadingType" class="material-symbols-outlined animate-spin text-xs">progress_activity</span>
                                Simpan Tipe
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Price (Rp) *</label>
                    <input type="number" name="price" required min="0" value="{{ old('price', (int)$product->price) }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Discount Price (Rp) - Optional</label>
                    <input type="number" name="discount_price" min="0" value="{{ old('discount_price', ($product->discount_price && $product->discount_price > 0) ? (int)$product->discount_price : '') }}" placeholder="Kosongkan jika tidak ada diskon" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    <p class="text-[11px] text-on-surface-variant mt-1">Kosongkan atau isi 0 jika tidak ada harga diskon (promo).</p>
                    @error('discount_price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Ringkasan Singkat (Short Description) - Opsional</label>
                <textarea name="short_description" rows="2" placeholder="Ringkasan singkat produk untuk tampilan kartu etalase..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('short_description', $product->short_description) }}</textarea>
                @error('short_description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Description *</label>
                <textarea name="description" rows="5" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('description', $product->description) }}</textarea>
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Demo URL (Optional)</label>
                <input type="url" name="demo_url" value="{{ old('demo_url', $product->demo_url) }}" placeholder="https://..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('demo_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>


            <div class="p-md bg-surface-container-low border border-outline-variant rounded-lg">
                <div class="flex justify-between items-center mb-xs">
                    <label class="block font-label-md text-on-surface">Tautan Unduhan Eksternal <span class="text-error">*</span></label>
                    <button type="button" onclick="addLink()" class="text-xs bg-primary text-white px-3 py-1 rounded font-bold hover:brightness-110 transition">+ Tambah Link</button>
                </div>
                <p class="text-xs text-on-surface-variant mb-4">Tambahkan minimal 1 tautan eksternal (contoh: Google Drive, Mega) yang akan dikirim ke email pembeli.</p>
                
                <div id="links-container" class="flex flex-col gap-sm">
                    @php $links = old('download_links', $product->download_links ?? []); @endphp
                    @if(!empty($links) && is_array($links))
                        @foreach($links as $index => $link)
                        <div class="flex gap-2 items-start">
                            <div class="flex-1">
                                <input type="text" name="download_links[{{ $index }}][name]" value="{{ $link['name'] ?? '' }}" placeholder="Nama Link (cth: Source Code)" required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm mb-1">
                            </div>
                            <div class="flex-[2]">
                                <input type="url" name="download_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="https://..." required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm">
                            </div>
                            <button type="button" onclick="if(document.querySelectorAll('#links-container > div').length > 1) this.parentElement.remove(); else alert('Minimal 1 tautan harus diisi.');" class="p-2 text-error hover:bg-error/10 rounded-lg transition" title="Hapus Link">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="flex gap-2 items-start">
                            <div class="flex-1">
                                <input type="text" name="download_links[0][name]" placeholder="Nama Link (cth: Source Code)" required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm mb-1">
                            </div>
                            <div class="flex-[2]">
                                <input type="url" name="download_links[0][url]" placeholder="https://..." required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm">
                            </div>
                            <button type="button" onclick="if(document.querySelectorAll('#links-container > div').length > 1) this.parentElement.remove(); else alert('Minimal 1 tautan harus diisi.');" class="p-2 text-error hover:bg-error/10 rounded-lg transition" title="Hapus Link">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div x-data="productImageValidator({{ $product->images->count() }})">
                <div class="flex items-center justify-between mb-xs">
                    <label class="block font-label-md text-on-surface">Tambah Foto Produk Baru</label>
                    <span class="text-[11px] text-on-surface-variant">Maks. 2 MB per foto (Total maksimal 5 foto)</span>
                </div>
                
                <div class="relative">
                    <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" @change="validateFiles($event)" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                </div>

                <!-- Alert Error Validasi File -->
                <div x-show="errorMessage" x-cloak class="mt-2 p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 rounded-lg flex items-start gap-2 text-rose-700 dark:text-rose-300 text-xs">
                    <span class="material-symbols-outlined text-[18px] shrink-0 text-rose-600">error</span>
                    <span x-text="errorMessage"></span>
                </div>

                <!-- Pratinjau Gambar Tambahan Terpilih -->
                <div x-show="files.length > 0" x-cloak class="mt-3 p-3 bg-surface-container-low border border-outline-variant rounded-lg">
                    <p class="text-xs font-semibold text-on-surface mb-2 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-emerald-600">check_circle</span>
                        <span x-text="files.length + ' foto baru siap diunggah:'"></span>
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="(f, i) in files" :key="i">
                            <div class="relative group border border-outline-variant rounded-lg overflow-hidden w-20 bg-surface-container-lowest shadow-sm">
                                <img :src="f.previewUrl" class="w-20 h-20 object-cover">
                                <div class="p-1 text-[10px] text-on-surface truncate text-center font-mono" x-text="f.size"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- List Foto Produk yang Sudah Tersimpan -->
                @if($product->images->count() > 0)
                <div class="mt-3 p-4 bg-surface-container-lowest border border-outline-variant rounded-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-base">photo_library</span>
                            <span class="text-xs font-bold text-on-surface">Foto Tersimpan Sekarang ({{ $product->images->count() }}/5)</span>
                        </div>
                        <span class="text-[11px] text-on-surface-variant">Hover foto untuk jadikan foto utama atau hapus</span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        @foreach($product->images as $img)
                        <div class="relative group w-24 h-24 rounded-lg overflow-hidden border {{ $img->is_main ? 'border-primary border-2 shadow-sm' : 'border-outline-variant' }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1.5 p-1">
                                @if(!$img->is_main)
                                <button type="button" onclick="setMainImage({{ $img->id }})" class="w-full py-1 bg-white text-black text-[10px] font-bold rounded shadow hover:bg-gray-100 transition">
                                    Set Utama
                                </button>
                                @endif
                                <button type="button" onclick="deleteImage({{ $img->id }})" class="w-full py-1 bg-error text-white text-[10px] font-bold rounded shadow hover:bg-red-700 transition">
                                    Hapus
                                </button>
                            </div>
                            @if($img->is_main)
                                <div class="absolute top-0 left-0 bg-primary text-white text-[9px] font-bold px-1.5 py-0.5 rounded-br">UTAMA</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <p class="text-xs text-on-surface-variant mt-1.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">info</span>
                    Tipe file dan ukuran dicek secara langsung sebelum diupload untuk mencegah upload lemot atau file berbahaya.
                </p>
                @error('images')<span class="text-error text-xs block mt-1">{{ $message }}</span>@enderror
                @error('images.*')<span class="text-error text-xs block mt-1">{{ $message }}</span>@enderror
            </div>

            @include('products._custom_fields', ['productItem' => $product])

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="rounded border-outline-variant text-primary focus:ring-primary">
                    <span class="font-label-md text-on-surface">Aktif (Tampil di Toko)</span>
                </label>
            </div>
            
            <div class="flex justify-end pt-md border-t border-outline-variant">
                <button type="submit" :disabled="submitting" class="px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow flex items-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed">
                    <span x-show="submitting" x-cloak class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                    <span x-text="submitting ? 'Memperbarui Produk...' : 'Update Product'">Update Product</span>
                </button>
            </div>
        </form>

        <!-- Hidden forms for image actions outside main form -->
        <form id="set-main-form" method="POST" style="display: none;">
            @csrf @method('PATCH')
        </form>
        <form id="delete-image-form" method="POST" style="display: none;">
            @csrf @method('DELETE')
        </form>
    </div>
</div>

<script>
    function productImageValidator(currentCount) {
        return {
            files: [],
            errorMessage: '',
            currentImagesCount: currentCount,
            validateFiles(event) {
                const input = event.target;
                const selectedFiles = Array.from(input.files);
                this.errorMessage = '';
                this.files = [];

                const totalCount = this.currentImagesCount + selectedFiles.length;
                if (totalCount > 5) {
                    const remainingSlot = Math.max(0, 5 - this.currentImagesCount);
                    this.errorMessage = `Total foto produk tidak boleh lebih dari 5! Produk ini sudah memiliki ${this.currentImagesCount} foto, Anda hanya dapat menambah maksimal ${remainingSlot} foto lagi.`;
                    input.value = '';
                    return;
                }

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];
                const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp', '.gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                for (let file of selectedFiles) {
                    const ext = '.' + file.name.split('.').pop().toLowerCase();
                    
                    if (!file.type.startsWith('image/') || !allowedTypes.includes(file.type) || !allowedExtensions.includes(ext)) {
                        this.errorMessage = `File "${file.name}" bukan file gambar yang valid! Hanya format JPG, JPEG, PNG, WEBP, dan GIF yang diperbolehkan. File selain gambar (.php, video, dll) dilarang.`;
                        input.value = '';
                        this.files = [];
                        return;
                    }

                    if (file.size > maxSize) {
                        this.errorMessage = `Ukuran file "${file.name}" (${(file.size / (1024 * 1024)).toFixed(2)} MB) terlalu besar! Maksimal 2 MB per foto agar proses upload cepat.`;
                        input.value = '';
                        this.files = [];
                        return;
                    }

                    this.files.push({
                        name: file.name,
                        size: (file.size / 1024).toFixed(1) + ' KB',
                        previewUrl: URL.createObjectURL(file)
                    });
                }
            }
        };
    }

    function addLink() {
        const container = document.getElementById('links-container');
        const index = Date.now(); // use timestamp to avoid index collision on edit
        
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-start';
        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="download_links[${index}][name]" placeholder="Link Name (e.g., Source Code)" required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm mb-1">
            </div>
            <div class="flex-[2]">
                <input type="url" name="download_links[${index}][url]" placeholder="https://..." required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg transition" title="Remove Link">
                <span class="material-symbols-outlined text-sm">delete</span>
            </button>
        `;
        container.appendChild(row);
    }

    function setMainImage(imageId) {
        const form = document.getElementById('set-main-form');
        form.action = `/dashboard/products/image/${imageId}/set-main`;
        form.submit();
    }

    function deleteImage(imageId) {
        if (confirm('Hapus foto ini?')) {
            const form = document.getElementById('delete-image-form');
            form.action = `/dashboard/products/image/${imageId}`;
            form.submit();
        }
    }
</script>
@endsection
