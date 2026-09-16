<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $company
 * @property string $subject
 * @property string $message
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method bool delete()
 * @method static Builder|ContactMessage query()
 * @method static Builder|ContactMessage whereNull(string $column)
 * @method static Builder|ContactMessage whereNotNull(string $column)
 * @method static Builder|ContactMessage whereIn(string $column, array $values)
 * @method static Builder|ContactMessage latest(string $column = 'created_at')
 * @method static int count(string $columns = '*')
 * @method static \Illuminate\Contracts\Pagination\LengthAwarePaginator paginate(int $perPage = 15)
 * @method static ContactMessage|null first()
 * @method static ContactMessage firstOrFail()
 * @method static \Database\Factories\ContactMessageFactory factory($count = null, $state = [])
 * @method static Builder<static>|ContactMessage newModelQuery()
 * @method static Builder<static>|ContactMessage newQuery()
 * @method static Builder<static>|ContactMessage whereCompany($value)
 * @method static Builder<static>|ContactMessage whereCreatedAt($value)
 * @method static Builder<static>|ContactMessage whereEmail($value)
 * @method static Builder<static>|ContactMessage whereId($value)
 * @method static Builder<static>|ContactMessage whereMessage($value)
 * @method static Builder<static>|ContactMessage whereName($value)
 * @method static Builder<static>|ContactMessage wherePhone($value)
 * @method static Builder<static>|ContactMessage whereReadAt($value)
 * @method static Builder<static>|ContactMessage whereStatus($value)
 * @method static Builder<static>|ContactMessage whereSubject($value)
 * @method static Builder<static>|ContactMessage whereUpdatedAt($value)
 */
class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'subject',
        'message', 'status', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}
