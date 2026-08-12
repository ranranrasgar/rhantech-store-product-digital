<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $store = Store::where('user_id', Auth::id())->first();

        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        $query = \App\Models\Affiliate::query();

        // 1. Search Filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('handle', 'like', '%' . $request->search . '%');
        }

        // 2. Category Filter
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->whereJsonContains('categories', $request->category);
        }
        
        // 3. Platform Filter
        if ($request->filled('platform') && $request->platform !== 'Semua') {
            $query->where('platform', strtolower($request->platform));
        }

        $affiliates = $query->paginate(20)->withQueryString();
        
        $categories = \App\Models\ProductCategory::pluck('name');

        return view('tenant.affiliates.index', compact('store', 'affiliates', 'categories'));
    }
}
