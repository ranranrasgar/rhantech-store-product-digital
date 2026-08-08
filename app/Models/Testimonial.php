<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'name', 'position', 'company', 'photo', 
        'content', 'rating', 'is_active', 'sort_order'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
