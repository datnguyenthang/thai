<div>
    <h1>{{ trans('backend.listorder') }}</h1>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="orderid" class="form-control-label">{{ trans('backend.orderid') }}</label>
                <input wire:model="orderid" class="form-control" type="text" placeholder="{{ trans('backend.orderid') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.trip') }}</label>
                <input wire:model="trip" class="form-control" type="text" placeholder="{{ trans('backend.trip') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.customername') }}</label>
                <input wire:model="customername" class="form-control" type="text" placeholder="{{ trans('backend.customername') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.customerphone') }}</label>
                <input wire:model="customerphone" class="form-control" type="text" placeholder="{{ trans('backend.customerphone') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.orderstatus') }}</label>
                <input wire:model="orderStatus" class="form-control" type="text" placeholder="{{ trans('backend.orderstatus') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="bookingdate" class="form-control-label">{{ trans('backend.bookingdate') }}</label>
                <input wire:model="bookingdate" class="form-control" type="text" placeholder="{{ trans('backend.bookingdate') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="enddate" class="form-control-label">{{ trans('backend.enddate') }}</label>
                <input wire:model="endDate" class="form-control" type="text" placeholder="{{ trans('backend.enddate') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="agentname" class="form-control-label">{{ trans('backend.agentname') }}</label>
                <input wire:model="agentName" class="form-control" type="text" placeholder="{{ trans('backend.agentname') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.ordercode') }}</label>
                <input wire:model="ordercode" class="form-control" type="text" placeholder="{{ trans('backend.customerphone') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.codeticket') }}</label>
                <input wire:model="codeticket" class="form-control" type="text" placeholder="{{ trans('backend.codeticket') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="form-control-label"></label>
                <button class="btn btn-success form-control" wire:click="search">{{ trans('backend.applysearch') }}</button>
            </div>
        </div>
    </div>

    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('orderId')"><i class="fas fa-sort"></i>{{ trans('backend.orderid') }}</th>
                <th wire:click="sortBy('orderCode')"><i class="fas fa-sort"></i>{{ trans('backend.ordercode') }}</th>
                <th wire:click="sortBy('code')"><i class="fas fa-sort"></i>{{ trans('backend.codeticket') }}</th>
                <th wire:click="sortBy('customerType')"><i class="fas fa-sort"></i>{{ trans('backend.customertype') }}</th>
                <th wire:click="sortBy('agentName')"><i class="fas fa-sort"></i>{{ trans('backend.agentname') }}</th>
                <th wire:click="sortBy('bookingDate')"><i class="fas fa-sort"></i>{{ trans('backend.bookingdate') }}</th>
                <th wire:click="sortBy('bookingDate')"><i class="fas fa-sort"></i>{{ trans('backend.bookingdate') }}</th>
                <th wire:click="sortBy('isReturn')"><i class="fas fa-sort"></i>{{ trans('backend.triptype') }}</th>
                <th wire:click="sortBy('totalPrice')"><i class="fas fa-sort"></i>{{ trans('backend.totalprice') }}</th>
                <th wire:click="sortBy('orderStatus')"><i class="fas fa-sort"></i>{{ trans('backend.orderstatus') }}</th>
                
                <th><i class="fas fa-tasks"></i>{{ trans('backend.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderList as $order)
                <tr>
                    <td>{{ $order->orderTicketId }}</td>
                    <td>{{ $order->orderCode }}</td>
                    <td>{{ $order->code }}</td>
                    <td>{{ CUSTOMERTYPE[$order->customerType] }}</td>
                    <td>{{ $order->agentName }}</td>
                    <td>{{ $order->bookingDate }}</td>
                    <td>{{ $order->bookingDate }}</td>
                    <td>{{ $order->isReturn }}</td>
                    <td>{{ $order->totalPrice }}</td>
                    <td>{{ ORDERSTATUS[$order->orderStatus] }}</td>
                    <td>
                        <button class="btn btn-info" wire:click="detail({{ $order->orderTicketId }})">{{ trans('backend.viewdetail') }}</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $orderList->links() }}
    </div>
</div>
