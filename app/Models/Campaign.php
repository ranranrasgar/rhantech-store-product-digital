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
        'usage_limit', 'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_value' => 'decimal:2',
        'minimum_spend' => 'decimal:2',
        'category_ids' => 'array',
        'product_ids' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
