<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\ProductCategory::all();
        $types = \App\Models\ProductType::all();

        $query = Product::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('product_category_id', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('product_type_id', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->whereRaw('COALESCE(discount_price, price) >= ?', [$request->min_price]);
        }

        if ($request->filled('max_price')) {
            $query->whereRaw('COALESCE(discount_price, price) <= ?', [$request->max_price]);
        }

        if ($request->sort == 'best_seller') {
            $query->withCount(['orders' => function ($q) {
                $q->where('status', 'paid');
            }])->orderBy('orders_count', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories', 'types'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('products.show', compact('product'));
    }
}
