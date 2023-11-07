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
        $currentDay = Carbon::now()->format('Y-m-d');
        $currentYearMonth = Carbon::now()->format('Y-m');
        $currentYear = Carbon::now()->format('Y');
        switch ($type) {
            case 'byYear':
                $dateFormat = '%Y-%m';
                $fromDate = Carbon::parse("$fromDate-01-01")->startOfYear()->toDateString();

                //if ($currentYear == $toDate) $toDate = $currentDay;
                //else $toDate = Carbon::parse("$toDate-12-31")->endOfYear()->toDateString();
                $toDate = Carbon::parse("$toDate-12-31")->endOfYear()->toDateString();
                break;
            case 'byMonth':
                $dateFormat = '%Y-%m-%d';
                $fromDate = Carbon::now()->parse("$fromDate")->firstOfMonth()->formatLocalized($dateFormat);
                
                if ($currentYearMonth == $toDate) $toDate = $currentDay;
                else $toDate = Carbon::now()->parse("$toDate")->lastOfMonth()->formatLocalized($dateFormat);
                break;
            case 'byDay':
                $dateFormat = '%Y-%m-%d';
                $fromDate = Carbon::now()->parse("$fromDate")->startOfDay()->format('Y-m-d H:i:s');
                $toDate = Carbon::now()->parse("$toDate")->endOfDay()->format('Y-m-d H:i:s');
                break;
        }
        return [$dateFormat, $fromDate, $toDate];
    }

    public static function revenueOrderInDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate) = self::dateFormat($type, $fromDate, $toDate);

        $revenueOrders = Order::with(['orderTickets' => function($orderTicket){
                                $orderTicket->select('order_tickets.*', 'r.name', 'r.departDate', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                                    ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                    ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                    ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                    ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                            }])
                            ->leftJoin('order_statuses as os', function($join) {
                                $join->on('orders.id', '=', 'os.orderId')
                                    ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
                            })
                            ->select(
                                DB::raw('SUM(orders.finalPrice) as revenue'),
                                DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}') as data")
                            )
                            ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                                if ($status) $query->where('os.status', $status);
                                if ($fromDate) $query->where('orders.bookingDate', '>=', $fromDate);
                                if ($toDate) $query->where('orders.bookingDate', '<=', $toDate);
                                if ($depart || $dest) {
                                    $query->whereIn('orders.id', function ($subquery) use ($depart, $dest) {
                                        $subquery->select('order_tickets.orderId')
                                            ->from('order_tickets')
                                            ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                            ->where(function ($ticketQuery) use ($depart, $dest) {
                                                if ($depart) $ticketQuery->where('r.fromLocation', $depart);
                                                if ($dest) $ticketQuery->where('r.toLocation', $dest);
                                            });
                                    });
                                }
                            })
                            ->groupBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                            ->orderBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                            ->get();

        return $revenueOrders;
    }

    public static function revenueRideInDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $revenueRides = Ride::select(
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
        return $revenueRides;
    }

    public static function paxOrderInDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $paxOrders = Order::with(['orderTickets' => function($orderTicket){
                            $orderTicket->select('order_tickets.*', 'r.name', 'r.departDate', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                                ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                        }])
                        ->leftJoin('order_statuses as os', function($join) {
                            $join->on('orders.id', '=', 'os.orderId')
                                ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
                        })
                        ->select(
                            DB::raw('SUM(orders.adultQuantity) as pax'),
                            DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}') as data")
                        )
                        ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                            if ($status) $query->where('os.status', $status);
                            if ($fromDate) $query->where('orders.bookingDate', '>=', $fromDate);
                            if ($toDate) $query->where('orders.bookingDate', '<=', $toDate);
                            if ($depart || $dest) {
                                $query->whereIn('orders.id', function ($subquery) use ($depart, $dest) {
                                    $subquery->select('order_tickets.orderId')
                                        ->from('order_tickets')
                                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                        ->where(function ($ticketQuery) use ($depart, $dest) {
                                            if ($depart) $ticketQuery->where('r.fromLocation', $depart);
                                            if ($dest) $ticketQuery->where('r.toLocation', $dest);
                                        });
                                });
                            }
                        })
                        ->groupBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                        ->orderBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                        ->get();
