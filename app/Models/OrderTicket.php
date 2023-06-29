<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'orderId', 'rideId', 'code', 'seatClassId', 'price', 'type', 'status'
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class);
    }

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
