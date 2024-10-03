<?php

namespace App\Actions;

use App\Models\Ad;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Console\Command;

final class OlxTrackingAdPrice
{
    use AsAction;

    public string $commandSignature = 'sync:ad-info';

    public function asComand(Command $command)
    {
        $this->handle($command);
    }


    public function handle($command)
    {
        Ad::chunk(100, function($ads) {
            /** @var Ad $ad */
            foreach ($ads as $ad) {
                ParseAndUpdateAd::dispatch($ad);
           }
        });
    }
}
