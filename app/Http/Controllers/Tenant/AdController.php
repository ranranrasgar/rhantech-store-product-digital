<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\AdTransaction;
use App\Models\Product;
use App\Models\ProductSearch;
use App\Models\SellerAd;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdController extends Controller
{
    private function getStore(): ?Store
    {
        return Store::where('user_id', Auth::id())->first();
    }

    /**
     * Dashboard Pusat Iklan Toko (Iklan Shopee Style)
     */
    public function index(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        $adBalance = (float) ($store->ad_balance ?? 0);
        $storeBalance = (float) ($store->balance ?? 0);

        // Hitung biaya iklan hari ini dari transaksi / ad
        $todaySpent = (float) AdTransaction::where('store_id', $store->id)
            ->where('type', 'deduction')
            ->whereDate('created_at', today())
            ->sum('amount');

        $adsQuery = SellerAd::where('store_id', $store->id);
        $adStats = (clone $adsQuery)->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 'paused' THEN 1 ELSE 0 END) as paused
        ")->first();
        $totalAdsCount = (int) ($adStats->total ?? 0);
        $activeAdsCount = (int) ($adStats->active ?? 0);
        $pausedAdsCount = (int) ($adStats->paused ?? 0);

        $ads = (clone $adsQuery)->with(['product.images'])->latest()->paginate(10);

        // Rekomendasi kata kunci pencarian populer di platform
        $trendingKeywords = ProductSearch::orderByDesc('hits')
            ->take(8)
            ->get();

        // Cek apakah saldo habis & ada iklan yang terhenti
        $hasZeroBalance = ($adBalance <= 0);

        // Cek apakah toko sudah mengklaim voucher selamat datang 500rb
        $hasClaimedWelcomeVoucher = AdTransaction::where('store_id', $store->id)
            ->where('payment_method', 'promo_voucher')
            ->exists();

        return view('tenant.ads.index', compact(
            'store',
            'adBalance',
            'storeBalance',
            'todaySpent',
            'totalAdsCount',
            'activeAdsCount',
            'pausedAdsCount',
            'ads',
            'trendingKeywords',
            'hasZeroBalance',
            'hasClaimedWelcomeVoucher'
        ));
    }

    /**
     * Klaim Bonus Saldo Iklan Rp500.000 (Welcome Voucher)
     */
    public function claimWelcomeVoucher()
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        $alreadyClaimed = AdTransaction::where('store_id', $store->id)
            ->where('payment_method', 'promo_voucher')
            ->exists();

        if ($alreadyClaimed) {
            return redirect()->back()->with('warning', 'Voucher Saldo Iklan Rp500.000 sudah pernah diklaim sebelumnya oleh tokomu.');
        }

        $store->increment('ad_balance', 500000);

        $referenceNo = 'ADVOU-' . strtoupper(Str::random(6)) . '-' . time();

        AdTransaction::create([
            'store_id' => $store->id,
            'reference_no' => $referenceNo,
            'type' => 'credit',
            'amount' => 500000,
            'tax_amount' => 0,
            'total_amount' => 500000,
            'payment_method' => 'promo_voucher',
            'status' => 'completed',
            'description' => 'Bonus Voucher Pembukaan Toko Baru - Saldo Iklan Rp500.000 (Dukungan Promosi Kunjungan +30%)',
        ]);

        return redirect()->back()->with('success', '🎉 Berhasil! Bonus Saldo Iklan Rp500.000 telah masuk ke tokomu! Gunakan sekarang untuk mempromosikan produkmu.');
    }

    /**
     * Halaman Top-Up / Isi Saldo Iklan
     */
    public function topUp(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        $adBalance = (float) ($store->ad_balance ?? 0);
        $storeBalance = (float) ($store->balance ?? 0);

        $packages = [
            25000,
            50000,
            100000,
            200000,
            500000,
            1000000,
            5000000,
            10000000,
            50000000
        ];

        return view('tenant.ads.top_up', compact('store', 'adBalance', 'storeBalance', 'packages'));
    }

    /**
     * Proses Pengisian Saldo Iklan
     */
    public function processTopUp(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:10000',
            'payment_method' => 'required|string|in:store_balance,direct_gateway',
        ]);

        $amount = (float) $request->amount;
        $ppnRate = 0.11; // PPN 11%
        $taxAmount = round($amount * $ppnRate, 2);
        $totalAmount = $amount + $taxAmount;

        $referenceNo = 'RHN-AD-' . date('ym') . '-' . strtoupper(Str::random(5));

        if ($request->payment_method === 'store_balance') {
            if ($store->balance < $totalAmount) {
                return back()->with('error', 'Saldo penghasilan toko tidak mencukupi (Tersedia: Rp' . number_format($store->balance, 0, ',', '.') . '). Silakan pilih nominal lain atau gunakan metode pembayaran instan.');
            }

            // Potong saldo penghasilan toko
            $store->decrement('balance', $totalAmount);

            // Tambahkan saldo iklan toko (sebesar nominal pokok iklan)
            $store->increment('ad_balance', $amount);

            // Catat mutasi transaksi iklan
            AdTransaction::create([
                'store_id' => $store->id,
                'reference_no' => $referenceNo,
                'type' => 'topup',
                'amount' => $amount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => 'store_balance',
                'status' => 'completed',
                'description' => 'Isi Saldo Iklan via Saldo Penghasilan Toko (Potong Rp' . number_format($totalAmount, 0, ',', '.') . ' termasuk PPN)',
            ]);

            // Jika ada iklan yang sebelumnya dipause karena saldo habis, aktifkan kembali
            SellerAd::where('store_id', $store->id)
                ->where('status', 'paused')
                ->update(['status' => 'active']);

            return redirect()->route('tenant.ads.index')->with('success', 'Isi Saldo Iklan Rp' . number_format($amount, 0, ',', '.') . ' berhasil diproses dari Saldo Toko! Semua iklan Anda kini aktif berjalan.');
        }

        // Metode direct payment via Midtrans Snap (QRIS / Bank Transfer / E-Wallet)
        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $user = Auth::user();
        $params = [
            'transaction_details' => [
                'order_id' => $referenceNo,
                'gross_amount' => (int) round($totalAmount),
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '08123456789',
            ],
            'item_details' => [
                [
                    'id' => 'AD_TOPUP_' . (int)$amount,
                    'price' => (int) $amount,
                    'quantity' => 1,
                    'name' => 'Top Up Saldo Iklan Toko',
                ],
                [
                    'id' => 'TAX_PPN_11',
                    'price' => (int) round($taxAmount),
                    'quantity' => 1,
                    'name' => 'PPN (11%)',
                ]
            ],
            'override_notification_urls' => [
                url('/api/webhooks/midtrans/callback')
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $transaction = AdTransaction::create([
                'store_id' => $store->id,
                'reference_no' => $referenceNo,
                'type' => 'topup',
                'amount' => $amount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'payment_method' => 'direct_gateway',
                'status' => 'pending',
                'snap_token' => $snapToken,
                'description' => 'Isi Saldo Iklan via Midtrans (Rp' . number_format($totalAmount, 0, ',', '.') . ' termasuk PPN)',
            ]);

            return redirect()->route('tenant.ads.payment', $transaction->reference_no);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses gateway pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Bayar Top Up Saldo Iklan (Snap)
     */
    public function payment(string $referenceNo)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard');
        }

        $transaction = AdTransaction::where('store_id', $store->id)
            ->where('reference_no', $referenceNo)
            ->firstOrFail();

        if ($transaction->status === 'completed') {
            return redirect()->route('tenant.ads.index')->with('success', 'Pembayaran saldo iklan telah berhasil terkonfirmasi!');
        }

        return view('tenant.ads.payment', compact('transaction'));
    }

    /**
     * Sinkronisasi Realtime Status Pembayaran Top Up Saldo Iklan dari Midtrans
     */
    public function finishTopUp(string $referenceNo)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard');
        }

        $transaction = AdTransaction::where('store_id', $store->id)
            ->where('reference_no', $referenceNo)
            ->firstOrFail();

        // Cek status ke Midtrans API
        $serverKey = config('midtrans.server_key');
        $isProduction = config('midtrans.is_production');
        $baseUrl = $isProduction ? 'https://api.midtrans.com' : 'https://api.sandbox.midtrans.com';

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
            ])->get("{$baseUrl}/v2/{$referenceNo}/status");

            if ($response->successful()) {
                $statusData = $response->json();
                $transactionStatus = $statusData['transaction_status'] ?? '';

                if (in_array($transactionStatus, ['settlement', 'capture'])) {
                    if ($transaction->status !== 'completed') {
                        $transaction->update(['status' => 'completed']);
                        $store->increment('ad_balance', $transaction->amount);

                        // Aktifkan iklan yang terjeda
                        SellerAd::where('store_id', $store->id)
                            ->where('status', 'paused')
                            ->update(['status' => 'active']);
                    }

                    return redirect()->route('tenant.ads.index')->with('success', '🎉 Pembayaran Berhasil! Saldo Iklan Rp' . number_format($transaction->amount, 0, ',', '.') . ' telah masuk.');
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $transaction->update(['status' => 'failed']);
                    return redirect()->route('tenant.ads.top-up')->with('error', 'Pembayaran saldo iklan gagal atau telah kedaluwarsa.');
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Failed to check Midtrans ad topup status: " . $e->getMessage());
        }

        if ($transaction->status === 'completed') {
            return redirect()->route('tenant.ads.index')->with('success', '🎉 Pembayaran Berhasil! Saldo Iklan Rp' . number_format($transaction->amount, 0, ',', '.') . ' telah masuk.');
        }

        return redirect()->route('tenant.ads.index')->with('warning', 'Transaksi Anda sedang menunggu pembayaran / diproses.');
    }

    /**
     * Form Buat Iklan Baru (Shopee Style)
     */
    public function create(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        // 1. Produk Milik Toko Sendiri (yang aktif)
        $ownProducts = $store->products()->where('products.is_active', true)->with('images')->orderBy('products.name', 'asc')->get();

        // 2. Produk Afiliasi yang Dipajang di Etalase Showcase Toko Ini
        $showcaseProducts = $store->showcaseProducts()
            ->where('products.is_active', true)
            ->wherePivot('is_active', true)
            ->with(['images', 'store'])
            ->orderBy('products.name', 'asc')
            ->get();

        $trendingSearches = ProductSearch::orderByDesc('hits')->take(12)->get();
        $adBalance = (float) ($store->ad_balance ?? 0);

        return view('tenant.ads.create', compact('store', 'ownProducts', 'showcaseProducts', 'trendingSearches', 'adBalance'));
    }

    /**
     * Simpan Iklan Baru
     */
    public function store(Request $request)
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'type' => 'required|in:product,store',
            'budget_type' => 'required|in:unlimited,daily',
            'daily_budget' => 'nullable|numeric|min:5000',
            'period_type' => 'required|in:unlimited,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'bidding_mode' => 'required|in:auto,manual',
            'bid_price' => 'nullable|numeric|min:100',
            'target_keywords' => 'nullable|array',
            'target_keywords.*' => 'nullable|string|max:100',
            'display_mode' => 'required|in:auto,manual',
        ]);

        // Pastikan product milik toko sendiri ATAU ada di etalase showcase afiliasi toko
        if (!empty($validated['product_id'])) {
            $isOwnProduct = $store->products()->where('id', $validated['product_id'])->exists();
            $isShowcaseProduct = $store->showcaseProducts()->where('products.id', $validated['product_id'])->exists();
            
            if (!$isOwnProduct && !$isShowcaseProduct) {
                return back()->with('error', 'Produk yang dipilih tidak valid atau belum ditambahkan ke etalase tokomu.');
            }
        }

        // Tentukan status: jika saldo iklan 0, status paused
        $adBalance = (float) ($store->ad_balance ?? 0);
        $status = ($adBalance > 0) ? 'active' : 'paused';

        // Filter kata kunci unik & kosong
        $keywords = [];
        if (!empty($request->target_keywords)) {
            $keywords = array_values(array_filter(array_unique($request->target_keywords)));
        }

        $ad = SellerAd::create([
            'store_id' => $store->id,
            'product_id' => $validated['product_id'] ?? null,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'budget_type' => $validated['budget_type'],
            'daily_budget' => $validated['daily_budget'] ?? null,
            'period_type' => $validated['period_type'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'bidding_mode' => $validated['bidding_mode'],
            'bid_price' => $validated['bid_price'] ?? 500,
            'target_keywords' => $keywords,
            'display_mode' => $validated['display_mode'],
            'status' => $status,
        ]);

        if ($adBalance <= 0) {
            return redirect()->route('tenant.ads.index')->with('warning', 'Iklan berhasil dibuat, namun statusnya DIJEDA karena Saldo Iklan Anda Rp0. Silakan isi saldo agar iklan langsung tampil.');
        }

        return redirect()->route('tenant.ads.index')->with('success', 'Iklan berhasil dibuat dan saat ini AKTIF dipromosikan di platform!');
    }

    /**
     * Pause / Aktifkan kembali iklan
     */
    public function toggle(SellerAd $ad)
    {
        $store = $this->getStore();
        if (!$store || $ad->store_id !== $store->id) {
            abort(403);
        }

        if ($ad->status === 'active') {
            $ad->update(['status' => 'paused']);
            $msg = 'Iklan berhasil dijeda.';
        } else {
            if ($store->ad_balance <= 0) {
                return back()->with('error', 'Tidak dapat mengaktifkan iklan karena Saldo Iklan Anda Rp0. Silakan isi saldo terlebih dahulu.');
            }
            $ad->update(['status' => 'active']);
            $msg = 'Iklan berhasil diaktifkan kembali!';
        }

        return back()->with('success', $msg);
    }

    /**
     * Hapus Iklan
     */
    public function destroy(SellerAd $ad)
    {
        $store = $this->getStore();
        if (!$store || $ad->store_id !== $store->id) {
            abort(403);
        }

        $ad->delete();

        return back()->with('success', 'Iklan berhasil dihapus.');
    }
}
