<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'download_links' => 'array',
        'highlights' => 'array',
        'package_includes' => 'array',
        'system_requirements' => 'array',
        'guarantees' => 'array',
        'faqs' => 'array',
    ];

    /**
     * Parse comma-separated tags into a clean array
     */
    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode(',', $this->tags))));
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function showcases()
    {
        return $this->belongsToMany(Store::class, 'store_showcase_products', 'product_id', 'store_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function helpCategory()
    {
        return $this->belongsTo(HelpCategory::class, 'help_category_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_visible', true)->latest();
    }

    public function scopeApproved($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('products.store_id')
              ->orWhere('products.approval_status', 'approved');
        });
    }

    public function scopePublished($query)
    {
        return $query->where('products.is_active', true)
            ->where(function ($q) {
                $q->whereNull('products.store_id')
                  ->orWhere('products.approval_status', 'approved');
            });
    }

    public function isApproved(): bool
    {
        return empty($this->store_id) || $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return !empty($this->store_id) && $this->approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return !empty($this->store_id) && $this->approval_status === 'rejected';
    }

    /**
     * Rating efektif terpadu (sinkron di semua halaman)
     */
    public function getEffectiveRatingAttribute(): float
    {
        if (!empty($this->rating_override) && (float)$this->rating_override > 0) {
            return round((float)$this->rating_override, 1);
        }

        if ($this->relationLoaded('reviews')) {
            $avg = $this->reviews->avg('rating');
            if ($avg) return round((float)$avg, 1);
        } else {
            $avg = $this->reviews()->avg('rating');
            if ($avg) return round((float)$avg, 1);
        }

        return 0.0;
    }

    /**
     * Jumlah ulasan efektif terpadu (sinkron di semua halaman)
     */
    public function getEffectiveReviewsCountAttribute(): int
    {
        if (!is_null($this->reviews_count) && (int)$this->reviews_count > 0) {
            return (int)$this->reviews_count;
        }

        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }

        return $this->reviews()->count();
    }

    public function ads()
    {
        return $this->hasMany(SellerAd::class);
    }

    public function activeAd()
    {
        return $this->hasOne(SellerAd::class)
            ->where('status', 'active')
            ->whereHas('store', function ($q) {
                $q->where('ad_balance', '>', 0);
            });
    }

    /**
     * Cari campaign diskon langsung yang sedang aktif untuk produk ini
     */
    public function getActiveDiscountCampaignAttribute()
    {
        $storeId = $this->store_id;
        $campaigns = \App\Models\Campaign::active()
            ->where('type', 'discount')
            ->where(function ($query) use ($storeId) {
                if ($storeId) {
                    $query->where('store_id', $storeId)->orWhereNull('store_id');
                } else {
                    $query->whereNull('store_id');
                }
            })
            ->get();
            
        foreach ($campaigns as $campaign) {
            if ($campaign->applies_to === 'all') return $campaign;
            if ($campaign->applies_to === 'product' && is_array($campaign->product_ids) && in_array($this->id, $campaign->product_ids)) return $campaign;
            if ($campaign->applies_to === 'category' && is_array($campaign->category_ids) && in_array($this->product_category_id, $campaign->category_ids)) return $campaign;
        }
        
        return null;
    }

    /**
     * Dapatkan harga diskon terbaik (dari campaign aktif atau manual discount_price)
     */
    public function getDiscountPriceAttribute($value)
    {
        $campaign = $this->active_discount_campaign;
        
        $campaignDiscountPrice = null;
        if ($campaign) {
            $eligibleTotal = (float) $this->price;
            if ($campaign->discount_type === 'percentage') {
                $pct = (float) $campaign->discount_value;
                if ($pct >= 100) {
                    $discount = $eligibleTotal;
                } else {
                    $discount = ($eligibleTotal * $pct) / 100;
                }
            } else {
                $discount = min($eligibleTotal, (float) $campaign->discount_value);
            }
            $campaignDiscountPrice = max(0, $eligibleTotal - $discount);
        }

        $manualDiscountPrice = ($value && $value > 0 && $value < $this->price) ? (float) $value : null;

        if ($campaignDiscountPrice !== null && $manualDiscountPrice !== null) {
            return min($campaignDiscountPrice, $manualDiscountPrice);
        }

        return $campaignDiscountPrice ?? $manualDiscountPrice;
    }
}
