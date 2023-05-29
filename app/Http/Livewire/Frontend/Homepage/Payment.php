<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Ticket;

class Payment extends Component
{
    public $step = 0;

    public $code;

    public $cardNumber;
    public $cardHolder;
    public $expirationDate;
    public $cvv;

    public function mount($code){
        $this->code = $code;
    }

    public function payment(){
        $this->step = 1;
        //send email after finish
    }

    public function render()
    {
        if ($this->step === 0) return view('livewire.frontend.homepage.payment');
        if ($this->step === 1) return view('livewire.frontend.homepage.success-booking');
    }
}
