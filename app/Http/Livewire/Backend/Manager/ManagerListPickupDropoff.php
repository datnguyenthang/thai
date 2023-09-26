<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Pickupdropoff;

class ManagerListPickupDropoff extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $pkdpId;
    public $name;

    public function createPkdp($pkdpId = 0){
        return redirect()->route('managerCreatePkdp', ['pkdpId' => $pkdpId]);
    }

    public function sortBy($field){
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render() {
        $pkdps = Pickupdropoff::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.manager.manager-list-pickup-dropoff', compact('pkdps'))
                    ->layout('manager.layouts.app');
    }
}
