<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderPayment;
use App\Models\OrderTicket;
use App\Models\Pickupdropoff;
use App\Models\Promotion;

class ProceedBooking extends Component
{
    public $tripType;
    public $countTicketSelected;
    public $fromLocation;
    public $toLocation;
    public $departureDate;
    public $returnDate;
    public $adults;
    public $children;

    public $fromLocationName;
    public $toLocationName;

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
    public $onlinePrice;
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
    public $agreepolicy;

    public $coupon;
    public $couponCode;
    public $isValidCoupon = false;
    public $discountAmount= null;

    public $pickupdropoffs;

    protected $rules = [
        'firstName' => 'required|max:255',
        'lastName' => 'required|max:255',
        'phone' => 'numeric|digits_between:8,12',
        'email' => 'required|max:255|email|regex:/(.+)@(.+)\.(.+)/i',
        'pickupAnyOther' => 'max:255|required_if:pickup,'.PICKUPANYOTHER,
        'dropoffAnyOther' => 'max:255|required_if:dropoff,'.DROPOFFANYOTHER,
    ];

    public function mount(Request $request) {
        //$this->tripType = $request->input('tripType');
        $this->tripType = $request->input('countTicketSelected');
        $this->fromLocation = $request->input('fromLocation');
        $this->toLocation = $request->input('toLocation');
        $this->departureDate = $request->input('departureDate');
        $this->returnDate = $request->input('returnDate');
        $this->adults = $request->input('adults');
        $this->children = $request->input('children');

        $this->order_depart_rideId = $request->input('order_depart_rideId') ? $request->input('order_depart_rideId') : $request->input('order_return_rideId');
        $this->order_depart_seatClassId = $request->input('order_depart_seatClassId') ? $request->input('order_depart_seatClassId') : $request->input('order_return_seatClassId');
        
        $this->order_return_rideId = $request->input('order_return_rideId');
        $this->order_return_seatClassId = $request->input('order_return_seatClassId');

        //CHECKING VALID PARAMETER
        if (
            !$request->has('countTicketSelected') ||
            !$request->has('fromLocation') ||
            !$request->has('toLocation') ||
            !$request->has('departureDate') ||
            !$request->has('returnDate') ||
            !$request->has('adults') ||
            !$request->has('children') ||
            !$this->order_depart_rideId ||
            !$this->order_depart_seatClassId
        ) return redirect()->to('/'); 
        
        //Get location name
        $this->fromLocationName = Location::find($this->fromLocation)->name;
        $this->toLocationName = Location::find($this->toLocation)->name;
        
        //Get departure ticket info
        $this->depart = Ride::find($this->order_depart_rideId);
        $this->seatDepart = SeatClass::find($this->order_depart_seatClassId);

        if ($this->tripType == ROUNDTRIP) {
            $this->return = Ride::find($this->order_return_rideId);
            $this->seatReturn = SeatClass::find($this->order_return_seatClassId);
            
            //currently, price don't include children
            //$this->returnPrice = $this->seatReturn->price * ($this->adults + $this->children);
            $this->returnPrice = $this->seatReturn->price * $this->adults;
        }

        //currently, price don't include children
        //$this->departPrice = $this->seatDepart->price * ($this->adults + $this->children);
        $this->departPrice = $this->seatDepart->price * $this->adults;

        $this->pickupdropoffs = Pickupdropoff::get();
        $this->pickup = 0;
        $this->dropoff = 0;

        $this->originalPrice = $this->onlinePrice = $this->departPrice + $this->returnPrice;
        $this->finalPrice = $this->departPrice + $this->returnPrice;
    }

    public function updatedPickup($pickup){
        if ($this->pickup == PICKUPANY) $this->pickupAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatedDropoff($dropoff){
        if ($this->dropoff == DROPOFFANY) $this->dropoffAny =  $this->pickupdropoffs->first()->name;
    }

    public function bookTicket(){

        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $this->emit('scroll-to-error'); // or dispatch browser event here
            }
        })->validate();
        
        //set value for pickup and dropoff
        if ($this->pickup == PICKUPDONTUSESERVICE) $this->pickup = "";
        if ($this->pickup == PICKUPANY) $this->pickup = $this->pickupAny;
        if ($this->pickup == PICKUPANYOTHER) $this->pickup = $this->pickupAnyOther;

        if ($this->dropoff == DROPOFFDONTUSESERVICE) $this->dropoff = "";
        if ($this->dropoff == DROPOFFANY) $this->dropoff = $this->dropoffAny;
        if ($this->dropoff == DROPOFFANYOTHER) $this->dropoff = $this->dropoffAnyOther;

        $codeOrder = Order::generateCode();
        $codeDepart = $codeOrder.'-1';
        $codeReturn = $codeOrder.'-2';
        
        // MAKE A TRANSACTION TO ENSURE DATA CONSISTENCY
        DB::beginTransaction();

        try {
            //SAVE ORDER FIRST
            $order = Order::create([
                'code' => $codeOrder,
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
                'onlinePrice' => $this->onlinePrice, 
                'originalPrice' => $this->originalPrice,
                'couponAmount' => $this->couponAmount,
                'finalPrice' => $this->finalPrice,
                'bookingDate' => date('Y-m-d H:i:s'),
                'customerType' => 0,
                'status' => 0,
            ]);

            //SAVE first status of this order
            OrderStatus::create([
                'orderId' => intVal($order->id),
                'status' => 0,
                //'note' => $this->note,
                'changeDate' => date('Y-m-d H:i:s'),
                //'userId' => Auth::id(),
            ]);

            //SAVE first payment of this order
            OrderPayment::create([
                'orderId' => intVal($order->id),
                'paymentStatus' => NOTPAID,
                'changeDate' => date('Y-m-d H:i:s'),
            ]);

            //SAVE ORDER TICKET DEPART FIRST
            OrderTicket::create([
                'orderId' => intVal($order->id),
                'code' => $codeDepart,
                'rideId' => intVal($this->order_depart_rideId),
                'seatClassId' => intVal($this->order_depart_seatClassId),
                'price' => intVal($this->departPrice), 
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
                    'price' => intVal($this->returnPrice),
                    'type' => RETURNTICKET,
                    'status' => 0,
                ]);
            }
            
            DB::commit();
            // Redirect to payment page with booking ID parameter
            return redirect()->to('/payment/' . $order->code);
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
            } else {  // if coupon has greater than used time
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
        return view('livewire.frontend.homepage.proceed-booking');
    }
}
