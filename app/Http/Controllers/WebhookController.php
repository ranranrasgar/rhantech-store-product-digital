<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPaidMail;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function midtrans(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $order = Order::where('invoice_number', $request->order_id)->first();
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'paid']);
                    
                    // Send Email
                    try {
                        Mail::to($order->customer_email)->send(new OrderPaidMail($order));
                    } catch (\Exception $e) {
                        Log::error("Failed to send order email: " . $e->getMessage());
                    }
                }
            } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny' || $request->transaction_status == 'expire') {
                $order = Order::where('invoice_number', $request->order_id)->first();
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'failed']);
                }
            }
        }

        return response()->json(['message' => 'success']);
    }
}
