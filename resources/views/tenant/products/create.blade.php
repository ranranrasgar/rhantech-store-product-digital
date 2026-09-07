@extends('layouts.tenant')
@section('title', isset($sourceProduct) ? 'Salin Produk' : 'Tambah Produk Digital')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-4xl mx-auto w-full">
    <div class="flex items-center gap-md mb-lg">
        <a href="{{ route('tenant.products.index') }}" class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-md font-bold text-on-surface">{{ isset($sourceProduct) ? 'Salin Produk' : 'Tambah Produk Digital' }}</h2>
            @if(isset($sourceProduct))
            <p class="text-xs text-on-surface-variant mt-0.5">Menyalin data dari: <span class="font-semibold text-primary">{{ $sourceProduct->name }}</span></p>
            @endif
        </div>
    </div>

    <div class="bg-surface rounded-md border border-outline-variant p-lg">
        <form action="{{ route('tenant.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
            @csrf

            @if(isset($sourceProduct))
                <input type="hidden" name="copied_from" value="{{ $sourceProduct->id }}">
            @endif

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name', isset($sourceProduct) ? $sourceProduct->name . ' (Salinan)' : '') }}" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
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
                    <label class="block font-label-md text-on-surface mb-xs">Harga (Rp) *</label>
                    <input type="number" name="price" required min="0" value="{{ old('price', isset($sourceProduct) ? (int)$sourceProduct->price : '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Harga Diskon (Rp) - Opsional</label>
                    <input type="number" name="discount_price" min="0" value="{{ old('discount_price', isset($sourceProduct) && $sourceProduct->discount_price ? (int)$sourceProduct->discount_price : '') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('discount_price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Deskripsi Produk *</label>
                <textarea name="description" rows="5" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('description', $sourceProduct->description ?? '') }}</textarea>
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Demo URL (Opsional)</label>
                <input type="url" name="demo_url" value="{{ old('demo_url', $sourceProduct->demo_url ?? '') }}" placeholder="https://..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('demo_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>


            <div class="p-md bg-surface-container-low border border-outline-variant rounded-lg">
                <div class="flex justify-between items-center mb-xs">
                    <label class="block font-label-md text-on-surface">Tautan Unduhan Eksternal <span class="text-error">*</span></label>
                    <button type="button" onclick="addLink()" class="text-xs bg-primary text-white px-3 py-1 rounded font-bold hover:brightness-110 transition">+ Tambah Link</button>
                </div>
                <p class="text-xs text-on-surface-variant mb-4">Tambahkan minimal 1 tautan eksternal (contoh: Google Drive, Mega) yang akan dikirim ke email pembeli.</p>
                
                <div id="links-container" class="flex flex-col gap-sm">
                    @php 
                        $downloadLinks = old('download_links', isset($sourceProduct) ? ($sourceProduct->download_links ?? []) : []); 
                    @endphp
                    @if(!empty($downloadLinks) && is_array($downloadLinks))
                        @foreach($downloadLinks as $idx => $link)
                        <div class="flex gap-2 items-start">
                            <div class="flex-1">
                                <input type="text" name="download_links[{{ $idx }}][name]" value="{{ $link['name'] ?? '' }}" placeholder="Nama Link (cth: Source Code)" required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm mb-1">
                            </div>
                            <div class="flex-[2]">
                                <input type="url" name="download_links[{{ $idx }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="https://..." required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm">
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

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Foto Produk (Maks 5) {{ isset($sourceProduct) && $sourceProduct->images->count() > 0 ? '(Opsional)' : '*' }}</label>
                @if(isset($sourceProduct) && $sourceProduct->images->count() > 0)
                    <div class="mb-3 p-3 bg-surface-container-low border border-outline-variant rounded-lg">
                        <p class="text-xs text-on-surface-variant mb-2 font-medium">Foto yang akan disalin dari produk asal (bisa unggah foto baru jika ingin mengganti):</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($sourceProduct->images as $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-16 h-16 object-cover rounded border border-outline-variant">
                            @endforeach
                        </div>
                    </div>
                @endif
                <input type="file" name="images[]" multiple accept="image/*" {{ isset($sourceProduct) && $sourceProduct->images->count() > 0 ? '' : 'required' }} class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                <p class="text-xs text-on-surface-variant mt-1">Pilih beberapa file sekaligus dengan menahan tombol CTRL/CMD.</p>
                @error('images')<span class="text-error text-xs">{{ $message }}</span>@enderror
                @error('images.*')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            @include('products._custom_fields', ['productItem' => $sourceProduct ?? null])

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($sourceProduct) ? $sourceProduct->is_active : true) ? 'checked' : '' }} class="rounded border-outline-variant text-primary focus:ring-primary">
                    <span class="font-label-md text-on-surface">Aktif (Tampil di Toko)</span>
                </label>
            </div>

            <div class="flex justify-end pt-md border-t border-outline-variant">
                <button type="submit" class="px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow">
                    {{ isset($sourceProduct) ? 'Simpan Salinan Produk' : 'Simpan Produk' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function addLink() {
        const container = document.getElementById('links-container');
        const index = container.children.length;
        
        const row = document.createElement('div');
        row.className = 'flex gap-2 items-start';
        row.innerHTML = `
            <div class="flex-1">
                <input type="text" name="download_links[${index}][name]" placeholder="Nama Link (cth: Source Code)" required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm mb-1">
            </div>
            <div class="flex-[2]">
                <input type="url" name="download_links[${index}][url]" placeholder="https://..." required class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="p-2 text-error hover:bg-error/10 rounded-lg transition" title="Hapus Link">
                <span class="material-symbols-outlined text-sm">delete</span>
            </button>
        `;
        container.appendChild(row);
    }
</script>
@endsection
