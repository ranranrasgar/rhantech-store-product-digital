<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $help_category_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int $views
 * @property int $helpful_yes
 * @property int $helpful_no
 * @property int $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HelpCategory $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereHelpCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereHelpfulNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereHelpfulYes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereViews($value)
 * @mixin \Eloquent
 */
class HelpArticle extends Model
{
    protected $fillable = ['help_category_id', 'title', 'slug', 'content', 'views', 'is_published'];

    public function category()
    {
        return $this->belongsTo(HelpCategory::class, 'help_category_id');
    }
}
