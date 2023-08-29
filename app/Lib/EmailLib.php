<?php

namespace App\Lib;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Lib\OrderLib;
use App\Lib\TicketLib;
use App\Mail\SendConfirmEticket;
use App\Mail\SendCancelationCustomerRequest;
use App\Mail\SendConfirmCompleteOrder;

class EmailLib {

    public static function sendMailConfirmOrderEticket($code){
        $order = OrderLib::getOrderDetailByCode($code);

        //If customer have no email and agent, return
        if (!$order->email && !$order->agentEmail) return false;

        foreach($order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $order->fullname;
            $orderTicket->pickup = $order->pickup;
            $orderTicket->dropoff = $order->dropoff;
            $orderTicket->code = $order->code;
            $orderTicket->adultQuantity = $order->adultQuantity;
            $orderTicket->childrenQuantity = $order->childrenQuantity;

            if ($order->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $order->discount);

            //$orderTicket->type == RETURNTICKET
            $fileName = $orderTicket->type == DEPARTURETICKET ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

            $locationFiles = TicketLib::getLocationFile($orderTicket->locationId);
            $pdfFiles[] = ['content' => TicketLib::generateEticket($orderTicket, $locationFiles), 'filename' => $fileName];
        }
        
        
        //if there are no email of customer, sending mail to agent instead.
        if (!$order->email) $order->email = $order->agentEmail;

        Mail::to($order->email)->send(new SendConfirmEticket($order, $pdfFiles));
    }

    public static function sendMailCancelOrder($code) {
        $order = OrderLib::getOrderDetailByCode($code);
        
        //if there are no email of customer, sending mail to agent instead.
        if (!$order->email) $order->email = $order->agentEmail;

        Mail::to($order->email)->send(new SendCancelationCustomerRequest($order));
    }

    public static function sendMailConfirmCompleteOrder($code) {
        $order = OrderLib::getOrderDetailByCode($code);

        foreach($order->orderTickets as $orderTicket) {
            $orderTicket->fullname = $order->fullname;
            $orderTicket->pickup = $order->pickup;
            $orderTicket->dropoff = $order->dropoff;
            $orderTicket->code = $order->code;
            $orderTicket->adultQuantity = $order->adultQuantity;
            $orderTicket->childrenQuantity = $order->childrenQuantity;

            if ($order->discount) $orderTicket->seatClassPrice =  $orderTicket->seatClassPrice - ($orderTicket->seatClassPrice * $order->discount);

            //$orderTicket->type == RETURNTICKET
            $fileName = $orderTicket->type == DEPARTURETICKET ? 'Departure Ticket.pdf' : 'Return Ticket.pdf';

            $locationFiles = TicketLib::getLocationFile($orderTicket->locationId);
            $pdfFiles[] = ['content' => TicketLib::generateEticket($orderTicket, $locationFiles), 'filename' => $fileName];
        }
        
        //if there are no email of customer, sending mail to agent instead.
        if (!$order->email) $order->email = $order->agentEmail;

        Mail::to($order->email)->send(new SendConfirmCompleteOrder($order, $pdfFiles));
    }

}
