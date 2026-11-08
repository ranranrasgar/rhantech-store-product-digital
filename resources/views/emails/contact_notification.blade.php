<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ($isAdmin ?? false) ? 'Pesan Kontak Masuk Baru' : 'Terima Kasih Telah Menghubungi Kami' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .header {
            background-color: #06B6D4;
            color: #ffffff;
            padding: 24px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .field {
            margin-bottom: 16px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 12px;
        }
        .field:last-child {
            border-bottom: none;
        }
        .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #888888;
            font-weight: bold;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }
        .value {
            font-size: 15px;
            color: #222222;
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 16px;
            border-radius: 6px;
            border-left: 4px solid #06B6D4;
            margin-top: 8px;
            white-space: pre-wrap;
            font-size: 14px;
        }
        .footer {
            background-color: #f8f9ff;
            text-align: center;
            padding: 16px;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ ($isAdmin ?? false) ? 'Pesan Kontak Masuk Baru' : 'Terima Kasih Telah Menghubungi Kami' }}</h1>
        </div>
        <div class="content">
            @if($isAdmin ?? false)
                <p style="margin-top: 0; margin-bottom: 20px; font-size: 15px;">
                    Halo Admin,<br>
                    Ada pesan baru yang masuk melalui formulir kontak website:
                </p>

                <div class="field">
                    <div class="label">Nama Pengirim</div>
                    <div class="value">{{ $contactMessage->name }}</div>
                </div>

                <div class="field">
                    <div class="label">Email Pengirim</div>
                    <div class="value"><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></div>
                </div>

                <div class="field">
                    <div class="label">Nomor Telepon</div>
                    <div class="value">{{ $contactMessage->phone ?? '-' }}</div>
                </div>

                <div class="field">
                    <div class="label">Subjek</div>
                    <div class="value" style="font-weight: bold;">{{ $contactMessage->subject }}</div>
                </div>

                <div class="field">
                    <div class="label">Pesan</div>
                    <div class="message-box">{{ $contactMessage->message }}</div>
                </div>
            @else
                <p style="margin-top: 0; margin-bottom: 20px; font-size: 15px;">
                    Halo <strong>{{ $contactMessage->name }}</strong>,<br><br>
                    Terima kasih telah menghubungi kami. Pesan Anda dengan subjek <em>"{{ $contactMessage->subject }}"</em> telah kami terima dengan baik.
                    <br><br>
                    Tim kami akan segera meninjau dan menghubungi Anda kembali melalui alamat email ini.
                </p>
                <p style="margin-top: 25px; margin-bottom: 0; font-size: 14px; color: #555;">
                    Salam hangat,<br>
                    <strong>Rhantech</strong>
                </p>
            @endif
        </div>
        <div class="footer">
            <p style="margin: 0;">Email ini dibuat secara otomatis oleh sistem Rhantech.</p>
        </div>
    </div>
</body>
</html>
