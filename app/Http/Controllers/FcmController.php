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

        if (Auth::check()) {
            Auth::user()->fcmTokens()->updateOrCreate(
                ['token' => $request->token],
                ['device_type' => $request->device_type ?? 'web']
            );

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }
}
