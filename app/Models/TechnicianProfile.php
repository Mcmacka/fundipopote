<?php

namespace App\Models;

use App\Scopes\ActiveSubscriptionScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// PHP 8.4 Attribute — applies GlobalScope automatically to ALL queries
#[ScopedBy([ActiveSubscriptionScope::class])]
class TechnicianProfile extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'bio',
        'years_experience',
        'id_number',
        'profile_photo',
        'latitude',
        'longitude',
        'location_name',
        'service_radius_km',
        'average_rating',
        'total_reviews',
    ];

    protected function casts(): array
    {
        return [
            'latitude'       => 'decimal:8',
            'longitude'      => 'decimal:8',
            'average_rating' => 'decimal:2',
        ];
    }

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // ── Local Scope: Nearby Technicians (Haversine Formula) ──

    public function scopeNearby(
        Builder $query,
        float $lat,
        float $lng,
        float $radiusKm = 20
    ): Builder {
        return $query->selectRaw("
                *,
                ( 6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance_km
            ", [$lat, $lng, $lat])
            ->having('distance_km', '<=', $radiusKm)
            ->orderBy('distance_km');
    }

    // ── Local Scope: By Category ──

    public function scopeInCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }


    public function reviews()
{
    // Fundi anaweza kuwa na reviews nyingi
    return $this->hasMany(Review::class, 'technician_id', 'user_id');
}

// Method ya kupata average rating
public function getAverageRatingAttribute()
{
    return $this->reviews()->avg('rating') ?? 0;
}


public function getProfilePhotoUrlAttribute(): ?string
{
    // Tunatumia 'profile_photo' kama ulivyoiandika kwenye $fillable
    if ($this->profile_photo) {
        return asset('storage/' . $this->profile_photo);
    }
    
    // Kurudisha null kama picha haipo, ili Blade ifanye 'fallback'
    return null;
}

public function isBusy(): bool
    {
        return \App\Models\Booking::where('technician_id', $this->user_id)
            ->whereIn('status', ['accepted', 'in_progress'])
            ->exists();
    }


}
