<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Get list of conversations for logged-in user (buyer side)
     */
    public function getConversations(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['conversations' => [], 'unread_total' => 0]);
        }

        $userId = Auth::id();

        // Get all stores this user has chatted with
        $storeIds = ChatMessage::where('user_id', $userId)
            ->distinct('store_id')
            ->pluck('store_id');

        $conversations = [];
        $unreadTotal = 0;

        foreach ($storeIds as $storeId) {
            $store = Store::find($storeId);
            if (!$store) continue;

            $lastMessage = ChatMessage::where('user_id', $userId)
                ->where('store_id', $storeId)
                ->latest()
                ->first();

            $unreadCount = ChatMessage::where('user_id', $userId)
                ->where('store_id', $storeId)
                ->where('sender_type', 'tenant')
                ->where('is_read', false)
                ->count();

            $unreadTotal += $unreadCount;

            $conversations[] = [
                'store_id' => $store->id,
                'store_name' => $store->name,
                'store_logo' => $store->logo ? asset('storage/' . $store->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($store->name) . '&background=00838f&color=fff',
                'store_slug' => $store->slug,
                'last_message' => $lastMessage ? $lastMessage->message : '',
                'last_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                'last_time_raw' => $lastMessage ? $lastMessage->created_at : now(),
                'unread_count' => $unreadCount,
            ];
        }

        // Sort conversations by latest message time
        usort($conversations, function ($a, $b) {
            return strtotime($b['last_time_raw']) - strtotime($a['last_time_raw']);
        });

        return response()->json([
            'conversations' => $conversations,
            'unread_total' => $unreadTotal,
        ]);
    }

    /**
     * Get chat messages between logged-in user and a specific store
     * 
     * @param \Illuminate\Http\Request $request
     * @param int|string $storeId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessages(Request $request, $storeId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $userId = Auth::id();
        $store = Store::findOrFail($storeId);

        // Mark messages from tenant as read
        ChatMessage::where('user_id', $userId)
            ->where('store_id', $storeId)
            ->where('sender_type', 'tenant')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ChatMessage::with('product')
            ->where('user_id', $userId)
            ->where('store_id', $storeId)
            ->orderBy('created_at', 'asc')
            ->take(100)
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'message' => $msg->message,
                    'is_read' => $msg->is_read,
                    'created_at' => $msg->created_at->format('H:i'),
                    'date_group' => $msg->created_at->translatedFormat('l, d M Y'),
                    'product' => $msg->product ? [
                        'id' => $msg->product->id,
                        'name' => $msg->product->name,
                        'price' => number_format($msg->product->price, 0, ',', '.'),
                        'image' => $msg->product->images->first() ? asset('storage/' . $msg->product->images->first()->image_path) : null,
                        'url' => route('products.show', $msg->product->slug ?? $msg->product->id),
                    ] : null,
                ];
            });

        return response()->json([
            'store' => [
                'id' => $store->id,
                'name' => $store->name,
                'logo' => $store->logo ? asset('storage/' . $store->logo) : 'https://ui-avatars.com/api/?name=' . urlencode($store->name) . '&background=00838f&color=fff',
                'slug' => $store->slug,
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * Send message from customer to tenant
     */
    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Silakan login terlebih dahulu untuk mengirim pesan.'], 401);
        }

        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'message' => 'required|string|max:2000',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $userId = Auth::id();
        $store = Store::findOrFail($request->store_id);

        $chat = ChatMessage::create([
            'user_id' => $userId,
            'store_id' => $store->id,
            'sender_type' => 'user',
            'message' => $request->message,
            'product_id' => $request->product_id,
            'is_read' => false,
        ]);

        // Send push notification to tenant
        $firebase = app(\App\Services\FirebaseService::class);
        $firebase->sendNotificationToUser(
            $store->user,
            'Pesan Baru dari ' . Auth::user()->name,
            substr($request->message, 0, 50) . (strlen($request->message) > 50 ? '...' : ''),
            [
                'type' => 'chat_message',
                'store_id' => $store->id,
                'user_id' => $userId,
                'url' => route('tenant.chat.index')
            ]
        );

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chat->id,
                'sender_type' => $chat->sender_type,
                'message' => $chat->message,
                'created_at' => $chat->created_at->format('H:i'),
                'product' => $chat->product ? [
                    'id' => $chat->product->id,
                    'name' => $chat->product->name,
                    'price' => number_format($chat->product->price, 0, ',', '.'),
                ] : null,
            ]
        ]);
    }
}
