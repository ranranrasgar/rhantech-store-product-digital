<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Order;

class TenantBroadcastController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->user()->store;
        
        if (!$store) {
            return redirect()->route('tenant.store.index')->with('error', 'Buat toko terlebih dahulu.');
        }

        if (!$store->isPro()) {
            return redirect()->route('tenant.pro.index')->with('error', 'Fitur WA Broadcast hanya tersedia untuk Toko PRO.');
        }

        // Get unique customers from orders
        $customers = Order::where('store_id', $store->id)
            ->whereNotNull('customer_phone')
            ->select('customer_name', 'customer_phone')
            ->distinct()
            ->get();

        return view('tenant.broadcast.index', compact('store', 'customers'));
    }

    public function send(Request $request)
    {
        $store = $request->user()->store;
        
        if (!$store || !$store->isPro()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'message' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'string'
        ]);

        // Di sistem nyata, ini akan dispatch job ke sistem antrian untuk kirim WA (misal Fonnte/Wablas)
        // Simulasi delay pengiriman
        sleep(1);

        return response()->json([
            'success' => true,
            'message' => 'Broadcast WhatsApp berhasil dikirim ke ' . count($request->recipients) . ' pelanggan.',
            'sent_count' => count($request->recipients)
        ]);
    }
}
