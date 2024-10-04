<?php

namespace App\Notifications;

use App\Models\TmpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return 'mail';
    }

    /**
     * @param $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $tmpCode = TmpCode::createCode($notifiable);

        return (new MailMessage)
            ->subject('Verify email')
            ->line('Verify your email using this code: ' . $tmpCode)
            ->line('Code will be active for ' . config('auth.code_timeout', 60). ' seconds');
    }
}
