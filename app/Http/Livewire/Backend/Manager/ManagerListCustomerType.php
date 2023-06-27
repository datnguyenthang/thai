<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\CustomerType;

class ManagerListCustomerType extends Component
{
    public $search = '';
    public $perPage = 20;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public $customerTypeId;
    public $name;

    public function createCustomerType($customerTypeId = 0)
    {
        return redirect()->route('managerCreateCustomerType', ['customerTypeId' => $customerTypeId]);
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
        $customerTypes = CustomerType::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
                $query->orWhere('code', 'like', '%'.$this->status.'%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.manager.manager-list-customer-type', compact('customerTypes'))
                    ->layout('manager.layouts.app');
    }
}
