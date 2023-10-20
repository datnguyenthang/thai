<?php

namespace App\Http\Livewire\Component\Report;

use Livewire\Component;
use Carbon\Carbon;
use App\Lib\EmployeeLib;

use App\Models\Agent;
use App\Models\Location;

class Performance extends Component {
    public $type = 'byMonth';
    public $fromDate;
    public $toDate;

    public $cpFromDate;
    public $cpToDate;

    public $pfmUsers;
    public $cpPfmUsers;

    public $chartDataOriginal;
    public $chartDataCompare;

    public $openChartList = [];
    public $openChart = false;

    public $userSelected;
    public $chartDataUser;

    public $status = CONFIRMEDORDER;

    protected $listeners = [
                            'updateDate' => 'updateDate',
                            'updateDateCompare' => 'updateDateCompare',
                        ];

    public function mount(){
        $this->fromDate = Carbon::now()->firstOfMonth()->formatLocalized('%Y-%m-%d');
        $this->toDate = Carbon::now()->formatLocalized('%Y-%m-%d');

        $this->pfmUsers = EmployeeLib::getListPerformanceAllUser($this->fromDate, $this->toDate, $this->status);
        $this->chartDataOriginal = EmployeeLib::performanceAllTime($this->fromDate, $this->toDate, 'byDay');
    }

    public function toggleChart($userId, $value){
        $this->userSelected = $this->pfmUsers->first(function ($item) use ($userId) {
            if ($item['id'] == $userId) {
                return $item;
            }
        });
        $this->chartDataOriginal = EmployeeLib::getPerformanceUser($userId, $this->fromDate, $this->toDate);
        $this->chartDataCompare = EmployeeLib::getPerformanceUser($userId, $this->cpFromDate, $this->cpToDate);
        $this->pfmUsers = EmployeeLib::getListPerformanceAllUser($this->fromDate, $this->toDate, $this->status);
        $this->emit('selectedUserEvent', [
            'chartDataOriginal' => json_encode($this->chartDataOriginal),
            'chartDataCompare' => json_encode($this->chartDataCompare), 
        ]);
    }

    function updateDate($start, $end){
        $this->fromDate = date('Y-m-d', strtotime($start));
        $this->toDate = date('Y-m-d', strtotime($end));
        $this->pfmUsers = EmployeeLib::getListPerformanceAllUser($this->fromDate, $this->toDate, $this->status);
        $this->chartDataOriginal = EmployeeLib::performanceAllTime($this->fromDate, $this->toDate, 'byDay');
        //$this->emit('chageDateRangeCompare', ['start' => $start, 'end' => $end]);
        $this->emit('updateDateRangeEvent', [
            'chartDataOriginal' => json_encode($this->chartDataOriginal),
            'fromDate' => $this->fromDate,
            'toDate' => $this->toDate,
        ]);
    }

    function updateDateCompare($start, $end, $distance){
        $this->cpFromDate = Carbon::parse($this->fromDate)->subMonth($distance)->format("Y-m-d H:i:s");
        $this->cpToDate = Carbon::parse($this->toDate)->subMonth($distance)->format("Y-m-d H:i:s");
        
        $this->cpPfmUsers = EmployeeLib::getListPerformanceAllUser($this->cpFromDate, $this->cpToDate);
        $this->pfmUsers = EmployeeLib::getListPerformanceAllUser($this->fromDate, $this->toDate, $this->status);

        foreach($this->pfmUsers as $key => $pfmUser) {
            foreach($this->cpPfmUsers as $keycp => $cpPfmUser) {

                if ($pfmUser->userId == $cpPfmUser->userId) {
                    $this->pfmUsers[$key]->cpTotalPrice = $pfmUser->totalPrice >= $cpPfmUser->totalPrice ? true : false;
                    $this->pfmUsers[$key]->percentTotalPrice = EmployeeLib::calPerDif($pfmUser->totalPrice, $cpPfmUser->totalPrice);
                    $this->pfmUsers[$key]->cpTotalOrder = $pfmUser->totalOrder >= $cpPfmUser->totalOrder ? true : false;
                    $this->pfmUsers[$key]->percentTotalOrder = EmployeeLib::calPerDif($pfmUser->totalOrder, $cpPfmUser->totalOrder);
                    break;
                }
            }
        }
        $this->chartDataOriginal = EmployeeLib::performanceAllTime($this->fromDate, $this->toDate, 'byDay');
        $this->chartDataCompare = EmployeeLib::performanceAllTime($this->cpFromDate, $this->cpToDate, 'byDay');
        $this->emit('updateCompareDateRangeEvent', [
            'chartDataOriginal' => json_encode($this->chartDataOriginal),
            'chartDataCompare' => json_encode($this->chartDataCompare)
        ]);
    }

    public function render() {
        
        $user = auth()->user();
        switch ($user->role) {
            case 'admin':
                return view('livewire.component.report.performance')->layout('admin.layouts.app');
                break;
            case 'manager':
                return view('livewire.component.report.performance')->layout('manager.layouts.app');
                break;
            case 'viewer':
                return view('livewire.component.report.performance')->layout('viewer.layouts.app');
                break;
            default:
                return <<<'blade'
                            <div><p>You do not have permission to access for this page.</p></div>
                        blade;
                break;
        }
    }
}