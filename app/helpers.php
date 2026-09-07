<?php

if (!function_exists('media_url')) {
    /**
     * Resolve URL for uploaded media.
     * Checks Cloudflare R2 / S3 first if configured, falls back to local asset storage.
     *
     * @param string|null $path
     * @return string
     */
    function media_url(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // If it's already a full HTTP(S) URL
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Clean leading slash
        $cleanPath = ltrim($path, '/');

        // Remove 'storage/' prefix if accidentally passed
        if (\Illuminate\Support\Str::startsWith($cleanPath, 'storage/')) {
            $cleanPath = \Illuminate\Support\Str::after($cleanPath, 'storage/');
        }

        // If Cloudflare R2 URL is configured
        $r2Url = config('filesystems.disks.r2.url');
        if (!empty($r2Url)) {
            return rtrim($r2Url, '/') . '/' . $cleanPath;
        }

        // Fallback to local storage
        return asset('storage/' . $cleanPath);
    }
}
