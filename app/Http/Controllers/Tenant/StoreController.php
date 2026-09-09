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
            'payment', 'download', 'contact', 'help', 'terms', 'privacy', 'copyright', 'refund-policy',
            'login', 'register', 'logout', 'forgot-password', 'reset-password', 'email', 'storage', 'chat', 'toko'
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
            'social_links' => 'nullable|array',
        ];

        // Saat baru pertama kali buka toko, WAJIB menyetujui Kontrak Elektronik PMSE & Hak Cipta
        if (!$store) {
            $rules['agree_terms'] = 'accepted';
        }

        $messages = [
            'slug.unique' => 'URL / Slug toko ini sudah digunakan oleh toko lain. Silakan pilih nama slug lain.',
            'slug.not_in' => 'URL / Slug ini merupakan kata kunci sistem dan tidak boleh digunakan.',
            'slug.regex' => 'URL / Slug hanya boleh berisi huruf, angka, dan tanda strip (-).',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 2 MB.',
            'logo.image' => 'File harus berupa gambar.',
            'agree_terms.accepted' => 'Anda wajib membaca dan menyetujui Syarat & Ketentuan Layanan, Kebijakan Hak Cipta & Regulasi RI untuk dapat membuka toko.',
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

        // Format and clean social links
        $socialLinks = [];
        if ($request->has('social_links') && is_array($request->social_links)) {
            foreach ($request->social_links as $item) {
                if (is_array($item) && !empty(trim($item['url'] ?? ''))) {
                    $platform = trim($item['platform'] ?? 'custom');
                    $name = !empty(trim($item['name'] ?? '')) ? trim($item['name']) : ucfirst($platform);
                    $url = trim($item['url']);
                    if ($platform === 'whatsapp' && !str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $cleanPhone = preg_replace('/[^0-9]/', '', $url);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $url = "https://wa.me/{$cleanPhone}";
                    } elseif (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $url = 'https://' . $url;
                    }

                    $socialLinks[] = [
                        'platform' => $platform,
                        'name' => $name,
                        'url' => $url,
                    ];
                }
            }
        }

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'bank_account_info' => $request->bank_account_info,
            'address' => $request->address,
            'maps_location' => $mapsLocation,
            'social_links' => $socialLinks,
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
            return redirect()->back()->with('success', $message);
        } else {
            $data['balance'] = 0;
            $data['ad_balance'] = 0;
            $data['terms_accepted_at'] = now();
            $data['terms_accepted_ip'] = $request->ip();
            $newStore = $user->store()->create($data);

            $message = '🎉 Selamat! Toko "' . $newStore->name . '" berhasil dibuka! Buka menu Iklan Toko & Promosi untuk mengklaim Bonus Saldo Iklan Rp500.000!';
            return redirect()->route('tenant.dashboard')->with('success', $message);
        }
    }
}
