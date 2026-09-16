<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property numeric $amount
 * @property numeric $fee_percentage
 * @property numeric $fee_amount
 * @property numeric $net_amount
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereFeeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereFeePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereNetAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PayoutRequest extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
