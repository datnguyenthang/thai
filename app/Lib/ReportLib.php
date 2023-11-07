<?php

namespace App\Lib;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Ride;

class ReportLib {

    public static function dateFormat($type = 'byMonth', $fromDate, $toDate){
        $currentDay = Carbon::now()->format('Y-m-d');
        $currentYearMonth = Carbon::now()->format('Y-m');
        $currentYear = Carbon::now()->format('Y');
        switch ($type) {
            case 'byYear':
                $dateFormat = '%Y-%m';
                $fromDate = Carbon::parse("$fromDate-01-01")->startOfYear()->toDateString();

                if ($currentYear == $toDate) $toDate = $currentDay;
                else $toDate = Carbon::parse("$toDate-12-31")->endOfYear()->toDateString();
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

    public static function getCustomerTypePayment($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $customerTypePayments = Order::with(['orderTickets' => function($orderTicket){
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
                            'ct.name as name',
                            DB::raw('SUM(orders.finalPrice) as amount')
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
                        ->groupBy("ct.name")
                        ->orderBy("ct.name")
                        ->get();

        return $customerTypePayments;
    }

    public static function getPaymentMethod($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $paymentMethodDetails = Order::with(['orderTickets' => function($orderTicket){
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
                        ->leftJoin('order_payments as op', function($join) {
                            $join->on('orders.id', '=', 'op.orderId')
                                ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = orders.id)');
                        })
                        ->leftJoin('payment_methods as pm', 'pm.id', '=', 'op.paymentMethod')
                        ->select(
                            'pm.name as name',
                            DB::raw('SUM(orders.finalPrice) as amount')
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
                        ->groupBy("pm.name")
                        ->orderBy("pm.name")
                        ->get();
        return $paymentMethodDetails;
    }

    public static function getCustomerTypePaymentTicket($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $customerTypePayments = Ride::select(
                                    'ct.name as name',
                                    DB::raw('SUM(ot.price) as amount')
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
                                ->leftJoin('customer_types as ct', 'ct.id', '=', 'o.customerType')

                                ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                                    if ($status) $query->where('os.status', $status);
                                    if ($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                                    if ($toDate) $query->where('rides.departDate', '<=', $toDate);
                                    if ($depart) $query->where('rides.fromLocation', $depart);
                                    if ($dest) $query->where('rides.toLocation', $dest);
                                })
                                ->groupBy("ct.name")
                                ->orderBy("ct.name")
                                ->get();

        return $customerTypePayments;
    }

    public static function getPaymentMethodTicket($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER, $depart, $dest) {
        list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $paymentMethodDetails = Ride::select(
                                    'pm.name as name',
                                    DB::raw('SUM(ot.price) as amount')
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
                                ->leftJoin('order_payments as op', function($join) {
                                    $join->on('o.id', '=', 'op.orderId')
                                        ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = o.id)');
                                })
                                ->leftJoin('payment_methods as pm', 'pm.id', '=', 'op.paymentMethod')

                                ->where(function ($query) use ($fromDate, $toDate, $status, $depart, $dest) {
                                    if ($status) $query->where('os.status', $status);
                                    if ($fromDate) $query->where('rides.departDate', '>=', $fromDate);
                                    if ($toDate) $query->where('rides.departDate', '<=', $toDate);
                                    if ($depart) $query->where('rides.fromLocation', $depart);
                                    if ($dest) $query->where('rides.toLocation', $dest);
                                })
                                ->groupBy("pm.name")
                                ->orderBy("pm.name")
                                ->get();

        return $paymentMethodDetails;
    }
}
