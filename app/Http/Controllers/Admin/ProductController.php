<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $types = ProductType::orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_type_id' => 'nullable|exists:product_types,id',
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
            'is_active' => 'boolean'
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'] ?? null,
            'product_type_id' => $validated['product_type_id'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'download_links' => $validated['download_links'] ?? null,
            'is_active' => $request->has('is_active'),
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

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = ProductCategory::orderBy('name', 'asc')->get();
        $types = ProductType::orderBy('name', 'asc')->get();
        return view('admin.products.edit', compact('product', 'categories', 'types'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'product_type_id' => 'nullable|exists:product_types,id',
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
            'is_active' => 'boolean'
        ]);

        $product->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']) . '-' . Str::random(5),
            'description' => $validated['description'],
            'product_category_id' => $validated['product_category_id'] ?? null,
            'product_type_id' => $validated['product_type_id'] ?? null,
            'demo_url' => $validated['demo_url'] ?? null,
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'download_links' => $validated['download_links'] ?? null,
            'is_active' => $request->has('is_active'),
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
}
