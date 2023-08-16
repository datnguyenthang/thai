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
    protected $charge;
	public $imageQR;
	public $chargeId;
	
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

    public function promptpayCreateCharge() {
        $charge_array = array(
            'amount'      => $this->amount,
            'currency'    => 'thb',
            'return_uri'  => url()->full(),
            'source'      => $this->source,
            'description' => $this->order->code,
            'expires_at'  => Carbon::now()->addMinutes(15)->toIso8601String(),
        );
		$this->charge = \OmiseCharge::create($charge_array);
		$this->chargeId = $this->charge['id'];
		$this->imageQR = $this->charge['source']['scannable_code']['image']['download_uri'];
    }
	
	public function getPromptpayData(){
		//check payment status in Omise server
		$payment = \OmiseCharge::retrieve($this->chargeId);
		
		//if successful checking in database to be sure
		if (!empty($payment) && $payment['status'] == SUCCESSFUL) {
			$payment_webhook = OmiseWebhookEvent::where('eventChargeid', $this->chargeId)
                                                ->where('eventStatus', SUCCESSFUL)
                                                ->first();
            if ($payment_webhook) echo 'Payment Successfull';
		}
		
    }

    public function payByPromptpay(){
        // Create a charge
        try {

            if ($charge['status'] == SUCCESSFUL){
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
