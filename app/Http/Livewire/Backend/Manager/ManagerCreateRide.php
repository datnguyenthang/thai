<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;

class ManagerCreateRide extends Component
{
    protected $rules = [];

    public $rideId;
    public $name;
    public $fromLocation;
    public $toLocation;
    public $departTime;
    public $returnTime;
    public $departDate;
    public $hoursBeforeBooking = 0;
    public $colorCode;
    public $status;

    public $locations;

    public $nameClass;
    public $capacity;
    public $price;
    public $seatClasses = [];

    public function addSeatClass() {
        $this->seatClasses[] = [
            'nameClass' => $this->nameClass,
            'capacity' => $this->capacity,
            'price' => $this->price,
        ];

        $this->resetInputFields();
        $this->setValidationRules();
    }

    private function resetInputFields() {
        $this->nameClass = '';
        $this->capacity = '';
        $this->price = '';
    }

    public function removeSeatClass($index) {
        unset($this->seatClasses[$index]);
        $this->seatClasses = array_values($this->seatClasses);
    }

    protected function setValidationRules() {
        $this->rules = [
            'name' => 'required',
            'fromLocation' => 'required',
            'toLocation' => 'required|different:fromLocation',
            'departTime' => 'required',
            'returnTime' => 'required',
            'departDate' => 'required',
        ];

        //$this->rules['seatClasses'] = [];

        foreach ($this->seatClasses as $index => $seatClass) {
            $this->rules['seatClasses.'.$index.'.nameClass'] = 'required';
            $this->rules['seatClasses.'.$index.'.capacity'] = 'required|numeric|min:1';
            $this->rules['seatClasses.'.$index.'.price'] = 'required|numeric';

            // Here you can add any additional validation rules you need
        }
    }

    public function mount($rideId = 0) {
        $this->rideId = $rideId;
        $this->fromLocation = 1;
        $this->toLocation = 1;

        if ($rideId > 0) {
            $ride = Ride::find($rideId);

            $this->name = $ride->name;
            $this->fromLocation = $ride->fromLocation;
            $this->toLocation = $ride->toLocation;
            $this->departTime = $ride->departTime;
            $this->returnTime = $ride->returnTime;
            $this->departDate = $ride->departDate;
            $this->hoursBeforeBooking = $ride->hoursBeforeBooking;
            $this->colorCode = $ride->colorCode;
            $this->status = $ride->status;

            $this->seatClasses = SeatClass::select('id','rideId', 'name as nameClass', 'capacity', 'price')
                                    ->where('rideId', $rideId)->get()->toArray();
        } else $this->addSeatClass();

        $this->locations = Location::get();
        
    }

    public function save() {   
        $this->setValidationRules();
        //dd($this->rules);
        $this->validate();

        if ($this->rideId > 0){ // update ride

            $ride = Ride::find($this->rideId);
            $ride->name = $this->name;
            $ride->fromLocation = intVal($this->fromLocation);
            $ride->toLocation = intVal($this->toLocation);
            $ride->departTime = $this->departTime;
            $ride->returnTime = $this->returnTime;
            $ride->departDate = $this->departDate;
            $ride->hoursBeforeBooking = $this->hoursBeforeBooking;
            $ride->colorCode = $this->colorCode;
            $ride->status = intVal($this->status);
            $ride->save();
            
            //Delete all seat class
            SeatClass::where('rideId', $this->rideId)->delete();

            foreach ($this->seatClasses as $key => $val) {
                SeatClass::create([
                    'rideId' => $this->rideId,
                    'name' => $val['nameClass'],
                    'capacity' => intVal($val['capacity']),
                    'price' => intVal($val['price']),
                ]);
            }

            session()->flash('success', 'Ride updated successfully!');
            
        } else { // create new ride
            $ride = Ride::create([
                'name' => $this->name,
                'fromLocation' => intVal($this->fromLocation),
                'toLocation' => intVal($this->toLocation),
                'departTime' => $this->departTime,
                'returnTime' => $this->returnTime,
                'departDate' => $this->departDate,
                'hoursBeforeBooking' => $this->hoursBeforeBooking,
                'colorCode' => $this->colorCode,
                'status' => intVal($this->status),
            ]);

            foreach ($this->seatClasses as $key => $val) {
                SeatClass::create([
                    'rideId' => $ride->id,
                    'name' => $val['nameClass'],
                    'capacity' => intVal($val['capacity']),
                    'price' => intVal($val['price']),
                ]);
            }
            session()->flash('success', 'Ride created successfully!');
        }

        return redirect()->route('managerListRide');
    }

    public function render() {
        return view('livewire.backend.manager.manager-create-ride')
                ->layout('manager.layouts.app');
    }
}
