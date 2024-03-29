<?php

namespace App\Http\Livewire\Backend\Admin;

use App\Imports\OrderImporting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Order;
use App\Models\OrderTicket;

class ImportOrder extends Component
{
    use WithFileUploads;

    public $file;

    public function import(){
        $this->validate([
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);

        //try {
            Excel::import(new OrderImporting, $this->file);
            $this->reset('file');

            session()->flash('success', 'Data imported successfully.');
        //} catch (\Exception $e) {
        //    session()->flash('error', 'An error occurred while importing data.');
        //}
    }

    public function migratePickupDropoff(){
        $orders = Order::get();

        foreach($orders as $order){
            OrderTicket::where('orderId', $order->id)
                    ->where('type', 1)
                    ->update([
                        'pickup' => $order->pickup,
                        'dropoff' => $order->dropoff,
                    ]);
        }
    }

    public function render() {
        return view('livewire.backend.admin.import-order')
                    ->layout('admin.layouts.app');
    }
}
