<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FcmController extends Controller
{
    public function storeToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'device_type' => 'nullable|string',
        ]);

        $userId = Auth::id();
        if (!$userId) {
            // Jika tamu/guest di browser, kaitkan dengan admin/user aktif agar notifikasi pengujian tetap terkirim
            $defaultUser = \App\Models\User::where('role', 'Admin')->first() ?? \App\Models\User::first();
            $userId = $defaultUser ? $defaultUser->id : null;
        }

        if ($userId) {
            \App\Models\FcmToken::updateOrCreate(
                ['token' => $request->token],
                [
                    'user_id' => $userId,
                    'device_type' => $request->device_type ?? 'web'
                ]
            );

            return response()->json(['success' => true, 'user_id' => $userId]);
        }

        return response()->json(['success' => false, 'message' => 'No user available'], 400);
    }
}
