<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopupAd extends Model
{
    use HasFactory;

    public const TARGET_ALL = 'all';
    public const TARGET_GUEST = 'guest';
    public const TARGET_CUSTOMER = 'customer';
    public const TARGET_TENANT = 'tenant';

    protected $fillable = [
        'title',
        'description',
        'images',
        'link_url',
        'link_text',
        'target_audience',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Daftar label tujuan sasaran
     */
    public static function getTargetOptions(): array
    {
        return [
            self::TARGET_ALL => [
                'label' => 'Semua Pengguna (Umum)',
                'desc' => 'Tampil untuk semua pengunjung (publik, calon member, dan pengguna terdaftar)',
                'icon' => 'public',
                'badge_color' => 'blue',
            ],
            self::TARGET_GUEST => [
                'label' => 'Calon Member (Belum Login / Publik)',
                'desc' => 'Khusus pengunjung yang belum punya akun. Cocok untuk ajakan daftar, promo new member.',
                'icon' => 'person_add',
                'badge_color' => 'amber',
            ],
            self::TARGET_CUSTOMER => [
                'label' => 'Member / Pembeli Terdaftar',
                'desc' => 'Khusus member yang sudah login. Cocok untuk promo loyalitas, saldo, diskon belanja.',
                'icon' => 'shopping_bag',
                'badge_color' => 'emerald',
            ],
            self::TARGET_TENANT => [
                'label' => 'Khusus Mitra Toko / Tenant',
                'desc' => 'Khusus penjual / pemilik toko. Tampil di Beranda & Dashboard Toko (Seller Center).',
                'icon' => 'storefront',
                'badge_color' => 'purple',
            ],
        ];
    }

    /**
     * Ambil label target saat ini
     */
    public function getTargetLabelAttribute(): string
    {
        $options = self::getTargetOptions();
        return $options[$this->target_audience]['label'] ?? 'Semua Pengguna (Umum)';
    }

    /**
     * Ambil iklan pop-up aktif yang paling sesuai untuk pengunjung saat ini
     */
    public static function getActiveForCurrentUser()
    {
        $user = auth()->user();
        $specificTarget = 'guest';

        if ($user) {
            if ($user->store || $user->role === 'tenant' || $user->role === 'admin') {
                $specificTarget = 'tenant';
            } else {
                $specificTarget = 'customer';
            }
        }

        // 1. Prioritaskan iklan yang menargetkan audiens spesifik pengguna saat ini
        $ad = self::where('is_active', true)
            ->where('target_audience', $specificTarget)
            ->latest()
            ->first();

        // 2. Jika tidak ada iklan spesifik, ambil iklan untuk 'all' (semua pengguna)
        if (!$ad) {
            $ad = self::where('is_active', true)
                ->where(function ($q) {
                    $q->where('target_audience', self::TARGET_ALL)
                      ->orWhereNull('target_audience');
                })
                ->latest()
                ->first();
        }

        return $ad;
    }
}
