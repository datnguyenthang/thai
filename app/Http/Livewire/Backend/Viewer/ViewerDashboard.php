<?php

namespace App\Http\Livewire\Backend\Viewer;
use App\Http\Livewire\Backend\Moderator\ModeratorDashboard;

use Livewire\Component;

class ViewerDashboard extends Component
{
    public $moderatorDashboard;

    public function mount()
    {
        $this->moderatorDashboard = ModeratorDashboard::class;
    }

    public function render()
    {
        return view('livewire.backend.viewer.viewer-dashboard')
                    ->layout('viewer.layouts.app');
    }
}
