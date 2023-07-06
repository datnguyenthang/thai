<?php

namespace App\Http\Livewire\Backend\Manager;
use App\Http\Livewire\Backend\Moderator\ModeratorOrderlist;

use Livewire\Component;

class ManagerOrderlist extends Component
{
    public $moderatorOrderlist;

    public function mount()
    {
        $this->moderatorOrderlist = ModeratorOrderlist::class;
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-orderlist')
                    ->layout('manager.layouts.app');
    }
}
