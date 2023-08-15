<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;
use Carbon\Carbon;

use Omise\Omise;
use App\Lib\OrderLib;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OmiseWebhookEvent;

class Promptpay extends Component
{
    public $order;
    public $source;
    public $publicKey;
    public $secretKey;
    public $charge = [];
    public $webhookEventData = null;

    protected $listeners = ['promptpayCreateCharge' => 'promptpayCreateCharge',
						    'promptpayRefresh' => 'promptpayRefresh'];

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->publicKey = OMISE_PUBLIC_KEY;
        $this->secretKey = OMISE_SECRET_KEY;

        $this->amount = $this->order->finalPrice * 100;
    }

    public function promptpayRefresh() {
        $this->reset(['charge']);
    }

    public function pollForPaymentUpdates(){
        while (true) {
            sleep(2); // Poll every 10 seconds
            
            $latestEventData = OmiseWebhookEvent::where('event_type', 'promptpay')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($latestEventData) {
                $this->latestPaymentEventData = $latestEventData;
            }
            
            // Livewire will handle the re-rendering of the component
            $this->emitSelf('paymentUpdateReceived', $this->latestPaymentEventData);
        }
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
        $this->pollForPaymentUpdates();
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
