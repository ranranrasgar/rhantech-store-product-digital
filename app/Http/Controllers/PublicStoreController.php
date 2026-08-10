<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class PublicStoreController extends Controller
{
    /**
     * Display the public store profile page.
     */
    public function show($slug)
    {
        $store = Store::where('slug', $slug)->firstOrFail();
        
        // Assuming products relationship exists and we only want active ones
        $products = $store->products()->where('is_active', true)->paginate(12);

        // Fetch appearance settings if any exist (Model not created yet, use mock data)
        $appearance = null;
        // For now, we will construct dummy data or parse existing
        
        return view('store.show', compact('store', 'products', 'appearance'));
    }
}
