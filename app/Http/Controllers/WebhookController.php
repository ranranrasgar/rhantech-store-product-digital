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
        $orderId = $request->input('order_id');

        Log::info("Midtrans Webhook Received", [
            'order_id'           => $orderId,
            'transaction_status' => $request->input('transaction_status'),
            'status_code'        => $request->input('status_code'),
            'gross_amount'       => $request->input('gross_amount'),
        ]);

        // Tangani jika ini adalah Test Notification dari Dashboard Midtrans (contoh: payment_notif_test_...)
        if (Str::startsWith($orderId, 'payment_notif_test_') || empty($orderId)) {
            Log::info("Handled Midtrans test notification: {$orderId}");
            return response()->json(['message' => 'Test notification received successfully'], 200);
        }

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
            // Jika tidak ada gateway app terdaftar, coba cek apakah order ada di database lokal
            $localOrderExists = Order::where('invoice_number', $orderId)->exists();
            if ($localOrderExists) {
                return $this->processLocal($request);
            }

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
        $orderId = $request->input('order_id');
        $statusCode = $request->input('status_code');
        $grossAmount = $request->input('gross_amount');
        $signatureKey = $request->input('signature_key');
        $trxStatus = $request->input('transaction_status');

        // Validasi signature key (mendukung baik raw gross_amount maupun format 2 desimal)
        $hash1 = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
        $amountFormatted = number_format((float) $grossAmount, 2, '.', '');
        $hash2 = hash("sha512", $orderId . $statusCode . $amountFormatted . $serverKey);

        $isValidSignature = ($signatureKey === $hash1 || $signatureKey === $hash2);

        if (!$isValidSignature) {
            Log::warning("Midtrans Webhook Invalid Signature for Order {$orderId}", [
                'expected' => $hash1,
                'received' => $signatureKey,
            ]);
            return response()->json(['message' => 'invalid signature'], 403);
        }

        $order = Order::with('orderItems.product.store')->where('invoice_number', $orderId)->first();

        if (!$order) {
            Log::warning("Midtrans Webhook: Order not found for invoice {$orderId}");
            return response()->json(['message' => 'order not found'], 404);
        }

        Log::info("Midtrans processing order {$orderId} with status {$trxStatus}");

        if ($trxStatus === 'capture' || $trxStatus === 'settlement') {
            if ($order->status !== 'paid' && $order->status !== 'downloaded') {
                $order->update(['status' => 'paid']);

                // Tambahkan saldo ke seller store
                foreach ($order->orderItems as $item) {
                    if ($item->product && $item->product->store_id) {
                        $itemTotal = $item->price * $item->quantity;
                        $item->product->store->increment('balance', $itemTotal);
                    }
                }

                // Kirim email notifikasi
                try {
                    Mail::to($order->customer_email)->send(new OrderPaidMail($order));
                } catch (\Exception $e) {
                    Log::error("Failed to send order email: " . $e->getMessage());
                }
            }
        } elseif (in_array($trxStatus, ['cancel', 'deny', 'expire'])) {
            if ($order->status !== 'paid' && $order->status !== 'downloaded') {
                $order->update(['status' => 'failed']);
            }
        } elseif ($trxStatus === 'pending') {
            if ($order->status !== 'paid' && $order->status !== 'downloaded') {
                $order->update(['status' => 'pending']);
            }
        } else {
            // Status lainnya dari midtrans jika ada (refund, chargeback, dsb.)
            Log::info("Unhandled Midtrans status: {$trxStatus} for order {$orderId}");
        }

        return response()->json(['message' => 'success', 'order_status' => $order->fresh()->status]);
    }
}
