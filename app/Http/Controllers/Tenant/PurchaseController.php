<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Cari semua pesanan di mana customer_email sama dengan email user
        $baseQuery = Order::where('customer_email', $user->email);

        // Counts untuk badge tab (Optimized to 1 query)
        $countsQuery = (clone $baseQuery)
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when status in ("paid", "downloaded") then 1 else 0 end) as completed')
            ->selectRaw('sum(case when status = "pending" then 1 else 0 end) as pending')
            ->selectRaw('sum(case when status = "failed" then 1 else 0 end) as cancelled')
            ->first();

        $counts = [
            'all' => $countsQuery->total ?? 0,
            'completed' => $countsQuery->completed ?? 0,
            'pending' => $countsQuery->pending ?? 0,
            'cancelled' => $countsQuery->cancelled ?? 0,
        ];

        $query = (clone $baseQuery)
            ->with(['product.store', 'orderItems.product.store', 'orderItems.product.images', 'reviews']);

        // Filter pencarian invoice atau nama produk
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('orderItems.product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('product', function($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Tab filter (mirip dengan halaman riwayat penjualan)
        $tab = $request->input('tab', 'all');
        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'completed') {
            $query->whereIn('status', ['paid', 'downloaded']);
        } elseif ($tab === 'cancelled') {
            $query->where('status', 'failed');
        }

        $purchases = $query->latest()->paginate(10)->withQueryString();

        return view('tenant.purchases.index', compact('purchases', 'tab', 'counts'));
    }
}
