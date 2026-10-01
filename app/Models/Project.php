<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method bool|null delete($id = null)
 * @property int $id
 * @property int|null $store_id
 * @property int|null $client_id
 * @property int|null $project_category_id
 * @property int|null $project_type_id
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $thumbnail
 * @property string|null $project_url
 * @property string|null $order_url
 * @property string|null $brochure_file
 * @property array<array-key, mixed>|null $technologies
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property int $is_featured
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Client> $clients
 * @property-read int|null $clients_count
 * @property-read string $thumbnail_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectImage> $images
 * @property-read int|null $images_count
 * @property-read \App\Models\ProjectCategory|null $projectCategory
 * @property-read \App\Models\ProjectType|null $projectType
 * @property-read \App\Models\Store|null $store
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereBrochureFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereOrderUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereProjectCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereProjectTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereProjectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereTechnologies($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id', 'client_id', 'project_category_id', 'project_type_id', 'title', 'slug', 'short_description', 'description',
        'thumbnail', 'brochure_file', 'project_url', 'order_url', 'technologies', 
        'completed_at', 'is_featured', 'status'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    protected $casts = [
        'technologies' => 'array',
        'completed_at' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_project')->withTimestamps();
    }

    public function projectCategory()
    {
        return $this->belongsTo(ProjectCategory::class);
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail ? media_url($this->thumbnail) : '';
    }

    protected static function booted(): void
    {
        $flush = function () {
            \Illuminate\Support\Facades\Cache::forget('public:project_categories');
            \Illuminate\Support\Facades\Cache::forget('public:project_types');
        };
        static::saved($flush);
        static::deleted($flush);
    }
}
