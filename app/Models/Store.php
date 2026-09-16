<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string|null $logo
 * @property string|null $banner
 * @property string|null $description
 * @property string|null $address
 * @property string|null $maps_location
 * @property array<array-key, mixed>|null $social_links
 * @property numeric $balance
 * @property numeric $default_affiliate_commission
 * @property bool $is_pro
 * @property \Illuminate\Support\Carbon|null $pro_expires_at
 * @property string|null $pro_plan
 * @property numeric|null $custom_payout_fee_percentage
 * @property numeric $ad_balance
 * @property int $views
 * @property string|null $bank_account_info
 * @property array<array-key, mixed>|null $appearance_data
 * @property string $store_mode
 * @property string $status
 * @property string|null $ban_reason
 * @property \Illuminate\Support\Carbon|null $banned_at
 * @property int|null $banned_by
 * @property array<array-key, mixed>|null $profile_links
 * @property \Illuminate\Support\Carbon|null $terms_accepted_at
 * @property string|null $terms_accepted_ip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AdTransaction> $adTransactions
 * @property-read int|null $ad_transactions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerAd> $ads
 * @property-read int|null $ads_count
 * @property-read \App\Models\User|null $bannedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PayoutRequest> $payoutRequests
 * @property-read int|null $payout_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProSubscription> $proSubscriptions
 * @property-read int|null $pro_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $showcaseProducts
 * @property-read int|null $showcase_products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereAdBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereAppearanceData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBanReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBankAccountInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBannedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereCustomPayoutFeePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereDefaultAffiliateCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereIsPro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereMapsLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereProExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereProPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereProfileLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereSocialLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereStoreMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereTermsAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereTermsAcceptedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereViews($value)
 * @mixin \Eloquent
 */
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Project>
     */
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
