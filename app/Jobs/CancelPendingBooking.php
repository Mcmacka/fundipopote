<?php
namespace App\Jobs;

use App\Models\Booking;
use App\Notifications\BookingStatusNotification; // Hakikisha ume-import hii
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

    if ($booking && $booking->status === 'pending') {
        $booking->update(['status' => 'cancelled']);
        
        // 1. Orodhesha watu wanaopaswa kupata notification
        $recipients = [];
        
        // Mteja (Customer)
        $customer = \App\Models\User::find($booking->customer_id);
        if ($customer) $recipients[] = $customer;

        // Technician (Kama yupo kwenye booking)
        if ($booking->technician_id) {
            $technician = \App\Models\User::find($booking->technician_id);
            if ($technician) $recipients[] = $technician;
        }

        // 2. Tuma kwa wote kwa kutumia Notification facade
        foreach ($recipients as $recipient) {
            \DB::table('notifications')->insert([
                'id' => \Illuminate\Support\Str::uuid(),
                'type' => 'App\Notifications\BookingStatusNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $recipient->id,
                'data' => json_encode(['message' => 'Booking  ' . $booking->booking_code . ' job was cancelled because time for the booking has expired.']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        \Log::info("Notification is sent to all recipients.");
    }
}

}