<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RidesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $passengers;

    public function __construct($passengers)
    {
        $this->passengers = $passengers;
    }

    public function collection()
    {
        return $this->passengers;
    }

    public function headings(): array
    {
        return [
            'Ride ID',
            'Ride Name',
            'From Location',
            'To Location',
            'Depart Time',
            'Return Time',
            'Depart Date',
            'Order Code',
            'Phone',
            'Email',
            'Adult Quantity',
            'Children Quantity',
            'Pickup',
            'Dropoff',
            'Full Name',
            'Customer Type',
            'Booking User',
            'Ticket Type',
            'Status',
        ];
    }
}
