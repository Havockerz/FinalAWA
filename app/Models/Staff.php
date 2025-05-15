<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff'; // 👈 Tell Laravel to use 'staff' table

    protected $fillable = ['user_id', 'branch'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
