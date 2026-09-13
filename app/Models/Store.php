<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'appearance_data' => 'array',
        'social_links' => 'array',
        'profile_links' => 'array',
        'terms_accepted_at' => 'datetime',
        'is_pro' => 'boolean',
        'pro_expires_at' => 'datetime',
        'banned_at' => 'datetime',
        'custom_payout_fee_percentage' => 'decimal:2',
    ];

    /**
     * Check if store is currently active
     */
    public function isActive(): bool
    {
        return ($this->status ?? 'active') === 'active';
    }

    /**
     * Check if store is suspended
     */
    public function isSuspended(): bool
    {
        return ($this->status ?? 'active') === 'suspended';
    }

    /**
     * Check if store is banned
     */
    public function isBanned(): bool
    {
        return ($this->status ?? 'active') === 'banned';
    }

    /**
     * Check if store has active Pro status
     */
    public function isPro(): bool
    {
        if (!$this->is_pro) {
            return false;
        }

        // If pro_expires_at is null, treat as lifetime Pro
        if ($this->pro_expires_at === null) {
            return true;
        }

        return $this->pro_expires_at->isFuture();
    }

    /**
     * Get withdrawal fee percentage (1% for Pro, 2.5% for regular, or admin custom)
     */
    public function getPayoutFeePercentage(): float
    {
        if ($this->custom_payout_fee_percentage !== null) {
            return (float) $this->custom_payout_fee_percentage;
        }

        return $this->isPro() ? 1.00 : 2.50;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function proSubscriptions()
    {
        return $this->hasMany(ProSubscription::class);
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

    public function bannedBy()
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
}
