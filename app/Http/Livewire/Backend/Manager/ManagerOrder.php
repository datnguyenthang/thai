<?php

namespace App\Http\Livewire\Backend\Manager;
use App\Http\Livewire\Backend\Moderator\ModeratorOrder;

use Livewire\Component;

class ManagerOrder extends Component
{
    public $moderatorOrder;

    public function mount() {
        $this->moderatorOrder = ModeratorOrder::class;
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-order')
                    ->layout('manager.layouts.app');
    }
}
