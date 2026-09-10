<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProPlan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Parse features text (newline separated) into array
     */
    public function getFeaturesListAttribute(): array
    {
        if (empty($this->features)) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $this->features);
        return array_values(array_filter(array_map('trim', $lines)));
    }
}
