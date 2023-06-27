<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Promotion;

class ManagerListPromotion extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $promotionId;
    public $name;

    public function createPromotion($promotionId = 0)
    {
        return redirect()->route('managerCreatePromotion', ['promotionId' => $promotionId]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $promotions = Promotion::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('code', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.backend.manager.manager-list-promotion', compact('promotions'))
                ->layout('manager.layouts.app');
    }
}
