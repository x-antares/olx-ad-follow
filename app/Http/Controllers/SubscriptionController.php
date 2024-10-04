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
           'url' => 'string|required|regex:/^https:\/\/www\.olx\.ua\/d\/uk\/obyavlenie\/[a-z0-9\-]+-[a-z0-9\-]+-[a-z0-9\-]+-[A-Za-z0-9]+\.html(\?.*)?$/',
           'email' => 'string|required|email:strict',
        ]);

        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user || !$user?->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'Unverified email address.']);
        }

        CreateAdSubscription::run($user, $request->url);
    }
}
