@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h1>

  <div class="mb-6 p-6 rounded-2xl border border-white/40 shadow-xl" 
     style="background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);">
    <h2 class="text-lg font-semibold mb-4 text-white">Profile Picture</h2>
    
    <form action="{{ route('customer.profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-6">
        @csrf
        @method('PATCH')
        
        <label for="profile_photo_input" class="cursor-pointer group relative">
            @if($user->customerProfile?->profile_photo)
                <img src="{{ asset('storage/'.$user->customerProfile->profile_photo) }}" 
                     class="w-24 h-24 rounded-full object-cover border-4 border-white/20 shadow-lg group-hover:opacity-75 transition">
            @else
                {{-- Hapa ndipo rangi ya herufi inatokea --}}
                <div class="w-24 h-24 rounded-full flex items-center justify-center border-4 border-white/20 shadow-lg text-white text-3xl font-bold uppercase transition group-hover:opacity-75"
                     style="background-color: {{ '#' . substr(md5($user->name), 0, 6) }};">
                     {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 text-white bg-black/40 rounded-full transition">
                Change
            </div>
        </label>
        
        <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" onchange="this.form.submit()">
        
        <div class="text-white text-sm">
            <p class="font-medium">Click your photo to change</p>
            
        </div>
    </form>
</div>


    {{-- Form ya Taarifa za Msingi --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm mb-6">
        <h2 class="text-lg font-semibold mb-4">Personal Information</h2>
        <form action="{{ route('customer.profile.update') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-1 p-2 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-1 p-2 border rounded-xl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full mt-1 p-2 border rounded-xl">
                </div>
            </div>
            <button type="submit" class="mt-4 px-6 py-2 bg-emerald-600 text-white rounded-xl font-medium hover:bg-emerald-700">Update Profile</button>
        </form>
    </div>

    {{-- Form ya Kubadilisha Nywila --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Change Password</h2>
        <form action="{{ route('customer.profile.password') }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" name="current_password" class="w-full mt-1 p-2 border rounded-xl">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" class="w-full mt-1 p-2 border rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border rounded-xl">
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-4 px-6 py-2 bg-gray-900 text-white rounded-xl font-medium hover:bg-gray-800">Update Password</button>
        </form>
    </div>
</div>
@endsection