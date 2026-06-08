<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicianWork extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_path',
        'category',
        'is_visible',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'views'      => 'integer',
        ];
    }

    // ── Relationships ──

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ──

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}