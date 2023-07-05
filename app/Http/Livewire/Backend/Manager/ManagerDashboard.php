<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RidesExport;
use App\Lib\DashboardLib;

use App\Models\Location;
use App\Models\Order;

use Carbon\Carbon;

class ManagerDashboard extends Component
{
    public $page = 'managerDashboard';

    public $listRides;
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

    public function render()
    {
        return view('livewire.backend.manager.manager-dashboard')
              ->layout('manager.layouts.app');
    }
    
}
