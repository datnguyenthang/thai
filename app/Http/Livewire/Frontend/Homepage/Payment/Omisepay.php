<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;
use Omise\Omise;
use App\Lib\OrderLib;

use App\Models\Order;
use App\Models\OrderStatus;

class Omisepay extends Component
{
    public $order;
    public $token;
    public $source;
    public $publicKey;
    public $secretKey;

    protected $listeners = ['pay' => 'pay'];

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->publicKey = OMISE_PUBLIC_KEY;
        $this->secretKey = OMISE_SECRET_KEY;

        $this->amount = $this->order->finalPrice * 100;
    }

    public function pay(){

        // Create a charge
        //try {
            $charge = \OmiseCharge::create([
                'amount' => $this->amount * 100, // Amount in cents
                'currency' => 'THB', // Change to your desired currency
                'card' => $this->token,
            ]);
dd($charge);
            if ($charge['status'] == 'successful'){
                //Update order status
                OrderStatus::create([
                    'orderId' => intVal($this->order->id),
                    'status' => PAIDORDER,
                    //'note' => $this->note,
                    'changeDate' => date('Y-m-d H:i:s'),
                    //'userId' => Auth::id(),
                ]);

                $order = Order::findOrFail($this->order->id);
                $order->paymentMethod = CARD;
                $order->paymentStatus = PAID;
                $order->transactionCode = $charge['transaction'];
                $order->transactionDate = date('Y-m-d H:i:s', strtotime($charge['created_at']));

                $order->save();
                
                //dispatch event to Payment component
                
                $this->emitUp('updatePayment', UPPLOADTRANSFER);
            }

        //} catch (\Exception $e) {
            // Handle payment error
            // Display an error message to the user
        //}
    }

    public function render(){
        return view('livewire.frontend.homepage.payment.omisepay');
    }
}
