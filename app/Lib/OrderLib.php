<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\OrderTicket;

class OrderLib
{
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
                            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'orders.status', 'orders.phone', 'orders.finalPrice',
                                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.originalPrice', 'orders.couponAmount', 'orders.customerType',
                                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity', 'orders.paymentMethod',
                                    'orders.pickup','orders.dropoff', 'orders.transactionCode', 'orders.transactionDate', 'orders.paymentStatus',
                                    'p.name as promotionName', 'p.code as promotionCode', 'p.discount as discount', 'a.name as agentName', 'ct.name as customerTypeName')
                            ->where('orders.id', $orderId)
                            ->first();
        return $order;
    }

    public static function getOrderDetailbByCode($code) {
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
                            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'orders.status', 'orders.phone', 'orders.finalPrice',
                                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.originalPrice', 'orders.couponAmount', 'orders.customerType',
                                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity', 'orders.paymentMethod',
                                    'orders.pickup','orders.dropoff', 'orders.transactionCode', 'orders.transactionDate', 'orders.paymentStatus',
                                    'p.name as promotionName', 'p.code as promotionCode', 'p.discount as discount', 'a.name as agentName', 'ct.name as customerTypeName')
                            ->where('orders.code', $code)
                            ->first();
        return $order;
    }

    public static function getOrderTicket($orderTicketId) {
        $orderDetail = OrderTicket::select('order_tickets.*', 'r.name', 'r.departTime', 'r.returnTime', 'r.departDate',
                                'fl.id as locationId', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'fl.nameOffice', 'fl.googleMapUrl', 
                                'sc.name as seatClassName', 'sc.price as seatClassPrice',
                                DB::raw('CONCAT(o.firstName, " ",o.lastName) as fullname'), 'o.customerType',
                                'o.phone', 'o.originalPrice', 'o.couponAmount', 'o.finalPrice', 'o.agentId',
                                'o.code', 'o.email', 'o.bookingDate', 'o.note', 'o.adultQuantity', 'o.childrenQuantity', 'o.pickup', 'o.dropoff',
                                'o.childrenQuantity', 'p.code as promotionCode', 'p.name as promotionName', 'p.discount as discount', 
                                'a.name as agentName', 'ct.type as agentType')
                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                        ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                        ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                        ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId')
                        ->leftJoin('orders as o', 'o.id', '=', 'order_tickets.orderId')
                        ->leftJoin('promotions as p', 'p.id', '=', 'o.promotionId')
                        ->leftJoin('agents as a', 'a.id', '=', 'o.agentId')
                        ->leftJoin('customer_types as ct', 'o.customerType', '=', 'a.agentType')
                        ->where('order_tickets.id', $orderTicketId)
                        ->first();
        return $orderDetail;
    }

    public static function generateEticket($orderTicket) {
        $dompdf = new Dompdf();
        
        $logoPath = public_path('img/logo.png');
        $logoData = File::get($logoPath);
        $logoBase64 = base64_encode($logoData);

        $bgPath = public_path('img/bg.png');
        $bgData = File::get($bgPath);
        $bgBase64 = base64_encode($bgData);
        
        $dompdf->loadHTML(View::make('pdf.eTicket', compact('orderTicket', 'bgBase64', 'logoBase64')));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->render();

        $pdfData = $dompdf->output();
        return $pdfData;
    }

    public static function generateBoardingPass($orderTicket) {
        $customPaper = array(0,0,302,378);

        $logoPath = public_path('img/logo.png');
        $logoData = File::get($logoPath);
        $logoBase64 = base64_encode($logoData);

        $dompdf = new Dompdf();
        
        $dompdf->loadHTML(View::make('pdf.boardingPass', compact('orderTicket', 'logoBase64')));
        $dompdf->setPaper($customPaper, 'portrait');
        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->render();

        $pdfData = $dompdf->output();
        return $pdfData;
    }

    public function testPDF(){
        $order = Order::with(['orderTickets' => function($orderTicket){
            $orderTicket->select('order_tickets.*', 'r.*', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                        ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                        ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                        ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
            }])
            ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
            ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'orders.customerType','orders.status',
                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.phone', 'orders.price',
                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.pickup', 'orders.dropoff',
                    'orders.childrenQuantity', 'p.name as promotionName', 'a.name as agentName')
            ->where('orders.code', $this->code)
            ->first();

        $pdfFiles = [];

        //Generate ticket
        foreach($order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $order->fullname;
            $orderTicket->pickup = $order->pickup;
            $orderTicket->dropoff = $order->dropoff;

            $customPaper = array(0,0,302,378);

            $logoPath = public_path('img/logo.png');
            $logoData = File::get($logoPath);
            $logoBase64 = base64_encode($logoData);

            $dompdf = new Dompdf();
            
            $dompdf->loadHTML(View::make('pdf.boardingPass', compact('orderTicket', 'logoBase64')));
            $dompdf->setPaper($customPaper, 'portrait');
            $dompdf->set_option('isHtml5ParserEnabled', true);

            $dompdf->render();

            $dompdf->stream('pdf.pdf');

            break;
            exit;
        }
    }

    public function testEticket(){
        $order = Order::with(['orderTickets' => function($orderTicket){
            $orderTicket->select('order_tickets.*', 'r.*', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'fl.nameOffice', 'fl.googleMapUrl', 'sc.name as seatClassName', 'sc.price as seatClassPrice')//,'sc.name as seatClassName')
                        ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                        ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                        ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                        ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
            }])
            ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
            ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'orders.customerType','orders.status',
                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.phone', 'orders.originalPrice', 'orders.couponAmount', 'orders.finalPrice',
                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity', 'orders.pickup', 'orders.dropoff',
                    'orders.childrenQuantity', 'p.code as promotionCode', 'p.name as promotionName', 'p.discount as discount', 'a.name as agentName')
            ->where('orders.code', $this->code)
            ->first();

        $pdfFiles = [];

        //Generate ticket
        foreach($order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $order->fullname;
            $orderTicket->pickup = $order->pickup;
            $orderTicket->dropoff = $order->dropoff;
            $orderTicket->code = $order->code;
            $orderTicket->adultQuantity = $order->adultQuantity;
            $orderTicket->childrenQuantity = $order->childrenQuantity;
            if($orderTicket->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $orderTicket->discount);

            if ($orderTicket->type == DEPARTURETICKET) $pdfFiles[] = ['content' => $this->generateEticket($orderTicket), 'filename' => 'Departure Ticket.pdf'];
            if ($orderTicket->type == RETURNTICKET) $pdfFiles[] = ['content' => $this->generateEticket($orderTicket), 'filename' => 'Return Ticket.pdf'];

            $dompdf = new Dompdf();

            $bgPath = public_path('img/bg.png');
            $bgData = File::get($bgPath);
            $bgBase64 = base64_encode($bgData);

            $logoPath = public_path('img/logo.png');
            $logoData = File::get($logoPath);
            $logoBase64 = base64_encode($logoData);
            
            $dompdf->loadHTML(View::make('pdf.eTicket', compact('orderTicket', 'bgBase64', 'logoBase64')));
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->set_option('isHtml5ParserEnabled', true);
            $dompdf->render();

            $dompdf->stream('pdf.pdf');

            break;
            exit;
        }
    }
}
