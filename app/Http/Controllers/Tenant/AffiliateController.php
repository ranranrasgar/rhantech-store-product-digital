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
    public function create()
    {
        $categories = \App\Models\ProductCategory::pluck('name');
        return view('tenant.affiliates.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'handle' => 'required|string|max:255',
            'avatar_url' => 'nullable|string',
            'followers_count' => 'required|string|max:255',
            'clicks_count' => 'required|string|max:255',
            'orders_count' => 'required|string|max:255',
            'sales_range' => 'required|string|max:255',
            'audience_demographic' => 'nullable|string|max:255',
            'platform' => 'required|string|in:Instagram,Tiktok,Facebook,Youtube,Twitter',
            'categories' => 'nullable|array',
            'is_golden_tick' => 'boolean',
        ]);

        \App\Models\Affiliate::create($validated);

        return redirect()->route('tenant.affiliates.index')->with('success', 'Affiliate berhasil ditambahkan.');
    }

    public function edit(\App\Models\Affiliate $affiliate)
    {
        $categories = \App\Models\ProductCategory::pluck('name');
        return view('tenant.affiliates.edit', compact('affiliate', 'categories'));
    }

    public function update(Request $request, \App\Models\Affiliate $affiliate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'handle' => 'required|string|max:255',
            'avatar_url' => 'nullable|string',
            'followers_count' => 'required|string|max:255',
            'clicks_count' => 'required|string|max:255',
            'orders_count' => 'required|string|max:255',
            'sales_range' => 'required|string|max:255',
            'audience_demographic' => 'nullable|string|max:255',
            'platform' => 'required|string|in:Instagram,Tiktok,Facebook,Youtube,Twitter',
            'categories' => 'nullable|array',
            'is_golden_tick' => 'boolean',
        ]);
        
        $validated['is_golden_tick'] = $request->has('is_golden_tick');

        $affiliate->update($validated);

        return redirect()->route('tenant.affiliates.index')->with('success', 'Affiliate berhasil diperbarui.');
    }

    public function destroy(\App\Models\Affiliate $affiliate)
    {
        $affiliate->delete();
        return redirect()->route('tenant.affiliates.index')->with('success', 'Affiliate berhasil dihapus.');
    }
}
