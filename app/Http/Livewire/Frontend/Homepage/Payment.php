<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;
use OmiseCharge;
use OmiseToken;
use OmiseCustomer;

use Livewire\WithFileUploads;
use App\Lib\OrderLib;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

use App\Mail\SendTicket;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\OrderStatus;

class Payment extends Component
{
    use WithFileUploads;
    protected $orderLib;

    const GOTOPAY = 0;
    const PAID = 1;

    public $step = 0;

    public $code;
    public $order;

    protected $listeners = [
        'updatePayment' => 'updatePayment'
    ];

    public function mount() {

        $this->code = $this->folderName = Route::current()->parameter('code');
        $this->order = OrderLib::getOrderDetailbByCode($this->code);

        //redirect to homepage if there are no order match code found
        if (!$this->order) redirect('/');
    }

    public function updatePayment($step){
        $this->step = $step;
    }

    public function hydrate(){
        $this->order = OrderLib::getOrderDetailbByCode($this->code);
    }

    public function render() {
        //$this->testPDF(); // Test ticket PDF file
        //$this->testEticket(); // Test e-ticket PDF file

        if ($this->step === self::GOTOPAY) return view('livewire.frontend.homepage.payment');
        if ($this->step === self::PAID) return view('livewire.frontend.homepage.success-booking');
    }
}
