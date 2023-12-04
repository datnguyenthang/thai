<?php

namespace App\Lib;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Ride;
use App\Models\Agent;
use App\Models\PaymentMethod;

class DebtLib {

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
                $toDate = Carbon::now()->parse("$toDate")->lastOfMonth()->formatLocalized($dateFormat);
                break;
            case 'byDay':
                $dateFormat = '%Y-%m-%d';
                $fromDate = Carbon::now()->parse("$fromDate")->startOfDay()->format('Y-m-d H:i:s');
                $toDate = Carbon::now()->parse("$toDate")->endOfDay()->format('Y-m-d H:i:s');
                break;
        }
        return [$dateFormat, $fromDate, $toDate];
    }

    public static function debtByDate($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER) {
        list($dateFormat, $fromDate, $toDate) = self::dateFormat($type, $fromDate, $toDate);
        $agentDebts = Agent::select('agents.id as agentId', 'agents.name as agentName', 'agents.code as agentCode',
                                    DB::raw('SUM(o.adultQuantity) as pax'),
                                    DB::raw('SUM(o.finalPrice) as revenue'),
                                    DB::raw('SUM(CASE WHEN op.paymentStatus = 8 THEN o.finalPrice ELSE 0 END) as paid'),
                                    DB::raw('SUM(CASE WHEN op.paymentStatus = 0 THEN o.finalPrice ELSE 0 END) as notpaid')
                                )
                            ->leftJoin('orders as o', 'o.agentId', '=', 'agents.id')
                            ->leftJoin('order_tickets as ot', 'o.id', '=', 'ot.orderId')
                            ->leftJoin('order_payments as op', function($join) {
                                $join->on('o.id', '=', 'op.orderId')
                                    ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = o.id)');
                            })
                            ->leftJoin('order_statuses as os', function($join) {
                                $join->on('o.id', '=', 'os.orderId')
                                    ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = o.id)');
                            })
                            ->where(function ($query) use ($fromDate, $toDate, $status) {
                                if ($status) $query->where('os.status', $status);
                                if ($fromDate) $query->where('o.bookingDate', '>=', $fromDate);
                                if ($toDate) $query->where('o.bookingDate', '<=', $toDate);
                            })
                            ->groupBy('agents.id', 'agents.name', 'agents.code')
                            ->orderBy('revenue', 'DESC')
                            ->get();

        $agentDebts = $agentDebts->map(function ($agentDebt) use ($fromDate, $toDate){
            // Calculate a new value based on existing attributes
            $rideInfo = self::getDeptByRide($agentDebt->agentId, $fromDate, $toDate);

            // Add the new attribute
            $agentDebt->ridePax = $rideInfo->pax ?? 0;
            $agentDebt->ridePaid = $rideInfo->paid ?? 0;
            $agentDebt->rideNotPaid = $rideInfo->notpaid ?? 0;
        
            // Return the modified item
            return $agentDebt;
        });
        return $agentDebts;
    }

    public static function getDeptByRide($agentId, $fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER){
        list($dateFormat, $fromDate, $toDate) = self::dateFormat($type, $fromDate, $toDate);
        $rideDebt = Agent::select('agents.id as agentId', 'agents.name as agentName', 'agents.code as agentCode',
                                    DB::raw('SUM(o.adultQuantity) as pax'),
                                    DB::raw('SUM(o.finalPrice) as revenue'),
                                    DB::raw('SUM(CASE WHEN op.paymentStatus = 8 THEN o.finalPrice ELSE 0 END) as paid'),
                                    DB::raw('SUM(CASE WHEN op.paymentStatus = 0 THEN o.finalPrice ELSE 0 END) as notpaid')
                                )
                            ->leftJoin('orders as o', 'o.agentId', '=', 'agents.id')
                            ->leftJoin('order_tickets as ot', 'o.id', '=', 'ot.orderId')
                            ->leftJoin('rides as r', 'r.id', '=', 'ot.rideId')
                            ->leftJoin('order_payments as op', function($join) {
                                $join->on('o.id', '=', 'op.orderId')
                                    ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = o.id)');
                            })
                            ->leftJoin('order_statuses as os', function($join) {
                                $join->on('o.id', '=', 'os.orderId')
                                    ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = o.id)');
                            })
                            ->where(function ($query) use ($agentId, $fromDate, $toDate, $status) {
                                $query->where('agents.id', $agentId);
                                if ($status) $query->where('o.status', $status);
                                if ($fromDate) $query->where('r.departDate', '>=', $fromDate);
                                if ($toDate) $query->where('r.departDate', '<=', $toDate);
                            })
                            ->groupBy('agents.id', 'agents.name', 'agents.code')
                            ->orderBy('revenue', 'DESC')
                            ->first();
        return $rideDebt;
    }
}