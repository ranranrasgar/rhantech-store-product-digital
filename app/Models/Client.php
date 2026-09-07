<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'phone', 'email', 'website', 
        'logo', 'description', 'is_active'
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'client_project')->withTimestamps();
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function getUrlAttribute()
    {
        return $this->website;
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['website'] = $value;
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? media_url($this->logo) : '';
    }
}
