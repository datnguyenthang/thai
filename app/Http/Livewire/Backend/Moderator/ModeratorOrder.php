<?php

namespace App\Http\Livewire\Backend\Moderator;
use Illuminate\Support\Facades\DB;

use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderTicket;

class ModeratorOrder extends Component
{
    public $tripType;
    public $fromLocation;
    public $toLocation;
    public $departureDate;
    public $returnDate;
    public $adults;
    public $children;
    public $customerType;

    public $fromLocationList;
    public $toLocationList;

    public $depart;
    public $seatDepart;
    public $return;
    public $seatReturn;

    public $order_depart_rideId;
    public $order_depart_seatClassId;
    public $order_return_rideId;
    public $order_return_seatClassId;

    public $price = 0;

    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $pickup;
    public $dropoff;
    public $note;
    public $promotionId;

    public $search = 0;

    public $fromLocationName;
    public $toLocationName;

    public $departRides = [];
    public $returnRides = [];

    public $countTicketSelected = 0;

    public $rideId;
    public $seatClassId;

    public function mount(){
        $this->adults = 1;
        $this->children = 0;
        $this->tripType = ROUNDTRIP;

        $this->fromLocationList = Location::get();
        $this->toLocationList = Location::get();

        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);
    }

    public function chooseTripType($typeTrip = 0){
        $this->tripType = $typeTrip;
    }

    public function chooseFromLocation($location){
        $this->toLocationList = Location::get()->except($location);
    }

    public function chooseToLocation($location){
        $this->fromLocationList = Location::get()->except($location);
    }
    
    public function chooseDepartDate($departDate){
        $this->returnDate = $departDate;
    }

    public function chooseDepartTrip($seatId, $seatClassId){
        $this->countTicketSelected += 1;
        $this->order_depart_rideId = $seatId;
        $this->order_depart_seatClassId = $seatClassId;
        $this->applyFilter();
    }

    public function chooseReturnTrip($seatId, $seatClassId){
        $this->countTicketSelected += 1;
        $this->order_return_rideId = $seatId;
        $this->order_return_seatClassId = $seatClassId;
        $this->applyFilter();
    }

    public function clearDepartTicket(){
        $this->countTicketSelected -= 1;
        $this->order_depart_rideId = null;
        $this->order_depart_seatClassId = null;

        $this->applyFilter();
    }

    public function clearReturnTicket(){
        $this->countTicketSelected -= 1;
        $this->order_return_rideId = null;
        $this->order_return_seatClassId = null;

        $this->applyFilter();
    }

    public function applyFilter(){
        $this->validate([
            'fromLocation' => 'required',
            'toLocation' => 'required',
            'departureDate' => 'required',
            'returnDate' => 'required',
            'customerType' => 'required',
            //'email' => 'required|email|unique:users,email,' . $this->userId,
        ]);
        
        $this->search = 1;
        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);

        $this->fromLocationName = Location::find($this->fromLocation)->name;
        $this->toLocationName = Location::find($this->toLocation)->name;

        $this->departRides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 
                                            'tl.name as toLocation', 'rides.departTime', 'rides.returnTime',
                                            'rides.departDate', 'rides.status',
                                            DB::raw('TIME_FORMAT(TIMEDIFF(rides.returnTime, rides.departTime), "%H:%i") AS distanceTime'),
                                            'sc.id as seatClassId', 'sc.name as seatClass', 'sc.capacity', 'sc.price', 'sc.status')
                                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                                    ->leftJoin('seat_classes as sc', 'sc.rideId', '=', 'rides.id')
                                    ->where(function ($query){
                                        $query->where('rides.fromLocation', $this->fromLocation);
                                        $query->where('rides.toLocation', $this->toLocation);
                                        $query->where('rides.departDate', $this->departureDate);
                                        $query->where('rides.status', 0);
                                        $query->where('sc.status', 0);
                                    })
                                    ->get();

        $this->returnRides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 
                                    'tl.name as toLocation', 'rides.departTime', 'rides.returnTime',
                                    'rides.departDate', 'rides.status',
                                    DB::raw('TIME_FORMAT(TIMEDIFF(rides.returnTime, rides.departTime), "%H:%i") AS distanceTime'),
                                    'sc.id as seatClassId', 'sc.name as seatClass', 'sc.capacity', 'sc.price', 'sc.status')
                            ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                            ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                            ->leftJoin('seat_classes as sc', 'sc.rideId', '=', 'rides.id')
                            ->where(function ($query){
                                $query->where('rides.fromLocation', $this->toLocation);
                                $query->where('rides.toLocation', $this->fromLocation);
                                $query->where('rides.departDate', $this->departureDate);
                                $query->where('rides.status', 0);
                                $query->where('sc.status', 0);
                            })
                            ->get();
    }

    public function bookTicket(){
        $codeDepart = OrderTicket::generateCode();
        $codeReturn = OrderTicket::generateCode();

        $price = $this->price;
        
        // MAKE A TRANSACTION TO ENSURE DATA CONSISTENCY
        DB::beginTransaction();

        try {
            $this->seatDepart = SeatClass::find($this->order_depart_seatClassId);
            $this->seatReturn = SeatClass::find($this->order_return_seatClassId);

            $totalPrice = $this->seatDepart->price * ($this->adults + $this->children) + 
                                        $this->seatReturn->price * ($this->adults + $this->children);

            //SAVE ORDER FIRST
            $order = Order::create([
                'code' => Order::generateCode(),
                'customerType' => intVal($this->customerType),
                'isReturn' => intVal($this->tripType),
                'promotionId' => intVal($this->promotionId),
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'phone' => $this->phone,
                'email' => $this->email,
                'note' => $this->note,
                'adultQuantity' => intVal($this->adults),
                'childrenQuantity' => intVal($this->children),
                'price' => $totalPrice,
                'bookingDate' => date('Y-m-d H:i:s'),
                'status' => 0,
            ]);

            //SAVE ORDER TICKET DEPART FIRST
            OrderTicket::create([
                'orderId' => intVal($order->id),
                'code' => OrderTicket::generateCode(),
                'rideId' => intVal($this->order_depart_rideId),
                'seatClassId' => intVal($this->order_depart_seatClassId),
                'price' => $this->seatDepart->price * ($this->adults + $this->children),
                'status' => 0,
            ]);
            
            //SAVE ORDER TICKET RETURN
            if ($this->tripType == ROUNDTRIP) {
                OrderTicket::create([
                    'orderId' => intVal($order->id),
                    'code' => OrderTicket::generateCode(),
                    'rideId' => intVal($this->order_return_rideId),
                    'seatClassId' => intVal($this->order_return_seatClassId),
                    'price' => $this->seatReturn->price * ($this->adults + $this->children),
                    'status' => 0,
                ]);
            }
            
            DB::commit();
            // Redirect to payment page with booking ID parameter
            return redirect()->to('moderatororderlist');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function render()
    {
        return view('livewire.backend.moderator.moderator-order')
                ->layout('moderator.layouts.app');
    }
}
