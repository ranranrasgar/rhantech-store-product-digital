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

        $query = \App\Models\Affiliate::where(function($q) use ($store) {
            $q->where('store_id', $store->id)
              ->orWhereNull('store_id');
        });

        // 1. Search Filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('handle', 'like', '%' . $request->search . '%')
                  ->orWhere('referral_code', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Platform Filter
        if ($request->filled('platform') && $request->platform !== 'Semua') {
            $query->where('platform', strtolower($request->platform));
        }

        $affiliates = $query->latest()->paginate(20)->withQueryString();
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
        $store = Store::where('user_id', Auth::id())->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'handle' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:30',
            'referral_code' => 'nullable|string|max:50|unique:affiliates,referral_code',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'platform' => 'nullable|string|max:50',
            'followers_count' => 'nullable|string|max:255',
            'categories' => 'nullable|array',
            'is_golden_tick' => 'nullable|boolean',
        ]);

        // Upload avatar jika ada
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('affiliates/avatars', 'public');
            $validated['avatar_url'] = '/storage/' . $path;
        }

        // Generate referral code jika kosong
        if (empty($validated['referral_code'])) {
            $base = !empty($validated['handle']) ? preg_replace('/[^A-Za-z0-9]/', '', $validated['handle']) : preg_replace('/[^A-Za-z0-9]/', '', $validated['name']);
            $base = strtoupper(substr($base, 0, 6));
            if (empty($base)) $base = 'AFF';
            $validated['referral_code'] = $base . rand(100, 999);
        } else {
            $validated['referral_code'] = strtoupper(preg_replace('/[^A-Za-z0-9_-]/', '', $validated['referral_code']));
        }

        $validated['store_id'] = $store ? $store->id : null;
        $validated['handle'] = $validated['handle'] ?? ('@' . \Illuminate\Support\Str::slug($validated['name']));
        $validated['followers_count'] = $validated['followers_count'] ?? '-';
        $validated['clicks_count'] = '0';
        $validated['orders_count'] = '0';
        $validated['sales_range'] = 'Rp 0';
        $validated['is_golden_tick'] = $request->has('is_golden_tick');
        $validated['is_active'] = true;

        \App\Models\Affiliate::create($validated);

        return redirect()->route('tenant.affiliates.index')->with('success', 'Mitra affiliate berhasil ditambahkan.');
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
            'handle' => 'nullable|string|max:255',
            'whatsapp' => 'nullable|string|max:30',
            'referral_code' => 'nullable|string|max:50|unique:affiliates,referral_code,' . $affiliate->id,
            'commission_rate' => 'required|numeric|min:0|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'platform' => 'nullable|string|max:50',
            'followers_count' => 'nullable|string|max:255',
            'categories' => 'nullable|array',
            'is_golden_tick' => 'nullable|boolean',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('affiliates/avatars', 'public');
            $validated['avatar_url'] = '/storage/' . $path;
        }

        if (!empty($validated['referral_code'])) {
            $validated['referral_code'] = strtoupper(preg_replace('/[^A-Za-z0-9_-]/', '', $validated['referral_code']));
        }

        $validated['is_golden_tick'] = $request->has('is_golden_tick');

        $affiliate->update($validated);

        return redirect()->route('tenant.affiliates.index')->with('success', 'Data affiliate berhasil diperbarui.');
    }

    public function destroy(\App\Models\Affiliate $affiliate)
    {
        $affiliate->delete();
        return redirect()->route('tenant.affiliates.index')->with('success', 'Affiliate berhasil dihapus.');
    }
}
