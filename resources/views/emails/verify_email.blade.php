<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" border="0" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 35px 30px 25px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                            @php
                                $company = \App\Models\CompanyProfile::first();
                                $companyName = $company->company_name ?? config('app.name', 'Rhantech');
                            @endphp
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; letter-spacing: -0.5px;">
                                {{ $companyName }}
                            </h1>
                            <p style="margin: 6px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 13px; font-weight: 500;">
                                Digital Product Platform & Solution
                            </p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 35px 30px;">
                            <h2 style="margin: 0 0 16px; color: #111827; font-size: 20px; font-weight: 700; line-height: 1.3;">
                                Selamat Datang, {{ $user->name ?? 'Mitra Digital' }}! 👋
                            </h2>
                            <p style="margin: 0 0 20px; color: #4b5563; font-size: 15px; line-height: 1.6;">
                                Terima kasih telah bergabung dengan <strong>{{ $companyName }}</strong>. Untuk memastikan keamanan akun dan mulai mengakses seluruh fitur, produk, serta layanan kami, silakan konfirmasi alamat email Anda dengan menekan tombol di bawah ini:
                            </p>

                            <!-- CTA Button -->
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verificationUrl }}" target="_blank" style="display: inline-block; background-color: #0284c7; color: #ffffff; text-decoration: none; padding: 14px 34px; border-radius: 8px; font-weight: 700; font-size: 15px; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35); letter-spacing: 0.2px;">
                                            Konfirmasi & Verifikasi Email
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Box -->
                            <div style="background-color: #f8fafc; border-left: 4px solid #0284c7; border-radius: 6px; padding: 14px 18px; margin: 25px 0;">
                                <p style="margin: 0; color: #64748b; font-size: 13px; line-height: 1.5;">
                                    💡 <strong>Tips:</strong> Link verifikasi ini berlaku selama 60 menit. Jika Anda tidak merasa mendaftar di {{ $companyName }}, silakan abaikan email ini secara aman.
                                </p>
                            </div>

                            <!-- Link Alternative -->
                            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                                <p style="margin: 0 0 8px; color: #94a3b8; font-size: 12px; line-height: 1.5;">
                                    Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut pada peramban web Anda:
                                </p>
                                <p style="margin: 0; word-break: break-all;">
                                    <a href="{{ $verificationUrl }}" style="color: #0284c7; font-size: 12px; text-decoration: underline;">{{ $verificationUrl }}</a>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 35px; background-color: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center;">
                            <p style="margin: 0 0 6px; color: #64748b; font-size: 13px; font-weight: 600;">
                                Salam Hangat,<br>Tim {{ $companyName }}
                            </p>
                            <p style="margin: 12px 0 0; color: #94a3b8; font-size: 11px;">
                                &copy; {{ date('Y') }} {{ $companyName }}. Seluruh hak cipta dilindungi.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
