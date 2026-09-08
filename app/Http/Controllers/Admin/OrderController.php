<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['product.store', 'orderItems.product.store']);

        // 1. Filter Customer (Name, Email, Phone)
        if ($request->filled('customer')) {
            $customerSearch = trim($request->input('customer'));
            $query->where(function ($q) use ($customerSearch) {
                $q->where('customer_name', 'like', "%{$customerSearch}%")
                    ->orWhere('customer_email', 'like', "%{$customerSearch}%")
                    ->orWhere('customer_phone', 'like', "%{$customerSearch}%")
                    ->orWhere('invoice_number', 'like', "%{$customerSearch}%");
            });
        }

        // 2. Filter Store
        if ($request->filled('store_id')) {
            $storeId = $request->input('store_id');
            $query->where(function ($q) use ($storeId) {
                $q->whereHas('orderItems.product', function ($sub) use ($storeId) {
                    $sub->where('store_id', $storeId);
                })->orWhereHas('product', function ($sub) use ($storeId) {
                    $sub->where('store_id', $storeId);
                });
            });
        }

        // 3. Filter Product
        if ($request->filled('product_id')) {
            $productId = $request->input('product_id');
            $query->where(function ($q) use ($productId) {
                $q->whereHas('orderItems', function ($sub) use ($productId) {
                    $sub->where('product_id', $productId);
                })->orWhere('product_id', $productId);
            });
        }

        // 4. Filter Status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'completed') {
                $query->whereIn('status', ['paid', 'downloaded']);
            } elseif ($status === 'pending') {
                $query->where('status', 'pending');
            } elseif ($status === 'failed') {
                $query->where('status', 'failed');
            }
        }

        // 5. Filter Periode
        $period = $request->input('period', '');
        if ($period === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($period === '7_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($period === '28_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(28));
        } elseif ($period === '30_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30));
        } elseif ($period === 'this_month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($period === 'custom') {
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->input('start_date'));
            }
            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->input('end_date'));
            }
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        // Get stores and products list for dropdown filters
        $stores = Store::orderBy('name')->get();
        $productsQuery = Product::query();
        if ($request->filled('store_id')) {
            $productsQuery->where('store_id', $request->input('store_id'));
        }
        $products = $productsQuery->orderBy('name')->get();

        return view('admin.orders.index', compact('orders', 'stores', 'products'));
    }

    public function approve(Order $order)
    {
        if ($order->status !== 'paid' && $order->status !== 'downloaded') {
            $order->update(['status' => 'paid']);

            // Tambahkan saldo ke toko bila item milik toko
            $order->loadMissing('orderItems.product.store');
            foreach ($order->orderItems as $item) {
                if ($item->product && $item->product->store_id) {
                    $itemTotal = $item->price * $item->quantity;
                    $item->product->store->increment('balance', $itemTotal);
                }
            }

            // Tambahkan komisi affiliator ke toko perujuk jika ada
            if ($order->referrer_store_id && $order->affiliate_commission > 0) {
                $refStore = \App\Models\Store::find($order->referrer_store_id);
                if ($refStore) {
                    $refStore->increment('balance', $order->affiliate_commission);
                }
            }

            // Kirim email notifikasi pembelian ke customer
            try {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPaidMail($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send order email: " . $e->getMessage());
            }

            return back()->with('success', "Order {$order->invoice_number} berhasil disetujui (Paid) dan email terkirim.");
        }

        return back()->with('info', "Order {$order->invoice_number} sudah berstatus Paid.");
    }

    /**
     * Sinkronisasi status order langsung dari API Midtrans.
     */
    public function syncStatus(Order $order)
    {
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production');
        $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        try {
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($serverKey, '')
                ->get("{$baseUrl}/v2/{$order->invoice_number}/status");

            if ($response->successful()) {
                $data = $response->json();
                $trxStatus = $data['transaction_status'] ?? null;

                if ($trxStatus === 'settlement' || $trxStatus === 'capture') {
                    if ($order->status !== 'paid' && $order->status !== 'downloaded') {
                        $order->update(['status' => 'paid']);

                        $order->loadMissing('orderItems.product.store');
                        foreach ($order->orderItems as $item) {
                            if ($item->product && $item->product->store_id) {
                                $itemTotal = $item->price * $item->quantity;
                                $item->product->store->increment('balance', $itemTotal);
                            }
                        }

                        // Tambahkan komisi affiliator ke toko perujuk jika ada
                        if ($order->referrer_store_id && $order->affiliate_commission > 0) {
                            $refStore = \App\Models\Store::find($order->referrer_store_id);
                            if ($refStore) {
                                $refStore->increment('balance', $order->affiliate_commission);
                            }
                        }

                        try {
                            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPaidMail($order));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Failed to send order email: " . $e->getMessage());
                        }
                    }
                    return back()->with('success', "Order {$order->invoice_number} berhasil disinkronkan: Status di Midtrans adalah {$trxStatus} (Telah Lunas).");
                } elseif (in_array($trxStatus, ['cancel', 'deny', 'expire'])) {
                    $order->update(['status' => 'failed']);
                    return back()->with('info', "Order {$order->invoice_number} di Midtrans berstatus: {$trxStatus} (Gagal/Kadaluarsa).");
                } elseif ($trxStatus === 'pending') {
                    return back()->with('info', "Order {$order->invoice_number} di Midtrans masih berstatus: PENDING (Belum dibayar oleh pelanggan).");
                }

                return back()->with('info', "Status di Midtrans: " . ($trxStatus ?? 'Unknown'));
            }

            return back()->with('error', "Gagal menghubungi Midtrans (HTTP {$response->status()}): " . ($response->json()['status_message'] ?? 'Order tidak ditemukan di akun Midtrans ini.'));
        } catch (\Exception $e) {
            return back()->with('error', "Gagal sync Midtrans: " . $e->getMessage());
        }
    }

    public function destroy(Order $order)
    {
        $invoice = $order->invoice_number;
        $order->orderItems()->delete();
        $order->delete();

        return back()->with('success', "Order {$invoice} berhasil dihapus.");
    }
}
