<?php

namespace App\Http\Livewire\Component\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

use App\Models\Agent;

class Profile extends Component
{
    public function render() {

        $user = auth()->user();
        $listAgent = Agent::pluck('name', 'id');

        switch ($user->role) {
            case 'admin':
                return view('livewire.component.user.profile', compact('user', 'listAgent'))->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.user.profile', compact('user', 'listAgent'))->layout('manager.layouts.app');
                break;
            case 'moderator':
                return view('livewire.component.user.profile', compact('user', 'listAgent'))->layout('moderator.layouts.app');
                break;
            case 'agent':
                return view('livewire.component.user.profile', compact('user', 'listAgent'))->layout('agent.layouts.app');
                break;
            case 'creator':
                return view('livewire.component.user.profile', compact('user', 'listAgent'))->layout('creator.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.user.profile', compact('user', 'listAgent'))->layout('viewer.layouts.app');
                break;
        }
    }
}
