<?php

namespace App\Http\Livewire\Backend\Moderator;
use Illuminate\Support\Facades\Storage;

use Livewire\Component;
use App\Lib\OrderLib;

class ModeratorProcessOrder extends Component
{
    public $order;
    public $orderId;
    public $confirmation;
    public $reasonDecline;

    public $photos = [];

    public function mount($orderId){
        $this->confirmation = CONFIRMEDORDER;
        $this->orderId = $orderId;
        $this->order = OrderLib::getOrderDetail($this->orderId);
        
        $this->loadProof();
    }

    public function hydrate() {
        $this->order = OrderLib::getOrderDetail($this->orderId);
    }

    //Loading proof images
    public function loadProof() {

        $folderPath = 'proofs/'. $this->order->code;
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

    public function downloadTicket($orderTicketId){
        
        $orderTicket = OrderLib::getOrderTicket($orderTicketId);
        //$this->orderDetail = OrderLib::getOrderDetail($orderTicket->orderId); // dirty fill up data

        //if exist promo, change seat price
        if ($orderTicket->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $orderTicket->discount);

        $content = OrderLib::generateTicket($orderTicket); 
        $fileName = $orderTicket->type == ONEWAY ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName);
    }

    public function save(){
        $this->validate([
            'confirmation' => 'required',
            'reasonDecline' => 'required_if:confirmation,'.DECLINEDORDER,
        ]);
        $this->order->status = CONFIRMEDORDER;
        $this->order->paymentStatus = PAID;
        $this->order->save();
        
        //send mail here
    }

    public function render()
    {
        return view('livewire.backend.moderator.moderator-process-order')
        ->layout('moderator.layouts.app');
    }
}
