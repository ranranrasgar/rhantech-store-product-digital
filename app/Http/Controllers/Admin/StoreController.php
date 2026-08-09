<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = \App\Models\Store::with('user')->latest()->paginate(20);
        return view('admin.stores.index', compact('stores'));
    }
}
