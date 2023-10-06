<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Lib\OrderLib;
use Omise\Omise;
use App\Models\OmiseWebhookEvent;
use App\Models\PaymentMethod;

class PaymentComplete extends Component {

    CONST SUCCESS = 0;
    CONST FAIL = 1;

    public $code;
    public $order;
    public $paymentMethod;
    public $error = false;
    public $errorMessage;

    public function mount(Request $request){
        $this->code = $request->input('code');
        $this->order = OrderLib::getOrderDetailByCode($this->code);

        $this->paymentMethod = PaymentMethod::where('name', '=', CARD)->first();

        $event = OmiseWebhookEvent::where('eventType', CARD)->where('eventData', $this->code)->where('eventStatus', CHARGE);       
        $charge = \OmiseCharge::retrieve($event->eventChargeid);

        if ( $charge['authorized'] == false || $charge['paid'] == false) {
            $this->error = true;
            $this->errorMessage = 'Payment Error!!!';
        } else {
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
        }

    }

    public function render() {
        //return view('livewire.frontend.homepage.payment-complete');
        if ($this->error) return view('livewire.frontend.homepage.payment');
        if (!$this->error) return view('livewire.frontend.homepage.success-booking');
    }
}
