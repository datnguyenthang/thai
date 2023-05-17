<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'rideId', 'code', 'isReturn', 'seatClassId', 'promotionId', 'firstName', 'lastName', 'phone', 'email', 'note',
        'adultQuantity', 'childrenQuantity', 'price', 'bookingDate', 'status'
    ];

    public static function generateCode()
    {
        $code = Str::random(10);
        while (self::codeExists($code)) {
            $code = Str::random(10);
        }
        return $code;
    }

    public static function codeExists($code)
    {
        return Ticket::where('code', $code)->exists();
    }
}
