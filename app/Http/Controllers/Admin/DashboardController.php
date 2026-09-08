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
        // 1. Basic counts for Digital Marketplace
        $totalOrders = Order::query()->whereIn('status', ['paid', 'downloaded'])->count('*');
        $totalRevenue = Order::query()->whereIn('status', ['paid', 'downloaded'])->sum('amount');
        $totalStores = Store::query()->count('*');
        $totalProducts = Product::query()->where('is_active', true)->count('*');
        $newMessages = ContactMessage::query()->where('read_at', null)->count('*');

        // 2. Order status breakdown
        $orderPending = Order::query()->where('status', 'pending')->count('*');
        $orderSuccess = Order::query()->whereIn('status', ['paid', 'downloaded'])->count('*');
        $orderFailed = Order::query()->where('status', 'failed')->count('*');
        $allOrdersCount = $orderPending + $orderSuccess + $orderFailed;

        // Percentages
        $projTotal = max(1, $allOrdersCount);
        $percentSuccess = $allOrdersCount > 0 ? round(($orderSuccess / $projTotal) * 100) : 0;
        $percentPending = $allOrdersCount > 0 ? round(($orderPending / $projTotal) * 100) : 0;
        $percentFailed = $allOrdersCount > 0 ? (100 - $percentSuccess - $percentPending) : 0;

        // 3. Monthly Revenue (Past 6 Months)
        $monthlyRevenue = [];
        $monthLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $monthName = $date->format('M');
            $monthLabels[] = $monthName;

            $rev = Order::query()->whereIn('status', ['paid', 'downloaded'])
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $monthlyRevenue[] = (float) $rev;
        }

        // 4. Recent Transactions (Orders)
        // Menampilkan 6 transaksi terbaru untuk tabel
        $recentTransactions = Order::query()->with('orderItems.product.store')->latest()->take(6)->get();

        // 5. Recent Activity Feed (Recent messages)
        $recentMessages = ContactMessage::query()->latest()->take(3)->get();

        // 6. Geographic Map Data (Stores & Customers)
        $mapData = $this->getMapMarkers();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
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

        // 1. Toko / Merchant Stores
        $stores = Store::with(['user', 'products'])->get();
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
                'products_count' => $s->products->count(),
                'maps_url' => $s->maps_location ?: "https://www.google.com/maps?q={$lat},{$lng}",
                'store_url' => $s->slug ? route('store.show', $s->slug) : null,
                'logo_url' => $s->logo ? asset('storage/' . $s->logo) : null,
            ];
        }

        // 2. Customers / Pelanggan from Orders
        $orders = Order::all();
        $customerGroups = [];

        foreach ($orders as $o) {
            $email = strtolower(trim($o->customer_email));
            if (!isset($customerGroups[$email])) {
                $customerGroups[$email] = [
                    'name' => $o->customer_name,
                    'email' => $o->customer_email,
                    'phone' => $o->customer_phone,
                    'orders_count' => 0,
                    'total_spent' => 0,
                    'last_order' => $o->created_at ? $o->created_at->format('d M Y') : '-',
                ];
            }
            $customerGroups[$email]['orders_count']++;
            $customerGroups[$email]['total_spent'] += (float) $o->amount;
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
