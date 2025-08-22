<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomRedirectIfVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user() && $request->user()->hasVerifiedEmail()) {

            if (session()->has('post_verified_redirect_to')) {
                $redirect = session('post_verified_redirect_to');
                session()->forget('post_verified_redirect_to');
                return redirect($redirect);
            }

            return redirect(config('fortify.home'));
        }

        return $next($request);
    }
    
}
