<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\MenuItem;
use MSA\LaravelGrapes\Models\Page;

class ManagerCreateMenu extends Component
{
    public $menuId;
    public $name;
    public $url;
    public $parent_id;
    public $status;

    public $menuList;
    public $pageList;

    public function mount($menuId = 0){
        $this->menuId = $menuId;        
        $this->status = 0;
        $this->menuList = MenuItem::get()->except($menuId);
        $this->pageList = Page::get();
        
        if ($menuId > 0) {
            $menu = MenuItem::find($menuId);

            $this->name = $menu->name;
            $this->url = $menu->url;
            $this->parent_id = $menu->parent_id;
            $this->status = $menu->status;
        }
    }

    public function getControllerMethodName($slug){
        $method = $slug === '/' ? 'home-page' : $slug;

        $slug_remove_dash = explode('-', $method);

        foreach ($slug_remove_dash as $key => $value) {
            $slug_remove_dash[$key] = ucwords($value);
        }

        return $method_name = implode($slug_remove_dash);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:menu_items,name,' . $this->menuId,
            'url' => 'required',
        ]);

        if ($this->menuId > 0){ // update location

            $menu = MenuItem::find($this->menuId);
            $menu->name = $this->name;
            $menu->url = $this->getControllerMethodName($this->url);
            $menu->parent_id = intVal($this->parent_id);
            $menu->status = intVal($this->status);
            $menu->save();

            session()->flash('success', 'Menu updated successfully!');
            
        } else { // create Promotion

            MenuItem::create([
                'name' => $this->name,
                'url' => $this->getControllerMethodName($this->url),
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
