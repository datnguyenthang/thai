<?php

namespace App\Http\Livewire\Backend\Moderator;
use Illuminate\Support\Facades\DB;

use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderTicket;

class ModeratorOrderlist extends Component
{
    public $search = '';
    public $perPage = 50;
    public $sortField = 'ot.code';
    public $sortDirection = 'desc';

    //public $orderList;

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

    public function search(){
        
        //dd($this->orderList);
    }

    public function detail(){
        
    }

    public function render()
    {
        $orderList = Order::select('orders.id as orderId', 'orders.code as orderCode', 'orders.userId', 'orders.isReturn', 'orders.agentId', 'orders.promotionId',
                                          'orders.firstName', 'orders.lastName', 'orders.phone', 'orders.email', 'orders.note', 'orders.adultQuantity', 'orders.childrenQuantity',
                                          'orders.price as totalPrice', 'orders.bookingDate', 'orders.status as orderStatus', 'orders.customerType as customerType',
                                          'ot.id as orderTicketId', 'ot.rideId', 'ot.code as code', 'ot.price as detailPrice', 'ot.status as orderTicketStatus',
                                          //'sc.name as seatClassName', 'sc.capacity as capacity', 'sc.price as seatPrice',
                                          'fl.name as fromLocationName', 'tl.name as toLocationName', 'p.name as promotionName',
                                          'a.name as agentName')
                                ->leftJoin('order_tickets as ot', 'ot.orderId', '=', 'orders.id')
                                ->leftJoin('rides as r', 'r.id', '=', 'ot.rideId')
                                ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                //->leftJoin('seat_classes as sc', 'sc.rideId', '=', 'r.id')
                                ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
                                ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
                                ->where(function ($query) {
                                    //$query->where('r.fromLocation', $this->toLocation);
                                    //$query->where('r.toLocation', $this->fromLocation);
                                    //$query->where('r.departDate', $this->departureDate);
                                    $query->where('r.status', 0);
                                    //$query->where('sc.status', 0);
                                })
                                ->orderBy($this->sortField, $this->sortDirection)
                                ->paginate($this->perPage);

        return view('livewire.backend.moderator.moderator-orderlist', compact('orderList'))
                ->layout('moderator.layouts.app');
    }
}
