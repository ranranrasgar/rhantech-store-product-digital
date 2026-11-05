<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Halaman tidak ditemukan - {{ $company->company_name ?? 'Rhantech' }}</title>
    <meta name="robots" content="noindex"/>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 16px; box-sizing: border-box; background: #f0f4f8; color: #0f172a; font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; text-align: center; }
        main { max-width: 480px; }
        .code { font-size: 64px; font-weight: 800; color: #0284c7; margin: 0; }
        h1 { font-size: 24px; margin: 8px 0 12px; }
        p { color: #475569; line-height: 1.6; margin: 0 0 24px; }
        a { display: inline-block; margin: 4px; padding: 10px 18px; border-radius: 8px; background: #0284c7; color: #fff; font-weight: 600; text-decoration: none; }
        a.secondary { background: #e2e8f0; color: #0f172a; }
    </style>
</head>
<body>
    <main>
        <p class="code">404</p>
        <h1>Halaman tidak ditemukan</h1>
        <p>Halaman yang Anda cari mungkin sudah dipindahkan atau tidak tersedia.</p>
        <a href="{{ route('products.index') }}">Lihat Katalog Aplikasi</a>
        <a class="secondary" href="{{ route('help.index') }}">Pusat Bantuan</a>
        <a class="secondary" href="{{ route('contact') }}">Hubungi Kami</a>
    </main>
</body>
</html>
