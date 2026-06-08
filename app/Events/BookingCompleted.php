<?php

namespace App\Events;

use App\Models\Booking; // Hakikisha umeingiza Model ya Booking
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // Hii ni muhimu
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCompleted implements ShouldBroadcast // Implement hii interface
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    /**
     * Create a new event instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // Tunatumia PrivateChannel inayomlenga mteja husika (customer)
        return [
            new PrivateChannel('booking.' . $this->booking->customer_id),
        ];
    }
}