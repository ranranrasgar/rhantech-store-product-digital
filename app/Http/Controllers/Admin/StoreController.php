<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = \App\Models\Store::with('user')->latest()->paginate(20);
        return view('admin.stores.index', compact('stores'));
    }

    public function update(Request $request, \App\Models\Store $store)
    {
        $request->validate([
            'is_pro' => 'boolean',
            'pro_expires_at' => 'nullable|date',
            'pro_plan' => 'nullable|string',
            'custom_payout_fee_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $store->update($request->only([
            'is_pro', 'pro_expires_at', 'pro_plan', 'custom_payout_fee_percentage'
        ]));

        return back()->with('success', 'Status PRO toko berhasil diperbarui.');
    }
}
