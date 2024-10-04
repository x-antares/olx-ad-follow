<?php

namespace App\Actions;

use App\Models\Ad;
use App\Models\AdSubscription;
use App\Models\User;
use Lorisleiva\Actions\Concerns\AsAction;

final class CreateAdSubscription
{
    use AsAction;

    public function handle(User $user, string $urlWithDomain)
    {
        $adPath = strtok($urlWithDomain, '?');
        $adPath = str_replace(['https://www.olx.ua/d/uk/obyavlenie/', '.html'], '', $adPath);

        $ad = Ad::where('url', $adPath)->first();

        if (is_null($ad)) {
            $ad = Ad::create(['url' => $adPath]);

            ParseAndUpdateAd::run($ad);
        }

        if (is_null(AdSubscription::where('user_id', $user->id)->where('ad_id', $ad->id)->first())) {
            $ad->subscriptions()->create([
                'user_id' => $user->id,
            ]);

            $user->notify(new \App\Notifications\NotifyAdSubscriptionCreated($ad));
        }
    }
}
