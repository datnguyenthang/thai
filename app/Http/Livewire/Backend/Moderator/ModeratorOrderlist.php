<?php

namespace App\Http\Livewire\Backend\Moderator;
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

class ModeratorOrderlist extends Component
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
    public $isAllowed = false;

    protected $orderList;

    public function mount(){
        $this->role = auth()->user()->role;
        if (in_array($this->role, ['admin', 'manager', 'viewer'])) $this->isAllowed = true;

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
                                        $this->customerType, $this->agentId, $this->fromLocation, $this->toLocation, $this->orderStatus)
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

    public function printBoardingPass($orderId = 0, $orderTicketId = 0){
        $this->orderDetail = OrderLib::getOrderDetail($orderId);
        return TicketLib::printBoardingPass($orderTicketId);
    }

    public function viewOrder($orderId) {
        $this->orderDetail = OrderLib::getOrderDetail($orderId);
        $this->showModal = true;
    }

    public function render() {
        $orderLists = OrderLib::getOrderListQuery($this->orderCode, $this->customerName, $this->customerPhone, 
                                    $this->customerType, $this->agentId, $this->fromLocation, $this->toLocation, $this->orderStatus)
                            ->orderBy($this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);

        return view('livewire.backend.moderator.moderator-orderlist', compact('orderLists'))
                ->layout('moderator.layouts.app');
    }
}
