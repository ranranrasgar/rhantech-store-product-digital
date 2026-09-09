@php
    $productItem = $productItem ?? $sourceProduct ?? null;
    $isEdit = isset($productItem) && $productItem->exists;
    
    // Default Highlights (Akses Instan & Aman, dll)
    $defaultHighlights = [
        ['title' => 'Akses Instan & Aman', 'icon' => 'verified'],
        ['title' => 'Direct Link Download', 'icon' => 'cloud_download'],
        ['title' => 'Source Code 100% Bersih', 'icon' => 'security'],
        ['title' => 'Bantuan & Dokumentasi', 'icon' => 'support_agent'],
    ];
    $highlights = old('highlights', $isEdit ? ($productItem->highlights ?? []) : ($productItem?->highlights ?? $defaultHighlights));

    // Default Package Includes (Paket yang Anda Dapatkan)
    $defaultPackage = [
        'Full Source Code Lengkap (Frontend + Backend tanpa enkripsi)',
        'File Database (.SQL) siap import lengkap dengan sample data',
        'Buku Panduan Instalasi (PDF / Readme) langkah demi langkah',
        'Akses konsultasi & bantuan teknis jika mengalami kendala instalasi',
    ];
    $packageIncludes = old('package_includes', $isEdit ? ($productItem->package_includes ?? []) : ($productItem?->package_includes ?? $defaultPackage));

    // Default System Requirements (Kebutuhan Sistem)
    $defaultSysReq = [
        ['label' => 'Web Server', 'value' => 'Apache / Nginx'],
        ['label' => 'PHP Version', 'value' => 'PHP 8.0 - 8.3+'],
        ['label' => 'Database', 'value' => 'MySQL 5.7+ / MariaDB'],
        ['label' => 'Environment', 'value' => 'Localhost (Laragon/XAMPP) & CPanel'],
    ];
    $systemRequirements = old('system_requirements', $isEdit ? ($productItem->system_requirements ?? []) : ($productItem?->system_requirements ?? $defaultSysReq));

    // Default Guarantees (Garansi & Keamanan)
    $defaultGuarantees = [
        [
            'title' => '100% Bebas Malware & Backdoor',
            'icon' => 'verified',
            'description' => 'Setiap baris source code telah diuji dan dipindai menggunakan tool keamanan standar industri. Kode bersih, tidak ada enkripsi (unobfuscated), dan bebas dari script berbahaya.'
        ],
        [
            'title' => 'Garansi Bantuan Instalasi',
            'icon' => 'support_agent',
            'description' => 'Bingung saat pertama kali menginstall? Tim teknis kami siap memandu Anda melalui WhatsApp / Google Meet / AnyDesk sampai aplikasi berjalan lancar di localhost maupun server hosting Anda.'
        ],
        [
            'title' => 'Akses Seumur Hidup (Lifetime)',
            'icon' => 'security_update_good',
            'description' => 'Sekali beli, tautan unduhan dan source code menjadi milik Anda selamanya. Tidak ada biaya langganan bulanan atau tahunan tersembunyi.'
        ],
        [
            'title' => 'Lisensi Komersial Bebas Re-branding',
            'icon' => 'balance',
            'description' => 'Anda berhak mengganti nama aplikasi, logo, identitas toko, serta memodifikasi fitur sesuai kebutuhan bisnis pribadi maupun klien Anda tanpa royalti.'
        ]
    ];
    $guarantees = old('guarantees', $isEdit ? ($productItem->guarantees ?? []) : ($productItem?->guarantees ?? $defaultGuarantees));

    // Default FAQ (Tanya Jawab)
    $defaultFaqs = [
        [
            'question' => 'Bagaimana cara saya menerima source code setelah membayar?',
            'answer' => 'Sistem secara otomatis memproses pesanan Anda setelah pembayaran terkonfirmasi. Link unduhan paket file (ZIP) akan langsung muncul di halaman invoice pembelian serta dikirimkan otomatis ke alamat email yang Anda cantumkan saat checkout.'
        ],
        [
            'question' => 'Apakah source code ini bisa diubah atau dikembangkan lagi?',
            'answer' => 'Ya, 100% full source code terbuka tanpa proteksi atau enkripsi (IonCube dll). Anda bebas memodifikasi logic, mengubah tampilan tema, menambahkan modul baru, atau menghubungkan dengan API lain sesuai kebutuhan.'
        ],
        [
            'question' => 'Apakah saya bisa minta bantuan jika error saat instalasi?',
            'answer' => 'Tentu saja! Kami menyediakan panduan instalasi lengkap di dalam file ZIP. Jika Anda mengalami kesulitan atau error konfigurasi, silakan hubungi tim support kami via WhatsApp yang tertera di menu kontak web. Kami siap membantu remote hingga aplikasi berhasil berjalan.'
        ],
        [
            'question' => 'Apakah aplikasi ini bisa dijalankan secara offline di toko?',
            'answer' => 'Bisa! Aplikasi berbasis web ini dapat diinstall di server lokal / PC kasir toko menggunakan localhost (Laragon/XAMPP) sehingga dapat beroperasi tanpa koneksi internet sama sekali, ataupun dihosting online agar bisa dipantau dari mana saja.'
        ]
    ];
    $faqs = old('faqs', $isEdit ? ($productItem->faqs ?? []) : ($productItem?->faqs ?? $defaultFaqs));
