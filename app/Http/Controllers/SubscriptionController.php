<?php

namespace App\Http\Controllers;

use App\Actions\CreateAdSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
           'url' => 'string|required',
           'email' => 'string|required|email:strict',
        ]);

        $adPath = $this->getUrlPath($request->url);

        if (!$adPath) {
            throw ValidationException::withMessages(['url' => 'This url is invalid.']);
        }

        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user?->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'Unverified email address.']);
        }

        CreateAdSubscription::run($user, $adPath);
    }

    /**
     * @param string $url
     * @return string|null
     */
    protected function getUrlPath(string $url)
    {
        $pattern = '/^https:\/\/www\.olx\.ua\/d\/uk\/obyavlenie\/[a-z0-9\-]+-[a-z0-9\-]+-[a-z0-9\-]+-[A-Za-z0-9]+\.html(\?.*)?$/';

        if (preg_match($pattern, $url)) {
            $urlPath = strtok($url, '?');

            return str_replace(['https://www.olx.ua/d/uk/obyavlenie/', '.html'], '', $urlPath);
        }

        return null;
    }
}
