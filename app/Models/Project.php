<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'project_category_id', 'project_type_id', 'title', 'slug', 'short_description', 'description',
        'thumbnail', 'brochure_file', 'project_url', 'order_url', 'technologies', 
        'completed_at', 'is_featured', 'status'
    ];

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
}
