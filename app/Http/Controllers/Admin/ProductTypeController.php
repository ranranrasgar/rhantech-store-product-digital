<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductType;
use Illuminate\Support\Str;

class ProductTypeController extends Controller
{
    public function index()
    {
        $types = ProductType::orderBy('name', 'asc')->get();
        return view('admin.product_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        ProductType::create($validated);
        
        return back()->with('success', 'Type added.');
    }

    public function update(Request $request, ProductType $product_type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $product_type->update($validated);
        
        return back()->with('success', 'Type updated.');
    }

    public function destroy(ProductType $product_type)
    {
        $product_type->delete();
        return back()->with('success', 'Type deleted.');
    }
}
