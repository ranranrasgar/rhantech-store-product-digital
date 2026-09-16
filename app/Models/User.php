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
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $provider_name
 * @property string|null $provider_id
 * @property string|null $avatar
 * @property numeric $credit_balance
 * @property \Illuminate\Support\Carbon|null $credit_expires_at
 * @property bool $is_new_member_credit_claimed
 * @property \Illuminate\Support\Carbon|null $onboarding_completed_at
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FcmToken> $fcmTokens
 * @property-read int|null $fcm_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Store> $followingStores
 * @property-read int|null $following_stores_count
 * @property-read string $avatar_fallback_svg
 * @property-read string $avatar_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Store|null $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreditBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreditExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsNewMemberCreditClaimed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOnboardingCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
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
