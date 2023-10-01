<?php

namespace App\Http\Livewire\Component\Report;

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

class Dashboard extends Component
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

    public $hasOrdered = true;
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

    public function render(){
        $perPage = 10;

        $listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation, $this->hasOrdered, $perPage);

        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.dashboard.manager', compact('listRides'))->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.dashboard.manager', compact('listRides'))->layout('manager.layouts.app');
                break;
            case 'moderator':
                return view('livewire.component.report.dashboard.moderator', compact('listRides'))->layout('moderator.layouts.app');
                break; 
            case 'viewer':
                return view('livewire.component.report.dashboard.manager', compact('listRides'))->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
