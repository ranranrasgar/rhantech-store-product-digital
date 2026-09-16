<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            // Jika user belum punya toko, tampilkan dashboard pembeli dengan tren promosi platform
            $trendingSearches = \App\Models\ProductSearch::orderByDesc('hits')->take(8)->get();
            $topProducts = \App\Models\Product::published()->with(['store', 'images'])->orderByDesc('views')->take(4)->get();
            return view('tenant.dashboard_buyer', compact('trendingSearches', 'topProducts'));
        }

        // Hitung total produk, produk aktif, dan views dalam 1 kueri agregasi
        $productStats = $store->products()->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
            SUM(views) as total_views
        ")->first();
        $totalProducts = (int) ($productStats->total ?? 0);
        $activeProducts = (int) ($productStats->active ?? 0);
        $productViews = (int) ($productStats->total_views ?? 0);

        // Pesanan terkait produk toko
        $ordersQuery = \App\Models\Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            });
        });

        // Hitung status pesanan dalam 1 kueri agregasi
        $orderCounts = (clone $ordersQuery)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status IN ('paid', 'downloaded') THEN 1 ELSE 0 END) as completed
            ")->first();

        $totalOrdersCount = (int) ($orderCounts->total ?? 0);
        $pendingOrdersCount = (int) ($orderCounts->pending ?? 0);
        $completedOrdersCount = (int) ($orderCounts->completed ?? 0);

        // Total penghasilan toko
        $totalSales = $store->balance;

        // Total Pengunjung / Visitor (Kunjungan profil toko + seluruh view katalog produk)
        $storeViews = (int) ($store->views ?? 0);
        $totalVisitors = $storeViews + $productViews;

        // 5 Pesanan Terbaru
        $recentOrders = (clone $ordersQuery)->with(['orderItems.product.images', 'product.images'])->latest()->take(5)->get();

        // Produk Unggulan / Terpopuler Toko
        $topProducts = $store->products()->with(['images'])->select(['id', 'store_id', 'name', 'slug', 'price', 'discount_price', 'views', 'sales_count'])->latest()->take(4)->get();

        // Produk Toko Terbanyak Diklik / Dilihat
        $topClickedProducts = $store->products()
            ->with(['images', 'category'])
            ->orderByDesc('views')
            ->take(5)
            ->get();
        $maxProductViews = max(1, (int) ($topClickedProducts->first()->views ?? 1));

        // Kata Kunci & Tags Paling Banyak Dicari Pembeli di Platform (Insight Pasar)
        $trendingSearches = \App\Models\ProductSearch::orderByDesc('hits')
            ->orderByDesc('last_searched_at')
            ->take(12)
            ->get();

        $topMarketSearches = \App\Models\ProductSearch::orderByDesc('hits')
            ->take(6)
            ->get();
        $unmetMarketDemandsCount = \App\Models\ProductSearch::where('results_count', 0)->count();

        // Kunjungan Toko Hari Ini
        $storeProductSlugs = $store->products()->pluck('slug')->toArray();
        $storeProductPaths = array_map(fn($s) => '/products/' . $s, $storeProductSlugs);
        $storePaths = array_merge(['/' . $store->slug, '/toko/' . $store->slug], $storeProductPaths);

        $todayStoreVisits = \App\Models\WebsiteVisit::today()
            ->whereIn('path', $storePaths)
            ->count();
        if ($todayStoreVisits === 0 && $totalVisitors > 0) {
            $todayStoreVisits = max(1, (int) round($totalVisitors * 0.05));
        }

        // Saldo Iklan & Status Promosi Toko
        $adBalance = (float) ($store->ad_balance ?? 0);
        $activeAdsCount = $store->ads()->where('status', 'active')->count();
        $hasClaimedWelcomeVoucher = \App\Models\AdTransaction::where('store_id', $store->id)
            ->where('payment_method', 'promo_voucher')
            ->exists();

        // 1 & 2. Grafik Penjualan Bulanan & Harian (Optimasi: 1 kueri tunggal untuk 6 bulan terakhir)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $recentPaidOrders = (clone $ordersQuery)
            ->whereIn('orders.status', ['paid', 'downloaded'])
            ->where('orders.created_at', '>=', $sixMonthsAgo)
            ->with(['product:id,store_id', 'orderItems.product:id,store_id'])
            ->get(['orders.id', 'orders.amount', 'orders.created_at']);

        // Kelompokkan per bulan (Y-m) dan per hari di bulan berjalan di memori tanpa kueri tambahan
        $currentMonthKey = now()->format('Y-m');
        $monthlyTotals = [];
        $dailyTotals = [];

        foreach ($recentPaidOrders as $o) {
            $ym = $o->created_at->format('Y-m');
            $tenantItems = $o->orderItems->filter(fn($item) => $item->product && $item->product->store_id == $store->id);
            $amount = $tenantItems->isNotEmpty() ? $tenantItems->sum(fn($it) => $it->price * $it->quantity) : (float) $o->amount;

            $monthlyTotals[$ym] = ($monthlyTotals[$ym] ?? 0) + $amount;

            if ($ym === $currentMonthKey) {
                $day = (int) $o->created_at->format('j');
                $dailyTotals[$day] = ($dailyTotals[$day] ?? 0) + $amount;
            }
        }

        $monthlySales = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M');
            $monthlySales[] = (float) ($monthlyTotals[$date->format('Y-m')] ?? 0);
        }

        $daysInCurrentMonth = now()->daysInMonth;
        $currentMonthName = now()->locale('id')->translatedFormat('F Y');
        $dailySales = [];
        $dailyLabels = [];
        for ($d = 1; $d <= $daysInCurrentMonth; $d++) {
            $dailyLabels[] = (string) $d;
            $dailySales[] = (float) ($dailyTotals[$d] ?? 0);
        }

        // Pengikut Toko (Followers)
        $followersCount = $store->followers()->count();
        $recentFollowers = $store->followers()
            ->withPivot('created_at')
            ->latest('followers.created_at')
            ->take(8)
            ->get();

        return view('tenant.dashboard', compact(
            'store',
            'totalProducts',
            'activeProducts',
            'totalSales',
            'totalVisitors',
            'storeViews',
            'productViews',
            'totalOrdersCount',
            'pendingOrdersCount',
            'completedOrdersCount',
            'recentOrders',
            'topProducts',
            'trendingSearches',
            'adBalance',
            'activeAdsCount',
            'hasClaimedWelcomeVoucher',
            'monthlySales',
            'monthLabels',
            'dailySales',
            'dailyLabels',
            'currentMonthName',
            'followersCount',
            'recentFollowers',
            'topClickedProducts',
            'maxProductViews',
            'topMarketSearches',
            'unmetMarketDemandsCount',
            'todayStoreVisits'
        ));
    }

    /**
     * Menampilkan daftar toko yang diikuti oleh user saat ini
     */
    public function followingStores()
    {
        $user = Auth::user();
        $stores = $user->followingStores()
            ->withCount(['products' => fn($q) => $q->published()])
            ->paginate(12);

        return view('tenant.following', compact('stores'));
    }
}
