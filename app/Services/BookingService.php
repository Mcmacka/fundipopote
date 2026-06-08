<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingRequestNotification;
use App\Notifications\BookingStatusNotification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

/**
 * BookingService
 *
 * Owns the full booking lifecycle:
 * Customer creates → Technician accepts/rejects → Completed
 */
class BookingService
{
    /**
     * Create a new booking request.
     * Notifies the assigned technician automatically.
     */
    public function createBooking(User $customer, array $data): Booking
    {
        return DB::transaction(function () use ($customer, $data) {
            $booking = Booking::create([
                'customer_id'      => $customer->id,
                'technician_id'    => $data['technician_id'],
                'category_id'      => $data['category_id'],
                'description'      => $data['description'],
                'location_address' => $data['location_address'],
                'location_lat'     => $data['location_lat'] ?? null,
                'location_lng'     => $data['location_lng'] ?? null,
                'scheduled_at'     => $data['scheduled_at'] ?? null,
                'status'           => 'pending',
            ]);

            // Notify the technician
            $booking->technician->notify(new BookingRequestNotification($booking));

            return $booking;
        });
    }

    /**
     * Technician accepts a booking and sets the agreed price.
     */
    public function acceptBooking(Booking $booking, float $price, User $technician): Booking
    {
        throw_if(
            $booking->technician_id !== $technician->id,
            AuthorizationException::class,
            'Huna ruhusa kukubali agizo hili.'
        );

        $booking->accept($price);

        // Notify the customer
        $booking->customer->notify(new BookingStatusNotification($booking));

        return $booking;
    }

    /**
     * Technician rejects a booking with an optional reason.
     */
    public function rejectBooking(Booking $booking, string $reason, User $technician): Booking
    {
        throw_if(
            $booking->technician_id !== $technician->id,
            AuthorizationException::class,
            'Huna ruhusa kukataa agizo hili.'
        );

        $booking->reject($reason);

        // Notify the customer
        $booking->customer->notify(new BookingStatusNotification($booking));

        return $booking;
    }

    /**
     * Mark a booking as completed.
     */
    public function completeBooking(Booking $booking, User $technician): Booking
    {
        throw_if(
            $booking->technician_id !== $technician->id,
            AuthorizationException::class
        );

        $booking->complete();

        return $booking;
    }
}
