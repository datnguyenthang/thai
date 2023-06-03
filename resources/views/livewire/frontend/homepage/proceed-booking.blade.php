<div>
    <form wire:submit.prevent="bookTicket">
        <div class="row align-items-start d-flex">
            <div class="col-md-8 col-md-offset-1">
                <div class="border rounded-3 overflow-hidden p-4 mt-3">
                    <h2 class="select-departure-header mb-3" >{{ trans('messages.contactdetail') }}</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="firstName" placeholder="{{ trans('messages.firstname') }}" required />
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="lastName" placeholder="{{ trans('messages.lastname') }}" required />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <input type="email" class="form-control" wire:model="email" placeholder="{{ trans('messages.email') }}" required />
                        </div>
                        <div class="col-md-6">
                            <input type="tel" class="form-control" wire:model="phone" placeholder="{{ trans('messages.phone') }}" required />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="pickup" placeholder="{{ trans('messages.pickup') }}"  />
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" wire:model="dropoff" placeholder="{{ trans('messages.dropoff') }}" />
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <textarea class="form-control" wire:model="note" rows="4" placeholder="{{ trans('messages.note') }}"></textarea>
                        </div>
                    </div>
                </div>

                <div class="border rounded-3 overflow-hidden p-4 mt-3">
                    <h2 class="select-departure-header mb-3" >{{ trans('messages.summary') }}</h2>
                    <hr />
                    <div class="row">
                        <div class="col-md-10">
                            <h5 class="mb-3" >{{ trans('messages.summary') }}</h5>
                        </div>
                        <div class="col-md-2">
                            <h5 class="mb-3" >{{ $price }}$</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-10">
                            <h4 class="mb-3 fw-bold" >{{ trans('messages.total') }}</h4>
                        </div>
                        <div class="col-md-2">
                            <h5 class="mb-3 fw-bold" >{{ $price }}$</h5>
                        </div>
                    </div>

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
                                {{ trans('messages.policy') }}
                              </label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-danger form-control">
                                {{ trans('messages.bookandpay') }}
                            </button>
                        </div>
                    </div>
                </div> 
            </div>
            
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
</div>