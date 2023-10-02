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
            <th>{{ trans('backend.username') }}</th>
        </tr>
    </thead>
    <tbody>
        @if (!empty($orderLists))
            @foreach ($orderLists as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->code }}</td>
                    <td>{{ TRIPTYPE[$order->isReturn] }}</td>
                    <td>{{ $order->customerTypeName ? $order->customerTypeName : 'ONLINE' }}</td>
                    <td>{{ $order->agentName }}</td>
                    <td>{{ $order->bookingDate }}</td>
                    <td>{{ round($order->finalPrice) }}</td>
                    <td>{{ ORDERSTATUS[$order->status] }}</td>
                    <td>{{ $order->username }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center text-danger" colspan="10">{{ trans('backend.noorderfound') }}</td>
            </tr>
        @endif
    </tbody>
</table>
<div>
    {{ $orderLists ? $orderLists->links() : '' }}
</div>