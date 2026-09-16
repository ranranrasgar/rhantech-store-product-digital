<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $store_id
 * @property int|null $product_id
 * @property string $name
 * @property string $type
 * @property string $budget_type
 * @property numeric|null $daily_budget
 * @property string $period_type
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property string $bidding_mode
 * @property numeric $bid_price
 * @property array<array-key, mixed>|null $target_keywords
 * @property string $display_mode
 * @property string $status
 * @property int $views_count
 * @property int $clicks_count
 * @property numeric $spent_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Store $store
 * @method static Builder<static>|SellerAd activeAndFunded()
 * @method static Builder<static>|SellerAd newModelQuery()
 * @method static Builder<static>|SellerAd newQuery()
 * @method static Builder<static>|SellerAd query()
 * @method static Builder<static>|SellerAd whereBidPrice($value)
 * @method static Builder<static>|SellerAd whereBiddingMode($value)
 * @method static Builder<static>|SellerAd whereBudgetType($value)
 * @method static Builder<static>|SellerAd whereClicksCount($value)
 * @method static Builder<static>|SellerAd whereCreatedAt($value)
 * @method static Builder<static>|SellerAd whereDailyBudget($value)
 * @method static Builder<static>|SellerAd whereDisplayMode($value)
 * @method static Builder<static>|SellerAd whereEndDate($value)
 * @method static Builder<static>|SellerAd whereId($value)
 * @method static Builder<static>|SellerAd whereName($value)
 * @method static Builder<static>|SellerAd wherePeriodType($value)
 * @method static Builder<static>|SellerAd whereProductId($value)
 * @method static Builder<static>|SellerAd whereSpentAmount($value)
 * @method static Builder<static>|SellerAd whereStartDate($value)
 * @method static Builder<static>|SellerAd whereStatus($value)
 * @method static Builder<static>|SellerAd whereStoreId($value)
 * @method static Builder<static>|SellerAd whereTargetKeywords($value)
 * @method static Builder<static>|SellerAd whereType($value)
 * @method static Builder<static>|SellerAd whereUpdatedAt($value)
 * @method static Builder<static>|SellerAd whereViewsCount($value)
 * @mixin \Eloquent
 */
class SellerAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'product_id',
        'name',
        'type',
        'budget_type',
        'daily_budget',
        'period_type',
        'start_date',
        'end_date',
        'bidding_mode',
        'bid_price',
        'target_keywords',
        'display_mode',
        'status',
        'views_count',
        'clicks_count',
        'spent_amount',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'target_keywords' => 'array',
        'daily_budget' => 'decimal:2',
        'bid_price' => 'decimal:2',
        'spent_amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope for active ads that belong to stores with positive ad_balance
     */
    public function scopeActiveAndFunded(Builder $query)
    {
        return $query->where('status', 'active')
            ->whereHas('store', function ($q) {
                $q->where('ad_balance', '>', 0);
            })
            ->where(function ($q) {
                $q->where('period_type', 'unlimited')
                  ->orWhere(function ($sub) {
                      $sub->where('period_type', 'custom')
                          ->where(function ($dateQuery) {
                              $dateQuery->whereNull('start_date')
                                        ->orWhere('start_date', '<=', now());
                          })
                          ->where(function ($dateQuery) {
                              $dateQuery->whereNull('end_date')
                                        ->orWhere('end_date', '>=', now());
                          });
                  });
            });
    }
}
