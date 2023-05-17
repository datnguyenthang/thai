<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Ticket;

class Payment extends Component
{
    public $code;

    public $cardNumber;
    public $cardHolder;
    public $expirationDate;
    public $cvv;

    public function mount($code){
        $this->code = $code;
    }

    public function payment(){
        
        //send email after finish
    }

    public function render()
    {
        return view('livewire.frontend.homepage.payment');
    }
}
