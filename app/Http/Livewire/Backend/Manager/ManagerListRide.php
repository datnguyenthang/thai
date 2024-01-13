<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Ride;
use App\Models\Location;
use App\Models\SeatClass;

class ManagerListRide extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $rideName;
    public $fromLocation;
    public $toLocation;
    public $fromDate;
    public $toDate;
    public $departTime;

    public $selectedAll;
    public $selected = [];

    protected $rides;
    public $perPage = 50;
    public $sortField = 'rides.departDate';
    public $sortDirection = 'desc';

    public $rideDetail;
    public $seatClasses;

    public $editMassRide;

    public $showModal = false;
    public $showModalMassEdit = false;

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

    public function resetSelected() {
        $this->selectedAll = false;
        $this->selected = [];
    }

    public function updatedSelected() {
        if (count($this->selected) == count($this->getRides())) $this->selectedAll = true;
        else $this->selectedAll = false;
    }

    public function updatedSelectedAll() {
        if ($this->selectedAll) {
            $this->selected = $this->getRides()->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function editMassRide($value){
        if ($value == 1) $this->showModalMassEdit = true;
    }

    public function viewRide($rideId) {
        $this->rideDetail = Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 'tl.name as toLocation', 'rides.departTime', 'rides.returnTime', 'rides.departDate', 'rides.colorCode', 'rides.status')
                                ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
                                ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
                                ->where('rides.id', '=', $rideId)
                                ->first()->toArray();

        $this->seatClasses = SeatClass::where('rideId', $rideId)->get()->toArray();

        $this->showModal = true;
    }

    protected function getRides() {
        return Ride::select('rides.id', 'rides.name', 'fl.name as fromLocation', 'tl.name as toLocation', 'rides.departTime', 'rides.returnTime', 'rides.departDate', 'rides.colorCode', 'rides.status')
            ->leftJoin('locations as fl', 'rides.fromLocation', '=', 'fl.id')
            ->leftJoin('locations as tl', 'rides.toLocation', '=', 'tl.id')
            ->where(function ($query) {
                if ($this->rideName) $query->where('rides.name', 'like', '%'.$this->rideName.'%');

                if ($this->fromLocation) $query->where('rides.fromLocation', $this->fromLocation);
                if ($this->toLocation) $query->where('rides.toLocation', $this->toLocation);
                
                if ($this->fromDate) $query->where('rides.departDate', '>=', $this->fromDate);
                if ($this->toDate) $query->where('rides.departDate', '<=', $this->toDate);
                if ($this->departTime) $query->where('rides.departTime', $this->departTime);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render(){
        $locationList = Location::get()->where('status', ACTIVE);

        return view('livewire.backend.manager.manager-list-ride', [ 'listRides' => $this->getRides(),
                                                                    'locationList' => $locationList
                                                                ])
                    ->layout('manager.layouts.app');
    }
}
