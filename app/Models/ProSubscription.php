<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property int $user_id
 * @property string $reference_no
 * @property string $plan
 * @property numeric $amount
 * @property string|null $payment_method
 * @property string $payment_status
 * @property string|null $snap_token
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereReferenceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereSnapToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProSubscription whereUserId($value)
 * @mixin \Eloquent
 */
class ProSubscription extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'paid_at' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
