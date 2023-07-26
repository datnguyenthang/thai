<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Pickupdropoff;

class ManagerCreatePickupDropoff extends Component
{
    public $pkdpId;
    public $name;
    public $status;

    public function mount($pkdpId = 0){
        $this->pkdpId = $pkdpId;        
        $this->status = 0;
        
        if ($pkdpId > 0) {
            $pkdp = Pickupdropoff::find($pkdpId);

            $this->name = $pkdp->name;
            $this->status = $pkdp->status;
        }
    }

    public function save(){
        $this->validate([
            'name' => 'required|unique:pickupdropoffs,name,' . $this->pkdpId,
        ]);

        if ($this->pkdpId > 0){ // update location

            $menu = Pickupdropoff::find($this->pkdpId);
            $menu->name = $this->name;
            $menu->status = intVal($this->status);
            $menu->save();

            session()->flash('success', 'Pickup/Dropoff location updated successfully!');
            
        } else { // create Promotion

            Pickupdropoff::create([
                'name' => $this->name,
                'status' => intVal($this->status)
            ]);

            session()->flash('success', 'Pickup/Dropoff location created successfully!');
        }

        return redirect()->route('managerPkdp');
    }
    public function render()
    {
        return view('livewire.backend.manager.manager-create-pickup-dropoff')
                    ->layout('manager.layouts.app');
    }
}
