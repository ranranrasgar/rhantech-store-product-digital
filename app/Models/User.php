<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @use HasFactory<UserFactory>
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\User create(array $attributes = [])
 * @method static \App\Models\User|null find($id, $columns = ['*'])
 * @method static \App\Models\User findOrFail($id, $columns = ['*'])
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'provider_name',
        'provider_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function followingStores()
    {
        return $this->belongsToMany(Store::class, 'followers', 'user_id', 'store_id')->withTimestamps();
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }
}
