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
                                    <a data-bs-toggle="pill" href="#credit-card" class="creditcard nav-link">
                                        <i class="fas fa-credit-card mr-2"></i> {{ trans('messages.creditcard') }}
                                    </a>
                                </li>
                            </ul>
                        </div> <!-- End -->
                        <!-- Credit card form content -->
                        <div class="tab-content">
                            <!-- credit card info-->
                                <div id="credit-card" class="tab-pane fade pt-3">
                                    <div class="form-group"> 
                                        <label for="username">
                                            <h6>{{ trans('messages.cardholdername') }}</h6>
                                        </label>
                                        <input type="text" class="form-control" wire:model="cardHolder" placeholder="{{ trans('messages.entercardholdername') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="cardNumber">
                                            <h6>{{ trans('messages.cardnumber') }}</h6>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" wire:model="cardNumber" placeholder="{{ trans('messages.enteryourcardnumber') }}" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text text-muted">
                                                    <i class="fab fa-cc-visa mx-1"></i>
                                                    <i class="fab fa-cc-mastercard mx-1"></i>
                                                    <i class="fab fa-cc-amex mx-1"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label>
                                                    <span class="hidden-xs">
                                                        <h6>{{ trans('messages.expirationdate') }}</h6>
                                                    </span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" wire:model="expirationDate" placeholder="MM/YY" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group mb-4">
                                                <label data-toggle="tooltip" title="Three digit CV code on the back of your card">
                                                    <h6>CVV <i class="fa fa-question-circle d-inline"></i></h6>
                                                </label>
                                                <input type="text" class="form-control" wire:model="cvv" placeholder="{{ trans('messages.entercvvcode') }}" required>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button wire:loading.attr="disabled" wire:click="payment({{ CARD }})" class="btn bg_own_color text-light">
                                            {{ trans('messages.submit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- End -->
                            <!-- Bank transfer info -->
                            <div id="bank-tranfer" class="tab-pane fade show active pt-3">
                                <div class="payment_box text-dark">
                                    {!! trans('messages.transferinfomation') !!}
                                </div>
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>{{ trans('messages.file') }}</th>
                                            <th>{{ trans('messages.filename') }}</th>
                                            <th>{{ trans('messages.dimensions') }}</th>
                                            <th>{{ trans('messages.extension') }}</th>
                                            <th>{{ trans('messages.action') }}</th>
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
                                                    <th class="action">
                                                        <button type="button" class="btn bg_own_color text-light" wire:loading.attr="disabled" wire:click="deleteProofs('{{ $photo['path'] }}')">
                                                            {{ trans('messages.delete') }}
                                                        </button>
                                                    </th>
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
                                                <input type="file" class="form-control" wire:model="proofFiles" id="{{ $counting }}-proofFiles" accept="image/*" multiple />
                                                @error('proofFiles.*') <span class="text-danger error">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" class="btn bg_own_color text-light" wire:loading.attr="disabled">{{ trans('messages.upload') }}</button>

                                                <button wire:loading.attr="disabled" wire:click="payment({{ BANKTRANSFER }})" class="btn bg_own_color text-light" @if (empty($photos)) disabled @endif>
                                                    {{ trans('messages.submit') }}
                                                </button>
                                            </div>
                                    </form>
                                </div>
                            </div> <!-- End -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
