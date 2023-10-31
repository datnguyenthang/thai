<?php

namespace App\Http\Livewire\Backend\Admin;
use App\Http\Livewire\Backend\Moderator\ModeratorOrderlist;

use Livewire\Component;

class AdminOrderlist extends Component
{
    public $moderatorOrderlist;

    public function mount()
    {
        $this->moderatorOrderlist = ModeratorOrderlist::class;
    }

    public function render()
    {
        return view('livewire.backend.admin.admin-orderlist')
                    ->layout('admin.layouts.app');
    }
}
