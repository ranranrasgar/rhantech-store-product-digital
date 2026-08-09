<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;
        return view('tenant.store.index', compact('store'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bank_account_info' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        if ($user->store) {
            $user->store->update([
                'name' => $request->name,
                'description' => $request->description,
                'bank_account_info' => $request->bank_account_info,
            ]);
            $message = 'Store updated successfully.';
        } else {
            $user->store()->create([
                'name' => $request->name,
                'slug' => \Str::slug($request->name) . '-' . uniqid(),
                'description' => $request->description,
                'bank_account_info' => $request->bank_account_info,
                'balance' => 0,
            ]);
            $message = 'Store created successfully.';
        }

        return redirect()->back()->with('success', $message);
    }
}
