<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_type',
        'amount_paid',
        'starts_at',
        'expires_at',
        'status',
        'mpesa_reference',
        'payment_method',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'   => 'datetime',
            'expires_at'  => 'datetime',
            'approved_at' => 'datetime',
            'amount_paid' => 'decimal:2',
        ];
    }

    // ── Plan Configuration ──

    public static array $planDurations = [
        'basic'    => 30,    // days
        'standard' => 90,
        'premium'  => 365,
    ];

    public static array $planPrices = [
        'basic'    => 15000,   // TZS
        'standard' => 35000,
        'premium'  => 100000,
    ];

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Status Helpers ──

    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->expires_at?->isFuture();
    }

    public function isPendingApproval(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired'
            || ($this->status === 'active' && $this->expires_at?->isPast());
    }

    // ── Admin Actions ──

    public function approve(User $admin): self
    {
        $days = self::$planDurations[$this->plan_type];

        $this->update([
            'status'      => 'active',
            'starts_at'   => now(),
            'expires_at'  => now()->addDays($days),
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        return $this;
    }

    public function reject(User $admin, string $reason = ''): self
    {
        $this->update([
            'status'      => 'rejected',
            'admin_notes' => $reason,
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        return $this;
    }
}
