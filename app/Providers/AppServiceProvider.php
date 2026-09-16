<?php

namespace App\Providers;

use App\Models\CompanyProfile;
use App\Models\PayoutRequest;
use App\Models\Product;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom URL Generator to intercept asset('storage/...') and route directly to Cloudflare R2 / S3
        $this->app->extend('url', function (\Illuminate\Contracts\Routing\UrlGenerator $url, $app) {
            $customUrl = new class($app['routes'], $app->rebinding('request', function ($app, $request) {
                $app['url']->setRequest($request);
            }), $app['config']['app.asset_url']) extends \Illuminate\Routing\UrlGenerator {
                public function asset($path, $secure = null)
                {
                    $clean = ltrim($path, '/');
                    if (\Illuminate\Support\Str::startsWith($clean, 'storage/')) {
                        $r2Url = config('filesystems.disks.r2.url');
                        if (!empty($r2Url)) {
                            $mediaPath = \Illuminate\Support\Str::after($clean, 'storage/');
                            return rtrim($r2Url, '/') . '/' . ltrim($mediaPath, '/');
                        }
                    }
                    return parent::asset($path, $secure);
                }
            };

            $customUrl->setSessionResolver(function () use ($app) {
                return $app['session'] ?? null;
            });

            $customUrl->setKeyResolver(function () use ($app) {
                $config = $app->make('config');
                return [$config->get('app.key'), ...($config->get('app.previous_keys') ?? [])];
            });

            return $customUrl;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });
        View::composer('*', function ($view) {
            static $cachedCompany = null;
            if ($cachedCompany === null) {
                $cachedCompany = CompanyProfile::first(['*']);
            }
            $view->with('company', $cachedCompany);
        });

        View::composer(['layouts.shopee', 'products.*'], function ($view) {
            $view->with('popularSearches', \App\Models\ProductSearch::getPopular(7));
        });

        View::composer(['layouts.public', 'welcome', 'layouts.tenant', 'tenant.*'], function ($view) {
            $view->with('popupAd', \App\Models\PopupAd::getActiveForCurrentUser());
        });

        View::composer(['layouts.admin', 'admin.*'], function ($view) {
            static $adminGlobalStats = null;
            if ($adminGlobalStats === null) {
                try {
                    $pendingPayoutsCount = PayoutRequest::where('status', 'pending')->count();
                    $pendingPayoutsList = PayoutRequest::with('store:id,name,slug,logo')
                        ->where('status', 'pending')
                        ->latest()
                        ->take(5)
                        ->get(['id', 'store_id', 'amount', 'status', 'created_at']);

                    $pendingProductsCount = Product::whereNotNull('store_id')
                        ->where('approval_status', 'pending')
                        ->count();

                    $pendingProductsList = Product::with(['store:id,name,slug,logo', 'category:id,name', 'images'])
                        ->whereNotNull('store_id')
                        ->where('approval_status', 'pending')
                        ->latest()
                        ->take(6)
                        ->get(['id', 'store_id', 'product_category_id', 'name', 'slug', 'price', 'created_at']);

                    $adminGlobalStats = [
                        'pendingPayoutsCount' => $pendingPayoutsCount,
                        'pendingPayoutsList' => $pendingPayoutsList,
                        'pendingProductsCount' => $pendingProductsCount,
                        'pendingProductsList' => $pendingProductsList,
                    ];
                } catch (\Throwable $e) {
                    $adminGlobalStats = [
                        'pendingPayoutsCount' => 0,
                        'pendingPayoutsList' => collect(),
                        'pendingProductsCount' => 0,
                        'pendingProductsList' => collect(),
                    ];
                }
            }

            $view->with($adminGlobalStats);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $company = CompanyProfile::first(['*']);
            $companyName = $company->company_name ?? config('app.name', 'Rhantech');

            return (new MailMessage)
                ->subject('Konfirmasi & Verifikasi Akun Anda - ' . $companyName)
                ->view('emails.verify_email', [
                    'user' => $notifiable,
                    'verificationUrl' => $url,
                ]);
        });

        // Blade helper for R2 / Local media fallback
        \Illuminate\Support\Facades\Blade::directive('mediaUrl', function ($expression) {
            return "<?php echo media_url($expression); ?>";
        });
    }
}
