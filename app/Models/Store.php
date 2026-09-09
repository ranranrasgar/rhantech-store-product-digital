<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'appearance_data' => 'array',
        'social_links' => 'array',
        'terms_accepted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function payoutRequests()
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'store_id', 'user_id')->withTimestamps();
    }

    public function showcaseProducts()
    {
        return $this->belongsToMany(Product::class, 'store_showcase_products', 'store_id', 'product_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function ads()
    {
        return $this->hasMany(SellerAd::class);
    }

    public function adTransactions()
    {
        return $this->hasMany(AdTransaction::class);
    }
}
