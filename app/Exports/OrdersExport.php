<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders;
    }

    public function headings(): array
    {
        $headings =  [
            'Order Id',
            'Order Code',
            'Booking User',
            'Ticket Type',
            'Customer Type',
            'Agent',
            'Channel',
            'Full Name',
            'Phone',
            'Email',
            'Note',
            'Pickup',
            'Dropoff',
            'Adult Quantity',
            'Children Quantity',
            'Final Price',
            'Booking Date',
            'Booking Status',
            'Promotion Name',
            'Payment Status',
            'Payment Method',
            'Transaction Code',
            'Transaction Date',
        ];

        if (auth()->user()->role !== 'manager') {
            // Exclude the 'Final Price' column if the user is not a 'manager'
            $index = array_search('Final Price', $headings);
            if ($index !== false) {
                unset($headings[$index]);
            }
        }

        return $headings;
    }
    public function map($row): array
    {
        $paymentStatus = PAYMENTSTATUS[$row->paymentStatus] ?? '';
        $status = ORDERSTATUS[$row->status];
        $isReturn = TRIPTYPE[$row->isReturn];
        $channel = CHANNELSTATUS[$row->channel] ?? '';

        $fields = [
            $row->id,
            $row->code,
            $row->username,
            $isReturn,
            $row->customerTypeName,
            $row->agentName,
            $channel,
            $row->fullname,
            $row->phone,
            $row->email,
            $row->note,
            $row->pickup,
            $row->dropoff,
            $row->adultQuantity,
            $row->childrenQuantity,
            $row->finalPrice,
            $row->bookingDate,
            $status,
            $row->promotionName,
            $paymentStatus,
            $row->paymentMethod,
            $row->transactionCode,
            $row->transactionDate,
        ];

        if (auth()->user()->role !== 'manager') {
            // Exclude the 'finalPrice' column if the user is a 'manager'
            $finalPriceIndex = array_search($row->finalPrice, $fields);
            if ($finalPriceIndex !== false) {
                unset($fields[$finalPriceIndex]);
            }
        }
        
        return $fields;
    }
}
