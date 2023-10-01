<?php

namespace App\Http\Livewire\Component\Report\Chart;

use Livewire\Component;
use App\Lib\ChartLib;

class Chart extends Component
{
    public $revenue;
    public $order;
    public $pax;

    public $fromDate;
    public $toDate;
    public $type = 'byMonth';
    public $status = CONFIRMEDORDER;
    public $depart;
    public $dest;

    public function render(){
        $revenues = ChartLib::revenueInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->depart, $this->dest);
        $orders = ChartLib::orderInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->depart, $this->dest);
        $paxes = ChartLib::paxInDate($this->fromDate, $this->toDate, $this->type, $this->status, $this->depart, $this->dest);

        return view('livewire.component.report.chart.chart', compact('revenues', 'orders', 'paxes'));
    }
}