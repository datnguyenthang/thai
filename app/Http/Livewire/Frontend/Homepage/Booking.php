<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;

class Booking extends Component
{
    public $roundtrip;
    public $oneway;

    public $tripType;
    public $fromLocation;
    public $toLocation;

    public $fromLocationList;
    public $toLocationList;
    public $departDate;
    public $returnDate;
    public $adults;
    public $children;

    public function mount(){
        $this->fromLocationList = $this->toLocationList = Location::get();
        $this->tripType = ROUNDTRIP;
        $this->roundtrip = ROUNDTRIP;
        $this->returnDate = date('Y-m-d');
    }

    public function chooseTripType($typeTrip = 0){
        $this->roundtrip = $typeTrip;
    }

    public function chooseDepartDate($departDate){
        $this->returnDate = $departDate;
    }

    public function chooseFromLocation($fromLocation){
        $this->toLocationList = Location::get()->except($fromLocation);
    }

    public function render()
    {
        return view('livewire.frontend.homepage.booking');
    }
}
