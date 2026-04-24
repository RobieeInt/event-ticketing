<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OtpEmail extends Notification
{
    use Queueable;

    public function __construct(
        public string $code,
        public int $minutes = 5
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode OTP Verifikasi Akun')
            ->greeting('Halo!')
            ->line('Berikut kode OTP untuk verifikasi akun kamu:')
            ->line("**{$this->code}**")
            ->line("Kode berlaku {$this->minutes} menit.")
            ->line('Kalau bukan kamu yang minta, abaikan email ini.');
    }
}
