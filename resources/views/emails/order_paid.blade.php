<!DOCTYPE html>
<html>
<head>
    <title>Your Digital Downloads</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Thank you for your purchase, {{ $order->customer_name }}!</h2>
    <p>Your payment for Order <strong>{{ $order->invoice_number }}</strong> has been successfully processed.</p>
    
    <div style="margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-radius: 5px; border-left: 4px solid #06B6D4;">
        <h3 style="margin-top: 0;">Download Your Files</h3>
        <p>Please use the link below to access your digital products. Keep this email safe.</p>
        
        <div style="margin-bottom: 15px;">
            <a href="{{ route('products.download', $order->download_token) }}" style="display: inline-block; background-color: #06B6D4; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                View My Downloads
            </a>
            <div style="font-size: 0.85em; color: #666; margin-top: 5px;">
                Alternative link: {{ route('products.download', $order->download_token) }}
            </div>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="font-size: 0.8em; color: #999;">This is an automated email, please do not reply.</p>
</body>
</html>
