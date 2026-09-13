<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function booted()
    {
        static::deleting(function ($order) {
            $invoice = $order->invoice_number;
            $orderId = $order->id;

            \App\Models\AppNotification::where('data->order_id', $orderId)
                ->orWhere('data->invoice', $invoice)
                ->orWhere('title', 'like', "%{$invoice}%")
                ->orWhere('body', 'like', "%{$invoice}%")
                ->delete();
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function referrerStore()
    {
        return $this->belongsTo(Store::class, 'referrer_store_id');
    }

    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
