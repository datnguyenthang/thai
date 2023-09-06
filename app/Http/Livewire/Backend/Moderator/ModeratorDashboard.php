<?php

namespace App\Http\Livewire\Backend\Moderator;

use Livewire\Component;
use Livewire\WithPagination;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RidesExport;

use App\Lib\DashboardLib;
use App\Lib\OrderLib;
use App\Lib\TicketLib;

use App\Models\Location;
use App\Models\Order;

use Carbon\Carbon;

class ModeratorDashboard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';

    //public $listRides = [];
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

    public $isOrder = true;
    public $role;

    public $rideId;
    public $showModal;
    public $listPassengers;

    public function mount(){
        $this->role = auth()->user()->role;

        $this->fromDate = $this->toDate = now()->toDateString();
        $this->fromLocationList = $this->toLocationList = Location::get()->where('status', ACTIVE);

        //$this->listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate);
        $this->revenue = DashboardLib::revenueInDay();
        //$this->totalAmountThisDay = $this->revenue->priceConfirmed + $this->revenue->priceNotConfirmed;
        $this->pendingComfirmation = Order::where('status', UPPLOADTRANSFER)->count();
        $this->totalOrderThisDay = Order::where('bookingDate', now()->toDateString())->count();
    }
    public function updatingSearch(){
        $this->resetPage();
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

        return TicketLib::downloadBoardingPass($orderTicketId);
    }

    public function render() {
        $perPage = 10;

        $listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation, $this->isOrder, $perPage);

        return view('livewire.backend.moderator.moderator-dashboard', ['listRides' => $listRides])
              ->layout('moderator.layouts.app');
    }
}
