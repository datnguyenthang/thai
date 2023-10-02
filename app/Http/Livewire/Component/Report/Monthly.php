<?php

namespace App\Http\Livewire\Component\Report;

use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrdersExport;

use App\Lib\OrderLib;
use App\Lib\DashboardLib;
use App\Lib\ChartLib;

use App\Models\Agent;
use App\Models\Location;

class Monthly extends Component {
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $revenues;
    public $orders;
    public $paxes;

    public $perPage = 10;

    public $fromDate;
    public $toDate;

    public $hasOrdered = true;

    public $fromLocation;
    public $toLocation;

    public $status = CONFIRMEDORDER;
    public $type = 'byMonth';
    public $selectType = 'month';

    public $locationList;

    public $orderDetail;
    public $showModal = false;

    public function mount(){
        $this->fromDate = $this->toDate = now()->format('Y-m');

        $this->locationList = Location::get()->where('status', ACTIVE);
        $this->agents = Agent::get();

        $this->revenues = ChartLib::revenueInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $this->orders = ChartLib::orderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $this->paxes = ChartLib::paxInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
    }

    public function updated(){
        $revenuesNewData = ChartLib::revenueInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $ordersNewData = ChartLib::orderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);
        $paxesNewData = ChartLib::paxInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->fromLocation, $this->toLocation);

        $this->emit('revenuesUpdated', ['revenuesNewData' => json_encode($revenuesNewData)]);
        $this->emit('ordersUpdated', ['ordersNewData' => json_encode($ordersNewData), 'paxesNewData' => json_encode($paxesNewData)]);
    }

    public function render() {
        list($type, $fromDate, $toDate) = ChartLib::dateFormat($this->type, $this->fromDate, $this->toDate);
        $listRides = DashboardLib::ridesInDay($fromDate, $toDate, $this->fromLocation, $this->toLocation, $this->hasOrdered, $this->perPage);

        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.monthly', compact('listRides'))->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.monthly', compact('listRides'))->layout('manager.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.report.monthly', compact('listRides'))->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}
