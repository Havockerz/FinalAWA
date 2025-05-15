<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'brand',
    'model',
    'year',
    'price_per_day',
    'license_plate', // ✅ MUST BE HERE
    'branch',
    'description',
    'status',
    'staff_id',
    'picture',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}


