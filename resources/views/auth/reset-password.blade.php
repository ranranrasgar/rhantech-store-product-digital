<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('components.theme-init')
    <title>Reset Password — {{ $company->company_name ?? 'rhantech' }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        body {
            margin: 0; padding: 0;
            min-height: 100vh;
            background: #060d1a;
            display: flex;
            overflow: hidden;
        }

        /* ── Animated background ── */
        .bg-grid {
            position: fixed; inset: 0; z-index: 0;
            background-image:
                linear-gradient(rgba(0,212,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(90px); opacity: 0.25; pointer-events: none;
        }
        .orb-1 { width: 500px; height: 500px; background: #00b3cc; top: -100px; left: -100px; animation: float1 8s ease-in-out infinite; }
        .orb-2 { width: 400px; height: 400px; background: #0056a0; bottom: -100px; right: -50px; animation: float2 10s ease-in-out infinite; }
        .orb-3 { width: 250px; height: 250px; background: #00d4ff; top: 40%; left: 60%; animation: float3 6s ease-in-out infinite; opacity: 0.1; }

        @keyframes float1 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(40px,30px)} }
        @keyframes float2 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(-30px,-40px)} }
        @keyframes float3 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(20px,-20px)} }

        /* ── Branding panel (now on right) ── */
        .branding-panel {
            flex: 1; display: flex; flex-direction: column;
            justify-content: center; align-items: flex-start; 
            padding: 60px 80px; position: relative; z-index: 1;
        }
        .branding-content {
            display: flex; flex-direction: column;
            align-items: flex-start; text-align: left;
            max-width: 420px;
        }
        .brand-logo {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 60px; text-decoration: none;
        }
        .brand-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: #00d4ff;
            box-shadow: 0 0 14px #00d4ff;
            animation: pulse-d 2s ease-in-out infinite;
        }
        @keyframes pulse-d {
            0%,100%{box-shadow:0 0 10px #00d4ff}
            50%{box-shadow:0 0 24px #00d4ff,0 0 40px rgba(0,212,255,0.4)}
        }
        .brand-name {
            font-size: 24px; font-weight: 900; letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff, #a8e6f0);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .branding-headline {
            font-size: clamp(32px, 3.5vw, 52px);
            font-weight: 900; line-height: 1.1;
            letter-spacing: -1px;
            color: #fff; margin-bottom: 20px;
        }
        .branding-headline span {
            background: linear-gradient(135deg, #00d4ff, #00b3cc);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .branding-sub {
            color: rgba(255,255,255,0.5);
            font-size: 16px; line-height: 1.6;
            max-width: 380px; margin-bottom: 48px;
        }
        .feature-item {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px; color: rgba(255,255,255,0.65); font-size: 14px;
        }
        .feature-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(0,212,255,0.12); border: 1px solid rgba(0,212,255,0.2);
            display: flex; align-items: center; justify-content: center;
            color: #00d4ff; font-size: 16px; flex-shrink: 0;
        }
        .material-symbols-outlined { font-family: 'Material Symbols Outlined'; font-size: inherit; font-weight: normal; font-style: normal; line-height: 1; display: inline-block; }

        /* ── Form panel (now on left) ── */
        .login-panel {
            width: 100%; max-width: 460px;
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255,255,255,0.07);
            display: flex; flex-direction: column;
            justify-content: center; padding: 60px 50px;
            position: relative; z-index: 1;
            overflow-y: auto;
        }
        .login-panel::-webkit-scrollbar { width: 6px; }
        .login-panel::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 3px; }
        
        .card-title {
            font-size: 26px; font-weight: 800; color: #fff;
            margin-bottom: 6px; letter-spacing: -0.5px;
        }
        .card-sub {
            color: rgba(255,255,255,0.45); font-size: 14px;
            margin-bottom: 36px; line-height: 1.5;
        }
        .form-label {
            display: block; font-size: 12px; font-weight: 600;
            color: rgba(255,255,255,0.55); letter-spacing: 0.5px;
            text-transform: uppercase; margin-bottom: 8px;
        }
        input.form-input {
            width: 100%;
            background: rgba(255,255,255,0.06) !important;
            border: 1.5px solid rgba(255,255,255,0.1) !important;
            border-radius: 10px;
            color: #fff !important; font-size: 15px;
            padding: 13px 16px;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        input.form-input::placeholder { color: rgba(255,255,255,0.25) !important; }
        input.form-input:focus {
            border-color: #00d4ff !important;
            background: rgba(0,212,255,0.05) !important;
            box-shadow: 0 0 0 3px rgba(0,212,255,0.12) !important;
        }
        input.form-input:-webkit-autofill,
        input.form-input:-webkit-autofill:hover, 
        input.form-input:-webkit-autofill:focus, 
        input.form-input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #0f1623 inset !important;
            -webkit-text-fill-color: #fff !important;
            transition: background-color 5000s ease-in-out 0s;
        }
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #00b3cc, #0077a8);
            color: #fff; font-size: 15px; font-weight: 700;
            border: none; border-radius: 10px;
            padding: 14px; cursor: pointer;
            transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 20px rgba(0,179,204,0.35);
            letter-spacing: 0.3px;
        }
        .submit-btn:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            box-shadow: 0 8px 28px rgba(0,179,204,0.45);
        }
        .submit-btn:active { transform: translateY(0); }
        .error-box {
            background: rgba(239,68,68,0.12);
            border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5; border-radius: 10px;
            padding: 12px 16px; font-size: 13px;
            margin-bottom: 24px;
        }

        /* Mobile */
        @media (max-width: 768px) {
            body { flex-direction: column; overflow: auto; }
            .branding-panel { display: none; }
            .login-panel {
                max-width: 100%; border-right: none;
                border-top: 1px solid rgba(255,255,255,0.07);
                padding: 40px 28px;
                min-height: 100vh;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    {{-- LEFT — Reset Password form --}}
    <div class="login-panel">
        <div class="card-title">Password Baru 🗝️</div>
        <div class="card-sub">Silakan buat password baru yang kuat untuk akun Anda.</div>

        @if($errors->any())
            <div class="error-box">
                <ul style="list-style-type: disc; padding-left: 20px; margin: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div style="margin-bottom: 16px;">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $request->email) }}"
                    class="form-input" placeholder="nama@email.com" required autofocus>
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password"
                    class="form-input" placeholder="••••••••" required>
            </div>

            <div style="margin-bottom: 28px;">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                    class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="submit-btn">
                Simpan Password Baru
            </button>
        </form>
    </div>

    {{-- RIGHT — Branding panel --}}
    <div class="branding-panel">
        <div class="branding-content">
            <a href="{{ url('/') }}" class="brand-logo">
                <span class="brand-dot"></span>
                <span class="brand-name">{{ $company->company_name ?? 'rhantech' }}</span>
            </a>

            <h1 class="branding-headline">
                Platform<br>Produk <span>Digital</span><br>Terpercaya
            </h1>
            <p class="branding-sub">
                Amankan kembali akses ke akun Anda untuk terus mengelola produk digital dan memantau transaksi.
            </p>

            <div>
                <div class="feature-item">
                    <div class="feature-icon"><span class="material-symbols-outlined" style="font-size:16px">verified</span></div>
                    Produk digital berkualitas tinggi & terverifikasi
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><span class="material-symbols-outlined" style="font-size:16px">bolt</span></div>
                    Transaksi instan, aman & terpercaya
                </div>
                <div class="feature-item">
                    <div class="feature-icon"><span class="material-symbols-outlined" style="font-size:16px">headset_mic</span></div>
                    Dukungan pelanggan 24 jam non-stop
                </div>
            </div>
        </div>
    </div>

    @include('components.theme-manager')
</body>
</html>
