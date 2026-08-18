<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to OAuth provider
     */
    public function redirectToProvider(string $provider)
    {
        if ($provider !== 'google') {
            return redirect()->route('login')->withErrors(['email' => 'Provider login tidak didukung saat ini.']);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle OAuth callback from provider
     */
    public function handleProviderCallback(string $provider)
    {
        if ($provider !== 'google') {
            return redirect()->route('login')->withErrors(['email' => 'Provider login tidak didukung saat ini.']);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();

            $email = $socialUser->getEmail();
            if (!$email) {
                return redirect()->route('login')->withErrors(['email' => 'Email dari akun Google tidak ditemukan.']);
            }

            // 1. Check if user already exists with this email or provider ID
            $user = User::query()->where('email', $email)->first();

            if ($user) {
                // Update Google info if not yet attached
                $user->update([
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar() ?? $user->avatar,
                    // If not verified, verify immediately via Google OAuth
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ]);
            } else {
                // 2. Create new user account
                $user = User::query()->create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Pengguna Google',
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'avatar' => $socialUser->getAvatar(),
                    'email_verified_at' => now(),
                    'role' => 'User',
                ]);
            }

            // 3. Log in the user
            Auth::login($user, true);

            // 4. Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->intended('/admin/dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            return redirect()->intended('/')->with('success', 'Berhasil masuk dengan akun Google.');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }
    }
}
