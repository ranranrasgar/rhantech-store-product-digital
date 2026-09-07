<?php

namespace App\Providers;

use App\Models\CompanyProfile;
use App\Models\PayoutRequest;
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
        //
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
            $view->with('company', CompanyProfile::first(['*']));
        });

        View::composer(['layouts.admin', 'admin.*'], function ($view) {
            $pendingPayoutsCount = PayoutRequest::where('status', 'pending')->count();
            $pendingPayoutsList = PayoutRequest::with('store')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();

            $view->with([
                'pendingPayoutsCount' => $pendingPayoutsCount,
                'pendingPayoutsList' => $pendingPayoutsList,
            ]);
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
