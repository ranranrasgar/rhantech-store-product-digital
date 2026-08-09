<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function index()
    {
        $payouts = \App\Models\PayoutRequest::with('store')->latest()->paginate(20);
        return view('admin.payouts.index', compact('payouts'));
    }

    public function update(Request $request, \App\Models\PayoutRequest $payout)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'notes' => 'nullable|string'
        ]);

        $payout->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        if ($request->status === 'rejected') {
            // Refund the balance
            $payout->store->increment('balance', $payout->amount);
        }

        return back()->with('success', 'Payout request updated successfully.');
    }
}
