<?php

namespace App\Http\Livewire\Backend\Viewer;


use Livewire\Component;
use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;

class ViewerRide extends Component
{
    public $search = '';
    public $perPage = 50;
    public $sortField = 'rides.name';
    public $sortDirection = 'asc';
    
    public $rideDetail;
    public $seatClasses;

    public $showModal = false;

    public function sortBy($field) {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function orderBy($field) {
        $this->rides = Ride::orderBy($field)->get();
    }

    public function viewRide($rideId) {
        $this->rideDetail = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 'tl.name as toLocation', 'rides.departTime', 'rides.returnTime', 'rides.departDate', 'rides.status')
                                ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                                ->where('rides.id', '=', $rideId)
                                ->first()->toArray();

        $this->seatClasses = SeatClass::where('rideId', $rideId)->get()->toArray();
        $this->showModal = true;
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
        return view('livewire.backend.viewer.viewer-ride', compact('rides'))
                    ->layout('viewer.layouts.app');
    }
}
