<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();

        $profile = optional($user->profile);

        $activeTab = $request->query('tab', 'sell');

        $buyProducts = $user->purchases()->latest()->get();

        $sellProducts = $user->products()->latest()->get();

        return view('profile.mypage', compact('user', 'profile', 'activeTab', 'buyProducts', 'sellProducts',));
    }
}
