<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;
use Carbon\Carbon;

use Omise\Omise;
use App\Lib\OrderLib;

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Log;

class Promptpay extends Component
{
    public $order;
    public $source;
    public $publicKey;
    public $secretKey;
    public $charge = [];
    public $webhookEventData = null;

    protected $listeners = ['promptpayCreateCharge' => 'promptpayCreateCharge',
						    'promptpayRefresh' => 'promptpayRefresh',
                            WebhookEvent::class => 'handleWebhookEvent',];

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->publicKey = OMISE_PUBLIC_KEY;
        $this->secretKey = OMISE_SECRET_KEY;

        $this->amount = $this->order->finalPrice * 100;
    }

    public function promptpayRefresh() {
        $this->reset(['charge']);
    }

    public function handleWebhookEvent($eventData) {
        Log::debug('Received to Promptpay');
        $this->webhookEventData = $eventData;
    }

    public function promptpayCreateCharge() {
        $charge_array = array(
            'amount'      => $this->amount,
            'currency'    => 'thb',
            'return_uri'  => url()->full(),
            'source'      => $this->source,
            'description' => $this->order->code,
            'expires_at'  => Carbon::now()->addMinutes(15)->toIso8601String(),
        );
        $charge = \OmiseCharge::create($charge_array);
        $this->charge = $charge['source'];
    }

    public function payByPromptpay(){
        // Create a charge
        try {

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
                $order->paymentMethod = PROMPTPAY;
                $order->paymentStatus = PAID;
                $order->transactionCode = $charge['transaction'];
                $order->transactionDate = date('Y-m-d H:i:s', strtotime($charge['created_at']));

                $order->save();

                //dispatch event to Payment component
                $this->emitUp('updatePayment', UPPLOADTRANSFER);
            }

        } catch (\Exception $e) {
            // Handle payment error
            // Display an error message to the user
        }
    }

    public function render() {
        return view('livewire.frontend.homepage.payment.promptpay');
    }
}
