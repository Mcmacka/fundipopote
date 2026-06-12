<?php

namespace App\Http\Controllers\Technician;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TechnicianProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
{
    $user = auth()->user();
    
    // Tumia withoutGlobalScopes() ili kuweza kuona profile hata kama subscription siyo active
    $profile = TechnicianProfile::withoutGlobalScopes()
                                ->where('user_id', $user->id)
                                ->first();
                                
    $categories = Category::where('is_active', true)->get();

    return view('technician.profile.edit', compact('user', 'profile', 'categories'));
}

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        // Tunavuta profile iliyopo
        $profile = $user->technicianProfile;

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:15',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category_id'       => 'required|exists:categories,id',
            'bio'               => 'nullable|string|max:500',
            'years_experience'  => 'required|integer|min:0|max:50',
            'id_number'         => 'nullable|string|max:50',
            'location_name'     => 'required|string|max:255',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'service_radius_km' => 'required|integer|min:1|max:100',
        ]);

        // 1. Update User info
        $user->update([
            'name'  => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        // 2. Data za Profile
        $profileData = [
            'category_id'       => $validated['category_id'],
            'bio'               => $validated['bio'],
            'years_experience'  => $validated['years_experience'],
            'id_number'         => $validated['id_number'],
            'location_name'     => $validated['location_name'],
            'latitude'          => $validated['latitude'],
            'longitude'         => $validated['longitude'],
            'service_radius_km' => $validated['service_radius_km'],
        ];

        // 3. Picha
        if ($request->hasFile('profile_picture')) {
            if ($profile && $profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                Storage::disk('public')->delete($profile->profile_photo);
            }
            $profileData['profile_photo'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        // 4. Hapa ndipo marekebisho yalipo
       $profile = TechnicianProfile::withoutGlobalScopes()
                                    ->where('user_id', $user->id)
                                    ->first();

        if ($profile) {
            // Update rekodi iliyopo (haitagusa documents za zamani)
            $profile->update($profileData);
        } else {
            // Kama haipo, tengeneza mpya (ila hii mara nyingi haitatokea kama profile ipo)
            $user->technicianProfile()->create($profileData);
        }

        return redirect()
            ->route('technician.profile.edit')
            ->with('success', 'Your profile has been updated! Customers can now find you.');
    }

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

}