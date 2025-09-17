<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class CustomResetPassword extends Notification implements ShouldQueue
{
    use Queueable;

    public $name;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $token, $name = null)
    {
        $this->name = $name;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $expires = Carbon::now()->addMinutes(config('auth.passwords.users.expire', 60));

        $url = URL::temporarySignedRoute(
            'password.reset',
            $expires,
            [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]
        );

        return (new MailMessage)
            ->subject(Lang::get('Đặt lại mật khẩu - TLO Fashion'))
            ->view('emails.password-reset', [
                'name' => $this->name ?? $notifiable->name,
                'verificationUrl' => $url,
                'supportEmail' => 'support@tlofashion.com',
                'expireMinutes' => config('auth.passwords.users.expire', 60)
            ]);
    }
}