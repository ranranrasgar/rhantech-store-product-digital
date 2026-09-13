<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method bool|null delete($id = null)
 */
class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'subject', 
        'message', 'status', 'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}
