<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property string $name
 * @property string $type
 * @property string $applies_to
 * @property array<array-key, mixed>|null $category_ids
 * @property array<array-key, mixed>|null $product_ids
 * @property string $discount_type
 * @property numeric $discount_value
 * @property string|null $code
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $status
 * @property numeric $minimum_spend
 * @property int|null $usage_limit
 * @property int $used_count
 * @property string|null $description
 * @property string|null $color Warna tema voucher: orange, red, blue, green, purple, pink, teal, indigo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereAppliesTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCategoryIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereMinimumSpend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereProductIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUsedCount($value)
 * @mixin \Eloquent
 */
class Campaign extends Model
{
    //
    protected $fillable = [
        'store_id', 'name', 'type', 'applies_to', 'category_ids', 'product_ids',
        'discount_type', 'discount_value', 
        'code', 'start_date', 'end_date', 'status', 'minimum_spend', 
        'usage_limit', 'used_count', 'description', 'color'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_value' => 'decimal:2',
        'minimum_spend' => 'decimal:2',
        'used_count' => 'integer',
        'category_ids' => 'array',
        'product_ids' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActive($query)
    {
        return $query->where('campaigns.status', 'active')
            ->where(function ($q) {
                $q->whereNull('campaigns.start_date')
                  ->orWhere('campaigns.start_date', '<=', now()->addMinutes(5));
            })
            ->where(function ($q) {
                $q->whereNull('campaigns.end_date')
                  ->orWhere('campaigns.end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('campaigns.usage_limit')
                  ->orWhereColumn('campaigns.used_count', '<', 'campaigns.usage_limit');
            });
    }

    /**
     * Hitung diskon yang didapat dari keranjang belanja
     */
    public function calculateDiscount(mixed $cart): float
    {
        $eligibleTotal = 0;
        if (!is_iterable($cart)) {
            return 0.0;
        }

        foreach ($cart as $productId => $item) {
            $isEligible = false;

            if ($this->applies_to === 'all') {
                $isEligible = true;
            } elseif ($this->applies_to === 'product') {
                $isEligible = in_array($productId, (array) ($this->product_ids ?? []));
            } elseif ($this->applies_to === 'category') {
                $product = Product::find($productId);
                if ($product && in_array($product->product_category_id, (array) ($this->category_ids ?? []))) {
                    $isEligible = true;
                }
            }

            if ($isEligible) {
                $price = is_array($item) ? ($item['price'] ?? 0) : ($item->price ?? 0);
                $qty = is_array($item) ? ($item['quantity'] ?? 1) : ($item->quantity ?? 1);
                $eligibleTotal += ((float) $price * (int) $qty);
            }
        }

        if ($eligibleTotal <= 0) {
            return 0.0;
        }

        if ($this->discount_type === 'percentage') {
            $pct = (float) $this->discount_value;
            if ($pct >= 100) {
                $discount = $eligibleTotal;
            } else {
                $discount = ($eligibleTotal * $pct) / 100;
            }
        } else {
            $discount = min($eligibleTotal, (float) $this->discount_value);
        }

        return round((float) $discount, 2);
    }

    /**
     * Cek apakah voucher ini menghasilkan diskon 100% / Gratis
     */
    public function isFree(): bool
    {
        return ($this->discount_type === 'percentage' && (float)$this->discount_value >= 100);
    }
}
