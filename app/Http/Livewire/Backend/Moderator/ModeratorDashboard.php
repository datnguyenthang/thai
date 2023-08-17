<?php

namespace App\Http\Livewire\Backend\Moderator;

use Livewire\Component;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\RidesExport;
use App\Lib\DashboardLib;
use App\Lib\OrderLib;

use App\Models\Location;
use App\Models\Order;

use Carbon\Carbon;

class ModeratorDashboard extends Component
{
    public $page = 'moderatorDashboard';
    public $listRides = [];
    public $revenue;
    public $pendingComfirmation;
    public $totalAmountThisDay;
    public $totalOrderThisDay;

    public $fromDate;
    public $toDate;

    public $fromLocation;
    public $toLocation;

    public $fromLocationList;
    public $toLocationList;

    public $rideId;
    public $showModal;
    public $listPassengers;

    public function mount(){
        $this->fromDate = $this->toDate = now()->toDateString();
        $this->fromLocationList = $this->toLocationList = Location::get()->where('status', ACTIVE);

        $this->listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate);
        $this->revenue = DashboardLib::revenueInDay();
        //$this->totalAmountThisDay = $this->revenue->priceConfirmed + $this->revenue->priceNotConfirmed;
        $this->pendingComfirmation = Order::where('status', UPPLOADTRANSFER)->count();
        $this->totalOrderThisDay = Order::where('bookingDate', now()->toDateString())->count();
    }

    public function updatedFromDate(){
        $this->listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation);
    }

    public function updatedToDate(){
        $this->listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation);
    }

    public function updatedFromLocation(){
        $this->listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation);
    }

    public function updatedToLocation(){
        $this->listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation);
    }

    public function showDashboard(){
        $this->page = $page;
    }

    public function hydrate(){
        $this->listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation);
    }

    public function exportRides($rideId = 0){
        $passengers = DashboardLib::exportRides($rideId, $this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation);
        
        $export = new RidesExport($passengers);

        return Excel::download($export, 'passengers.xlsx');
    }

    public function displayRide($rideId = 0){
        $this->rideId = $rideId;
        $this->listPassengers = DashboardLib::detailRides($rideId);
        
        $this->showModal = true;
    }

    public function boardingPass($rideId = 0, $orderTicketId = 0){
        $this->listPassengers = DashboardLib::detailRides($rideId);

        $orderTicket = OrderLib::getOrderTicket($orderTicketId);
        //$this->orderDetail = OrderLib::getOrderDetail($orderTicket->orderId); // dirty fill up data

        //if exist promo, change seat price
        if ($orderTicket->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $orderTicket->discount);

        $content = OrderLib::generateBoardingPass($orderTicket); 
        $fileName = $orderTicket->type == ONEWAY ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName);
    }

    public function render() {
        return view('livewire.backend.moderator.moderator-dashboard')
              ->layout('moderator.layouts.app');
    }
}
