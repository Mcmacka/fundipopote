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
        $profile = $user->technicianProfile;
        $categories = Category::where('is_active', true)->get();

        return view('technician.profile.edit', compact('user', 'profile', 'categories'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
        
        // Tunavuta profile kwa kutumia Eager Loading pattern kupitia user relation
        $profile = $user->technicianProfile;

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'phone'             => 'nullable|string|max:15',
            'profile_picture'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Jina la input kutoka kwenye Blade Form
            'category_id'       => 'required|exists:categories,id',
            'bio'               => 'nullable|string|max:500',
            'years_experience'  => 'required|integer|min:0|max:50',
            'id_number'         => 'nullable|string|max:50',
            'location_name'     => 'required|string|max:255',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
            'service_radius_km' => 'required|integer|min:1|max:100',
        ]);

        // 1. Update miundombinu ya msingi ya User infrastructure
        $user->update([
            'name'  => $validated['name'],
            'phone' => $validated['phone'],
        ]);

        // 2. Maandalizi ya data za meza ya muundo wa TechnicianProfile
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

        // 3. Kuchakata na kusafisha faili la picha kwenye Storage Pipeline
        if ($request->hasFile('profile_picture')) {
            
            // Futa picha ya zamani kwa kutumia uwanja sahihi wa database 'profile_photo'
            if ($profile && $profile->profile_photo && Storage::disk('public')->exists($profile->profile_photo)) {
                Storage::disk('public')->delete($profile->profile_photo);
            }

            // Hifadhi picha mpya kwenye disk ya public
            $path = $request->file('profile_picture')->store('profiles', 'public');
            
            // MAPENDEKEZO: Hapa tunaiweka path kwenye key ya 'profile_photo' ambayo ipo kwenye $fillable ya Model
            $profileData['profile_photo'] = $path;
        }

        // 4. Kuhifadhi rekodi mpya au kufanya mabadiliko kwenye database
        TechnicianProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

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