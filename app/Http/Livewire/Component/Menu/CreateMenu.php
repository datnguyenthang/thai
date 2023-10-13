<?php

namespace App\Http\Livewire\Component\Menu;

use Livewire\Component;
use App\Models\MenuItem;
use MSA\LaravelGrapes\Models\Page;

class CreateMenu extends Component
{
    public $menuId;
    public $name;
    public $url;
    public $page_id;
    public $parent_id;
    public $isOpenNewTab;
    public $sortOrder;
    public $status;

    public $menuList;
    public $pageList;

    public function mount($menuId = 0){
        $this->menuId = $menuId;
        $this->sortOrder = 0;     
        $this->status = 0;
        
        $this->menuList = MenuItem::get()->except($menuId);

        $this->pageList = Page::get();

        if ($menuId > 0) {
            $menu = MenuItem::find($menuId);

            $this->name = $menu->name;
            $this->url = $menu->url;
            $this->page_id = $menu->page_id;
            $this->parent_id = $menu->parent_id;
            $this->isOpenNewTab = $menu->isOpenNewTab;
            $this->sortOrder = $menu->sortOrder;
            $this->status = $menu->status;
        }
    }

    public function save(){
        $this->validate([
            'name' => 'required|unique:menu_items,name,' . $this->menuId,
            'url' => 'required_without:page_id',
            'page_id' => 'nullable|numeric|required_without:url|unique:menu_items,page_id,'. $this->menuId,
        ]);

        if ($this->menuId > 0){ // update location

            $menu = MenuItem::find($this->menuId);
            $menu->name = $this->name;
            $menu->url = $this->url;
            $menu->page_id = intVal($this->page_id);
            $menu->parent_id = intVal($this->parent_id);
            $menu->isOpenNewTab = intVal($this->isOpenNewTab);
            $menu->sortOrder = intVal($this->sortOrder);
            $menu->status = intVal($this->status);
            $menu->save();

            session()->flash('success', 'Menu updated successfully!');
            
        } else { // create Promotion

            MenuItem::create([
                'name' => $this->name,
                'url' => $this->url,
                'page_id' => intVal($this->page_id),
                'parent_id' => isset($this->parent_id) ? intVal($this->parent_id) : null,
                'isOpenNewTab' => intVal($this->isOpenNewTab),
                'sortOrder' => intVal($this->sortOrder),
                'status' => intVal($this->status)
            ]);

            session()->flash('success', 'Menu created successfully!');
        }

        return redirect()->route('listMenu');
    }

    public function render(){
        $user = auth()->user();

        switch ($user->role) {
            case 'manager':
                return view('livewire.component.menu.create-menu')->layout('manager.layouts.app');
                break;
            case 'creator':
                return view('livewire.component.menu.create-menu')->layout('creator.layouts.app');
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
