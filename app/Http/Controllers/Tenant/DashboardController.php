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
            // Jika user belum punya toko, tampilkan dashboard khusus pembeli
            return view('tenant.dashboard_buyer');
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

        // Total Pengunjung / Visitor (Kunjungan profil toko + seluruh view katalog produk)
        $storeViews = (int) ($store->views ?? 0);
        $productViews = (int) $store->products()->sum('views');
        $totalVisitors = $storeViews + $productViews;

        // 5 Pesanan Terbaru
        $recentOrders = (clone $ordersQuery)->with(['orderItems.product', 'product'])->latest()->take(5)->get();

        // Produk Unggulan / Terpopuler Toko
        $topProducts = $store->products()->with(['images'])->latest()->take(4)->get();

        // Kata Kunci & Tags Paling Banyak Dicari Pembeli di Platform (Insight Pasar)
        $trendingSearches = \App\Models\ProductSearch::orderByDesc('hits')
            ->orderByDesc('last_searched_at')
            ->take(12)
            ->get();

        return view('tenant.dashboard', compact(
            'store',
            'totalProducts',
            'activeProducts',
            'totalSales',
            'totalVisitors',
            'storeViews',
            'productViews',
            'totalOrdersCount',
            'pendingOrdersCount',
            'completedOrdersCount',
            'recentOrders',
            'topProducts',
            'trendingSearches'
        ));
    }
}
