<div>
    <h1>{{ trans('messages.listorder') }}</h1>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="orderId" class="form-control-label">{{ trans('messages.orderId') }}</label>
                <input wire:model="orderId" class="form-control" type="text" placeholder="{{ trans('messages.orderId') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('messages.trip') }}</label>
                <input wire:model="trip" class="form-control" type="text" placeholder="{{ trans('messages.trip') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('messages.customername') }}</label>
                <input wire:model="customername" class="form-control" type="text" placeholder="{{ trans('messages.customername') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('messages.customerphone') }}</label>
                <input wire:model="customerphone" class="form-control" type="text" placeholder="{{ trans('messages.customerphone') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('messages.orderStatus') }}</label>
                <input wire:model="orderStatus" class="form-control" type="text" placeholder="{{ trans('messages.orderStatus') }}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="startdate" class="form-control-label">{{ trans('messages.startdate') }}</label>
                <input wire:model="startDate" class="form-control" type="text" placeholder="{{ trans('messages.startdate') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="enddate" class="form-control-label">{{ trans('messages.enddate') }}</label>
                <input wire:model="endDate" class="form-control" type="text" placeholder="{{ trans('messages.enddate') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="agentname" class="form-control-label">{{ trans('messages.agentname') }}</label>
                <input wire:model="agentName" class="form-control" type="text" placeholder="{{ trans('messages.agentname') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('messages.customerphone') }}</label>
                <input wire:model="customerphone" class="form-control" type="text" placeholder="{{ trans('messages.customerphone') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('messages.orderStatus') }}</label>
                <input wire:model="orderStatus" class="form-control" type="text" placeholder="{{ trans('messages.orderStatus') }}">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="form-control-label"></label>
                <button class="btn btn-success form-control" wire:click="search">{{ trans('messages.applysearch') }}</button>
            </div>
        </div>
    </div>

    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('orderId')"><i class="fas fa-sort"></i>{{ trans('messages.orderid') }}</th>
                <th wire:click="sortBy('orderCode')"><i class="fas fa-sort"></i>{{ trans('messages.ordercode') }}</th>
                <th wire:click="sortBy('code')"><i class="fas fa-sort"></i>{{ trans('messages.codeticket') }}</th>
                <th wire:click="sortBy('customerType')"><i class="fas fa-sort"></i>{{ trans('messages.customertype') }}</th>
                <th wire:click="sortBy('agentName')"><i class="fas fa-sort"></i>{{ trans('messages.agentname') }}</th>
                <th wire:click="sortBy('bookingDate')"><i class="fas fa-sort"></i>{{ trans('messages.bookingdate') }}</th>
                <th wire:click="sortBy('bookingDate')"><i class="fas fa-sort"></i>{{ trans('messages.bookingdate') }}</th>
                <th wire:click="sortBy('isReturn')"><i class="fas fa-sort"></i>{{ trans('messages.isreturn') }}</th>
                <th wire:click="sortBy('totalPrice')"><i class="fas fa-sort"></i>{{ trans('messages.totalprice') }}</th>
                <th wire:click="sortBy('orderStatus')"><i class="fas fa-sort"></i>{{ trans('messages.orderstatus') }}</th>
                
                <th><i class="fas fa-tasks"></i>{{ trans('messages.action') }}</th>
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
                        <button class="btn btn-info" wire:click="detail({{ $order->orderTicketId }})">{{ trans('messages.viewdetail') }}</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{ $orderList->links() }}
    </div>
</div>
