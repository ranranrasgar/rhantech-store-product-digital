<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProjectCategory extends Model
{
    protected $fillable = ['name', 'slug'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
