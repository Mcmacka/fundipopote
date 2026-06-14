<?php

use Illuminate\Support\Facades\Schedule;
use App\Models\Subscription;
use Carbon\Carbon;

Schedule::call(function () {
    // 1. Tafuta subscription zote zilizokwisha muda wake (active -> expired)
    Subscription::where('status', 'active')
        ->where('expires_at', '<=', Carbon::now())
        ->update(['status' => 'expired']);

    // 2. Tafuta subscription zilizokuwa 'queued' na uzifanye 'active'
    // Tunachukua ile ya kwanza iliyokuwa queued kwa kila user
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
})->daily(); // Hii itakimbia kila siku saa 00:00
