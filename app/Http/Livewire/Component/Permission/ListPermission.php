<?php

namespace App\Http\Livewire\Component\Permission;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Permission;

class ListPermission extends Component
{
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
        $permissions = Permission::query()
                    ->when($this->search, function ($query) {
                        $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate($this->perPage);

        return view('livewire.component.permission.list-permission', compact('permissions'))->layout('admin.layouts.app');
    }
}
