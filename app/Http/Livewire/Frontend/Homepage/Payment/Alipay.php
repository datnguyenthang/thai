<?php

namespace App\Http\Livewire\Frontend\Homepage\Payment;

use Livewire\Component;

use OmiseCharge;
use OmiseToken;
use OmiseCustomer;
use OmiseSource;

use App\Lib\OrderLib;

class Alipay extends Component
{
    public $order;
    public $amount;
    public $alipayRedirectUrl;

    public function mount($orderId) {
        $this->order = OrderLib::getOrderDetail($orderId);
    }

    public function createAlipaySource()
    {
        $customer = OmiseCustomer::create([
            'email' => $this->order->email,
            'description' => $this->order->fullname,
        ]);

        $source = OmiseSource::create([
            'type' => 'alipay',
            'amount' => $this->order->finalPrice,
            'currency' => 'thb',
            'redirect' => ['return_uri' => route('payment', ['code' => $this->order->code])],
            'metadata' => ['customer_id' => $this->order->id],
        ]);

        $this->alipayRedirectUrl = $source['authorize_uri'];
    }

    public function processPayment(){
        $charge = OmiseCharge::create([
            'amount' => $this->order->finalPrice,
            'currency' => 'thb',
            'source' => $_POST['omiseToken'],
        ]);

        if ($charge['status'] === 'successful') {
            // Adding to database and send mail
            $this->emitUp('updatePayment', PAID);
        } else {
            // Payment failed
            // You can perform additional actions here
            $this->emit('paymentError', $charge['failure_message']);
        }
    }

    public function render()
    {
        return view('livewire.frontend.homepage.payment.alipay');
    }
}
