<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use App\Notifications\OtpEmail;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function generate(User $user, int $minutes = 5): string
    {
        // invalidate OTP lama yg belum kepake (optional tapi rapi)
        Otp::where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $code = (string) random_int(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'channel' => 'email',
            'destination' => $user->email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes($minutes),
            'sent_at' => now(),
            'attempts' => 0,
            'max_attempts' => 5,
        ]);

        $user->notify(new OtpEmail($code, $minutes));

        return $code; // return ini cuma buat testing lokal kalau mau, bisa dihapus nanti
    }

    public function verify(User $user, string $code): bool
    {
        /** @var Otp|null $otp */
        $otp = Otp::where('user_id', $user->id)
            ->whereNull('consumed_at')
            ->latest()
            ->first();

        if (!$otp) return false;
        if ($otp->isExpired()) return false;
        if ($otp->attempts >= $otp->max_attempts) return false;

        $otp->increment('attempts');

        if (!Hash::check($code, $otp->code_hash)) {
            return false;
        }

        $otp->update(['consumed_at' => now()]);

        $user->update([
            'status' => 'active',
            'otp_verified_at' => now(),
        ]);

        return true;
    }
}
