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
    protected $chargeCreate;
	public $imageQR;
	public $chargeId;
	public $paymentStatus = PENDING;
    public $webhookEventData = null;
    public $chargeTransaction;

    protected $listeners = ['promptpayCreateCharge' => 'promptpayCreateCharge',
						    'promptpayRefresh' => 'promptpayRefresh',
                            'checkPaymentStatus' => 'checkPaymentStatus',
                            'paidByPromptpay' => 'paidByPromptpay'];

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->publicKey = OMISE_PUBLIC_KEY;
        $this->secretKey = OMISE_SECRET_KEY;

        $this->amount = $this->order->finalPrice * 100;
    }

    public function promptpayRefresh() {
        $this->reset(['chargeCreate']);
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
		$this->chargeCreate = \OmiseCharge::create($charge_array);
		$this->chargeId = $this->chargeCreate['id'];
		$this->imageQR = $this->chargeCreate['source']['scannable_code']['image']['download_uri'];
    }
	
	public function checkPaymentStatus(){
        if ($this->chargeId) {
            //check payment status in Omise server
            $omisePayment = \OmiseCharge::retrieve($this->chargeId);

            //verify transaction again in webhook
            if (!empty($omisePayment) && $omisePayment['status'] == SUCCESSFUL) {
                $webhookPayment = OmiseWebhookEvent::where('eventChargeid', $this->chargeId)
                                                    ->where('eventStatus', SUCCESSFUL)
                                                    ->first();
                if ($webhookPayment) {
                    $this->paymentStatus = SUCCESSFUL;         
                    //$this->paidByPromptpay();           
                    //$this->chargeTransaction = $omisePayment;
                    $this->emit('paymentStatusUpdated', SUCCESSFUL);
                }
            }
        }
    }

    public function paidByPromptpay(){
        try {
            if ($this->chargeTransaction['status'] == SUCCESSFUL){
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
