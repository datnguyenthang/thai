<div>
    <div class="border rounded-3 overflow-hidden p-4 mt-3">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="own_color" width="75" height="75"
                        fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>
                    {{ trans('messages.thankyou') }}
                </h2>
                <h5>{{ trans('messages.emailconfirm' , ['email' => $order->email]) }}</h5>
            </div>
        </div>
        <div class="payment text-dark mb-2">
            <p class="order fs-5">{{ trans('messages.bookingthanks') }}</p>
            <ul class="order_details fs-5">
				<li class="order">
					{{ trans('messages.bookingno') }}     <strong>{{ $order->code }}</strong>
				</li>
				<li class="order_date">
					{{ trans('messages.bookingdate') }}	  <strong>{{ date('F j, Y', strtotime($order->bookingDate)) }}</strong>
				</li>
				<li class="order_total">
					{{ trans('messages.bookingtotal') }}	      <strong><span class="order_amount">฿</span>{{ $order->price }}</strong>
				</li>		
			</ul>
        </div>
        <section class="">                    
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th class="product-name">{{ trans('messages.product') }}</th>
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
                                </td>
                                <td class="product-total">
                                    <span class="amount">
                                        <span>฿</span>{{ $orderTicket->price }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                        @if ($orderTicket->type == RETURNTICKET)
                            <tr class="order_item">
                                <td class="">
                                    {!! trans('messages.detailorder', ['fromlocation' => $orderTicket->toLocationName, 
                                                                    'tolocation' => $orderTicket->fromLocationName, 
                                                                    'ride' => $orderTicket->name,
                                                                    'departdate' => date('F j, Y', strtotime($orderTicket->departDate)),
                                                                    'departtime' =>  $orderTicket->departTime]) !!}
                                    <br>{{ trans('messages.people') }}: {{ $order->adultQuantity + $order->childrenQuantity }} <strong class="product-quantity">×{{ $order->adultQuantity + $order->childrenQuantity }}</strong>	</td>
                
                                <td class="product-total">
                                    <span class="amount">฿</span>{{ $orderTicket->price }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="row">{{ trans('messages.subtotal') }}</th>
                        <td><span class="amount">฿{{ $order->price }}</span></td>
                    </tr>
                    <tr>
                        <th scope="row">{{ trans('messages.total') }}:</th>
                        <td><span class="amount">฿{{ $order->price }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>
</div>
