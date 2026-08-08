<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download($token)
    {
        $order = Order::with('product')->where('download_token', $token)->firstOrFail();

        if ($order->status === 'pending' || $order->status === 'failed') {
            abort(403, 'Payment has not been completed.');
        }

        if (!$order->product->file_path || !Storage::exists($order->product->file_path)) {
            abort(404, 'The digital product file is missing.');
        }

        // Update status to downloaded if it was just paid
        if ($order->status === 'paid') {
            $order->update(['status' => 'downloaded']);
        }

        return Storage::download($order->product->file_path, $order->product->slug . '.zip');
    }
}
