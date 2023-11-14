<?php

namespace App\Http\Livewire\Component\Report;

use Livewire\Component;
use App\Lib\DebtLib;

class Debt extends Component
{
    public $type = 'byMonth';
    public $selectType = 'month';
    public $fromDate;
    public $toDate;

    public $agentDebts;

    public function mount(){
        $this->fromDate = $this->toDate = now()->format('Y-m');
        $this->agentDebts = DebtLib::debtByDate($this->fromDate, $this->toDate, $this->type);
    }

    public function updated(){
        $this->agentDebts = DebtLib::debtByDate($this->fromDate, $this->toDate, $this->type);
    }

    public function render(){
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.debt')->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.debt')->layout('manager.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.report.debt')->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
