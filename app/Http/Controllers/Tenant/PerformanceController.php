<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the performance metrics.
     */
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        // Query orders belonging to this store
        $ordersQuery = Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            });
        });

        // Basic order & sales metrics (consolidated in 1 query)
        $totalSales = (float) ($store->balance ?? 0);
        $orderStats = (clone $ordersQuery)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status IN ('paid', 'downloaded') THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending
        ")->first();

        $allOrdersCount = (int) ($orderStats->total ?? 0);
        $totalOrders = (int) ($orderStats->completed ?? 0);
        $pendingOrders = (int) ($orderStats->pending ?? 0);

        // Products stats (consolidated in 1 query)
        $productStats = $store->products()->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(views) as total_views
        ")->first();

        $totalProducts = (int) ($productStats->total ?? 0);
        $activeProducts = (int) ($productStats->active ?? 0);
        $productViews = (int) ($productStats->total_views ?? 0);

        // Top performing products
        $topProducts = $store->products()
            ->with(['images'])
            ->latest()
            ->take(5)
            ->get();

        // Total Pengunjung / Visitor (Toko + Produk)
        $storeViews = (int) ($store->views ?? 0);
        $totalVisitors = $storeViews + $productViews;

        // Conversion calculation (mock calculation based on actual paid vs total)
        $conversionRate = $allOrdersCount > 0 ? round(($totalOrders / $allOrdersCount) * 100, 1) : 0;
        $averageOrderValue = $totalOrders > 0 ? round($totalSales / $totalOrders) : 0;

        return view('tenant.performance.index', compact(
            'store',
            'totalSales',
            'totalOrders',
            'totalVisitors',
            'storeViews',
            'productViews',
            'pendingOrders',
            'allOrdersCount',
            'totalProducts',
            'activeProducts',
            'topProducts',
            'conversionRate',
            'averageOrderValue'
        ));
    }
}
