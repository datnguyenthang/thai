<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Ride;
use App\Models\OrderPayment;
use App\Models\OrderStatus;
use App\Models\OrderTicket;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrderImporting implements ToModel, WithHeadingRow
{
    public function model(array $row){
        if (empty(array_filter($row))) {
            return null; // Skip empty row
        }
        return DB::transaction(function () use ($row) {
            // Check if a Ride with the same attributes exists
            $ride = Ride::where([
                'fromLocation' => $row['fromlocation'],
                'toLocation' => $row['tolocation'],
                'departDate' => Date::excelToDateTimeObject($row['departdate'])->format('Y-m-d'),
                'departTime' => $row['departtime'],
            ])->first();

            if (!$ride) {
                // Create a new Ride if not exists
                $ride = Ride::create([
                    'name' => $row['ridename'],
                    'fromLocation' => $row['fromlocation'],
                    'toLocation' => $row['tolocation'],
                    //'returnTime' => $row['returntime'],
                    'departDate' => Date::excelToDateTimeObject($row['departdate'])->format('Y-m-d'),
                    'departTime' => $row['departtime'],
                ]);
            }

            // Create an Order
            $order = Order::create([
                'code' => $row['code'],
                'customerType' => $row['customertype'],
                'userId' => $row['userid'],
                'isReturn' => $row['isreturn'],
                'agentId' => $row['agentid'],
                'promotionId' => $row['promotionid'],
                'firstName' => $row['firstname'],
                'lastName' => $row['lastname'],
                'phone' => $row['phone'],
                'email' => $row['email'],
                'note' => $row['note'],
                'pickup' => $row['pickup'],
                'dropoff' => $row['dropoff'],
                'adultQuantity' => $row['adultquantity'],
                'childrenQuantity' => $row['childrenquantity'],
                'onlinePrice' => 0,
                'originalPrice' => 0,
                'finalPrice' => $row['finalprice'],
                'bookingDate' => Date::excelToDateTimeObject($row['bookingdate'])->format('Y-m-d'),
            ]);

            // Create an OrderTicket
            $orderTicket = OrderTicket::create([
                'orderId' => $order->id, // Link to the created order
                'rideId' => $ride->id,
                'code' => $row['code'].'-1',
                'seatClassId' => 0,
                'type' => 1,
                'price' => $row['finalprice'],
            ]);

            // Create an OrderPayment
            $orderPayment = OrderPayment::create([
                'orderId' => $order->id, // Link to the created order
                'paymentMethod' => $row['paymentmethod'],
                'paymentStatus' => $row['paymentstatus'],
                'transactionCode' => $row['transactioncode'],
                'transactionDate' => $row['transactiondate'],
                'changeDate' => date('Y-m-d'), 
            ]);

            // Create an OrderStatus
            $orderStatus = OrderStatus::create([
                'orderId' => $order->id, // Link to the created order
                'status' => $row['status'],
                'changeDate' => date('Y-m-d'), 
            ]);

            //return $order;
        });
    }
}
