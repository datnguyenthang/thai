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
            'Return Pickup',
            'Return Dropoff',
            'Depart Trip Name',
            'Depart Date',
            'Depart Price',
            'Return Trip Name',
            'Return Date',
            'Return Price',
            'Adult Quantity',
            'Children Quantity',
            'Total Price',
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

        foreach ($row->orderTickets as $ticket)
        if ($ticket->type == ONEWAY) {
            $row->departTripName = $ticket->name;
            $row->departDate = $ticket->departDate;
            $row->departPrice = $ticket->price;
            $row->pickup = $ticket->pickup;
            $row->dropoff = $ticket->dropoff;
        }
        if ($ticket->type == ROUNDTRIP) {
            $row->returnTripName = $ticket->name;
            $row->returnDate = $ticket->departDate;
            $row->returnPrice = $ticket->price;
            $row->returnPickup = $ticket->pickup;
            $row->returnDropoff = $ticket->dropoff;
        }
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
            $row->returnPickup,
            $row->returnDropoff,
            $row->departTripName,
            $row->departDate,
            $row->departPrice,
            $row->returnTripName,
            $row->returnDate,
            $row->returnPrice,
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
