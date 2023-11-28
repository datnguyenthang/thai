<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RidesExport implements FromCollection, WithHeadings, WithMapping
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
            'Price',
            'Pickup',
            'Dropoff',
            'Full Name',
            'Agent Name',
            'Customer Type',
            'Booking User',
            'Ticket Type',
            'Status',
            'Payment method',
            'Payment status'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->ridename,
            $row->fromLocationName,
            $row->toLocationName,
            $row->departTime,
            $row->returnTime,
            $row->departDate,
            $row->code,
            $row->phone,
            $row->email,
            $row->adultQuantity,
            $row->childrenQuantity,
            $row->price,
            $row->pickup,
            $row->dropoff,
            $row->fullname,
            $row->agentName,
            $row->customerType,
            $row->name,
            $row->ticket,
            $row->status,
            $row->paymentMethod,
            PAYMENTSTATUS[$row->paymentStatus],
        ];
    }
}
