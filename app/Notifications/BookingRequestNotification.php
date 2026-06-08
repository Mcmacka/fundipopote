<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New request — {$this->booking->booking_code}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You have a new request from a customer.")
            ->line("**Service:** {$this->booking->category->name}")
            ->line("**Description:** {$this->booking->description}")
            ->line("**Location:** {$this->booking->location_address}")
            ->action('View Request', url("/fundi/bookings"))
            ->line('Respond quickly to avoid losing customers!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id'   => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'customer'     => $this->booking->customer->name,
            'category'     => $this->booking->category->name,
            'message'      => 'you have got new request.',
        ];
    }
}
