<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        // Fetch products for the dropdown filter
        $products = Product::where('store_id', $store->id)->get();

        // Build the base query: support both multi-item cart orders and single product orders
        $query = Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            });
        })->with(['product', 'orderItems.product.images']);

        // 1. Tab filter
        $tab = $request->input('tab', 'all');
        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'completed') {
            $query->whereIn('status', ['paid', 'downloaded']);
        } elseif ($tab === 'cancelled') {
            $query->where('status', 'failed');
        }

        // 2. Product filter
        if ($request->filled('product_id')) {
            $productId = $request->input('product_id');
            $query->where(function ($q) use ($productId) {
                $q->where('product_id', $productId)
                    ->orWhereHas('orderItems', function ($sub) use ($productId) {
                        $sub->where('product_id', $productId);
                    });
            });
        }

        // 3. Search filter
        if ($request->filled('search_query')) {
            $searchType = $request->input('search_type', 'invoice');
            $searchQuery = $request->input('search_query');

            if ($searchType === 'invoice') {
                $query->where('invoice_number', 'like', '%' . $searchQuery . '%');
            } elseif ($searchType === 'customer') {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('customer_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('customer_email', 'like', '%' . $searchQuery . '%');
                });
            }
        }

        // Execute query
        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('tenant.orders.index', compact('orders', 'products', 'tab', 'store'));
    }
}
