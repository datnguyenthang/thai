<?php

namespace App\Http\Livewire\Backend\Moderator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Omise\Omise;
use Livewire\Component;
use App\Lib\OrderLib;
use App\Lib\EmailLib;
use App\Lib\TicketLib;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderPayment;
use App\Models\PaymentMethod;

class ModeratorProcessOrder extends Component
{
    public $order;
    public $orderId;
    public $orderStatuses;
    public $orderPayments;
    public $paymentMethodList;

    public $paymentMethod;
    public $paymentStatus;
    public $transactionCode;
    public $transactionDate;

    public $status;
    public $note;
    public $isSendmail = true;

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

        $this->orderPayments = OrderPayment::select('order_payments.paymentStatus', 'order_payments.orderId', 'order_payments.transactionCode',
                                                    'order_payments.transactionDate', 'order_payments.paymentMethod', 'order_payments.note',
                                                    'order_payments.changeDate', 'u.name')
                                            ->leftJoin('users as u', 'u.id', '=', 'order_payments.userId')
                                            ->where('orderId', $this->orderId)->get();
        $this->paymentMethodList = PaymentMethod::get();
        $this->paymentMethod = $this->paymentMethodList->first()->id;
        $this->loadProof();
    }

    public function hydrate() {
        $this->order = OrderLib::getOrderDetail($this->orderId);
        $this->orderStatuses = OrderStatus::select('order_statuses.status', 'order_statuses.orderId', 'order_statuses.note', 'order_statuses.changeDate', 'u.name')
                                            ->leftJoin('users as u', 'u.id', '=', 'order_statuses.userId')
                                            ->where('orderId', $this->orderId)->get();
        
        $this->orderPayments = OrderPayment::select('order_payments.paymentStatus', 'order_payments.orderId', 'order_payments.transactionCode',
                                            'order_payments.transactionDate', 'order_payments.paymentMethod', 'order_payments.note',
                                            'order_payments.changeDate', 'u.name')
                                    ->leftJoin('users as u', 'u.id', '=', 'order_payments.userId')
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

        $this->paymentMethod = $this->order->paymentMethod ?? $this->paymentMethodList->first()->id;
        $this->paymentStatus = $this->order->paymentStatus ?? PAID;
        $this->transactionCode = $this->order->transactionCode ?? null;
        $this->transactionDate = $this->order->transactionDate ?? null;

        $this->isTransaction = PaymentMethod::find($this->paymentMethod)->isTransaction;

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
        return TicketLib::downloadBoardingPass($orderTicketId);
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

        //SAVE first payment of this order
        $orderPayment = OrderPayment::create([
            'orderId' => intVal($this->orderId),
            'paymentMethod' => $this->paymentMethod,
            'paymentStatus' => $this->paymentStatus,
            'transactionCode' => $this->isTransaction ? $this->transactionCode : '',
            'transactionDate' => $this->transactionDate,
            //'note' => $this->paymentNote,
            'changeDate' => date('Y-m-d H:i:s'),
            'userId' => Auth::id(),
        ]);

        // Add the new order status to the existing list
        $orderPayment->name = Auth::user()->name;
        $this->orderPayments->push($orderPayment);

        // Update order payment info
        $order = Order::findOrFail($this->orderId);
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

        //send email
        if ($this->isSendmail) {
            switch ($this->status) {
                case NEWORDER:
                    EmailLib::sendMailConfirmOrderEticket($order->code);
                    break;
                case RESERVATION:
                    EmailLib::sendMailConfirmReservation($order->code);
                    break;
                case CONFIRMEDORDER:
                    EmailLib::sendMailConfirmCompleteOrder($order->code);
                break;
                case PAIDORDER:
                    EmailLib::sendMailConfirmPaidOrder($order->code);
                    break;
                case CANCELDORDER:
                    EmailLib::sendMailCancelOrder($order->code);
                    break;

                default:
                echo "Your favorite color is neither red, blue, nor green!";
            }
        }
        //reset field
        $this->status = null;
        $this->note = null;

        $this->showModalStatus = false;
    }

    public function refundOrder($orderId, $transactionCode) {
        $order = Order::findOrFail($orderId);

        $charge = \OmiseCharge::retrieve(OMISE_SECRET_KEY);
        try {
            $omise = new \Omise([
                'publicKey' => OMISE_PUBLIC_KEY,
                'secretKey' => OMISE_SECRET_KEY,
            ]);

            $charge = OmiseCharge::retrieve($transactionCode);
            $refund = $charge->refunds()->create([
                'amount'   => $order->finalPrice,  // Amount to refund in the smallest currency unit (e.g., cents)
                'metadata' => [
                    'order_id' => $order->code,  // Optional metadata, you can customize as needed
                    'color'    => 'pink'
                ]
            ]);
        
            // Refund successful, handle your logic here
            echo 'Refund successful! Refund ID: ' . $refund['id'];
        } catch (Exception $e) {
            // Handle errors
            echo 'Error: ' . $e->getMessage();
        }        
    }

    public function render() {
        return view('livewire.backend.moderator.moderator-process-order')
        ->layout('moderator.layouts.app');
    }
}