<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('tenant.dashboard');
        }
        return view('onboarding.index', compact('user'));
    }

    public function saveMode(Request $request)
    {
        $request->validate(['store_mode' => 'required|in:store,profile,hybrid']);
        session(['onboarding_mode' => $request->store_mode]);
        return redirect()->route('onboarding.setup');
    }

    public function setup()
    {
        $user = Auth::user();
        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('tenant.dashboard');
        }
        $mode = session('onboarding_mode', 'store');
        return view('onboarding.setup', compact('user', 'mode'));
    }

    public function saveStore(Request $request)
    {
        $user = Auth::user();
        $mode = session('onboarding_mode', 'store');

        $reservedSlugs = [
            'admin','tenant','dashboard','projects','products','clients','cart','checkout',
            'payment','download','contact','help','terms','privacy','copyright','refund-policy',
            'login','register','logout','forgot-password','reset-password','email','storage','chat','toko','onboarding',
        ];

        $rawSlug = $request->filled('slug') ? $request->slug : $request->name;
        $slug = Str::slug($rawSlug);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:100','regex:/^[a-zA-Z0-9\-_]+$/',
                \Illuminate\Validation\Rule::notIn($reservedSlugs),
                \Illuminate\Validation\Rule::unique('stores','slug'),
            ],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'name.required'  => 'Nama toko wajib diisi.',
            'slug.unique'    => 'URL ini sudah dipakai toko lain. Coba nama lain.',
            'slug.not_in'    => 'URL ini tidak bisa digunakan. Coba nama lain.',
            'slug.regex'     => 'URL hanya boleh huruf, angka, dan tanda hubung.',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $finalSlug = $slug ?: Str::slug($request->name) . '-' . Str::random(4);

        $store = $user->store()->create([
            'name'               => $request->name,
            'slug'               => $finalSlug,
            'store_mode'         => $mode,
            'logo'               => $logoPath,
            'description'        => $request->description,
            'status'             => 'active',
            'terms_accepted_at'  => now(),
            'terms_accepted_ip'  => $request->ip(),
        ]);

        session(['onboarding_store_id' => $store->id]);

        if ($mode === 'profile') {
            return redirect()->route('onboarding.complete');
        }

        return redirect()->route('onboarding.product');
    }

    public function product()
    {
        $user = Auth::user();
        if ($user->hasCompletedOnboarding()) {
            return redirect()->route('tenant.dashboard');
        }
        $store = $user->store;
        if (!$store) {
            return redirect()->route('onboarding.setup');
        }
        $categories = \App\Models\ProductCategory::orderBy('name')->get();
        $mode = session('onboarding_mode', 'store');
        return view('onboarding.product', compact('user', 'store', 'categories', 'mode'));
    }

    public function saveProduct(Request $request)
    {
        $user = Auth::user();
        $store = $user->store;

        if ($store && $request->filled('name')) {
            $request->validate([
                'name'                => 'required|string|max:255',
                'price'               => 'required|numeric|min:0',
                'product_category_id' => 'nullable|exists:product_categories,id',
                'description'         => 'nullable|string',
                'image'               => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            $product = $store->products()->create([
                'name'                => $request->name,
                'slug'                => Str::slug($request->name) . '-' . Str::random(4),
                'price'               => $request->price,
                'product_category_id' => $request->product_category_id,
                'description'         => $request->description ?? '',
                'is_active'           => false,
                'status'              => 'pending',
            ]);

            if ($request->hasFile('image')) {
                $imgPath = $request->file('image')->store('products', 'public');
                $product->images()->create(['image_path' => $imgPath, 'is_main' => true]);
            }
        }

        return redirect()->route('onboarding.complete');
    }

    public function complete()
    {
        $user = Auth::user();
        $user->update(['onboarding_completed_at' => now()]);
        session()->forget(['onboarding_mode', 'onboarding_store_id']);

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('success', '🎉 Profilmu sudah siap! Silakan cek email untuk verifikasi agar bisa mengakses dashboard penuh.');
        }

        return redirect()->route('tenant.dashboard')
            ->with('success', '🎉 Selamat datang di Rhantech! Profilmu sudah siap.');
    }
}