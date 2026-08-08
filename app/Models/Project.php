<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'title', 'slug', 'short_description', 'description',
        'category', 'thumbnail', 'project_url', 'technologies', 
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

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('sort_order');
    }
}
