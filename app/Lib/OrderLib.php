<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Mail\SendTicket;

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
                            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'orders.status', 'orders.phone', 'orders.finalPrice',
                                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.originalPrice', 'orders.couponAmount', 'orders.customerType',
                                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity', 'orders.paymentMethod',
                                    'orders.pickup','orders.dropoff', 'orders.transactionCode', 'orders.transactionDate', 'orders.paymentStatus',
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

    public static function sendMailConfirmTicket($code){
        $order = self::getOrderDetailByCode($code);

        foreach($order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $order->fullname;
            $orderTicket->pickup = $order->pickup;
            $orderTicket->dropoff = $order->dropoff;
            $orderTicket->code = $order->code;
            $orderTicket->adultQuantity = $order->adultQuantity;
            $orderTicket->childrenQuantity = $order->childrenQuantity;

            if ($order->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $order->discount);

            //$orderTicket->type == RETURNTICKET
            $fileName = $orderTicket->type == DEPARTURETICKET ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

            //foreach(self::getLocationFile($orderTicket->locationId) as $value){
            //    $locationFiles[] = ['path' => $value['path'], 'filename' => $value['filename']];
            //}
            $locationFiles = self::getLocationFile($orderTicket->locationId);
            $pdfFiles[] = ['content' => self::generateEticket($orderTicket, $locationFiles), 'filename' => $fileName];
        }
        
        //if there are no email of customer, sending mail to agent instead.
        if (!$order->email) $order->email = $order->agentEmail;

        Mail::to($order->email)->send(new SendTicket($order, $pdfFiles));
    }

    public static function getLocationFile($locationId) {
        $path = 'location/'.$locationId.'/';
        $allFiles = Storage::disk('public')->allFiles($path);

        $files = [];

        foreach ($allFiles as $key => $file) {
            //$files[$key]['content'] = Storage::disk('public')->get($file);
            $files[$key]['filename'] = basename($file);
            $files[$key]['path'] = $file;
        }
        return collect($files);
    }

    public static function generateEticket($orderTicket, $locationFiles = []) {
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

        //Save in temp file
        $tempPdfPath = sys_get_temp_dir() . '/' . uniqid('temp_pdf_') . '.pdf';
        file_put_contents($tempPdfPath, $pdfData);

        //merge with location pdf file
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF($tempPdfPath, 'all'); //eTicket file

        //merge to location pdf file, only one 1 file
        if(Storage::disk('public')->exists($locationFiles[0]['path'])) {
            $pdfMerger->addPDF(Storage::disk('public')->path($locationFiles[0]['path']), 'all');
        }
       
        $pdfMerger->merge();

        //unlink temporary file
        unlink($tempPdfPath);

        return $pdfMerger->output();
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

    public static function downloadBoardingPass($orderTicketId){
        $orderTicket = self::getOrderTicket($orderTicketId);
        //$this->orderDetail = OrderLib::getOrderDetail($orderTicket->orderId); // dirty fill up data

        //if exist promo, change seat price
        if ($orderTicket->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $orderTicket->discount);

        $content = self::generateBoardingPass($orderTicket); 
        $fileName = $orderTicket->type == ONEWAY ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName);
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
