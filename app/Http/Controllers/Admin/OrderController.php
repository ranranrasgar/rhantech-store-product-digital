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

    public function destroy(Order $order)
    {
        $invoice = $order->invoice_number;
        $order->orderItems()->delete();
        $order->delete();

        return back()->with('success', "Order {$invoice} berhasil dihapus.");
    }
}
