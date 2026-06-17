<?php

namespace App\Jobs;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelPendingBooking implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $bookingId) {}

    public function handle(): void
    {
        $booking = Booking::find($this->bookingId);

        // Tuna-cancel tu kama bado iko 'pending'
        if ($booking && $booking->status === 'pending') {
    $booking->update([
        'status' => 'cancelled',
        'cancellation_reason' => 'TIMEOUT: Time limit exceeded That is why this booking was cancelled.'
    ]);
}
    }
}