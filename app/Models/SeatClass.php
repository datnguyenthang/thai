<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatClass extends Model
{
    use HasFactory;
    protected $fillable = [
        'rideId', 'name', 'capacity', 'price', 'status'
    ];
}
