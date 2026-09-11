<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
