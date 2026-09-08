<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name', 'tagline', 'short_description', 'description', 
        'logo', 'favicon', 'email', 'phone', 'whatsapp', 'address', 
        'website', 'facebook', 'instagram', 'linkedin', 'youtube', 
        'founded_year', 'vision', 'mission',
        'hero_mode', 'hero_badge', 'hero_title', 'hero_subtitle',
        'hero_image', 'hero_btn_primary_text', 'hero_btn_primary_url',
        'hero_btn_secondary_text', 'hero_btn_secondary_url',
        'hero_stats_val', 'hero_stats_label'
    ];
}
