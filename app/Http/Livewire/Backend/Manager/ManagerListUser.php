<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\User;
use App\Models\Agent;

class ManagerListUser extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function createUser($userId = 0)
    {
        return redirect()->route('managerCreateUser', ['userId' => $userId]);
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
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%')
                      ->orWhere('role', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $listAgent = Agent::pluck('name', 'id');
            
        return view('livewire.backend.manager.manager-list-user', compact('users', 'listAgent'))
                    ->layout('manager.layouts.app');
    }
}
