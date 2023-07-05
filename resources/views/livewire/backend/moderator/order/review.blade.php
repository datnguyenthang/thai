<div class="container">
    <div class="border rounded-3 overflow-hidden p-4 mt-3">
        <h4 class="select-departure-header mb-3" >{{ trans('messages.bookingdetail') }}</h4>
        <section class="">                    
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th class="product-name">{{ trans('messages.product') }}</th>
                        <th class="product-total">{{ trans('messages.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="order_item">
                        <td class="product-name">
                            {!! trans('messages.detailorder', ['fromlocation' => $fromLocationName, 
                                                              'tolocation' => $toLocationName, 
                                                              'ride' => isset($depart->name) ? $depart->name : '',
                                                              'departdate' => isset($depart->departDate) ? date('F j, Y', strtotime($depart->departDate)) : '',
                                                              'departtime' => isset($depart->departTime) ? $depart->departTime : '']) !!}
                            <br>{{ trans('messages.people') }}: {{ $adults + $children }} <strong class="product-quantity">×{{ $adults + $children }}</strong>
                        </td>
                        <td class="product-total">
                            <span class="amount">
                                <span>฿</span>{{ $departPrice  }}
                            </span>
                        </td>
                    </tr>
                    @if ($tripType == ROUNDTRIP)
                    <tr class="order_item">
                        <td class="">
                            {!! trans('messages.detailorder', ['fromlocation' => $toLocationName, 
                                                              'tolocation' => $fromLocationName, 
                                                              'ride' => isset($return->name) ? $return->name : '',
                                                              'departdate' => isset($return->departDate) ? date('F j, Y', strtotime($return->departDate)) : '',
                                                              'departtime' => isset($return->departTime) ? $return->departTime : '']) !!}
                            <br>{{ trans('messages.people') }}: {{ $adults + $children }} <strong class="product-quantity">×{{ $adults + $children }}</strong>	</td>
        
                        <td class="product-total">
                            <span class="amount">฿</span>{{ $returnPrice  }}
                        </td>
                    </tr>
                    @endif
                </tbody>
        
                <tfoot>
                    <tr>
                        <th scope="row">{{ trans('messages.subtotal') }}</th>
                        <td><span class="amount">฿{{ round($originalPrice) }}</span></td>
                    </tr>

                @if ($isValidCoupon)
                    <tr class="bg-secondary">
                        <th scope="row">{{ trans('messages.couponprice') }} ({{ trans('messages.couponcode') }}: {{ $coupon->code }} - {{ $coupon->discount * 100 }}%)</th>
                        <td><span class="amount">฿{{ round($couponAmount) }}</span><button class="float-end" wire:click="removeCoupon">x</button></td>
                    </tr>
                @endif

                    <tr>
                        <th scope="row">{{ trans('messages.total') }}:</th>
                        <td><span class="amount">฿{{ round($finalPrice) }}</span></td>
                    </tr>
                </tfoot>
            </table>
        </section>
    </div>

    <!---Payment--->
    <div class="row align-items-start d-flex">
        <div class="col-md-12 col-md-offset-1">
            <div class="border rounded-3 overflow-hidden p-4 mt-3 bg_own_color">
                <h2 class="select-departure-header mb-3" >{{ trans('backend.payment') }}</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <span class="form-label">{{ trans('backend.paymentmethodname') }}</span>
                            <select id="paymentMethod" name="paymentMethod" class="form-select" wire:model="paymentMethod">
                                @foreach($paymentMethodList as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                            @error('paymentMethod') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    @if($isTransaction)
                        <div class="col-md-4">
                            <div class="form-group">
                                <span class="form-label">{{ trans('backend.transactioncode') }}</span>
                                <input id="transactionCode" class="form-control" name="transactionCode" class="form-input" wire:model="transactionCode" />
                                @error('transactionCode') <span class="text-light error">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row align-items-start d-flex">
        <div class="col-md-12 col-md-offset-1">
            <div class="border rounded-3 overflow-hidden p-4 mt-3">
                <h2 class="select-departure-header mb-3" >{{ trans('backend.contactdetail') }}</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('messages.fullname') }}</span>
                            </div>
                            <p type="text" class="form-control">{{ $firstName }} {{ $lastName }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('messages.email') }}</span>
                            </div>
                            <p type="text" class="form-control mb-0">{{ $email }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('messages.phone') }}</span>
                            </div>
                            <p type="text" class="form-control mb-0">{{ $phone }}</p>
                        </div>
                    </div>
                </div>

                <!---Pickup & dropoff information--->
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('messages.pickupinfo') }}</span>
                            </div>
                            <p type="text" class="form-control mb-0">
                                @if($pickup == PICKUPANY) {{ $pickupAny }} @endif
                                @if($pickup == PICKUPANYOTHER) {{ $pickupAnyOther }} @endif
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{{ trans('messages.dropoffinfo') }}</span>
                            </div>
                            <p type="text" class="form-control mb-0">
                                @if ($dropoff == DROPOFFANY) {{ $dropoffAny }} @endif
                                @if ($dropoff == DROPOFFANYOTHER) {{ $dropoffAnyOther }} @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!--NOTE-->
                <div class="row mt-3">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ trans('messages.note') }}</span>
                        </div>
                        <p type="text" class="form-control mb-0">{{ $note }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>