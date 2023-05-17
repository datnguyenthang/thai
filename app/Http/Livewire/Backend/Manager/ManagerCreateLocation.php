<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Location;

class ManagerCreateLocation extends Component
{
    public $locationId;
    public $name;
    public $status;

    public function mount($locationId = 0)
    {
        $this->locationId = $locationId;

        if ($locationId > 0) {
            $location = Location::find($locationId);

            $this->name = $location->name;
            $this->status = $location->status;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:locations,name,' . $this->locationId,
        ]);

        if ($this->locationId > 0){ // update location

            $location = Location::find($this->locationId);
            $location->name = $this->name;
            $location->status = intVal($this->status);
            $location->save();

            session()->flash('success', 'Location updated successfully!');
            
        } else { // create location
           
            Location::create([
                'name' => $this->name,
                'status' => intVal($this->status),
            ]);

            session()->flash('success', 'Location created successfully!');
        }
        // Reset input fields
        $this->name = '';
        return redirect()->route('managerLocation');
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-create-location')
        ->layout('manager.layouts.app');
    }
}
