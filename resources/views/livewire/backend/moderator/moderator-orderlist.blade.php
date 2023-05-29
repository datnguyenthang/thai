<div>
    <h1>{{ trans('backend.listorder') }}</h1>
    <div class="row">
        {{--
        <div class="col-md-2">
            <div class="form-group">
                <label for="orderid" class="form-control-label">{{ trans('backend.orderid') }}</label>
                <input wire:model="orderid" class="form-control" type="text" placeholder="">
            </div>
        </div>
        --}}
        
        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.ordercode') }}</label>
                <input wire:model.lazy="orderCode" class="form-control" type="text" placeholder="">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.codeticket') }}</label>
                <input wire:model.lazy="ticketCode" class="form-control" type="text" placeholder="">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="ridename" class="form-control-label">{{ trans('backend.ridename') }}</label>
                <input wire:model.lazy="rideName" class="form-control" type="text" placeholder="">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="customername" class="form-control-label">{{ trans('backend.customername') }}</label>
                <input wire:model.lazy="customerName" class="form-control" type="text" placeholder="">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="customerphone" class="form-control-label">{{ trans('backend.customerphone') }}</label>
                <input wire:model.lazy="customerPhone" class="form-control" type="text" placeholder="">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="bookingdate" class="form-control-label">{{ trans('backend.bookingdate') }}</label>
                <input wire:model.lazy="bookingDate" class="form-control" type="date" placeholder="">
            </div>
        </div>
{{--
        <div class="col-md-2">
            <div class="form-group">
                <label for="enddate" class="form-control-label">{{ trans('backend.enddate') }}</label>
                <input wire:model.lazy="endDate" class="form-control" type="date" placeholder="">
            </div>
        </div>
--}}
        <div class="col-md-2">
            <div class="form-group">
                <label for="agentId" class="form-control-label">{{ trans('backend.agentname') }}</label>
                <select id="agentId" name="agentId" class="form-select" wire:model.lazy="agentId" >
                    <option value=""></option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.orderstatus') }}</label>
                <select id="orderStatus" name="orderStatus" class="form-select" wire:model.lazy="orderStatus" >
                    <option value=""></option>
                    @foreach(ORDERSTATUS as $key=>$value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.customerType') }}</label>
                <select id="customerType" name="customerType" class="form-select" wire:model.lazy="customerType" >
                    <option value="-1"></option>
                    @foreach(CUSTOMERTYPE as $key=>$value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label class="form-control-label"></label>
                <button class="btn btn-success form-control" wire:click="filter">{{ trans('backend.applysearch') }}</button>
            </div>
        </div>
    </div>

    <table class="table datatable-table">
        <thead>
            <tr>
                <th wire:click="sortBy('orderId')"><i class="fas fa-sort"></i>{{ trans('backend.orderid') }}</th>
                <th wire:click="sortBy('orderCode')"><i class="fas fa-sort"></i>{{ trans('backend.ordercode') }}</th>
                <th wire:click="sortBy('ticketCode')"><i class="fas fa-sort"></i>{{ trans('backend.codeticket') }}</th>
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
            @if (!empty($orderList))
                @foreach ($orderList as $order)
                    <tr>
                        <td>{{ $order->orderTicketId }}</td>
                        <td>{{ $order->orderCode }}</td>
                        <td>{{ $order->ticketCode }}</td>
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
</div>
