<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OnboardingController extends Controller
{
    private const RESERVED_SLUGS = [
        'admin','tenant','dashboard','about','projects','products','clients','cart','checkout',
        'payment','download','contact','help','terms','privacy','copyright','refund-policy',
        'login','register','logout','forgot-password','reset-password','email','storage','chat','toko',
        'onboarding','api','auth','notifications','up',
    ];

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

    /**
     * Cek ketersediaan URL profil saat pengguna mengetik.
     */
    public function checkSlug(Request $request): JsonResponse
    {
        $slug = Str::slug((string) $request->query('slug'));

        if ($slug === '') {
            return response()->json(['available' => false, 'slug' => '', 'message' => 'Isi URL dengan huruf atau angka.']);
        }

        if (in_array($slug, self::RESERVED_SLUGS, true)) {
            return response()->json(['available' => false, 'slug' => $slug, 'message' => 'URL ini tidak bisa digunakan. Coba nama lain.']);
        }

        if (! Store::where('slug', $slug)->exists()) {
            return response()->json(['available' => true, 'slug' => $slug, 'message' => 'URL tersedia.']);
        }

        $number = 2;
        while (Store::where('slug', "{$slug}-{$number}")->exists()) {
            $number++;
        }

        return response()->json([
            'available' => false,
            'slug' => $slug,
            'message' => 'URL ini sudah dipakai toko lain.',
            'suggestion' => "{$slug}-{$number}",
        ]);
    }

    public function saveStore(Request $request)
    {
        $user = Auth::user();
        if ($user->hasCompletedOnboarding() || $user->store()->exists()) {
            return redirect()->route('tenant.dashboard')->with('info', 'Anda sudah memiliki toko.');
        }
        $mode = session('onboarding_mode', 'store');

        // Foto disimpan lebih dulu agar tidak perlu dipilih ulang jika URL ternyata ditolak.
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'Format gambar harus JPG, PNG, GIF, atau WebP.',
            'logo.max'   => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($request->hasFile('logo')) {
            if ($previousLogo = session('onboarding_logo')) {
                Storage::disk('public')->delete($previousLogo);
            }
            session(['onboarding_logo' => $request->file('logo')->store('logos', 'public')]);
        }

        // Slug dinormalkan dulu supaya slug yang dibuat dari nama ikut dicek keunikannya.
        $slug = Str::slug($request->filled('slug') ? $request->slug : $request->name);
        $request->merge(['slug' => $slug]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['nullable','string','max:100',
                \Illuminate\Validation\Rule::notIn(self::RESERVED_SLUGS),
                \Illuminate\Validation\Rule::unique('stores','slug'),
            ],
        ], [
            'name.required'  => 'Nama toko wajib diisi.',
            'slug.unique'    => 'URL ini sudah dipakai toko lain. Coba nama lain.',
            'slug.not_in'    => 'URL ini tidak bisa digunakan. Coba nama lain.',
        ]);

        $logoPath = session()->pull('onboarding_logo');
        $finalSlug = $slug ?: 'toko-' . Str::lower(Str::random(6));

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
        // Toko baru saja dibuat di langkah sebelumnya: hasCompletedOnboarding() sudah bernilai true.
        if ($user->hasCompletedOnboarding() && !session('onboarding_store_id')) {
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
                ->with('success', 'Profilmu sudah siap! Silakan cek email untuk verifikasi agar bisa mengakses dashboard penuh.');
        }

        return redirect($user->startPageUrl())
            ->with('success', 'Selamat datang di Rhantech! Profilmu sudah siap.');
    }
}