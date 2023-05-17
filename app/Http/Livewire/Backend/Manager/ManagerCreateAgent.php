<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Agent;

class ManagerCreateAgent extends Component
{
    public $agentId;
    public $name;
    public $status;

    public function mount($agentId = 0)
    {
        $this->agentId = $agentId;

        if ($agentId > 0) {
            $agent = Agent::find($agentId);

            $this->name = $agent->name;
            $this->status = $agent->status;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:locations,name,' . $this->agentId,
        ]);

        if ($this->agentId > 0){ // update agent

            $agent = Agent::find($this->agentId);
            $agent->name = $this->name;
            $agent->status = intVal($this->status);
            $agent->save();

            session()->flash('success', 'Agent updated successfully!');
            
        } else { // create agent
           
            Agent::create([
                'name' => $this->name,
                'status' => intVal($this->status),
            ]);

            session()->flash('success', 'Agent created successfully!');
        }
        // Reset input fields
        $this->name = '';
        return redirect()->route('managerAgent');
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-create-agent')
                ->layout('manager.layouts.app');
    }
}
