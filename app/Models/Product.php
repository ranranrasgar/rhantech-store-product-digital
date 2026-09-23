<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $store_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $short_description
 * @property string|null $tags
 * @property int|null $product_category_id
 * @property int|null $product_type_id
 * @property int|null $help_category_id
 * @property string|null $demo_url
 * @property numeric $price
 * @property numeric $affiliate_commission_rate
 * @property int $is_affiliate_enabled
 * @property numeric|null $discount_price
 * @property string|null $file_path
 * @property array<array-key, mixed>|null $download_links
 * @property array<array-key, mixed>|null $highlights
 * @property array<array-key, mixed>|null $package_includes
 * @property array<array-key, mixed>|null $system_requirements
 * @property array<array-key, mixed>|null $guarantees
 * @property array<array-key, mixed>|null $faqs
 * @property numeric|null $rating_override
 * @property-read int|null $reviews_count
 * @property int|null $sales_count
 * @property int $is_active
 * @property string $approval_status
 * @property string|null $rejection_reason
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SellerAd|null $activeAd
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellerAd> $ads
 * @property-read int|null $ads_count
 * @property-read \App\Models\ProductCategory|null $category
 * @property-read mixed $active_discount_campaign
 * @property-read float $effective_rating
 * @property-read int $effective_reviews_count
 * @property-read array $tags_array
 * @property-read \App\Models\HelpCategory|null $helpCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductReview> $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Store> $showcases
 * @property-read int|null $showcases_count
 * @property-read \App\Models\Store|null $store
 * @property-read \App\Models\ProductType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereAffiliateCommissionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereApprovalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDemoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDownloadLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFaqs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereGuarantees($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHelpCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereHighlights($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsAffiliateEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePackageIncludes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereRatingOverride($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereReviewsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSalesCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSystemRequirements($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereViews($value)
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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

    public function scopeApproved(Builder $query)
    {
        return $query->where(function ($q) {
            $q->whereNull('products.store_id')
              ->orWhere('products.approval_status', 'approved');
        });
    }

    public function scopePublished(Builder $query)
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

        if (isset($this->attributes['reviews_avg_rating']) && !is_null($this->attributes['reviews_avg_rating'])) {
            return round((float)$this->attributes['reviews_avg_rating'], 1);
        }

        if ($this->relationLoaded('reviews')) {
            $avg = $this->reviews->where('is_visible', true)->avg('rating');
            if ($avg) return round((float)$avg, 1);
        } else {
            $avg = $this->reviews()->where('is_visible', true)->avg('rating');
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

        if (isset($this->attributes['reviews_count'])) {
            return (int)$this->attributes['reviews_count'];
        }

        if ($this->relationLoaded('reviews')) {
            return $this->reviews->where('is_visible', true)->count();
        }

        return $this->reviews()->where('is_visible', true)->count();
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
        static $cachedDiscountCampaigns = null;
        if ($cachedDiscountCampaigns === null) {
            $cachedDiscountCampaigns = \App\Models\Campaign::active()
                ->where('type', 'discount')
                ->get();
        }

        $storeId = $this->store_id;
        $campaigns = $cachedDiscountCampaigns->filter(function ($c) use ($storeId) {
            return is_null($c->store_id) || ($storeId && $c->store_id == $storeId);
        });
            
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
    public function getDiscountPriceAttribute(mixed $value)
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
