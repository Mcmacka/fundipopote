<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'customer_id',
        'technician_id',
        'rating',
        'comment',
    ];

    public function technician()
{
    return $this->belongsTo(\App\Models\User::class, 'technician_id');
}

public function customer()
{
    return $this->belongsTo(\App\Models\User::class, 'customer_id');
}
}