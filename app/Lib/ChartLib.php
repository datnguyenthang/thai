<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Ride;

class ChartLib {

    public static function dateFormat($type = 'byMonth', $fromDate, $toDate){
        switch ($type) {
            case 'byYear':
                $dateFormat = '%Y';
                $fromDate = Carbon::parse("$fromDate-01-01")->startOfYear()->toDateString();
                $toDate = Carbon::parse("$toDate-12-31")->endOfYear()->toDateString();
                break;
            case 'byMonth':
                $dateFormat = '%Y-%m';
                $fromDate = Carbon::parse($fromDate)->startOfMonth()->toDateString();
                $toDate = Carbon::parse($toDate)->endOfMonth()->toDateString();
                break;
            case 'byDay':
                $dateFormat = '%Y-%m-%d';
                break;
        }
        return [$dateFormat, $fromDate, $toDate];
    }

    public static function revenueInDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRM, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $revenues = Ride::select(
                    DB::raw('SUM(ot.price) as revenue'),
                    DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}') as data")
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
                ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                    if ($status) $query->where('os.status', $status);
                    if ($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                    if ($toDate) $query->where('rides.departDate', '<=', $toDate);
                    if ($depart) $query->where('rides.fromLocation', $depart);
                    if ($dest) $query->where('rides.toLocation', $dest);
                })
                ->groupBy(DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}')"))
                ->orderBy(DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}')"))
                ->get();
        return $revenues;
    }

    public static function orderInDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRM, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $orders = Ride::select(
                    DB::raw('COUNT(ot.id) as totalOrder'),
                    DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}') as data")
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
                ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                    if ($status) $query->where('os.status', $status);
                    if ($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                    if ($toDate) $query->where('rides.departDate', '<=', $toDate);
                    if ($depart) $query->where('rides.fromLocation', $depart);
                    if ($dest) $query->where('rides.toLocation', $dest);
                })
                ->groupBy(DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}')"))
                ->orderBy(DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}')"))
                ->get();
        return $orders;
    }

    public static function paxInDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRM, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $paxes = Ride::select(
                    DB::raw('SUM(o.adultQuantity) as pax'),
                    DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}') as data")
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
                ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                    if ($status) $query->where('os.status', $status);
                    if ($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                    if ($toDate) $query->where('rides.departDate', '<=', $toDate);
                    if ($depart) $query->where('rides.fromLocation', $depart);
                    if ($dest) $query->where('rides.toLocation', $dest);
                })
                ->groupBy(DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}')"))
                ->orderBy(DB::raw("DATE_FORMAT(rides.departDate, '{$dateFormat}')"))
                ->get();
        return $paxes;
    }
    
    
}