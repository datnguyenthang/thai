<?php
namespace App\Lib;

use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Ride;

class RideLib {

    public static function getBookingStatus($rideId, $seatClassId = 0) {

        $rideBookingStatus = Ride::leftJoin('seat_classes as sc' , function ($scjoin) {
                                    $scjoin->on('sc.rideId', '=', 'rides.id');
                                })
                                ->leftJoin('order_tickets as ot' , function ($otjoin) {
                                    $otjoin->on('rides.id', '=', 'ot.rideId');
                                })
                                ->leftJoin('orders as o' , function ($ojoin) {
                                    $ojoin->on('ot.orderId', '=', 'o.id');
                                })
                                ->leftJoin('order_statuses as os', function($join) {
                                    $join->on('o.id', '=', 'os.orderId')
                                        ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = o.id)');
                                })
                                ->leftJoin('order_payments as op', function($opjoin) {
                                    $opjoin->on('o.id', '=', 'os.orderId')
                                        ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = o.id)');
                                })
                                ->where(function ($query) use ($rideId, $seatClassId) {
                                    if ($rideId) $query->where('rides.id', $rideId);
                                    if ($seatClassId) $query->where('sc.id', $seatClassId);
                                })
                                ->selectRaw('sc.name')
                                ->selectRaw('sc.capacity');

        foreach (ORDERSTATUS as $key => $value) {
            $rideBookingStatus->selectRaw("SUM(CASE WHEN os.status = '{$key}' THEN o.adultQuantity ELSE 0 END) as '{$value}'");
        }
        
        $rideBookingStatus = $rideBookingStatus->groupBy('sc.name', 'sc.capacity')
                                                ->get();

        return $rideBookingStatus;
    }
}