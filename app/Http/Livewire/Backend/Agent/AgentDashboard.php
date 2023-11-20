<?php

namespace App\Http\Livewire\Backend\Agent;

use Livewire\Component;

class AgentDashboard extends Component
{
    public function render()
    {
        return view('livewire.backend.agent.agent-dashboard')->layout('agent.layouts.app');
    }
}
