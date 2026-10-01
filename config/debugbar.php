<?php

/*
 * Override minimal untuk barryvdh/laravel-debugbar.
 * Key lain tetap memakai default dari package (mergeConfigFrom menggabungkan level atas).
 *
 * Debugbar default-nya MATI karena merekam semua view & query sehingga halaman
 * seperti /products dan /projects jadi sangat lambat.
 * Untuk menyalakan saat debugging, tambahkan di .env:  DEBUGBAR_ENABLED=true
 */
return [
    'enabled' => env('DEBUGBAR_ENABLED', false),
];
