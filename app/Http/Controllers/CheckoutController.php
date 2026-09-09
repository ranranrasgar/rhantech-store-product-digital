<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Campaign;
use App\Models\Store;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * POST /checkout/select — simpan item yang dipilih ke session, redirect ke GET /checkout
     */
    public function selectItems(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong.');
        }

        $selectedIds = $request->input('selected_items', []);

        if (!empty($selectedIds)) {
            session()->put('checkout_selected_ids', $selectedIds);
        } else {
            session()->put('checkout_selected_ids', array_keys($cart));
        }

        return redirect()->route('checkout.index');
    }

    /**
     * GET /checkout — tampilkan form checkout dengan item yang dipilih
     */
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Ambil item yang dipilih dari session
        $selectedIds = session()->get('checkout_selected_ids', array_keys($cart));
        $cart = array_intersect_key($cart, array_flip($selectedIds));

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tidak ada produk yang dipilih.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        // Ambil store IDs dari produk yang ada di cart
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->with('store')->get()->keyBy('id');
        $storeIds = $products->pluck('store_id')->filter()->unique()->toArray();

        // Kupon voucher aktif yang tersedia untuk toko produk di cart
        $availableVouchers = Campaign::active()
            ->where(function($q) {
                $q->where('type', 'voucher')->orWhereNull('type');
            })
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->where(function($q) use ($storeIds) {
                if (!empty($storeIds)) {
                    $q->whereIn('store_id', $storeIds)->orWhereNull('store_id');
                } else {
                    $q->whereNull('store_id')->orWhere('store_id', 1);
                }
            })
            ->with('store')
            ->get();

        // Cek apakah ada voucher yang sedang aktif di session
        $appliedVoucher = session('applied_voucher');
        $discountAmount = 0;
        if ($appliedVoucher && !empty($appliedVoucher['id'])) {
            $campaign = Campaign::active()->find($appliedVoucher['id']);
            if ($campaign && ($campaign->minimum_spend == 0 || $subtotal >= (float)$campaign->minimum_spend)) {
                $discountAmount = $campaign->calculateDiscount($cart);
                $appliedVoucher['discount_amount'] = $discountAmount;
                $appliedVoucher['is_free'] = ($discountAmount >= $subtotal);
                session()->put('applied_voucher', $appliedVoucher);
            } else {
                session()->forget('applied_voucher');
                $appliedVoucher = null;
            }
        }

        // Ambil daftar ID voucher yang sudah pernah digunakan oleh user ini (1x pakai per pembeli)
        $usedCampaignIds = [];
        if (Auth::check()) {
            $userEmail = Auth::user()->email;
            $usedOrders = Order::where('customer_email', $userEmail)
                ->whereIn('status', ['paid', 'downloaded', 'pending'])
                ->where(function($q) {
                    $q->whereNotNull('campaign_id')->orWhereNotNull('voucher_code');
                })
                ->get(['campaign_id', 'voucher_code']);

            $usedCampaignIds = $usedOrders->pluck('campaign_id')->filter()->unique()->toArray();
            $usedCodes = $usedOrders->pluck('voucher_code')->filter()->unique()->toArray();

            $codeIds = !empty($usedCodes) ? Campaign::whereIn('code', $usedCodes)->pluck('id')->toArray() : [];
            $usedCampaignIds = array_values(array_unique(array_merge($usedCampaignIds, $codeIds)));

            if ($appliedVoucher && in_array($appliedVoucher['id'], $usedCampaignIds)) {
                session()->forget('applied_voucher');
                $appliedVoucher = null;
                $discountAmount = 0;
            }
        }

        $finalAmount = max(0, $subtotal - $discountAmount);

        // Ambil nomor HP default jika pembeli sedang login
        $defaultPhone = '';
        if (Auth::check()) {
            $user = Auth::user();
            $defaultPhone = $user->phone ?? '';

            // Jika di kolom user->phone masih kosong, cek riwayat order terakhir
            if (empty($defaultPhone)) {
                $defaultPhone = Order::where('customer_email', $user->email)
                    ->whereNotNull('customer_phone')
                    ->latest()
                    ->value('customer_phone') ?? '';
            }
        }

        return view('checkout.index', compact('cart', 'subtotal', 'availableVouchers', 'appliedVoucher', 'discountAmount', 'finalAmount', 'defaultPhone', 'usedCampaignIds'));
    }

    /**
     * POST /checkout/apply-voucher — Validasi & terapkan kode voucher
     */
    public function applyVoucher(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));
        if (empty($code)) {
            return response()->json(['success' => false, 'message' => 'Silakan masukkan kode voucher.'], 422);
        }

        $cart = session()->get('cart', []);
        $selectedIds = session()->get('checkout_selected_ids', array_keys($cart));
        $cart = array_intersect_key($cart, array_flip($selectedIds));

        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Keranjang checkout Anda kosong.'], 422);
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $storeIds = $products->pluck('store_id')->filter()->unique()->toArray();

        $voucher = Campaign::active()
            ->where('type', 'voucher')
            ->where('code', $code)
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kupon voucher tidak ditemukan, sudah kedaluwarsa, atau kuota habis.'
            ], 404);
        }

        // Validasi batasan 1 kupon hanya bisa dipakai 1 kali per akun/email pembeli
        $userEmail = Auth::check() ? Auth::user()->email : trim($request->input('email', ''));
        if (!empty($userEmail)) {
            $alreadyUsed = Order::where('customer_email', $userEmail)
                ->where(function($q) use ($voucher) {
                    $q->where('campaign_id', $voucher->id)
                      ->orWhere('voucher_code', $voucher->code);
                })
                ->whereIn('status', ['paid', 'downloaded', 'pending'])
                ->exists();

            if ($alreadyUsed) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda sudah pernah menggunakan voucher {$voucher->code}. Setiap kupon hanya dapat digunakan 1 kali per pelanggan."
                ], 422);
            }
        }

        // Cek kepemilikan toko
        if (!empty($storeIds) && $voucher->store_id && !in_array($voucher->store_id, $storeIds)) {
            $voucherStoreName = $voucher->store->name ?? 'toko lain';
            return response()->json([
                'success' => false,
                'message' => "Voucher ini khusus diterbitkan oleh {$voucherStoreName} dan tidak berlaku untuk produk toko yang sedang Anda beli."
            ], 422);
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        // Cek minimum belanja
        if ($voucher->minimum_spend > 0 && $subtotal < (float)$voucher->minimum_spend) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal belanja untuk menggunakan voucher ini adalah Rp ' . number_format($voucher->minimum_spend, 0, ',', '.') . '.'
            ], 422);
        }

        // Hitung diskon
        $discount = $voucher->calculateDiscount($cart);
        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak dapat diterapkan pada produk pilihan di keranjang Anda.'
            ], 422);
        }

        $finalTotal = max(0, $subtotal - $discount);
        $isFree = ($finalTotal <= 0);

        session()->put('applied_voucher', [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'name' => $voucher->name,
            'discount_type' => $voucher->discount_type,
            'discount_value' => (float)$voucher->discount_value,
            'discount_amount' => $discount,
            'is_free' => $isFree,
        ]);

        return response()->json([
            'success' => true,
            'message' => $isFree ? '🎉 Selamat! Diskon 100% diterapkan, pesanan Anda GRATIS!' : '✅ Voucher berhasil digunakan!',
            'voucher' => session('applied_voucher'),
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            'discount_amount' => $discount,
            'discount_amount_formatted' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'final_total' => $finalTotal,
            'final_total_formatted' => $isFree ? 'GRATIS (Rp 0)' : 'Rp ' . number_format($finalTotal, 0, ',', '.'),
            'is_free' => $isFree,
        ]);
    }

    /**
     * POST /checkout/remove-voucher — Hapus voucher yang diterapkan
     */
    public function removeVoucher(Request $request)
    {
        session()->forget('applied_voucher');

        $cart = session()->get('cart', []);
        $selectedIds = session()->get('checkout_selected_ids', array_keys($cart));
        $cart = array_intersect_key($cart, array_flip($selectedIds));

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Voucher berhasil dibatalkan.',
            'subtotal' => $subtotal,
            'subtotal_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
            'final_total' => $subtotal,
            'final_total_formatted' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
        ]);
    }

    /**
     * GET /checkout/store-vouchers — Ambil daftar voucher toko untuk modal
     */
    public function getStoreVouchers(Request $request)
    {
        $cart = session()->get('cart', []);
        $selectedIds = session()->get('checkout_selected_ids', array_keys($cart));
        $cart = array_intersect_key($cart, array_flip($selectedIds));

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $storeIds = $products->pluck('store_id')->filter()->unique()->toArray();

        $vouchers = Campaign::active()
            ->where(function($q) {
                $q->where('type', 'voucher')->orWhereNull('type');
            })
            ->whereNotNull('code')
            ->where('code', '!=', '')
            ->where(function($q) use ($storeIds) {
                if (!empty($storeIds)) {
                    $q->whereIn('store_id', $storeIds)->orWhereNull('store_id');
                } else {
                    $q->whereNull('store_id')->orWhere('store_id', 1);
                }
            })
            ->with('store')
            ->get();

        $usedCampaignIds = [];
        $userEmail = Auth::check() ? Auth::user()->email : trim($request->input('email', ''));
        if (!empty($userEmail)) {
            $usedCampaignIds = Order::where('customer_email', $userEmail)
                ->whereIn('status', ['paid', 'downloaded', 'pending'])
                ->whereNotNull('campaign_id')
                ->pluck('campaign_id')
                ->toArray();

            $usedCodes = Order::where('customer_email', $userEmail)
                ->whereIn('status', ['paid', 'downloaded', 'pending'])
                ->whereNotNull('voucher_code')
                ->pluck('voucher_code')
                ->toArray();

            $codeIds = Campaign::whereIn('code', $usedCodes)->pluck('id')->toArray();
            $usedCampaignIds = array_values(array_unique(array_merge($usedCampaignIds, $codeIds)));
        }

        return response()->json([
            'success' => true,
            'vouchers' => $vouchers,
            'used_ids' => $usedCampaignIds
        ]);
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // Ambil hanya item yang dipilih
        $selectedIds = session()->get('checkout_selected_ids', array_keys($cart));
        $cart = array_intersect_key($cart, array_flip($selectedIds));

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Keranjang Anda kosong.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'voucher_code' => 'nullable|string|max:50',
        ]);

        // Auto Create / Check User for Guest Checkout
        if (!Auth::check()) {
            $existingUser = User::query()->where('email', $validated['customer_email'])->first();
            if (!$existingUser) {
                // Generate secure random password
                $randomPassword = Str::random(12);
                
                $newUser = User::query()->create([
                    'name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                    'phone' => $validated['customer_phone'],
                    'password' => Hash::make($randomPassword),
                    'role' => 'User',
                ]);

                // Trigger Laravel standard email verification event
                event(new Registered($newUser));

                // Auto log in the user so their session is active
                Auth::login($newUser);
            } else {
                if (empty($existingUser->phone)) {
                    $existingUser->update(['phone' => $validated['customer_phone']]);
                }
            }
        } else {
            // Update phone user jika belum terisi
            $currentUser = Auth::user();
            if (empty($currentUser->phone)) {
                $currentUser->update(['phone' => $validated['customer_phone']]);
            }
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Voucher discount validation & calculation
        $appliedVoucher = session('applied_voucher');
        $code = strtoupper(trim($request->input('voucher_code', $appliedVoucher['code'] ?? '')));
        $campaign = null;
        $discountAmount = 0;

        if (!empty($code)) {
            $campaign = Campaign::active()
                ->where(function($q) {
                    $q->where('type', 'voucher')->orWhereNull('type');
                })
                ->where('code', $code)
                ->first();

            if ($campaign) {
                // Validasi ketat 1x pemakaian per pembeli/akun
                $customerEmail = $validated['customer_email'];
                $alreadyUsed = Order::where('customer_email', $customerEmail)
                    ->where(function($q) use ($campaign) {
                        $q->where('campaign_id', $campaign->id)
                          ->orWhere('voucher_code', $campaign->code);
                    })
                    ->whereIn('status', ['paid', 'downloaded', 'pending'])
                    ->exists();

                if ($alreadyUsed) {
                    session()->forget('applied_voucher');
                    return redirect()->route('checkout.index')->with('error', "Kupon {$campaign->code} hanya dapat digunakan 1 kali per pembeli dan akun/email Anda sudah pernah menggunakannya.");
                }

                if ($campaign->minimum_spend == 0 || $subtotal >= (float)$campaign->minimum_spend) {
                    $discountAmount = $campaign->calculateDiscount($cart);
                }
            }
        }

        $finalTotal = max(0, $subtotal - $discountAmount);
        $isFreeOrder = ($finalTotal <= 0);

        // Check if there is an affiliate referrer in session
        $referrerStoreId = null;
        $affiliateCommission = 0;
        $affiliateRef = session('affiliate_ref');
        if ($affiliateRef && $finalTotal > 0) {
            $affiliateRecord = \App\Models\Affiliate::where('referral_code', $affiliateRef)->first();
            if ($affiliateRecord) {
                // Cari store milik pemilik referral
                $refStore = null;
                if ($affiliateRecord->affiliate_store_id) {
                    $refStore = \App\Models\Store::find($affiliateRecord->affiliate_store_id);
                } elseif ($affiliateRecord->user_id) {
                    $refStore = \App\Models\Store::where('user_id', $affiliateRecord->user_id)->first();
                } elseif ($affiliateRecord->store_id) {
                    $refStore = \App\Models\Store::find($affiliateRecord->store_id);
                }

                if ($refStore) {
                    $referrerStoreId = $refStore->id;
                    $commRate = $affiliateRecord->commission_rate ?? 10;
                    $affiliateCommission = round(($finalTotal * $commRate) / 100, 2);
                }
            } else {
                // Cek jika referral berupa store slug
                $refStoreBySlug = \App\Models\Store::where('slug', $affiliateRef)->first();
                if ($refStoreBySlug) {
                    $referrerStoreId = $refStoreBySlug->id;
                    $affiliateCommission = round(($finalTotal * 10) / 100, 2); // default 10%
                }
            }
        }

        $order = Order::create([
            'invoice_number' => 'RHN-' . date('ym') . '-' . Str::random(5),
            'referrer_store_id' => $referrerStoreId,
            'affiliate_commission' => $affiliateCommission,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'amount' => $finalTotal,
            'voucher_code' => $campaign ? $campaign->code : null,
            'discount_amount' => $discountAmount,
            'campaign_id' => $campaign ? $campaign->id : null,
            'status' => $isFreeOrder ? 'paid' : 'pending',
            'download_token' => Str::random(60),
        ]);

        if ($campaign) {
            $campaign->increment('used_count');
        }

        $midtransItemDetails = [];

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $midtransItemDetails[] = [
                'id' => (string) $id,
                'price' => (int) $item['price'],
                'quantity' => (int) $item['quantity'],
                'name' => mb_substr($item['name'], 0, 50)
            ];
        }

        // Kasus 1: Pesanan GRATIS (Diskon 100% atau Rp 0)
        // Midtrans tidak bisa memproses gross_amount = 0, sehingga pesanan langsung lunas otomatis!
        if ($isFreeOrder) {
            // Bersihkan keranjang dan voucher dari session
            session()->forget(['cart', 'checkout_selected_ids', 'applied_voucher']);

            try {
                \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPaidMail($order));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send free order email: " . $e->getMessage());
            }

            return redirect()->route('tenant.purchases.index')
                ->with('success', '🎉 Selamat! Kupon voucher 100% diterapkan! Pesanan Anda GRATIS dan file siap langsung diunduh!');
        }

        // Kasus 2: Pesanan Berbayar (Midtrans)
        if ($discountAmount > 0) {
            $midtransItemDetails[] = [
                'id' => 'VOUCHER-DISCOUNT',
                'price' => -1 * (int) round($discountAmount),
                'quantity' => 1,
                'name' => 'Diskon Kupon ' . ($campaign->code ?? 'PROMO')
            ];
        }

        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->invoice_number,
                'gross_amount' => (int) round($finalTotal),
            ),
            'customer_details' => array(
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ),
            'item_details' => $midtransItemDetails,
            'override_notification_urls' => array(
                url('/api/webhooks/midtrans/callback')
            )
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
            
            // Clear cart & voucher
            session()->forget(['cart', 'checkout_selected_ids', 'applied_voucher']);
            
            return redirect()->route('checkout.payment', $order->invoice_number);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function payment(string $invoice_number)
    {
        $order = Order::with('orderItems.product')->where('invoice_number', $invoice_number)->firstOrFail();
        
        if ($order->status !== 'pending') {
            return redirect()->route('tenant.purchases.index')->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }

        return view('checkout.payment', compact('order'));
    }

    /**
     * Endpoint untuk sinkronisasi realtime saat pembayaran di Midtrans Snap selesai
     */
    public function checkStatus(string $invoice_number)
    {
        $order = Order::with('orderItems.product.store')->where('invoice_number', $invoice_number)->firstOrFail();

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

                        foreach ($order->orderItems as $item) {
                            if ($item->product && $item->product->store_id) {
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

                        try {
                            \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderPaidMail($order));
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Failed to send order email: " . $e->getMessage());
                        }
                    }
                } elseif (in_array($trxStatus, ['cancel', 'deny', 'expire'])) {
                    $order->update(['status' => 'failed']);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to check status: " . $e->getMessage());
        }

        return redirect()->route('tenant.purchases.index')->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }
}
