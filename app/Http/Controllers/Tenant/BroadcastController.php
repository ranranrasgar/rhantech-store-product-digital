<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastController extends Controller
{
    private function getStore(): ?Store
    {
        return Auth::user()->store;
    }

    public function index()
    {
        $store = $this->getStore();
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Silakan buat toko terlebih dahulu.');
        }

        // Get past customers phone numbers & names from orders
        $orders = Order::where(function ($q) use ($store) {
            $q->whereHas('orderItems.product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            })->orWhereHas('product', function ($sub) use ($store) {
                $sub->where('store_id', $store->id);
            });
        })->whereIn('status', ['paid', 'downloaded'])
          ->latest()
          ->get();

        $customers = collect();
        foreach ($orders as $order) {
            if (!empty($order->customer_phone)) {
                $phone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                if (str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                }
                if (!$customers->has($phone)) {
                    $customers->put($phone, [
                        'name' => $order->customer_name,
                        'phone' => $phone,
                        'total_orders' => 1,
                        'last_order_at' => $order->created_at,
                    ]);
                } else {
                    $existing = $customers->get($phone);
                    $existing['total_orders'] += 1;
                    $customers->put($phone, $existing);
                }
            }
        }

        $products = $store->products()->where('is_active', true)->take(10)->get();

        return view('tenant.broadcast.index', compact('store', 'customers', 'products'));
    }
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'recipients' => 'required|array',
        ]);

        // Simulating the broadcast send for now
        // In a real application, you would send this via a Whatsapp API or SMS API
        
        return response()->json([
            'success' => true,
            'message' => 'Pesan broadcast berhasil dikirim ke ' . count($request->recipients) . ' penerima.',
        ]);
    }
}
