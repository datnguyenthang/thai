<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Lib\OrderLib;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Mail\SendTicket;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Ticket;
use App\Models\Order;

class Payment extends Component
{
    use WithFileUploads;
    protected $orderLib;

    const GOTOPAY = 0;
    const PAID = 1;

    public $step = 0;

    public $code;
    public $order;

    public $cardNumber = "9447876598712";
    public $cardHolder = "Karfol Ripy";
    public $expirationDate = "09/27";
    public $cvv = 415;

    public $proofFiles = [];
    public $photos = [];
    public $folderName;

    public $counting = 1;

    public function mount() {

        $this->code = $this->folderName = Route::current()->parameter('code');
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
                DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.phone', 'orders.originalPrice', 'orders.couponAmount', 'orders.finalPrice',
                'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.pickup', 'orders.dropoff',
                'orders.childrenQuantity', 'p.code as promotionCode', 'p.name as promotionName', 'p.discount as discount', 'a.name as agentName')
        ->where('orders.code', $this->code)
        ->first();

        //redirect to homepage if there are no order match code found
        if (!$this->order) redirect('/');

        $this->loadProof();
    }

    public function hydrate(){
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
                            DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.phone', 'orders.originalPrice', 'orders.couponAmount', 'orders.finalPrice',
                            'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.pickup', 'orders.dropoff',
                            'orders.childrenQuantity', 'p.code as promotionCode', 'p.name as promotionName', 'p.discount as discount', 'a.name as agentName')
                    ->where('orders.code', $this->code)
                    ->first();
    }

    //Loading proof images
    public function loadProof() {
        $this->photos = null;

        $folderPath = 'proofs/'. $this->folderName;
        $files = Storage::disk('public')->allFiles($folderPath);

        foreach ($files as $file) { 
            $dimensions = getimagesize(Storage::disk('public')->path($file))[0] .'x'. getimagesize(Storage::disk('public')->path($file))[1];
            $url = Storage::disk('public')->url($file);
            //$path = Storage::disk('public')->path($file);
            
            $this->photos[] = [
                'url' => $url,
                'path' => $file,
                'name' => basename($file),
                'extension' => Storage::disk('public')->mimeType($file),
                'dimension' => $dimensions,
            ];
        }
    }

    //Delete proof images
    public function deleteProofs($filePath) {

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }
        $this->loadProof();
    }

    //Upload proof images
    public function uploadProof() {
        $this->validate([
           'proofFiles.*' => 'required|mimes:jpeg,jpg,png,gif,pdf|max:5120',
        ]);

        //creating folder inside storage folder
        $folderPath = 'proofs/'. $this->folderName;
        $disk = 'public';

        if (!Storage::exists($folderPath)) {
            Storage::disk($disk)->makeDirectory($folderPath, 0777, true, true);
        }

        if ($this->proofFiles){
            foreach ($this->proofFiles as $file) {
                $file->storeAs($folderPath, $file->getClientOriginalName(), 'public');
                //Storage::disk($disk)->putFile($folderPath, $file);
            }
        }

        $this->counting++;
        $this->proofFiles = null;

        //get image upload again
        $this->loadProof();
    }

    public function payment($paymentMethod) {

        if ($paymentMethod == BANKTRANSFER) {
            Order::where('code', $this->code)
                ->update(['paymentMethod' => BANKTRANSFER, 'status' => UPPLOADTRANSFER]);
        }

        if ($paymentMethod == CARD) {
            Order::where('code', $this->code)
                ->update(['paymentMethod' => CARD, 'status' => COMPLETEDORDER]);
        }

        $this->step = self::PAID;
        //send email after finish

        $this->order = Order::with(['orderTickets' => function($orderTicket){
            $orderTicket->select('order_tickets.*', 'r.*','fl.id as locationId', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'fl.nameOffice', 'fl.googleMapUrl', 'sc.name as seatClassName', 'sc.price as seatClassPrice')//,'sc.name as seatClassName')
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

        //Generate eticket
        foreach($this->order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $this->order->fullname;
            $orderTicket->pickup = $this->order->pickup;
            $orderTicket->dropoff = $this->order->dropoff;
            $orderTicket->code = $this->order->code;
            $orderTicket->adultQuantity = $this->order->adultQuantity;
            $orderTicket->childrenQuantity = $this->order->childrenQuantity;

            if ($this->order->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $this->order->discount);
                
            $orderLib = new OrderLib();

            if ($orderTicket->type == DEPARTURETICKET) {
                $pdfFiles[] = ['content' => $orderLib->generateEticket($orderTicket), 'filename' => 'Departure Ticket.pdf'];

                foreach($this->getLocationFile($orderTicket->locationId) as $value){
                    $pdfFiles[] = ['content' => $value['content'], 'filename' => $value['filename']];
                }
                
            }
            if ($orderTicket->type == RETURNTICKET) {
                $pdfFiles[] = ['content' => $orderLib->generateEticket($orderTicket), 'filename' => 'Return Ticket.pdf'];

                foreach($this->getLocationFile($orderTicket->locationId) as $value){
                    $pdfFiles[] = ['content' => $value['content'], 'filename' => $value['filename']];
                }
            }
        }
        //Generate ticket
        /*
        foreach($order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $order->fullname;
            $orderTicket->pickup = $order->pickup;
            $orderTicket->dropoff = $order->dropoff;

            if ($orderTicket->type == DEPARTURETICKET) $pdfFiles[] = ['content' => $this->generateTicket($orderTicket), 'filename' => 'Departure Ticket.pdf'];
            if ($orderTicket->type == RETURNTICKET) $pdfFiles[] = ['content' => $this->generateTicket($orderTicket), 'filename' => 'Return Ticket.pdf'];
        }
        */
        Mail::to($this->order->email)->send(new SendTicket($this->order, $pdfFiles));
    }

    public function getLocationFile($locationId) {
        $path = 'location/'.$locationId.'/';
        $allFiles = Storage::disk('public')->allFiles($path);
       
        $files = [];

        foreach ($allFiles as $key => $file) {
            $files[$key]['content'] = Storage::disk('public')->get($file);
            $files[$key]['filename'] = basename($file);
        }
        return collect($files);
    }

    public function render() {
        //$this->testPDF(); // Test ticket PDF file
        //$this->testEticket(); // Test e-ticket PDF file

        if ($this->step === self::GOTOPAY) return view('livewire.frontend.homepage.payment');
        if ($this->step === self::PAID) return view('livewire.frontend.homepage.success-booking');
    }
}
