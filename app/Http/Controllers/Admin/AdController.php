<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdTransaction;
use App\Models\SellerAd;
use App\Models\Store;
use Illuminate\Http\Request;

class AdController extends Controller
{
    /**
     * Display a listing of ad transactions and active campaigns.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'transaksi');
        $status = $request->get('status');
        $search = $request->get('search');

        // 1. Metrik Keuangan Iklan Platform
        // Total uang masuk riil (midtrans & potong saldo penjualan toko, tidak termasuk subsidi voucher gratis)
        $totalPaidAdRevenue = AdTransaction::where('type', 'credit')
            ->where('status', 'completed')
            ->where('payment_method', '!=', 'promo_voucher')
            ->sum('total_amount');

        // Total subsidi promosi platform (voucher pembukaan toko Rp500.000)
        $totalVoucherSubsidies = AdTransaction::where('payment_method', 'promo_voucher')
            ->where('status', 'completed')
            ->sum('amount');

        // Total saldo iklan beredar di akun seluruh tenant
        $totalAdBalanceInCirculation = Store::sum('ad_balance');

        // Total kampanye iklan aktif & total tayangan / klik
        $activeAdsCount = SellerAd::where('status', 'active')->count();
        $totalAdImpressions = SellerAd::sum('views_count');
        $totalAdClicks = SellerAd::sum('clicks_count');

        // 2. Query Transaksi Top-Up Saldo Iklan
        $txQuery = AdTransaction::with('store')->latest();

        if ($search) {
            $txQuery->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $txQuery->where('status', $status);
        }

        $transactions = $txQuery->paginate(15, ['*'], 'tx_page')->withQueryString();

        // 3. Query Kampanye Iklan Tenant
        $adQuery = SellerAd::with(['store', 'product.images'])->latest();

        if ($search) {
            $adQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('store', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $campaigns = $adQuery->paginate(15, ['*'], 'ad_page')->withQueryString();

        return view('admin.ads.index', compact(
            'tab',
            'status',
            'search',
            'totalPaidAdRevenue',
            'totalVoucherSubsidies',
            'totalAdBalanceInCirculation',
            'activeAdsCount',
            'totalAdImpressions',
            'totalAdClicks',
            'transactions',
            'campaigns'
        ));
    }
}
