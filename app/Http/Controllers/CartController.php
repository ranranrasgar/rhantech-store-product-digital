<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::with('store')->findOrFail($request->product_id);
        
        $cart = session()->get('cart', []);

        $qty = max(1, (int)$request->input('quantity', 1));

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $qty;
        } else {
            $cart[$product->id] = [
                'name'       => $product->name,
                'slug'       => $product->slug,
                'quantity'   => $qty,
                'price'      => $product->discount_price ?? $product->price,
                'image'      => $product->images->where('is_main', true)->first()->image_path ?? ($product->images->first()->image_path ?? null),
                'store_name' => $product->store->name ?? 'Admin Store'
            ];
        }

        session()->put('cart', $cart);

        $cartCount = array_sum(array_column($cart, 'quantity'));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => $cartCount,
            ]);
        }

        if ($request->redirect_to_checkout == '1') {
            return redirect()->route('checkout.index');
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if($request->id) {
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
            }
        } elseif ($request->ids && is_array($request->ids)) {
            foreach ($request->ids as $id) {
                if (isset($cart[$id])) {
                    unset($cart[$id]);
                }
            }
        }
        
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}
