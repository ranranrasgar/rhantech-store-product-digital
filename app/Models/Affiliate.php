<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
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
}
