<?php

namespace App\Http\Livewire\Backend\Creator;
use App\Http\Livewire\Backend\Manager\ManagerCreateCms;

use Livewire\Component;

class CreatorCreateCms extends Component
{
    public $creatorCreateCms;

    public function mount() {
        $this->creatorCreateCms = ManagerCreateCms::class;
    }

    public function render() {
        return view('livewire.backend.creator.creator-create-cms')
                    ->layout('creator.layouts.app');
    }
}
