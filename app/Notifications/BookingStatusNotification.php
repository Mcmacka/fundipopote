<?php
namespace App\Notifications;
use App\models\Booking;
use Illuminate\Bus\Queueable; 
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels; 

class BookingStatusNotification extends Notification
{
    
    use Queueable, SerializesModels;
    // Tunaondoa Queueable na ShouldQueue ili iwe instant
    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function via(object $notifiable): array
    {
        // Database inakuja kwanza ili iandikwe haraka
        return ['database' ];
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
            'message'      => 'Booking ' . $this->booking->booking_code . ' status changed to: ' . $this->booking->status,
            'type'         => $this->booking->status === 'cancelled' ? 'booking_cancelled' : 'status_update',
        ];
    }
}