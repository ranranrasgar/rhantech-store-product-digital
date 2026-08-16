<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        $store = Auth::user()->store;
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

        $user = Auth::user();

        // Otomatis tentukan lokasi Google Maps untuk Superadmin
        $mapsLocation = $user->store->maps_location ?? null;

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $mapsLocation = "https://www.google.com/maps?q={$lat},{$lng}";
        } elseif ($request->filled('address')) {
            $encodedAddress = urlencode($request->address);
            $mapsLocation = "https://www.google.com/maps/search/?api=1&query={$encodedAddress}";
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'bank_account_info' => $request->bank_account_info,
            'address' => $request->address,
            'maps_location' => $mapsLocation,
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
