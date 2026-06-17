<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $url;
    protected $booking;
    protected $technicianId; // Tumeongeza hii variable

    // Tumeongeza $technicianId kwenye constructor
    public function __construct($message, $url, $booking = null, $technicianId = null)
    {
        $this->message = $message;
        $this->url = $url;
        $this->booking = $booking;
        $this->technicianId = $technicianId;
    }

    public function via($notifiable) { return ['database']; }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => $this->url,
            'booking_code' => $this->booking->booking_code ?? 'N/A', // Ikiwa column yako ni 'booking_code'
            'technician_id' => $this->technicianId, // Sasa inatumia variable tuliyoi-define
        ];
    }
}