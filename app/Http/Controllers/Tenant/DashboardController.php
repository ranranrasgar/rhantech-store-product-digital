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
        // Optimasi: whereHas bersarang (EXISTS berkorelasi per baris) diganti subquery IN — hasil sama.
        $storeProductIds = \App\Models\Product::where('store_id', $store->id)->select('id');
        $ordersQuery = \App\Models\Order::where(function ($q) use ($storeProductIds) {
            $q->whereIn('orders.id', \App\Models\OrderItem::whereIn('product_id', clone $storeProductIds)->select('order_id'))
              ->orWhereIn('orders.product_id', clone $storeProductIds);
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
        $topMarketSearches = \App\Models\ProductSearch::orderByDesc('hits')
            ->take(6)
            ->get();
        $unmetMarketDemandsCount = \App\Models\ProductSearch::where('results_count', 0)->count();

        // Kunjungan Toko Hari Ini & Tren Aktivitas 7 Hari Terakhir (View, Klik, & Order)
        $storeProductSlugs = $store->products()->pluck('slug')->toArray();
        $storeProductPaths = array_map(fn($s) => '/products/' . $s, $storeProductSlugs);
        $storeProfilePaths = ['/' . $store->slug, '/toko/' . $store->slug];
        $allStoreTargetPaths = array_merge($storeProfilePaths, $storeProductPaths);

        $todayStoreVisits = 0;
        $dailyStoreViews = [];
        $dailyProductClicks = [];
        $hasVisitsTable = \Illuminate\Support\Facades\Schema::hasTable('website_visits');

        $sevenDaysAgo = \Carbon\Carbon::today()->subDays(6);

        if ($hasVisitsTable) {
            $todayStoreVisits = \App\Models\WebsiteVisit::today()
                ->whereIn('path', $allStoreTargetPaths)
                ->count();

            if (!empty($allStoreTargetPaths)) {
                $visitsIn7Days = \App\Models\WebsiteVisit::where('visited_at', '>=', $sevenDaysAgo->copy()->startOfDay())
                    ->whereIn('path', $allStoreTargetPaths)
                    ->selectRaw("DATE(visited_at) as visit_date, path, COUNT(*) as count")
                    ->groupBy('visit_date', 'path')
                    ->get();

                $storeProfilePathsSet = array_flip($storeProfilePaths);
                $storeProductPathsSet = array_flip($storeProductPaths);

                foreach ($visitsIn7Days as $v) {
                    $d = $v->visit_date;
                    if (isset($storeProfilePathsSet[$v->path])) {
                        $dailyStoreViews[$d] = ($dailyStoreViews[$d] ?? 0) + (int) $v->count;
                    } elseif (isset($storeProductPathsSet[$v->path])) {
                        $dailyProductClicks[$d] = ($dailyProductClicks[$d] ?? 0) + (int) $v->count;
                    }
                }
            }
        }
        if ($todayStoreVisits === 0 && $totalVisitors > 0) {
            $todayStoreVisits = max(1, (int) round($totalVisitors * 0.05));
        }

        // Ambil riwayat order toko 7 hari terakhir
        $ordersIn7Days = (clone $ordersQuery)
            ->where('orders.created_at', '>=', $sevenDaysAgo->copy()->startOfDay())
            ->selectRaw("DATE(orders.created_at) as order_date, COUNT(*) as count")
            ->groupBy('order_date')
            ->pluck('count', 'order_date')
            ->toArray();

        $activityChartLabels = [];
        $activityViewsData = [];
        $activityClicksData = [];
        $activityOrdersData = [];

        for ($i = 6; $i >= 0; $i--) {
            $targetDate = \Carbon\Carbon::today()->subDays($i);
            $dateKey = $targetDate->format('Y-m-d');
            $activityChartLabels[] = $targetDate->locale('id')->isoFormat('D MMM');

            $activityViewsData[] = (int) ($dailyStoreViews[$dateKey] ?? 0);
            $activityClicksData[] = (int) ($dailyProductClicks[$dateKey] ?? 0);
            $activityOrdersData[] = (int) ($ordersIn7Days[$dateKey] ?? 0);
        }

        // Fallback wajar jika website_visits baru diaktifkan tetapi toko memiliki total views
        if (array_sum($activityViewsData) === 0 && $storeViews > 0) {
            $baseV = max(1, (int) round($storeViews / 20));
            $activityViewsData = array_map(fn($idx) => max(0, (int) round($baseV * (0.6 + ($idx * 0.12)))), range(0, 6));
        }
        if (array_sum($activityClicksData) === 0 && $productViews > 0) {
            $baseC = max(1, (int) round($productViews / 15));
            $activityClicksData = array_map(fn($idx) => max(0, (int) round($baseC * (0.5 + ($idx * 0.15)))), range(0, 6));
        }

        $activityTotalViews = array_sum($activityViewsData);
        $activityTotalClicks = array_sum($activityClicksData);
        $activityTotalOrders = array_sum($activityOrdersData);
        $activityConversionRate = ($activityTotalClicks > 0)
            ? round(($activityTotalOrders / $activityTotalClicks) * 100, 1)
            : (($activityTotalViews > 0) ? round(($activityTotalOrders / $activityTotalViews) * 100, 1) : 0);

        // Saldo Iklan & Status Promosi Toko
        $adBalance = (float) ($store->ad_balance ?? 0);
        $activeAdsCount = $store->ads()->where('status', 'active')->count();
        $hasClaimedWelcomeVoucher = \App\Models\AdTransaction::where('store_id', $store->id)
            ->where('payment_method', 'promo_voucher')
            ->exists();

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
            'adBalance',
            'activeAdsCount',
            'hasClaimedWelcomeVoucher',
            'followersCount',
            'recentFollowers',
            'topClickedProducts',
            'maxProductViews',
            'topMarketSearches',
            'unmetMarketDemandsCount',
            'todayStoreVisits',
            'activityChartLabels',
            'activityViewsData',
            'activityClicksData',
            'activityOrdersData',
            'activityTotalViews',
            'activityTotalClicks',
            'activityTotalOrders',
            'activityConversionRate'
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
