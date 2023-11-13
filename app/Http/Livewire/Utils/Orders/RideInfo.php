<?php

namespace App\Http\Livewire\Utils\Orders;

use Livewire\Component;
use App\Lib\RideLib;

class RideInfo extends Component
{
    public $infos;

    public function mount($rideId, $seatClassId){
        $this->infos = RideLib::getBookingStatus($rideId,  $seatClassId);
    }
    public function render()
    {
        return view('livewire.utils.orders.ride-info');
    }
}
