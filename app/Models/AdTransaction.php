<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property string $reference_no
 * @property string $type
 * @property numeric $amount
 * @property numeric $tax_amount
 * @property numeric $total_amount
 * @property string|null $payment_method
 * @property string $status
 * @property string|null $snap_token
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereReferenceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereSnapToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdTransaction whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
class AdTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'reference_no',
        'type',
        'amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'status',
        'snap_token',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
