<div>
    {{ Breadcrumbs::render('payment') }}
    
    <!---Booking detail--->
    <div class="border rounded-3 overflow-hidden p-4 mt-3">
        <h4 class="select-departure-header mb-3" >{{ trans('messages.bookingdetail') }}</h4>
        
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
					{{ trans('messages.bookingtotal') }}  <strong><span class="order_amount">฿</span>{{ round($order->finalPrice) }}</strong>
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
                                        <span>฿</span>{{ round($orderTicket->price) }}
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
                                    <br>{{ trans('messages.people') }}: {{ $order->adultQuantity + $order->childrenQuantity }} <strong class="product-quantity">×{{ $order->adultQuantity + $order->childrenQuantity }}</strong>	</td>
                
                                <td class="product-total">
                                    <span class="amount">฿</span>{{ round($orderTicket->price) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="row">{{ trans('messages.subtotal') }}</th>
                        <td><span class="amount">฿{{ round($order->originalPrice) }}</span></td>
                    </tr>
                @if ($order->couponAmount)
                    <tr class="bg-secondary">
                        <th scope="row">{{ trans('messages.couponprice') }} ({{ trans('messages.couponcode') }}: {{ $order->promotionCode }} - {{ $order->discount * 100 }}%)</th>
                        <td><span class="amount">฿{{ round($order->couponAmount) }}</span></td>
                    </tr>
                @endif
                    <tr>
                        <th scope="row">{{ trans('messages.total') }}:</th>
                        <td><span class="amount">฿{{ round($order->finalPrice) }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </section>

        <!---PAYMENT--->
        <section class="">
            <h4 class="select-departure-header mb-3" >{{ trans('messages.payment') }}</h4>
            <div class="col-lg-12 mx-auto">
                <div class="card ">
                    <div class="card-header">
                        <div class="shadow-sm pt-4 pl-2 pr-2 pb-2">
                            <!-- Credit card form tabs -->
                            <ul role="tablist" class="nav bg-light nav-tabs nav-pills rounded nav-fill mb-3">
                                <li class="nav-item">
                                    <a data-bs-toggle="pill" href="#bank-tranfer" class="banktransfer nav-link active">
                                        <i class="fas fa-mobile-alt mr-2"></i> {{ trans('messages.banktranfer') }}
                                    </a> 
                                </li>
                                <li class="nav-item">
                                    <a data-bs-toggle="pill" href="#alipay" class="alipay nav-link">
                                        <i class="fab fa-alipay mr-2"></i> {{ trans('messages.alipay') }}
                                    </a> 
                                </li>
                                <li class="nav-item">
                                    <a data-bs-toggle="pill" href="#wechat" class="wechat nav-link">
                                        <i class="fab fa-wechat mr-2"></i> {{ trans('messages.wechat') }}
                                    </a> 
                                </li>
                                <li class="nav-item">
                                    <a data-bs-toggle="pill" href="#promptpay" class="promptpay nav-link">
                                        <i class="fab fa-promptpay mr-2"></i> {{ trans('messages.promptpay') }}
                                    </a> 
                                </li>  
                            </ul>
                        </div> <!-- End -->
                        <!-- Credit card form content -->
                        <div class="tab-content">
                            <!-- Bank transfer info -->
                            <div id="bank-tranfer" class="tab-pane fade show active pt-3">
                                <livewire:frontend.homepage.payment.banktransfer :orderId="$order->id" />
                            </div>

                            <!-- Alipay payment -->
                            <div id="alipay" class="tab-pane fade pt-3">
                                <livewire:frontend.homepage.payment.alipay :orderId="$order->id" />
                            </div>

                            <!-- Wechat payment -->
                            <div id="wechat" class="tab-pane fade pt-3">
                                <livewire:frontend.homepage.payment.wechat :orderId="$order->id" />
                            </div>

                            <!-- Promptpay payment -->
                            <div id="promptpay" class="tab-pane fade pt-3">
                                <livewire:frontend.homepage.payment.promptpay :orderId="$order->id" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