@endphp

<!-- Accordion/Section: Penyesuaian Detail Tampilan Halaman Produk (Badges, Ulasan, Garansi, FAQ) -->
<div class="border border-outline-variant rounded-xl overflow-hidden bg-surface-container-lowest shadow-xs">
    <div class="bg-surface-container-low px-4 py-3 border-b border-outline-variant flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">tune</span>
            <span class="font-bold text-sm text-on-surface">Kustomisasi Halaman Detail Produk (Brosur, Kepercayaan & Tab Informasi)</span>
        </div>
        <span class="text-xs text-on-surface-variant">Bisa disesuaikan manual per barang</span>
    </div>

    <div class="p-4 space-y-6">

        {{-- 1. Bagian Ulasan & Statistik Penjualan: Hanya tampil untuk produk resmi platform (store_id null atau di panel admin) --}}
        @php
            $isPlatformProduct = request()->routeIs('admin.*') || (isset($productItem) && is_null($productItem->store_id));
        @endphp

        @if($isPlatformProduct)
        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/60">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-amber-500 text-[18px]">star</span>
                <label class="font-bold text-xs md:text-sm text-on-surface">Ulasan & Statistik Penjualan (Bintang, Penilaian, Terjual)</label>
            </div>
            <p class="text-xs text-on-surface-variant mb-3">Atur tampilan angka rating, jumlah penilaian, dan jumlah produk terjual yang tampil di bawah judul produk (Khusus Produk Resmi Platform). Jika dikosongkan, sistem akan mengkalkulasi otomatis.</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-on-surface mb-1">Rating Bintang (1.0 - 5.0)</label>
                    <input type="number" step="0.1" min="1" max="5" name="rating_override" value="{{ old('rating_override', $productItem?->rating_override) }}" placeholder="0 (Biarkan kosong untuk nilai ril)" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface mb-1">Jumlah Ulasan / Penilaian</label>
                    <input type="number" min="0" name="reviews_count" value="{{ old('reviews_count', $productItem?->reviews_count) }}" placeholder="0 (Biarkan kosong untuk nilai ril)" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                    <span class="text-[10px] text-on-surface-variant mt-1 block">Biarkan kosong agar sesuai dengan ulasan ril pembeli.</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-on-surface mb-1">Jumlah Produk Terjual</label>
                    <input type="number" min="0" name="sales_count" value="{{ old('sales_count', $productItem?->sales_count) }}" placeholder="0 (Biarkan kosong untuk nilai ril)" class="w-full px-3 py-2 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                </div>
            </div>
        </div>
        @endif

        <!-- 2. Bagian 4 Badge Kepercayaan Utama (Akses Instan & Aman, Direct Link, dll) -->
        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/60">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 text-[18px]">verified</span>
                    <label class="font-bold text-xs md:text-sm text-on-surface">Badge Keunggulan Cepat (4 Kotak di Bawah Harga)</label>
                </div>
                <button type="button" onclick="addHighlightRow()" class="text-xs bg-primary/10 text-primary hover:bg-primary hover:text-white px-2.5 py-1 rounded font-bold transition">+ Tambah Item</button>
            </div>
            <p class="text-xs text-on-surface-variant mb-3">Tiap produk memiliki karakter beda (ada yg source code, aplikasi jadi, file aset grafis, dll). Ubah teks & icon sesuai produk ini:</p>
            <div id="highlights-container" class="space-y-2">
                @foreach($highlights as $idx => $hl)
                <div class="flex items-center gap-2">
                    <div class="w-36">
                        <select name="highlights[{{ $idx }}][icon]" class="w-full px-2 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                            <option value="verified" {{ ($hl['icon'] ?? '') == 'verified' ? 'selected' : '' }}>✓ verified</option>
                            <option value="cloud_download" {{ ($hl['icon'] ?? '') == 'cloud_download' ? 'selected' : '' }}>⬇ cloud_download</option>
                            <option value="security" {{ ($hl['icon'] ?? '') == 'security' ? 'selected' : '' }}>🛡 security</option>
                            <option value="support_agent" {{ ($hl['icon'] ?? '') == 'support_agent' ? 'selected' : '' }}>🎧 support_agent</option>
                            <option value="code" {{ ($hl['icon'] ?? '') == 'code' ? 'selected' : '' }}>💻 code</option>
                            <option value="speed" {{ ($hl['icon'] ?? '') == 'speed' ? 'selected' : '' }}>⚡ speed</option>
                            <option value="bolt" {{ ($hl['icon'] ?? '') == 'bolt' ? 'selected' : '' }}>⚡ bolt</option>
                            <option value="check_circle" {{ ($hl['icon'] ?? '') == 'check_circle' ? 'selected' : '' }}>✔ check_circle</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <input type="text" name="highlights[{{ $idx }}][title]" value="{{ $hl['title'] ?? '' }}" placeholder="Judul Badge (cth: Akses Instan & Aman)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition" title="Hapus">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 3. Paket yang Anda Dapatkan (Tab Deskripsi Kiri) -->
        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/60">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">check_box</span>
                    <label class="font-bold text-xs md:text-sm text-on-surface">Paket yang Anda Dapatkan (Poin Checklist di Tab Deskripsi)</label>
                </div>
                <button type="button" onclick="addPackageRow()" class="text-xs bg-primary/10 text-primary hover:bg-primary hover:text-white px-2.5 py-1 rounded font-bold transition">+ Tambah Poin</button>
            </div>
            <p class="text-xs text-on-surface-variant mb-3">Daftar apa saja yang disertakan saat pembeli membeli produk ini (source code, database, dokumen, dll):</p>
            <div id="package-container" class="space-y-2">
                @foreach($packageIncludes as $idx => $pkg)
                <div class="flex items-center gap-2">
                    <div class="flex-1">
                        <input type="text" name="package_includes[]" value="{{ is_array($pkg) ? ($pkg['title'] ?? '') : $pkg }}" placeholder="Cth: Full Source Code Lengkap (Frontend + Backend)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition" title="Hapus">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Kebutuhan Sistem (Tab Deskripsi Kanan) -->
        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/60">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">tune</span>
                    <label class="font-bold text-xs md:text-sm text-on-surface">Kebutuhan Sistem (System Requirements)</label>
                </div>
                <button type="button" onclick="addSysReqRow()" class="text-xs bg-primary/10 text-primary hover:bg-primary hover:text-white px-2.5 py-1 rounded font-bold transition">+ Tambah Spesifikasi</button>
            </div>
            <p class="text-xs text-on-surface-variant mb-3">Kebutuhan environment seperti PHP Version, Database, Web Server, OS, dll:</p>
            <div id="sysreq-container" class="space-y-2">
                @foreach($systemRequirements as $idx => $sr)
                <div class="flex items-center gap-2">
                    <div class="w-1/3">
                        <input type="text" name="system_requirements[{{ $idx }}][label]" value="{{ $sr['label'] ?? ($sr['title'] ?? '') }}" placeholder="Label (cth: PHP Version)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="system_requirements[{{ $idx }}][value]" value="{{ $sr['value'] ?? ($sr['description'] ?? '') }}" placeholder="Nilai (cth: PHP 8.1 - 8.3+)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition" title="Hapus">
                        <span class="material-symbols-outlined text-sm">delete</span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 5. Tab Garansi & Keamanan (Trust Builders) -->
        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/60">
            <div class="flex justify-between items-center mb-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-500 text-[18px]">verified_user</span>
                    <label class="font-bold text-xs md:text-sm text-on-surface">Tab: Garansi & Keamanan</label>
                </div>
                <button type="button" onclick="addGuaranteeRow()" class="text-xs bg-primary/10 text-primary hover:bg-primary hover:text-white px-2.5 py-1 rounded font-bold transition">+ Tambah Garansi</button>
            </div>
            <p class="text-xs text-on-surface-variant mb-3">Item jaminan keamanan, garansi instalasi, lisensi, atau bebas malware yang tampil di Tab Garansi:</p>
            <div id="guarantees-container" class="space-y-3">
                @foreach($guarantees as $idx => $g)
                <div class="p-2.5 rounded-lg bg-surface border border-outline-variant/50 relative">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-32">
                            <select name="guarantees[{{ $idx }}][icon]" class="w-full px-2 py-1 bg-surface-container-lowest border border-outline-variant rounded text-xs">
                                <option value="verified" {{ ($g['icon'] ?? '') == 'verified' ? 'selected' : '' }}>verified</option>
                                <option value="support_agent" {{ ($g['icon'] ?? '') == 'support_agent' ? 'selected' : '' }}>support_agent</option>
                                <option value="security_update_good" {{ ($g['icon'] ?? '') == 'security_update_good' ? 'selected' : '' }}>security_update_good</option>
                                <option value="balance" {{ ($g['icon'] ?? '') == 'balance' ? 'selected' : '' }}>balance</option>
                                <option value="shield" {{ ($g['icon'] ?? '') == 'shield' ? 'selected' : '' }}>shield</option>
                                <option value="lock" {{ ($g['icon'] ?? '') == 'lock' ? 'selected' : '' }}>lock</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="text" name="guarantees[{{ $idx }}][title]" value="{{ $g['title'] ?? '' }}" placeholder="Judul Garansi (cth: Garansi Bantuan Instalasi)" class="w-full px-3 py-1 bg-surface-container-lowest border border-outline-variant rounded font-body-sm text-xs font-semibold">
                        </div>
                        <button type="button" onclick="this.closest('.p-2\\.5').remove()" class="p-1 text-error hover:bg-error/10 rounded transition" title="Hapus">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                    <textarea name="guarantees[{{ $idx }}][description]" rows="2" placeholder="Penjelasan garansi..." class="w-full px-3 py-1.5 bg-surface-container-lowest border border-outline-variant rounded text-xs">{{ $g['description'] ?? '' }}</textarea>
                </div>
                @endforeach
            </div>
        </div>

        <!-- 6. Tab Tanya Jawab (FAQ) & Panduan Produk -->
        <div class="p-3.5 rounded-lg bg-surface-container-low border border-outline-variant/60">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-500 text-[18px]">help_outline</span>
                    <label class="font-bold text-xs md:text-sm text-on-surface">Tab: Tanya Jawab (FAQ) & Panduan Produk</label>
                </div>
                <button type="button" onclick="addFaqRow()" class="text-xs bg-primary/10 text-primary hover:bg-primary hover:text-white px-2.5 py-1 rounded font-bold transition flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">add</span> Tambah Tanya Jawab Toko
                </button>
            </div>
            <p class="text-xs text-on-surface-variant mb-3">
                Hubungkan dengan kategori Pusat Bantuan platform atau buat panduan & tanya jawab khusus toko Anda di bawah ini agar pembeli memahami produk yang dirilis:
            </p>
            
            <div class="space-y-4">
                {{-- Pilihan Kategori Pusat Bantuan Resmi Platform --}}
                <div class="p-3 bg-surface rounded-lg border border-outline-variant/50">
                    <label class="block text-xs font-bold text-on-surface mb-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-primary">category</span>
                        Kategori Pusat Bantuan Platform (Opsional):
                    </label>
                    <p class="text-[11px] text-on-surface-variant mb-2">Pilih kategori standar platform agar artikel bantuan terkait langsung muncul di tab produk & terhubung ke Pusat Bantuan resmi.</p>
                    <select name="help_category_id" class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg font-body-sm text-xs focus:border-primary focus:ring-1 focus:ring-primary/20">
                        <option value="">-- Tanpa Kategori Platform (Hanya Tampilkan Tanya Jawab Khusus Toko) --</option>
                        @foreach($helpCategories ?? [] as $hc)
                            <option value="{{ $hc->id }}" {{ (string)old('help_category_id', $productItem?->help_category_id) === (string)$hc->id ? 'selected' : '' }}>
                                {{ $hc->name }} ({{ $hc->articles_count ?? $hc->articles()->count() }} topik bantuan platform)
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Panduan / Tanya Jawab Khusus Produk Toko --}}
                <div>
                    <label class="block text-xs font-bold text-on-surface mb-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-sm text-primary">quiz</span>
                        Daftar Tanya Jawab / Panduan Khusus Toko Anda:
                    </label>
                    <p class="text-[11px] text-on-surface-variant mb-2.5">Toko bebas membuat FAQ spesifik untuk produk ini (contoh: cara instalasi khusus, lisensi toko, akun demo, kontak bantuan teknis toko, dll):</p>
                    
                    <div id="faqs-container" class="space-y-3">
                        @foreach($faqs as $fIdx => $faq)
                        <div class="p-2.5 rounded-lg bg-surface border border-outline-variant/50 relative">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-primary text-base shrink-0">help</span>
                                <div class="flex-1">
                                    <input type="text" name="faqs[{{ $fIdx }}][question]" value="{{ $faq['question'] ?? '' }}" placeholder="Pertanyaan (cth: Bagaimana cara import database produk ini?)" class="w-full px-3 py-1.5 bg-surface-container-lowest border border-outline-variant rounded font-body-sm text-xs font-semibold">
                                </div>
                                <button type="button" onclick="this.closest('.p-2\\.5').remove()" class="p-1 text-error hover:bg-error/10 rounded transition" title="Hapus Pertanyaan">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </div>
                            <textarea name="faqs[{{ $fIdx }}][answer]" rows="2" placeholder="Jawaban / langkah panduan..." class="w-full px-3 py-1.5 bg-surface-container-lowest border border-outline-variant rounded text-xs">{{ $faq['answer'] ?? '' }}</textarea>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
