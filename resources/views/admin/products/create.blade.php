@extends('layouts.admin')
@section('title', 'Add Digital Product')
@section('content')
<div class="p-lg md:p-xl flex-1 max-w-4xl mx-auto w-full">
    <div class="flex items-center gap-md mb-lg">
        <a href="{{ route('admin.products.index') }}" class="p-2 text-on-surface-variant hover:bg-surface-container-high rounded-full transition" wire:navigate>
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-headline-md font-bold text-on-surface">Add Digital Product</h2>
    </div>

    <div class="bg-surface rounded-md border border-outline-variant  p-lg">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
            @csrf

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Product Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('name')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Category</label>
                    <select name="product_category_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- No Category --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('product_category_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Type</label>
                    <select name="product_type_id" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                        <option value="">-- No Type --</option>
                        @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ old('product_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('product_type_id')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Price (Rp) *</label>
                    <input type="number" name="price" required min="0" value="{{ old('price') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Discount Price (Rp) - Optional</label>
                    <input type="number" name="discount_price" min="0" value="{{ old('discount_price') }}" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                    @error('discount_price')<span class="text-error text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Short Description (Ringkasan Singkat)</label>
                <textarea name="short_description" rows="2" placeholder="Ringkasan singkat produk untuk tampilan kartu katalog & etalase toko..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">{{ old('short_description') }}</textarea>
                @error('short_description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Full Description *</label>
                <div id="editor-container" class="w-full bg-surface-container-low border border-[#CBD5E1] rounded-b-lg font-body-md text-body-md text-on-surface" style="min-height: 250px;"></div>
                <input type="hidden" name="description" id="description" value="{{ old('description') }}">
                @error('description')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <!-- Tags / Kata Kunci Pencarian & Auto-Generator -->
            <div x-data="{
                tagsString: '{{ addslashes(old('tags', '')) }}',
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
                    const descInput = document.querySelector('input[name=\'description\']')?.value || document.querySelector('textarea[name=\'description\']')?.value || '';
                    const catSelect = document.querySelector('select[name=\'product_category_id\']');
                    const catText = catSelect && catSelect.selectedIndex > 0 ? catSelect.options[catSelect.selectedIndex].text.replace(/\(.*\)/, '').trim() : '';
                    const typeSelect = document.querySelector('select[name=\'product_type_id\']');
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
                            Pisahkan dengan koma. Kata kunci ini dicocokkan saat calon pembeli mencari di katalog.
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
                <input type="url" name="demo_url" value="{{ old('demo_url') }}" placeholder="https://..." class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('demo_url')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="p-md bg-secondary-container/20 border border-secondary-container rounded-lg">
                <label class="block font-label-md text-on-surface mb-xs">Digital File (ZIP/RAR) (Optional)</label>
                <p class="text-xs text-on-surface-variant mb-2">Upload a file directly. Max 100MB.</p>
                <input type="file" name="file" class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                @error('file')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            <div class="p-md bg-surface-container-low border border-outline-variant rounded-lg">
                <div class="flex justify-between items-center mb-xs">
                    <label class="block font-label-md text-on-surface">External Download Links (Optional)</label>
                    <button type="button" onclick="addLink()" class="text-xs bg-primary text-white px-3 py-1 rounded font-bold hover:brightness-110 transition">+ Add Link</button>
                </div>
                <p class="text-xs text-on-surface-variant mb-4">Add multiple external links (e.g., Google Drive, Mega) to be sent to the buyer's email.</p>
                
                <div id="links-container" class="flex flex-col gap-sm">
                    <!-- Dynamic links will be appended here -->
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-xs">Product Images (Up to 5) *</label>
                <input type="file" name="images[]" multiple accept="image/*" required class="w-full pl-4 pr-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary/20">
                <p class="text-xs text-on-surface-variant mt-1">Select multiple files holding CTRL/CMD.</p>
                @error('images')<span class="text-error text-xs">{{ $message }}</span>@enderror
                @error('images.*')<span class="text-error text-xs">{{ $message }}</span>@enderror
            </div>

            @include('products._custom_fields', ['productItem' => null])

            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-outline-variant text-primary focus:ring-primary">
                    <span class="font-label-md text-on-surface">Active (Visible in Store)</span>
                </label>
            </div>

            <div class="flex justify-end pt-md border-t border-outline-variant">
                <button type="submit" class="px-md py-2 bg-primary text-white rounded-lg font-label-md font-bold hover:brightness-110 transition shadow">Save Product</button>
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

<!-- Quill Rich Text Editor (Toolbox) -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
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
            toolbar.classList.add('bg-surface-container', 'border-[#CBD5E1]', 'rounded-t-lg');
        }
        editorElem.classList.add('border-t-0', 'border-[#CBD5E1]', 'rounded-b-lg', 'bg-surface-container-low');

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
