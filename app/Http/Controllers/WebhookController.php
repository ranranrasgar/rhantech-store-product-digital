<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPaidMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function midtrans(Request $request)
    {
        $orderId = $request->order_id;

        // Jika order_id berawalan RHN-, tidak perlu di-forward
        if ($orderId && !Str::startsWith($orderId, 'RHN-')) {
            // Ambil semua gateway apps yang aktif
            $apps = \App\Models\GatewayApp::where('is_active', true)->get();
            $targetApp = null;

            // Cocokkan awalan order_id dengan prefix di database
            $fallbackApp = null;
            foreach ($apps as $app) {


                // Mendukung multi-prefix dengan pemisah koma (contoh: "PLT-, INV-, NOC-")
                $prefixes = array_map('trim', explode(',', $app->prefix));
                foreach ($prefixes as $p) {
                    if ($p !== '' && Str::startsWith($orderId, $p)) {
                        $targetApp = $app;
                        break 2; // keluar dari 2 lapis foreach
                    }
                }

                // Jika prefix diset menjadi *, jadikan sebagai fallback
                if (trim($app->prefix) === '*') {
                    $fallbackApp = $app;
                    continue;
                }
            }

            // Jika tidak ada prefix yang cocok, gunakan fallback (yang prefix-nya *)
            if (!$targetApp && $fallbackApp) {
                $targetApp = $fallbackApp;
            }

            if ($targetApp) {
                try {
                    Log::info("Forwarding webhook for Order {$orderId} to App: {$targetApp->name} at {$targetApp->callback_url}");
                    $response = Http::post($targetApp->callback_url, $request->all());
                    Log::info("Forward response status: " . $response->status());

                    return response()->json([
                        'message' => 'forwarded',
                        'target' => $targetApp->name,
                        'target_status' => $response->status()
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to forward webhook to {$targetApp->name}: " . $e->getMessage());
                    return response()->json(['message' => 'forward failed'], 500);
                }
            } else {
                // Tidak ada prefix yang cocok, abaikan atau catat log
                Log::warning("Webhook received with unknown prefix: {$orderId}");
                return response()->json(['message' => 'ignored, unknown prefix'], 200);
            }
        }

        // Proses lokal untuk rhantech.com (awalan RHN-)
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $order = Order::with('orderItems.product.store')->where('invoice_number', $request->order_id)->first();
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'paid']);

                    // Add balance to store if product belongs to a store
                    foreach ($order->orderItems as $item) {
                        if ($item->product && $item->product->store_id) {
                            $itemTotal = $item->price * $item->quantity;
                            $item->product->store->increment('balance', $itemTotal);
                        }
                    }

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
