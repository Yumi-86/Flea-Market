<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return app(LoginResponse::class);
        }

        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません。',
        ]);
    }
}
