<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    public function index(Request $request)
    {
        $store = Store::where('user_id', Auth::id())->first();

        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        $query = \App\Models\Affiliate::where(function($q) use ($store) {
            $q->where('store_id', $store->id)
              ->orWhereNull('store_id');
        });

        // 1. Search Filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('handle', 'like', '%' . $request->search . '%')
                  ->orWhere('referral_code', 'like', '%' . $request->search . '%');
            });
        }

        // 2. Platform Filter
        if ($request->filled('platform') && $request->platform !== 'Semua') {
            $query->where('platform', strtolower($request->platform));
        }

        $affiliates = $query->with('affiliateStore')->latest()->paginate(20)->withQueryString();
        $categories = \App\Models\ProductCategory::pluck('name');

        return view('tenant.affiliates.index', compact('store', 'affiliates', 'categories'));
    }

    public function create()
    {
        $currentUserId = Auth::id();
        
        // Ambil semua akun pengguna terdaftar di platform (selain akun sendiri)
        $users = \App\Models\User::where('id', '!=', $currentUserId)
            ->with('store')
            ->orderBy('name')
            ->get();

        return view('tenant.affiliates.create', compact('users'));
    }

    public function store(Request $request)
    {
        $currentStore = Store::where('user_id', Auth::id())->first();

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $targetUser = \App\Models\User::with('store')->findOrFail($validated['user_id']);
        $hasStore = $targetUser->store;

        // Otomatis buat Kode Referral berdasarkan nama akun user
        $rawName = $targetUser->name;
        $cleanBase = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $rawName));
        $cleanBase = substr($cleanBase, 0, 10);
        if (empty($cleanBase)) {
            $cleanBase = 'USER' . $targetUser->id;
        }

        $referralCode = $cleanBase;
        $counter = 1;
        while (\App\Models\Affiliate::where('referral_code', $referralCode)->exists()) {
            $referralCode = $cleanBase . rand(10, 99);
            $counter++;
            if ($counter > 5) {
                $referralCode = $cleanBase . rand(100, 999);
                break;
            }
        }
        $validated['referral_code'] = $referralCode;

        $validated['store_id'] = $currentStore ? $currentStore->id : null;
        $validated['affiliate_store_id'] = $hasStore ? $hasStore->id : null;
        $validated['name'] = $targetUser->name . ($hasStore ? ' (' . $hasStore->name . ')' : '');
        $validated['handle'] = $hasStore ? ('@' . $hasStore->slug) : ('@' . \Illuminate\Support\Str::slug($targetUser->name));
        $validated['whatsapp'] = $targetUser->phone ?? ($hasStore ? $hasStore->phone : null);
        $validated['avatar_url'] = $targetUser->avatar ?? ($hasStore ? $hasStore->logo : null);
        $validated['platform'] = $hasStore ? 'Toko & Akun Terdaftar' : 'Akun Terdaftar';
        $validated['followers_count'] = '-';
        $validated['clicks_count'] = '0';
        $validated['orders_count'] = '0';
        $validated['sales_range'] = 'Rp 0';
        $validated['is_golden_tick'] = true;
        $validated['is_active'] = true;

        \App\Models\Affiliate::create($validated);

        return redirect()->route('tenant.affiliates.index')->with('success', 'Akun ' . $targetUser->name . ' berhasil dihubungkan sebagai affiliate!');
    }

    public function edit(\App\Models\Affiliate $affiliate)
    {
        return view('tenant.affiliates.edit', compact('affiliate'));
    }

    public function update(Request $request, \App\Models\Affiliate $affiliate)
    {
        $validated = $request->validate([
            'referral_code' => 'required|string|max:50|unique:affiliates,referral_code,' . $affiliate->id,
            'commission_rate' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['referral_code'] = strtoupper(preg_replace('/[^A-Za-z0-9_-]/', '', $validated['referral_code']));
        $validated['is_active'] = $request->has('is_active');

        $affiliate->update($validated);

        return redirect()->route('tenant.affiliates.index')->with('success', 'Data komisi & kode referral berhasil diperbarui.');
    }

    public function updateDefaultCommission(Request $request)
    {
        $store = Store::where('user_id', Auth::id())->firstOrFail();
        $validated = $request->validate([
            'default_affiliate_commission' => 'required|numeric|min:0|max:100',
        ]);

        $newRate = $validated['default_affiliate_commission'];

        $store->update([
            'default_affiliate_commission' => $newRate,
        ]);

        // Otomatis sinkronkan juga komisi semua mitra toko ini agar selalu seragam dan tidak membingungkan
        \App\Models\Affiliate::where('store_id', $store->id)->update([
            'commission_rate' => $newRate,
        ]);

        return back()->with('success', "Komisi toko berhasil diubah menjadi {$newRate}%, dan komisi seluruh mitra otomatis diperbarui!");
    }

    public function destroy(\App\Models\Affiliate $affiliate)
    {
        $affiliate->delete();
        return redirect()->route('tenant.affiliates.index')->with('success', 'Affiliate berhasil dihapus.');
    }
}
