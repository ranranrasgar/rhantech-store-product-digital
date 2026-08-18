<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    protected $fillable = ['help_category_id', 'title', 'slug', 'content', 'views', 'is_published'];

    public function category()
    {
        return $this->belongsTo(HelpCategory::class, 'help_category_id');
    }
}
