@extends('layouts.technician')
@section('title', 'My Profile')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Profile completion warning --}}
    @if(!$profile)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-6">
            <strong>Your profile is incomplete!</strong>
            Customers will not see you in search results until you fill in your details below.
        </div>
    @endif

    <div class="flex items-center gap-4 mb-8">
        <div class="relative group">
            @if($profile && $profile->profile_photo)
                <img id="avatar-preview" src="{{ asset('storage/' . $profile->profile_photo) }}" 
                     class="w-16 h-16 rounded-full object-cover border border-slate-200 shadow-sm">
            @else
                <div id="avatar-placeholder" class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <img id="avatar-preview" class="w-16 h-16 rounded-full object-cover border border-slate-200 shadow-sm hidden">
            @endif
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">My Profile</h1>
            <p class="text-slate-500 text-sm">Information visible to customers</p>
        </div>
    </div>

    <form method="POST" action="{{ route('technician.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Section 1: Personal Info & Avatar --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-5">
            <h2 class="text-base font-semibold text-slate-800 mb-4">Personal Information</h2>
            
            <div class="mb-5 pb-5 border-b border-slate-100">
                <label class="block text-sm font-medium text-slate-700 mb-1">Profile Picture</label>
                <div class="mt-1 flex items-center gap-4">
                    <input type="file" name="profile_picture" id="profile-pic-input" accept="image/*"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                </div>
                @error('profile_picture')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-1">Recommended: Square image (JPEG or PNG), max 2MB.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           placeholder="+255 712 345 678"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        ID Number <span class="text-slate-400 font-normal">(20 digits)</span>
                    </label>
                    <input type="text" name="id_number" 
                           value="{{ old('id_number', $profile?->id_number) }}"
                           placeholder="19XXXXXXXXXXXXX" 
                           maxlength="20" 
                           pattern="\d{20}" 
                           title="ID number must be exactly 20 digits"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                    @error('id_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Experience (Years) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="years_experience" min="0" max="50"
                           value="{{ old('years_experience', $profile?->years_experience ?? 0) }}" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                    @error('years_experience')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Short Bio</label>
                <textarea name="bio" rows="3" maxlength="500"
                          placeholder="Describe yourself..."
                          class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">{{ old('bio', $profile?->bio) }}</textarea>
                <p class="text-xs text-slate-400 mt-1">Maximum 500 characters</p>
            </div>
        </div>

        {{-- Section 2: Service Info --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-5">
            <h2 class="text-base font-semibold text-slate-800 mb-4">Service Type</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Service Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" required
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                        <option value="">-- Choose the service you provide --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $profile?->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">
                        Service Radius (km) <span class="text-red-500">*</span>
                    </label>
                    <select name="service_radius_km" required
                            class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                        @foreach([5, 10, 15, 20, 30, 50] as $km)
                            <option value="{{ $km }}" {{ old('service_radius_km', $profile?->service_radius_km ?? 10) == $km ? 'selected' : '' }}>
                                {{ $km }} km
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Section 3: Location --}}
        <div class="bg-white rounded-2xl border border-slate-100 p-6 mb-5">
            <h2 class="text-base font-semibold text-slate-800 mb-1">Your Location</h2>
            <p class="text-xs text-slate-400 mb-4">Your location helps nearby customers find you.</p>

            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Location Name <span class="text-red-500">*</span></label>
                <input type="text" name="location_name"
                       value="{{ old('location_name', $profile?->location_name) }}"
                       placeholder="Example: Kinondoni, Dar es Salaam" required
                       class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Latitude (GPS)</label>
                    <input type="text" name="latitude" id="lat-field"
                           value="{{ old('latitude', $profile?->latitude) }}"
                           placeholder="-6.7924"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Longitude (GPS)</label>
                    <input type="text" name="longitude" id="lng-field"
                           value="{{ old('longitude', $profile?->longitude) }}"
                           placeholder="39.2083"
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <button type="button" onclick="getGPS()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm transition">
                Get My GPS Automatically
            </button>
            <span id="gps-status" class="text-xs text-slate-400 ml-2"></span>
        </div>

        {{-- Submit Actions --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition text-sm shadow-sm">
                Save Profile
            </button>
            <a href="{{ route('technician.dashboard') }}"
               class="px-6 py-3 border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl text-sm transition">
                Back to Dashboard
            </a>
        </div>
    </form>

    {{-- Profile Preview --}}
    @if($profile)
        <div class="mt-8 bg-white rounded-2xl border border-slate-100 p-5">
            <h2 class="text-sm font-semibold text-slate-700 mb-4">How you appear to customers</h2>
            <div class="flex items-start gap-4">
                @if($profile->profile_photo)
                    <img id="preview-card-avatar" src="{{ asset('storage/' . $profile->profile_photo) }}" 
                         class="w-12 h-12 rounded-full object-cover border border-slate-100 flex-shrink-0 shadow-sm">
                @else
                    <div id="preview-card-placeholder" class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-semibold flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <img id="preview-card-avatar" class="w-12 h-12 rounded-full object-cover border border-slate-100 flex-shrink-0 shadow-sm hidden">
                @endif
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-slate-900">{{ $user->name }}</span>
                        <span class="text-xs bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">
                            {{ $profile->category?->name }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 mb-2">{{ $profile->bio ?? 'No bio available.' }}</p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-400">
                        <span>Location: {{ $profile->location_name }}</span>
                        <span>Rating: {{ number_format($profile->average_rating, 1) }}</span>
                        <span>Experience: {{ $profile->years_experience }} years</span>
                        <span>Radius: {{ $profile->service_radius_km }} km</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Security Settings --}}
    <div class="bg-white rounded-2xl border border-slate-100 p-6 mt-8">
        <h2 class="text-base font-semibold text-slate-800 mb-4">Security Settings</h2>
        <form method="POST" action="{{ route('technician.profile.password') }}">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Current Password</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                </div>
            </div>
            <button type="submit" 
                    class="mt-4 px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-medium rounded-xl transition text-sm">
                Update Password
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('profile-pic-input').addEventListener('change', function(event) {
    const reader = new FileReader();
    reader.onload = function() {
        const preview = document.getElementById('avatar-preview');
        const placeholder = document.getElementById('avatar-placeholder');
        const cardPreview = document.getElementById('preview-card-avatar');
        const cardPlaceholder = document.getElementById('preview-card-placeholder');
        
        if (preview) { preview.src = reader.result; preview.classList.remove('hidden'); }
        if (placeholder) { placeholder.classList.add('hidden'); }
        if (cardPreview) { cardPreview.src = reader.result; cardPreview.classList.remove('hidden'); }
        if (cardPlaceholder) { cardPlaceholder.classList.add('hidden'); }
    }
    if(event.target.files[0]) { reader.readAsDataURL(event.target.files[0]); }
});

function getGPS() {
    const status = document.getElementById('gps-status');
    if (!navigator.geolocation) { status.textContent = 'GPS not supported.'; return; }
    status.textContent = 'Fetching...';
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            document.getElementById('lat-field').value = pos.coords.latitude.toFixed(6);
            document.getElementById('lng-field').value = pos.coords.longitude.toFixed(6);
            status.textContent = 'GPS acquired!';
            status.style.color = '#1D9E75';
        },
        function() { status.textContent = 'Failed. Enable GPS permissions.'; status.style.color = '#ef4444'; }
    );
}
</script>
@endpush
@endsection