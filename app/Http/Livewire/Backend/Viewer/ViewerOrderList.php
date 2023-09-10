<?php

namespace App\Http\Livewire\Backend\Viewer;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

use App\Lib\OrderLib;
use App\Lib\TicketLib;

use Livewire\Component;

use App\Models\Agent;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderTicket;

class ViewerOrderList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;
    public $sortField = 'orders.id';
    public $sortDirection = 'desc';

    public $agents;

    public $orderCode;
    public $ticketCode;
    public $rideName;
    public $customerName;
    public $customerPhone;
    public $bookingDate;
    public $endDate;
    public $agentId;
    public $orderStatus;
    public $customerType = -1;
    public $fromLocation;
    public $toLocation;

    public $locationList;

    public $orderDetail;
    public $showModal = false;

    public $role;

    protected $orderList;

    public function mount(){
        $this->role = auth()->user()->role;

        $this->locationList = Location::get()->where('status', ACTIVE);

        $this->agents = Agent::get();
    }
    public function sortBy($field){
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function orderBy($field){
        $this->rides = Ride::orderBy($field)->get();
    }

    public function filter(){
        $this->resetPage();
    }

    public function downloadOrderList(){
        $orderLists = OrderLib::getOrderListQuery($this->orderCode, $this->customerName, $this->customerPhone, 
                                        $this->customerType, $this->agentId, $this->fromLocation, $this->toLocation)
                                ->orderBy($this->sortField, $this->sortDirection)
                                ->get();

        $export = new OrdersExport($orderLists);

        return Excel::download($export, 'order_list.xlsx');
    }

    public function downnloadTicket($orderId = 0, $orderTicketId){
        $this->orderDetail = OrderLib::getOrderDetail($orderId);
        return TicketLib::downloadEticket($orderTicketId);
    }

    public function downnloadboardingPass($orderId = 0, $orderTicketId = 0){
        $this->orderDetail = OrderLib::getOrderDetail($orderId);
        return TicketLib::downloadBoardingPass($orderTicketId);
    }

    public function viewOrder($orderId) {
        $this->orderDetail = OrderLib::getOrderDetail($orderId);
        $this->showModal = true;
    }

    public function render() {
        $orderList = OrderLib::getOrderListQuery($this->orderCode, $this->customerName, $this->customerPhone, 
                                    $this->customerType, $this->agentId, $this->fromLocation, $this->toLocation)
                            ->orderBy($this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);

        return view('livewire.backend.viewer.viewer-order-list', compact('orderList'))
                ->layout('viewer.layouts.app');
    }
}