function addHighlightRow() {
    const container = document.getElementById('highlights-container');
    const idx = Date.now();
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <div class="w-36">
            <select name="highlights[${idx}][icon]" class="w-full px-2 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
                <option value="verified">✓ verified</option>
                <option value="cloud_download">⬇ cloud_download</option>
                <option value="security">🛡 security</option>
                <option value="support_agent">🎧 support_agent</option>
                <option value="code">💻 code</option>
                <option value="speed">⚡ speed</option>
                <option value="bolt">⚡ bolt</option>
                <option value="check_circle">✔ check_circle</option>
            </select>
        </div>
        <div class="flex-1">
            <input type="text" name="highlights[${idx}][title]" placeholder="Judul Badge (cth: Source Code 100% Bersih)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition" title="Hapus">
            <span class="material-symbols-outlined text-sm">delete</span>
        </button>
    `;
    container.appendChild(div);
}

function addPackageRow() {
    const container = document.getElementById('package-container');
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <div class="flex-1">
            <input type="text" name="package_includes[]" placeholder="Item paket (cth: Dokumentasi PDF & Video)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition" title="Hapus">
            <span class="material-symbols-outlined text-sm">delete</span>
        </button>
    `;
    container.appendChild(div);
}

function addSysReqRow() {
    const container = document.getElementById('sysreq-container');
    const idx = Date.now();
    const div = document.createElement('div');
    div.className = 'flex items-center gap-2';
    div.innerHTML = `
        <div class="w-1/3">
            <input type="text" name="system_requirements[${idx}][label]" placeholder="Label (cth: Node.js / Python)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
        </div>
        <div class="flex-1">
            <input type="text" name="system_requirements[${idx}][value]" placeholder="Nilai (cth: Node 18+)" class="w-full px-3 py-1.5 bg-surface border border-outline-variant rounded-lg font-body-sm text-xs">
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition" title="Hapus">
            <span class="material-symbols-outlined text-sm">delete</span>
        </button>
    `;
    container.appendChild(div);
}

