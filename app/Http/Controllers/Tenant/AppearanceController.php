<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppearanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        return view('tenant.appearance.index', compact('store'));
    }
    public function update(Request $request)
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'components' => 'required|array',
            'header_banner' => 'nullable|string',
        ]);

        $store->appearance_data = $request->input('components');
        if ($request->has('header_banner')) {
            $bannerVal = trim($request->input('header_banner') ?? '');
            $store->banner = !empty($bannerVal) ? $bannerVal : null;
        }
        $store->save();

        return response()->json([
            'success' => true,
            'message' => 'Dekorasi toko berhasil disimpan.'
        ]);
    }

    public function uploadImage(Request $request)
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('store_appearance', 'public');
            $url = asset('storage/' . $path);
            
            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Gagal mengunggah gambar.'], 400);
    }

    /**
     * Simpan konfigurasi penempatan voucher/diskon di appearance_data
     */
    public function saveVoucherPlacement(Request $request)
    {
        $store = Auth::user()->store;

        if (!$store) {
            return response()->json(['success' => false, 'message' => 'Toko tidak ditemukan.'], 404);
        }

        $request->validate([
            'voucher_placement'              => 'nullable|array',
            'voucher_placement.header'       => 'nullable|integer',
            'voucher_placement.product_page' => 'nullable|integer',
            'voucher_placement.checkout'     => 'nullable|integer',
        ]);

        // Merge ke dalam appearance_data yang ada — jangan timpa widget lainnya
        $current = is_array($store->appearance_data) ? $store->appearance_data : [];
        $current['voucher_placement'] = $request->input('voucher_placement', []);
        $store->appearance_data = $current;
        $store->save();

        return response()->json(['success' => true, 'message' => 'Penempatan kupon berhasil disimpan.']);
    }
}
