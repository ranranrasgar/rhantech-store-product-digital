<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\HelpCategory;
use App\Models\Store;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['store', 'images', 'category', 'type'])->latest();

        // 1. Filter store / origin: 'internal' (platform), or specific store_id
        if ($request->filled('origin')) {
            if ($request->origin === 'internal') {
                $query->whereNull('store_id');
            } elseif ($request->origin === 'tenant') {
                $query->whereNotNull('store_id');
            }
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        // 2. Filter approval status: 'all', 'pending', 'approved', 'rejected'
        if ($request->filled('approval_status') && in_array($request->approval_status, ['pending', 'approved', 'rejected'])) {
            $query->where('approval_status', $request->approval_status);
        }

        // 3. Filter active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }

        // 4. Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Counts for quick badges
        $totalCount = Product::count();
        $pendingCount = Product::whereNotNull('store_id')->where('approval_status', 'pending')->count();
        $approvedCount = Product::where('approval_status', 'approved')->count();
        $rejectedCount = Product::where('approval_status', 'rejected')->count();
        $internalCount = Product::whereNull('store_id')->count();
        $tenantCount = Product::whereNotNull('store_id')->count();

        $products = $query->paginate(20)->withQueryString();
        $stores = Store::orderBy('name', 'asc')->get();

        return view('admin.products.index', compact(
            'products', 
            'stores', 
            'totalCount', 
            'pendingCount', 
            'approvedCount', 
            'rejectedCount', 
            'internalCount', 
            'tenantCount'
        ));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $types = ProductType::orderBy('name', 'asc')->get();
        $helpCategories = HelpCategory::orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories', 'types', 'helpCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'help_category_id' => 'nullable|exists:help_categories,id',
            'description' => 'required|string',
            'demo_url' => 'nullable|url|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx|max:102400', // 100MB max
            'download_links' => 'nullable|array',
            'download_links.*.name' => 'required_with:download_links|string|max:255',
            'download_links.*.url' => 'required_with:download_links|url|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'images' => 'required|array|min:1|max:5',
            'is_active' => 'boolean',
            'rating_override' => 'nullable|numeric|min:1|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'sales_count' => 'nullable|integer|min:0',
            'highlights' => 'nullable|array',
            'package_includes' => 'nullable|array',
            'system_requirements' => 'nullable|array',
            'guarantees' => 'nullable|array',
            'faqs' => 'nullable|array',
        ]);

        // Filter and clean dynamic array fields
        $highlights = $this->cleanArrayItems($request->input('highlights'));
        $packageIncludes = $this->cleanArrayItems($request->input('package_includes'));
        $systemRequirements = $this->cleanAssocItems($request->input('system_requirements'));
        $guarantees = $this->cleanAssocItems($request->input('guarantees'));
        $faqs = $this->cleanFaqItems($request->input('faqs'));

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'] ?? null,
            'product_type_id' => $validated['product_type_id'] ?? null,
            'help_category_id' => $validated['help_category_id'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'price' => $validated['price'],
            'discount_price' => (!empty($validated['discount_price']) && (float)$validated['discount_price'] > 0) ? $validated['discount_price'] : null,
            'download_links' => $validated['download_links'] ?? null,
            'is_active' => $request->has('is_active'),
            'rating_override' => $validated['rating_override'] ?? null,
            'reviews_count' => $validated['reviews_count'] ?? null,
            'sales_count' => $validated['sales_count'] ?? null,
            'highlights' => $highlights,
            'package_includes' => $packageIncludes,
            'system_requirements' => $systemRequirements,
            'guarantees' => $guarantees,
            'faqs' => $faqs,
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('digital_products'); // local non-public disk
            $product->update(['file_path' => $path]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        // Jika reviews_count diisi, otomatis buatkan ulasan random berbahasa Indonesia sesuai nama produk
        if ($request->filled('reviews_count') && (int)$request->input('reviews_count') > 0) {
            $this->syncRandomReviews($product, (int)$request->input('reviews_count'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $types = ProductType::orderBy('name', 'asc')->get();
        $helpCategories = HelpCategory::orderBy('name', 'asc')->get();
        return view('admin.products.edit', compact('product', 'categories', 'types', 'helpCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'help_category_id' => 'nullable|exists:help_categories,id',
            'description' => 'required|string',
            'demo_url' => 'nullable|url|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx|max:102400', 
            'download_links' => 'nullable|array',
            'download_links.*.name' => 'required_with:download_links|string|max:255',
            'download_links.*.url' => 'required_with:download_links|url|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'images' => 'nullable|array|max:5',
            'is_active' => 'boolean',
            'rating_override' => 'nullable|numeric|min:1|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'sales_count' => 'nullable|integer|min:0',
            'highlights' => 'nullable|array',
            'package_includes' => 'nullable|array',
            'system_requirements' => 'nullable|array',
            'guarantees' => 'nullable|array',
            'faqs' => 'nullable|array',
        ]);

        $highlights = $this->cleanArrayItems($request->input('highlights'));
        $packageIncludes = $this->cleanArrayItems($request->input('package_includes'));
        $systemRequirements = $this->cleanAssocItems($request->input('system_requirements'));
        $guarantees = $this->cleanAssocItems($request->input('guarantees'));
        $faqs = $this->cleanFaqItems($request->input('faqs'));

        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'] ?? null,
            'product_type_id' => $validated['product_type_id'] ?? null,
            'help_category_id' => $validated['help_category_id'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'price' => $validated['price'],
            'discount_price' => (!empty($validated['discount_price']) && (float)$validated['discount_price'] > 0) ? $validated['discount_price'] : null,
            'download_links' => $validated['download_links'] ?? null,
            'is_active' => $request->has('is_active'),
            'rating_override' => $validated['rating_override'] ?? null,
            'reviews_count' => $validated['reviews_count'] ?? null,
            'sales_count' => $validated['sales_count'] ?? null,
            'highlights' => $highlights,
            'package_includes' => $packageIncludes,
            'system_requirements' => $systemRequirements,
            'guarantees' => $guarantees,
            'faqs' => $faqs,
        ]);

        if ($request->hasFile('file')) {
            if ($product->file_path) {
                Storage::delete($product->file_path);
            }
            $path = $request->file('file')->store('digital_products');
            $product->update(['file_path' => $path]);
        }

        if ($request->hasFile('images')) {
            $currentImagesCount = $product->images()->count();
            $newImagesCount = count($request->file('images'));
            
            if ($currentImagesCount + $newImagesCount > 5) {
                return back()->withErrors(['images' => 'Maximum 5 images allowed total.'])->withInput();
            }

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        // Jika reviews_count diisi, otomatis buatkan/sinkronkan ulasan random berbahasa Indonesia sesuai judul produk
        if ($request->filled('reviews_count') && (int)$request->input('reviews_count') > 0) {
            $this->syncRandomReviews($product, (int)$request->input('reviews_count'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->file_path) {
            Storage::delete($product->file_path);
        }
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Image removed.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Product status updated.');
    }

    public function setMainImage(ProductImage $image)
    {
        // Set all other images for this product to not main
        ProductImage::where('product_id', $image->product_id)->update(['is_main' => false]);
        // Set this image to main
        $image->update(['is_main' => true]);
        return back()->with('success', 'Main image updated.');
    }

    public function approve(Product $product)
    {
        $product->update([
            'approval_status' => 'approved',
            'rejection_reason' => null,
            'is_active' => true, // Automatically activate upon approval
        ]);

        return back()->with('success', "Produk \"{$product->name}\" berhasil disetujui (Approved) dan sudah tayang di website!");
    }

    public function reject(Request $request, Product $product)
    {
        $validated = $request->validate([
            'reason_type' => 'required|string',
            'custom_reason' => 'nullable|string|max:500',
        ]);

        $reason = $validated['reason_type'];
        if ($reason === 'other' && !empty($validated['custom_reason'])) {
            $reason = $validated['custom_reason'];
        } elseif (!empty($validated['custom_reason'])) {
            $reason .= ' - Catatan: ' . $validated['custom_reason'];
        }

        $product->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $reason,
            'is_active' => false,
        ]);

        return back()->with('info', "Produk \"{$product->name}\" telah ditolak dengan alasan yang disimpan.");
    }

    protected function cleanArrayItems($items)
    {
        if (!is_array($items)) return null;
        $cleaned = [];
        foreach ($items as $item) {
            $val = is_string($item) ? trim($item) : $item;
            if (!empty($val)) {
                $cleaned[] = $val;
            }
        }
        return count($cleaned) > 0 ? array_values($cleaned) : null;
    }

    protected function cleanAssocItems($items)
    {
        if (!is_array($items)) return null;
        $cleaned = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $title = isset($item['title']) ? trim($item['title']) : (isset($item['label']) ? trim($item['label']) : '');
                $desc = isset($item['description']) ? trim($item['description']) : (isset($item['value']) ? trim($item['value']) : '');
                $icon = isset($item['icon']) ? trim($item['icon']) : null;
                if ($title !== '' || $desc !== '') {
                    $cleaned[] = [
                        'title' => $title,
                        'description' => $desc,
                        'icon' => $icon,
                    ];
                }
            }
        }
        return count($cleaned) > 0 ? array_values($cleaned) : null;
    }

    protected function cleanFaqItems($items)
    {
        if (!is_array($items)) return null;
        $cleaned = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $q = isset($item['question']) ? trim($item['question']) : '';
                $a = isset($item['answer']) ? trim($item['answer']) : '';
                if ($q !== '' && $a !== '') {
                    $cleaned[] = [
                        'question' => $q,
                        'answer' => $a,
                    ];
                }
            }
        }
        return count($cleaned) > 0 ? array_values($cleaned) : null;
    }

    /**
     * Otomatis sinkronkan/buat ulasan random berbahasa Indonesia sesuai nama/judul produk
     */
    protected function syncRandomReviews(Product $product, int $targetCount)
    {
        // Batasi maksimal yang dibuat di database (misal max 150 agar database tetap ringan dan cepat)
        $targetCount = min($targetCount, 150);

        // Ambil ulasan yang sudah ada untuk produk ini
        $existingReviewsCount = \App\Models\ProductReview::where('product_id', $product->id)->count();
        $needToCreate = $targetCount - $existingReviewsCount;

        if ($needToCreate <= 0) {
            return;
        }

        // Ambil nama-nama user yang bukan akun rill mendaftar mandiri (admin, staff, demo, atau user seeder)
        $systemUserNames = \App\Models\User::where(function($q) {
            $q->whereIn('role', ['admin', 'Admin', 'superadmin', 'Superadmin', 'accounting', 'staff', 'demo'])
              ->orWhere('email', 'like', '%@rhantech.com')
              ->orWhere('email', 'like', '%@example%')
              ->orWhere('email', 'like', '%@store%');
        })->pluck('name')->filter()->toArray();

        // Bank nama pembeli lokal Indonesia yang natural & beragam untuk melengkapi
        $fallbackNames = [
            'Budi Santoso', 'Rizky Pratama', 'Ahmad Fauzi', 'Dian Permana', 'Hendra Setiawan',
            'Dimas Saputra', 'Bayu Nugroho', 'Fajar Ramadhan', 'Aris Munandar', 'Wahyu Hidayat',
            'Agus Triyono', 'Eko Prasetyo', 'Doni Kurniawan', 'Rian Kusuma', 'Teguh Wibowo',
            'Siti Rahmawati', 'Anisa Nur', 'Dewi Lestari', 'Putri Ayu', 'Rina Anggraini',
            'Indah Pertiwi', 'Nurul Hidayah', 'Tri Wahyuni', 'Fitri Handayani', 'Mega Silvia',
            'Yusuf Maulana', 'Gilang Ramadhan', 'Aditya Wijaya', 'Bagus Prabowo', 'Ilham Syahputra',
            'M. Reza Fahlevi', 'Danang Prasetya', 'Bambang Supriyanto', 'Arief Budiman', 'Yudi Hermawan',
            'Sandi Gunawan', 'Ferry Irawan', 'Taufik Rahman', 'Irvan Fachrudin', 'Lukman Hakim'
        ];

        $poolNames = array_values(array_unique(array_merge($systemUserNames, $fallbackNames)));

        $productName = $product->name;

        // Template kalimat ulasan natural bahasa Indonesia bertema produk digital / source code / aplikasi
        $templates = [
            "Source code {$productName} sangat rapi dan mudah dimengerti. Panduan instalasinya jelas, langsung running lancar di localhost tanpa error. Mantap!",
            "Alhamdulillah sangat puas dengan {$productName}. Fiturnya lengkap sesuai deskripsi dan sangat membantu mempercepat project klien saya.",
            "Aplikasi {$productName} ini recommended banget! Desain UI-nya modern, clean, dan kodingannya mudah di-custom. Admin juga fast respon saat ditanya.",
            "Proses download instan langsung masuk email setelah checkout. {$productName} bekerja 100% normal tanpa kendala. Terima kasih Rhantech!",
            "Bagus sekali, kodingan {$productName} rapi berstandar MVC/Laravel. Dokumentasi lengkap dan database langsung siap import.",
            "Sangat worth it dengan harganya. Menghemat waktu development berminggu-minggu berkat {$productName}. Sukses terus buat developernya!",
            "Awalnya ragu, tapi setelah coba pasang {$productName} ternyata beneran work 100% dan bebas error. Pelayanan dan responnya jempolan!",
            "Fitur di {$productName} lengkap banget dan responsive saat dibuka di HP maupun laptop. Kualitasnya jempolan, bintang lima!",
            "Pengalaman beli {$productName} sangat memuaskan. File zip lengkap beserta panduan step by step, langsung bisa dipakai.",
            "Sangat membantu bisnis kami. Modul di dalam {$productName} sangat terstruktur dan mudah disesuaikan dengan kebutuhan.",
            "Recomended seller! {$productName} kualitas premium, source code bersih tanpa enkripsi jadi gampang dimodifikasi.",
            "Mantap pisan {$productName}, proses instalasinya gampang banget tinggal import database dan setting config. Top!",
            "Aplikasi {$productName} ini bener-bener powerful. Fitur-fiturnya lengkap dan tampilannya sangat memanjakan mata.",
            "Sesuai ekspektasi dan gambar demo! {$productName} berjalan mulus di server hosting cPanel maupun localhost. Makasih banyak!",
            "Keren abis! Source code {$productName} mudah dipelajari buat bahan skripsi / portofolio maupun dipakai langsung untuk bisnis.",
            "Pelayanan memuaskan, link unduhan {$productName} langsung aktif hitungan detik setelah bayar. Sangat profesional.",
            "Kualitas {$productName} bintang lima, fungsi-fungsi krusialnya berjalan optimal. Worth every rupiah!",
            "Sudah coba beberapa modul di {$productName}, semuanya berjalan lancar. Dokumentasi PDF-nya ngebantu banget.",
            "Sangat puas order {$productName} di sini. Source code bersih tanpa malware dan supportnya ramah ketika ada pertanyaan teknis.",
            "Produk {$productName} sangat recommended untuk developer atau agensi yang mau hemat waktu buat bikin sistem."
        ];

        for ($i = 0; $i < $needToCreate; $i++) {
            $randomName = $poolNames[array_rand($poolNames)];
            $randomTemplate = $templates[array_rand($templates)];
            // Distribusi rating realistis (sebagian besar 5 bintang, sedikit 4 bintang)
            $randomRating = (rand(1, 10) <= 8) ? 5 : 4;
            $randomDaysAgo = rand(1, 45);
            $randomCreatedAt = now()->subDays($randomDaysAgo)->subHours(rand(1, 23))->subMinutes(rand(1, 59));

            \App\Models\ProductReview::create([
                'product_id' => $product->id,
                'user_id' => null,
                'order_id' => null,
                'customer_name' => $randomName,
                'customer_email' => \Illuminate\Support\Str::slug($randomName) . rand(10, 99) . '@gmail.com',
                'rating' => $randomRating,
                'comment' => $randomTemplate,
                'is_visible' => true,
                'created_at' => $randomCreatedAt,
                'updated_at' => $randomCreatedAt,
            ]);
        }
    }
}
