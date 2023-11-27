<?php

namespace App\Http\Livewire\Component\Role;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Role;

class ListRole extends Component {

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function sortBy($field) {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render() {
        $roles = Role::query()
                    ->when($this->search, function ($query) {
                        $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage);
        return view('livewire.component.role.list-role', compact('roles'))->layout('admin.layouts.app');
    }
}
