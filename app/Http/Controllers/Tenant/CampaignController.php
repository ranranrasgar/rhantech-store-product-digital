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
        return view('tenant.campaigns.create', compact('store'));
    }

    public function store(Request $request)
    {
        $store = $this->getStore();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:discount,voucher',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'code' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'minimum_spend' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['store_id'] = $store->id;
        $validated['minimum_spend'] = $validated['minimum_spend'] ?? 0;

        Campaign::create($validated);

        return redirect()->route('tenant.campaigns.index')->with('success', 'Campaign berhasil dibuat.');
    }

    public function edit(Campaign $campaign)
    {
        $store = $this->getStore();
        
        if ($campaign->store_id !== $store->id) {
            abort(403);
        }

        return view('tenant.campaigns.edit', compact('store', 'campaign'));
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
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'code' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive,scheduled',
            'minimum_spend' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $validated['minimum_spend'] = $validated['minimum_spend'] ?? 0;
        
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
