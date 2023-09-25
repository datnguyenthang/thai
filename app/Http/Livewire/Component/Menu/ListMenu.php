<?php

namespace App\Http\Livewire\Component\Menu;

use Livewire\Component;
use App\Models\MenuItem;
use MSA\LaravelGrapes\Models\Page;

class ListMenu extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $menuId;
    public $name;

    public $menuList;
    public $pageList;

    public function mount(){
        $this->menuList = MenuItem::pluck('name', 'id');
        $this->pageList = Page::pluck('name', 'id');
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

    public function deleteMenu($menuId){
        $menu = MenuItem::findOrFail($menuId);
        $menu->delete();

        session()->flash('success', 'Menu deleted successfully!');
    }

    public function render(){
        $user = auth()->user();
        $menus = MenuItem::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        switch ($user->role) {
            case 'manager':
                return view('livewire.component.menu.list-menu', compact('menus'))->layout('manager.layouts.app');
                break;
            case 'creator':
                return view('livewire.component.menu.list-menu', compact('menus'))->layout('creator.layouts.app');
                break;
            case 'moderator':
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
            case 'agent':
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
