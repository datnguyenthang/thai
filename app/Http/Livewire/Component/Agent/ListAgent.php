<?php

namespace App\Http\Livewire\Component\Agent;

use Livewire\Component;
use App\Models\Agent;
use App\Models\CustomerType;

class ListAgent extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $agentId;
    public $name;

    public $role;

    public function mount(){
        $this->role = auth()->user()->role;
    }

    public function createAgent($agentId = 0) {
        return redirect()->route('managerCreateAgent', ['agentId' => $agentId]);
    }

    public function sortBy($field) {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    public function render() {
        $agents = Agent::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
                      //->orWhere('status', 'like', '%'.$this->status.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        
        $customerType = CustomerType::pluck('name', 'id');
        
        switch ($this->role) {
            case 'manager':
                return view('livewire.component.agent.list-agent', compact('agents', 'customerType'))
                            ->layout('manager.layouts.app');
                break;
            case 'moderator':
                return view('livewire.component.agent.list-agent', compact('agents', 'customerType'))
                            ->layout('moderator.layouts.app');
                break;
            case 'agent':
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
