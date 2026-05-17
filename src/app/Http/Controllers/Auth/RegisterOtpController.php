<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterOtpController extends Controller
{
    public function requestOtp(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users,email'],
            'phone' => ['required','string','max:20','unique:users,phone'],
        ]);

        // Kalau session masih nyangkut (misal user reload), bersihin dulu biar gak kacau
        $request->session()->forget('register_otp_user_id');
        $request->session()->forget('otp_sent');

        // Buat user pending dengan password random (karena kolom password wajib)
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'],
            'status'   => 'pending',
            'password' => Hash::make(StrRandom::strong(32)), // lihat helper di bawah
        ]);

        $otp->generate($user);

        $request->session()->put('register_otp_user_id', $user->id);

        return back()
            ->withInput($request->only('name','email','phone'))
            ->with('otp_sent', true)
            ->with('status', 'OTP sudah dikirim ke email kamu. Cek Mailpit.');
    }

    public function verifyOtp(Request $request, OtpService $otp)
    {
        $data = $request->validate([
            'code' => ['required','digits:6'],
            'password' => ['required','string','min:8','confirmed'],
        ]);

        $userId = $request->session()->get('register_otp_user_id');
        if (!$userId) {
            throw ValidationException::withMessages([
                'code' => 'Session OTP tidak ditemukan. Klik Kirim OTP lagi.',
            ]);
        }

        $user = User::findOrFail($userId);

        // Verifikasi OTP (di service lu: set status active + otp_verified_at)
        if (!$otp->verify($user, $data['code'])) {
            return back()
                ->withInput($request->only('name','email','phone'))
                ->with('otp_sent', true)
                ->withErrors(['code' => 'OTP salah / expired.']);
        }

        // Set password final setelah OTP valid
        $user->update([
            'otp_verified_at' => now(),
            'status' => 'active',
            'password' => Hash::make($data['password']),
        ]);

        auth()->login($user);

        $request->session()->forget('register_otp_user_id');

        return redirect()->route('dashboard');
    }

    public function resend(Request $request, OtpService $otp)
    {
        $userId = $request->session()->get('register_otp_user_id');
        if (!$userId) {
            return back()->withErrors([
                'code' => 'Session OTP tidak ditemukan. Klik Kirim OTP lagi.',
            ]);
        }

        $user = User::findOrFail($userId);

        $otp->generate($user);

        return back()
            ->withInput($request->only('name','email','phone'))
            ->with('otp_sent', true)
            ->with('status', 'OTP baru sudah dikirim.');
    }
}

/**
 * Helper kecil buat random password sementara.
 * (Biar gak perlu mikir panjang, dan gak bocor.)
 */
final class StrRandom
{
    public static function strong(int $length = 32): string
    {
        // random_bytes -> base64 -> buang karakter non-alnum biar aman
        $raw = base64_encode(random_bytes($length));
        return substr(preg_replace('/[^a-zA-Z0-9]/', '', $raw), 0, $length);
    }
}
