<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Role Helpers ──

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTechnician(): bool
    {
        return $this->role === 'technician';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // ── Relationships ──

    public function technicianProfile(): HasOne
    {
        return $this->hasOne(TechnicianProfile::class);
    }

    public function technicianWorks(): HasMany
    {
        return $this->hasMany(TechnicianWork::class)
                    ->where('is_visible', true)
                    ->latest();
    }

    public function allTechnicianWorks(): HasMany
    {
        return $this->hasMany(TechnicianWork::class)->latest();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(Subscription::class)
                    ->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->latestOfMany();
    }

    public function bookingsAsCustomer(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function bookingsAsTechnician(): HasMany
    {
        return $this->hasMany(Booking::class, 'technician_id');
    }

    // ── Computed Helpers ──

    /**
     * Kagua kama mtumiaji ana usajili hai bila kurudia query ikiwa tayari umesha-load mahusiano.
     */
    public function hasActiveSubscription(): bool
    {
        if ($this->relationLoaded('activeSubscription')) {
            return $this->activeSubscription !== null;
        }

        return $this->subscriptions()
                    ->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->exists();
    }
}