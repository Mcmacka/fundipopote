@extends('layouts.technician')
@section('title', 'My Subscription')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Flash messages --}}
    @if(session('warning'))
        <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 mb-6">
            ⚠️ {{ session('warning') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Current Status --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-3">Your Subscription Status</h2>
        @if($currentSubscription)
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                <div>
                    <p class="font-medium text-gray-900">Plan: {{ ucfirst($currentSubscription->plan_type) }}</p>
                    <p class="text-sm text-gray-500">
                        Expires: {{ $currentSubscription->expires_at->format('d M Y') }}
                        ({{ $currentSubscription->expires_at->diffInDays(now()) }} days remaining)
                    </p>
                </div>
            </div>
        @else
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <p class="text-gray-600">No active subscription. Please choose a plan below.</p>
            </div>
        @endif
    </div>

    {{-- Plan Selection & Payment Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-5">Select a New Plan</h2>
        
        <form method="POST" action="{{ route('technician.subscription.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-3 gap-4 mb-6">
                @php
                    $planInfo = [
                        'basic'    => ['label' => 'Basic',    'price' => 15000,  'days' => 30,  'note'  => 'Good for starting'],
                        'standard' => ['label' => 'Standard', 'price' => 35000,  'days' => 90,  'note'  => 'Most popular'],
                        'premium'  => ['label' => 'Premium',  'price' => 100000, 'days' => 365, 'note'  => 'Best value'],
                    ];
                @endphp

                @foreach($planInfo as $key => $plan)
                <label class="cursor-pointer">
                    <input type="radio" name="plan_type" value="{{ $key }}" class="sr-only peer" required>
                    <div class="border-2 border-gray-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 rounded-2xl p-4 transition text-center">
                        <p class="text-xs text-gray-400 font-medium mb-1">{{ strtoupper($plan['label']) }}</p>
                        <p class="text-2xl font-bold text-gray-900">TZS {{ number_format($plan['price']) }}</p>
                        <p class="text-xs text-gray-500">{{ $plan['days'] }} days</p>
                        <p class="text-xs text-emerald-600 mt-2 font-medium">{{ $plan['note'] }}</p>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                    <select name="payment_method" required
                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-emerald-500">
                        <option value="">-- Select --</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="tigopesa">Tigo Pesa</option>
                        <option value="airtel">Airtel Money</option>
                    </select>
                    @error('payment_method')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Payment Receipt</label>
                    <input type="file" name="payment_receipt" accept="image/*" required 
                           class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-emerald-500 file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-emerald-50 file:text-emerald-700">
                    @error('payment_receipt')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-5 text-sm text-blue-700">
                📱 <strong>How to pay:</strong> Send the amount to number <strong>0712 345 678</strong> (FundiPopote). Then upload your payment receipt photo above. Admin will verify and approve your request.
            </div>

            <button type="submit"
                class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition">
                ✉️ Submit Subscription Request
            </button>
        </form>
    </div>

    {{-- History --}}
    @if($allSubscriptions->count() > 0)
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment History</h2>
        <div class="space-y-0">
            @foreach($allSubscriptions as $sub)
            <div class="flex justify-between items-center py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="font-medium text-gray-800">{{ ucfirst($sub->plan_type) }}</p>
                    <p class="text-xs text-gray-400">
                        @if($sub->payment_receipt)
                            <a href="{{ asset('storage/' . $sub->payment_receipt) }}" target="_blank" class="text-emerald-600 hover:underline font-bold">View Receipt</a>
                        @else
                            No Receipt
                        @endif
                        · {{ $sub->created_at->format('d M Y') }}
                    </p>
                </div>
                <span class="text-xs px-3 py-1 rounded-full font-medium
                    {{ $sub->status === 'active'           ? 'bg-emerald-50 text-emerald-700' : '' }}
                    {{ $sub->status === 'pending_approval' ? 'bg-amber-50 text-amber-700'     : '' }}
                    {{ $sub->status === 'rejected'         ? 'bg-red-50 text-red-600'         : '' }}
                    {{ $sub->status === 'expired'          ? 'bg-gray-100 text-gray-500'      : '' }}">
                    {{ match($sub->status) {
                        'active'           => 'Active',
                        'pending_approval' => 'Pending',
                        'rejected'         => 'Rejected',
                        'expired'          => 'Expired',
                        default            => ucfirst($sub->status),
                    } }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection