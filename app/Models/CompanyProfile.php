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
        'founded_year', 'vision', 'mission'
    ];
}
