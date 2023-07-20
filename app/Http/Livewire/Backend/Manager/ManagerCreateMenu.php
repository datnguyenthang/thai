<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\MenuItem;

class ManagerCreateMenu extends Component
{
    public $menuId;
    public $name;
    public $url;
    public $parent_id;
    public $status;

    public $menuList;

    public function mount($menuId = 0){
        $this->menuId = $menuId;        
        $this->status = 0;
        $this->menuList = MenuItem::get()->except($menuId);
        
        if ($menuId > 0) {
            $menu = MenuItem::find($menuId);

            $this->name = $menu->name;
            $this->url = $menu->url;
            $this->parent_id = $menu->parent_id;
            $this->status = $menu->status;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:menu_items,name,' . $this->menuId,
            'url' => 'required|url',
        ]);

        if ($this->menuId > 0){ // update location

            $menu = MenuItem::find($this->menuId);
            $menu->name = $this->name;
            $menu->url = $this->url;
            $menu->parent_id = intVal($this->parent_id);
            $menu->status = intVal($this->status);
            $menu->save();

            session()->flash('success', 'Menu updated successfully!');
            
        } else { // create Promotion

            MenuItem::create([
                'name' => $this->name,
                'url' => $this->url,
                'parent_id' => isset($this->parent_id) ? intVal($this->parent_id) : null,
                'status' => intVal($this->status)
            ]);

            session()->flash('success', 'Menu created successfully!');
        }

        return redirect()->route('managerMenu');
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-create-menu')
                    ->layout('manager.layouts.app');
    }
}
