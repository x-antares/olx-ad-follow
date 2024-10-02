<?php

namespace App\Http\Controllers;

use App\Models\TmpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
           'email' => 'string',
        ]);

        /** @var User $user */
        $user = User::firstOrCreate(['email' => $request->email]);

        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'This email already verified.']);
        }

        $user->notify(new \App\Notifications\VerifyEmail());
    }

    public function verify(Request $request)
    {
        $request->validate([
           'code' => 'string',
           'email' => 'email',
        ]);

        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        /** @var TmpCode $tmpCode */
        $tmpCode = $user->tmpCodes()->byCreatedAt()->where('code', $request->code)->first();

        if (is_null($tmpCode)) {
            throw ValidationException::withMessages(['code' => 'Verification code is incorrect.']);
        }

        $user->markEmailAsVerified();
        $tmpCode->useCode();
    }
}
