<div>
    <div class="modal fade show" tabindex="-1" style="display: block;" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">{{ trans('backend.updateorder') }}</h5>
                    <button class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.ordercode') }} : </span>
                            <span class="form-label fw-bold">{{ $order->code }}</span>
                        </div>
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.name') }} : </span>
                            <span class="form-label fw-bold">{{ $order->firstName.' '.$order->lastName }}</span>
                        </div>
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.email') }} : </span>
                            <span class="form-label fw-bold">{{ $order->email }}</span>
                        </div>
                        <div class="col-md-12">
                            <span class="form-label">{{ trans('backend.phone') }} : </span>
                            <span class="form-label fw-bold">{{ $order->phone }}</span>
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

                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <button class="btn bg_own_color text-light"
                                    wire:click="save()"
                                    wire:loading.attr="disabled">{{ trans('backend.update') }}</button>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" wire:click="closeModal" class="btn btn-secondary">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
