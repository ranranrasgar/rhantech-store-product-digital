<!DOCTYPE html>
<html>
<head>
    <title>Your Digital Downloads</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Thank you for your purchase, {{ $order->customer_name }}!</h2>
    <p>Your payment for Order <strong>{{ $order->invoice_number }}</strong> has been successfully processed.</p>
    
    <div style="margin: 20px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px; border-left: 4px solid #06B6D4;">
        <h3 style="margin-top: 0; color: #111;">Akses Produk Digital Anda</h3>
        <p style="color: #555; margin-bottom: 15px;">Terima kasih atas pesanan Anda. Anda dapat mengunduh atau mengakses link file digital langsung di bawah ini atau melalui Portal Unduhan resmi kami:</p>
        
        @php
            $order->loadMissing('orderItems.product');
        @endphp

        @if($order->orderItems && $order->orderItems->count() > 0)
            <div style="margin-bottom: 20px;">
                @foreach($order->orderItems as $item)
                    @if($item->product)
                        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; padding: 14px; margin-bottom: 10px;">
                            <div style="font-weight: bold; font-size: 15px; color: #1f2937; margin-bottom: 8px;">
                                📦 {{ $item->product->name }}
                            </div>
                            
                            <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 6px;">
                                {{-- Direct Link File ZIP/RAR jika diupload ke server --}}
                                @if($item->product->file_path)
                                    <a href="{{ route('products.download.file', ['token' => $order->download_token, 'item' => $item->id]) }}" style="display: inline-block; background-color: #06B6D4; color: #ffffff; text-decoration: none; padding: 8px 14px; border-radius: 4px; font-weight: bold; font-size: 13px; margin-right: 6px; margin-bottom: 6px;">
                                        ⬇️ Unduh File (.ZIP)
                                    </a>
                                @endif

                                {{-- Direct External Links (Google Drive, Mega, dsb) --}}
                                @if(!empty($item->product->download_links) && is_array($item->product->download_links))
                                    @foreach($item->product->download_links as $link)
                                        <a href="{{ $link['url'] }}" target="_blank" style="display: inline-block; background-color: #2563EB; color: #ffffff; text-decoration: none; padding: 8px 14px; border-radius: 4px; font-weight: bold; font-size: 13px; margin-right: 6px; margin-bottom: 6px;">
                                            🔗 Buka {{ $link['name'] ?: 'Link Unduhan' }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px dashed #d1d5db;">
            <p style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">Atau buka halaman portal unduhan lengkap (Download Center):</p>
            <a href="{{ route('products.download', $order->download_token) }}" style="display: inline-block; background-color: #0F172A; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 5px; font-weight: bold; font-size: 14px;">
                Buka Pusat Unduhan Web
            </a>
            <div style="font-size: 0.8em; color: #6b7280; margin-top: 6px;">
                Link alternatif: <a href="{{ route('products.download', $order->download_token) }}" style="color: #06B6D4;">{{ route('products.download', $order->download_token) }}</a>
            </div>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
    <p style="font-size: 0.8em; color: #999;">This is an automated email, please do not reply.</p>
</body>
</html>
