<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PayoutRequest;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = auth()->user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        $payouts = $store->payoutRequests()->latest()->get();
        return view('tenant.payouts.index', compact('store', 'payouts'));
    }

    public function store(Request $request)
    {
        $store = auth()->user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        $request->validate([
            'amount' => 'required|numeric|min:10000',
        ]);

        if ($request->amount > $store->balance) {
            return back()->with('error', 'Insufficient balance.');
        }

        if (empty($store->bank_account_info)) {
            return back()->with('error', 'Please update your bank account info in the Store Profile before requesting a payout.');
        }

        // Deduct balance
        $store->decrement('balance', $request->amount);

        // Create payout request
        $store->payoutRequests()->create([
            'amount' => $request->amount,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Payout requested successfully. We will process it shortly.');
    }
}