function addGuaranteeRow() {
    const container = document.getElementById('guarantees-container');
    const idx = Date.now();
    const div = document.createElement('div');
    div.className = 'p-2.5 rounded-lg bg-surface border border-outline-variant/50 relative';
    div.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <div class="w-32">
                <select name="guarantees[${idx}][icon]" class="w-full px-2 py-1 bg-surface-container-lowest border border-outline-variant rounded text-xs">
                    <option value="verified">verified</option>
                    <option value="support_agent">support_agent</option>
                    <option value="security_update_good">security_update_good</option>
                    <option value="balance">balance</option>
                    <option value="shield">shield</option>
                    <option value="lock">lock</option>
                </select>
            </div>
            <div class="flex-1">
                <input type="text" name="guarantees[${idx}][title]" placeholder="Judul Garansi" class="w-full px-3 py-1 bg-surface-container-lowest border border-outline-variant rounded font-body-sm text-xs font-semibold">
            </div>
            <button type="button" onclick="this.closest('.p-2\\\\.5').remove()" class="p-1 text-error hover:bg-error/10 rounded transition" title="Hapus">
                <span class="material-symbols-outlined text-sm">delete</span>
            </button>
        </div>
        <textarea name="guarantees[${idx}][description]" rows="2" placeholder="Penjelasan garansi..." class="w-full px-3 py-1.5 bg-surface-container-lowest border border-outline-variant rounded text-xs"></textarea>
    `;
    container.appendChild(div);
}

function addFaqRow() {
    const container = document.getElementById('faqs-container');
    const idx = Date.now();
    const div = document.createElement('div');
    div.className = 'p-2.5 rounded-lg bg-surface border border-outline-variant/50 relative';
    div.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-primary text-base shrink-0">help</span>
            <div class="flex-1">
                <input type="text" name="faqs[${idx}][question]" placeholder="Pertanyaan baru seputar produk..." class="w-full px-3 py-1.5 bg-surface-container-lowest border border-outline-variant rounded font-body-sm text-xs font-semibold">
            </div>
            <button type="button" onclick="this.closest('.p-2\\\\.5').remove()" class="p-1 text-error hover:bg-error/10 rounded transition" title="Hapus Pertanyaan">
                <span class="material-symbols-outlined text-sm">delete</span>
            </button>
        </div>
        <textarea name="faqs[${idx}][answer]" rows="2" placeholder="Jawaban atau penjelasan panduan..." class="w-full px-3 py-1.5 bg-surface-container-lowest border border-outline-variant rounded text-xs"></textarea>
    `;
    container.appendChild(div);
}
</script>
