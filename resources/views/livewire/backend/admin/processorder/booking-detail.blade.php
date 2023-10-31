<!---Booking detail--->
<div class="border rounded-3 overflow-hidden p-4 mt-3">
    <h4 class="select-departure-header mb-3" >
        {{ trans('messages.bookingdetail') }}
        <button class="btn bg_own_color" wire:click="$set('showModalUpdateOrder', true)">Update Order</button>
    </h4>

    <div class="row text-dark mb-2">
        <div class="col-md-4">
            <ul class="fs-5">
                <li>
                    {{ trans('backend.ordercode') }}: <strong>{{ $order->code }}</strong>
                </li>
                <li>
                    {{ trans('backend.bookingdate') }}: <strong>{{ date('F j, Y H:i:s', strtotime($order->bookingDate)) }}</strong>
                </li>
                <li>
                    {{ trans('backend.totalprice') }}: <strong><span class="order_amount">฿</span>{{ round($order->finalPrice) }}</strong>
                </li>
                <li>
                    {{ trans('backend.triptype') }}: <strong>{{ TRIPTYPE[$order->isReturn] }}</strong>
                </li>
                <li>
                    {{ trans('backend.customerType') }}: <strong>{{ $order->customerType > 0 ? $order->customerTypeName : 'Online'}}</strong>
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <ul class="fs-5">
                <li>
                    {{ trans('backend.fullname') }}: <strong>{{ $order->fullname }}</strong>
                </li>
                <li>
                    {{ trans('backend.email') }}: <strong>{{ $order->email }}</strong>
                </li>
                <li>
                    {{ trans('backend.phone') }}: <strong>{{ $order->phone }}</strong>
                </li>
                <li>
                    {{ trans('backend.orderstatus') }}: <strong>{{ ORDERSTATUS[$order->status] }}</strong>
                </li>
                <li>
                    {{ trans('backend.agent') }}: <strong>{{ $order->agentName }}</strong>
                </li>
            </ul>
        </div>
        <div class="col-md-4">
            <ul class="fs-5">
                <li>
                    {{ trans('backend.adults') }}: <strong>{{ $order->adultQuantity }}</strong>
                </li>
                <li>
                    {{ trans('backend.children') }}: <strong>{{ $order->childrenQuantity }}</strong>
                </li>
                <li>
                    {{ trans('backend.pickup') }}: <strong>{{ $order->pickup }}</strong>
                </li>	
                <li>
                    {{ trans('backend.dropoff') }}: <strong>{{ $order->dropoff }}</strong>
                </li>
                <li>
                    {{ trans('backend.note') }}: <strong>{{ $order->note }}</strong>
                </li>
            </ul>
        </div>
    </div>

    <section class="">                    
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th class="product-name">{{ trans('messages.product') }}</th>
                    <th class="product-seat">{{ trans('messages.seatclass') }}</th>
                    <th class="product-total">{{ trans('messages.total') }}</th>
                    <th class="product-total">{{ trans('backend.status') }}</th>
                    <th class="product-action">{{ trans('messages.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderTickets as $orderTicket)
                    @if ($orderTicket->type == DEPARTURETICKET)
                        <tr class="order_item">
                            <td class="product-name">
                                {!! trans('messages.detailorder', ['fromlocation' => $orderTicket->fromLocationName, 
                                                                'tolocation' => $orderTicket->toLocationName, 
                                                                'ride' => $orderTicket->name,
                                                                'departdate' => date('F j, Y', strtotime($orderTicket->departDate)),
                                                                'departtime' =>  $orderTicket->departTime]) !!}
                                <br>{{ trans('messages.people') }}: {{ $order->adultQuantity + $order->childrenQuantity }} <strong class="product-quantity">×{{ $order->adultQuantity + $order->childrenQuantity }}</strong>
                                @if($order->status == CONFIRMEDORDER)
                                    <button class="btn bg_own_color text-light" wire:click="downloadBoardingPass({{ $orderTicket->id }})">Download Boarding Pass</button>
                                @endif
                            </td>
                            <td class="product-total">
                                <span class="product-seatclass">
                                    <span>{{ $orderTicket->seatClassName }}</span>
                                </span>
                            </td>
                            <td class="product-total">
                                <span class="amount">
                                    <span>฿</span>{{ round($orderTicket->price) }}
                                </span>
                            </td>
                            <td class="product-status">
                                    <span>{{ TICKETSTATUS[$orderTicket->status] }}</span>
                            </td>
                        </tr>
                    @endif
                    @if ($orderTicket->type == RETURNTICKET)
                        <tr class="order_item">
                            <td class="">
                                {!! trans('messages.detailorder', ['fromlocation' => $orderTicket->fromLocationName, 
                                                                'tolocation' => $orderTicket->toLocationName, 
                                                                'ride' => $orderTicket->name,
                                                                'departdate' => date('F j, Y', strtotime($orderTicket->departDate)),
                                                                'departtime' =>  $orderTicket->departTime]) !!}
                                <br>{{ trans('messages.people') }}: {{ $order->adultQuantity + $order->childrenQuantity }} <strong class="product-quantity">×{{ $order->adultQuantity + $order->childrenQuantity }}</strong>
                                @if($order->status == CONFIRMEDORDER)
                                    <button class="btn bg_own_color text-light" wire:click="downloadBoardingPass({{ $orderTicket->id }})">Download Boarding Pass</button>
                                @endif
                            </td>
                            <td class="product-total">
                                <span class="product-seatclass">
                                    <span>{{ $orderTicket->seatClassName }}</span>
                                </span>
                            </td>
            
                            <td class="product-total">
                                <span class="amount">฿</span>{{ round($orderTicket->price) }}
                            </td>
                            <td class="product-status">
                                <span>{{ TICKETSTATUS[$orderTicket->status] }}</span>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th scope="row">{{ trans('messages.subtotal') }}</th>
                    <td><span class="amount">฿{{ round($order->originalPrice) }}</span></td>
                </tr>
            @if ($order->couponAmount)
                <tr>
                    <th></th>
                    <th class="bg-secondary" scope="row">{{ trans('messages.couponprice') }} ({{ trans('messages.couponcode') }}: {{ $order->promotionCode }} - {{ $order->discount * 100 }}%)</th>
                    <td class="bg-secondary"><span class="amount">฿{{ round($order->couponAmount) }}</span></td>
                </tr>
            @endif
                <tr>
                    <th></th>
                    <th scope="row">{{ trans('messages.total') }}:</th>
                    <td><span class="amount">฿{{ round($order->finalPrice) }}</span></td>
                </tr>
            </tfoot>
        </table>
    </section>
</div>