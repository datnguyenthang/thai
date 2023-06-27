<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Carbon\Carbon;

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
    public $departureDate;
    public $returnDate;
    public $adults = 1;
    public $children = 0;

    public function mount(){
        $locations = Location::get();
        $this->fromLocationList = $this->toLocationList = $locations;

        $this->tripType = ROUNDTRIP;
        $this->roundtrip = ROUNDTRIP;

        $this->fromLocation = $locations->random()->id;
        $this->toLocation = $locations->filter(function ($location){
            return $location->id !==  $this->fromLocation;
        })->random()->id;

        $this->departureDate = now()->addDay()->toDateString();
        $this->returnDate = now()->addDays(2)->toDateString();
    }

    public function addOne($model){
        if ($model == 'adults') $this->adults += 1;
        if ($model == 'children') $this->children += 1;
    }

    public function minusOne($model){
        if ($model == 'adults' && $this->adults >= 2) $this->adults -= 1;
        if ($model == 'children' && $this->children >= 1) $this->children -= 1;
    }

    public function chooseTripType($typeTrip = 0){
        $this->roundtrip = $typeTrip;
    }

    public function chooseDepartDate($departureDate){
        $this->returnDate = Carbon::create($departureDate)->addDay()->toDateString();
    }

    public function chooseFromLocation($fromLocation){
        $this->toLocationList = Location::get()->except($fromLocation);
    }

    public function render()
    {
        return view('livewire.frontend.homepage.booking');
    }
}
