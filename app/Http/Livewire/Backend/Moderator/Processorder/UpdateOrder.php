<?php

namespace App\Http\Livewire\Backend\Moderator\Processorder;

use Livewire\Component;

use App\Lib\OrderLib;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Pickupdropoff;

class UpdateOrder extends Component {
    public $orderId;
    public $order;
    public $tripType;

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

    public function mount($orderId){
        $this->orderId = $orderId;
        $this->order = OrderLib::getOrderDetail($orderId);
        $this->tripType = $this->order->isReturn;

        $this->pickupdropoffs = Pickupdropoff::get();
        $this->pickup = PICKUPANYOTHER;
        $this->pickupAnyOther = $this->order->orderTickets[0]->pickup;
        $this->dropoff = DROPOFFANYOTHER;
        $this->dropoffAnyOther = $this->order->orderTickets[0]->dropoff;

        if ($this->tripType == ROUNDTRIP) {
            $this->returnPickup = PICKUPANYOTHER;
            $this->returnPickupAnyOther = $this->order->orderTickets[1]->pickup;
            $this->returnDropoff = DROPOFFANYOTHER;
            $this->returnDropoffAnyOther = $this->order->orderTickets[1]->dropoff;
        }
    }

    public function updated(){
        $this->order = OrderLib::getOrderDetail($this->orderId);
    }

    public function closeModal(){
        $this->emitUp('closeModalUpdateOrder');
    }

    public function updatedPickup(){
        if ($this->pickup == PICKUPANY) $this->pickupAny = $this->pickupdropoffs->first()->name;
    }

    public function updatedDropoff(){
        if ($this->dropoff == DROPOFFANY) $this->dropoffAny = $this->pickupdropoffs->first()->name;
    }

    public function updatedReturnPickup($returnPickup){
        if ($this->returnPickup == PICKUPANY) $this->returnPickupAny = $this->pickupdropoffs->first()->name;
    }

    public function updatedReturnDropoff($returnDropoff){
        if ($this->returnDropoff == DROPOFFANY) $this->returnDropoffAny = $this->pickupdropoffs->first()->name;
    }


    public function save(){
        $this->validate([
            //'firstName' => 'required|max:255',
            //'lastName' => 'required|max:255',
            //'phone' => 'nullable|numeric|digits_between:8,12|required_without:email',
            //'email' => 'nullable|email|max:255||regex:/(.+)@(.+)\.(.+)/i|required_without:phone',
            'pickupAnyOther' => 'max:255|required_if:pickup,'.PICKUPANYOTHER,
            'dropoffAnyOther' => 'max:255|required_if:dropoff,'.DROPOFFANYOTHER,

            'returnPickupAnyOther' => 'max:255|required_if:returnPickup,'.PICKUPANYOTHER,
            'returnDropoffAnyOther' => 'max:255|required_if:returnDropoff,'.DROPOFFANYOTHER,
        ]);

        //set value for pickup and dropoff
        
        OrderTicket::where('id', $this->order->orderTickets[0]->id)
                    ->update([
                        'pickup' => $this->getPickup(),
                        'dropoff' => $this->getDropOff(),
                    ]);
        if ($this->tripType == ROUNDTRIP) {
            OrderTicket::where('id', $this->order->orderTickets[1]->id)
            ->update([
                'pickup' => $this->getReturnPickup(),
                'dropoff' => $this->getReturnDropOff(),
            ]);
        }
        //close odal update rder
        $this->closeModal();
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

    public function render(){
        return view('livewire.backend.moderator.processorder.update-order');
    }
}
