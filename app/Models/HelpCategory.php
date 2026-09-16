<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property string|null $description
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HelpArticle> $articles
 * @property-read int|null $articles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class HelpCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'description', 'sort_order'];

    public function articles()
    {
        return $this->hasMany(HelpArticle::class);
    }
}
