<?php

namespace App\Actions;

use App\Models\Ad;
use App\Models\AdSubscription;
use Illuminate\Support\Facades\Notification;
use Lorisleiva\Actions\Concerns\AsAction;

final class SendNotificationAdPriceChanged
{
    use AsAction;

    public function handle(Ad $ad)
    {
        $subscribersEmail = AdSubscription::where('ad_id', $ad->id)
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'ad_subscriptions.user_id');
            })
            ->select(['users.email'])
            ->pluck('email')
            ->toArray();

        if (count($subscribersEmail)) {
            Notification::route('mail', $subscribersEmail)
                ->notify(new \App\Notifications\NotifyAdPriceChanged($ad));
        }
    }
}
