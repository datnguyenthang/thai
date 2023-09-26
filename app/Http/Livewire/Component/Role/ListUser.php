<?php

namespace App\Http\Livewire\Component\Role;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Agent;

class ListUser extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function createUser($userId = 0) {
        return redirect()->route('createUser', ['userId' => $userId]);
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
        $user = auth()->user();

        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('email', 'like', '%'.$this->search.'%')
                      ->orWhere('role', 'like', '%'.$this->search.'%');
                if ($user->role == 'manager') $query->where('role', '>', MANAGER);
                if ($user->role == 'admin')   $query->where('role', '>', ADMIN);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $listAgent = Agent::pluck('name', 'id');

        switch ($user->role) {
            case 'admin':
                return view('livewire.component.role.list-user', compact('users', 'listAgent'))
                            ->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.role.list-user', compact('users', 'listAgent'))
                            ->layout('manager.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
