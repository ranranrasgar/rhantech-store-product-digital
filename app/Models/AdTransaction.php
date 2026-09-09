<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'reference_no',
        'type',
        'amount',
        'tax_amount',
        'total_amount',
        'payment_method',
        'status',
        'snap_token',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
