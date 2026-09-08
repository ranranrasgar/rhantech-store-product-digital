<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductType;
use App\Models\HelpCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) return redirect()->route('tenant.store.index')->with('warning', 'Please setup your store first.');

        // Get total counts for the tabs
        $allCount = $store->products()->count();
        $activeCount = $store->products()->where('is_active', true)->where('approval_status', 'approved')->count();
        $pendingCount = $store->products()->where('approval_status', 'pending')->count();
        $rejectedCount = $store->products()->where('approval_status', 'rejected')->count();
        $inactiveCount = $store->products()->where('is_active', false)->count();

        // Query builder for products
        $query = $store->products();

        // 1. Tab filter
        $tab = $request->input('tab', 'all');
        if ($tab === 'active') {
            $query->where('is_active', true)->where('approval_status', 'approved');
        } elseif ($tab === 'pending') {
            $query->where('approval_status', 'pending');
        } elseif ($tab === 'rejected') {
            $query->where('approval_status', 'rejected');
        } elseif ($tab === 'inactive') {
            $query->where('is_active', false);
        }

        // 2. Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', $search)
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        // 3. Category filter
        if ($request->filled('category')) {
            $query->where('product_category_id', $request->input('category'));
        }

        // 4. Sort
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc'); // latest
        }

        $products = $query->paginate(20)->withQueryString();
        $categories = ProductCategory::orderBy('name', 'asc')->get();

        return view('tenant.products.index', compact('products', 'allCount', 'activeCount', 'pendingCount', 'rejectedCount', 'inactiveCount', 'categories', 'tab'));
    }

    public function create(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        $sourceProduct = null;
        if ($request->filled('copy')) {
            $sourceProduct = Product::where('store_id', $store->id)
                ->with(['images', 'category', 'type'])
                ->find($request->input('copy'));
        }

        $categories = ProductCategory::whereNull('store_id')
            ->orWhere('store_id', $store->id)
            ->orderByRaw('store_id IS NULL DESC, name ASC')
            ->get();
        $types = ProductType::whereNull('store_id')
            ->orWhere('store_id', $store->id)
            ->orderByRaw('store_id IS NULL DESC, name ASC')
            ->get();
        $helpCategories = HelpCategory::orderBy('name', 'asc')->get();
        return view('tenant.products.create', compact('categories', 'types', 'sourceProduct', 'helpCategories'));
    }

    public function quickStoreCategory(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 403);

        $request->validate(['name' => 'required|string|max:100']);
        $slug = \Illuminate\Support\Str::slug($request->name) . '-' . $store->id;

        $cat = ProductCategory::create([
            'store_id' => $store->id,
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return response()->json([
            'success' => true,
            'category' => $cat,
            'message' => 'Kategori toko berhasil ditambahkan'
        ]);
    }

    public function quickStoreType(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan'], 403);

        $request->validate(['name' => 'required|string|max:100']);
        $slug = \Illuminate\Support\Str::slug($request->name) . '-' . $store->id;

        $type = ProductType::create([
            'store_id' => $store->id,
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return response()->json([
            'success' => true,
            'type' => $type,
            'message' => 'Tipe produk berhasil ditambahkan'
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'help_category_id' => 'nullable|exists:help_categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'demo_url' => 'nullable|url|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx|max:102400', // 100MB max
            'download_links' => 'required|array|min:1',
            'download_links.*.name' => 'required|string|max:255',
            'download_links.*.url' => 'required|url|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp,gif|max:2048',
            'copied_from' => 'nullable|exists:products,id',
            'is_active' => 'boolean',
            'rating_override' => 'nullable|numeric|min:1|max:5',
            'reviews_count' => 'nullable|integer|min:0',
            'sales_count' => 'nullable|integer|min:0',
            'highlights' => 'nullable|array',
            'package_includes' => 'nullable|array',
            'system_requirements' => 'nullable|array',
            'guarantees' => 'nullable|array',
            'faqs' => 'nullable|array',
        ];

        // If not copying from existing product, images are required
        if (!$request->filled('copied_from')) {
            $rules['images'] = 'required|array|min:1|max:5';
        } else {
            $rules['images'] = 'nullable|array|max:5';
        }

        $validated = $request->validate($rules, [
            'images.*.max' => 'Ukuran setiap gambar produk tidak boleh lebih dari 2 MB.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau gif.',
            'images.max' => 'Maksimal 5 gambar diperbolehkan.',
            'file.max' => 'Ukuran file produk maksimal 100 MB.',
        ]);

        $store = Auth::user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        $highlights = $this->cleanArrayItems($request->input('highlights'));
        $packageIncludes = $this->cleanArrayItems($request->input('package_includes'));
        $systemRequirements = $this->cleanAssocItems($request->input('system_requirements'));
        $guarantees = $this->cleanAssocItems($request->input('guarantees'));
        $faqs = $this->cleanFaqItems($request->input('faqs'));

        $product = Product::create([
            'store_id' => $store->id,
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'] ?? null,
            'product_type_id' => $validated['product_type_id'] ?? null,
            'help_category_id' => $validated['help_category_id'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'price' => $validated['price'],
            'discount_price' => (!empty($validated['discount_price']) && (float)$validated['discount_price'] > 0) ? $validated['discount_price'] : null,
            'download_links' => $validated['download_links'] ?? null,
            'is_active' => $request->has('is_active'),
            'approval_status' => 'pending',
            'rejection_reason' => null,
            'rating_override' => null,
            'reviews_count' => null,
            'sales_count' => null,
            'highlights' => $highlights,
            'package_includes' => $packageIncludes,
            'system_requirements' => $systemRequirements,
            'guarantees' => $guarantees,
            'faqs' => $faqs,
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('digital_products'); // local non-public disk
            $product->update(['file_path' => $path]);
        } elseif ($request->filled('copied_from')) {
            $sourceProduct = Product::where('store_id', $store->id)->find($request->input('copied_from'));
            if ($sourceProduct && $sourceProduct->file_path && Storage::exists($sourceProduct->file_path)) {
                $ext = pathinfo($sourceProduct->file_path, PATHINFO_EXTENSION);
                $newPath = 'digital_products/' . Str::random(40) . ($ext ? '.' . $ext : '');
                Storage::copy($sourceProduct->file_path, $newPath);
                $product->update(['file_path' => $newPath]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        } elseif ($request->filled('copied_from')) {
            $sourceProduct = Product::where('store_id', $store->id)->with('images')->find($request->input('copied_from'));
            if ($sourceProduct && $sourceProduct->images->count() > 0) {
                foreach ($sourceProduct->images as $sourceImg) {
                    if (Storage::disk('public')->exists($sourceImg->image_path)) {
                        $ext = pathinfo($sourceImg->image_path, PATHINFO_EXTENSION);
                        $newImagePath = 'products/' . Str::random(40) . ($ext ? '.' . $ext : '');
                        Storage::disk('public')->copy($sourceImg->image_path, $newImagePath);
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $newImagePath,
                            'is_main' => $sourceImg->is_main
                        ]);
                    }
                }
            }
        }

        return redirect()->route('tenant.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $store = Auth::user()->store;
        if ($product->store_id !== $store->id) abort(403);
        $product->load('images');
        $categories = ProductCategory::whereNull('store_id')
            ->orWhere('store_id', $store->id)
            ->orderByRaw('store_id IS NULL DESC, name ASC')
            ->get();
        $types = ProductType::whereNull('store_id')
            ->orWhere('store_id', $store->id)
            ->orderByRaw('store_id IS NULL DESC, name ASC')
            ->get();
        $helpCategories = HelpCategory::orderBy('name', 'asc')->get();
        return view('tenant.products.edit', compact('product', 'categories', 'types', 'helpCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'help_category_id' => 'nullable|exists:help_categories,id',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'demo_url' => 'nullable|url|max:255',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx,xls,xlsx|max:102400',
            'download_links' => 'required|array|min:1',
            'download_links.*.name' => 'required|string|max:255',
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
        ], [
            'images.*.max' => 'Ukuran setiap gambar produk tidak boleh lebih dari 2 MB.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, webp, atau gif.',
            'images.max' => 'Maksimal 5 gambar diperbolehkan.',
            'file.max' => 'Ukuran file produk maksimal 100 MB.',
        ]);

        if ($product->store_id !== Auth::user()->store->id) abort(403);

        $highlights = $this->cleanArrayItems($request->input('highlights'));
        $packageIncludes = $this->cleanArrayItems($request->input('package_includes'));
        $systemRequirements = $this->cleanAssocItems($request->input('system_requirements'));
        $guarantees = $this->cleanAssocItems($request->input('guarantees'));
        $faqs = $this->cleanFaqItems($request->input('faqs'));

        $product->update([
            'name' => $validated['name'],
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'] ?? null,
            'product_type_id' => $validated['product_type_id'] ?? null,
            'help_category_id' => $validated['help_category_id'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'price' => $validated['price'],
            'discount_price' => (!empty($validated['discount_price']) && (float)$validated['discount_price'] > 0) ? $validated['discount_price'] : null,
            'download_links' => $validated['download_links'] ?? null,
            'is_active' => $request->has('is_active'),
            'approval_status' => $product->approval_status === 'rejected' ? 'pending' : $product->approval_status,
            'rejection_reason' => $product->approval_status === 'rejected' ? null : $product->rejection_reason,
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

        return redirect()->route('tenant.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->store_id !== Auth::user()->store->id) abort(403);
        if ($product->file_path) {
            Storage::delete($product->file_path);
        }
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $product->delete();
        return redirect()->route('tenant.products.index')->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(ProductImage $image)
    {
        if ($image->product->store_id !== Auth::user()->store->id) abort(403);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return back()->with('success', 'Image removed.');
    }

    public function toggleActive(Product $product)
    {
        if ($product->store_id !== Auth::user()->store->id) abort(403);
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', 'Product status updated.');
    }

    public function setMainImage(ProductImage $image)
    {
        if ($image->product->store_id !== Auth::user()->store->id) abort(403);
        // Set all other images for this product to not main
        ProductImage::where('product_id', $image->product_id)->update(['is_main' => false]);
        // Set this image to main
        $image->update(['is_main' => true]);
        return back()->with('success', 'Main image updated.');
    }

    protected function cleanArrayItems(mixed $items): ?array
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

    protected function cleanAssocItems(mixed $items): ?array
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

    protected function cleanFaqItems(mixed $items): ?array
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
}
