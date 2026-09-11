<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    private function getStore()
    {
        return Store::where('user_id', Auth::id())->first();
    }

    public function index(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        $campaigns = Campaign::where('store_id', $store->id)
            ->latest()
            ->paginate(15);

        return view('tenant.campaigns.index', compact('store', 'campaigns'));
    }

    public function create()
    {
        $store = $this->getStore();
        $categories = \App\Models\ProductCategory::orderBy('name', 'asc')->get();
        $products = $store->products()->where('is_active', true)->orderBy('name', 'asc')->get();

        return view('tenant.campaigns.create', compact('store', 'categories', 'products'));
    }

    public function store(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:discount,voucher',
            'applies_to' => 'required|in:all,category,product',
            'category_ids' => 'nullable|array',
            'product_ids' => 'nullable|array',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'code' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'minimum_spend' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'color' => 'nullable|string|in:orange,red,rose,pink,purple,indigo,blue,cyan,teal,green,amber,slate',
        ]);

        $validated['store_id'] = $store->id;
        $validated['minimum_spend'] = $validated['minimum_spend'] ?? 0;
        $validated['code'] = !empty($validated['code']) ? strtoupper(trim($validated['code'])) : null;

        if ($validated['applies_to'] === 'all') {
            $validated['category_ids'] = null;
            $validated['product_ids'] = null;
        } elseif ($validated['applies_to'] === 'category') {
            $validated['product_ids'] = null;
        } elseif ($validated['applies_to'] === 'product') {
            $validated['category_ids'] = null;
        }

        Campaign::create($validated);

        return redirect()->route('tenant.campaigns.index')->with('success', 'Campaign berhasil dibuat.');
    }

    public function edit(Campaign $campaign)
    {
        $store = $this->getStore();
        
        if ($campaign->store_id !== $store->id) {
            abort(403);
        }

        $categories = \App\Models\ProductCategory::orderBy('name', 'asc')->get();
        $products = $store->products()->where('is_active', true)->orderBy('name', 'asc')->get();

        return view('tenant.campaigns.edit', compact('store', 'campaign', 'categories', 'products'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $store = $this->getStore();
        
        if ($campaign->store_id !== $store->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:discount,voucher',
            'applies_to' => 'required|in:all,category,product',
            'category_ids' => 'nullable|array',
            'product_ids' => 'nullable|array',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'code' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'minimum_spend' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'color' => 'nullable|string|in:orange,red,rose,pink,purple,indigo,blue,cyan,teal,green,amber,slate',
        ]);

        $validated['minimum_spend'] = $validated['minimum_spend'] ?? 0;
        $validated['code'] = !empty($validated['code']) ? strtoupper(trim($validated['code'])) : null;

        if ($validated['applies_to'] === 'all') {
            $validated['category_ids'] = null;
            $validated['product_ids'] = null;
        } elseif ($validated['applies_to'] === 'category') {
            $validated['product_ids'] = null;
        } elseif ($validated['applies_to'] === 'product') {
            $validated['category_ids'] = null;
        }
        
        $campaign->update($validated);

        return redirect()->route('tenant.campaigns.index')->with('success', 'Campaign berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        $store = $this->getStore();
        
        if ($campaign->store_id !== $store->id) {
            abort(403);
        }

        $campaign->delete();

        return redirect()->route('tenant.campaigns.index')->with('success', 'Campaign berhasil dihapus.');
    }
}
