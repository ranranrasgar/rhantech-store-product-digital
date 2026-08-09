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

        if (!$store) {
            return redirect()->route('tenant.store.index')->with('warning', 'Please setup your store profile first.');
        }

        $totalProducts = $store->products()->count();
        $totalSales = $store->balance; // Simplified for now

        return view('tenant.dashboard', compact('store', 'totalProducts', 'totalSales'));
    }
}
