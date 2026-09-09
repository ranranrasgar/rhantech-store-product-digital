<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1 & 2. Consolidated basic counts & order breakdown in ONE single aggregated query
        $orderStats = Order::query()
            ->selectRaw("
                SUM(CASE WHEN status IN ('paid', 'downloaded') THEN 1 ELSE 0 END) as success_count,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN status IN ('paid', 'downloaded') THEN amount ELSE 0 END) as total_revenue
            ")
            ->first();

        $orderSuccess = (int) ($orderStats->success_count ?? 0);
        $orderPending = (int) ($orderStats->pending_count ?? 0);
        $orderFailed = (int) ($orderStats->failed_count ?? 0);
        $totalOrders = $orderSuccess;
        $totalRevenue = (float) ($orderStats->total_revenue ?? 0);
        $allOrdersCount = $orderPending + $orderSuccess + $orderFailed;

        $totalStores = Store::query()->count();
        $totalProducts = Product::query()->where('is_active', true)->count();
        $newMessages = ContactMessage::query()->where('read_at', null)->count();

        // Percentages
        $projTotal = max(1, $allOrdersCount);
        $percentSuccess = $allOrdersCount > 0 ? round(($orderSuccess / $projTotal) * 100) : 0;
        $percentPending = $allOrdersCount > 0 ? round(($orderPending / $projTotal) * 100) : 0;
        $percentFailed = $allOrdersCount > 0 ? (100 - $percentSuccess - $percentPending) : 0;

        // 3. Monthly Revenue (Past 6 Months) consolidated into ONE single query
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlyAggregated = Order::query()
            ->whereIn('status', ['paid', 'downloaded'])
            ->where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as ym, SUM(amount) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $monthlyRevenue = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthLabels[] = $date->format('M');
            $monthlyRevenue[] = (float) ($monthlyAggregated[$monthKey] ?? 0);
        }

        // 3b. Daily Revenue for Current Month (Bulan Berjalan)
        $daysInCurrentMonth = now()->daysInMonth;
        $currentMonthName = now()->locale('id')->translatedFormat('F Y');
        $currentMonthOrders = Order::query()
            ->whereIn('status', ['paid', 'downloaded'])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('DAY(created_at) as day, SUM(amount) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $dailyRevenue = [];
        $dailyLabels = [];
        for ($d = 1; $d <= $daysInCurrentMonth; $d++) {
            $dailyLabels[] = (string) $d;
            $dailyRevenue[] = (float) ($currentMonthOrders[$d] ?? 0);
        }

        // 4. Recent Transactions (Orders)
        // Menampilkan 6 transaksi terbaru untuk tabel dengan relasi yang dipilih
        $recentTransactions = Order::query()->with('orderItems.product.store')->latest()->take(6)->get();

        // 5. Recent Activity Feed (Recent messages)
        $recentMessages = ContactMessage::query()->latest()->take(3)->get();

        // 5b. Metrik Iklan & Saldo Tenant
        $totalAdRevenue = \App\Models\AdTransaction::where('type', 'credit')
            ->where('status', 'completed')
            ->where('payment_method', '!=', 'promo_voucher')
            ->sum('total_amount');
        $activeAdsCount = \App\Models\SellerAd::where('status', 'active')->count();
        $totalAdBalance = Store::sum('ad_balance');

        // 6. Geographic Map Data (Stores & Customers)
        $mapData = $this->getMapMarkers();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalAdRevenue',
            'activeAdsCount',
            'totalAdBalance',
            'totalStores',
            'totalProducts',
            'newMessages',
            'orderPending',
            'orderSuccess',
            'orderFailed',
            'allOrdersCount',
            'percentSuccess',
            'percentPending',
            'percentFailed',
            'monthLabels',
            'monthlyRevenue',
            'dailyLabels',
            'dailyRevenue',
            'currentMonthName',
            'recentTransactions',
            'recentMessages',
            'mapData'
        ));
    }

    /**
     * Parse and compile geographical markers for stores and customers
     */
    private function getMapMarkers(): array
    {
        $cityCoords = [
            'bandung' => ['lat' => -6.917464, 'lng' => 107.619123, 'city' => 'Bandung, Jawa Barat'],
            'garut' => ['lat' => -7.227414, 'lng' => 107.908699, 'city' => 'Garut, Jawa Barat'],
            'tasikmalaya' => ['lat' => -7.327420, 'lng' => 108.220718, 'city' => 'Tasikmalaya, Jawa Barat'],
            'pekanbaru' => ['lat' => 0.507068, 'lng' => 101.447779, 'city' => 'Pekanbaru, Riau'],
            'riau' => ['lat' => 0.507068, 'lng' => 101.447779, 'city' => 'Pekanbaru, Riau'],
            'solo' => ['lat' => -7.566600, 'lng' => 110.825600, 'city' => 'Surakarta (Solo), Jawa Tengah'],
            'surakarta' => ['lat' => -7.566600, 'lng' => 110.825600, 'city' => 'Surakarta (Solo), Jawa Tengah'],
            'tegal' => ['lat' => -6.879704, 'lng' => 109.125595, 'city' => 'Tegal, Jawa Tengah'],
            'jakarta' => ['lat' => -6.208763, 'lng' => 106.845599, 'city' => 'DKI Jakarta'],
            'surabaya' => ['lat' => -7.257472, 'lng' => 112.752090, 'city' => 'Surabaya, Jawa Timur'],
            'lampung' => ['lat' => -5.429200, 'lng' => 105.262500, 'city' => 'Bandar Lampung, Lampung'],
            'yogyakarta' => ['lat' => -7.795580, 'lng' => 110.369490, 'city' => 'DI Yogyakarta'],
            'jogja' => ['lat' => -7.795580, 'lng' => 110.369490, 'city' => 'DI Yogyakarta'],
            'semarang' => ['lat' => -6.966667, 'lng' => 110.416664, 'city' => 'Semarang, Jawa Tengah'],
        ];

        // 1. Toko / Merchant Stores (Optimasi: selective columns & withCount products)
        $stores = Store::with(['user:id,name,email'])
            ->withCount(['products' => function($q) {
                $q->published();
            }])
            ->get(['id', 'user_id', 'name', 'slug', 'address', 'maps_location', 'logo']);
        $storeMarkers = [];

        foreach ($stores as $s) {
            $lat = null;
            $lng = null;
            $locationLabel = $s->address ?: 'Indonesia';

            if ($s->maps_location) {
                if (preg_match('/q=(-?\d+\.?\d*),\s*(-?\d+\.?\d*)/', $s->maps_location, $m)) {
                    $lat = (float) $m[1];
                    $lng = (float) $m[2];
                } elseif (preg_match('/query=([^&]+)/', $s->maps_location, $m)) {
                    $q = strtolower(urldecode($m[1]));
                    foreach ($cityCoords as $cityKey => $coords) {
                        if (str_contains($q, $cityKey)) {
                            $lat = $coords['lat'];
                            $lng = $coords['lng'];
                            $locationLabel = $coords['city'];
                            break;
                        }
                    }
                }
            }

            if (!$lat && $s->address) {
                $addr = strtolower($s->address);
                foreach ($cityCoords as $cityKey => $coords) {
                    if (str_contains($addr, $cityKey)) {
                        $lat = $coords['lat'];
                        $lng = $coords['lng'];
                        $locationLabel = $coords['city'];
                        break;
                    }
                }
            }

            if (!$lat) {
                $seed = crc32($s->slug ?? $s->name);
                $lat = -6.917464 + (($seed % 100) / 1000);
                $lng = 107.619123 + (($seed % 80) / 1000);
                $locationLabel = 'Bandung, Jawa Barat';
            }

            $storeMarkers[] = [
                'id' => 'store-' . $s->id,
                'type' => 'store',
                'name' => $s->name,
                'slug' => $s->slug,
                'owner' => $s->user?->name ?? 'Mitra Toko',
                'email' => $s->user?->email,
                'address' => $locationLabel,
                'lat' => $lat,
                'lng' => $lng,
                'products_count' => (int) ($s->products_count ?? 0),
                'maps_url' => $s->maps_location ?: "https://www.google.com/maps?q={$lat},{$lng}",
                'store_url' => $s->slug ? route('store.show', $s->slug) : null,
                'logo_url' => $s->logo ? asset('storage/' . $s->logo) : null,
            ];
        }

        // 2. Customers / Pelanggan from Orders (Optimasi: agregasi langsung via database alih-alih Order::all())
        $customerGroupsRaw = Order::query()
            ->whereNotNull('customer_email')
            ->selectRaw('customer_email, MAX(customer_name) as customer_name, MAX(customer_phone) as customer_phone, COUNT(*) as orders_count, SUM(amount) as total_spent, MAX(created_at) as last_order_at')
            ->groupBy('customer_email')
            ->get();

        $customerGroups = [];
        foreach ($customerGroupsRaw as $row) {
            $email = strtolower(trim($row->customer_email));
            $customerGroups[$email] = [
                'name' => $row->customer_name ?: 'Pelanggan',
                'email' => $row->customer_email,
                'phone' => $row->customer_phone ?: '-',
                'orders_count' => (int) $row->orders_count,
                'total_spent' => (float) $row->total_spent,
                'last_order' => $row->last_order_at ? \Carbon\Carbon::parse($row->last_order_at)->format('d M Y') : '-',
            ];
        }

        $customerLocations = [
            'admin@rhantech.com' => ['lat' => -6.928161, 'lng' => 107.669617, 'city' => 'Bandung, Jawa Barat'],
            'ranran250880@gmail.com' => ['lat' => -7.227414, 'lng' => 107.908699, 'city' => 'Garut, Jawa Barat'],
            'ingathutangcairacan@gmail.com' => ['lat' => -7.218500, 'lng' => 107.899500, 'city' => 'Garut Kota, Jawa Barat'],
            'egideskha619@gmail.com' => ['lat' => -7.327420, 'lng' => 108.220718, 'city' => 'Tasikmalaya, Jawa Barat'],
            'dimassyahreza08@gmail.com' => ['lat' => -6.208763, 'lng' => 106.845599, 'city' => 'Jakarta Selatan, DKI Jakarta'],
            'alfieakbarriyadi@gmail.com' => ['lat' => -5.429200, 'lng' => 105.262500, 'city' => 'Bandar Lampung, Lampung'],
            'suwitoskillslablaboran@gmail.com' => ['lat' => -6.903890, 'lng' => 107.618610, 'city' => 'Bandung Wetan, Jawa Barat'],
            'answeddingorganizer@gmail.com' => ['lat' => -6.938500, 'lng' => 107.645000, 'city' => 'Buahbatu, Bandung'],
            'lilohom@mailinator.com' => ['lat' => -6.175392, 'lng' => 106.827153, 'city' => 'Jakarta Pusat, DKI Jakarta'],
        ];

        $customerMarkers = [];
        foreach ($customerGroups as $email => $c) {
            if (isset($customerLocations[$email])) {
                $lat = $customerLocations[$email]['lat'];
                $lng = $customerLocations[$email]['lng'];
                $city = $customerLocations[$email]['city'];
            } else {
                $hash = crc32($email);
                $lat = -6.917 + (($hash % 80) / 100);
                $lng = 107.619 + (($hash % 100) / 80);
                $city = 'Jawa Barat, Indonesia';
            }

            $customerMarkers[] = [
                'id' => 'cust-' . md5($email),
                'type' => 'customer',
                'name' => $c['name'],
                'email' => $c['email'],
                'phone' => $c['phone'],
                'address' => $city,
                'lat' => $lat,
                'lng' => $lng,
                'orders_count' => $c['orders_count'],
                'total_spent' => $c['total_spent'],
                'last_order' => $c['last_order'],
                'maps_url' => "https://www.google.com/maps?q={$lat},{$lng}",
            ];
        }

        return [
            'stores' => $storeMarkers,
            'customers' => $customerMarkers,
            'all' => array_merge($storeMarkers, $customerMarkers),
            'total_stores' => count($storeMarkers),
            'total_customers' => count($customerMarkers),
            'total_points' => count($storeMarkers) + count($customerMarkers),
        ];
    }
}
