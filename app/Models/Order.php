<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId', 'agentId', 'code', 'customerType', 'isReturn', 'promotionId', 'firstName', 'lastName', 'phone', 'email', 'note',
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
        return Order::where('code', $code)->exists();
    }
}
