<?php

namespace App\Http\Livewire\Component\Report;

use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RidesExport;
use App\Exports\OrdersExport;

use App\Lib\OrderLib;
use App\Lib\DashboardLib;
use App\Lib\ChartLib;
use App\Lib\TicketLib;

use App\Models\Agent;
use App\Models\Location;

class Daily extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $revenueOrders;
    public $revenueRides;
    public $paxOrders;
    public $paxTravels;

    public $fromDate;
    public $toDate;
    public $fromLocation;
    public $toLocation;

    public $revenueOrderIndex;
    public $revenueRideIndex;
    public $paxOrderIndex;
    public $paxTravelIndex;

    public $confirmOrderTotal;
    public $reserveOrderTotal;
    public $cancelOrderTotal;

    public $paxOrderCustomerType;
    public $paxRideCustomerType;

    public $status = CONFIRMEDORDER;
    public $type = 'byDay';
    public $selectType = 'date';

    public $locationList;

    public $orderDetail;
    public $showModal = false;
    public $rideId;
    public $listPassengers;

    public function mount(){
        $this->role = auth()->user()->role;

        $this->fromDate = $this->toDate = now()->toDateString();

        $this->locationList = Location::get()->where('status', ACTIVE);
        $this->agents = Agent::get();

        $this->revenueOrders = ChartLib::revenueOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $this->revenueRides = ChartLib::revenueRideInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $this->paxOrders = ChartLib::paxOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $this->paxTravels = ChartLib::paxTravelInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);

        $this->revenueOrderIndex = $this->revenueOrders->sum('revenue');
        $this->revenueRideIndex = $this->revenueRides->sum('revenue');
        $this->paxOrderIndex = $this->paxOrders->sum('pax');
        $this->paxTravelIndex = $this->paxTravels->sum('pax');

        $this->confirmOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);
        $this->reserveOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, RESERVATION, $this->fromLocation, $this->toLocation);
        $this->cancelOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, CANCELDORDER, $this->fromLocation, $this->toLocation);

        $this->paxOrderCustomerType = ChartLib::countPaxOrderByCustomerType($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);
        $this->paxRideCustomerType = ChartLib::countPaxTraveledByCustomerType($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);
    }

    public function updated(){
        $revenueOrderNewData = ChartLib::revenueOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $revenueRideNewData = ChartLib::revenueRideInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $paxOrdersNewData = ChartLib::paxOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $paxTravelsNewData = ChartLib::paxTravelInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);

        $this->revenueOrderIndex = $revenueOrderNewData->sum('revenue');
        $this->revenueRideIndex = $revenueRideNewData->sum('revenue');
        $this->paxOrderIndex = $paxOrdersNewData->sum('pax');
        $this->paxTravelIndex = $paxTravelsNewData->sum('pax');

        $this->confirmOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);
        $this->reserveOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, RESERVATION, $this->fromLocation, $this->toLocation);
        $this->cancelOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, CANCELDORDER, $this->fromLocation, $this->toLocation);

        $this->paxOrderCustomerType = ChartLib::countPaxOrderByCustomerType($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);
        $this->paxRideCustomerType = ChartLib::countPaxTraveledByCustomerType($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);

        $this->emit('revenuesUpdated', [
                                        'revenueOrderNewData' => json_encode($revenueOrderNewData),
                                        'revenueRideNewData' => json_encode($revenueRideNewData),
                                    ]);
        $this->emit('ordersUpdated', [
                                        'paxOrdersNewData' => json_encode($paxOrdersNewData), 
                                        'paxTravelsNewData' => json_encode($paxTravelsNewData)
                                    ]);
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

        $listRides = DashboardLib::ridesInDay($this->fromDate, $this->toDate, $this->fromLocation, $this->toLocation);
    
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.daily', ['listRides' => $listRides])->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.daily', ['listRides' => $listRides])->layout('manager.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.report.daily', ['listRides' => $listRides])->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
