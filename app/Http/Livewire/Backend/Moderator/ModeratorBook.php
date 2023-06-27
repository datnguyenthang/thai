<?php

namespace App\Http\Livewire\Backend\Moderator;

use Livewire\Component;

class ModeratorBook extends Component
{
    public function render()
    {
        return view('livewire.backend.moderator.moderator-book')
                    ->layout('moderator.layouts.app');
    }
}
