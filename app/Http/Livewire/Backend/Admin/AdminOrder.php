<?php

namespace App\Http\Livewire\Backend\Admin;

use Livewire\Component;
use App\Http\Livewire\Backend\Moderator\ModeratorOrder;

class AdminOrder extends Component{

    public $moderatorOrder;

    public function mount() {
        $this->moderatorOrder = ModeratorOrder::class;
    }

    public function render() {
        return view('livewire.backend.admin.admin-order')
                    ->layout('admin.layouts.app');
    }
}
