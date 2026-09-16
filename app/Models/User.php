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
 * @method static \App\Models\User|null find($id, array $columns = [])
 * @method static \App\Models\User findOrFail($id, array $columns = [])
 * @method static \App\Models\User|null first(array $columns = [])
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method bool|null delete($id = null)
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
        'credit_balance',
        'credit_expires_at',
        'is_new_member_credit_claimed',
        'onboarding_completed_at',
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
            'credit_expires_at' => 'datetime',
            'is_new_member_credit_claimed' => 'boolean',
            'credit_balance' => 'decimal:2',
            'onboarding_completed_at' => 'datetime',
        ];
    }

    /**
     * Check if the user has completed the onboarding wizard.
     */
    public function hasCompletedOnboarding(): bool
    {
        if ($this->onboarding_completed_at !== null) {
            return true;
        }

        // Jika user sudah memiliki toko, otomatis dianggap selesai onboarding
        if ($this->store()->exists()) {
            $this->updateQuietly(['onboarding_completed_at' => now()]);
            return true;
        }

        return false;
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

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            if (\Illuminate\Support\Str::startsWith($this->avatar, ['http://', 'https://'])) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'User') . '&background=0284c7&color=fff&bold=true';
    }

    public function getAvatarFallbackSvgAttribute(): string
    {
        $initial = strtoupper(substr($this->name ?? 'U', 0, 1));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><rect width="40" height="40" rx="20" fill="#0284c7"/><text x="50%" y="55%" dominant-baseline="middle" text-anchor="middle" fill="#ffffff" font-family="sans-serif" font-weight="bold" font-size="18">' . $initial . '</text></svg>';
        return 'data:image/svg+xml;utf8,' . rawurlencode($svg);
    }
}