//dd($paxOrders);
        return $paxOrders;
    }

    public static function paxTravelInDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $paxTravels = Ride::select(
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
        return $paxTravels;
    }

    public static function countOrderStatus($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest){
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $total = Order::with(['orderTickets' => function($orderTicket){
                            $orderTicket->select('order_tickets.*', 'r.name', 'r.departDate', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                                ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                        }])
                        ->leftJoin('order_statuses as os', function($join) {
                            $join->on('orders.id', '=', 'os.orderId')
                                ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
                        })
                        ->select(
                            DB::raw('COUNT(orders.id) as totalOrder'),
                            DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}') as data")
                        )
                        ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                            if ($status) $query->where('os.status', $status);
                            if ($fromDate) $query->where('orders.bookingDate', '>=', $fromDate);
                            if ($toDate) $query->where('orders.bookingDate', '<=', $toDate);
                            if ($depart || $dest) {
                                $query->whereIn('orders.id', function ($subquery) use ($depart, $dest) {
                                    $subquery->select('order_tickets.orderId')
                                        ->from('order_tickets')
                                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                        ->where(function ($ticketQuery) use ($depart, $dest) {
                                            if ($depart) $ticketQuery->where('r.fromLocation', $depart);
                                            if ($dest) $ticketQuery->where('r.toLocation', $dest);
                                        });
                                });
                            }
                        })
                        ->groupBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                        ->orderBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                        ->get();

        return $total->sum('totalOrder');
    }

    public static function countPaxOrderByCustomerType($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest){
        list($dateFormat, $fromDate, $toDate) = self::dateFormat($type, $fromDate, $toDate);

        $paxOrders = Order::with(['orderTickets' => function($orderTicket){
                            $orderTicket->select('order_tickets.*', 'r.name', 'r.departDate', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                                ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                        }])
                        ->leftJoin('order_statuses as os', function($join) {
                            $join->on('orders.id', '=', 'os.orderId')
                                ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
                        })
                        ->leftJoin('customer_types as ct', 'ct.id', '=', 'orders.customerType')
                        ->select(
                            'ct.type',
                            DB::raw('SUM(orders.adultQuantity) as pax'),
                        )
                        ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                            if ($status) $query->where('os.status', $status);
                            if ($fromDate) $query->where('orders.bookingDate', '>=', $fromDate);
                            if ($toDate) $query->where('orders.bookingDate', '<=', $toDate);
                            if ($depart || $dest) {
                                $query->whereIn('orders.id', function ($subquery) use ($depart, $dest) {
                                    $subquery->select('order_tickets.orderId')
                                        ->from('order_tickets')
                                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                        ->where(function ($ticketQuery) use ($depart, $dest) {
                                            if ($depart) $ticketQuery->where('r.fromLocation', $depart);
                                            if ($dest) $ticketQuery->where('r.toLocation', $dest);
                                        });
                                });
                            }
                        })
                        ->groupBy("ct.type")
                        ->orderBy("ct.type")
                        ->get();
                        //->toSql();dd($paxOrders);
        return $paxOrders;
    }

    public static function countPaxTraveledByCustomerType($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest){
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $paxTravels = Ride::select(
                            DB::raw('SUM(o.adultQuantity) as pax'),
                            'ct.type',
                        )
                        ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                        ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')

                        ->leftJoin('order_tickets as ot' , function ($otjoin) {
                            $otjoin->on('rides.id', '=', 'ot.rideId');
                        })
                        ->leftJoin('orders as o' , function ($ojoin) {
                            $ojoin->on('ot.orderId', '=', 'o.id');
                        })
                        ->leftJoin('customer_types as ct', 'ct.id', '=', 'o.customerType')
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
                        ->groupBy("ct.type")
                        ->orderBy("ct.type")
                        ->get();

        return $paxTravels;
    }
}