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
                                <li class="nav-item" wire:click="$set('tab', 'manual')">
                                    <a data-bs-toggle="pill" href="#bank-tranfer" class="banktransfer nav-link {{ $tab == 'manual' ? 'active' : '' }}">
                                        <i class="fas fa-mobile-alt mr-2"></i> {{ trans('messages.banktranfer') }}
                                    </a> 
                                </li>
                                <li class="nav-item" wire:click="$set('tab', 'omise')">
                                    <a data-bs-toggle="pill" href="#omisepay" class="omisepay nav-link {{ $tab == 'omise' ? 'active' : '' }}">
                                        <i class="fas fa-money-check-alt"></i> {{ trans('messages.paymentonline') }}
                                    </a> 
                                </li>
                            </ul>
                        </div> <!-- End -->
                        <!-- Credit card form content -->
                        <div class="tab-content">
                            <!-- Bank transfer info -->
                            <div id="bank-tranfer" class="tab-pane fade pt-3 {{ $tab == 'manual' ? 'show active' : '' }}">
                                <livewire:frontend.homepage.payment.banktransfer :orderId="$order->id" />
                            </div>
                            <div id="omisepay" class="tab-pane fade pt-3 {{ $tab == 'omise' ? 'show active' : '' }}">
                                <div class="payment_box text-dark">
                                    {!! trans('messages.omiseinfomation') !!}
                                </div>
                                <script type="text/javascript" src="https://cdn.omise.co/omise.js"></script>

                                @foreach ($paymentMethodList as $paymentMethod)
                                    @if($paymentMethod->name == CARD && $paymentMethod->status == ACTIVE)
                                        <livewire:frontend.homepage.payment.omisepay :orderId="$order->id" />
                                    @endif

                                    @if($paymentMethod->name == PROMPTPAY && $paymentMethod->status == ACTIVE)
                                        <livewire:frontend.homepage.payment.promptpay :orderId="$order->id" />
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @if($error)
        <div id="paymenterror" class="modal d-block" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error...</h5>
                    </div>
                    <div class="modal-body">
                        <p>{{ $errorMessage }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#paymenterror" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal-backdrop d-block" id="backdrop"></div>
    @endif
    <!-- Loading state-->
    @include('loading.loading')
</div>