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
        <form action="{{ route('tenant.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg" x-data="{ submitting: false, imageHasError: false }" @image-validation-state.window="imageHasError = $event.detail.hasError" @submit="if(imageHasError) { $event.preventDefault(); alert('Mohon perbaiki foto yang melebihi batas 2 MB terlebih dahulu sebelum menyimpan.'); return false; } submitting = true">
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
                <label class="block font-label-md text-on-surface mb-xs">Full Description *</label>
                <div id="editor-container" class="w-full bg-surface-container-low border border-[#CBD5E1] dark:border-outline-variant rounded-b-lg font-body-md text-body-md text-on-surface" style="min-height: 250px;"></div>
                <input type="hidden" name="description" id="description" value="{{ old('description', $product->description) }}">
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Tags / Kata Kunci Pencarian & Auto-Generator -->
            <div x-data="{
                tagsString: '{{ addslashes(old('tags', $product->tags ?? '')) }}',
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
                        if (regex.test(combined)) {
                            extractedTags.add(term);
                        }
                    });

                    businessDictionary.forEach(term => {
                        const regex = new RegExp('\\b' + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\b', 'i');
                        if (regex.test(combined)) {
                            extractedTags.add(term);
                        }
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

                        for (let i = 0; i < words.length - 1; i++) {
                            const phrase = (words[i].charAt(0).toUpperCase() + words[i].slice(1)) + ' ' +
                                           (words[i+1].charAt(0).toUpperCase() + words[i+1].slice(1));
                            if (phrase.length <= 25) {
                                extractedTags.add(phrase);
                            }
                        }
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
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-xs">
                    <div>
                        <label class="block font-label-md text-on-surface font-bold">
                            Tags / Kata Kunci Pencarian (SEO)
                        </label>
                        <p class="text-xs text-on-surface-variant">
                            Pisahkan dengan koma. Kata kunci ini dicocokkan saat calon pembeli mencari di website.
                        </p>
                    </div>

                    <button type="button" 
                            @click="generateTags()" 
                            :disabled="generating"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-500/10 hover:bg-amber-500/20 text-amber-700 dark:text-amber-300 border border-amber-300 dark:border-amber-700 text-xs font-bold transition-all shrink-0">
                        <span class="material-symbols-outlined text-[16px] text-amber-600 dark:text-amber-400" :class="{ 'animate-spin': generating }">
                            auto_awesome
                        </span>
                        <span x-text="generating ? 'Menganalisis Teks...' : '⚡ Generate Tags Otomatis'"></span>
                    </button>
                </div>

                <input type="text" 
                       name="tags" 
                       x-model="tagsString"
                       placeholder="Contoh: Aplikasi Kasir, POS, Toko Online, Laravel 11, PHP MySQL" 
                       class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20 text-xs md:text-sm">
                @error('tags')<span class="text-error text-xs">{{ $message }}</span>@enderror

                <div class="mt-2 flex flex-wrap items-center gap-1.5 min-h-[28px]" x-show="tagsList.length > 0">
                    <span class="text-[11px] text-on-surface-variant font-medium mr-1">Preview Tag:</span>
                    <template x-for="(tag, index) in tagsList" :key="index">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                            <span class="text-primary/70">#</span>
                            <span x-text="tag"></span>
                            <button type="button" @click="removeTag(index)" class="hover:text-error text-on-surface-variant ml-0.5" title="Hapus tag">
                                <span class="material-symbols-outlined text-[14px]">close</span>
                            </button>
                        </span>
                    </template>
                </div>

                <div class="mt-1.5 flex items-center justify-between text-[11px] text-on-surface-variant">
                    <span>💡 <em>Tips: Klik tombol <strong>Generate Tags Otomatis</strong> setelah mengisi Nama atau Deskripsi untuk menghasilkan tags relevan secara instan.</em></span>
                </div>
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

            @php
                $initialSavedImages = $product->images->map(function($img) {
                    return [
                        'id' => $img->id,
                        'image_path' => asset('storage/' . $img->image_path),
                        'is_main' => (bool)$img->is_main,
                        'deleting' => false,
                    ];
                })->values();
            @endphp

            <div x-data="productImageValidator({{ json_encode($initialSavedImages) }}, {{ $product->id }})">
                <div class="flex items-center justify-between mb-xs flex-wrap gap-1">
                    <label class="block font-label-md text-on-surface">Tambah Foto Produk Baru</label>
                    <span class="text-[11px] text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-primary">verified</span>
                        Maks. <strong>2 MB</strong> per foto (Total maks 5 foto)
                    </span>
                </div>

                <!-- Info Ketentuan Ukuran File -->
                <div class="mb-2 p-2.5 rounded-lg bg-teal-500/10 border border-teal-500/30 text-xs text-teal-800 dark:text-teal-200 flex items-center justify-between gap-2 flex-wrap">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-[#00838f] dark:text-teal-400 shrink-0">info</span>
                        <span><strong>Ketentuan Foto:</strong> Setiap foto maksimal <strong>2 MB</strong> (2.048 KB). Format: JPG, JPEG, PNG, WEBP, GIF.</span>
                    </div>
                    <span class="text-[11px] font-semibold text-teal-700 dark:text-teal-300">
                        Slot tersisa: <strong x-text="remainingSlot"></strong> foto
                    </span>
                </div>
                
                <div class="relative">
                    <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" @change="validateFiles($event)" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" :class="hasOversized ? 'border-rose-500 ring-1 ring-rose-500/30' : ''">
                </div>

                <!-- Alert Error Validasi File Melebihi 2MB / Tidak Valid -->
                <div x-show="errorMessage" x-cloak class="mt-2.5 p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 rounded-lg flex items-start gap-2.5 text-rose-700 dark:text-rose-300 text-xs leading-relaxed">
                    <span class="material-symbols-outlined text-[20px] shrink-0 text-rose-600 mt-0.5">warning</span>
                    <div>
                        <strong class="font-bold block mb-0.5 text-rose-800 dark:text-rose-200">Peringatan Ukuran Foto:</strong>
                        <span x-text="errorMessage"></span>
                    </div>
                </div>

                <!-- Pratinjau Gambar Tambahan Terpilih -->
                <div x-show="files.length > 0" x-cloak class="mt-3 p-3 bg-surface-container-low border border-outline-variant rounded-lg">
                    <div class="flex items-center justify-between flex-wrap gap-2 mb-2.5">
                        <p class="text-xs font-semibold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm" :class="hasOversized ? 'text-rose-600' : 'text-emerald-600'" x-text="hasOversized ? 'error' : 'check_circle'"></span>
                            <span x-text="files.length + ' foto baru dipilih (Total: ' + totalSizeFormatted + '):'"></span>
                        </p>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded"
                              :class="hasOversized ? 'bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300' : 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300'"
                              x-text="hasOversized ? '⚠️ Ada foto > 2 MB' : '✓ Semua foto aman (< 2 MB)'"></span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="(f, i) in files" :key="i">
                            <div class="relative group rounded-lg overflow-hidden w-24 bg-surface-container-lowest shadow-sm border"
                                 :class="f.isOversized ? 'border-2 border-rose-500' : 'border-outline-variant'">
                                <img :src="f.previewUrl" class="w-24 h-24 object-cover">
                                
                                <!-- Status Badge Ukuran -->
                                <div class="p-1 text-[10px] truncate text-center font-mono font-bold"
                                     :class="f.isOversized ? 'bg-rose-600 text-white' : 'bg-surface-container-low text-on-surface'"
                                     :title="f.name + ' (' + f.size + ')'">
                                    <span x-text="f.isOversized ? '⚠️ ' + f.size : '✓ ' + f.size"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- List Foto Produk yang Sudah Tersimpan -->
                <div x-show="savedImages.length > 0" x-cloak class="mt-3 p-4 bg-surface-container-lowest border border-outline-variant rounded-lg">
                    <div class="flex items-center justify-between mb-3 flex-wrap gap-2">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-primary text-base">photo_library</span>
                            <span class="text-xs font-bold text-on-surface">
                                Foto Tersimpan Sekarang (<span x-text="savedImages.length"></span>/5)
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-on-surface-variant hidden sm:inline">Hover foto untuk jadikan foto utama atau hapus</span>
                            <button type="button" 
                                    @click="deleteAllImages()" 
                                    :disabled="deletingAll"
                                    class="px-2.5 py-1 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800 rounded-md transition flex items-center gap-1 disabled:opacity-50 cursor-pointer">
                                <span class="material-symbols-outlined text-sm" :class="deletingAll ? 'animate-spin' : ''" x-text="deletingAll ? 'progress_activity' : 'delete_sweep'"></span>
                                <span x-text="deletingAll ? 'Menghapus Semua...' : 'Hapus Semua Foto'">Hapus Semua Foto</span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-3">
                        <template x-for="img in savedImages" :key="img.id">
                            <div class="relative group w-24 h-24 rounded-lg overflow-hidden border transition-all"
                                 :class="img.is_main ? 'border-primary border-2 shadow-sm' : 'border-outline-variant'">
                                <img :src="img.image_path" class="w-full h-full object-cover">
                                
                                <!-- Loading overlay saat proses hapus foto berlangsung -->
                                <div x-show="img.deleting" x-cloak class="absolute inset-0 bg-black/75 flex flex-col items-center justify-center gap-1 text-white p-1">
                                    <span class="material-symbols-outlined animate-spin text-base">progress_activity</span>
                                    <span class="text-[9px] font-semibold">Menghapus...</span>
                                </div>

                                <div x-show="!img.deleting" class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-1.5 p-1">
                                    <button x-show="!img.is_main" 
                                            type="button" 
                                            @click="setMainImage(img)" 
                                            class="w-full py-1 bg-white text-black text-[10px] font-bold rounded shadow hover:bg-gray-100 transition cursor-pointer">
                                        Set Utama
                                    </button>
                                    <button type="button" 
                                            @click="deleteImage(img)" 
                                            class="w-full py-1 bg-error text-white text-[10px] font-bold rounded shadow hover:bg-red-700 transition cursor-pointer">
                                        Hapus
                                    </button>
                                </div>
                                
                                <div x-show="img.is_main" class="absolute top-0 left-0 bg-primary text-white text-[9px] font-bold px-1.5 py-0.5 rounded-br">UTAMA</div>
                            </div>
                        </template>
                    </div>
                </div>

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
            
            <div class="flex items-center justify-between pt-md border-t border-outline-variant flex-wrap gap-2">
                <div x-show="imageHasError" x-cloak class="text-xs text-rose-600 font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span>Ada foto yang melebihi batas 2 MB. Harap ganti foto sebelum menyimpan.</span>
                </div>
                <div class="ml-auto">
                    <button type="submit" :disabled="submitting || imageHasError" class="px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" :class="imageHasError ? 'bg-slate-400 hover:bg-slate-400 cursor-not-allowed' : ''">
                        <span x-show="submitting" x-cloak class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                        <span x-text="submitting ? 'Memperbarui Produk...' : 'Update Product'">Update Product</span>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>

<script>
    function productImageValidator(initialImages, productId) {
        return {
            productId: productId,
            savedImages: Array.isArray(initialImages) ? initialImages : [],
            deletingAll: false,
            files: [],
            errorMessage: '',
            hasOversized: false,
            totalSizeFormatted: '0 KB',
            maxSizePerFile: 2 * 1024 * 1024, // 2MB
            csrfToken: '{{ csrf_token() }}',
            get currentImagesCount() {
                return this.savedImages.length;
            },
            get remainingSlot() {
                return Math.max(0, 5 - this.currentImagesCount);
            },
            async deleteImage(img) {
                if (!confirm('Hapus foto ini?')) return;
                
                img.deleting = true;
                try {
                    const response = await fetch(`/dashboard/products/image/${img.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        this.savedImages = this.savedImages.filter(item => item.id !== img.id);
                        if (data.new_main_id) {
                            this.savedImages.forEach(item => {
                                item.is_main = (item.id === data.new_main_id);
                            });
                        }
                    } else {
                        alert(data.message || 'Gagal menghapus foto.');
                        img.deleting = false;
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan saat menghapus foto.');
                    img.deleting = false;
                }
            },
            async deleteAllImages() {
                if (this.savedImages.length === 0) return;
                if (!confirm(`Hapus SEMUA (${this.savedImages.length}) foto produk yang tersimpan?`)) return;

                this.deletingAll = true;
                try {
                    const response = await fetch(`/dashboard/products/${this.productId}/images/delete-all`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        this.savedImages = [];
                    } else {
                        alert(data.message || 'Gagal menghapus semua foto.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan saat menghapus semua foto.');
                } finally {
                    this.deletingAll = false;
                }
            },
            async setMainImage(img) {
                try {
                    const response = await fetch(`/dashboard/products/image/${img.id}/set-main`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        this.savedImages.forEach(item => {
                            item.is_main = (item.id === img.id);
                        });
                    } else {
                        alert(data.message || 'Gagal mengubah foto utama.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan jaringan saat mengubah foto utama.');
                }
            },
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

                if (selectedFiles.length > this.remainingSlot) {
                    this.errorMessage = `Total foto produk tidak boleh lebih dari 5! Produk ini sudah memiliki ${this.currentImagesCount} foto tersimpan, Anda hanya dapat menambah maksimal ${this.remainingSlot} foto lagi.`;
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
                        this.errorMessage = `File "${file.name}" bukan file gambar yang valid! Hanya format JPG, JPEG, PNG, WEBP, dan GIF yang diperbolehkan.`;
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
                    this.errorMessage = `Ukuran foto melebihi batas 2 MB: ${oversizedList.join(', ')}. Input otomatis dikosongkan agar server tidak error saat disimpan. Mohon kompres foto tersebut atau gunakan foto di bawah 2 MB.`;
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
</script>

<!-- Quill Rich Text Editor (Toolbox Area Text) -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        background-color: #f8fafc;
        border-color: #CBD5E1;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        background-color: #ffffff;
        border-color: #CBD5E1;
        font-family: inherit;
        font-size: 0.875rem;
    }
    .dark .ql-toolbar.ql-snow {
        background-color: rgb(var(--theme-surface-container) / 1);
        border-color: rgb(var(--theme-outline-variant) / 0.8);
    }
    .dark .ql-container.ql-snow {
        background-color: rgb(var(--theme-surface-lowest) / 1);
        border-color: rgb(var(--theme-outline-variant) / 0.8);
        color: #f1f5f9;
    }
    .dark .ql-snow .ql-stroke {
        stroke: #cbd5e1;
    }
    .dark .ql-snow .ql-fill {
        fill: #cbd5e1;
    }
    .dark .ql-snow .ql-picker {
        color: #cbd5e1;
    }
    .dark .ql-snow .ql-picker-options {
        background-color: rgb(var(--theme-surface-container) / 1);
        border-color: rgb(var(--theme-outline-variant) / 0.8);
        color: #cbd5e1;
    }
</style>
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    function initQuillProductEdit() {
        var editorElem = document.getElementById('editor-container');
        if (!editorElem || editorElem.__quill_initialized) return;

        // Clear existing toolbar/editor if re-initialized
        editorElem.innerHTML = '';
        var prevToolbar = editorElem.previousElementSibling;
        if (prevToolbar && prevToolbar.classList.contains('ql-toolbar')) {
            prevToolbar.remove();
        }

        var quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tuliskan deskripsi lengkap produk di sini...',
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

        // Realtime sync to hidden input on every change
        quill.on('text-change', function() {
            var html = quill.root.innerHTML;
            descriptionInput.value = (html === '<p><br></p>' || quill.getText().trim().length === 0) ? '' : html;
        });

        // Add custom styles to match theme
        var toolbar = editorElem.previousElementSibling;
        if (toolbar && toolbar.classList.contains('ql-toolbar')) {
            toolbar.classList.add('bg-surface-container', 'rounded-t-lg');
        }
        editorElem.classList.add('border-t-0', 'rounded-b-lg');

        var productForm = editorElem.closest('form');
        if (productForm) {
            productForm.addEventListener('submit', function(e) {
                var html = quill.root.innerHTML;
                descriptionInput.value = (html === '<p><br></p>' || quill.getText().trim().length === 0) ? '' : html;
            });
        }
    }

    document.addEventListener('DOMContentLoaded', initQuillProductEdit);
    document.addEventListener('livewire:navigated', initQuillProductEdit);
</script>
@endsection
