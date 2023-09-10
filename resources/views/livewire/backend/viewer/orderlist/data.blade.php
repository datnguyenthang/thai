<table class="table datatable-table table-secondary table-striped mt-3">
    <thead>
        <tr>
            <th>{{ trans('backend.orderid') }}</th>
            <th>{{ trans('backend.ordercode') }}</th>
            <th>{{ trans('backend.triptype') }}</th>
            <th>{{ trans('backend.customertype') }}</th>
            <th>{{ trans('backend.agentname') }}</th>
            <th>{{ trans('backend.bookingdate') }}</th>
            <th>{{ trans('backend.totalprice') }}</th>
            <th>{{ trans('backend.orderstatus') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($orderList))
            @foreach ($orderList as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->code }}</td>
                    <td>{{ TRIPTYPE[$order->isReturn] }}</td>
                    <td>{{ $order->customerTypeName ? $order->customerTypeName : 'ONLINE' }}</td>
                    <td>{{ $order->agentName }}</td>
                    <td>{{ $order->bookingDate }}</td>
                    <td>{{ round($order->finalPrice) }}</td>
                    <td>{{ ORDERSTATUS[$order->status] }}</td>
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
    {{ $orderList->links() }}
</div>