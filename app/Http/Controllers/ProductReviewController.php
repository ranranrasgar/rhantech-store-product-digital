<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    /**
     * Simpan review produk dari pembeli yang terverifikasi
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:1000',
            'order_id' => 'nullable|exists:orders,id',
            'download_token' => 'nullable|string',
        ]);

        $user = Auth::user();
        $order = null;

        // Validasi pembeli: jika ada order_id atau download_token
        if (!empty($validated['download_token'])) {
            $order = Order::where('download_token', $validated['download_token'])->first();
        } elseif (!empty($validated['order_id'])) {
            $order = Order::find($validated['order_id']);
        }

        // Jika user login dan order belum terdeteksi, cari dari email user
        if (!$order && $user) {
            $order = Order::where('customer_email', $user->email)
                ->whereIn('status', ['paid', 'downloaded'])
                ->whereHas('orderItems', function ($q) use ($product) {
                    $q->where('product_id', $product->id);
                })
                ->latest()
                ->first();
        }

        // Tentukan nama pelanggan
        $customerName = $user ? $user->name : ($order ? $order->customer_name : 'Pembeli Terverifikasi');
        $customerEmail = $user ? $user->email : ($order ? $order->customer_email : null);

        // Cek jika sudah pernah memberi review untuk produk ini dari order yang sama / user yang sama
        $existing = ProductReview::where('product_id', $product->id)
            ->when($user, fn($q) => $q->where('user_id', $user->id))
            ->when(!$user && $order, fn($q) => $q->where('order_id', $order->id))
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah pernah memberikan ulasan untuk produk ini. Ulasan yang telah dikirim tidak dapat diubah atau dihapus.');
        }

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $user?->id,
            'order_id' => $order?->id,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_visible' => true,
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda! Ulasan Anda telah berhasil diterbitkan.');
    }

    /**
     * Hapus ulasan (hanya untuk Superadmin/Pemilik Platform)
     */
    public function destroy(ProductReview $review)
    {
        if (!Auth::check() || Auth::user()->role !== 'Super Admin') {
            abort(403, 'Hanya superadmin/pemilik platform yang berhak menghapus ulasan.');
        }

        $review->delete();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
