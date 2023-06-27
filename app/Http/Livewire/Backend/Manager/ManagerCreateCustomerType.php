<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\CustomerType;

class ManagerCreateCustomerType extends Component
{
    public $customerTypeId;
    public $name;
    public $code;
    public $price;
    public $status;

    public function mount($customerTypeId = 0)
    {
        $this->customerTypeId = $customerTypeId;

        if ($customerTypeId > 0) {
            $customerType = CustomerType::find($customerTypeId);

            $this->name = $customerType->name;
            $this->code = $customerType->code;
            $this->price = $customerType->price;
            $this->status = $customerType->status;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:promotions,name,' . $this->customerTypeId,
            'code' => 'required|unique:promotions,code,' . $this->customerTypeId,
            'price' => 'required|numeric|min:0'
        ]);

        if ($this->customerTypeId > 0){ // update location

            $customerType = CustomerType::find($this->customerTypeId);
            $customerType->name = $this->name;
            $customerType->code = $this->code;
            $customerType->price = intVal($this->price);
            $customerType->status = intVal($this->status);
            $customerType->save();

            session()->flash('success', 'Customer Type updated successfully!');
            
        } else { // create Promotion

            CustomerType::create([
                'name' => $this->name,
                'code' => $this->code,
                'price' => intVal($this->price),
                'status' => intVal($this->status)
            ]);

            session()->flash('success', 'Customer Type created successfully!');
        }
        // Reset input fields
        $this->name = '';
        return redirect()->route('managerCustomerType');
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-create-customer-type')
                    ->layout('manager.layouts.app');
    }
}
