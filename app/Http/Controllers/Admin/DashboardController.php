<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Service;
use App\Models\Client;
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
        // 1. Basic counts
        $totalProjects = Project::query()->count('*');
        $activeServices = Service::query()->where('is_active', true)->count('*');
        $newMessages = ContactMessage::query()->where('read_at', null)->count('*');
        $totalClients = Client::query()->count('*');
        $totalOrders = Order::query()->whereIn('status', ['paid', 'downloaded'])->count('*');
        $totalRevenue = Order::query()->whereIn('status', ['paid', 'downloaded'])->sum('amount');
        $totalStores = Store::query()->count('*');
        $totalProducts = Product::query()->count('*');

        // 2. Project status breakdown
        $projectInProgress = Project::query()->where('status', 'in_progress')->count('*');
        $projectCompleted = Project::query()->where('status', 'completed')->count('*');
        $projectOnHold = Project::query()->where('status', 'on_hold')->count('*');

        // Fallback percentages if no status or 0 projects
        $projTotal = max(1, $totalProjects);
        $percentInProgress = $totalProjects > 0 ? round(($projectInProgress / $projTotal) * 100) : 50;
        $percentCompleted = $totalProjects > 0 ? round(($projectCompleted / $projTotal) * 100) : 35;
        $percentOnHold = $totalProjects > 0 ? (100 - $percentInProgress - $percentCompleted) : 15;

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

        // 4. Top Services or Digital Products
        $topServices = Service::query()->where('is_active', true)->take(5)->get();

        // 5. Recent Activity Feed (Recent messages & latest orders)
        $recentMessages = ContactMessage::query()->latest()->take(3)->get();
        $recentOrders = Order::query()->with('product')->latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'totalProjects',
            'activeServices',
            'newMessages',
            'totalClients',
            'totalOrders',
            'totalRevenue',
            'totalStores',
            'totalProducts',
            'projectInProgress',
            'projectCompleted',
            'projectOnHold',
            'percentInProgress',
            'percentCompleted',
            'percentOnHold',
            'monthLabels',
            'monthlyRevenue',
            'topServices',
            'recentMessages',
            'recentOrders'
        ));
    }
}
