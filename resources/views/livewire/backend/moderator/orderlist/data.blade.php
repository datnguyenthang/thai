<table class="table datatable-table table-secondary table-striped mt-3">
    <thead>
        <tr>
            <th>{{ trans('backend.orderid') }}</th>
            <th>{{ trans('backend.ordercode') }}</th>
            <th>{{ trans('backend.triptype') }}</th>
            <!--
            <th>{{ trans('backend.codeticketdepart') }}</th>
            <th>{{ trans('backend.codeticketreturn') }}</th>
            -->
            <th>{{ trans('backend.customertype') }}</th>
            <th>{{ trans('backend.agentname') }}</th>
            <th>{{ trans('backend.bookingdate') }}</th>
            <th>{{ trans('backend.totalprice') }}</th>
            <th>{{ trans('backend.orderstatus') }}</th>
            
            <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($orderLists))
            @foreach ($orderLists as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->code }}</td>
                    <td>{{ TRIPTYPE[$order->isReturn] }}</td>
                    <!--
                    @foreach ($order->orderTickets as $orderTicket)
                        @if($order->isReturn == ONEWAY)
                            @if($orderTicket->type == DEPARTURETICKET) 
                                <td> {{ $orderTicket->code }} </td>
                                <td> - </td> 
                            @endif
                        @endif
                        @if($order->isReturn == ROUNDTRIP)
                            @if($orderTicket->type == DEPARTURETICKET) <td> {{ $orderTicket->code }} </td>@endif
                            @if($orderTicket->type == RETURNTICKET) <td> {{ $orderTicket->code }} </td> @endif
                        @endif
                        
                    @endforeach
                    -->
                    <td>{{ $order->customerTypeName ? $order->customerTypeName : 'ONLINE' }}</td>
                    <td>{{ $order->agentName }}</td>
                    <td>{{ $order->bookingDate }}</td>
                    <td>{{ round($order->finalPrice) }}</td>
                    <td>{{ ORDERSTATUS[$order->status] }}</td>
                    <td>
                        <!--<button class="btn btn-info" wire:click="detail({{ $order->orderTicketId }})"></button>-->
                        <button class="call-btn btn btn-success btn-floating btn-sm"
                            wire:click="viewOrder({{ $order->id }})">
                                <!--<i class="fa fa-eye"></i>-->
                                {{ trans('backend.vieworder') }}
                        </button>
                        <a class="call-btn btn btn-warning btn-floating btn-sm"
                            href="/{{ auth()->user()->role}}processorder/{{ $order->id }}">
                                <!--<i class="fa fa-process"></i>-->
                                {{ trans('backend.processorder') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td>{{ trans('backend.noorderfound') }}</td>
            </tr>
        @endif
    </tbody>
</table>
<div>
    {{ $orderLists->links() }}
</div>