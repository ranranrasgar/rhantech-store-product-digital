<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WebsiteVisit extends Model
{
    use HasFactory;

    protected $table = 'website_visits';

    protected $fillable = [
        'ip_address',
        'session_id',
        'path',
        'referer',
        'device_type',
        'browser',
        'platform',
        'is_unique_daily',
        'visited_at',
    ];

    protected $casts = [
        'is_unique_daily' => 'boolean',
        'visited_at' => 'datetime',
    ];

    /**
     * Scope for today's visits
     */
    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', Carbon::today());
    }

    /**
     * Scope for visits within the last N days
     */
    public function scopeLastDays($query, int $days = 7)
    {
        return $query->where('visited_at', '>=', Carbon::now()->subDays($days)->startOfDay());
    }

    /**
     * Scope for unique visitors (grouped by date and session/ip)
     */
    public function scopeUniqueVisitors($query)
    {
        return $query->where('is_unique_daily', true);
    }
}
