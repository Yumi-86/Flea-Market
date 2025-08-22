<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;
use app\Providers\RouteServiceProvider;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        } elseif (!$user->profile) {
            return redirect()->route('profile.create');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
