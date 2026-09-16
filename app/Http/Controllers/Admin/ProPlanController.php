<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProPlanController extends Controller
{
    public function index(Request $request)
    {
        $plans = ProPlan::orderBy('sort_order')->get();

        $subQuery = \App\Models\ProSubscription::with(['store.user', 'user']);

        if ($request->filled('search_sub')) {
            $s = trim($request->input('search_sub'));
            $subQuery->where(function ($q) use ($s) {
                $q->where('reference_no', 'like', "%{$s}%")
                  ->orWhere('plan', 'like', "%{$s}%")
                  ->orWhere('payment_method', 'like', "%{$s}%")
                  ->orWhereHas('store', function ($sq) use ($s) {
                      $sq->where('name', 'like', "%{$s}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%");
                  });
            });
        }

        if ($request->filled('status_sub') && $request->input('status_sub') !== 'all') {
            $subQuery->where('payment_status', $request->input('status_sub'));
        }

        $subscriptions = $subQuery->latest()->paginate(15, ['*'], 'sub_page')->withQueryString();

        $totalProRevenue = \App\Models\ProSubscription::where('payment_status', 'paid')->sum('amount');
        $totalPaidSubCount = \App\Models\ProSubscription::where('payment_status', 'paid')->count();
        $activeProStoresCount = \App\Models\Store::where('is_pro', true)->count();

        return view('admin.pro_plans.index', compact(
            'plans', 
            'subscriptions', 
            'totalProRevenue', 
            'totalPaidSubCount', 
            'activeProStoresCount'
        ));
    }

    public function create()
    {
        return view('admin.pro_plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:50|unique:pro_plans,slug',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'duration_label' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ProPlan::create($validated);

        return redirect()->route('admin.pro_plans.index')->with('success', 'Paket Toko PRO berhasil ditambahkan.');
    }

    public function edit(ProPlan $proPlan)
    {
        return view('admin.pro_plans.edit', compact('proPlan'));
    }

    public function update(Request $request, ProPlan $proPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:50|unique:pro_plans,slug,' . $proPlan->id,
            'price' => 'required|numeric|min:0',
            'duration_days' => 'nullable|integer|min:1',
            'duration_label' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_popular'] = $request->has('is_popular');
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $proPlan->update($validated);

        return redirect()->route('admin.pro_plans.index')->with('success', 'Paket Toko PRO berhasil diperbarui.');
    }

    public function toggleActive(ProPlan $proPlan)
    {
        $proPlan->update([
            'is_active' => !$proPlan->is_active,
        ]);

        $statusText = $proPlan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.pro_plans.index')->with('success', "Paket {$proPlan->name} berhasil {$statusText}.");
    }

    public function destroy(ProPlan $proPlan)
    {
        $proPlan->delete();
        return redirect()->route('admin.pro_plans.index')->with('success', 'Paket Toko PRO berhasil dihapus.');
    }
}
