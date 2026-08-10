<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            'address' => 'nullable|string|max:500',
            'maps_location' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = auth()->user();
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'bank_account_info' => $request->bank_account_info,
            'address' => $request->address,
            'maps_location' => $request->maps_location,
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores', 'public');
            $data['logo'] = $logoPath;

            // Delete old logo if updating
            if ($user->store && $user->store->logo) {
                Storage::disk('public')->delete($user->store->logo);
            }
        }

        if ($user->store) {
            $user->store->update($data);
            $message = 'Profil toko berhasil diperbarui.';
        } else {
            $data['slug'] = Str::slug($request->name) . '-' . uniqid();
            $data['balance'] = 0;
            $user->store()->create($data);
            $message = 'Profil toko berhasil dibuat.';
        }

        return redirect()->back()->with('success', $message);
    }
}
