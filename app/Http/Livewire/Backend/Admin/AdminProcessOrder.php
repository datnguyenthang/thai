<?php

namespace App\Http\Livewire\Backend\Admin;
use App\Http\Livewire\Backend\Moderator\ModeratorProcessOrder;

use Livewire\Component;

class AdminProcessOrder extends Component
{
    public $moderatorProcessOrder;
    public $orderId;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
        $this->moderatorProcessOrder = ModeratorProcessOrder::class;
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-process-order')
                    ->layout('manager.layouts.app');
    }
}
