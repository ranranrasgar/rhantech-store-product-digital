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
                $sub->where('store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            });
        });

        // Basic order & sales metrics
        $totalSales = $store->balance ?? 0;
        $totalOrders = (clone $ordersQuery)->whereIn('status', ['paid', 'downloaded'])->count();
        $pendingOrders = (clone $ordersQuery)->where('status', 'pending')->count();
        $allOrdersCount = (clone $ordersQuery)->count();

        // Products stats
        $totalProducts = $store->products()->count();
        $activeProducts = $store->products()->where('is_active', true)->count();

        // Top performing products
        $topProducts = $store->products()
            ->with(['images'])
            ->latest()
            ->take(5)
            ->get();

        // Conversion calculation (mock calculation based on actual paid vs total)
        $conversionRate = $allOrdersCount > 0 ? round(($totalOrders / $allOrdersCount) * 100, 1) : 0;
        $averageOrderValue = $totalOrders > 0 ? round($totalSales / $totalOrders) : 0;

        return view('tenant.performance.index', compact(
            'store',
            'totalSales',
            'totalOrders',
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
