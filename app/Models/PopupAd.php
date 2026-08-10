<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'images',
        'link_url',
        'link_text',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
    ];
}
