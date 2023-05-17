<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Agent;

class ManagerListAgent extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $agentId;
    public $name;

    public function createAgent($agentId = 0)
    {
        return redirect()->route('managerCreateAgent', ['agentId' => $agentId]);
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
        $agents = Agent::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
                      //->orWhere('status', 'like', '%'.$this->status.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.manager.manager-list-agent', compact('agents'))
                ->layout('manager.layouts.app');
    }
}
