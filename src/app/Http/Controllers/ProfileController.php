<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Storage;

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

    public function create()
    {
        $user = Auth::user();
        return view('profile.create', compact('user'));
    }

    public function store(ProfileRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();
        
        $profileData = [
            'postal_code' => $validated['postal_code'],
            'address' => $validated['address'],
            'building' => $validated['building'],
        ];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $profileData['profile_image'] = $path;
        }


        if ($request->filled('name')) {
            $user->name = $validated['name'];
            $user->save();
        }

        $user->profile()->create($profileData);

        return redirect()->route('mypage');
    }

    public function edit()
    {
        $user = Auth::user();

        $profile = optional($user->profile);

        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

        if ($request->hasFile('profile_image')) {
            if ($user->profile && $user->profile->profile_image) {
                Storage::disk('public')->delete($user->profile->profile_image);
            }
            $profile_image = $request->file('profile_image')->store('profile_images', 'public');
        } else {
            $profile_image = optional($user->profile)->profile_image;
        }

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $validated['postal_code'],
                'address' => $validated['address'],
                'building' => $validated['building'],
                'profile_image' => $profile_image,
            ]
        );
        if ($request->filled('name')) {
            $user->name = $validated['name'];
            $user->save();
        }

        return redirect()->route('profile.edit')->with('status', 'プロフィールを更新しました。');
    }
}
