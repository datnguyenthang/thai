<?php

namespace App\Http\Livewire\Backend\Moderator;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

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
    public $perPage = 50;
    public $sortField = 'orderCode';
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

    protected $orderList;

    public function mount(){
        $this->agents = Agent::get();
    }

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

    public function detail(){
        
    }

    public function render()
    {
        $orderList = Order::select('orders.id as orderId', 'orders.code as orderCode', 'orders.userId', 'orders.isReturn', 'orders.agentId', 'orders.promotionId',
                                        DB::raw('CONCAT(orders.firstName, " ", orders.lastName) as fullname'), 'orders.firstName', 'orders.lastName', 'orders.phone', 'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity',
                                        'orders.price as totalPrice', 'orders.bookingDate', 'orders.status as orderStatus', 'orders.customerType as customerType',
                                        'ot.id as orderTicketId', 'ot.rideId', 'ot.code as ticketCode', 'ot.price as detailPrice', 'ot.status as orderTicketStatus',
                                        //'sc.name as seatClassName', 'sc.capacity as capacity', 'sc.price as seatPrice',
                                        'fl.name as fromLocationName', 'tl.name as toLocationName', 'p.name as promotionName',
                                        'a.name as agentName',
                                        'r.name as rideName')
                                ->leftJoin('order_tickets as ot', 'ot.orderId', '=', 'orders.id')
                                ->leftJoin('rides as r', 'r.id', '=', 'ot.rideId')
                                ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                //->leftJoin('seat_classes as sc', 'sc.rideId', '=', 'r.id')
                                ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
                                ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
                                ->where(function ($query) {
                                    if ($this->orderCode) $query->where('orders.code', 'like', '%'.$this->orderCode.'%');
                                    if ($this->ticketCode) $query->where('ot.code', 'like', '%'.$this->ticketCode.'%');
                                    if ($this->customerName) $query->whereRaw('CONCAT(orders.firstName, " ", orders.lastName) LIKE ?', ['%'.$this->customerName.'%']);
                                    if ($this->customerPhone) $query->where('orders.phone', 'like', '%'.$this->customerPhone.'%');
                                    if ($this->rideName) $query->where('r.name', $this->rideName);

                                    if ($this->bookingDate) $query->whereRaw('STR_TO_DATE(orders.bookingDate, "%Y-%m-%d") = ?', [$this->bookingDate]);
                                    if ($this->agentId) $query->where('orders.agentId', $this->agentName);
                                    if ($this->orderStatus) $query->where('orders.status', $this->orderStatus);
                                    if ($this->customerType >= 0) $query->where('orders.customerType', $this->customerType);
                                    
                                    //$query->where('r.status', 0);
                                })
                                ->orderBy($this->sortField, $this->sortDirection)
                                ->paginate($this->perPage);

        return view('livewire.backend.moderator.moderator-orderlist', compact('orderList'))
                ->layout('moderator.layouts.app');
    }
}
