<!DOCTYPE html>
<html>
<head>
    <title>Your Digital Download</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Thank you for your purchase, {{ $order->customer_name }}!</h2>
    <p>Your payment for <strong>{{ $order->product->name }}</strong> (Invoice: {{ $order->invoice_number }}) has been successfully processed.</p>
    
    <div style="margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-radius: 5px; border-left: 4px solid #06B6D4;">
        <h3 style="margin-top: 0;">Download Your File</h3>
        <p>Please click the button below to download your digital product. Keep this email safe as it contains your secure download link.</p>
        
        <a href="{{ route('products.download', $order->download_token) }}" style="display: inline-block; background-color: #06B6D4; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 5px; font-weight: bold; margin-top: 10px;">
            Download {{ $order->product->name }}
        </a>
    </div>

    <p style="font-size: 0.9em; color: #666;">If the button above does not work, copy and paste this URL into your browser:</p>
    <p style="font-size: 0.9em; color: #666;">{{ route('products.download', $order->download_token) }}</p>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="font-size: 0.8em; color: #999;">This is an automated email, please do not reply.</p>
</body>
</html>
