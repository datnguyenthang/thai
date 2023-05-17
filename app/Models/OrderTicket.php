<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'orderId', 'rideId', 'code', 'seatClassId', 'price', 'status'
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
        return OrderTicket::where('code', $code)->exists();
    }
}
