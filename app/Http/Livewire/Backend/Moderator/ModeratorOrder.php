<?php

namespace App\Http\Livewire\Backend\Moderator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Carbon\Carbon;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\CustomerType;
use App\Models\Promotion;
use App\Models\Pickupdropoff;

class ModeratorOrder extends Component
{
    public $step = 1;

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
    public $customerTypelist;

    public $depart;
    public $seatDepart;
    public $return;
    public $seatReturn;

    public $order_depart_rideId;
    public $order_depart_seatClassId;
    public $order_return_rideId;
    public $order_return_seatClassId;

    public $departPrice = 0;
    public $returnPrice = 0;
    public $originalPrice;
    public $couponAmount;
    public $finalPrice;

    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $pickup;
    public $pickupAny;
    public $pickupAnyOther;
    public $dropoff;
    public $dropoffAny;
    public $dropoffAnyOther;
    public $note;
    public $promotionId;

    public $fromLocationName;
    public $toLocationName;

    public $departRides = [];
    public $returnRides = [];

    public $countTicketSelected = 0;

    public $coupon;
    public $couponCode;
    public $isValidCoupon = false;
    public $discountAmount= null;

    public $order;

    public function mount(){
        $this->adults = 1;
        $this->children = 0;
        $this->tripType = ONEWAY;

        $this->customerTypelist = CustomerType::get()->where('status', ACTIVE);
        $this->customerType = $this->customerTypelist->first()->id;

        $locations = Location::get()->where('status', ACTIVE);
        $this->fromLocation = $locations->random()->id;
        $this->toLocation = $locations->filter(function ($location){
                                                    return $location->id !==  $this->fromLocation;
                                                })->random()->id;

        $this->departureDate = now()->addDay()->toDateString();
        $this->returnDate = now()->addDays(2)->toDateString();

        $this->fromLocationList = Location::get()->where('status', ACTIVE);
        $this->toLocationList = Location::get()->where('status', ACTIVE);

        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);

