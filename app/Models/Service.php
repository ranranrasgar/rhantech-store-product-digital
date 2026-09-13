<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method static int count(string $columns = '*')
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder latest($column = null)
 * @method static \Illuminate\Database\Eloquent\Builder oldest($column = null)
 * @method static \App\Models\Service create(array $attributes = [])
 * @method static \App\Models\Service|null find($id, array $columns = [])
 * @method static \App\Models\Service findOrFail($id, array $columns = [])
 * @method static \App\Models\Service|null first(array $columns = [])
 * @method bool|null delete($id = null)
 */
class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'short_description', 'description', 
        'icon', 'image', 'is_active', 'sort_order'
    ];
}
