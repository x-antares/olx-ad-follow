<?php

namespace App\Notifications;

use App\Models\Ad;
use App\Support\Olx\Olx;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdSubscriptionCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public Ad $ad;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ad $ad)
    {
        $this->ad = $ad;
    }

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
        $ad = $this->ad;

        return (new MailMessage)
            ->subject('Creating Ad subscription')
            ->line('You are successfully tracking Olx ad: ')
            ->line(Olx::API_HOST . '/' . $ad->url . '.html')
            ->line('Ad price now: ' . $ad->external_price);
    }
}
