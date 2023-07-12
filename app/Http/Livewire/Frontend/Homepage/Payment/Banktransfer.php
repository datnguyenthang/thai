<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;

use Livewire\WithFileUploads;
use App\Lib\OrderLib;

use App\Mail\SendTicket;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Models\OrderStatus;

class Banktransfer extends Component
{
    use WithFileUploads;

    protected $orderLib;

    public $proofFiles = [];
    public $photos = [];
    public $folderName;

    public $counting = 1;

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->code = $this->folderName = $this->order->code;
        $this->loadProof();
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

    public function payment($paymentMethod) {
        //Update status of this order
        OrderStatus::create([
            'orderId' => intVal($this->order->id),
            'status' => UPPLOADTRANSFER,
            //'note' => $this->note,
            'changeDate' => date('Y-m-d H:i:s'),
            //'userId' => Auth::id(),
        ]);

        $this->order = OrderLib::getOrderDetailbByCode($this->code);

        foreach($this->order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $this->order->fullname;
            $orderTicket->pickup = $this->order->pickup;
            $orderTicket->dropoff = $this->order->dropoff;
            $orderTicket->code = $this->order->code;
            $orderTicket->adultQuantity = $this->order->adultQuantity;
            $orderTicket->childrenQuantity = $this->order->childrenQuantity;

            if ($this->order->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $this->order->discount);

            if ($orderTicket->type == DEPARTURETICKET) {
                $pdfFiles[] = ['content' => OrderLib::generateEticket($orderTicket), 'filename' => 'Departure Ticket.pdf'];

                foreach($this->getLocationFile($orderTicket->locationId) as $value){
                    $pdfFiles[] = ['content' => $value['content'], 'filename' => $value['filename']];
                }
                
            }
            if ($orderTicket->type == RETURNTICKET) {
                $pdfFiles[] = ['content' => OrderLib::generateEticket($orderTicket), 'filename' => 'Return Ticket.pdf'];

                foreach($this->getLocationFile($orderTicket->locationId) as $value){
                    $pdfFiles[] = ['content' => $value['content'], 'filename' => $value['filename']];
                }
            }
        }

        Mail::to($this->order->email)->send(new SendTicket($this->order, $pdfFiles));
        
        $this->emitUp('updatePayment', UPPLOADTRANSFER);
    }

    public function render()
    {
        return view('livewire.frontend.homepage.payment.banktransfer');
    }
}
