<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Ride;

class DashboardLib {

    public static function ridesInDay($fromDate = 0, $toDate = 0, $depart = 0, $dest = 0, $isOrder = true, $perPage = 10){
        $rides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'rides.departTime', 'rides.returnTime', 'rides.departDate',
                                DB::raw('SUM(o.adultQuantity + COALESCE(o.childrenQuantity, 0)) as totalCustomer'),
                                DB::raw('SUM(CASE WHEN os.status = '.CONFIRMEDORDER.' THEN (o.adultQuantity + COALESCE(o.childrenQuantity, 0)) ELSE 0 END) as totalCustomerConfirm'),
                                
                                DB::raw('COUNT(o.id) as totalOrder'),
                                DB::raw('SUM(CASE WHEN os.status = '.CONFIRMEDORDER.' THEN 1 ELSE 0 END) as totalOrderConfirm'),

                                DB::raw('SUM(ot.price) as totalMoney'),
                                DB::raw('SUM(CASE WHEN os.status = '.CONFIRMEDORDER.' THEN ot.price ELSE 0 END) as totalMoneyConfirm'),

                                DB::raw('CASE WHEN cast(CONCAT(rides.departDate, " ", rides.departTime) as datetime) > "'.Carbon::now()->format('Y-m-d H:i:s').'" THEN 0 ELSE 1 END AS isDepart')
                            )
                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')

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
                    ->where(function ($query) use ($fromDate, $toDate, $depart, $dest, $isOrder) {
                        if($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                        if($toDate) $query->where('rides.departDate', '<=', $toDate);
                        if($depart) $query->where('rides.fromLocation', $depart);
                        if($dest) $query->where('rides.toLocation', $dest);
                        //if($isOrder) $query->where('totalCustomer', '>', 0);

                        if (!$fromDate && !$toDate)
                            $query->where('rides.departDate', '=', Carbon::now()->toDateString());
                    })
                    ->groupBy('rides.id', 'rides.name', 'fl.name', 'tl.name', 'rides.departTime', 'rides.returnTime', 'rides.departDate')
                    ->when($isOrder, function ($query) {
                        $query->having('totalCustomer', '>', 0);
                    })
                    ->orderBy('rides.departDate', 'asc')
                    ->orderBy('rides.departTime', 'asc')
                    ->paginate($perPage);

        return $rides;
    }

    public static function revenueInDay(){
        $revenue = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'rides.departTime', 'rides.returnTime', 'rides.departDate',
                                DB::raw('SUM(CASE WHEN os.status = '.CONFIRMEDORDER.' THEN o.finalPrice ELSE 0 END) as priceConfirmed'),
                                DB::raw('SUM(CASE WHEN os.status <> '.CONFIRMEDORDER.' THEN o.finalPrice ELSE 0 END) as priceNotConfirmed'),
                            )
                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')

                    ->leftJoin('order_tickets as ot' , function ($otjoin) {
                        $otjoin->on('rides.id', '=', 'ot.rideId');
                    })
                    ->leftJoin('orders as o' , function ($ojoin) {
                        $ojoin->on('ot.orderId', '=', 'o.id');
                    })
                    ->leftJoin('order_statuses as os', function($ojoin) {
                        $ojoin->on('o.id', '=', 'os.orderId')
                            ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = o.id)');
                    })
                    ->where('rides.departDate', '=', Carbon::now()->toDateString())
                    ->groupBy('rides.id', 'rides.name', 'fl.name', 'tl.name', 'rides.departTime', 'rides.returnTime', 'rides.departDate')
                    ->first();
        return $revenue;
    }

    public static function exportRides($rideId = 0, $fromDate = 0, $toDate = 0, $depart = 0, $dest = 0) {
        $passengers = Ride::select('rides.id', 'rides.name as Ridename', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'rides.departTime', 'rides.returnTime', 'rides.departDate',
                                   'o.id as orderId','o.code','o.code', 'o.phone', 'o.email', 'o.adultQuantity', 'o.childrenQuantity', 'o.pickup', 'o.dropoff',
                                   DB::raw('CONCAT(o.firstName, " ", o.lastName) as fullname'),
                                   DB::raw('CASE WHEN o.customerType <> 0 THEN ct.name ELSE "Online" END AS CustomerType'), 'u.name',
                                   DB::raw('CASE WHEN ot.type = '.ONEWAY.' THEN "Departure" ELSE "Return" END AS Ticket'),
                                   DB::raw('CASE WHEN os.status = '.CONFIRMEDORDER.' THEN "Confirm" ELSE "Not Confirm" END AS Status')
                                )
                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                    ->join('order_tickets as ot' , function ($otjoin) {
                        $otjoin->on('rides.id', '=', 'ot.rideId');
                    })
                    ->leftJoin('orders as o' , function ($ojoin) {
                        $ojoin->on('ot.orderId', '=', 'o.id');
                    })
                    ->leftJoin('order_statuses as os', function($join) {
                        $join->on('o.id', '=', 'os.orderId')
                             ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = o.id)');
                    })
                    ->leftJoin('customer_types as ct', 'ct.id', '=', 'o.customerType')
                    ->leftJoin('users as u', 'u.id', '=', 'o.userId')
                    ->where(function ($query) use ($rideId, $fromDate, $toDate, $depart, $dest) {

                        if ($rideId) {
                            $query->where('rides.id', $rideId);
                        } else {
                            if ($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                            if ($toDate) $query->where('rides.departDate', '<=', $toDate);
                            if ($depart) $query->where('rides.fromLocation', $depart);
                            if ($dest) $query->where('rides.toLocation', $dest);
                        }
                    })
                    ->orderBy('rides.id', 'asc')
                    ->get();

        return $passengers;
    }

    public static function detailRides($rideId = 0) {
        $passengers = Ride::select('rides.id', 'rides.name as Ridename', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'rides.departTime', 'rides.returnTime', 'rides.departDate',
                                  'o.id as orderId','o.code', 'o.phone', 'o.email', 'o.adultQuantity', 'o.childrenQuantity', 'o.pickup', 'o.dropoff', 'ot.id as orderTicketId',
                                   DB::raw('COALESCE(op.paymentStatus, 0) as paymentStatus'), 'os.status',
                                   DB::raw('CONCAT(o.firstName, " ", o.lastName) as fullname'),
                                   DB::raw('CASE WHEN o.customerType <> 0 THEN ct.name ELSE "Online" END AS CustomerType'), 'u.name',
                                   DB::raw('CASE WHEN ot.type = '.ONEWAY.' THEN "Departure" ELSE "Return" END AS ticket'),
                                   //DB::raw('CASE WHEN os.status = '.CONFIRMEDORDER.' THEN "Confirm" ELSE "Not Confirm" END AS status')
                                )
                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                    ->join('order_tickets as ot' , function ($otjoin) {
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
                    ->leftJoin('customer_types as ct', 'ct.id', '=', 'o.customerType')
                    ->leftJoin('users as u', 'u.id', '=', 'o.userId')
                    ->where(function ($query) use ($rideId) {

                        if ($rideId) {
                            $query->where('rides.id', $rideId);
                        }
                    })
                    ->orderBy('rides.id', 'asc')
                    ->get();

        return $passengers;
    }
}
