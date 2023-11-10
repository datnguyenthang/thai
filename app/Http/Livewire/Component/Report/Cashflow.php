<?php

namespace App\Http\Livewire\Component\Report;

use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CashflowsExport;
use App\Lib\ReportLib;
use Illuminate\Support\Str;

use App\Lib\AccountingLib;

class Cashflow extends Component {

    public $type = 'byMonth';
    public $selectType = 'month';
    public $fromDate;
    public $toDate;

    public $headerTables;
    public $cashflows;

    public function mount(){
        $this->fromDate = $this->toDate = now()->format('Y-m');
        $this->cashflows = AccountingLib::cashFlowByDate($this->fromDate, $this->toDate, $this->type);
        $this->headerTables = array_keys($this->cashflows->first()->getAttributes());
    }

    public function updated(){
        $this->cashflows = AccountingLib::cashFlowByDate($this->fromDate, $this->toDate, $this->type);
    }

    public function downloadCashflow(){
        $this->cashflows = AccountingLib::cashFlowByDate($this->fromDate, $this->toDate, $this->type);

        $export = new CashflowsExport($this->headerTables, $this->cashflows);

        return Excel::download($export, 'Cashflow.xlsx');
    }

    public function render(){
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.cashflow')->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.cashflow')->layout('manager.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.report.cashflow')->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
