<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;
use Omise\Omise;
use App\Lib\OrderLib;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderPayment;
use App\Models\PaymentMethod;

class Omisepay extends Component
{
    public $order;
    public $token;
    public $source;
    public $publicKey;
    public $secretKey;

    protected $listeners = ['payByCard' => 'payByCard'];

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->publicKey = OMISE_PUBLIC_KEY;
        $this->secretKey = OMISE_SECRET_KEY;

        $this->paymentMethod = PaymentMethod::where('name', '=', CARD)->first();

        $this->amount = $this->order->finalPrice * 100;
    }

    public function payByCard(){
        // Create a charge
        try {
            $charge = \OmiseCharge::create([
                'amount' => $this->amount, // Amount in cents
                'currency' => 'thb', // Change to your desired currency
                'card' => $this->token,
                'description' => $this->order->code,
                'expires_at'  => Carbon::now()->addMinutes(15)->toIso8601String(),
            ]);

            if ($charge['status'] == SUCCESSFUL){
                //Update order status
                OrderStatus::create([
                    'orderId' => intVal($this->order->id),
                    'status' => PAIDORDER,
                    //'note' => $this->note,
                    'changeDate' => date('Y-m-d H:i:s'),
                    //'userId' => Auth::id(),
                ]);

                //Update order payment
                OrderPayment::create([
                    'orderId' => intVal($this->order->id),
                    'paymentMethod' => $this->paymentMethod->id,
                    'paymentStatus' => PAID,
                    'transactionCode' => $charge['transaction'],
                    'transactionDate' => date('Y-m-d H:i:s', strtotime($charge['created_at'])),
                    'changeDate' => date('Y-m-d H:i:s'),
                ]);

                //dispatch event to Payment component
                $this->emitUp('updatePayment', ALREADYPAID);
            }

        } catch (\Exception $e) {

        }
    }

    public function render(){
        return view('livewire.frontend.homepage.payment.omisepay');
    }
}
