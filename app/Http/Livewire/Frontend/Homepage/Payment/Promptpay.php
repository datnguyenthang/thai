<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;
use Carbon\Carbon;

use Omise\Omise;
use App\Lib\OrderLib;

use App\Models\Order;
use App\Models\OrderStatus;

class Promptpay extends Component
{
    public $order;
    public $source;
    public $publicKey;
    public $secretKey;
    public $charge = [];
    public $webhookEventData = null;

    protected $listeners = ['promptpay' => 'promptpay', 'refresh' => 'refresh', 'webhookEventReceived' => 'handleWebhookEvent'];

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->publicKey = OMISE_PUBLIC_KEY;
        $this->secretKey = OMISE_SECRET_KEY;

        $this->amount = $this->order->finalPrice * 100;
    }

    public function refresh() {
        $this->reset(['charge']);
    }

    public function handleWebhookEvent($eventData){ dd($eventData);
        $this->webhookEventData = $eventData;
    }

    public function promptpay() {
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

    public function render() {
        return view('livewire.frontend.homepage.payment.promptpay');
    }
}
