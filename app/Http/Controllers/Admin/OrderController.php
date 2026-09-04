<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product', 'orderItems.product.store'])->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
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
