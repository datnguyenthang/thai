<div>
    <h1>{{ trans('backend.listorder') }}</h1>
    <div class="row">
        {{--
        <div class="col-md-2">
            <div class="form-group">
                <label for="orderid" class="form-control-label">{{ trans('backend.orderid') }}</label>
                <input wire:model.defer="orderid" class="form-control" type="text" placeholder="">
            </div>
        </div>
        --}}
        
        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.ordercode') }}</label>
                <input wire:model.defer="orderCode" class="form-control" type="text" placeholder="">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="trip" class="form-control-label">{{ trans('backend.codeticket') }}</label>
                <input wire:model.defer="ticketCode" class="form-control" type="text" placeholder="">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="ridename" class="form-control-label">{{ trans('backend.ridename') }}</label>
                <input wire:model.defer="rideName" class="form-control" type="text" placeholder="">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="customername" class="form-control-label">{{ trans('backend.customername') }}</label>
                <input wire:model.defer="customerName" class="form-control" type="text" placeholder="">
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="customerphone" class="form-control-label">{{ trans('backend.customerphone') }}</label>
                <input wire:model.defer="customerPhone" class="form-control" type="text" placeholder="">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="bookingdate" class="form-control-label">{{ trans('backend.bookingdate') }}</label>
                <input wire:model.defer="bookingDate" class="form-control" type="date" placeholder="">
            </div>
        </div>
{{--
        <div class="col-md-2">
            <div class="form-group">
                <label for="enddate" class="form-control-label">{{ trans('backend.enddate') }}</label>
                <input wire:model.defer="endDate" class="form-control" type="date" placeholder="">
            </div>
        </div>
--}}
        <div class="col-md-2">
            <div class="form-group">
                <label for="agentId" class="form-control-label">{{ trans('backend.agentname') }}</label>
                <select id="agentId" name="agentId" class="form-select" wire:model.defer="agentId" >
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
                <select id="orderStatus" name="orderStatus" class="form-select" wire:model.defer="orderStatus" >
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
                <select id="customerType" name="customerType" class="form-select" wire:model.defer="customerType" >
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
                        <td>{{ TRIPTYPE[$order->isReturn] }}</td>
                        <td>{{ $order->totalPrice }}</td>
                        <td>{{ ORDERSTATUS[$order->orderStatus] }}</td>
                        <td>
                            <!--<button class="btn btn-info" wire:click="detail({{ $order->orderTicketId }})"></button>-->
                            <button class="call-btn btn btn-outline-success btn-floating btn-sm"
                                wire:click="viewOrder({{ $order->orderId }})">
                                    <!--<i class="fa fa-eye"></i>-->
                                    {{ trans('backend.vieworder') }}
                            </button>
                            <button class="call-btn btn btn-outline-info btn-floating btn-sm"
                                wire:click="editOrder({{ $order->orderId }})">
                                    <!--<i class="fa fa-edit"></i>-->
                                    {{ trans('backend.editorder') }}
                            </button>
                            <button class="call-btn btn btn-outline-warning btn-floating btn-sm"
                                wire:click="processOrder({{ $order->orderId }})">
                                    <!--<i class="fa fa-process"></i>-->
                                    {{ trans('backend.processorder') }}
                            </button>
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

     {{--Show modal boostrap to quick view detail order--}}
    <div class="modal fade show" tabindex="-1" 
        style="display: @if($showModal === true) block @else none @endif;" role="dialog" wire:model="viewOrder">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('backend.orderdetail') }}</h5>
                    <button wire:click="$set('showModal', false)">{{ trans('backend.close') }}</button>
                </div>
                <div class="modal-body">
                    
                    @if($showModal === true && $orderDetail)
                        <p>{{ trans('backend.id') }} : {{ $orderDetail['orderId'] }}</p>
                        <p>{{ trans('backend.ridename') }} : {{ $orderDetail['rideName'] }}</p>
                        <p>{{ trans('backend.fromlocation') }} : {{ $orderDetail['fromLocationName'] }}</p>
                        <p>{{ trans('backend.tolocation') }} : {{ $orderDetail['toLocationName'] }}</p>
                        <p>{{ trans('backend.departtime') }} : {{ $orderDetail['departTime'] }}</p>
                        <p>{{ trans('backend.returntime') }}: {{ $orderDetail['returnTime'] }}</p>
                        <p>{{ trans('backend.departdate') }}: {{ $orderDetail['departDate'] }}</p>
                    <!-- Other details -->
                        <p>{{ trans('backend.ordercode') }}: {{ $orderDetail['orderCode'] }}</p>
                        <p>{{ trans('backend.agentname') }}: {{ $orderDetail['agentName'] }}</p>
                        <p>{{ trans('backend.fullname') }}: {{ $orderDetail['fullname'] }}</p>
                        <p>{{ trans('backend.phone') }}: {{ $orderDetail['phone'] }}</p>
                        <p>{{ trans('backend.email') }}: {{ $orderDetail['email'] }}</p>
                        <p>{{ trans('backend.bookingdate') }}: {{ $orderDetail['bookingDate'] }}</p>
                        <p>{{ trans('backend.note') }}: {{ $orderDetail['note'] }}</p>
                        <p>{{ trans('backend.adults') }}: {{ $orderDetail['adultQuantity'] }}</p>
                        <p>{{ trans('backend.children') }}: {{ $orderDetail['childrenQuantity'] }}</p>
                        <p>{{ trans('backend.customerType') }}: {{ CUSTOMERTYPE[$orderDetail->customerType] }}</p>
                        <p>{{ trans('backend.orderstatus') }}: {{ ORDERSTATUS[$orderDetail->orderStatus] }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;"></div>
    </div>
</div>
