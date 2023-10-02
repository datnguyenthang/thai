<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Ride;

class EmployeeLib {
    public static function performanceAll($fromDate, $toDate, $status){
        $performances = Order::select(
                            DB::raw('orders.userId'),
                            'u.name as userName',
                            'a.name as agentName',
                            DB::raw('COUNT(orders.id) as totalOrder'),
                            DB::raw('SUM(orders.finalPrice) as totalPrice')
                        )
                        ->leftJoin('users as u', 'u.id', '=', 'orders.userId')
                        ->leftJoin('agents as a', 'a.id', '=', 'u.agentId')
                        ->leftJoin('order_statuses as os', function($ojoin) {
                            $ojoin->on('orders.id', '=', 'os.orderId')
                                ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
                        })
                        ->where(function ($query) use ($fromDate, $toDate, $status) {
                            if ($status) $query->where('os.status', $status);
                            if ($fromDate) $query->where('orders.bookingDate', '>=', $fromDate);
                            if ($toDate) $query->where('orders.bookingDate', '<=', $toDate);
                        })
                        ->groupBy(DB::raw("orders.userId"), 'u.name', 'a.name')
                        ->orderBy(DB::raw("orders.userId"))
                        ->get();
        return $performances;
    }
}