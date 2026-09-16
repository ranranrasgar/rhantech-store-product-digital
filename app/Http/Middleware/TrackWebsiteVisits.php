<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\WebsiteVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TrackWebsiteVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests for human public pages
        if (!$request->isMethod('GET') || $response->getStatusCode() >= 400) {
            return $response;
        }

        // Exclude AJAX, Livewire, JSON, background, or file requests
        if ($request->ajax() || $request->header('X-Livewire') || $request->expectsJson()) {
            return $response;
        }

        // Exclude admin, tenant dashboard, api, webhook, assets, debug, and health check routes
        if ($request->is(
            'admin*',
            'dashboard*',
            'tenant*',
            'api*',
            'up',
            '_debugbar*',
            'chat*',
            'tenant/chat*',
            'storage*',
            'build*'
        )) {
            return $response;
        }

        try {
            $this->recordVisit($request);
        } catch (\Throwable $e) {
            // Silently suppress errors so tracking never breaks the user experience
            Log::debug('Visitor tracking error: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Record the visit in database with deduplication per session.
     */
    protected function recordVisit(Request $request): void
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('website_visits')) {
            return;
        }

        $path = '/' . ltrim($request->path(), '/');
        $ip = $request->ip();
        $sessionId = $request->hasSession() ? $request->session()->getId() : md5($ip);
        $userAgent = (string) $request->userAgent();

        // Detect bot/crawler
        if (preg_match('/(bot|crawl|spider|slurp|facebookexternalhit|whatsapp)/i', $userAgent)) {
            return;
        }

        // Detect device
        $device = 'desktop';
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $userAgent)) {
            $device = 'tablet';
        } elseif (preg_match('/(mobile|android|iphone|ipod|blackberry|opera mini|iemobile|wpdesktop)/i', $userAgent)) {
            $device = 'mobile';
        }

        // Detect browser
        $browser = 'Other';
        if (preg_match('/edg/i', $userAgent)) {
            $browser = 'Edge';
        } elseif (preg_match('/chrome|crios/i', $userAgent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/firefox|fxios/i', $userAgent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/safari/i', $userAgent)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera|opr/i', $userAgent)) {
            $browser = 'Opera';
        }

        // Detect platform OS
        $platform = 'Other';
        if (preg_match('/windows/i', $userAgent)) {
            $platform = 'Windows';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'macOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        }

        // Throttle tracking for same session on same path within 3 minutes
        $cacheKey = 'visited_' . md5($sessionId . '_' . $path);
        if (cache()->has($cacheKey)) {
            return;
        }
        cache()->put($cacheKey, true, now()->addMinutes(3));

        // Check if unique visitor for today
        $dailyKey = 'unique_visitor_today_' . md5($sessionId . '_' . Carbon::today()->toDateString());
        $isUniqueDaily = !cache()->has($dailyKey);
        if ($isUniqueDaily) {
            cache()->put($dailyKey, true, now()->endOfDay());
        }

        WebsiteVisit::create([
            'ip_address' => substr((string)$ip, 0, 45),
            'session_id' => substr((string)$sessionId, 0, 100),
            'path' => substr($path, 0, 255),
            'referer' => $request->headers->get('referer') ? substr($request->headers->get('referer'), 0, 255) : null,
            'device_type' => $device,
            'browser' => $browser,
            'platform' => $platform,
            'is_unique_daily' => $isUniqueDaily,
            'visited_at' => Carbon::now(),
        ]);
    }
}
