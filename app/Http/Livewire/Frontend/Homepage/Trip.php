<?php

namespace App\Http\Livewire\Frontend\Homepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;

class Trip extends Component
{
    public $countTicketSelected;
    public $enableSubmit;
    public $text_ticket_select;
    
    public $departRides;
    public $returnRides;

    public $tripType;
    public $fromLocation;
    public $toLocation;
    public $departureDate;
    public $returnDate;
    public $adults;
    public $children;

    public $fromLocationName;
    public $toLocationName;

    public $order_depart_rideId;
    public $order_depart_seatClassId;

    public $order_return_rideId;
    public $order_return_seatClassId;

    public function mount(Request $request){
        $this->tripType = $request->input('tripType');
        $this->fromLocation = $request->input('fromLocation');
        $this->toLocation = $request->input('toLocation');
        $this->departureDate = $request->input('departureDate');
        $this->returnDate = $request->input('returnDate');
        $this->adults = $request->input('adults');
        $this->children = $request->input('children');

        $this->fromLocationName = Location::find($this->fromLocation)->name;
        $this->toLocationName = Location::find($this->toLocation)->name;

        $this->countTicketSelected = 0;
        $this->enableSubmit = false;
        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);

        $this->getDepartTrip();
        $this->getReturnTrip(); 
        
    }

    public function updateState(){
        $this->getDepartTrip($this->order_depart_rideId, $this->order_depart_seatClassId);
        $this->getReturnTrip($this->order_return_rideId, $this->order_return_seatClassId);

        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);
        $this->checkEnableSubmit();
    }

    public function chooseTicketDepart($rideId, $seatClassId){
        $this->countTicketSelected += 1;
        $this->order_depart_rideId = $rideId;
        $this->order_depart_seatClassId = $seatClassId;

        $this->updateState();
    }

    public function chooseTicketReturn($rideId, $seatClassId){
        $this->countTicketSelected += 1;
        $this->order_return_rideId = $rideId;
        $this->order_return_seatClassId = $seatClassId;

        $this->updateState();
    }

    public function clearDepartTicket(){
        $this->countTicketSelected -= 1;
        $this->order_depart_rideId = null;
        $this->order_depart_seatClassId = null;

        $this->updateState();
    }

    public function clearReturnTicket(){
        $this->countTicketSelected -= 1;
        $this->order_return_rideId = null;
        $this->order_return_seatClassId = null;

        $this->updateState();
    }

    public function checkEnableSubmit(){
        if ($this->tripType == ONEWAY && $this->countTicketSelected == ONEWAY) {
            return $this->enableSubmit = true;
        }
        if ($this->tripType == ROUNDTRIP && $this->countTicketSelected == ROUNDTRIP) {
            return $this->enableSubmit = true;
        }
        return $this->enableSubmit = false;
    }

    public function getDepartTrip($rideId = 0, $seatClassId = 0)
    {
        $this->departRides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 
                                            'tl.name as toLocation', 'rides.departTime', 'rides.returnTime',
                                            'rides.departDate', 'rides.status',
                                            DB::raw('TIME_FORMAT(TIMEDIFF(rides.returnTime, rides.departTime), "%H:%i") AS distanceTime'),
                                            'sc.id as seatClassId', 'sc.name as seatClass', 'sc.capacity', 'sc.price', 'sc.status')
                                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                                    ->leftJoin('seat_classes as sc', 'sc.rideId', '=', 'rides.id')
                                    ->where(function ($query) use ($rideId, $seatClassId){
                                        $query->where('rides.fromLocation', $this->fromLocation);
                                        $query->where('rides.toLocation', $this->toLocation);
                                        $query->where('rides.departDate', $this->departureDate);
                                        $query->where('rides.status', 0);
                                        $query->where('sc.status', 0);
                                        if ($rideId)
                                            $query->where('rides.id', $rideId);
                                        if ($seatClassId)
                                            $query->where('sc.id', $seatClassId); 
                                    })
                                    ->get();
    }

    public function getReturnTrip($rideId = 0, $seatClassId = 0)
    {
        $this->returnRides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 
                                            'tl.name as toLocation', 'rides.departTime', 'rides.returnTime',
                                            'rides.departDate', 'rides.status',
                                            DB::raw('TIME_FORMAT(TIMEDIFF(rides.returnTime, rides.departTime), "%H:%i") AS distanceTime'),
                                            'sc.id as seatClassId', 'sc.name as seatClass', 'sc.capacity', 'sc.price', 'sc.status')
                                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                                    ->leftJoin('seat_classes as sc', 'sc.rideId', '=', 'rides.id')
                                    ->where(function ($query) use ($rideId, $seatClassId) {
                                        $query->where('rides.fromLocation', $this->toLocation);
                                        $query->where('rides.toLocation', $this->fromLocation);
                                        $query->where('rides.departDate', $this->departureDate);
                                        $query->where('rides.status', 0);
                                        $query->where('sc.status', 0);
                                        if ($rideId)
                                            $query->where('rides.id', $rideId);
                                        if ($seatClassId)
                                            $query->where('sc.id', $seatClassId); 
                                    })
                                    ->get();
    }

    public function render()
    {
        return view('livewire.frontend.homepage.trip');
    }
}
