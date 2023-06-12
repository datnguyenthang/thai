<div>
    {{ Breadcrumbs::render('proceedbooking') }}
    <form wire:submit.prevent="bookTicket">
        <div class="row align-items-start d-flex">
            <div class="col-md-8 col-md-offset-1">
                <div class="border rounded-3 overflow-hidden p-4 mt-3">
                    <h4 class="select-departure-header mb-3" >{{ trans('messages.contactdetail') }}</h4>
                    <!---Customer information--->
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="firstName" placeholder="{{ trans('messages.firstname') }}" required />
                            @error('firstName') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="lastName" placeholder="{{ trans('messages.lastname') }}" required />
                            @error('lastName') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="email" placeholder="{{ trans('messages.email') }}" required />
                            @error('email') <span class="text-danger error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <input type="tel" class="form-control" wire:model="phone" placeholder="{{ trans('messages.phone') }}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required />
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
                                </div>
                            @endif
                        </div>
                    </div>
                    {{--
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="pickup" placeholder="{{ trans('messages.pickup') }}"  />
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="dropoff" placeholder="{{ trans('messages.dropoff') }}" />
                        </div>
                    </div>
                    --}}
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <textarea class="form-control" wire:model="note" rows="4" placeholder="{{ trans('messages.note') }}"></textarea>
                        </div>
                    </div>
                </div>
                <!---Booking detail--->
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
                                                                          'ride' => $depart->name,
                                                                          'departdate' => date('F j, Y', strtotime($depart->departDate)),
                                                                          'departtime' =>  $depart->departTime]) !!}
                                        <br>{{ trans('messages.people') }}: {{ $adults + $children }} <strong class="product-quantity">×{{ $adults + $children }}</strong>
                                    </td>
                                    <td class="product-total">
                                        <span class="amount">
                                            <span>฿</span> {{ $departPrice  }}
                                        </span>
                                    </td>
                                </tr>
                                @if ($tripType == ROUNDTRIP)
                                <tr class="order_item">
                                    <td class="">
                                        {!! trans('messages.detailorder', ['fromlocation' => $toLocationName, 
                                                                          'tolocation' => $fromLocationName, 
                                                                          'ride' => $return->name,
                                                                          'departdate' => date('F j, Y', strtotime($return->departDate)),
                                                                          'departtime' =>  $return->departTime]) !!}
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
                                    <td><span class="amount">฿{{ $subPrice }}</span></td>
                                </tr>
                                <tr>
                                    <th scope="row">{{ trans('messages.total') }}:</th>
                                    <td><span class="amount">฿{{ $totalPrice }}</span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </section>

                    <hr />

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button class="btn btn-link text-info" type="button">{{ trans('messages.inputcoupon') }}</button>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="coupon" placeholder="{{ trans('messages.coupon') }}" />
                            <button class="btn btn-danger float-end mt-2" type="button">{{ trans('messages.apply') }}</button>
                        </div>
                    </div>
                    <hr />
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <input type="checkbox" class="form-check-input" wire:model="agreepolicy" required>
                            <label class="form-check-label" for="agreepolicy">
                                {!! trans('messages.policy') !!}
                              </label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger form-control">
                                {{ trans('messages.bookandpay') }}
                            </button>
                        </div>
                    <!--
                        <div class="col-md-12 mt-2">
                            <button wire:click="saveAndPayLater" class="btn btn-danger form-control">
                                {{ trans('messages.saveandpaylater') }}
                            </button>
                        </div>
                    -->
                    </div>
                </div> 
            </div>
            
            <!---Customer Orders--->
            <div class="col-md-4 col-md-offset-1 mt-3">
                <div class="border rounded-3 overflow-hidden depart_trip">
                    <div class="bg_own_color p-3">
                        <h5 class="fw-bold text-light">
                            {{ $fromLocationName }}
                                <i class="fas fa-arrow-right fa-md fa-1x"></i>
                            {{ $toLocationName }}
                        </h5>
                        <div class="fw-bold text-center text-light">
                            <span>{{ date('F j, Y', strtotime($depart->departDate)) }}</span>
                            <span>, {{ $depart->departTime }}</span>
                        </div>
                    </div>
                    <div class="info">
                        <div class="info-trip p-3 fw-bold">
                            <h5>{{ $depart->name }}</h5>
                            <div class="text-left">
                                <span><i class="fas fa-chair fa-lg"></i>  {{ $seatDepart->name }}</span>
                                <span class="float-end">{{ $seatDepart->price }}$</span>
                            </div>
                            <div class="text-left">
                                <span>{{ trans('messages.passenger') }} {{ $adults + $children }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            @if ($tripType == ROUNDTRIP)
                <div class="border rounded-3 overflow-hidden return_trip mt-4">
                    <div class="bg_own_color p-3">
                        <h5 class="fw-bold text-light" >
                            {{ $toLocationName }}
                                <i class="fas fa-arrow-right fa-md fa-1x"></i>
                            {{ $fromLocationName }}
                        </h5>
                        <div class="fw-bold text-center text-light">
                            <span>{{ date('F j, Y', strtotime($return->departDate)) }}</span>
                            <span>, {{ $return->departTime }}</span>
                        </div>
                    </div>
                    <div class="p-3 fw-bold">
                        <h5>{{ $return->name }}</h5>
                        <div class="text-left">
                            <span><i class="fas fa-chair fa-lg"></i>  {{ $seatReturn->name }}</span>
                            <span class="float-end">{{ $seatReturn->price }}$</span>
                        </div>
                        <div class="text-left">
                            <span>{{ trans('messages.passenger') }} {{ $adults + $children }}</span>
                        </div>
                    </div>
                </div>
            @endif

            </div>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.on('scroll-to-error', function () {
                const firstErrorElement = document.querySelector('.error');
                if (firstErrorElement) {
                    firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    </script>
    
</div>