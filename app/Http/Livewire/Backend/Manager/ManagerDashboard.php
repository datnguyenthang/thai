<?php

namespace App\Http\Livewire\Backend\Manager;
use App\Http\Livewire\Backend\Moderator\ModeratorDashboard;

use Livewire\Component;

class ManagerDashboard extends Component
{
    public $moderatorDashboard;

    public function mount()
    {
        $this->moderatorDashboard = ModeratorDashboard::class;
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-dashboard')
                    ->layout('manager.layouts.app');
    }
    
}
