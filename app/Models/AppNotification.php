<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $target_role
 * @property string $type
 * @property string $title
 * @property string $body
 * @property string $icon
 * @property string|null $url
 * @property bool $is_read
 * @property array<array-key, mixed>|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereTargetRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppNotification whereUserId($value)
 * @mixin \Eloquent
 */
class AppNotification extends Model
{
    protected $fillable = [
        'user_id',
        'target_role',
        'type',
        'title',
        'body',
        'icon',
        'url',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
