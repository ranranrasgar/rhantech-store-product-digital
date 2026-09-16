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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProjectType extends Model
{
    protected $fillable = ['name', 'slug'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
