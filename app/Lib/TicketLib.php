<?php

namespace App\Lib;

use Dompdf\Dompdf;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Lib\OrderLib;

class TicketLib {

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

        //Merge location file if exist
        if (isset($locationFiles[0]['path'])) {
            //Save in temp file
            $tempPdfPath = sys_get_temp_dir() . '/' . uniqid('temp_pdf_') . '.pdf';
            file_put_contents($tempPdfPath, $pdfData);

            //merge with location pdf file
            $pdfMerger = PDFMerger::init();
            $pdfMerger->addPDF($tempPdfPath, 'all'); //eTicket file

            //merge to location pdf file, only one 1 file
            if (Storage::disk('public')->exists($locationFiles[0]['path'])) {
                $pdfMerger->addPDF(Storage::disk('public')->path($locationFiles[0]['path']), 'all');
            }

            $pdfMerger->merge();

            //unlink temporary file
            unlink($tempPdfPath);

            return $pdfMerger->output();
        }

        return $pdfData;
    }

    public static function downloadEticket($orderTicketId) { 
        $orderTicket = self::getOrderTicket($orderTicketId);
        //$this->orderDetail = self::getOrderDetail($orderTicket->orderId); // dirty fill up data

        //if exist promo, change seat price
        //if ($orderTicket->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $orderTicket->discount);

        $content = self::generateEticket($orderTicket); 
        $fileName = $orderTicket->type == ONEWAY ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName);
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
        $orderTicket = OrderLib::getOrderTicket($orderTicketId);
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
