<?php

namespace App\Actions;

use App\Models\Ad;
use App\Support\Olx\Olx;
use GuzzleHttp\Exception\GuzzleException;
use Lorisleiva\Actions\Concerns\AsAction;

final class ParseAndUpdateAd
{
    use AsAction;

    /**
     * @throws GuzzleException
     */
    public function handle(Ad $ad)
    {
        $olx = new Olx();

        if ($price = $olx->parseAdPriceByPath($ad->url)) {
            if ($price !== $ad->external_price) {
                $ad->update([
                    'external_price' => $price,
                ]);
            }
        }
    }
}
