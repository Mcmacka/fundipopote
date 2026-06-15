<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Booking;
use App\Models\Subscription;
use App\Notifications\BookingStatusNotification;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// 1. Kazi ya Booking (Inakimbia kila dakika)
Schedule::call(function () {
    $timeout = now()->subMinutes(5);

    $cancelledBookings = Booking::where('status', 'pending')
        ->where('created_at', '<', $timeout)
        ->get();

    foreach ($cancelledBookings as $booking) {
        $booking->update([
            'status' => 'cancelled',
            'technician_notes' => 'work has been cancelled because the technician did not respond in time.'
        ]);

        // Tuma notification kwa mteja
        if ($booking->customer) {
            Notification::send($booking->customer, new BookingStatusNotification($booking));
        }
    }
})->everyMinute();

// 2. Kazi ya Subscription (Inakimbia kila siku)
Schedule::call(function () {
    Log::info('Subscription cron started at: ' . now());

    Subscription::where('status', 'active')
        ->where('expires_at', '<=', Carbon::now())
        ->update(['status' => 'expired']);

    $queuedSubscriptions = Subscription::where('status', 'queued')
        ->orderBy('created_at', 'asc')
        ->get();

    foreach ($queuedSubscriptions as $sub) {
        $activeCheck = Subscription::where('user_id', $sub->user_id)
            ->where('status', 'active')
            ->exists();

        if (!$activeCheck) {
            $sub->update([
                'status' => 'active',
                'expires_at' => Carbon::now()->addDays($sub->getPlanDuration())
            ]);
        }
    }
    
    Log::info('Subscription cron finished.');
})->daily();