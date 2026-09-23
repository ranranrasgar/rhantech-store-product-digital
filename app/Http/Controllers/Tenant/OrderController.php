<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) return redirect()->route('tenant.store.index');

        // Fetch products for the dropdown filter (selective columns)
        $products = Product::where('store_id', $store->id)->select(['id', 'name'])->orderBy('name')->get();

        // Optimized: Find all order IDs belonging to this store via items or direct product linkage
        $orderIdsFromItems = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.store_id', $store->id)
            ->pluck('order_items.order_id');

        $orderIdsFromProducts = \Illuminate\Support\Facades\DB::table('orders')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->where('products.store_id', $store->id)
            ->pluck('orders.id');

        $allOrderIds = $orderIdsFromItems->merge($orderIdsFromProducts)->unique();

        // Build the base query using whereIn for massive performance boost over whereHas
        $query = Order::whereIn('id', $allOrderIds)
            ->with(['product:id,store_id,name', 'orderItems.product:id,store_id,name', 'orderItems.product.images']);

        // 1. Tab filter
        $tab = $request->input('tab', 'all');
        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'completed') {
            $query->whereIn('status', ['paid', 'downloaded']);
        } elseif ($tab === 'cancelled') {
            $query->where('status', 'failed');
        }

        // 2. Product filter
        if ($request->filled('product_id')) {
            $productId = $request->input('product_id');
            $query->where(function ($q) use ($productId) {
                $q->where('product_id', $productId)
                    ->orWhereHas('orderItems', function ($sub) use ($productId) {
                        $sub->where('product_id', $productId);
                    });
            });
        }

        // 3. Search filter
        if ($request->filled('search_query')) {
            $searchType = $request->input('search_type', 'invoice');
            $searchQuery = $request->input('search_query');

            if ($searchType === 'invoice') {
                $query->where('invoice_number', 'like', '%' . $searchQuery . '%');
            } elseif ($searchType === 'customer') {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('customer_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('customer_email', 'like', '%' . $searchQuery . '%');
                });
            }
        }

        // Execute query
        $orders = $query->latest()->paginate(20)->withQueryString();

        // Calculate counts for tabs in ONE single query instead of 4 separate queries
        $countsQuery = Order::whereIn('id', $allOrderIds)
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

        return view('tenant.orders.index', compact('orders', 'products', 'tab', 'store', 'counts'));
    }

    private function checkOrderAccess(Order $order)
    {
        $store = Auth::user()->store;
        if (!$store) abort(403);

        $belongsToStore = false;
        if ($order->product && $order->product->store_id == $store->id) {
            $belongsToStore = true;
        } elseif ($order->orderItems()->whereHas('product', function($q) use($store) { $q->where('store_id', $store->id); })->exists()) {
            $belongsToStore = true;
        }

        if (!$belongsToStore) {
            abort(403, 'Akses ditolak.');
        }
    }


    public function markPaid(Order $order)
    {
        $this->checkOrderAccess($order);

        if ($order->status !== 'pending') {
            return back()->with('error', 'Status pesanan tidak dapat diubah.');
        }

        $order->update(['status' => 'paid']);

        // Tambahkan saldo ke toko bila item milik toko
        $order->loadMissing('orderItems.product.store');
        foreach ($order->orderItems as $item) {
            if ($item->product && $item->product->store_id) {
                $itemTotal = $item->price * $item->quantity;
                $item->product->store->increment('balance', $itemTotal);
            }
        }

        // Send email
        try {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPaidMail($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send OrderPaidMail: ' . $e->getMessage());
        }

        // Kirim notifikasi push FCM & lonceng (Pembeli, Toko, Platform Admin)
        try {
            app(\App\Services\FirebaseService::class)->notifyOrderStatusChanged($order, 'paid');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("FCM markPaid notification failed: " . $e->getMessage());
        }

        return back()->with('success', 'Status pesanan diubah menjadi Lunas dan email produk telah dikirim.')
            ->with('fcm_notification', [
                'title' => "🎉 Pembayaran Berhasil: #{$order->invoice_number}",
                'body' => "Pesanan senilai Rp " . number_format($order->amount, 0, ',', '.') . " telah lunas. Notifikasi & email terkirim ke pembeli."
            ]);
    }

    public function resendEmail(Order $order)
    {
        $this->checkOrderAccess($order);

        if (!in_array($order->status, ['paid', 'downloaded'])) {
            return back()->with('error', 'Hanya pesanan lunas yang dapat dikirim ulang link produknya.');
        }

        // Pastikan download_token ada agar link unduhan tidak error
        if (empty($order->download_token)) {
            $order->update(['download_token' => \Illuminate\Support\Str::random(60)]);
        }

        // Pastikan relasi produk dan item ter-load
        $order->loadMissing(['orderItems.product', 'product']);

        $mailSent = true;
        $mailError = null;
        try {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPaidMail($order));
        } catch (\Throwable $e) {
            $mailSent = false;
            $mailError = $e->getMessage();
            \Illuminate\Support\Facades\Log::error('Failed to send OrderPaidMail: ' . $mailError);
        }

        // Kirim Push Notification FCM & Notifikasi Lonceng ke Pembeli
        try {
            app(\App\Services\FirebaseService::class)->notifyOrderLinkResent($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("FCM notifyOrderLinkResent failed: " . $e->getMessage());
        }

        $firstItem = $order->orderItems->first();
        $productName = $firstItem->product->name ?? ($order->product->name ?? 'Produk Digital');
        $msg = $mailSent 
            ? "Link produk '{$productName}' berhasil dikirim ke {$order->customer_email} dan push notifikasi terkirim!" 
            : "Push notifikasi link produk berhasil dikirim (Catatan email: {$mailError})";

        return back()
            ->with($mailSent ? 'success' : 'warning', $msg)
            ->with('fcm_notification', [
                'title' => "📥 Link Produk Terkirim: #{$order->invoice_number}",
                'body' => "Tautan akses produk telah dikirim ke {$order->customer_email} beserta push notifikasi ke pembeli."
            ]);
    }
}
