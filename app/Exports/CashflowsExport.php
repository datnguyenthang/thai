<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashflowsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $headers;
    protected $cashflows;

    public function __construct($headers, $cashflows)
    {
        $this->headers = $headers;
        $this->cashflows = $cashflows;
    }

    public function collection()
    {
        return $this->cashflows;
    }

    public function headings(): array
    {
        return $this->headers;
    }
}
