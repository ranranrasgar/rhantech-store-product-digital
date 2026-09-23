<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display Tenant Chat Center Page
     */
    public function index()
    {
        $store = Auth::user()->store;
        if (!$store) {
            return redirect()->route('tenant.store.index')->with('warning', 'Silakan lengkapi profil toko Anda terlebih dahulu.');
        }

        return view('tenant.chat.index', compact('store'));
    }

    /**
     * Get list of conversations for tenant
     */
    public function getConversations(Request $request)
    {
        if (!$request->expectsJson() && !$request->ajax()) {
            return redirect()->route('tenant.chat.index');
        }

        $store = Auth::user()->store;
        if (!$store) {
            return response()->json(['conversations' => [], 'unread_total' => 0]);
        }
        $storeId = $store->id;

        // Optimized: Get the latest message ID for each user chatting with this store
        $latestMessageIds = ChatMessage::where('store_id', $storeId)
            ->selectRaw('MAX(id) as id')
            ->groupBy('user_id')
            ->pluck('id');

        // Fetch those exact messages with the associated user
        $latestMessages = ChatMessage::with('user:id,name,email')
            ->whereIn('id', $latestMessageIds)
            ->get();

        // Get unread counts grouped by user_id
        $unreadCounts = ChatMessage::where('store_id', $storeId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->selectRaw('user_id, count(*) as count')
            ->groupBy('user_id')
            ->pluck('count', 'user_id');

        $conversations = [];
        $unreadTotal = 0;

        foreach ($latestMessages as $msg) {
            if (!$msg->user) continue;
            
            $uCount = $unreadCounts->get($msg->user_id, 0);
            $unreadTotal += $uCount;

            $conversations[] = [
                'user_id' => $msg->user_id,
                'user_name' => $msg->user->name,
                'user_avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($msg->user->name) . '&background=random',
                'user_email' => $msg->user->email,
                'last_message' => $msg->message,
                'last_time' => $msg->created_at->diffForHumans(),
                'last_time_raw' => $msg->created_at,
                'unread_count' => $uCount,
            ];
        }

        usort($conversations, function ($a, $b) {
            return $b['last_time_raw']->timestamp <=> $a['last_time_raw']->timestamp;
        });

        return response()->json([
            'conversations' => $conversations,
            'unread_total' => $unreadTotal,
        ]);
    }

    /**
     * Get chat messages with a specific customer
     */
    public function getMessages(Request $request, $userId)
    {
        if (!$request->expectsJson() && !$request->ajax()) {
            return redirect()->route('tenant.chat.index');
        }

        $store = Auth::user()->store;
        if (!$store) {
            return response()->json(['error' => 'Store not found'], 404);
        }

        $user = User::findOrFail($userId);

        // Mark messages from user as read
        ChatMessage::where('store_id', $store->id)
            ->where('user_id', $userId)
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ChatMessage::with(['product', 'product.images'])
            ->where('store_id', $store->id)
            ->where('user_id', $userId)
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
                    ] : null,
                ];
            });

        return response()->json([
            'customer' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random',
            ],
            'messages' => $messages,
        ]);
    }

    /**
     * Send message from tenant to customer
     */
    public function sendMessage(Request $request)
    {
        $store = Auth::user()->store;
        if (!$store) {
            return response()->json(['error' => 'Toko tidak ditemukan'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $chat = ChatMessage::create([
            'user_id' => $request->user_id,
            'store_id' => $store->id,
            'sender_type' => 'tenant',
            'message' => $request->message,
            'product_id' => $request->product_id,
            'is_read' => false,
        ]);

        // Send push notification to buyer
        $user = User::find($request->user_id);
        if ($user) {
            $firebase = app(\App\Services\FirebaseService::class);
            $firebase->sendNotificationToUser(
                $user,
                'Pesan Baru dari ' . $store->name,
                substr($request->message, 0, 50) . (strlen($request->message) > 50 ? '...' : ''),
                [
                    'type' => 'chat_message',
                    'store_id' => $store->id,
                    'url' => route('home')
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chat->id,
                'sender_type' => $chat->sender_type,
                'message' => $chat->message,
                'created_at' => $chat->created_at->format('H:i'),
            ]
        ]);
    }
}
