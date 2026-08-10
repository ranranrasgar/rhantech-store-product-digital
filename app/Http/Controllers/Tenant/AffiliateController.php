<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    public function index()
    {
        $store = Store::where('user_id', Auth::id())->first();

        if (!$store) {
            return redirect()->route('tenant.dashboard')->with('error', 'You do not have a store associated with your account.');
        }

        // Dummy data for affiliate marketplace
        $affiliates = [
            [
                'name' => 'rempahsosmed.etc',
                'handle' => 'rempahsosmed.etc',
                'avatar' => 'https://ui-avatars.com/api/?name=rempahsosmed&background=F59E0B&color=fff',
                'followers' => '10,4RB',
                'clicks' => '3RB',
                'orders' => '100-200',
                'sales' => '2JT - 10JT',
                'categories' => ['Komputer & Aksesoris', '+2'],
                'audience' => 'Laki-laki, Umur 23-32',
                'platform' => 'instagram'
            ],
            [
                'name' => 'Kere Tech',
                'handle' => 'tokohgaming',
                'avatar' => 'https://ui-avatars.com/api/?name=Kere+Tech&background=3B82F6&color=fff',
                'followers' => '982',
                'clicks' => '8RB',
                'orders' => '200-500',
                'sales' => '10JT - 50JT',
                'categories' => ['Komputer & Aksesoris', '+?'],
                'audience' => 'Laki-laki, Umur 23-32',
                'platform' => 'youtube'
            ],
            [
                'name' => 'clipper.produkterpercaya',
                'handle' => 'clipper.produk',
                'avatar' => 'https://ui-avatars.com/api/?name=Clipper&background=10B981&color=fff',
                'followers' => '47',
                'clicks' => '<1RB',
                'orders' => '50-100',
                'sales' => '2JT - 10JT',
                'categories' => ['Komputer & Aksesoris', '+2'],
                'audience' => 'Laki-laki, Umur 23-32',
                'platform' => 'tiktok'
            ],
            [
                'name' => 'Pammi pam',
                'handle' => 'fahmithaib98',
                'avatar' => 'https://ui-avatars.com/api/?name=Pammi+Pam&background=EF4444&color=fff',
                'followers' => '123',
                'clicks' => '<1RB',
                'orders' => '50-100',
                'sales' => '2JT - 10JT',
                'categories' => [],
                'audience' => '',
                'platform' => 'facebook'
            ]
        ];

        return view('tenant.affiliates.index', compact('store', 'affiliates'));
    }
}
