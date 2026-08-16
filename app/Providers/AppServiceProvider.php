<?php

namespace App\Providers;

use App\Models\CompanyProfile;
use App\Models\PayoutRequest;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
    }
}
