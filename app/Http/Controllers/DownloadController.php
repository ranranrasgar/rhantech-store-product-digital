<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use App\Models\OrderItem;

class DownloadController extends Controller
{
    public function download($token)
    {
        $order = Order::with('orderItems.product')->where('download_token', $token)->firstOrFail();

        if ($order->status === 'pending' || $order->status === 'failed') {
            abort(403, 'Payment has not been completed.');
        }

        // Update status to downloaded if it was just paid
        if ($order->status === 'paid') {
            $order->update(['status' => 'downloaded']);
        }

        return view('downloads.index', compact('order'));
    }

    public function downloadFile($token, $item_id)
    {
        $order = Order::where('download_token', $token)->firstOrFail();
        
        if ($order->status === 'pending' || $order->status === 'failed') {
            abort(403, 'Payment has not been completed.');
        }

        $orderItem = OrderItem::where('order_id', $order->id)->where('id', $item_id)->firstOrFail();

        if (!$orderItem->product->file_path || !Storage::exists($orderItem->product->file_path)) {
            abort(404, 'The digital product file is missing.');
        }

        return Storage::download($orderItem->product->file_path, $orderItem->product->slug . '.zip');
    }
}
