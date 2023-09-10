<?php

namespace App\Http\Livewire\Backend\Viewer;

use Livewire\Component;
use App\Models\Agent;
use App\Models\CustomerType;

class ViewerAgent extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $agentId;
    public $name;

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
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        
        $customerType = CustomerType::pluck('name', 'id');

        return view('livewire.backend.viewer.viewer-agent', compact('agents', 'customerType'))
                    ->layout('viewer.layouts.app');
    }
}
