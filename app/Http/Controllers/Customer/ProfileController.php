<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\CustomerProfile;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('customer.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    // Nyongeza: Update Password
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

   public function updatePhoto(Request $request) {
    $request->validate(['profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
    
    $user = auth()->user();
    
    // Futa picha ya zamani kama ipo
    $oldProfile = $user->customerProfile;
    if ($oldProfile && $oldProfile->profile_photo) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldProfile->profile_photo);
    }
    
    $path = $request->file('profile_photo')->store('profiles', 'public');

    $user->customerProfile()->updateOrCreate(
        ['user_id' => $user->id],
        ['profile_photo' => $path]
    );

    return back()->with('success', 'Profile picture updated!');
}
}