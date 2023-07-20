<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\MenuItem;

class ManagerListMenu extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $menuId;
    public $name;

    public $menuList;

    public function mount(){
        $this->menuList = MenuItem::pluck('name', 'id');
    }

    public function createMenu($menuId = 0){
        return redirect()->route('managerCreateMenu', ['menuId' => $menuId]);
    }

    public function sortBy($field){
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render(){
        $menus = MenuItem::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.manager.manager-list-menu', compact('menus'))
                    ->layout('manager.layouts.app');
    }
}
