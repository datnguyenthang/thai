<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Ride;
use App\Models\PaymentMethod;

class AccountingLib {

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

                //if ($currentYearMonth == $toDate) $toDate = $currentDay;
                //else 
                //$toDate = Carbon::now()->parse("$toDate")->lastOfMonth()->formatLocalized($dateFormat);
				$toDate = Carbon::now()->parse("$toDate")->endOfMonth()->setTime(23, 59, 59)->format($dateFormat);
                break;
            case 'byDay':
                $dateFormat = '%Y-%m-%d';
                $fromDate = Carbon::now()->parse("$fromDate")->startOfDay()->format('Y-m-d H:i:s');
                $toDate = Carbon::now()->parse("$toDate")->endOfDay()->format('Y-m-d H:i:s');
                break;
        }
        return [$dateFormat, $fromDate, $toDate];
    }

    public static function cashFlowByDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER) {
        list($dateFormat, $fromDate, $toDate) = self::dateFormat($type, $fromDate, $toDate);

        $workflows = Order::with(['orderTickets' => function($orderTicket){
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
                        ->selectRaw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}') as data")
                        ->selectRaw('SUM(orders.adultQuantity) as pax')
                        ->selectRaw('SUM(orders.finalPrice) as revenue')
                        ->selectRaw("SUM(CASE WHEN op.paymentStatus = 8 THEN orders.finalPrice ELSE 0 END) as paid");

        $pms = PaymentMethod::get();
        foreach ($pms as $pm) {
            $workflows->selectRaw("SUM(CASE WHEN (pm.name = '{$pm->name}' AND op.paymentStatus = 8) THEN orders.finalPrice ELSE 0 END) as '{$pm->name}-paid'");
        }

        $workflows->selectRaw("SUM(CASE WHEN op.paymentStatus = 0 THEN orders.finalPrice ELSE 0 END) as notpaid");
        foreach ($pms as $pm) {
            $workflows->selectRaw("SUM(CASE WHEN (pm.name = '{$pm->name}' AND op.paymentStatus = 0) THEN orders.finalPrice ELSE 0 END) as '{$pm->name}-notpaid'");
        }

        $workflows = $workflows
                ->where(function ($query) use ($fromDate, $toDate, $status) {
                    if ($status) $query->where('os.status', $status);
                    if ($fromDate) $query->where('orders.bookingDate', '>=', $fromDate);
                    if ($toDate) $query->where('orders.bookingDate', '<=', $toDate);
                })
                ->groupBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                ->orderBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                ->get();

        return $workflows;
    }
}