<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'plan_type', 'amount_paid', 'starts_at', 
        'expires_at', 'status', 'payment_receipt', 'payment_method', 
        'admin_notes', 'approved_by', 'approved_at',
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
        'basic'    => 30,
        'standard' => 90,
        'premium'  => 365,
    ];


    public static array $planPrices = [
        'basic'    => 15000,
        'standard' => 35000,
        'premium'  => 100000,
    ];

    // ── Admin Actions (Ililoboreshwa) ──

    public function approve(User $admin): self
    {
        // Kutumia duration iliyopo kwenye $planDurations
        $days = self::$planDurations[$this->plan_type] ?? 30;

        $this->update([
            'status'      => 'active',
            'starts_at'   => now(),
            'expires_at'  => Carbon::now()->addDays($days),
            'approved_by' => $admin->id,
            'approved_at' => now(),
        ]);

        return $this;
    }

    // ... (baki na njia zako zingine kama isActive, reject, n.k.)
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}