<?php

namespace App\Http\Livewire\Backend\Moderator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Livewire\Component;
use App\Lib\OrderLib;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;

class ModeratorProcessOrder extends Component
{
    public $order;
    public $orderId;
    public $orderStatuses;
    public $paymentMethodList;

    public $paymentMethod;
    public $paymentStatus;
    public $transactionCode;
    public $transactionDate;

    public $status;
    public $note;

    public $showModalStatus = false;
    public $showModalPayment = false;

    public $isTransaction = false;

    public $photos = [];

    public function mount($orderId){
        $this->orderId = $orderId;
        $this->order = OrderLib::getOrderDetail($this->orderId);
        $this->orderStatuses = OrderStatus::select('order_statuses.status', 'order_statuses.orderId', 'order_statuses.note', 'order_statuses.changeDate', 'u.name')
                                            ->leftJoin('users as u', 'u.id', '=', 'order_statuses.userId')
                                            ->where('orderId', $this->orderId)->get();
        $this->paymentMethodList = PaymentMethod::get()->where('status', ACTIVE);
        $this->paymentMethod = $this->paymentMethodList->first()->id;
        $this->loadProof();
    }

    public function hydrate() {
        $this->order = OrderLib::getOrderDetail($this->orderId);
        $this->orderStatuses = OrderStatus::select('order_statuses.status', 'order_statuses.orderId', 'order_statuses.note', 'order_statuses.changeDate', 'u.name')
                                            ->leftJoin('users as u', 'u.id', '=', 'order_statuses.userId')
                                            ->where('orderId', $this->orderId)->get();
    }

    public function updatedPaymentStatus(){
        $this->isTransaction = PaymentMethod::find($this->paymentMethod)->isTransaction;
    }

    public function updatedPaymentMethod(){
        $this->isTransaction = PaymentMethod::find($this->paymentMethod)->isTransaction;
    }

    public function viewOrderStatus() {
        $this->status = CONFIRMEDORDER;
        $this->showModalStatus = true;
    }

    public function viewOrderPayment() {
        $this->paymentMethod = $this->paymentMethodList->first()->id;
        $this->paymentStatus = PAID;
        $this->transactionCode = null;
        $this->transactionDate = null;
        $this->showModalPayment = true;
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

    public function downloadBoardingPass($orderTicketId){
        
        $orderTicket = OrderLib::getOrderTicket($orderTicketId);
        //$this->orderDetail = OrderLib::getOrderDetail($orderTicket->orderId); // dirty fill up data

        //if exist promo, change seat price
        if ($orderTicket->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $orderTicket->discount);

        $content = OrderLib::generateBoardingPass($orderTicket); 
        $fileName = $orderTicket->type == ONEWAY ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName);
    }

    public function updatePayment(){
        $this->validate([
            'transactionCode' => [
                'required_if:isTransaction,1',
                $this->isTransaction == 1 ? 'min:4' : '',
                $this->isTransaction == 1 ? 'max:15' : '',
            ],
            'transactionDate' => [
                'required_without_all:isTransaction,paymentStatus',
                Rule::requiredIf(function () {
                    return $this->isTransaction == 1 || $this->paymentStatus == PAID;
                }),
            ],
            'paymentMethod' => 'required',
            'paymentStatus' => 'required',
        ]);

        $order = Order::findOrFail($this->orderId);
        $order->paymentMethod = $this->paymentMethod;
        $order->paymentStatus = $this->paymentStatus;
        $order->transactionCode = $this->transactionCode;
        $order->transactionDate = $this->transactionDate;

        $order->save();

        // Update order payment info
        $this->order = $order;

        //reset field
        $this->paymentMethod = null;
        $this->paymentStatus = null;
        $this->transactionCode = null;
        $this->transactionDate = null;

        $this->showModalPayment = false;
    }

    public function updateOrderStatus(){
        $this->validate([
            'status' => 'required',
            'note' => 'required',
        ]);

        $orderStatus = OrderStatus::create([
            'orderId' => $this->orderId,
            'status' => $this->status,
            'note' => $this->note,
            'changeDate' => date('Y-m-d H:i:s'),
            'userId' => Auth::id(),
        ]);

        // Add the new order status to the existing list
        $orderStatus->name = Auth::user()->name;
        $this->orderStatuses->push($orderStatus);

        $order = Order::findOrFail($this->orderId);
        $order->status = $this->status;
        $order->save();

        //reset field
        $this->status = null;
        $this->note = null;

        $this->showModalStatus = false;
    }

    public function render() {
        return view('livewire.backend.moderator.moderator-process-order')
        ->layout('moderator.layouts.app');
    }
}
