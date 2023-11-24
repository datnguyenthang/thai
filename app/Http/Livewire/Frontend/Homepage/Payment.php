<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;
use OmiseCharge;
use OmiseToken;
use OmiseCustomer;

use Livewire\WithFileUploads;
use App\Lib\OrderLib;
use App\Lib\EmailLib;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;

class Payment extends Component
{
    use WithFileUploads;
    protected $orderLib;

    CONST GOTOPAY = 0;
    CONST PAID = 1;

    public $step = 0;

    public $tab = 'manual';

    public $code;
    public $order;

    public $errorMessage;

    protected $listeners = [
        'updatePayment' => 'updatePayment'
    ];

    public function mount() {

        $this->code = $this->folderName = Route::current()->parameter('code');
        $this->order = OrderLib::getOrderDetailByCode($this->code);

        //redirect to homepage if there are no order match code found or has been paid
        if (!$this->order || $this->order->paymentStatus == PAID) {
            redirect('/');
        }

        $this->paymentMethodList = PaymentMethod::get()->whereNotIn('name', [BANKTRANSFER, CASH]);

        $this->errorMessage = session('message');
        if ($this->errorMessage) $this->tab = 'omise';
    }

    public function updatePayment($step){
        $this->step = $step;

        //SEND MAIL
        EmailLib::sendMailConfirmOrderEticket($this->code);
    }

    public function hydrate(){
        $this->order = OrderLib::getOrderDetailByCode($this->code);
    }

    public function render() {
        //$this->testPDF(); // Test ticket PDF file
        //$this->testEticket(); // Test e-ticket PDF file

        if ($this->step === self::GOTOPAY) return view('livewire.frontend.homepage.payment');
        if ($this->step === self::PAID) return view('livewire.frontend.homepage.success-booking');
    }
}
