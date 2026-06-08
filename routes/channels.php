<?php
use App\Models\Booking;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('booking.{customerId}', function ($user, $customerId) {
    return (int) $user->id === (int) $customerId;
});