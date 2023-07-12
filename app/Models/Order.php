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
        'pickup', 'dropoff', 'adultQuantity', 'childrenQuantity', 'onlinePrice', 'originalPrice', 'couponAmount', 'finalPrice', 'bookingDate',
        'extraFee', 'paymentMethod', 'paymentStatus', 'transactionCode', 'transactionDate', 'status'
    ];

    public function orderTickets()
    {
        return $this->hasMany(OrderTicket::class, 'orderId');
    }

    public static function generateCode()
    {
        $latestId = self::max('id');
        $newId = $latestId + 1;

        $code = 'SDG' . str_pad($newId, 8, '0', STR_PAD_LEFT);
        while (self::codeExists($code)) {
            $newId++;
            $code = 'SDG' . str_pad($newId, 8, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public static function codeExists($code)
    {
        return Order::where('code', $code)->exists();
    }
}
