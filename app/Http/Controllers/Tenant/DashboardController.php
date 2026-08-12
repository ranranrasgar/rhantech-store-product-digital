<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $store = $user->store;

        $totalProducts = $store ? $store->products()->count() : 0;
        $totalSales = $store ? $store->balance : 0;

        return view('tenant.dashboard', compact('store', 'totalProducts', 'totalSales'));
    }
}
