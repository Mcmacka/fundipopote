<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = match ($this->booking->status) {
            'accepted'  => 'Agreed ',
            'rejected'  => 'Disagreed ',
            'completed' => 'Completed ',
            default     => ucfirst($this->booking->status),
        };

        $mail = (new MailMessage)
            ->subject("Request status {$this->booking->booking_code}: {$status}")
            ->greeting("Hi {$notifiable->name}!")
            ->line("your request have new status: **{$status}**");

        if ($this->booking->status === 'accepted' && $this->booking->agreed_price) {
            $mail->line("new cost have been agreed: TZS " . number_format($this->booking->agreed_price));
        }

        if ($this->booking->technician_notes) {
            $mail->line("Technician notice: {$this->booking->technician_notes}");
        }

        return $mail
            ->action('check request', url("/app/bookings/{$this->booking->id}"))
            ->line('Thank for use FundiPopote!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id'   => $this->booking->id,
            'booking_code' => $this->booking->booking_code,
            'status'       => $this->booking->status,
            'message'      => "your request have new status: {$this->booking->status}",
        ];
    }
}
