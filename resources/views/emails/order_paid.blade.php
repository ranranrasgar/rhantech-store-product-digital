<!DOCTYPE html>
<html>
<head>
    <title>Your Digital Download</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Thank you for your purchase, {{ $order->customer_name }}!</h2>
    <p>Your payment for <strong>{{ $order->product->name }}</strong> (Invoice: {{ $order->invoice_number }}) has been successfully processed.</p>
    
    <div style="margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-radius: 5px; border-left: 4px solid #06B6D4;">
        <h3 style="margin-top: 0;">Download Your File(s)</h3>
        <p>Please use the links below to access your digital product. Keep this email safe.</p>
        
        @if($order->product->file_path)
            <div style="margin-bottom: 15px;">
                <a href="{{ route('products.download', $order->download_token) }}" style="display: inline-block; background-color: #06B6D4; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                    Download Main File (ZIP/RAR)
                </a>
                <div style="font-size: 0.85em; color: #666; margin-top: 5px;">
                    Alternative link: {{ route('products.download', $order->download_token) }}
                </div>
            </div>
        @endif

        @if(!empty($order->product->download_links))
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                <h4 style="margin-top: 0; margin-bottom: 10px;">External Download Links:</h4>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($order->product->download_links as $link)
                    <li style="margin-bottom: 10px;">
                        <a href="{{ $link['url'] ?? '#' }}" style="display: inline-block; background-color: #3b82f6; color: #fff; text-decoration: none; padding: 8px 16px; border-radius: 5px; font-weight: bold; font-size: 0.9em;">
                            {{ $link['name'] ?? 'Download' }}
                        </a>
                        <div style="font-size: 0.85em; color: #666; margin-top: 3px;">
                            {{ $link['url'] ?? '' }}
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="font-size: 0.8em; color: #999;">This is an automated email, please do not reply.</p>
</body>
</html>
