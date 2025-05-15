<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['car_id', 'user_id', 'start_date', 'end_date', 'status'];

    // Relationship to car
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // Relationship to user (customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
{
    return $this->belongsTo(Customer::class);
}

}
