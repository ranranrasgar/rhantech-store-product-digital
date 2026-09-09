<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\ProSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'payment_source' => 'required|in:balance,instant,manual',
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

        // Check if paying with store balance
        if ($paymentSource === 'balance') {
            if ($store->balance < $amount) {
                return back()->with('error', 'Saldo penjualan Anda tidak mencukupi untuk pembayaran paket ini. Saldo: Rp ' . number_format($store->balance, 0, ',', '.') . ', Diperlukan: Rp ' . number_format($amount, 0, ',', '.'));
            }
            $store->decrement('balance', $amount);
        }

        // Calculate expiration
        $currentExpiry = ($store->isPro() && $store->pro_expires_at) ? $store->pro_expires_at : now();
        $expiresAt = $selectedPlan['duration_days'] ? $currentExpiry->copy()->addDays($selectedPlan['duration_days']) : null;

        // Record Subscription
        $subscription = ProSubscription::create([
            'store_id' => $store->id,
            'user_id' => Auth::id(),
            'reference_no' => 'PRO-' . strtoupper(Str::random(6)) . '-' . date('Ymd'),
            'plan' => $plan,
            'amount' => $amount,
            'payment_method' => $paymentSource === 'balance' ? 'Saldo Toko' : 'Aktivasi Kilat (Transfer/Midtrans)',
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

        return redirect()->route('tenant.pro.index')->with('success', 'Selamat! Toko Anda kini telah resmi menjadi Toko PRO! Semua fitur eksklusif dan fee penarikan 1% telah aktif.');
    }
}
