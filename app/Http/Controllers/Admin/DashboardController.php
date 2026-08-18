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
            'recentMessages'
        ));
    }
}
