<div>
    {{-- Breadcrumbs::render('processpayment') --}}

    <!---Booking detail--->
    <div class="border rounded-3 overflow-hidden p-4 mt-3">
        <h4 class="select-departure-header mb-3" >{{ trans('messages.bookingdetail') }}</h4>
        
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
                        {{ trans('backend.customerType') }}: <strong>{{ $order->customerType ?  $order->customerTypeName : 'Online'}}</strong>
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
                        {{ trans('backend.agent') }}: <strong>{{ $order->agentId }}</strong>
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
                        {{ trans('backend.note') }}: <strong>{{ $order->dropoff }}</strong>
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
                                        <button class="btn bg_own_color text-light" wire:click="downloadTicket({{ $orderTicket->id }})">Download Ticket</button>
                                    @endif
                                </td>
                                <td class="product-total">
                                    <span class="product-seatclass">
                                        <span>{{ $orderTicket->seatClassName }}</span>
                                    </span>
                                </td>
                                <td class="product-total">
                                    <span class="amount">
                                        <span>฿</span>{{ round($orderTicket->seatPrice  * $order->adultQuantity) }}
                                    </span>
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
                                        <button class="btn bg_own_color text-light" wire:click="downloadTicket({{ $orderTicket->id }})">Download Ticket</button>
                                    @endif
                                </td>
                                <td class="product-total">
                                    <span class="product-seatclass">
                                        <span>{{ $orderTicket->seatClassName }}</span>
                                    </span>
                                </td>
                
                                <td class="product-total">
                                    <span class="amount">฿</span>{{ round($orderTicket->seatPrice * $order->adultQuantity) }}
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

    <div class="border rounded-3 overflow-hidden p-4 mt-3">
        <!---PAYMENT--->
        <section class="">
            <h4 class="select-departure-header mb-3" >{{ trans('messages.payment') }}</h4>
            <div class="col-lg-12 mx-auto">
                
                <!-- Bank transfer info -->
                @if($order->paymentMethod == BANKTRANSFER)
                    <div id="bank-tranfer" class="tab-pane fade show active pt-3">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>{{ trans('messages.file') }}</th>
                                    <th>{{ trans('messages.filename') }}</th>
                                    <th>{{ trans('messages.dimensions') }}</th>
                                    <th>{{ trans('messages.extension') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($photos)
                                    @foreach ($photos as $photo)
                                        <tr>
                                            <th class="file">
                                                <a href={{ $photo['url'] }} target="_blank">
                                                    <img src="{{ $photo['url'] }}" width="75" height="75" alt="Proof image" />
                                                </a>
                                            </th>
                                            <th class="filename">{{ $photo['name'] }}</th>
                                            <th class="dimensions">{{ $photo['dimension'] }}</th>
                                            <th class="extension">{{ $photo['extension'] }}</th>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>{{ trans('messages.nofile') }}</td>
                                    </tr>
                                @endif
                            <tbody>
                        </table>
                        <div class="card-footer text-center">
                            <form wire:submit.prevent="uploadProof" enctype="multipart/form-data">
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        
                                    </div>
                                    <div class="col-md-6">
                                        
                                    </div>
                            </form>
                        </div>
                    </div> <!-- End -->
                @endif
            </div>
        </section>
    </div>

    @if($order->status == UPPLOADTRANSFER)
        <div class="border rounded-3 overflow-hidden p-4 mt-3 mb-4">
            <h4 class="select-departure-header mb-3" >{{ trans('backend.review') }}</h4>
            <div class="col-lg-12 mx-auto">
                <div class="row">
                    <div class="col-lg-1 col-sm-2">
                    <div class="rdio rdio-primary">
                        <input type="radio" class="" id="approval" value="{{ CONFIRMEDORDER }}" name="approval" wire:model="confirmation">
                        <label for="approval" class="">{{ trans('backend.approval') }}</label>
                    </div>
                    </div>
                    <div class="col-lg-1 col-sm-2">
                    <div class="rdio rdio-primary">
                        <input type="radio" class="" id="decline" value="{{ DECLINEDORDER }}" name="decline" wire:model="confirmation">
                        <label for="decline" class="">{{ trans('backend.decline') }}</label>
                    </div>
                    </div>
                @if($confirmation == DECLINEDORDER)
                    <div class="col-lg-8 col-sm-8">
                        <label for="approval" class="">{{ trans('backend.reasondecline') }}</label>
                        @error('reasonDecline') <span class="text-danger error">{{ $message }}</span> @enderror
                        <textarea name="reasonDecline" cols="60" rows="5" wire:model="reasonDecline"></textarea>
                    </div>
                @endif
                    <div class="col-lg-2 col-sm-2">
                        <button type="submit" wire:click="save" class="btn text-light bg_own_color">{{ trans('backend.save') }}</button>
                    </div>
            </div>
        </div>
    @endif
</div>
