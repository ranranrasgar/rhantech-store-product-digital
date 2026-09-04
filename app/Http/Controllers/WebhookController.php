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

        // Ambil semua gateway apps yang aktif
        $apps = \App\Models\GatewayApp::where('is_active', true)->get();
        $targetApp = null;
        $fallbackApp = null;

        // Cocokkan awalan order_id dengan prefix di database
        foreach ($apps as $app) {
            // Mendukung multi-prefix dengan pemisah koma (contoh: "PLT-, INV-, NOC-")
            $prefixes = array_map('trim', explode(',', $app->prefix));
            foreach ($prefixes as $p) {
                if ($p !== '' && $p !== '*' && Str::startsWith($orderId, $p)) {
                    $targetApp = $app;
                    break 2;
                }
            }

            // Jika prefix diset menjadi *, jadikan sebagai fallback
            if (trim($app->prefix) === '*') {
                $fallbackApp = $app;
            }
        }

        // Jika tidak ada prefix yang cocok, gunakan fallback (yang prefix-nya *)
        if (!$targetApp && $fallbackApp) {
            $targetApp = $fallbackApp;
        }

        if (!$targetApp) {
            Log::warning("Webhook received with unknown prefix, no fallback available: {$orderId}");
            return response()->json(['message' => 'ignored, no matching gateway app'], 200);
        }

        // Jika app ini ditandai is_local, proses di sini (aplikasi rhantech ini sendiri)
        if ($targetApp->is_local) {
            return $this->processLocal($request);
        }

        // Proteksi anti-loop: jika callback_url mengarah ke server ini sendiri, proses lokal
        $ownUrl = url('/api/webhooks/midtrans/callback');
        if (rtrim($targetApp->callback_url, '/') === rtrim($ownUrl, '/')) {
            Log::warning("Self-loop detected for {$targetApp->name}, processing locally instead. Set is_local=true to remove this warning.");
            return $this->processLocal($request);
        }

        // Teruskan ke callback_url aplikasi lain
        try {
            Log::info("Forwarding webhook for Order {$orderId} to App: {$targetApp->name} at {$targetApp->callback_url}");
            $response = Http::post($targetApp->callback_url, $request->all());
            Log::info("Forward response status: " . $response->status());

            return response()->json([
                'message'       => 'forwarded',
                'target'        => $targetApp->name,
                'target_status' => $response->status(),
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to forward webhook to {$targetApp->name}: " . $e->getMessage());
            return response()->json(['message' => 'forward failed'], 500);
        }
    }

    /**
     * Proses webhook secara lokal untuk aplikasi ini.
     */
    private function processLocal(Request $request)
    {
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
            } elseif (in_array($request->transaction_status, ['cancel', 'deny', 'expire'])) {
                $order = Order::where('invoice_number', $request->order_id)->first();
                if ($order && $order->status === 'pending') {
                    $order->update(['status' => 'failed']);
                }
            }
        }

        return response()->json(['message' => 'success']);
    }
}
