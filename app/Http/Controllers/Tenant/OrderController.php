<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        $orders = Order::whereHas('product', function ($query) use ($store) {
            $query->where('store_id', $store->id);
        })->with('product')->latest()->paginate(20);

        return view('tenant.orders.index', compact('orders'));
    }
}
