<?php

namespace App\Http\Livewire\Component\Agent;

use Livewire\Component;
use App\Models\Agent;
use App\Models\CustomerType;

class CreateAgent extends Component
{
    public $agentId;
    public $name;
    public $code;
    public $agentType = [];
    public $type;
    public $manager;
    public $email;
    public $phone;
    public $line;
    public $paymentType;
    public $status;

    public $customerType;
    
    public $role;

    public function mount($agentId = 0){
        $this->role = auth()->user()->role;

        $this->customerType = CustomerType::get()->where('status', 0);
        $this->type = $this->customerType->first()->id; 

        $this->agentId = $agentId;
        //$this->paymentType = 0;
        $this->status = 0;

        if ($agentId > 0) {
            $agent = Agent::find($agentId);

            $this->name = $agent->name;
            $this->code = $agent->code;
            $this->agentType = explode(',', $agent->agentType);
            $this->type = $agent->type;
            $this->manager = $agent->manager;
            $this->email = $agent->email;
            $this->phone = $agent->phone;
            $this->line = $agent->line;
            $this->paymentType = $agent->paymentType;
            $this->status = $agent->status;
        }
    }

    public function save(){
        $this->validate([
            'name' => 'required|unique:agents,name,' . $this->agentId,
            //'code' => 'required|unique:agents,code,' . $this->agentId,
            'agentType' => 'required',
            'type' => 'required',
            //'manager' => 'required',
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
            //'phone' => 'required|numeric|digits_between:8,11',
            //'paymentType' => 'required',
        ]);

        if ($this->agentId > 0){ // update agent

            $agent = Agent::find($this->agentId);
            $agent->name = $this->name;
            $agent->code = $this->code;
            $agent->agentType = implode(',', $this->agentType);
            $agent->type = intVal($this->type);
            $agent->manager = $this->manager;
            $agent->email = $this->email;
            $agent->phone = $this->phone;
            $agent->line = $this->line;
            $agent->paymentType = $this->paymentType ? intVal($this->paymentType) : null;
            $agent->status = intVal($this->status);
            $agent->save();

            session()->flash('success', 'Agent updated successfully!');
            
        } else { // create agent
            Agent::create([
                'name' => $this->name,
                'code' => $this->code,
                'agentType' => implode(',', $this->agentType),
                'type' => intVal($this->type),
                'manager' => $this->manager,
                'email' => $this->email,
                'phone' => $this->phone,
                'line' => $this->line,
                'paymentType' => $this->paymentType ? intVal($this->paymentType) : null,
                'status' => intVal($this->status),
            ]);

            session()->flash('success', 'Agent created successfully!');
        }
        // Reset input fields
        $this->name = '';
        return redirect()->route('agentList');
    }

    public function render(){
        switch ($this->role) {
            case 'manager':
                return view('livewire.component.agent.create-agent')->layout('manager.layouts.app');
                break;
            case 'moderator':
                return view('livewire.component.agent.create-agent')->layout('moderator.layouts.app');
                break;
            case 'agent':
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
