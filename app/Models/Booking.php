<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_code',
        'customer_id',
        'technician_id',
        'category_id',
        'description',
        'location_address',
        'location_lat',
        'location_lng',
        'scheduled_at',
        'status',
        'agreed_price',
        'technician_notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
            'agreed_price' => 'decimal:2',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate booking code on creation
        static::creating(function (Booking $booking) {
            $booking->booking_code = 'FP-' . date('Y') . '-' . strtoupper(Str::random(6));
        });
    }

    // ── Relationships ──

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ── Status Actions ──

    /**
     * Updated to handle acceptance without forcing a price update
     */
    public function accept(float $price = 0): self
    {
        $this->update([
            'status'       => 'accepted',
            'agreed_price' => $price,
        ]);
        return $this;
    }

    public function reject(string $reason = ''): self
    {
        $this->update([
            'status'           => 'rejected',
            'technician_notes' => $reason,
        ]);
        return $this;
    }

    public function complete(string $notes = null): self
    {
        $this->update([
            'status'           => 'completed',
            'completed_at'     => now(),
            'technician_notes' => $notes,
        ]);
        return $this;
    }

    public function cancel(): self
    {
        $this->update(['status' => 'cancelled']);
        return $this;
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id');
    }

    public function getCanViewContactAttribute(): bool
    {
        return in_array($this->status, ['accepted', 'in_progress', 'completed']);
    }
}