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

    public static function ridesInDay($fromDate = 0, $toDate = 0, $depart = 0, $dest = 0){
        $rides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'rides.departTime', 'rides.returnTime', 'rides.departDate',
                                DB::raw('COUNT(ot.id) as totalCustomer'), 
                                DB::raw('COUNT(o.id) as totalCustomerConfirmed') ,
                                DB::raw('CASE WHEN cast(CONCAT(rides.departDate, " ", rides.departTime) as datetime) > "'.Carbon::now()->format('Y-m-d H:i:s').'" THEN 0 ELSE 1 END AS isDepart')
                            )
                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')

                    ->leftJoin('order_tickets as ot' , function ($otjoin) {
                        $otjoin->on('rides.id', '=', 'ot.rideId');
                    })
                    ->leftJoin('orders as o' , function ($ojoin) {
                        $ojoin->on('ot.orderId', '=', 'o.id');
                        $ojoin->where('o.status', CONFIRMEDORDER);
                    })
                    ->where(function ($query) use ($fromDate, $toDate, $depart, $dest) {
                        if($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                        if($toDate) $query->where('rides.departDate', '<=', $toDate);
                        if($depart) $query->where('rides.fromLocation', $depart);
                        if($dest) $query->where('rides.toLocation', $dest);

                        if (!$fromDate && !$toDate)
                            $query->where('rides.departDate', '=', Carbon::now()->toDateString());
                    })
                    ->groupBy('rides.id', 'rides.name', 'fl.name', 'tl.name', 'rides.departTime', 'rides.returnTime', 'rides.departDate')
                    ->orderBy('rides.departDate', 'asc')
                    ->orderBy('rides.departTime', 'asc')
                    ->get();

        return $rides;
    }

    public static function revenueInDay(){
        $revenue = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'rides.departTime', 'rides.returnTime', 'rides.departDate',
                                DB::raw('SUM(onc.finalPrice) as priceNotConfirmed'), 
                                DB::raw('SUM(oc.finalPrice) as priceConfirmed'),
                            )
                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')

                    ->leftJoin('order_tickets as ot' , function ($otjoin) {
                        $otjoin->on('rides.id', '=', 'ot.rideId');
                    })
                    ->leftJoin('orders as oc' , function ($ojoin) {
                        $ojoin->on('ot.orderId', '=', 'oc.id');
                        $ojoin->where('oc.status', CONFIRMEDORDER);
                    })
                    ->leftJoin('orders as onc' , function ($ojoin) {
                        $ojoin->on('ot.orderId', '=', 'onc.id');
                        $ojoin->where('onc.status', '<>', CONFIRMEDORDER);
                    })
                    ->where('rides.departDate', '=', Carbon::now()->toDateString())
                    ->groupBy('rides.id', 'rides.name', 'fl.name', 'tl.name', 'rides.departTime', 'rides.returnTime', 'rides.departDate')
                    ->first();

        return $revenue;
    }

    public static function exportRides($rideId = 0, $fromDate = 0, $toDate = 0, $depart = 0, $dest = 0) {
        $passengers = Ride::select('rides.id', 'rides.name as Ridename', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'rides.departTime', 'rides.returnTime', 'rides.departDate',
                                   'o.code', 'o.phone', 'o.email', 'o.adultQuantity', 'o.childrenQuantity', 'o.pickup', 'o.dropoff',
                                   DB::raw('CONCAT(o.firstName, " ", o.lastName) as fullname'),
                                   DB::raw('CASE WHEN ct.name THEN ct.name ELSE "Online" END AS CustomerType'), 'u.name',
                                   DB::raw('CASE WHEN ot.type = '.ONEWAY.' THEN "Departure" ELSE "Return" END AS Ticket'),
                                   DB::raw('CASE WHEN o.status = '.CONFIRMEDORDER.' THEN "Confirm" ELSE "Not Confirm" END AS Status')
                                )
                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                    ->join('order_tickets as ot' , function ($otjoin) {
                        $otjoin->on('rides.id', '=', 'ot.rideId');
                    })
                    ->leftJoin('orders as o' , function ($ojoin) {
                        $ojoin->on('ot.orderId', '=', 'o.id');
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
}
