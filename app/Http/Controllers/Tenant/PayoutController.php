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
    public function index(Request $request)
    {
        $store = auth()->user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        $tab = $request->get('tab', 'semua');

        // 1. Pesanan produk sendiri yang sudah lunas (Paid / Downloaded)
        $ownProductOrdersQuery = \App\Models\Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            });
        })->whereIn('status', ['paid', 'downloaded'])
          ->with(['product', 'orderItems.product']);

        $ownProductOrders = (clone $ownProductOrdersQuery)->latest()->get();
        $totalOwnRevenue = 0;
        foreach ($ownProductOrders as $o) {
            $tenantItems = $o->orderItems->filter(fn($item) => $item->product && $item->product->store_id == $store->id);
            $totalOwnRevenue += $tenantItems->isNotEmpty() ? $tenantItems->sum(fn($i) => $i->price * $i->quantity) : $o->amount;
        }

        // 2. Data Afiliasi / Showcase Toko Saya
        $myShowcaseCount = $store->showcaseProducts()->count();
        $myAffiliateMitra = \App\Models\Affiliate::where('store_id', $store->id)->get();
        $totalAffiliateClicks = $myAffiliateMitra->sum('clicks_count');
        $totalAffiliateOrders = $myAffiliateMitra->sum('orders_count');

        // 2b. Transaksi Afiliasi yang Berhasil Terjual lewat Toko ini (Showcase / Referral Link Toko)
        $affiliateSoldOrdersQuery = \App\Models\Order::where('referrer_store_id', $store->id)
            ->whereIn('status', ['paid', 'downloaded'])
            ->with(['orderItems.product.store', 'product.store']);

        $totalAffiliateCommission = (clone $affiliateSoldOrdersQuery)->sum('affiliate_commission');
        $totalAffiliateSoldOrdersCount = (clone $affiliateSoldOrdersQuery)->count();
        $pagedAffiliateSoldOrders = (clone $affiliateSoldOrdersQuery)->latest()->paginate(15, ['*'], 'affiliate_page');

        // 3. Riwayat Penarikan Dana (Payouts)
        $payoutsQuery = $store->payoutRequests()->latest();
        $payouts = (clone $payoutsQuery)->get();
        $totalWithdrawn = $payouts->where('status', 'approved')->sum('amount');
        $totalPendingPayout = $payouts->where('status', 'pending')->sum('amount');

        // 4. Data untuk Tab aktif
        $pagedPayouts = $payoutsQuery->paginate(15, ['*'], 'payout_page');
        $pagedOwnOrders = $ownProductOrdersQuery->paginate(15, ['*'], 'order_page');

        return view('tenant.payouts.index', compact(
            'store',
            'tab',
            'payouts',
            'pagedPayouts',
            'pagedOwnOrders',
            'pagedAffiliateSoldOrders',
            'totalOwnRevenue',
            'myShowcaseCount',
            'myAffiliateMitra',
            'totalAffiliateClicks',
            'totalAffiliateOrders',
            'totalAffiliateCommission',
            'totalAffiliateSoldOrdersCount',
            'totalWithdrawn',
            'totalPendingPayout'
        ));
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
