<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingRequestNotification;
use App\Notifications\BookingStatusNotification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Create a new booking request.
     */
    public function createBooking(User $customer, array $data): Booking
    {
        return DB::transaction(function () use ($customer, $data) {
            $booking = Booking::create([
                'customer_id'      => $customer->id,
                'technician_id'    => $data['technician_id'] ?? null,
                'category_id'      => $data['category_id'],
                'description'      => $data['description'],
                'location_address' => $data['location_address'],
                'location_lat'     => $data['location_lat'] ?? null,
                'location_lng'     => $data['location_lng'] ?? null,
                'scheduled_at'     => $data['scheduled_at'] ?? null,
                'status'           => 'pending',
            ]);

            // Notify the technician if assigned
            if ($booking->technician) {
                $booking->technician->notify(new BookingRequestNotification($booking));
            }

            return $booking;
        });
    }

    /**
     * Technician accepts a booking.
     */
    public function acceptBooking(Booking $booking, User $technician): Booking
    {
        // Ruhusu kukubali kazi kama:
        // 1. Kazi haijawa assigned kwa fundi mwingine (technician_id ni null)
        // 2. AU kazi tayari ni ya kwako
        if ($booking->technician_id !== null && $booking->technician_id !== $technician->id) {
            throw new AuthorizationException('Huna ruhusa kukubali agizo hili.');
        }

        $booking->update([
            'technician_id' => $technician->id,
            'status'        => 'accepted',
        ]);

        // Notify the customer
        $booking->customer->notify(new BookingStatusNotification($booking));

        return $booking;
    }

    /**
     * Technician rejects a booking.
     */
    public function rejectBooking(Booking $booking, string $reason, User $technician): Booking
    {
        // Hakikisha ni kazi yako au ni kazi huru
        if ($booking->technician_id !== null && $booking->technician_id !== $technician->id) {
            throw new AuthorizationException('Huna ruhusa kukataa agizo hili.');
        }

        $booking->update([
            'status' => 'rejected',
            // Unaweza kuongeza column ya 'rejection_reason' kwenye model kama ipo
        ]);

        $booking->customer->notify(new BookingStatusNotification($booking));

        return $booking;
    }

    /**
     * Mark a booking as completed.
     */
    public function completeBooking(Booking $booking, User $technician): Booking
    {
        if ($booking->technician_id !== $technician->id) {
            throw new AuthorizationException('Huna ruhusa kukamilisha kazi hii.');
        }

        $booking->update(['status' => 'completed']);

        return $booking;
    }
}