<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::withCount('products')->orderBy('name', 'asc')->get();
        return view('admin.product_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        ProductCategory::create($validated);
        
        return back()->with('success', 'Category added.');
    }

    public function update(Request $request, ProductCategory $product_category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $product_category->update($validated);
        
        return back()->with('success', 'Category updated.');
    }

    public function destroy(ProductCategory $product_category)
    {
        $product_category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
