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
    <div class="row align-items-start d-flex">
        <div class="col-md-12 col-md-offset-1">
            <div class="border rounded-3 overflow-hidden p-4 mt-3">
                <h2 class="select-departure-header mb-3" >{{ trans('backend.contactdetail') }}</h2>
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="firstName" placeholder="{{ trans('backend.firstname') }}" required />
                        @error('firstName') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="lastName" placeholder="{{ trans('backend.lastname') }}" required />
                        @error('lastName') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="email" placeholder="{{ trans('backend.email') }}" required />
                        @error('email') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="phone" placeholder="{{ trans('backend.phone') }}" required />
                        @error('phone') <span class="text-danger error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!---Pickup & dropoff information--->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h4>{{ trans('messages.pickupinfo') }}</h4>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="pickup" name="pickup" id="pickup1" value="0" checked>
                            <label class="form-check-label" for="radio1">{{ trans('messages.dontusetranferservie') }}</label>
                        </div>
                            
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="pickup" name="pickup" id="pickup2" value="1">
                            <label class="form-check-label" for="radio2">{{ trans('messages.pickupany') }}</label>
                        </div>
                        @if ($pickup == PICKUPANY)
                            <select id="selectPickup" name="pickupAny" class="form-select" wire:model="pickupAny">
                                @foreach($pickupdropoffs as $pickupdropoff)
                                    <option value="{{ $pickupdropoff->name }}">{{ $pickupdropoff->name }}</option>
                                @endforeach
                            </select>
                        @endif
                            
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="pickup" name="pickup" id="pickup3" value="2">
                            <label class="form-check-label" for="radio3">{{ trans('messages.pickupother') }}</label>
                        </div>
                        @if ($pickup == PICKUPANYOTHER)
                            <div class="form-input row">
                                <input class="form-control w-75" type="text" name="pickupAnyOther" wire:model="pickupAnyOther">
                                @error('pickupAnyOther') <span class="text-danger error">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h4>{{ trans('messages.dropoffinfo') }}</h4>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="dropoff" name="dropoff" id="dropoff1" value="0" checked>
                            <label class="form-check-label" for="radio1">{{ trans('messages.dontusetranferservie') }}</label>
                        </div>
                            
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="dropoff" name="dropoff" id="dropoff2" value="1">
                            <label class="form-check-label" for="radio2">{{ trans('messages.dropoffany') }}</label>
                        </div>
                        @if ($dropoff == DROPOFFANY)
                            <select id="selectDropoff" name="dropoffAny" class="form-select" wire:model="dropoffAny">
                                @foreach($pickupdropoffs as $pickupdropoff)
                                    <option value="{{ $pickupdropoff->name }}">{{ $pickupdropoff->name }}</option>
                                @endforeach
                            </select>
                        @endif
                            
                        <div class="form-check">
                            <input class="form-check-input" type="radio" wire:model="dropoff" name="dropoff" id="dropoff3" value="2">
                            <label class="form-check-label" for="radio3">{{ trans('messages.dropoffother') }}</label>
                        </div>
                        @if ($dropoff == DROPOFFANYOTHER)
                            <div class="form-input row">
                                <input class="form-control w-75" type="text" wire:model="dropoffAnyOther" name="dropoffAnyOther">
                                @error('dropoffAnyOther') <span class="text-danger error">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <!--NOTE-->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <textarea class="form-control" wire:model="note" rows="4" placeholder="{{ trans('backend.note') }}"></textarea>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" wire:model="couponCode" placeholder="{{ trans('backend.coupon') }}" />
                        @error('couponCode') <span class="text-danger error">{{ $message }}</span> @enderror
                        
                        <button class="btn bg_own_color text-light float-end mt-2" 
                            wire:click="applyCoupon"
                            wire:loading.attr="disabled">{{ trans('backend.apply') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>