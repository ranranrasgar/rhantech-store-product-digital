<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            return redirect()->route('tenant.store.index')->with('info', 'Silakan buat profil toko Anda terlebih dahulu.');
        }

        // Hitung total produk & produk aktif
        $totalProducts = $store->products()->count();
        $activeProducts = $store->products()->where('is_active', true)->count();

        // Pesanan terkait produk toko
        $ordersQuery = \App\Models\Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            });
        });

        $totalOrdersCount = (clone $ordersQuery)->count();
        $pendingOrdersCount = (clone $ordersQuery)->where('status', 'pending')->count();
        $completedOrdersCount = (clone $ordersQuery)->whereIn('status', ['paid', 'downloaded'])->count();

        // Total penghasilan toko
        $totalSales = $store->balance;

        // 5 Pesanan Terbaru
        $recentOrders = (clone $ordersQuery)->with(['orderItems.product', 'product'])->latest()->take(5)->get();

        // Produk Unggulan / Terpopuler Toko
        $topProducts = $store->products()->with(['images'])->latest()->take(4)->get();

        return view('tenant.dashboard', compact(
            'store',
            'totalProducts',
            'activeProducts',
            'totalSales',
            'totalOrdersCount',
            'pendingOrdersCount',
            'completedOrdersCount',
            'recentOrders',
            'topProducts'
        ));
    }
}
