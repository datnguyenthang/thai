<?php

namespace App\Http\Livewire\Frontend;

use Livewire\Component;
use App\Models\MenuItem;

class Menu extends Component
{
    public $menuItems = [];

    public function render() {
        $this->menuItems = MenuItem::whereNull('parent_id')->where('status', ACTIVE)->with('subMenus')->get();

        return view('livewire.frontend.menu');
    }
}
