<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
           'url' => 'string',
           'email' => 'string',
        ]);

        if ($url = $this->getUrlPath($request->url)) {
            throw ValidationException::withMessages(['url' => 'This url is invalid.']);
        }

        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        if (!is_null($user) && !$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'Unverified email address.']);
        }

        $ad = Ad::create([
            'url' => $url,
        ]);

        $ad->subscriptions()->create([
            'url' => $url,
            'user_id' => $user->id,
        ]);
    }

    public function getUrlPath(string $url)
    {
        $pattern = '/^https:\/\/www\.olx\.ua\/d\/uk\/obyavlenie\/[a-z0-9\-]+-[a-z0-9\-]+-[a-z0-9\-]+-[A-Za-z0-9]+\.html$/';

        if (preg_match($pattern, $url)) {
            return str_replace(['https://www.olx.ua/d/uk/obyavlenie/', '.html'], '', $url);
        }

        return '';
    }
}
