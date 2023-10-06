<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;
use Carbon\Carbon;

use Omise\Omise;
use App\Lib\OrderLib;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderPayment;
use App\Models\PaymentMethod;
use App\Models\OmiseWebhookEvent;

class Omisepay extends Component
{
    public $order;
    public $paymentMethod;
    public $token;
    public $source;
    public $publicKey;
    public $secretKey;
    public $error = false;
    public $errorMessage;

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
                'amount'      => $this->amount, // Amount in cents
                'currency'    => 'thb',
                'return_uri'  => 'https://www.seudamgo.com/payment_complete/'.$this->order->code,
                'description' => $this->order->code,
                'card'        => $this->token
            ]);

            // Delete existing records that match the specified conditions
            OmiseWebhookEvent::where('eventType', 'CARD')
                ->where('eventStatus', CHARGE)
                ->where('orderCode', $this->order->code)
                ->delete();

            // Save the event data into the database
            OmiseWebhookEvent::create([
                'eventType' => CARD,
                'eventChargeid' => $charge['id'],
                'eventStatus' => CHARGE,
                'orderCode' => $this->order->code,
            ]);
            // redirect to authorize_uri
            header('Location: ' . $charge['authorize_uri']);
            exit();
        } catch (\Exception $e) {
            $this->error = true;
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render(){
        return view('livewire.frontend.homepage.payment.omisepay');
    }
}
