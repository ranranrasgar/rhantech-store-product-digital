<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property string|null $logo
 * @property string|null $description
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Testimonial> $testimonials
 * @property-read int|null $testimonials_count
 * @method static \Database\Factories\ClientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Client whereWebsite($value)
 */
	class Client extends \Eloquent {}
}

namespace App\Models{
/**
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
 * @property string|null $founded_year
 * @property string|null $vision
 * @property string|null $mission
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereFacebook($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereFavicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereFoundedYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereInstagram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereLinkedin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereMission($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereTagline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereVision($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereWebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompanyProfile whereYoutube($value)
 */
	class CompanyProfile extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $company
 * @property string $subject
 * @property string $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ContactMessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ContactMessage whereUpdatedAt($value)
 */
	class ContactMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $client_id
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $category
 * @property string|null $thumbnail
 * @property string|null $project_url
 * @property array<array-key, mixed>|null $technologies
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property int $is_featured
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProjectImage> $images
 * @property-read int|null $images_count
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereProjectUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereTechnologies($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereUpdatedAt($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $project_id
 * @property string $image
 * @property string|null $caption
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project $project
 * @method static \Database\Factories\ProjectImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectImage whereUpdatedAt($value)
 */
	class ProjectImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $icon
 * @property string|null $image
 * @property int $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\ServiceFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereShortDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 */
	class Service extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $client_id
 * @property string $name
 * @property string|null $position
 * @property string|null $company
 * @property string|null $photo
 * @property string $content
 * @property int $rating
 * @property int $is_active
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Client|null $client
 * @method static \Database\Factories\TestimonialFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Testimonial whereUpdatedAt($value)
 */
	class Testimonial extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

