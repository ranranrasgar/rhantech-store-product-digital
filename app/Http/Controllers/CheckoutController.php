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

        $selectedIds = $request->input('selected_ids', []);
        if (!empty($selectedIds)) {
            session()->put('checkout_selected_ids', $selectedIds);
        } else {
            // Jika tidak ada, pilih semua
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

        return view('checkout.index', compact('cart', 'defaultPhone'));
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

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Check if there is an affiliate referrer in session
        $referrerStoreId = null;
        $affiliateCommission = 0;
        $affiliateRef = session('affiliate_ref');
        if ($affiliateRef) {
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
                    $affiliateCommission = round(($totalAmount * $commRate) / 100, 2);
                }
            } else {
                // Cek jika referral berupa store slug
                $refStoreBySlug = \App\Models\Store::where('slug', $affiliateRef)->first();
                if ($refStoreBySlug) {
                    $referrerStoreId = $refStoreBySlug->id;
                    $affiliateCommission = round(($totalAmount * 10) / 100, 2); // default 10%
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
            'amount' => $totalAmount,
            'status' => 'pending',
            'download_token' => Str::random(60),
        ]);

        $midtransItemDetails = [];

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            $midtransItemDetails[] = [
                'id' => $id,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'name' => mb_substr($item['name'], 0, 50)
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
                'gross_amount' => $totalAmount,
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
            
            // Clear cart
            session()->forget('cart');
            
            return redirect()->route('checkout.payment', $order->invoice_number);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function payment($invoice_number)
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
    public function checkStatus($invoice_number)
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
