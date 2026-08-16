<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    /**
     * Display a listing of the bank accounts.
     */
    public function index()
    {
        $store = Auth::user()->store;
        
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        return view('tenant.bank.index', compact('store'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_holder' => 'required|string|max:150',
        ]);

        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'Toko belum terhubung.');
        }

        $formatted = "Bank: {$request->bank_name}\nNo. Rekening: {$request->account_number}\nAtas Nama: {$request->account_holder}";
        $store->update([
            'bank_account_info' => $formatted
        ]);

        return redirect()->route('tenant.bank.index')->with('success', 'Rekening bank pencairan berhasil disimpan.');
    }
}
