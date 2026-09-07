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
        $user = Auth::user();
        $store = $user->store;

        $reservedSlugs = [
            'admin', 'tenant', 'dashboard', 'projects', 'products', 'clients', 'cart', 'checkout', 
            'payment', 'download', 'contact', 'help', 'login', 'register', 'logout', 
            'forgot-password', 'reset-password', 'email', 'storage', 'chat', 'toko'
        ];

        // Format slug dari input atau nama toko
        $rawSlug = $request->filled('slug') ? $request->slug : $request->name;
        $slug = Str::slug($rawSlug);

        $rules = [
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z0-9\-_]+$/',
                \Illuminate\Validation\Rule::notIn($reservedSlugs),
                \Illuminate\Validation\Rule::unique('stores', 'slug')->ignore($store?->id),
            ],
            'description' => 'nullable|string',
            'bank_account_info' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'maps_location' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'slug.unique' => 'URL / Slug toko ini sudah digunakan oleh toko lain. Silakan pilih nama slug lain.',
            'slug.not_in' => 'URL / Slug ini merupakan kata kunci sistem dan tidak boleh digunakan.',
            'slug.regex' => 'URL / Slug hanya boleh berisi huruf, angka, dan tanda strip (-).',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 2 MB.',
            'logo.image' => 'File harus berupa gambar.',
        ];

        $request->validate($rules, $messages);

        // Otomatis tentukan lokasi Google Maps untuk Superadmin
        $mapsLocation = $store->maps_location ?? null;

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
            'slug' => $slug,
            'description' => $request->description,
            'bank_account_info' => $request->bank_account_info,
            'address' => $request->address,
            'maps_location' => $mapsLocation,
        ];

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('stores', 'public');
            $data['logo'] = $logoPath;

            // Delete old logo if updating
            if ($store && $store->logo) {
                Storage::disk('public')->delete($store->logo);
            }
        }

        if ($store) {
            $store->update($data);
            $message = 'Profil toko berhasil diperbarui.';
        } else {
            $data['balance'] = 0;
            $user->store()->create($data);
            $message = 'Profil toko berhasil dibuat.';
        }

        return redirect()->back()->with('success', $message);
    }
}
