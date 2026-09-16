<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $keyword
 * @property int $hits
 * @property int $results_count
 * @property \Illuminate\Support\Carbon|null $last_searched_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch whereHits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch whereKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch whereLastSearchedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch whereResultsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductSearch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'hits',
        'results_count',
        'last_searched_at',
    ];

    protected $casts = [
        'hits' => 'integer',
        'results_count' => 'integer',
        'last_searched_at' => 'datetime',
    ];

    /**
     * Catat pencarian baru atau tingkatkan hitungan (hits) pencarian yang sudah ada
     */
    public static function record(?string $rawKeyword, int $resultsCount = 0): ?self
    {
        if (empty($rawKeyword)) {
            return null;
        }

        $clean = trim(preg_replace('/\s+/', ' ', $rawKeyword));
        $len = mb_strlen($clean);

        // Jangan catat jika terlalu pendek (< 2 char) atau terlalu panjang (> 60 char)
        if ($len < 2 || $len > 60) {
            return null;
        }

        // Normalisasi kata kunci (Title Case, contoh: "toko online" -> "Toko Online")
        $normalized = Str::title(mb_strtolower($clean));

        $record = static::firstOrNew(['keyword' => $normalized]);
        $record->hits = $record->exists ? ($record->hits + 1) : 1;
        $record->results_count = $resultsCount;
        $record->last_searched_at = now();
        $record->save();

        // Invalidate cache kata kunci populer
        Cache::forget('popular_search_keywords');

        return $record;
    }

    /**
     * Ambil kata kunci pencarian paling populer untuk ditampilkan di navbar
     */
    public static function getPopular(int $limit = 7): array
    {
        return Cache::remember('popular_search_keywords', 600, function () use ($limit) {
            $searches = static::orderBy('hits', 'desc')
                ->limit($limit)
                ->pluck('keyword')
                ->toArray();

            // Jika riwayat pencarian pembeli masih sedikit (< 4), lengkapi dengan nama kategori teratas
            if (count($searches) < 4) {
                $categoryFallbacks = ProductCategory::has('products')
                    ->orderBy('name', 'asc')
                    ->limit($limit - count($searches))
                    ->pluck('name')
                    ->toArray();

                $searches = array_unique(array_merge($searches, $categoryFallbacks));
            }

            return array_slice($searches, 0, $limit);
        });
    }
}
