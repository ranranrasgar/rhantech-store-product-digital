<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Membuat thumbnail WebP (lebar tetap) dari gambar di disk "public" secara lazy:
 * dibuat sekali saat pertama diminta, lalu dipakai ulang dari storage/app/public/thumbs/{w}/...
 *
 * Kalau GD/WebP tidak tersedia, file tidak ada, atau terjadi error apa pun,
 * fungsi ini mengembalikan URL gambar asli — tampilan tidak pernah rusak.
 *
 * Pemakaian di Blade: {{ \App\Support\ImageThumb::url($product->image_path, 480) }}
 */
class ImageThumb
{
    /** Batas aman agar GD tidak kehabisan memori pada gambar raksasa. */
    private const MAX_PIXELS = 40_000_000;

    private const MAX_BYTES = 15 * 1024 * 1024;

    /** @var array<string, string> cache per-request */
    private static array $memo = [];

    public static function url(?string $path, int $width = 480, int $quality = 72): string
    {
        if (! $path) {
            return '';
        }

        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }

        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        $key = $width.'|'.$quality.'|'.$path;
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }

        return self::$memo[$key] = self::build($path, $width, $quality);
    }

    private static function build(string $path, int $width, int $quality): string
    {
        $original = asset('storage/'.$path);

        try {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || ! function_exists('imagewebp')) {
                return $original;
            }

            // Thumbnail hanya dibuat bila disk "public" benar-benar folder lokal.
            // Jika FILESYSTEM_DISK=r2, disk "public" = Cloudflare R2 (S3): setiap exists() adalah
            // request HTTP ke R2 -> halaman jadi sangat lambat. Pakai URL asli saja.
            if (config('filesystems.disks.public.driver') !== 'local') {
                return $original;
            }

            $disk = Storage::disk('public');
            $thumb = 'thumbs/'.$width.'/'.preg_replace('/\.[^.\/]+$/', '', $path).'.webp';

            if ($disk->exists($thumb)) {
                return asset('storage/'.$thumb);
            }

            if (! $disk->exists($path)) {
                return $original;
            }

            $src = $disk->path($path);
            if (@filesize($src) > self::MAX_BYTES) {
                return $original;
            }

            $info = @getimagesize($src);
            if (! $info || ! $info[0] || ! $info[1] || $info[0] * $info[1] > self::MAX_PIXELS) {
                return $original;
            }
            [$w, $h, $type] = $info;

            $img = match ($type) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($src),
                IMAGETYPE_PNG => @imagecreatefrompng($src),
                IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($src) : false,
                default => false,
            };
            if (! $img) {
                return $original;
            }

            if ($w > $width) {
                $newH = max(1, (int) round($h * $width / $w));
                $dst = imagecreatetruecolor($width, $newH);
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                imagecopyresampled($dst, $img, 0, 0, 0, 0, $width, $newH, $w, $h);
                imagedestroy($img);
                $img = $dst;
            } else {
                imagepalettetotruecolor($img);
                imagealphablending($img, false);
                imagesavealpha($img, true);
            }

            $full = $disk->path($thumb);
            $dir = dirname($full);
            if (! is_dir($dir) && ! @mkdir($dir, 0755, true) && ! is_dir($dir)) {
                imagedestroy($img);

                return $original;
            }

            // Tulis ke file sementara lalu rename, supaya request paralel tidak membaca file setengah jadi.
            $tmp = $full.'.'.getmypid().'.tmp';
            $ok = @imagewebp($img, $tmp, $quality);
            imagedestroy($img);

            if (! $ok || ! @rename($tmp, $full)) {
                @unlink($tmp);

                return $original;
            }

            return asset('storage/'.$thumb);
        } catch (Throwable) {
            return $original;
        }
    }
}
