<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \App\Models\CompanyProfile|null first(array $columns = [])
 * @method static \App\Models\CompanyProfile findOrFail($id, array $columns = [])
 * @method static \App\Models\CompanyProfile create(array $attributes = [])
 * @property int $id
 * @property string $company_name
 * @property string|null $tagline
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $logo
 * @property string|null $favicon
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $whatsapp
 * @property string|null $address
 * @property string|null $website
 * @property string|null $facebook
 * @property string|null $instagram
 * @property string|null $linkedin
 * @property string|null $youtube
 * @property array<array-key, mixed>|null $social_links
 * @property string|null $founded_year
 * @property string|null $vision
 * @property string|null $mission
 * @property string $hero_mode
 * @property string|null $hero_badge
 * @property string|null $hero_title
 * @property string|null $hero_subtitle
 * @property string|null $hero_image
 * @property string|null $hero_btn_primary_text
 * @property string|null $hero_btn_primary_url
 * @property string|null $hero_btn_secondary_text
 * @property string|null $hero_btn_secondary_url
 * @property string|null $hero_stats_val
 * @property string|null $hero_stats_label
 * @property bool $hero_stats_show
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereFavicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereFoundedYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroBadge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroBtnPrimaryText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroBtnPrimaryUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroBtnSecondaryText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroBtnSecondaryUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroStatsLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroStatsShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroStatsVal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereHeroTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereMission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereSocialLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereYoutube($value)
 * @mixin \Eloquent
 */
class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name', 'tagline', 'short_description', 'description', 
        'logo', 'favicon', 'email', 'phone', 'whatsapp', 'address', 
        'website', 'facebook', 'instagram', 'linkedin', 'youtube', 'social_links',
        'founded_year', 'vision', 'mission',
        'hero_mode', 'hero_badge', 'hero_title', 'hero_subtitle',
        'hero_image', 'hero_btn_primary_text', 'hero_btn_primary_url',
        'hero_btn_secondary_text', 'hero_btn_secondary_url',
        'hero_stats_val', 'hero_stats_label', 'hero_stats_show'
    ];

    protected $casts = [
        'social_links' => 'array',
        'hero_stats_show' => 'boolean',
    ];
}
