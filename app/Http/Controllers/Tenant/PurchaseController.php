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
        // Karena sistem ini tidak pakai user_id di order, melainkan email
        $query = Order::where('customer_email', $user->email)
            ->with(['product', 'orderItems.product.images', 'reviews']);

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

        return view('tenant.purchases.index', compact('purchases', 'tab'));
    }
}
