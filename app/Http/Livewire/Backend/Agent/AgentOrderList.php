<?php

namespace App\Http\Livewire\Backend\Agent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Response;
use App\Lib\OrderLib;

use Livewire\Component;

use App\Models\Agent;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderTicket;

class AgentOrderList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 20;
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

    public $orderDetail;
    public $showModal = false;

    protected $orderList;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function orderBy($field)
    {
        $this->rides = Ride::orderBy($field)->get();
    }

    public function filter(){
        $this->resetPage();
    }

    public function downnloadTicket($orderTicketId){
        $orderTicket = OrderLib::getOrderTicket($orderTicketId);
        $this->orderDetail = OrderLib::getOrderDetail($orderTicket->orderId); // dirty fill up data

        $content = OrderLib::generateEticket($orderTicket); 
        $fileName = $orderTicket->type == ONEWAY ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName);
    }

    public function viewOrder($orderId) {
        $this->orderDetail = OrderLib::getOrderDetail($orderId);
        $this->showModal = true;
    }

    public function render()
    {
        $orderList = Order::with(['orderTickets' => function($orderTicket){
                                    $orderTicket->select('order_tickets.*', 'r.name', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                                                ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                                ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                                ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                                ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                                }])
                            ->whereHas('orderTickets', function ($query) {
                                    if ($this->ticketCode) $query->where('order_tickets.code', 'like', '%'.$this->ticketCode.'%');
                                    if ($this->rideName) $query->where('r.name', $this->rideName);
                                })
                            ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
                            ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
                            ->leftJoin('customer_types as ct', 'ct.id', '=', 'orders.customerType')
                            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'orders.customerType', 'orders.status',
                                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.phone', 'orders.finalPrice',
                                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 
                                    'orders.childrenQuantity', 'p.name as promotionName', 'a.name as agentName', 'ct.name as customerTypeName')
                            ->where(function ($query) {
                                if ($this->orderCode) $query->where('orders.code', 'like', '%'.$this->orderCode.'%');
                                if ($this->customerName) $query->whereRaw('CONCAT(orders.firstName, " ", orders.lastName) LIKE ?', ['%'.$this->customerName.'%']);
                                if ($this->customerPhone) $query->where('orders.phone', 'like', '%'.$this->customerPhone.'%');
                                if ($this->customerType >= 0) $query->where('orders.customerType', $this->customerType);
                                $query->where('orders.agentId', Auth::user()->agentId);
                            })
                            ->orderBy($this->sortField, $this->sortDirection)
                            ->paginate($this->perPage);
        return view('livewire.backend.agent.agent-order-list', compact('orderList'))
                    ->layout('agent.layouts.app');
    }
}
