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

        // Build the base query: support both multi-item cart orders and single product orders
        $query = Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('products.store_id', $store->id);
            });
        })->with(['product:id,store_id,name', 'orderItems.product:id,store_id,name', 'orderItems.product.images']);

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

        return view('tenant.orders.index', compact('orders', 'products', 'tab', 'store'));
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

    public function update(Request $request, Order $order)
    {
        $this->checkOrderAccess($order);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
        ]);

        $order->update($validated);

        return back()->with('success', 'Detail pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $this->checkOrderAccess($order);

        $invoice = $order->invoice_number;
        $orderId = $order->id;

        // Bersihkan notifikasi terkait pesanan ini dari lonceng
        \App\Models\AppNotification::where('data->order_id', $orderId)
            ->orWhere('data->invoice', $invoice)
            ->orWhere('title', 'like', "%{$invoice}%")
            ->orWhere('body', 'like', "%{$invoice}%")
            ->delete();

        $order->delete();

        return back()->with('success', 'Pesanan beserta riwayat notifikasinya berhasil dihapus.');
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
            return back()->with('error', 'Hanya pesanan lunas yang dapat dikirim ulang emailnya.');
        }

        try {
            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPaidMail($order));
            return back()->with('success', 'Email produk berhasil dikirim ulang ke ' . $order->customer_email);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send OrderPaidMail: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
