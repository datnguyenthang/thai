<div class="col-md-7 col-md-offset-1">
    <div class="booking-form">
        <form action="{{ route('trip') }}" method="get">
            <div class="form-group">
                <div class="form-checkbox">
                    <label for="roundtrip">
                        <input type="radio" id="roundtrip" value="{{ ROUNDTRIP }}" name="tripType" wire:model="tripType" wire:click="chooseTripType(1)">
                        <span></span>{{ trans('messages.roundtrip') }}
                    </label>
                    <label for="one-way">
                        <input type="radio" id="one-way" value="{{ ONEWAY }}" name="tripType" wire:model="tripType" wire:click="chooseTripType(0)">
                        <span></span>{{ trans('messages.oneway') }}
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="form-label">{{ trans('messages.fromlocation') }}</span>
                        <select id="fromLocation" name="fromLocation" class="form-control" wire:model="fromLocation" wire:change="chooseFromLocation($event.target.value)" required placeholder="{{ trans('messages.pickup') }}">
                            <option value=""></option>
                            @foreach($fromLocationList as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        <span class="select-arrow"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="form-label">{{ trans('messages.tolocation') }}</span>
                        <select id="toLocation" name="toLocation" class="form-control" wire:model="toLocation" required placeholder="{{ trans('messages.dropoff') }}">
                            <option value=""></option>
                            @foreach($toLocationList as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        <span class="select-arrow"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="form-label">{{ trans('messages.departuredate') }}</span>
                        <input id="departureDate" name="departureDate" class="form-control" type="date" wire:change="chooseDepartDate($event.target.value)" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                @if($tripType == ROUNDTRIP)
                <div class="col-md-6">
                    <div class="form-group">
                        <span class="form-label">{{ trans('messages.returndate') }}</span>
                        <input id="returnDate" name="returnDate" class="form-control" type="date" min="{{ $returnDate }}" required="">
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="form-label">{{ trans('messages.adults') }}</span>
                        <input type="number" id="adults" name="adults" class="form-control"  min="1" value="1" required/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="form-label">{{ trans('messages.children') }}</span>
                        <input type="number" id="children" name="children" class="form-control" min="0" value="0" required/>
                    </div>
                </div>
            <!--
                <div class="col-md-4">
                    <div class="form-group">
                        <span class="form-label">{{ trans('messages.seatclass') }}</span>
                        <select class="form-control">
                            <option>Economy class</option>
                            <option>Business class</option>
                            <option>First class</option>
                        </select>
                        <span class="select-arrow"></span>
                    </div>
                </div>
            -->
            </div>
            <div class="form-btn">
              <button class="submit-btn">{{ trans('messages.showboat') }}</button>
            </div>
        </form>
    </div>
</div>