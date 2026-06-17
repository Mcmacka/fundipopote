<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThankYouNotification extends Notification
{
    use Queueable;

    // 1. Fafanua property hapa
    protected $message;

    public function __construct($message) 
    {
        $this->message = $message;
    }

    public function via($notifiable) 
    { 
        return ['database']; 
    }

    public function toArray($notifiable) 
    {
        return [
            'message' => $this->message,
            'type'    => 'thank_you',
        ];
    }
}