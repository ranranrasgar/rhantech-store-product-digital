<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
