<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (is_null($user->otp_verified_at)) {
            auth()->logout();

            return redirect()
                ->route('register')
                ->withErrors([
                    'otp' => 'Akun kamu belum verifikasi OTP.'
                ]);
        }

        return $next($request);
    }
}
