<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'handle',
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
    ];

    protected $casts = [
        'categories' => 'array',
        'is_golden_tick' => 'boolean',
        'is_good_sample_completion' => 'boolean',
    ];
}
