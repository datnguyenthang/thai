<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

use App\Mail\SendTicket;
use Dompdf\Dompdf;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Ticket;
use App\Models\Order;

class Payment extends Component
{
    public $step = 0;

    public $code;
    public $order;

    public $cardNumber = "9447876598712";
    public $cardHolder = "Karfol Ripy";
    public $expirationDate = "09/27";
    public $cvv = 415;

    public function mount() {
        $this->code = Route::current()->parameter('code');
        $this->order = Order::with(['orderTickets' => function($orderTicket){
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
    }

    public function payment() {
        $this->step = 1;
        //send email after finish

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

            if($orderTicket->type == DEPARTURETICKET) $pdfFiles[] = ['content' => $this->generateTicket($orderTicket), 'filename' => 'Departure Ticket.pdf'];
            if($orderTicket->type == RETURNTICKET) $pdfFiles[] = ['content' => $this->generateTicket($orderTicket), 'filename' => 'Return Ticket.pdf'];
        }

        Mail::to($order->email)->send(new SendTicket($order, $pdfFiles));
    }

    public function render() {
        if ($this->step === 0) return view('livewire.frontend.homepage.payment');
        if ($this->step === 1) return view('livewire.frontend.homepage.success-booking');
    }

    public function generateTicket($orderTicket) {
        $customPaper = array(0,0,550,530);

        $logoPath = public_path('img/logo.png');
        $logoData = File::get($logoPath);
        $logoBase64 = base64_encode($logoData);

        $dompdf = new Dompdf();
        
        $dompdf->loadHTML(View::make('pdf.boardingPass', compact('orderTicket', 'logoBase64')));
        $dompdf->setPaper($customPaper, 'portrait');
        $dompdf->render();

        $pdfData = $dompdf->output();
        return $pdfData;
    }
}
