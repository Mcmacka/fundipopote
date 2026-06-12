<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification
{
    use Queueable;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('OTP Verification - FundiPopote')
            ->line('Welcome to FundiPopote! To complete your registration, please use the following OTP:')
            ->line($this->otp)
            ->line('This OTP will expire in 10 minutes.')
            ->line('If you did not request this registration, please ignore this email.');
    }
}