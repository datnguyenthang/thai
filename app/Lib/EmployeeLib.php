<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Lib\ChartLib;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderTicket;
use App\Models\Ride;

class EmployeeLib {

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

    public static function getUserList(){
        $userList = User::select(
            'users.id',
            'users.name as userName',
            'a.name as agentName'
        )
        ->leftJoin('agents as a', 'a.id', '=', 'users.agentId')
        ->whereIn('role', array(AGENT, MODERATOR, MANAGER))
        ->get();
        return $userList;
    }

    public static function calPerDif($newValue, $oldValue) {
        if ($oldValue == 0) {
            return null;
        }
        return round(abs(($newValue - $oldValue) / $oldValue) * 100, 2);
    }

    public static function getListPerformanceAllUser($fromDate, $toDate, $bookingStatus = CONFIRMEDORDER, $status = ACTIVE){
        $performances = User::select(
                            'users.id',
                            'users.name as userName',
                            'a.name as agentName',
                            DB::raw('COALESCE(COUNT(o.id), 0) as totalOrder'),
                            DB::raw('COALESCE(SUM(o.finalPrice), 0) as totalPrice')
                        )
                        ->leftJoin('agents as a', 'a.id', '=', 'users.agentId')
                        ->leftJoin('orders as o', function($join) use ($fromDate, $toDate) {
                            $join->on('users.id', '=', 'o.userId')
                                ->where(function ($query) use ($fromDate, $toDate) {
                                    if ($fromDate) $query->where('o.bookingDate', '>=', $fromDate);
                                    if ($toDate) $query->where('o.bookingDate', '<=', $toDate);
                                });
                        })
                        ->leftJoin('order_statuses as os', function($ojoin) use ($bookingStatus) {
                            $ojoin->on('o.id', '=', 'os.orderId')
                                ->where('os.id', DB::raw('(select max(id) from order_statuses where order_statuses.orderId = o.id and os.status ='.$bookingStatus.')'));
                        })
                        ->where('users.status', ACTIVE)
                        ->whereIn('users.role', array(AGENT, MODERATOR, MANAGER))
                        ->groupBy('users.id', 'users.name', 'a.name')
                        ->orderBy('totalPrice', 'DESC')
                        ->get();
        return $performances;
    }

    public static function getPerformanceUser($userId, $fromDate, $toDate, $bookingStatus = CONFIRMEDORDER, $status = ACTIVE){
        //list($dateFormat, $fromDate, $toDate)  = self::dateFormat($type, $fromDate, $toDate);

        $performance = User::select(
                            DB::raw('COALESCE(COUNT(o.id), 0) as totalOrder'),
                            DB::raw('COALESCE(SUM(o.finalPrice), 0) as revenue'),
                            DB::raw("DATE_FORMAT(o.bookingDate, '%Y-%m-%d') as data")
                        )
                        ->leftJoin('agents as a', 'a.id', '=', 'users.agentId')
                        ->leftJoin('orders as o', 'users.id', '=', 'o.userId')
                        ->leftJoin('order_statuses as os', function($ojoin) {
                            $ojoin->on('o.id', '=', 'os.orderId')
                                ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = o.id)');
                        })
                        ->where(function ($query) use ($userId, $fromDate, $toDate, $bookingStatus, $status) {
                            if ($userId) $query->where('o.userId', $userId);
                            if ($bookingStatus) $query->where('os.status', $bookingStatus);
                            if ($fromDate) $query->where('o.bookingDate', '>=', $fromDate);
                            if ($toDate) $query->where('o.bookingDate', '<=', $toDate);
                            if ($status) $query->where('u.status', ACTIVE);
                        })
                        ->groupBy(DB::raw("DATE_FORMAT(o.bookingDate, '%Y-%m-%d')"))
                        ->orderBy(DB::raw("DATE_FORMAT(o.bookingDate, '%Y-%m-%d')"))
                        ->get();
        return self::modifyData($performance, $fromDate, $toDate);
    }

    public static function performanceAllTime($fromDate, $toDate, $type = 'byMonth', $status = CONFIRMEDORDER) {
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
                                DB::raw('COALESCE(COUNT(orders.id), 0) as totalOrder'),
                                DB::raw('COALESCE(SUM(orders.finalPrice), 0) as revenue'),
                                DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}') as data")
                            )
                            ->where(function ($query) use ($fromDate, $toDate, $status) {
                                if ($status) $query->where('os.status', $status);
                                if ($fromDate) $query->where('orders.bookingDate', '>=', $fromDate);
                                if ($toDate) $query->where('orders.bookingDate', '<=', $toDate);
                            })
                            ->groupBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                            ->orderBy(DB::raw("DATE_FORMAT(orders.bookingDate, '{$dateFormat}')"))
                            ->get();

        return self::modifyData($revenueOrders, $fromDate, $toDate);
    }

    public static function modifyData($data, $fromDate, $toDate) {
        $modifiedData = collect(); // Create a new collection for modified data

        //list($type, $fromDate, $toDate) = ChartLib::dateFormat('byMonth', $fromDate, $toDate);
        $currentDate = new \DateTime($fromDate);
        $endDate = new \DateTime($toDate);

        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->format('Y-m-d');

            // Check if the data for the current date exists
            $itemForCurrentDate = $data->firstWhere('data', $formattedDate);
    
            if ($itemForCurrentDate) {
                // If data for the current date exists, add it to the modified collection
                $modifiedData->push($itemForCurrentDate);
            } else {
                // If data for the current date doesn't exist, add a new item
                //$modifiedData->push(['data' => $formattedDate]);
                $modifiedData->push(['data' => $formattedDate, 'totalOrder' => 0, 'revenue' => 0]);
            }
    
            $currentDate->modify('+1 day');
        }
        return $modifiedData;
    }

}