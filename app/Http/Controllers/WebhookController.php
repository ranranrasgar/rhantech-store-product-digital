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
        try {
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

            // 1. Prioritaskan jika order ini milik aplikasi ini sendiri (ada di tabel orders atau ad_transactions)
            $localOrderExists = Order::where('invoice_number', $orderId)->exists();
            $adTopUpExists = \App\Models\AdTransaction::where('reference_no', $orderId)->exists();
            if ($localOrderExists || $adTopUpExists) {
                return $this->processLocal($request);
            }

            // 2. Jika bukan order lokal, cari di Gateway Apps yang aktif untuk diteruskan
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

            // Teruskan ke callback_url aplikasi lain (contoh: NOC Rhantech)
            try {
                Log::info("Forwarding webhook for Order {$orderId} to App: {$targetApp->name} at {$targetApp->callback_url}");
                
                $payload = $request->json()->all() ?: $request->all();
                
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])->timeout(15)->post($targetApp->callback_url, $payload);

                Log::info("Forward response status for {$orderId}: " . $response->status());

                return response()->json([
                    'message'       => 'forwarded',
                    'target'        => $targetApp->name,
                    'target_status' => $response->status(),
                ], 200);
            } catch (\Throwable $e) {
                Log::error("Failed to forward webhook to {$targetApp->name}: " . $e->getMessage());
                // Tetap kembalikan 200 agar Midtrans menganggap callback sudah diterima
                return response()->json(['message' => 'forward logged with error', 'error' => $e->getMessage()], 200);
            }
        } catch (\Throwable $e) {
            Log::error("Midtrans Webhook Exception: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Jaminan status 200 agar Midtrans tidak me-retry terus / status In progress
            return response()->json(['status' => 'error_logged', 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Proses webhook secara lokal untuk aplikasi ini.
     */
    private function processLocal(Request $request)
    {
        try {
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

            // Periksa apakah ini transaksi Top Up Saldo Iklan
            $adTransaction = \App\Models\AdTransaction::where('reference_no', $orderId)->first();
            if ($adTransaction) {
                if ($trxStatus === 'capture' || $trxStatus === 'settlement') {
                    if ($adTransaction->status !== 'completed') {
                        $adTransaction->update(['status' => 'completed']);
                        $adTransaction->store->increment('ad_balance', $adTransaction->amount);

                        // Aktifkan iklan toko yang terjeda
                        \App\Models\SellerAd::where('store_id', $adTransaction->store_id)
                            ->where('status', 'paused')
                            ->update(['status' => 'active']);

                        Log::info("Ad Topup #{$orderId} settled successfully! Saldo Rp{$adTransaction->amount} added to store #{$adTransaction->store_id}");
                    }
                } elseif (in_array($trxStatus, ['cancel', 'deny', 'expire'])) {
                    $adTransaction->update(['status' => 'failed']);
                }

                return response()->json(['message' => 'ad topup processed', 'status' => $adTransaction->fresh()->status], 200);
            }

            $order = Order::with('orderItems.product.store')->where('invoice_number', $orderId)->first();

            if (!$order) {
                Log::warning("Midtrans Webhook: Order not found for invoice {$orderId}");
                return response()->json(['message' => 'order not found'], 200);
            }

            if (!$isValidSignature) {
                Log::warning("Midtrans Webhook Signature Mismatch for Order {$orderId}", [
                    'expected_raw'       => $hash1,
                    'expected_formatted' => $hash2,
                    'received'           => $signatureKey,
                ]);
            }

            Log::info("Midtrans processing order {$orderId} with status {$trxStatus}");

            if ($trxStatus === 'capture' || $trxStatus === 'settlement') {
                if ($order->status !== 'paid' && $order->status !== 'downloaded') {
                    $order->update(['status' => 'paid']);

                    // Tambahkan saldo ke seller store secara aman
                    try {
                        foreach ($order->orderItems as $item) {
                            if ($item->product && $item->product->store_id && $item->product->store) {
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

                        // Update statistik mitra affiliate jika ada
                        if ($order->affiliate_id) {
                            $affRecord = \App\Models\Affiliate::find($order->affiliate_id);
                            if ($affRecord) {
                                $affRecord->increment('orders_count');
                                $currSales = (float) preg_replace('/[^0-9]/', '', $affRecord->sales_range ?? '0');
                                $newSales = $currSales + $order->amount;
                                $affRecord->update(['sales_range' => 'Rp ' . number_format($newSales, 0, ',', '.')]);
                            }
                        }
                    } catch (\Throwable $storeEx) {
                        Log::error("Failed to increment store balance for Order {$orderId}: " . $storeEx->getMessage());
                    }

                    // Kirim email notifikasi
                    if (!empty($order->customer_email)) {
                        try {
                            Mail::to($order->customer_email)->send(new OrderPaidMail($order));
                        } catch (\Throwable $mailEx) {
                            Log::error("Failed to send order email: " . $mailEx->getMessage());
                        }
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
                Log::info("Unhandled Midtrans status: {$trxStatus} for order {$orderId}");
            }

            return response()->json(['message' => 'success', 'order_status' => $order->fresh()->status], 200);
        } catch (\Throwable $e) {
            Log::error("Process local webhook error: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['message' => 'handled with error', 'error' => $e->getMessage()], 200);
        }
    }
}
