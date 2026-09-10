<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\ProSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProController extends Controller
{
    private function getStore(): ?Store
    {
        return Auth::user()->store;
    }

    public function index()
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.store.index')->with('error', 'Silakan lengkapi profil toko Anda terlebih dahulu.');
        }

        $subscriptions = $store->proSubscriptions()->latest()->paginate(10);

        return view('tenant.pro.index', compact('store', 'subscriptions'));
    }

    public function upgrade(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.store.index')->with('error', 'Toko tidak ditemukan.');
        }

        $request->validate([
            'plan' => 'required|in:monthly,yearly,lifetime',
            'payment_source' => 'required|in:qris,midtrans,balance',
        ]);

        $plan = $request->input('plan');
        $pricing = [
            'monthly' => ['amount' => 49000, 'duration_days' => 30, 'label' => 'Bulanan (1 Bulan)'],
            'yearly' => ['amount' => 399000, 'duration_days' => 365, 'label' => 'Tahunan (1 Tahun)'],
            'lifetime' => ['amount' => 799000, 'duration_days' => null, 'label' => 'Lifetime (Selamanya)'],
        ];

        $selectedPlan = $pricing[$plan];
        $amount = $selectedPlan['amount'];
        $paymentSource = $request->input('payment_source');

        // Nomor invoice diawali dengan RHN- (wajib untuk pencocokan callback Midtrans lokal di rhantech.com)
        $referenceNo = 'RHN-PRO-' . date('ym') . '-' . strtoupper(Str::random(5));

        // Hitung masa berlaku
        $currentExpiry = ($store->isPro() && $store->pro_expires_at) ? $store->pro_expires_at : now();
        $expiresAt = $selectedPlan['duration_days'] ? $currentExpiry->copy()->addDays($selectedPlan['duration_days']) : null;

        // 1. Opsi Pembayaran Potong Saldo Penjualan Toko
        if ($paymentSource === 'balance') {
            if ($store->balance < $amount) {
                return back()->with('error', 'Saldo penjualan Anda tidak mencukupi untuk pembayaran paket ini. Saldo: Rp ' . number_format($store->balance, 0, ',', '.') . ', Diperlukan: Rp ' . number_format($amount, 0, ',', '.'));
            }

            $store->decrement('balance', $amount);

            $subscription = ProSubscription::create([
                'store_id' => $store->id,
                'user_id' => Auth::id(),
                'reference_no' => $referenceNo,
                'plan' => $plan,
                'amount' => $amount,
                'payment_method' => 'Saldo Toko',
                'payment_status' => 'paid',
                'paid_at' => now(),
                'expires_at' => $expiresAt,
            ]);

            // Update Store to PRO
            $store->update([
                'is_pro' => true,
                'pro_expires_at' => $expiresAt,
                'pro_plan' => $plan,
            ]);

            return redirect()->route('tenant.pro.index')->with('success', '🎉 Selamat! Toko Anda kini telah resmi menjadi Toko PRO! Semua fitur eksklusif dan fee penarikan 1% telah aktif.');
        }

        // 2. Opsi Pembayaran QRIS / Midtrans Instant
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $user = Auth::user();
        $params = [
            'transaction_details' => [
                'order_id' => $referenceNo,
                'gross_amount' => (int) $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? ($store->phone ?? '08123456789'),
            ],
            'item_details' => [
                [
                    'id' => 'PRO_' . strtoupper($plan),
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => 'Upgrade Toko PRO - ' . $selectedPlan['label'],
                ]
            ],
            'override_notification_urls' => [
                url('/api/webhooks/midtrans/callback')
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $subscription = ProSubscription::create([
                'store_id' => $store->id,
                'user_id' => Auth::id(),
                'reference_no' => $referenceNo,
                'plan' => $plan,
                'amount' => $amount,
                'payment_method' => 'Midtrans (QRIS / Instant)',
                'payment_status' => 'pending',
                'snap_token' => $snapToken,
                'expires_at' => $expiresAt,
            ]);

            return redirect()->route('tenant.pro.payment', $subscription->reference_no);
        } catch (\Exception $e) {
            Log::error("Failed to generate Midtrans Snap for PRO subscription: " . $e->getMessage());
            return back()->with('error', 'Gagal memproses QRIS Midtrans: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Bayar Upgrade Toko PRO (Snap QRIS)
     */
    public function payment(string $referenceNo)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard');
        }

        $subscription = ProSubscription::where('store_id', $store->id)
            ->where('reference_no', $referenceNo)
            ->firstOrFail();

        if ($subscription->payment_status === 'paid') {
            return redirect()->route('tenant.pro.index')->with('success', 'Pembayaran Toko PRO telah berhasil terkonfirmasi!');
        }

        return view('tenant.pro.payment', compact('subscription', 'store'));
    }

    /**
     * Sinkronisasi Status Pembayaran Toko PRO dari Midtrans API
     */
    public function finishPayment(string $referenceNo)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard');
        }

        $subscription = ProSubscription::where('store_id', $store->id)
            ->where('reference_no', $referenceNo)
            ->firstOrFail();

        // Cek status langsung ke Midtrans API
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production');
        $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
            ])->get("{$baseUrl}/v2/{$referenceNo}/status");

            if ($response->successful()) {
                $statusData = $response->json();
                $transactionStatus = $statusData['transaction_status'] ?? '';

                if (in_array($transactionStatus, ['settlement', 'capture'])) {
                    if ($subscription->payment_status !== 'paid') {
                        $subscription->update([
                            'payment_status' => 'paid',
                            'paid_at' => now(),
                        ]);

                        // Aktifkan Toko PRO
                        $store->update([
                            'is_pro' => true,
                            'pro_expires_at' => $subscription->expires_at,
                            'pro_plan' => $subscription->plan,
                        ]);
                    }

                    return redirect()->route('tenant.pro.index')->with('success', '🎉 Pembayaran Berhasil! Toko Anda kini resmi menjadi Toko PRO! Nikmati seluruh keuntungan eksklusif.');
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to check Midtrans PRO status: " . $e->getMessage());
        }

        return redirect()->route('tenant.pro.index')->with('info', 'Status pembayaran sedang diproses. Toko Anda akan otomatis menjadi PRO begitu pembayaran terverifikasi.');
    }
}
