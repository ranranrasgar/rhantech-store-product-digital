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

        // Get basic metrics
        $totalSales = Order::whereHas('product', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->where('status', 'paid')
            ->sum('amount');
            
        $totalOrders = Order::whereHas('product', function ($query) use ($store) {
                $query->where('store_id', $store->id);
            })
            ->where('status', 'paid')
            ->count();

        return view('tenant.performance.index', compact('store', 'totalSales', 'totalOrders'));
    }
}