        $this->pickupdropoffs = Pickupdropoff::get();
        $this->pickup = 0;
        $this->dropoff = 0;
    }

    public function chooseFromLocation($location){
        $this->toLocationList = Location::get()->where('status', ACTIVE)->except($location);
        $this->toLocation =  $this->toLocationList->first()->id;
    }

    public function chooseToLocation($location){
        $this->fromLocationList = Location::get()->where('status', ACTIVE)->except($location);
        $this->fromLocation =  $this->fromLocationList->first()->id;
    }

    public function updatedPickup($pickup){
        if ($this->pickup == PICKUPANY) $this->pickupAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatedDropoff($dropoff){
        if ($this->dropoff == DROPOFFANY) $this->dropoffAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatePrice(){
        if ($this->order_depart_rideId) {
            $this->depart = Ride::find($this->order_depart_rideId);
            $this->seatDepart = SeatClass::find($this->order_depart_seatClassId);
            $this->departPrice = $this->seatDepart->price * $this->adults;
        }

        if ($this->order_return_rideId) {
            $this->return = Ride::find($this->order_return_rideId);
            $this->seatReturn = SeatClass::find($this->order_return_seatClassId);
            $this->returnPrice = $this->seatReturn->price * $this->adults;
        }

        $this->originalPrice = $this->departPrice + $this->returnPrice;
        $this->finalPrice = $this->departPrice + $this->returnPrice;

        if ($this->promotionId) $this->applyCoupon();
    }

    public function chooseDepartTrip($seatId, $seatClassId){
        $this->countTicketSelected += 1;
        $this->order_depart_rideId = $seatId;
        $this->order_depart_seatClassId = $seatClassId;

        //Update departure ticket info and price
        $this->updatePrice();
    }

    public function chooseReturnTrip($seatId, $seatClassId){
        $this->countTicketSelected += 1;
        $this->order_return_rideId = $seatId;
        $this->order_return_seatClassId = $seatClassId;

        //Update return ticket info and price
        $this->updatePrice();
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
        
        $this->step++;
        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);

        $this->fromLocationName = Location::find($this->fromLocation)->name;
        $this->toLocationName = Location::find($this->toLocation)->name;

        //Update price
        $this->updatePrice();
    }

    function countingSeatBooked($rideId = 0, $seatClassId = 0) {
        $counting = Order::with(['orderTickets' => function($orderTicket){
                        $orderTicket->query('order_tickets.rideId', $rideId)
                                    ->query('order_tickets.seatClassId', $seatClassId);
                    }])
                    ->where('status', COMPLETEDORDER)
                    ->sum('adultQuantity');
        return $counting;
    }

    public function hydrate(){
        $this->departRides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 
                                            'tl.name as toLocation', 'rides.departTime', 'rides.returnTime',
                                            'rides.departDate', 'rides.status',
                                            //DB::raw('TIME_FORMAT(TIMEDIFF(rides.returnTime, rides.departTime), "%H:%i") AS distanceTime'),
                                            'sc.id as seatClassId', 'sc.name as seatClass', 'sc.capacity', 'sc.price', 'sc.status')
                                    ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                                    ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                                    ->leftJoin('seat_classes as sc', 'rides.id', '=', 'sc.rideId')
                                    ->where(function ($query) {
                                        $query->where('rides.fromLocation', $this->fromLocation);
                                        $query->where('rides.toLocation', $this->toLocation);
                                        $query->where('rides.departDate', $this->departureDate);
                                        $query->where('sc.capacity', '>=', $this->countingSeatBooked('rides.id', 'sc.id') + $this->adults); //check avaiable seatclasses to show
                                        $query->where('rides.status', 0);
                                        $query->where('sc.status', 0);
                                    })
                                    ->get();

        $this->returnRides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 
                                    'tl.name as toLocation', 'rides.departTime', 'rides.returnTime',
                                    'rides.departDate', 'rides.status',
                                    //DB::raw('TIME_FORMAT(TIMEDIFF(rides.returnTime, rides.departTime), "%H:%i") AS distanceTime'),
                                    'sc.id as seatClassId', 'sc.name as seatClass', 'sc.capacity', 'sc.price', 'sc.status')
                            ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                            ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                            ->leftJoin('seat_classes as sc', 'rides.id', '=', 'sc.rideId')
                            ->where(function ($query) {
                                $query->where('rides.fromLocation', $this->toLocation);
                                $query->where('rides.toLocation', $this->fromLocation);
                                $query->where('rides.departDate', $this->departureDate);
                                $query->where('sc.capacity', '>=', $this->countingSeatBooked('rides.id', 'sc.id') + $this->adults); //check avaiable seatclasses to show
                                $query->where('rides.status', 0);
                                $query->where('sc.status', 0);
                            })
                            ->get();
    }

    public function checkInfo() {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'phone' => 'nullable|numeric|digits_between:8,11|required_without:email',
            'email' => 'nullable|email|regex:/(.+)@(.+)\.(.+)/i|required_without:phone',
            'pickupAnyOther' => 'required_if:pickup,'.PICKUPANYOTHER,
            'dropoffAnyOther' => 'required_if:dropoff,'.DROPOFFANYOTHER,
        ]);
        $this->step++;
    }

    public function bookTicket(){
        $codeOrder = Order::generateCode();
        $codeDepart = $codeOrder.'-001';
        $codeReturn = $codeOrder.'-002';
        
        // MAKE A TRANSACTION TO ENSURE DATA CONSISTENCY
        DB::beginTransaction();

        try {
            //SAVE ORDER FIRST
            $order = Order::create([
                'code' => $codeOrder,
                'customerType' => intVal($this->customerType),
                'isReturn' => intVal($this->tripType),
                'promotionId' => intVal($this->promotionId),
                'firstName' => $this->firstName,
                'lastName' => $this->lastName,
                'phone' => $this->phone,
                'email' => $this->email,
                'note' => $this->note,
                'pickup' => $this->pickup,
                'dropoff' => $this->dropoff,
                'adultQuantity' => intVal($this->adults),
                'childrenQuantity' => intVal($this->children),
                'originalPrice' => $this->originalPrice,
                'couponAmount' => $this->couponAmount,
                'finalPrice' => $this->finalPrice,
                'bookingDate' => date('Y-m-d H:i:s'),
                'userId' => Auth::id(),
                'status' => 0,
            ]);

            //SAVE ORDER TICKET DEPART FIRST
            OrderTicket::create([
                'orderId' => intVal($order->id),
                'code' => $codeDepart,
                'rideId' => intVal($this->order_depart_rideId),
                'seatClassId' => intVal($this->order_depart_seatClassId),
                'price' => $this->seatDepart->price * ($this->adults + $this->children),
                'type' => DEPARTURETICKET,
                'status' => 0,
            ]);
            
            //SAVE ORDER TICKET RETURN
            if ($this->tripType == ROUNDTRIP) {
                OrderTicket::create([
                    'orderId' => intVal($order->id),
                    'code' => $codeReturn,
                    'rideId' => intVal($this->order_return_rideId),
                    'seatClassId' => intVal($this->order_return_seatClassId),
                    'price' => $this->seatReturn->price * ($this->adults + $this->children),
                    'type' => RETURNTICKET,
                    'status' => 0,
                ]);
            }
            
            DB::commit();
            // Redirect to payment page with booking ID parameter
            $this->order = Order::with(['orderTickets' => function($orderTicket){
                                $orderTicket->select('order_tickets.*', 'r.*', 'fl.name as fromLocationName', 'tl.name as toLocationName', 'sc.name as seatClassName')//,'sc.name as seatClassName')
                                            ->leftJoin('rides as r', 'r.id', '=', 'order_tickets.rideId')
                                            ->leftJoin('locations as fl', 'r.fromLocation', '=', 'fl.id')
                                            ->leftJoin('locations as tl', 'r.toLocation', '=', 'tl.id')
                                            ->leftJoin('seat_classes as sc', 'sc.id', '=', 'order_tickets.seatClassId');
                            }])
                            ->leftJoin('promotions as p', 'p.id', '=', 'orders.promotionId')
                            ->leftJoin('agents as a', 'a.id', '=', 'orders.agentId')
                            ->select('orders.id', 'orders.code', 'orders.userId', 'orders.isReturn', 'orders.customerType','orders.status',
                                    DB::raw('CONCAT(firstName, " ",lastName) as fullname'), 'orders.phone', 'orders.originalPrice', 'orders.couponAmount', 'orders.finalPrice',
                                    'orders.email', 'orders.bookingDate', 'orders.note', 'orders.adultQuantity', 'orders.pickup', 'orders.dropoff',
                                    'orders.childrenQuantity', 'p.code as promotionCode', 'p.name as promotionName', 'p.discount as discount', 'a.name as agentName')
                            ->where('orders.id', intVal($order->id))
                            ->first();
            $this->step++;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function removeCoupon() {
        $this->couponCode = '';
        $this->isValidCoupon = false;
        $this->promotionId = null;
        $this->discountAmount = 0;
        $this->finalPrice = $this->originalPrice;
    }

    public function applyCoupon() {
        //Reset data before applying new coupon code
        $this->isValidCoupon = false;
        $this->promotionId = null;
        $this->discountAmount = 0;
        $this->finalPrice = $this->originalPrice;

        //applying coupon
        $this->validate([
            'couponCode' => 'required|exists:promotions,code'
        ]);

        $coupon = Promotion::where('code', $this->couponCode)->first();

        //Check valid time of coupon
        if ($coupon && Carbon::now()->between($coupon->fromDate, $coupon->toDate)) {
            //check valid quantity of coupon 
            $ordersWithCoupon = Order::where('promotionId', $coupon->id)
                                       ->where('status', COMPLETEDORDER)->count();

            if ($coupon->quantity == 0) { // if coupon has unlimited used time
                $this->isValidCoupon = true;
            } elseif ($ordersWithCoupon < $coupon->quantity) { // if coupon has less than used time
                $this->isValidCoupon = true;
            } else { // if coupon has greater than used time
                $this->isValidCoupon = fale;
                $this->addError('coupon', trans('messages.invalidcouponquantity'));
            }
        } else {
            $this->isValidCoupon = false;
            $this->addError('coupon', trans('messages.invalidcoupondate'));
        }

        if ($this->isValidCoupon) {
            $this->promotionId = $coupon->id;
            $this->coupon = $coupon;

            // Apply the discount to orginalPrice
            $this->couponAmount = $this->originalPrice * $coupon->discount;
            $this->finalPrice = round($this->originalPrice - $this->couponAmount);

            //apply to each ticket 
            //$this->departPrice = round($this->departPrice - ($this->departPrice * $coupon->discount));
            //$this->returnPrice = round($this->returnPrice - ($this->returnPrice * $coupon->discount));
        }
    }

    public function render() {
        return view('livewire.backend.moderator.moderator-order')
                ->layout('moderator.layouts.app');
    }
}
