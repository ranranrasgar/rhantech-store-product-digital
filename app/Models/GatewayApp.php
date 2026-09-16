<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $prefix
 * @property string $callback_url
 * @property int $is_active
 * @property int $is_local
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp whereCallbackUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp whereIsLocal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp wherePrefix($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GatewayApp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
