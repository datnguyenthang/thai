<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;

class ManagerDashboard extends Component
{
    public $page = 'managerDashboard';

    public function showDashboard()
    {
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-dashboard')
              ->layout('manager.layouts.app');
    }
    
}
