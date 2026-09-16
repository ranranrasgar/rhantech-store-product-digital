<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $store_id
 * @property int|null $user_id
 * @property int|null $affiliate_store_id
 * @property string $name
 * @property string $handle
 * @property string|null $whatsapp
 * @property string|null $referral_code
 * @property numeric $commission_rate Persentase komisi %
 * @property string|null $avatar_url
 * @property string|null $followers_count
 * @property string|null $clicks_count
 * @property string|null $orders_count
 * @property string|null $sales_range
 * @property string|null $audience_demographic
 * @property string|null $platform
 * @property array<array-key, mixed>|null $categories
 * @property bool $is_golden_tick
 * @property bool $is_active
 * @property bool $is_good_sample_completion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store|null $affiliateStore
 * @property-read \App\Models\Store|null $store
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\AffiliateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereAffiliateStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereAudienceDemographic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereClicksCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIsGoldenTick($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIsGoodSampleCompletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereOrdersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereReferralCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereSalesRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereWhatsapp($value)
 * @mixin \Eloquent
 */
class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'affiliate_store_id',
        'name',
        'handle',
        'whatsapp',
        'referral_code',
        'commission_rate',
        'avatar_url',
        'followers_count',
        'clicks_count',
        'orders_count',
        'sales_range',
        'audience_demographic',
        'platform',
        'categories',
        'is_golden_tick',
        'is_good_sample_completion',
        'is_active',
    ];

    protected $casts = [
        'categories' => 'array',
        'is_golden_tick' => 'boolean',
        'is_good_sample_completion' => 'boolean',
        'is_active' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function affiliateStore()
    {
        return $this->belongsTo(Store::class, 'affiliate_store_id');
    }
}
