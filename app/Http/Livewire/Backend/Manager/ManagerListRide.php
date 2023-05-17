<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;

class ManagerListRide extends Component
{
    public $search = '';
    public $perPage = 50;
    public $sortField = 'rides.name';
    public $sortDirection = 'asc';
    
    public $rideDetail;
    public $seatClasses;

    public $showModal = false;

    public function createRide($rideId = 0)
    {
        return redirect()->route('managerCreateRide', ['rideId' => $rideId]);
    }

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

    public function render()
    {
        $rides = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 'tl.name as toLocation', 'rides.departTime', 'rides.returnTime', 'rides.departDate', 'rides.status')
                      ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                      ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                      ->where('rides.name', 'like', '%'.$this->search.'%')
                      ->orWhere('fl.name', 'like', '%'.$this->search.'%')
                      ->orWhere('tl.name', 'like', '%'.$this->search.'%')
                      ->orderBy($this->sortField, $this->sortDirection)
                      ->paginate($this->perPage);
        
        return view('livewire.backend.manager.manager-list-ride', compact('rides'))
               ->layout('manager.layouts.app');
    }

    public function viewRide($rideId)
    {
        $this->rideDetail = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 'tl.name as toLocation', 'rides.departTime', 'rides.returnTime', 'rides.departDate', 'rides.status')
                                ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                                ->where('rides.id', '=', $rideId)
                                ->first()->toArray();

        $this->seatClasses = SeatClass::where('rideId', $rideId)->get()->toArray();

        $this->showModal = true;
    }

}
