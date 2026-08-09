<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message</title>
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
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px;
        }
        .field {
            margin-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
            padding-bottom: 15px;
        }
        .field:last-child {
            border-bottom: none;
        }
        .label {
            font-size: 12px;
            text-transform: uppercase;
            color: #888888;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .value {
            font-size: 16px;
            color: #222222;
        }
        .message-box {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #06B6D4;
            margin-top: 10px;
            white-space: pre-wrap;
            font-size: 15px;
        }
        .footer {
            background-color: #f8f9ff;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999999;
            border-top: 1px solid #eeeeee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Contact Request</h1>
        </div>
        <div class="content">
            <p style="margin-top: 0; margin-bottom: 25px; font-size: 16px;">
                Hi {{ $contactMessage->name }},<br><br>
                Thank you for contacting us! We have received your message and our team will get back to you as soon as possible.
                <br><br>
                Here is a copy of the message you sent:
            </p>

            <div class="field">
                <div class="label">Subject</div>
                <div class="value" style="font-weight: bold;">{{ $contactMessage->subject }}</div>
            </div>

            <div class="field">
                <div class="label">Message</div>
                <div class="message-box">{{ $contactMessage->message }}</div>
            </div>
            
            <p style="margin-top: 30px; margin-bottom: 0; font-size: 15px; color: #555;">
                Best regards,<br>
                <strong>Our Team</strong>
            </p>
        </div>
        <div class="footer">
            <p style="margin: 0;">This is an automated reply. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
