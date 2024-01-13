<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;

class ManagerMassiveCreateRide extends Component
{
    protected $rules = [];

    public $name;
    public $fromLocation;
    public $toLocation;
    public $departTime;
    public $returnTime;
    public $hoursBeforeBooking = 0;
    public $colorCode;
    public $status;

    public $loopfrom;
    public $loopto;

    public $monday;
    public $tuesday;
    public $wednesday;
    public $thursday;
    public $friday;
    public $saturday;
    public $sunday;

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

        $this->locations = Location::get();
        $this->monday = $this->tuesday = $this->wednesday = $this->thursday = $this->friday = $this->saturday = $this->sunday = 1;

        $this->addSeatClass();
    }

    public function save() {
        $this->validate([
            'name' => 'required',
            'fromLocation' => 'required',
            'toLocation' => 'required|different:fromLocation',
            'departTime' => 'required',
            'returnTime' => 'required',
            'loopfrom' => 'required',
            'loopto' => 'required|different:loopfrom||after:loopfrom',
        ]);

        $weekday = [];
        if ($this->monday) array_push($weekday, 'Monday');
        if ($this->tuesday) array_push($weekday, 'Tuesday');
        if ($this->wednesday) array_push($weekday, 'Wednesday');
        if ($this->thursday) array_push($weekday, 'Thursday');
        if ($this->friday) array_push($weekday, 'Friday');
        if ($this->saturday) array_push($weekday, 'Saturday');
        if ($this->sunday) array_push($weekday, 'Sunday');

        // Massive create new Ride

        $begin = new \DateTime($this->loopfrom);
        $end = new \DateTime($this->loopto);

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $day) {
            if (in_array($day->format("l"), $weekday)) {
                $ride = Ride::create([
                    'name' => $this->name,
                    'fromLocation' => intVal($this->fromLocation),
                    'toLocation' => intVal($this->toLocation),
                    'departTime' => $this->departTime,
                    'returnTime' => $this->returnTime,
                    'departDate' => $day->format("Y-m-d"),
                    'hoursBeforeBooking' => intVal($this->hoursBeforeBooking),
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
            }
        }

        session()->flash('success', 'Ride Massive created successfully!');

        return redirect()->route('managerListRide');
    }

    public function render() {
        return view('livewire.backend.manager.manager-massive-create-ride')
                ->layout('manager.layouts.app');
    }
}
