<?php

namespace App\Lib;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Mail\SendTicket;

use App\Models\Order;
use App\Models\OrderTicket;

class OrderLib {

    public static function getOrderDetail($orderId) {
        $order = Order::with(['orderTickets' => function($orderTicket){
                                $orderTicket->select('order_tickets.*', 'r.name', 'r.departTime', 'r.returnTime', 'r.departDate',
                                                    'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName', 'sc.price as seatPrice')
                                            ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                            ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                            ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                            ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                            }])
                            ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
                            ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
                            ->leftJoin('customer_types as ct', 'ct.id', '=', 'orders.customerType')
                            ->leftJoin('order_payments as op', function($join) {
                                $join->on('orders.id', '=', 'op.orderId')
                                     ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = orders.id)');
                            })
                            ->leftJoin('order_statuses as os', function($join) {
                                $join->on('orders.id', '=', 'os.orderId')
                                     ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
                            })
                            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'os.status', 'orders.phone', 'orders.finalPrice',
                                    DB::raw('CONCAT(COALESCE(firstname, ""), " ", COALESCE(lastName, "")) as fullname'), 'orders.originalPrice', 'orders.couponAmount', 'orders.customerType',
                                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity', 'orders.paymentMethod',
                                    'orders.pickup','orders.dropoff', 'op.paymentMethod', 'op.transactionCode', 'op.transactionDate', 'op.paymentStatus',
                                    'p.name as promotionName', 'p.code as promotionCode', 'p.discount as discount', 'a.name as agentName', 'ct.name as customerTypeName')
                            ->where('orders.id', $orderId)
                            ->first();
        return $order;
    }

    public static function getOrderDetailByCode($code) {
        $order = Order::with(['orderTickets' => function($orderTicket){
                                $orderTicket->select('order_tickets.*', 'r.name', 'r.departTime', 'r.returnTime', 'r.departDate',
                                                    'fl.id as locationId', 'fl.name as fromLocationName', 'fl.nameOffice', 'fl.googleMapUrl', 'tl.name as toLocationName', 'sc.name as seatClassName', 'sc.price as seatPrice')
                                            ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                            ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                            ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                            ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                            }])
                            ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
                            ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
                            ->leftJoin('customer_types as ct', 'ct.id', '=', 'orders.customerType')
                            ->leftJoin('order_payments as op', function($join) {
                                $join->on('orders.id', '=', 'op.orderId')
                                     ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = orders.id)');
                            })
                            ->leftJoin('order_statuses as os', function($join) {
                                $join->on('orders.id', '=', 'os.orderId')
                                     ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
                            })
                            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'os.status', 'orders.phone', 'orders.finalPrice',
                                    DB::raw('CONCAT(COALESCE(firstname, ""), " ", COALESCE(lastName, "")) as fullname'), 'orders.originalPrice', 'orders.couponAmount', 'orders.customerType',
                                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity', 'orders.paymentMethod',
                                    'orders.pickup','orders.dropoff', 'op.paymentMethod', 'op.transactionCode', 'op.transactionDate', 'op.paymentStatus',
                                    'p.name as promotionName', 'p.code as promotionCode', 'p.discount as discount', 
                                    'a.name as agentName', 'a.email as agentEmail', 'ct.name as customerTypeName')
                            ->where('orders.code', $code)
                            ->first();
        return $order;
    }

    public static function getOrderTicket($orderTicketId) {
        $orderDetail = OrderTicket::select('order_tickets.*', 'r.name', 'r.departTime', 'r.returnTime', 'r.departDate',
                                'fl.id as locationId', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'fl.nameOffice', 'fl.googleMapUrl', 
                                'sc.name as seatClassName', 'sc.price as seatClassPrice',
                                DB::raw('CONCAT(COALESCE(firstname, ""), " ", COALESCE(lastName, "")) as fullname'), 'o.customerType',
                                'o.phone', 'o.originalPrice', 'o.couponAmount', 'o.finalPrice', 'o.agentId',
                                'o.code', 'o.email', 'o.bookingDate', 'o.note', 'o.adultQuantity', 'o.childrenQuantity', 'o.pickup', 'o.dropoff',
                                'o.childrenQuantity', 'p.code as promotionCode', 'p.name as promotionName', 'p.discount as discount', 
                                'op.paymentStatus', 'os.status as orderStatus', 'a.name as agentName', 'ct.type as agentType')
                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                        ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                        ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                        ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId')
                        ->leftJoin('orders as o', 'o.id', '=', 'order_tickets.orderId')
                        ->leftJoin('promotions as p', 'p.id', '=', 'o.promotionId')
                        ->leftJoin('agents as a', 'a.id', '=', 'o.agentId')
                        ->leftJoin('customer_types as ct', 'o.customerType', '=', 'a.agentType')
                        ->leftJoin('order_payments as op', function($join) {
                            $join->on('o.id', '=', 'op.orderId')
                                 ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = o.id)');
                        })
                        ->leftJoin('order_statuses as os', function($join) {
                            $join->on('o.id', '=', 'os.orderId')
                                 ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = o.id)');
                        })
                        ->where('order_tickets.id', $orderTicketId)
                        ->first();
        return $orderDetail;
    }

    public static function getOrderListQuery($orderCode, $customerName, $customerPhone, $customerType,
                                             $agentId, $fromLocation, $toLocation){
        return Order::with(['orderTickets' => function($orderTicket){
            $orderTicket->select('order_tickets.*', 'r.name', 'r.departDate', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                        ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                        ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                        ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
            }])
            ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
            ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
            ->leftJoin('customer_types as ct', 'ct.id', '=', 'orders.customerType')
            ->leftJoin('users as u', 'u.id', '=', 'orders.userId')
            
            ->leftJoin('order_payments as op', function($join) {
                $join->on('orders.id', '=', 'op.orderId')
                    ->whereRaw('op.id = (select max(id) from order_payments where order_payments.orderId = orders.id)');
            })
            ->leftJoin('payment_methods as pm', 'pm.id', '=', 'op.paymentMethod')
            ->leftJoin('order_statuses as os', function($join) {
                $join->on('orders.id', '=', 'os.orderId')
                    ->whereRaw('os.id = (select max(id) from order_statuses where order_statuses.orderId = orders.id)');
            })
            ->select('orders.id', 'orders.code', 'u.name as username', 'orders.isReturn', 'ct.name as customerTypeName', 'a.name as agentName', 'orders.channel', 
                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.phone', 'orders.email', 'orders.note', 'orders.pickup', 'orders.dropoff',
                    'orders.adultQuantity', 'orders.childrenQuantity', 'orders.finalPrice','orders.bookingDate', 'os.status',
                    'p.name as promotionName', 'op.paymentStatus', 'pm.name as paymentMethod', 'op.transactionCode', 'op.transactionDate')
            ->where(function ($query) use ($orderCode, $customerName, $customerPhone, $customerType, $agentId, $fromLocation, $toLocation) {
                if ($orderCode) $query->where('orders.code', 'like', '%'.$orderCode.'%');
                if ($customerName) $query->whereRaw('CONCAT(orders.firstName, " ", orders.lastName) LIKE ?', ['%'.$customerName.'%']);
                if ($customerPhone) $query->where('orders.phone', 'like', '%'.$customerPhone.'%');
                if ($customerType >= 0) $query->where('orders.customerType', $customerType);
                if ($agentId) $query->where('orders.agentId', $agentId);

                if ($fromLocation || $toLocation) {
                    $query->whereIn('orders.id', function ($subquery) use ($fromLocation, $toLocation) {
                        $subquery->select('order_tickets.orderId')
                            ->from('order_tickets')
                            ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                            ->where(function ($ticketQuery) use ($fromLocation, $toLocation) {
                                if ($fromLocation) $ticketQuery->where('r.fromLocation', $fromLocation);
                                if ($toLocation) $ticketQuery->where('r.toLocation', $toLocation);
                            });
                    });
                }
            });
    }
}
