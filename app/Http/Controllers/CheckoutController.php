<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('checkout.index', compact('product'));
    }

    public function process(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
        ]);

        $amount = $product->discount_price ?? $product->price;

        $order = Order::create([
            'invoice_number' => 'INV-' . time() . '-' . Str::random(5),
            'product_id' => $product->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'amount' => $amount,
            'status' => 'pending',
            'download_token' => Str::random(60),
        ]);

        // Configure Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->invoice_number,
                'gross_amount' => $amount,
            ),
            'customer_details' => array(
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ),
            'item_details' => array(
                array(
                    'id' => $product->id,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => mb_substr($product->name, 0, 50)
                )
            )
        );

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);
            return redirect()->route('checkout.payment', $order->invoice_number);
        } catch (\Exception $e) {
            return back()->with('error', 'Payment gateway error: ' . $e->getMessage());
        }
    }

    public function payment($invoice_number)
    {
        $order = Order::with('product')->where('invoice_number', $invoice_number)->firstOrFail();
        
        if ($order->status !== 'pending') {
            return redirect()->route('products.index')->with('success', 'Order already processed.');
        }

        return view('checkout.payment', compact('order'));
    }
}
