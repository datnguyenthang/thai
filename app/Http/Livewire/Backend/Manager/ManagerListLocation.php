<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Location;

class ManagerListLocation extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $locationId;
    public $name;

    public function createLocation($locationId = 0)
    {
        return redirect()->route('managerCreateLocation', ['locationId' => $locationId]);
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

    public function render()
    {
        $locations = Location::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
                      //->orWhere('status', 'like', '%'.$this->status.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.manager.manager-list-location', compact('locations'))
              ->layout('manager.layouts.app');
    }
}
