<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brosur - {{ $product->name }} - {{ $company->company_name ?? 'Rhantech Digital' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        :root {
            --primary: #0284c7;
            --primary-dark: #0369a1;
            --primary-gradient: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            --accent: #f59e0b;
            --dark: #090d16;
            --dark-surface: #0f172a;
            --text-dark: #0f172a;
            --text-muted: #475569;
            --border: #cbd5e1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-dark);
            background: #0b0f19;
            line-height: 1.5;
            font-size: 13px;
        }

        /* Top Action Bar (Hanya tampil di Browser) */
        .no-print-bar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(12px);
            color: #fff;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .no-print-bar .title-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .no-print-bar .actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.4);
        }
        .btn-primary:hover {
            opacity: 0.95;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: rgba(255,255,255,0.08);
            color: #e2e8f0;
            border: 1px solid rgba(255,255,255,0.18);
        }
        .btn-outline:hover {
            background: rgba(255,255,255,0.15);
            color: #ffffff;
        }

        /* Brosur Container A4 */
        .brochure-sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 28px auto;
            background: #ffffff;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Aksen Warna Pita Atas */
        .top-accent-bar {
            height: 7px;
            background: linear-gradient(90deg, #0284c7 0%, #38bdf8 25%, #6366f1 50%, #f59e0b 75%, #10b981 100%);
            width: 100%;
        }

        /* Header Premium Dark */
        .brochure-header {
            background: radial-gradient(circle at top right, #1e293b 0%, #0a0f1d 100%);
            color: #ffffff;
            padding: 24px 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            border-bottom: 2px solid rgba(2, 132, 199, 0.3);
        }

        .brochure-header::after {
            content: "";
            position: absolute;
            bottom: -1px;
            right: 0;
            width: 200px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #38bdf8);
        }

        .company-brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .company-logo {
            height: 52px;
            width: auto;
            max-width: 160px;
            object-fit: contain;
            background: #ffffff;
            padding: 6px 12px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .company-info h2 {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #ffffff;
            line-height: 1.15;
            text-transform: uppercase;
        }

        .company-info p {
            font-size: 11px;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 2px;
        }

        .brochure-badge-box {
            text-align: right;
        }

        .brochure-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.25) 0%, rgba(99, 102, 241, 0.25) 100%);
            border: 1px solid rgba(56, 189, 248, 0.6);
            color: #38bdf8;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.2);
        }

        .brochure-id {
            font-size: 10px;
            color: #64748b;
            margin-top: 4px;
            font-family: monospace;
            font-weight: 600;
        }

        /* Hero Banner Project */
        .project-hero {
            padding: 24px 34px;
            background: linear-gradient(180deg, #f0f9ff 0%, #f8fafc 100%);
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .project-thumb-wrapper {
            flex: 0 0 270px;
            max-width: 270px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #0284c7;
            box-shadow: 0 10px 20px -3px rgba(2, 132, 199, 0.25), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
        }

        .project-thumb {
            width: 100%;
            height: auto;
            max-height: 230px;
            object-fit: contain;
            display: block;
            border-radius: 6px;
        }

        .project-hero-content {
            flex: 1;
        }

        .project-hero-meta {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .tag {
            font-size: 10px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .tag-category {
            background: #0284c7;
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(2, 132, 199, 0.3);
        }

        .tag-type {
            background: #e2e8f0;
            color: #1e293b;
            border: 1px solid #cbd5e1;
        }

        .tag-price {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
            font-weight: 800;
        }

        .project-title {
            font-size: 26px;
            font-weight: 800;
            line-height: 1.2;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 10px;
        }

        .project-short-desc {
            font-size: 12.5px;
            color: #334155;
            line-height: 1.6;
            font-weight: 500;
        }

        /* Spesifikasi Cepat (4 Grid Cards Full Color) */
        .specs-section {
            padding: 16px 34px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: relative;
            z-index: 2;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .spec-card {
            padding: 10px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid transparent;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            min-width: 0;
            overflow: hidden;
        }

        .spec-card.card-blue {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-color: #bae6fd;
        }
        .spec-card.card-indigo {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            border-color: #c7d2fe;
        }
        .spec-card.card-amber {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-color: #fde68a;
        }
        .spec-card.card-emerald {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-color: #a7f3d0;
        }

        .spec-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .card-blue .spec-icon { background: #0284c7; color: #fff; }
        .card-indigo .spec-icon { background: #4f46e5; color: #fff; }
        .card-amber .spec-icon { background: #d97706; color: #fff; }
        .card-emerald .spec-icon { background: #059669; color: #fff; }

        .spec-meta {
            min-width: 0;
            flex: 1;
        }

        .spec-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.04em;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .spec-value {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            white-space: normal;
            line-height: 1.25;
            word-break: break-word;
        }

        /* Degradasi Transparan Tampilan Aplikasi di Kanan Bawah */
        .bottom-watermark {
            position: absolute;
            bottom: 45px;
            right: -40px;
            width: 340px;
            height: 260px;
            pointer-events: none;
            z-index: 1;
            opacity: 0.09;
            transform: rotate(-6deg);
            border-radius: 16px;
            overflow: hidden;
            border: 2px solid #0284c7;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            -webkit-mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 100%);
            mask-image: radial-gradient(circle at center, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 100%);
        }

        .bottom-watermark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: contrast(110%);
        }

        /* Main Content */
        .brochure-body {
            padding: 24px 34px;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .section-header-modern {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            position: relative;
        }

        .section-header-badge {
            background: linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .section-header-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
        }

        .section-header-line {
            flex: 1;
            height: 2px;
            background: linear-gradient(90deg, #cbd5e1, transparent);
        }

        .html-content {
            font-size: 12px;
            line-height: 1.7;
            color: #334155;
            margin-bottom: 20px;
            background: #fafafa;
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 16px 20px;
        }

        .html-content p {
            margin-bottom: 8px;
        }

        .html-content strong, .html-content b {
            color: #0f172a;
            font-weight: 700;
        }

        .html-content ul, .html-content ol {
            margin-left: 18px;
            margin-bottom: 10px;
        }

        .html-content li {
            margin-bottom: 4px;
        }

        .html-content h1, .html-content h2, .html-content h3 {
            font-size: 13.5px;
            font-weight: 800;
            color: #0284c7;
            margin-top: 12px;
            margin-bottom: 6px;
        }

        /* 3-Pillar Value Proposition Highlight */
        .value-props {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .value-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0284c7;
            padding: 10px 14px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .value-card h5 {
            font-size: 11.5px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 3px;
        }

        .value-card p {
            font-size: 10.5px;
            color: #64748b;
            line-height: 1.4;
        }

        /* Galeri / Screenshot Sistem (3 Gambar Full Card) */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .gallery-card {
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #cbd5e1;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            transition: all 0.2s;
        }

        .gallery-card .img-box {
            height: 120px;
            background: #0f172a;
            overflow: hidden;
        }

        .gallery-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .gallery-card .caption {
            padding: 6px 10px;
            font-size: 10px;
            font-weight: 700;
            color: #334155;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Call To Action Box (Sangat Kontras & Mengundang) */
        .brochure-cta-banner {
            background: linear-gradient(135deg, #0284c7 0%, #1e40af 100%);
            border-radius: 12px;
            padding: 18px 24px;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 20px -4px rgba(2, 132, 199, 0.4);
            margin-top: 10px;
            gap: 16px;
            position: relative;
            overflow: hidden;
        }

        .brochure-cta-banner::before {
            content: "";
            position: absolute;
            top: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .cta-content h4 {
            font-size: 15px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 3px;
        }

        .cta-content p {
            font-size: 11.5px;
            color: #e0f2fe;
            line-height: 1.4;
        }

        .cta-contact-pills {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .pill-btn {
            background: #ffffff;
            color: #0284c7;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            white-space: nowrap;
        }

        /* Footer Mewah */
        .brochure-footer {
            background: #090d16;
            color: #94a3b8;
            padding: 18px 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 10.5px;
            border-top: 3px solid #0284c7;
            margin-top: auto;
        }

        .footer-contacts {
            display: flex;
            gap: 20px;
        }

        .footer-contacts span {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #cbd5e1;
            font-weight: 600;
        }

        .footer-contacts .icon {
            color: #38bdf8;
            font-size: 14px;
        }

        /* PRINT MEDIA OPTIMIZATION (Untuk Cetak / Save As PDF A4) */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .no-print-bar {
                display: none !important;
            }

            .brochure-sheet {
                width: 100% !important;
                min-height: 100vh !important;
                margin: 0 !important;
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .gallery-card {
                box-shadow: none !important;
                border-color: #cbd5e1 !important;
            }

            .brochure-cta-banner {
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Bar Aksi (Hanya muncul di Layar Monitor) -->
    <div class="no-print-bar">
        <div class="title-bar">
            <span class="material-symbols-outlined" style="color: #38bdf8; font-size: 20px;">auto_awesome</span>
            <span>Brosur Resmi Produk: <strong>{{ $product->name }}</strong></span>
        </div>
        <div class="actions">
            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline">
                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_back</span>
                <span>Kembali ke Produk</span>
            </a>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <span class="material-symbols-outlined" style="font-size: 16px;">print</span>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Halaman Brosur Lembar A4 -->
    <div class="brochure-sheet">
        <!-- Pita Warna Atas (Gradien Pelangi Digital) -->
        <div class="top-accent-bar"></div>

        <!-- Header Premium Dark -->
        <header class="brochure-header">
            <div class="company-brand">
                @if(isset($company) && $company->logo)
                    <img src="{{ media_url($company->logo) }}" alt="{{ $company->company_name ?? 'Logo' }}" class="company-logo">
                @endif
                <div class="company-info">
                    <h2>{{ $company->company_name ?? 'RHANTECH DIGITAL SOLUTION' }}</h2>
                    <p>{{ $company->tagline ?? 'Software Development, Digital Products & Enterprise IT Solutions' }}</p>
                </div>
            </div>
            <div class="brochure-badge-box">
                <div class="brochure-badge">
                    OFFICIAL PRODUCT BROCHURE
                </div>
                <div class="brochure-id">REF: #{{ strtoupper(substr(md5($product->slug), 0, 8)) }}</div>
            </div>
        </header>

        <!-- Hero Banner Product (Full Gambar Tanpa Terpotong) -->
        @php
            $mainImg = $product->images->where('is_main', true)->first() ?? $product->images->first();
            $mainImgUrl = $mainImg ? asset('storage/' . $mainImg->image_path) : '';
        @endphp
        <div class="project-hero">
            @if($mainImgUrl)
            <div class="project-thumb-wrapper">
                <img src="{{ $mainImgUrl }}" alt="{{ $product->name }}" class="project-thumb">
            </div>
            @endif

            <div class="project-hero-content">
                <div class="project-hero-meta">
                    @if($product->category)
                        <span class="tag tag-category">{{ $product->category->name }}</span>
                    @endif
                    @if($product->type)
                        <span class="tag tag-type">{{ $product->type->name }}</span>
                    @endif
                    @if($product->price)
                        <span class="tag tag-price">
                            Rp{{ number_format($product->discount_price ?: $product->price, 0, ',', '.') }}
                        </span>
                    @endif
                </div>

                <h1 class="project-title">{{ $product->name }}</h1>

                <p class="project-short-desc">
                    {{ Str::limit(strip_tags($product->description), 160) }}
                </p>
            </div>
        </div>

        <!-- 4 Kotak Spesifikasi Cepat Full-Color -->
        <div class="specs-section">
            <div class="specs-grid">
                <!-- Kategori -->
                <div class="spec-card card-blue">
                    <div class="spec-icon">
                        <span class="material-symbols-outlined" style="font-size: 18px;">category</span>
                    </div>
                    <div class="spec-meta">
                        <div class="spec-label">Kategori</div>
                        <div class="spec-value">{{ $product->category?->name ?? 'Software & App' }}</div>
                    </div>
                </div>

                <!-- Tipe / Platform -->
                <div class="spec-card card-indigo">
                    <div class="spec-icon">
                        <span class="material-symbols-outlined" style="font-size: 18px;">devices</span>
                    </div>
                    <div class="spec-meta">
                        <div class="spec-label">Platform</div>
                        <div class="spec-value">{{ $product->type?->name ?? 'Web / Desktop' }}</div>
                    </div>
                </div>

                <!-- Toko / Vendor -->
                <div class="spec-card card-amber">
                    <div class="spec-icon">
                        <span class="material-symbols-outlined" style="font-size: 18px;">storefront</span>
                    </div>
                    <div class="spec-meta">
                        <div class="spec-label">Toko / Official</div>
                        <div class="spec-value">{{ $product->store?->name ?? ($company->company_name ?? 'Rhantech Official') }}</div>
                    </div>
                </div>

                <!-- Kesiapan Sistem -->
                <div class="spec-card card-emerald">
                    <div class="spec-icon">
                        <span class="material-symbols-outlined" style="font-size: 18px;">verified</span>
                    </div>
                    <div class="spec-meta">
                        <div class="spec-label">Kesiapan</div>
                        <div class="spec-value" style="color: #059669;">Siap Pakai & Bergaransi</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body & Detail Produk -->
        <div class="brochure-body">
            <!-- Nilai Tambah / Keunggulan Utama (Dinamis sesuai input produk) -->
            @php
                $brochureHighlights = $product->highlights ?: [
                    ['title' => 'Akses Instan & Cepat', 'icon' => 'speed', 'description' => 'File digital dan lisensi dapat diunduh langsung segera setelah pembayaran berhasil diverifikasi.'],
                    ['title' => 'Aman & Teruji', 'icon' => 'security', 'description' => 'Aplikasi siap pakai, bebas error, serta mudah dipasang pada sistem komputer atau server Anda.'],
                    ['title' => 'Panduan & Dukungan', 'icon' => 'support_agent', 'description' => 'Dilengkapi dokumentasi instalasi lengkap serta dukungan teknis dari vendor terpercaya.'],
                ];
                $bColors = ['#0284c7', '#4f46e5', '#059669', '#d97706'];
            @endphp
            <div class="value-props">
                @foreach(array_slice($brochureHighlights, 0, 3) as $bIdx => $bhl)
                @php $bCol = $bColors[$bIdx % count($bColors)]; @endphp
                <div class="value-card" style="border-left-color: {{ $bCol }};">
                    <h5><span class="material-symbols-outlined" style="font-size: 15px; color: {{ $bCol }};">{{ $bhl['icon'] ?? 'check_circle' }}</span> {{ $bhl['title'] ?? '' }}</h5>
                    <p>{{ $bhl['description'] ?? 'Fitur dan layanan resmi yang disertakan khusus untuk produk digital ini.' }}</p>
                </div>
                @endforeach
            </div>

            <!-- Header Seksi Deskripsi -->
            <div class="section-header-modern">
                <div class="section-header-badge">
                    <span class="material-symbols-outlined" style="font-size: 14px;">description</span>
                    <span>FITUR & SPESIFIKASI</span>
                </div>
                <h3 class="section-header-title">Deskripsi Lengkap & Fitur Produk</h3>
                <div class="section-header-line"></div>
            </div>

            <div class="html-content">
                @if($product->description)
                    {!! $product->description !!}
                @endif
            </div>

            <!-- Tangkapan Layar / Galeri Foto Sistem (3 Gambar Full Card) -->
            @if($product->images && $product->images->count() > 0)
            <div class="section-header-modern" style="margin-top: 20px;">
                <div class="section-header-badge" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                    <span class="material-symbols-outlined" style="font-size: 14px;">gallery_thumbnail</span>
                    <span>GALERI</span>
                </div>
                <h3 class="section-header-title">Tangkapan Layar & Antarmuka Aplikasi</h3>
                <div class="section-header-line"></div>
            </div>

            <div class="gallery-grid">
                @foreach($product->images->take(3) as $idx => $img)
                <div class="gallery-card">
                    <div class="img-box">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Tampilan Layar {{ $idx + 1 }}">
                    </div>
                    <div class="caption">Modul & Tampilan Antarmuka #{{ $idx + 1 }}</div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Call to Action Banner (Full Color Biru Mewah) -->
            <div class="brochure-cta-banner">
                <div class="cta-content">
                    <h4>Tertarik Menggunakan atau Membeli Produk Digital Ini?</h4>
                    <p>Beli sekarang untuk mendapatkan akses instan, atau hubungi kami untuk konsultasi dan demonstrasi fitur.</p>
                </div>
                <div class="cta-contact-pills">
                    <div class="pill-btn">
                        <span class="material-symbols-outlined" style="font-size: 16px;">shopping_cart</span>
                        <span>Beli / Order Online</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Elemen Degradasi Transparan Tampilan Aplikasi di Kanan Bawah Kertas -->
        @if($mainImgUrl)
        <div class="bottom-watermark">
            <img src="{{ $mainImgUrl }}" alt="Watermark Preview">
        </div>
        @endif

        <!-- Footer Mewah Dark -->
        <footer class="brochure-footer">
            <div>
                &copy; {{ date('Y') }} <strong>{{ $company->company_name ?? 'Rhantech Digital' }}</strong>. Dokumen brosur resmi produk digital.
            </div>
            <div class="footer-contacts">
                @if(isset($company->email))
                    <span><span class="icon material-symbols-outlined">mail</span> {{ $company->email }}</span>
                @endif
                @if(isset($company->phone))
                    <span><span class="icon material-symbols-outlined">call</span> {{ $company->phone }}</span>
                @endif
                @if(isset($company->website))
                    <span><span class="icon material-symbols-outlined">language</span> {{ parse_url($company->website, PHP_URL_HOST) ?? $company->website }}</span>
                @else
                    <span><span class="icon material-symbols-outlined">language</span> {{ request()->getHost() }}</span>
                @endif
            </div>
        </footer>
    </div>

    <script>
        // Otomatis buka dialog print / simpan PDF jika dipanggil dengan parameter ?print=1
        window.addEventListener('load', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === '1' || urlParams.has('autoprint')) {
                setTimeout(() => {
                    window.print();
                }, 400);
            }
        });
    </script>
</body>
</html>
