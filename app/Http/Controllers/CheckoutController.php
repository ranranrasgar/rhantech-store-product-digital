<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        return view('checkout.index', compact('cart'));
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

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'invoice_number' => 'RHN-' . date('ym') . '-' . Str::random(5),
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
            'item_details' => $midtransItemDetails
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
            return redirect()->route('products.index')->with('success', 'Order already processed.');
        }

        return view('checkout.payment', compact('order'));
    }
}
