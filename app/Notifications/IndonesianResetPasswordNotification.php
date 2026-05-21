<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IndonesianResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $token;
    public string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token, string $url)
    {
        $this->token = $token;
        $this->url = $url;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Atur Ulang Kata Sandi - DIKPORA BINTUNI')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Anda menerima email ini karena kami menerima permintaan atur ulang kata sandi untuk akun Anda.')
            ->action('Atur Ulang Kata Sandi', $this->url)
            ->line('Tautan atur ulang kata sandi ini hanya akan berlaku selama 60 menit.')
            ->line('Jika Anda tidak merasa melakukan permintaan ini, tidak ada tindakan lanjutan yang perlu diambil.')
            ->salutation('Salam hangat,' . "\n" . config('app.name', 'DIKPORABINTUNI.COM'));
    }
}
