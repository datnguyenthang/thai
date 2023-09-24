<?php

namespace App\Http\Livewire\Backend\Moderator\Processorder;

use Livewire\Component;

use App\Models\Order;
use App\Models\Pickupdropoff;

class UpdateOrder extends Component {
    public $order;

    public $pickup;
    public $pickupAny;
    public $pickupAnyOther;
    public $dropoff;
    public $dropoffAny;
    public $dropoffAnyOther;

    public function mount($orderId, $pickup, $dropoff){
        $this->order = Order::findOrFail($orderId);

        $this->pickupdropoffs = Pickupdropoff::get();
        $this->pickup = PICKUPANYOTHER;
        $this->pickupAnyOther = $pickup;
        $this->dropoff = DROPOFFANYOTHER;
        $this->dropoffAnyOther = $dropoff;
    }

    public function closeModal(){
        $this->emitUp('closeModalUpdateOrder');
    }

    public function updatedPickup(){
        if ($this->pickup == PICKUPANY) $this->pickupAny =  $this->pickupdropoffs->first()->name;
    }

    public function updatedDropoff(){
        if ($this->dropoff == DROPOFFANY) $this->dropoffAny =  $this->pickupdropoffs->first()->name;
    }

    public function save(){
        $this->validate([
            //'firstName' => 'required|max:255',
            //'lastName' => 'required|max:255',
            //'phone' => 'nullable|numeric|digits_between:8,12|required_without:email',
            //'email' => 'nullable|email|max:255||regex:/(.+)@(.+)\.(.+)/i|required_without:phone',
            'pickupAnyOther' => 'max:255|required_if:pickup,'.PICKUPANYOTHER,
            'dropoffAnyOther' => 'max:255|required_if:dropoff,'.DROPOFFANYOTHER,
        ]);

        //set value for pickup and dropoff
        if ($this->pickup == PICKUPDONTUSESERVICE) $this->pickup = "";
        if ($this->pickup == PICKUPANY) $this->pickup = $this->pickupAny;
        if ($this->pickup == PICKUPANYOTHER) $this->pickup = $this->pickupAnyOther;

        if ($this->dropoff == DROPOFFDONTUSESERVICE) $this->dropoff = "";
        if ($this->dropoff == DROPOFFANY) $this->dropoff = $this->dropoffAny;
        if ($this->dropoff == DROPOFFANYOTHER) $this->dropoff = $this->dropoffAnyOther;

        $this->order->pickup = $this->pickup;
        $this->order->dropoff = $this->dropoff;
        $this->order->save();
        
        //close odal update rder
        $this->closeModal();
    }

    public function render(){
        return view('livewire.backend.moderator.processorder.update-order');
    }
}
