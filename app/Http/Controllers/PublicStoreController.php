<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class PublicStoreController extends Controller
{
    /**
     * Display the public store profile page.
     */
    public function show(string $slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        
        // Assuming products relationship exists and we only want active ones
        $products = $store->products()->where('is_active', true)->paginate(12);

        // Fetch appearance settings
        $appearance = is_string($store->appearance_data) ? json_decode($store->appearance_data, true) : $store->appearance_data;
        if (!is_array($appearance)) {
            $appearance = [];
        }
        
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = $store->followers()->where('user_id', Auth::id())->exists();
        }
        
        // Fetch categories from products
        $categories = \App\Models\ProductCategory::whereHas('products', function($q) use ($store) {
            $q->where('store_id', $store->id)->where('is_active', true);
        })->get();
        
        return view('store.show', compact('store', 'products', 'appearance', 'isFollowing', 'categories'));
    }

    /**
     * Toggle follow status for the store.
     */
    public function toggleFollow(Store $store)
    {
        $user = Auth::user();
        
        if ($store->followers()->where('user_id', $user->id)->exists()) {
            $store->followers()->detach($user->id);
            return response()->json(['following' => false, 'message' => 'Berhenti mengikuti toko.']);
        } else {
            $store->followers()->attach($user->id);
            return response()->json(['following' => true, 'message' => 'Berhasil mengikuti toko.']);
        }
    }
}
