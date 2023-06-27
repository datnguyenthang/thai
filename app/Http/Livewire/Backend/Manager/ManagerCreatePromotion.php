<?php

namespace App\Http\Livewire\Backend\Manager;

use Livewire\Component;
use App\Models\Promotion;

class ManagerCreatePromotion extends Component
{
    public $promotionId;
    public $name;
    public $code;
    public $quantity;
    public $discount;
    public $fromDate;
    public $toDate;
    public $status;

    public function mount($promotionId = 0)
    {
        $this->promotionId = $promotionId;

        if ($promotionId > 0) {
            $promotion = Promotion::find($promotionId);

            $this->name = $promotion->name;
            $this->code = $promotion->code;
            $this->quantity = $promotion->quantity;
            $this->discount = $promotion->discount;
            $this->fromDate = $promotion->fromDate;
            $this->toDate = $promotion->toDate;
            $this->status = $promotion->status;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:promotions,name,' . $this->promotionId,
            'code' => 'required|unique:promotions,code,' . $this->promotionId,
            'quantity' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0|max:1',
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after:fromDate',
        ]);

        if ($this->promotionId > 0){ // update location

            $promotion = Promotion::find($this->promotionId);
            $promotion->name = $this->name;
            $promotion->code = $this->code;
            $promotion->quantity = intVal($this->quantity);
            $promotion->discount = floatVal($this->discount);
            $promotion->fromDate = $this->fromDate;
            $promotion->toDate = $this->toDate;
            $promotion->status = intVal($this->status);
            $promotion->save();

            session()->flash('success', 'Promotion updated successfully!');
            
        } else { // create Promotion

            Promotion::create([
                'name' => $this->name,
                'code' => $this->code,
                'quantity' => intVal($this->quantity),
                'discount' => floatVal($this->discount),
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
                'status' => intVal($this->status)
            ]);

            session()->flash('success', 'Promotion created successfully!');
        }
        // Reset input fields
        $this->name = '';
        return redirect()->route('managerPromotion');
    }

    public function render()
    {
        return view('livewire.backend.manager.manager-create-promotion')
                    ->layout('manager.layouts.app');
    }
}
