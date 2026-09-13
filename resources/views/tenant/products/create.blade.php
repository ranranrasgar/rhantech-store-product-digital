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
        <form action="{{ route('tenant.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg" x-data="{ submitting: false, imageHasError: false }" @image-validation-state.window="imageHasError = $event.detail.hasError" @submit="if(imageHasError) { $event.preventDefault(); alert('Mohon perbaiki foto yang melebihi batas 2 MB terlebih dahulu sebelum menyimpan.'); return false; } submitting = true">
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

            <!-- Pengaturan Bagi Hasil Komisi Afiliasi (Showcase) -->
            <div class="p-4 rounded-xl border border-sky-500/30 bg-sky-500/[0.03] dark:bg-sky-500/[0.05] space-y-3">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-500 text-[22px]">storefront</span>
                        <div>
                            <h4 class="text-xs font-bold text-on-surface">Bagi Hasil Komisi Afiliasi (Etalase Showcase)</h4>
                            <p class="text-[11px] text-on-surface-variant">Izinkan toko lain memajang produk ini di etalase mereka dan tentukan bagi hasil komisi saat produk terjual.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input type="checkbox" name="is_affiliate_enabled" value="1" {{ old('is_affiliate_enabled', $sourceProduct->is_affiliate_enabled ?? true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-sky-500"></div>
                    </label>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2 border-t border-outline-variant/40">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface mb-1">Persentase Komisi (%) *</label>
                        <div class="relative">
                            <input type="number" name="affiliate_commission_rate" min="0" max="100" step="0.5" 
                                   value="{{ old('affiliate_commission_rate', $sourceProduct->affiliate_commission_rate ?? 10) }}" 
                                   placeholder="10" 
                                   class="w-full pl-4 pr-8 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-xs font-bold text-on-surface focus:border-sky-500 focus:ring-1 focus:ring-sky-500/20">
                            <span class="absolute right-3 top-2 text-xs font-bold text-slate-400">%</span>
                        </div>
                        <span class="text-[10px] text-on-surface-variant mt-1 block">Default platform: 10%. Semakin menarik komisi, semakin banyak toko yang memajang produk Anda.</span>
                    </div>
                    <div class="bg-surface-container-low/60 rounded-lg p-2.5 flex flex-col justify-center border border-outline-variant/30 text-[11px] text-on-surface-variant">
                        <span class="font-bold text-on-surface flex items-center gap-1"><span class="material-symbols-outlined text-[15px] text-amber-500">payments</span> Simulasi Bagi Hasil:</span>
                        <span class="mt-0.5">Toko lain yang memajang produk ini akan langsung melihat persentase & nominal komisi di menu Etalase Afiliasi.</span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Ringkasan Singkat (Short Description) - Opsional</label>
                <textarea name="short_description" rows="2" placeholder="Ringkasan singkat produk untuk tampilan kartu etalase..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('short_description', $sourceProduct->short_description ?? '') }}</textarea>
                @error('short_description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Full Description *</label>
                <div id="editor-container" class="w-full bg-surface-container-low border border-[#CBD5E1] dark:border-outline-variant rounded-b-lg font-body-md text-body-md text-on-surface" style="min-height: 250px;"></div>
                <input type="hidden" name="description" id="description" value="{{ old('description', $sourceProduct->description ?? '') }}">
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Tags / Kata Kunci Pencarian & Auto-Generator -->
            <div x-data="{
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

            <div x-data="productImageValidator()">
                <div class="flex items-center justify-between mb-xs flex-wrap gap-1">
                    <label class="block font-label-md text-on-surface">Foto Produk (Maks 5) {{ isset($sourceProduct) && $sourceProduct->images->count() > 0 ? '(Opsional)' : '*' }}</label>
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
                        Maksimal 5 foto produk
                    </span>
                </div>
                
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

                <div class="relative">
                    <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" @change="validateFiles($event)" {{ isset($sourceProduct) && $sourceProduct->images->count() > 0 ? '' : 'required' }} class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20 file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer" :class="hasOversized ? 'border-rose-500 ring-1 ring-rose-500/30' : ''">
                </div>

                <!-- Alert Error Validasi File Melebihi 2MB / Tidak Valid -->
                <div x-show="errorMessage" x-cloak class="mt-2.5 p-3 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900 rounded-lg flex items-start gap-2.5 text-rose-700 dark:text-rose-300 text-xs leading-relaxed">
                    <span class="material-symbols-outlined text-[20px] shrink-0 text-rose-600 mt-0.5">warning</span>
                    <div>
                        <strong class="font-bold block mb-0.5 text-rose-800 dark:text-rose-200">Peringatan Ukuran Foto:</strong>
                        <span x-text="errorMessage"></span>
                    </div>
                </div>

                <!-- Pratinjau Gambar Terpilih -->
                <div x-show="files.length > 0" x-cloak class="mt-3 p-3 bg-surface-container-low border border-outline-variant rounded-lg">
                    <div class="flex items-center justify-between flex-wrap gap-2 mb-2.5">
                        <p class="text-xs font-semibold text-on-surface flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm" :class="hasOversized ? 'text-rose-600' : 'text-emerald-600'" x-text="hasOversized ? 'error' : 'check_circle'"></span>
                            <span x-text="files.length + ' foto dipilih (Total: ' + totalSizeFormatted + '):'"></span>
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

                <p class="text-xs text-on-surface-variant mt-1.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">info</span>
                    Sistem akan memvalidasi tipe file dan ukuran secara otomatis sebelum upload agar proses penyimpanan tidak lemot.
                </p>
                @error('images')<span class="text-error text-xs block mt-1">{{ $message }}</span>@enderror
                @error('images.*')<span class="text-error text-xs block mt-1">{{ $message }}</span>@enderror
            </div>

            @include('products._custom_fields', ['productItem' => $sourceProduct ?? null])

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($sourceProduct) ? $sourceProduct->is_active : true) ? 'checked' : '' }} class="rounded border-outline-variant text-primary focus:ring-primary">
                    <span class="font-label-md text-on-surface">Aktif (Tampil di Toko setelah Disetujui)</span>
                </label>
            </div>

            <!-- Info Moderasi Kualitas & Link Produk (SOP Perlindungan Konsumen) -->
            <div class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-300 text-xs flex items-start gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-amber-500 shrink-0 mt-0.5">verified</span>
                <div class="leading-relaxed">
                    <span class="font-bold block mb-0.5">Verifikasi Kualitas & Link Aktif oleh Admin:</span>
                    Demi mencegah produk fiktif dan memastikan tautan unduhan benar-benar aktif untuk pembeli, setiap produk baru akan ditinjau secara berkala oleh tim moderator (status: <span class="font-semibold text-amber-600 dark:text-amber-400">In Review</span>) sebelum tampil publik di marketplace.
                </div>
            </div>

            <div class="flex items-center justify-between pt-md border-t border-outline-variant flex-wrap gap-2">
                <div x-show="imageHasError" x-cloak class="text-xs text-rose-600 font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">error</span>
                    <span>Ada foto yang melebihi batas 2 MB. Harap ganti foto sebelum menyimpan.</span>
                </div>
                <div class="ml-auto">
                    <button type="submit" :disabled="submitting || imageHasError" class="px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed" :class="imageHasError ? 'bg-slate-400 hover:bg-slate-400 cursor-not-allowed' : ''">
                        <span x-show="submitting" x-cloak class="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                        <span x-text="submitting ? 'Menyimpan Produk...' : '{{ isset($sourceProduct) ? 'Simpan Salinan Produk' : 'Simpan Produk' }}'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
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
                        this.errorMessage = `File "${file.name}" bukan format gambar yang valid! Hanya format JPG, JPEG, PNG, WEBP, dan GIF yang diperbolehkan.`;
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
    function initQuillProductCreate() {
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

    document.addEventListener('DOMContentLoaded', initQuillProductCreate);
    document.addEventListener('livewire:navigated', initQuillProductCreate);
</script>
@endsection
