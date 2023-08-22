<?php

namespace App\Http\Livewire\Backend\Creator;
use App\Http\Livewire\Backend\Manager\ManagerCms;

use Livewire\Component;

class CreatorCms extends Component
{
    public $manageCms;

    public function mount() {
        $this->manageCms = ManagerCms::class;
    }

    public function render()
    {
        return view('livewire.backend.creator.creator-cms')
                    ->layout('creator.layouts.app');
    }
}
