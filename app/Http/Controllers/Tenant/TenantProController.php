<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

class TenantProController extends Controller
{
    public function index(Request $request)
    {
        $store = $request->user()->store;
        
        if (!$store) {
            return redirect()->route('tenant.store.index')->with('error', 'Buat toko terlebih dahulu.');
        }

        return view('tenant.pro.index', compact('store'));
    }
}
