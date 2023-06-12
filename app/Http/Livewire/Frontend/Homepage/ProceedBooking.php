<?php

namespace App\Http\Livewire\Frontend\Homepage;

use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Pickupdropoff;

class ProceedBooking extends Component
{
    public $tripType;
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
    public $subPrice;
    public $totalPrice;

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

    public $pickupdropoffs;

    protected $rules = [
        'firstName' => 'required',
        'lastName' => 'required',
        'phone' => 'numeric|digits_between:8,11',
        'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
    ];

    public function mount(Request $request) {
        $this->tripType = $request->input('tripType');
        $this->fromLocation = $request->input('fromLocation');
        $this->toLocation = $request->input('toLocation');
        $this->departureDate = $request->input('departureDate');
        $this->returnDate = $request->input('returnDate');
        $this->adults = $request->input('adults');
        $this->children = $request->input('children');

        $this->fromLocationName = Location::find($this->fromLocation)->name;
        $this->toLocationName = Location::find($this->toLocation)->name;

        $this->order_depart_rideId = $request->input('order_depart_rideId');
        $this->order_depart_seatClassId = $request->input('order_depart_seatClassId');
        $this->order_return_rideId = $request->input('order_return_rideId');
        $this->order_return_seatClassId = $request->input('order_return_seatClassId');

        $this->depart = Ride::find($this->order_depart_rideId);
        $this->seatDepart = SeatClass::find($this->order_depart_seatClassId);
        
        if ($this->tripType == ROUNDTRIP) {
            $this->return = Ride::find($this->order_return_rideId);
            $this->seatReturn = SeatClass::find($this->order_return_seatClassId);
            $this->returnPrice = $this->seatReturn->price * ($this->adults + $this->children);
        }

        $this->departPrice = $this->seatDepart->price * ($this->adults + $this->children);

        $this->pickupdropoffs = Pickupdropoff::get();
        $this->pickup = 0;
        $this->dropoff = 0;

        $this->subPrice = $this->departPrice + $this->returnPrice;
        $this->totalPrice = $this->departPrice + $this->returnPrice;
    }

    public function bookTicket(){
        //$this->validate();
        //$this->dispatchBrowserEvent('scroll-to-error');

        $this->withValidator(function ($validator) {
            if ($validator->fails()) {
                $this->emit('scroll-to-error'); // or dispatch browser event here
            }
        })->validate();
        
        //set value for pickup and dropoff
        if ($this->pickup == PICKUPDONTUSESERVICE) $this->dropoff = "";
        if ($this->pickup == PICKUPANY) $this->pickup = $this->pickupAny;
        if ($this->pickup == PICKUPANYOTHER) $this->pickup = $this->pickupAnyOther;

        if ($this->dropoff == DROPOFFDONTUSESERVICE) $this->dropoff = "";
        if ($this->dropoff == DROPOFFANY) $this->dropoff = $this->dropoffAny;
        if ($this->dropoff == DROPOFFANYOTHER) $this->dropoff = $this->dropoffAnyOther;

        $codeDepart = OrderTicket::generateCode();
        $codeReturn = OrderTicket::generateCode();

        $price = $this->totalPrice;
        
        // MAKE A TRANSACTION TO ENSURE DATA CONSISTENCY
        DB::beginTransaction();

        try {
            //SAVE ORDER FIRST
            $order = Order::create([
                'code' => Order::generateCode(),
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
                'price' => $this->totalPrice,
                'bookingDate' => date('Y-m-d H:i:s'),
                'status' => 0,
            ]);

            //SAVE ORDER TICKET DEPART FIRST
            OrderTicket::create([
                'orderId' => intVal($order->id),
                'code' => OrderTicket::generateCode(),
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
                    'code' => OrderTicket::generateCode(),
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

    public function render()
    {
        return view('livewire.frontend.homepage.proceed-booking');
    }
}
