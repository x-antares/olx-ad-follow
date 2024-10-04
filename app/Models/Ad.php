<?php

namespace App\Models;

use App\Actions\SendNotificationAdPriceChanged;
use App\Http\Traits\HasUuidTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    use HasUuidTrait;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        self::updated(static function (self $ad): void {
            if ($ad->isDirty('external_price')) {
                SendNotificationAdPriceChanged::run($ad);
            }
        });
    }

    /**
     * @return HasMany
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(AdSubscription::class);
    }
}
