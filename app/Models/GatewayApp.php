<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayApp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prefix',
        'callback_url',
        'is_active',
        'is_local',
    ];
}
