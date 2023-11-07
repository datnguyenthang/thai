<?php

namespace App\Http\Livewire\Component\Report;

use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

use App\Lib\OrderLib;
use App\Lib\DashboardLib;
use App\Lib\ChartLib;
use App\Lib\ReportLib;

use App\Models\Agent;
use App\Models\Location;

class Monthly extends Component {
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
    public $type = 'byMonth';
    public $selectType = 'month';

    public $locationList;

    public $orderDetail;
    public $showModal = false;
    public $customerTypePayments;
    public $paymentMethodDetails;

    public function mount(){
        $this->fromDate = $this->toDate = now()->format('Y-m');

        $this->customerTypePayments = ReportLib::getCustomerTypePaymentTicket($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $this->paymentMethodDetails = ReportLib::getPaymentMethodTicket($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);

        $this->locationList = Location::get()->where('status', ACTIVE);
        $this->agents = Agent::get();

        $this->revenueOrders = $this->modifyData(ChartLib::revenueOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation));
        $this->revenueRides = $this->modifyData(ChartLib::revenueRideInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation));
        $this->paxOrders = $this->modifyData(ChartLib::paxOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation), 'pax');
        $this->paxTravels = $this->modifyData(ChartLib::paxTravelInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation), 'pax');

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
        $revenueOrderNewData = $this->modifyData(ChartLib::revenueOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation));
        $revenueRideNewData = $this->modifyData(ChartLib::revenueRideInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation));
        $paxOrdersNewData = $this->modifyData(ChartLib::paxOrderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation), 'pax');
        $paxTravelsNewData = $this->modifyData(ChartLib::paxTravelInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation), 'pax');

        $this->revenueOrderIndex = $revenueOrderNewData->sum('revenue');
        $this->revenueRideIndex = $revenueRideNewData->sum('revenue');
        $this->paxOrderIndex = $paxOrdersNewData->sum('pax');
        $this->paxTravelIndex = $paxTravelsNewData->sum('pax');

        $this->confirmOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);
        $this->reserveOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, RESERVATION, $this->fromLocation, $this->toLocation);
        $this->cancelOrderTotal = ChartLib::countOrderStatus($this->fromDate, $this->toDate, $this->type, CANCELDORDER, $this->fromLocation, $this->toLocation);

        $this->paxOrderCustomerType = ChartLib::countPaxOrderByCustomerType($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);
        $this->paxRideCustomerType = ChartLib::countPaxTraveledByCustomerType($this->fromDate, $this->toDate, $this->type, CONFIRMEDORDER, $this->fromLocation, $this->toLocation);

        $this->customerTypePayments = ReportLib::getCustomerTypePaymentTicket($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $this->paymentMethodDetails = ReportLib::getPaymentMethodTicket($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
    
        $this->emit('revenuesUpdated', [
                                        'revenueOrderNewData' => json_encode($revenueOrderNewData),
                                        'revenueRideNewData' => json_encode($revenueRideNewData),
                                    ]);
        $this->emit('ordersUpdated', [
                                        'paxOrdersNewData' => json_encode($paxOrdersNewData), 
                                        'paxTravelsNewData' => json_encode($paxTravelsNewData)
                                    ]);
    }

    public function modifyData($data, $attributeName = 'revenue') {
        $modifiedData = collect(); // Create a new collection for modified data

        list($type, $fromDate, $toDate) = ChartLib::dateFormat($this->type, $this->fromDate, $this->toDate);
        $currentDate = new \DateTime($fromDate);
        $endDate = new \DateTime($toDate);

        while ($currentDate <= $endDate) {
            $formattedDate = $currentDate->format('Y-m-d');

            // Check if the data for the current date exists
            $itemForCurrentDate = $data->firstWhere('data', $formattedDate);
    
            if ($itemForCurrentDate) {
                // If data for the current date exists, add it to the modified collection
                $modifiedData->push($itemForCurrentDate);
            } else {
                // If data for the current date doesn't exist, add a new item
                $modifiedData->push(['data' => $formattedDate, $attributeName => 0]);
            }
    
            $currentDate->modify('+1 day');
        }
        return $modifiedData;
    }

    public function render() {

        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.monthly')->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.monthly')->layout('manager.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.report.monthly')->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
