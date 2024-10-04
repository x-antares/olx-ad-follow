<?php

namespace App\Notifications;

use App\Models\Ad;
use App\Support\Olx\Olx;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdPriceChanged extends Notification implements ShouldQueue
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
            ->subject('Ad price changed')
            ->line('Ad price changed to: ' . $ad->external_price)
            ->line('Visit your subscribed Olx ad for check: ')
            ->line(Olx::API_HOST . '/' . $ad->url . '.html');
    }
}
