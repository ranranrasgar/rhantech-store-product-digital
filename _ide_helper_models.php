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
 * @property string $handle
 * @property string|null $avatar_url
 * @property string|null $followers_count
 * @property string|null $clicks_count
 * @property string|null $orders_count
 * @property string|null $sales_range
 * @property string|null $audience_demographic
 * @property string|null $platform
 * @property array<array-key, mixed>|null $categories
 * @property bool $is_golden_tick
 * @property bool $is_good_sample_completion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\AffiliateFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereAudienceDemographic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereAvatarUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereCategories($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereClicksCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereFollowersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIsGoldenTick($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereIsGoodSampleCompletion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereOrdersCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereSalesRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Affiliate whereUpdatedAt($value)
 */
	class Affiliate extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $title
 * @property string $image_path
 * @property string $position
 * @property string|null $link
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Banner whereUpdatedAt($value)
 */
	class Banner extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $store_id
 * @property string $name
 * @property string $type
 * @property string $discount_type
 * @property numeric $discount_value
 * @property string|null $code
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $status
 * @property numeric $minimum_spend
 * @property int|null $usage_limit
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereMinimumSpend($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campaign whereUsageLimit($value)
 */
	class Campaign extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property string $sender_type
 * @property string $message
 * @property int|null $product_id
 * @property bool $is_read
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Store $store
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereUserId($value)
 */
	class ChatMessage extends \Eloquent {}
}

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
 * @property mixed $url
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
 * @property int $user_id
 * @property string $token
 * @property string|null $device_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken whereDeviceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FcmToken whereUserId($value)
 */
	class FcmToken extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Follower whereUserId($value)
 */
	class Follower extends \Eloquent {}
}

namespace App\Models{
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
 */
	class GatewayApp extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $help_category_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int $views
 * @property int $helpful_yes
 * @property int $helpful_no
 * @property int $is_published
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\HelpCategory $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereHelpCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereHelpfulNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereHelpfulYes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpArticle whereViews($value)
 */
	class HelpArticle extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $icon
 * @property string|null $description
 * @property int $sort_order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\HelpArticle> $articles
 * @property-read int|null $articles_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HelpCategory whereUpdatedAt($value)
 */
	class HelpCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $invoice_number
 * @property int|null $product_id
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property numeric $amount
 * @property string $status
 * @property string|null $download_token
 * @property string|null $snap_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDownloadToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSnapToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int|null $product_id
 * @property int $quantity
 * @property numeric $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $store_id
 * @property numeric $amount
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayoutRequest whereUpdatedAt($value)
 */
	class PayoutRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property array<array-key, mixed>|null $images
 * @property string|null $link_url
 * @property string|null $link_text
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereLinkText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PopupAd whereUpdatedAt($value)
 */
	class PopupAd extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $store_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $product_category_id
 * @property int|null $product_type_id
 * @property string|null $demo_url
 * @property numeric $price
 * @property numeric|null $discount_price
 * @property string|null $file_path
 * @property array<array-key, mixed>|null $download_links
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductCategory|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Store|null $store
 * @property-read \App\Models\ProductType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDemoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDownloadLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereProductTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductCategory whereUpdatedAt($value)
 */
	class ProductCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $product_id
 * @property string $image_path
 * @property int $is_main
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereImagePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductImage whereUpdatedAt($value)
 */
	class ProductImage extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductType whereUpdatedAt($value)
 */
	class ProductType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $client_id
 * @property int|null $project_category_id
 * @property string $title
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
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
 * @property-read \App\Models\ProjectCategory|null $projectCategory
 * @method static \Database\Factories\ProjectFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Project whereProjectCategoryId($value)
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
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProjectCategory whereUpdatedAt($value)
 */
	class ProjectCategory extends \Eloquent {}
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
 * @property int $user_id
 * @property string $name
 * @property string $slug
 * @property string|null $logo
 * @property string|null $banner
 * @property string|null $description
 * @property string|null $address
 * @property string|null $maps_location
 * @property numeric $balance
 * @property string|null $bank_account_info
 * @property array<array-key, mixed>|null $appearance_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PayoutRequest> $payoutRequests
 * @property-read int|null $payout_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereAppearanceData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBankAccountInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereMapsLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Store whereUserId($value)
 */
	class Store extends \Eloquent {}
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
 * @use HasFactory<UserFactory>
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\User create(array $attributes = [])
 * @method static \App\Models\User|null find($id, $columns = ['*'])
 * @method static \App\Models\User findOrFail($id, $columns = ['*'])
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $password
 * @property string|null $provider_name
 * @property string|null $provider_id
 * @property string|null $avatar
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FcmToken> $fcmTokens
 * @property-read int|null $fcm_tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Store> $followingStores
 * @property-read int|null $following_stores_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Store|null $store
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProviderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

