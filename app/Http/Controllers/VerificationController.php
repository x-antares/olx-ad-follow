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
           'email' => 'string|required|email:strict|unique:users',
        ]);

        /** @var User $user */
        $user = User::create(['email' => $request->email]);

        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'This email already verified.']);
        }

        $user->notify(new \App\Notifications\VerifyEmail());
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'integer|required',
            'email' => 'string|required|email:strict',
        ]);

        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages(['email' => 'Invalid email address.']);
        }

        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages(['email' => 'This email already verified.']);
        }

        /** @var TmpCode $tmpCode */
        $tmpCode = $user->tmpCodes()->byCreatedAt()->where('code', $request->code)->first();

        if (!$tmpCode) {
            throw ValidationException::withMessages(['code' => 'Verification code is incorrect.']);
        }

        $user->markEmailAsVerified();
        $tmpCode->useCode();
    }
}
