<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class SocialiteController extends Controller
{
    public function redirect(string $provider): SymfonyRedirect
    {
        abort_unless($provider === 'google', 404);

        return Socialite::driver('google')->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless($provider === 'google', 404);

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('Google OAuth failed', ['error' => $e->getMessage()]);
            return redirect('/login')->withErrors([
                'email' => 'Login dengan Google gagal. Coba lagi atau pakai email & password.',
            ]);
        }

        $email = $googleUser->getEmail();
        $allowed = config('services.google.allowed_domains', []);
        if (! empty($allowed)) {
            $domain = strtolower(substr(strrchr($email ?? '@', '@'), 1));
            if (! in_array($domain, $allowed, true)) {
                return redirect('/login')->withErrors([
                    'email' => 'Email Google harus dari domain yang diizinkan: '.implode(', ', $allowed),
                ]);
            }
        }

        // Cari user existing by google_id, lalu by email.
        $user = User::where('google_id', $googleUser->getId())->first()
            ?? User::where('email', $email)->first();

        if (! $user) {
            return redirect('/login')->withErrors([
                'email' => 'Akun belum terdaftar. Daftar dulu via "Create Account" atau hubungi admin.',
            ]);
        }

        // Auto-link google_id ke user existing yang login pertama kali via Google.
        if (! $user->google_id) {
            $user->forceFill([
                'google_id' => $googleUser->getId(),
                'avatar' => $user->avatar ?: $googleUser->getAvatar(),
            ])->save();
        }

        Auth::login($user, remember: true);

        return redirect('/dashboard');
    }
}
