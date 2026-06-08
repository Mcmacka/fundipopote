@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h1>

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