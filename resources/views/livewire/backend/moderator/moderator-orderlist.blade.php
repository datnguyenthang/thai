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
                <button class="btn bg_own_color text-light form-control" wire:click="filter">{{ trans('backend.applysearch') }}</button>
            </div>
        </div>
    </div>

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
            @if (!empty($orderList))
                @foreach ($orderList as $order)
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
                            <button class="call-btn btn btn-outline-success btn-floating btn-sm"
                                wire:click="viewOrder({{ $order->id }})">
                                    <!--<i class="fa fa-eye"></i>-->
                                    {{ trans('backend.vieworder') }}
                            </button>
                            <a class="call-btn btn btn-outline-warning btn-floating btn-sm"
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
        {{ $orderList->links() }}
    </div>

     {{--Show modal boostrap to quick view detail order--}}
    <div class="modal fade show" tabindex="-1" 
        style="display: @if($showModal === true) block @else none @endif;" role="dialog" wire:model="viewOrder">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('backend.orderdetail') }}</h5>
                    <button class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    
                    @if($showModal === true && $orderDetail)
                        <div class="row">
                            <div class="col-md-6">
                                <p>{{ trans('backend.ordercode') }}: {{ $orderDetail['code'] }}</p>
                                <p>{{ trans('backend.agentname') }}: {{ $orderDetail['agentName'] }}</p>
                                <p>{{ trans('backend.fullname') }}: {{ $orderDetail['fullname'] }}</p>
                                <p>{{ trans('backend.phone') }}: {{ $orderDetail['phone'] }}</p>
                                <p>{{ trans('backend.email') }}: {{ $orderDetail['email'] }}</p>
                                <p>{{ trans('backend.bookingdate') }}: {{ $orderDetail['bookingDate'] }}</p>
                                <p>{{ trans('backend.pickup') }}: {{ $orderDetail['pickup'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <!--<p>{{ trans('backend.id') }} : {{ $orderDetail['id'] }}</p>-->
                                <p>{{ trans('backend.id') }} : {{ TRIPTYPE[$orderDetail['isReturn']] }}</p>
                                <p>{{ trans('backend.customerType') }}: {{ $orderDetail->customerTypeName ? $orderDetail->customerTypeName : 'ONLINE' }}</p>
                                <p>{{ trans('backend.adults') }}: {{ $orderDetail['adultQuantity'] }}</p>
                                <p>{{ trans('backend.children') }}: {{ $orderDetail['childrenQuantity'] }}</p>
                                <p>{{ trans('backend.note') }}: {{ $orderDetail['note'] }}</p>
                                <p>{{ trans('backend.orderstatus') }}: {{ ORDERSTATUS[$orderDetail->status] }}</p>
                                <p>{{ trans('backend.dropoff') }}: {{ $orderDetail['dropoff'] }}</p>
                            </div>
                        </div>

                        <!-- Other details -->
                        <div class="row">
                            @foreach ($orderDetail->orderTickets as $orderTicket)
                                @if($orderDetail->isReturn == ONEWAY)
                                    @if($orderTicket->type == DEPARTURETICKET)
                                        <div class="col-md-6">
                                            <h5>{{ trans('backend.ticketdepart') }}</h5>
                                            <p>{{ trans('backend.ridename') }} : {{ $orderTicket['name'] }}</p>
                                            <p>{{ trans('backend.fromlocation') }} : {{ $orderTicket['fromLocationName'] }}</p>
                                            <p>{{ trans('backend.tolocation') }} : {{ $orderTicket['toLocationName'] }}</p>
                                            <p>{{ trans('backend.departtime') }} : {{ $orderTicket['departTime'] }}</p>
                                            <p>{{ trans('backend.returntime') }}: {{ $orderTicket['returnTime'] }}</p>
                                            <p>{{ trans('backend.departdate') }}: {{ $orderTicket['departDate'] }}</p>
                                            <button class="btn bg_own_color text-light"
                                                    wire:click="downnloadTicket({{$orderTicket->id}})"
                                                    wire:loading.attr="disabled">{{ trans('backend.download') }}</button>
                                        </div>
                                    @endif
                                @endif
                                
                                @if($orderDetail->isReturn == ROUNDTRIP)
                                    @if($orderTicket->type == DEPARTURETICKET)
                                        <div class="col-md-6">
                                            <h5>{{ trans('backend.ticketdepart') }}</h5>
                                            <p>{{ trans('backend.ridename') }} : {{ $orderTicket['name'] }}</p>
                                            <p>{{ trans('backend.fromlocation') }} : {{ $orderTicket['fromLocationName'] }}</p>
                                            <p>{{ trans('backend.tolocation') }} : {{ $orderTicket['toLocationName'] }}</p>
                                            <p>{{ trans('backend.departtime') }} : {{ $orderTicket['departTime'] }}</p>
                                            <p>{{ trans('backend.returntime') }}: {{ $orderTicket['returnTime'] }}</p>
                                            <p>{{ trans('backend.departdate') }}: {{ $orderTicket['departDate'] }}</p>
                                            <button class="btn bg_own_color text-light"
                                                    wire:click="downnloadTicket({{$orderTicket->id}})"
                                                    wire:loading.attr="disabled">{{ trans('backend.download') }}</button>
                                        </div>
                                    @endif

                                    @if($orderTicket->type == RETURNTICKET)
                                        <div class="col-md-6">
                                            <h5>{{ trans('backend.ticketreturn') }}</h5>
                                            <p>{{ trans('backend.ridename') }} : {{ $orderTicket['name'] }}</p>
                                            <p>{{ trans('backend.fromlocation') }} : {{ $orderTicket['fromLocationName'] }}</p>
                                            <p>{{ trans('backend.tolocation') }} : {{ $orderTicket['toLocationName'] }}</p>
                                            <p>{{ trans('backend.departtime') }} : {{ $orderTicket['departTime'] }}</p>
                                            <p>{{ trans('backend.returntime') }}: {{ $orderTicket['returnTime'] }}</p>
                                            <p>{{ trans('backend.departdate') }}: {{ $orderTicket['departDate'] }}</p>
                                            <button class="btn bg_own_color text-light"
                                                    wire:click="downnloadTicket({{$orderTicket->id}})"
                                                    wire:loading.attr="disabled">{{ trans('backend.download') }}</button>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
                  </div>
            </div>
        </div>
    </div>

    <!-- Let's also add the backdrop / overlay here -->
    <div class="modal-backdrop fade show" id="backdrop"
        style="display: @if($showModal === true) block @else none @endif;"></div>
    </div>
</div>
