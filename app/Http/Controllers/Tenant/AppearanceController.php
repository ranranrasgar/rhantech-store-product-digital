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
        ]);

        $store->appearance_data = $request->input('components');
        $store->save();

        return response()->json([
            'success' => true,
            'message' => 'Dekorasi toko berhasil disimpan.'
        ]);
    }
}
