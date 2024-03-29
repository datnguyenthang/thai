<?php

namespace App\Http\Livewire\Backend\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;
use Carbon\Carbon;

use App\Lib\OrderLib;
use App\Lib\EmailLib;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderPayment;
use App\Models\OrderTicket;
use App\Models\CustomerType;
use App\Models\Promotion;
use App\Models\Pickupdropoff;
use App\Models\PaymentMethod;
use App\Models\Agent;

class AgentOrder extends Component
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

    public $returnPickup;
    public $returnPickupAny;
    public $returnPickupAnyOther;
    public $returnDropoff;
    public $returnDropoffAny;
    public $returnDropoffAnyOther;

    public $note;
    public $promotionId;
    public $paymentMethod;
    public $paymentStatus;
    public $transactionCode;
    public $transactionDate;
    public $agentId;
    public $status;

    public $statusNote;
    public $paymentNote;

    public $agent;

    public $fromLocationList;
    public $toLocationList;
    public $customerTypelist;
    public $paymentMethodList;
    public $orderStatusList;

    public $depart;
    public $seatDepart;
    public $return;
    public $seatReturn;

    public $order_depart_rideId;
    public $order_depart_seatClassId;
    public $order_return_rideId;
    public $order_return_seatClassId;
    public $customerTypeType;
    public $customerTypePrice;

    public $departPrice = 0;
    public $returnPrice = 0;
    public $onlinePrice;
    public $originalPrice;
    public $couponAmount;
    public $finalPrice;

    public $isTransaction = false;

    public $fromLocationName;
    public $toLocationName;

    public $departRides = [];
    public $returnRides = [];

    public $countTicketSelected = 0;

    public $coupon;
    public $couponCode;
    public $isValidCoupon = false;
    public $discountAmount= null;

    public $validationPassed = false;

    public $order;
    protected $listeners = ['refreshAgentOrder' => 'refreshAgentOrder'];

    public function mount(){
        $this->adults = 1;
        $this->children = 0;
        $this->tripType = ONEWAY;

        $this->agent = Agent::find( Auth::user()->agentId);
        $this->customerTypelist = CustomerType::whereIn('id', explode(',', $this->agent->agentType))
                                        ->where('status', ACTIVE)
                                        ->get();
        $this->customerType = $this->customerTypelist->first()->id;
        $this->customerTypeType = $this->customerTypelist->first()->type;

        $locations = Location::get()->where('status', ACTIVE);

        if (count($locations) > 0) {
            $this->fromLocation = $locations->random()->id;
            $this->toLocation = $locations->filter(function ($location){
                                                    return $location->id !==  $this->fromLocation;
                                                })->random()->id;
        }

        $this->departureDate = now()->addDay()->toDateString();
        $this->returnDate = now()->addDays(2)->toDateString();

        $this->fromLocationList = Location::get()->where('status', ACTIVE);
        $this->toLocationList = Location::get()->where('status', ACTIVE);

        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);

        $this->pickupdropoffs = Pickupdropoff::get();
        $this->pickup = 0;
        $this->dropoff = 0;
        $this->returnPickup = 0;
        $this->returnDropoff = 0;

        $this->paymentMethodList = PaymentMethod::get()->where('status', ACTIVE);
        //$this->paymentMethod = $this->paymentMethodList->first()->id;

        $this->orderStatusList = array_intersect_key(ORDERSTATUS, array_flip([RESERVATION, UPPLOADTRANSFER]));
        $this->status = RESERVATION;
        $this->paymentStatus = NOTPAID;
    }
    public function refreshOrder(){
        return redirect()->to('/agentorder');
    }

    public function chooseFromLocation($location){
        $this->toLocationList = Location::get()->where('status', ACTIVE)->except($location);
        if($this->toLocation == $location) $this->toLocation =  $this->toLocationList->first()->id;
    }

    public function chooseToLocation($location){
        $this->fromLocationList = Location::get()->where('status', ACTIVE)->except($location);
        if($this->fromLocation == $location) $this->fromLocation =  $this->fromLocationList->first()->id;
    }

    public function updatedPickup($pickup){
        if ($this->pickup == PICKUPANY) $this->pickupAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatedDropoff($dropoff){
        if ($this->dropoff == DROPOFFANY) $this->dropoffAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatedReturnPickup($returnPickup){
        if ($this->returnPickup == PICKUPANY) $this->returnPickupAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatedReturnDropoff($returnDropoff){
        if ($this->returnDropoff == DROPOFFANY) $this->returnDropoffAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatedStatus(){
        if ($this->status == RESERVATION) {
            $this->paymentMethod = null;
            $this->transactionCode = null;
            $this->transactionDate = null;
            $this->paymentStatus = NOTPAID;
        }
        if ($this->status == CONFIRMEDORDER){
            $this->paymentMethod = $this->paymentMethodList->first()->id;
            $this->paymentStatus = PAID;
        }
    }

    public function updatedPaymentMethod(){
        $this->isTransaction = PaymentMethod::find($this->paymentMethod)->isTransaction;
    }

    public function updatedCustomerType(){
        $this->customerTypeType = $this->customerTypelist->first(function($item){
            return $item->id == $this->customerType;
        })->type;
    }

    public function updatePrice(){
        $departOnlinePrice = $returnOnlinePrice = 0;
        if ($this->order_depart_rideId) {
            $this->depart = Ride::find($this->order_depart_rideId);
            $this->seatDepart = SeatClass::find($this->order_depart_seatClassId);
            $departOnlinePrice = $this->seatDepart->price * $this->adults;
            
            //CHECK TYPE OF PRICE
            if($this->customerTypeType == ONLINEPRICE)
                $this->departPrice = $this->seatDepart->price * $this->adults;

            if($this->customerTypeType != ONLINEPRICE)
                $this->departPrice = $this->customerTypePrice * $this->adults;
        }

        if ($this->order_return_rideId) {
            $this->return = Ride::find($this->order_return_rideId);
            $this->seatReturn = SeatClass::find($this->order_return_seatClassId);
            $returnOnlinePrice = $this->seatReturn->price * $this->adults;
            
            //CHECK TYPE OF PRICE
            if($this->customerTypeType == ONLINEPRICE)
                $this->returnPrice = $this->seatReturn->price * $this->adults;
            
            if($this->customerTypeType != ONLINEPRICE)
                $this->returnPrice = $this->customerTypePrice * $this->adults;
        }

        $this->onlinePrice = $departOnlinePrice + $returnOnlinePrice;
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
            'adults' => 'required|numeric|gt:0',
            'children' => 'required|numeric',
            'fromLocation' => 'required',
            'toLocation' => 'required',
            'departureDate' => 'required',
            'returnDate' => 'required',
            'customerType' => 'required',
            //'email' => 'required|email|unique:users,email,' . $this->userId,
        ]);
        // Set the property to true if validation passes
        $this->validationPassed = true;
        $this->findRides();
        
        $this->step++;
        $this->text_ticket_select = trans('messages.ticketselected', ['totalTicket' => $this->countTicketSelected, 'typeTrip'=> $this->tripType]);

        $this->fromLocationName = Location::find($this->fromLocation)->name;
        $this->toLocationName = Location::find($this->toLocation)->name;

        $this->customerTypePrice = CustomerType::find($this->customerType)->price;

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
        $this->findRides();
    }
    public function findRides(){
        if ($this->validationPassed) {
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
                                            $query->where('rides.departDate', $this->returnDate);
                                            $query->where('sc.capacity', '>=', $this->countingSeatBooked('rides.id', 'sc.id') + $this->adults); //check avaiable seatclasses to show
                                            $query->where('rides.status', 0);
                                            $query->where('sc.status', 0);
                                        })
                                        ->get();
        }
    }

    public function checkInfo() {
        $this->validate([
            //'firstName' => 'required|max:255',
            //'lastName' => 'required|max:255',
            'phone' => 'nullable|numeric|digits_between:8,12|required_without:email',
            'email' => 'nullable|email|max:255||regex:/(.+)@(.+)\.(.+)/i|required_without:phone',
            'pickupAnyOther' => 'max:255|required_if:pickup,'.PICKUPANYOTHER,
            'dropoffAnyOther' => 'max:255|required_if:dropoff,'.DROPOFFANYOTHER,
        ]);
        $this->step++;
    }

    public function bookTicket(){
        $this->validate([
            'transactionCode' => [
                'required_if:isTransaction,1',
                $this->isTransaction == 1 ? 'min:4' : '',
                $this->isTransaction == 1 ? 'max:15' : '',
            ],
            'transactionDate' => [
                'required_if:isTransaction,1',
            ],
        ]);

        $codeOrder = Order::generateCode();
        $codeDepart = $codeOrder.'-1';
        $codeReturn = $codeOrder.'-2';

        // MAKE A TRANSACTION HERE TO ENSURE DATA CONSISTENCY
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
                'phone' => $this->phone ?? null,
                'email' => $this->email ?? null,
                'note' => $this->note,
                'adultQuantity' => intVal($this->adults),
                'childrenQuantity' => intVal($this->children),
                'onlinePrice' => $this->onlinePrice,
                'originalPrice' => $this->originalPrice,
                'couponAmount' => $this->couponAmount,
                'finalPrice' => $this->finalPrice,
                'bookingDate' => date('Y-m-d H:i:s'),
                'userId' => Auth::id(),
                'agentId' => Auth::user()->agentId,
                'paymentStatus' => $this->paymentStatus,
                'paymentMethod' => $this->paymentMethod,
                'transactionCode' => $this->transactionCode,
                'transactionDate' => $this->transactionDate,

                'status' => $this->status,
            ]);

            //SAVE first status of this order
            OrderStatus::create([
                'orderId' => intVal($order->id),
                'status' => $this->status,
                //'note' => $this->note,
                'changeDate' => date('Y-m-d H:i:s'),
                'userId' => Auth::id(),
            ]);

            //SAVE first payment of this order
            OrderPayment::create([
                'orderId' => intVal($order->id),
                'paymentStatus' => $this->paymentStatus,
                'paymentMethod' => $this->paymentMethod,
                'transactionCode' => $this->transactionCode,
                'transactionDate' => $this->transactionDate,
                'changeDate' => date('Y-m-d H:i:s'),
                'userId' => Auth::id(),
            ]);

            //SAVE ORDER TICKET DEPART FIRST
            OrderTicket::create([
                'orderId' => intVal($order->id),
                'code' => $codeDepart,
                'rideId' => intVal($this->order_depart_rideId),
                'seatClassId' => intVal($this->order_depart_seatClassId),
                'price' => $this->departPrice,
                'type' => DEPARTURETICKET,
                'pickup' => $this->getPickup(),
                'dropoff' => $this->getDropOff(),
                'status' => 0,
            ]);
            
            //SAVE ORDER TICKET RETURN
            if ($this->tripType == ROUNDTRIP) {
                OrderTicket::create([
                    'orderId' => intVal($order->id),
                    'code' => $codeReturn,
                    'rideId' => intVal($this->order_return_rideId),
                    'seatClassId' => intVal($this->order_return_seatClassId),
                    'price' => $this->returnPrice,
                    'type' => RETURNTICKET,
                    'pickup' => $this->getReturnPickup(),
                    'dropoff' => $this->getReturnDropOff(),
                    'status' => 0,
                ]);
            }
            
            DB::commit();

            //SEND MAIL
            EmailLib::sendMailConfirmOrderEticket($codeOrder);
            $this->order = OrderLib::getOrderDetailByCode($codeOrder);

            $this->step++;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getPickup(){
        if ($this->pickup == PICKUPDONTUSESERVICE) $this->pickup = "";
        if ($this->pickup == PICKUPANY) $this->pickup = $this->pickupAny;
        if ($this->pickup == PICKUPANYOTHER) $this->pickup = $this->pickupAnyOther;
        return $this->pickup;
    }

    public function getDropOff(){
        if ($this->dropoff == DROPOFFDONTUSESERVICE) $this->dropoff = "";
        if ($this->dropoff == DROPOFFANY) $this->dropoff = $this->dropoffAny;
        if ($this->dropoff == DROPOFFANYOTHER) $this->dropoff = $this->dropoffAnyOther;
        return $this->dropoff;
    }

    public function getReturnPickup(){
        if ($this->returnPickup == PICKUPDONTUSESERVICE) $this->returnPickup = "";
        if ($this->returnPickup == PICKUPANY) $this->returnPickup = $this->returnPickupAny;
        if ($this->returnPickup == PICKUPANYOTHER) $this->returnPickup = $this->returnPickupAnyOther;
        return $this->returnPickup;
    }

    public function getReturnDropOff(){
        if ($this->returnDropoff == DROPOFFDONTUSESERVICE) $this->returnDropoff = "";
        if ($this->returnDropoff == DROPOFFANY) $this->returnDropoff = $this->returnDropoffAny;
        if ($this->returnDropoff == DROPOFFANYOTHER) $this->returnDropoff = $this->returnDropoffAnyOther;
        return $this->returnDropoff;
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
            $this->departPrice = round($this->departPrice - ($this->departPrice * $coupon->discount));
            $this->returnPrice = round($this->returnPrice - ($this->returnPrice * $coupon->discount));
        }
    }

    public function render()
    {
        return view('livewire.backend.agent.agent-order')
                ->layout('agent.layouts.app');
    }
}
