<?php

namespace App\Http\Livewire\Component\Report;

use Livewire\Component;
use App\Lib\EmployeeLib;

use App\Models\Agent;
use App\Models\Location;

class Performance extends Component {
    public $fromDate;
    public $toDate;

    public $fromLocation;
    public $toLocation;

    public $status = CONFIRMEDORDER;
    public $locationList;

    public function mount(){
        //$this->fromDate = $this->toDate = now()->format('Y-m');

        $this->locationList = Location::get()->where('status', ACTIVE);
        $this->agents = Agent::get();
    }

    public function render() {
        $performances = EmployeeLib::performanceAll($this->fromDate, $this->toDate, $this->status);
        
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.performance', compact('performances'))->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.performance', compact('performances'))->layout('manager.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.report.performance', compact('performances'))->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
