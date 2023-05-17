<?php

namespace App\Http\Livewire\Backend\Moderator;

use Livewire\Component;

class ModeratorDashboard extends Component
{
    public $page = 'moderatorDashboard';

    public function showDashboard()
    {
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.backend.moderator.moderator-dashboard')
              ->layout('moderator.layouts.app');
    }
    
}
