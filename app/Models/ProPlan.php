<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property numeric $price
 * @property int|null $duration_days
 * @property string|null $duration_label
 * @property string|null $badge
 * @property string|null $description
 * @property string|null $features
 * @property bool $is_popular
 * @property bool $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read array $features_list
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereDurationLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereIsPopular($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProPlan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
