<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string|null $ip_address
 * @property string|null $session_id
 * @property string $path
 * @property string|null $referer
 * @property string $device_type
 * @property string $browser
 * @property string $platform
 * @property bool $is_unique_daily
 * @property \Illuminate\Support\Carbon $visited_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit lastDays(int $days = 7)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit uniqueVisitors()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereIsUniqueDaily($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereReferer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WebsiteVisit whereVisitedAt($value)
 * @mixin \Eloquent
 */
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
