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

        // Counts untuk badge tab
        $counts = [
            'all' => (clone $baseQuery)->count(),
            'completed' => (clone $baseQuery)->whereIn('status', ['paid', 'downloaded'])->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'failed')->count(),
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
