<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PayoutRequest;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        $payouts = PayoutRequest::where('store_id', $store->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tenant.balance.index', compact('store', 'payouts'));
    }
}
