<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'image', 'caption', 'sort_order'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getImageUrlAttribute(): string
    {
        return $this->image ? media_url($this->image) : '';
    }
}
