<div class="container">
    <div class="row mt-3">
        <div class="col-md-8">
            <div class="form-group">
                <div class="form-checkbox">
                    <label for="one-way">
                        <input type="radio" id="one-way" value="{{ ONEWAY }}" name="tripType" wire:model="tripType" wire:click="chooseTripType(ONEWAY)">
                        <span></span>{{ trans('messages.oneway') }}
                    </label>
                    <label for="roundtrip">
                        <input type="radio" id="roundtrip" value="{{ ROUNDTRIP }}" name="tripType" wire:model="tripType" wire:click="chooseTripType(ROUNDTRIP)">
                        <span></span>{{ trans('messages.roundtrip') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <div class="form-group">
                <span class="form-label">{{ trans('messages.adults') }}</span>
                <input type="number" id="adults" wire:model="adults" name="adults" class="form-control" min="1" required/>
                @error('adults') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <span class="form-label">{{ trans('messages.children') }}</span>
                <input type="number" id="children" wire:model="children" name="children" class="form-control" min="0" required/>
                @error('children') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <span class="form-label">{{ trans('messages.departure') }}</span>
                <select id="fromLocation" name="fromLocation" class="form-select" wire:model="fromLocation" wire:change="chooseFromLocation($event.target.value)" required placeholder="{{ trans('messages.pickup') }}">
                    @foreach($fromLocationList as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
                @error('fromLocation') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <span class="form-label">{{ trans('messages.destination') }}</span>
                <select id="toLocation" name="toLocation" class="form-select" wire:model="toLocation" wire:change="chooseToLocation($event.target.value)" required placeholder="{{ trans('messages.dropoff') }}">
                    @foreach($toLocationList as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
                @error('toLocation') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <span class="form-label">{{ trans('messages.departuredate') }}</span>
                <input id="departureDate" wire:model="departureDate" name="departureDate" class="form-control" type="date" required>
                @error('departureDate') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
        </div>

        @if($tripType == ROUNDTRIP)
        <div class="col-md-3">
            <div class="form-group">
                <span class="form-label">{{ trans('messages.returndate') }}</span>
                <input id="returnDate" wire:model="returnDate" name="returnDate" class="form-control" type="date" min="{{ $returnDate }}" required="">
                @error('returnDate') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif

        <div class="col-md-3">
            <div class="form-group">
                <span class="form-label">{{ trans('backend.customertype') }}</span>
                <select id="customerType" name="customerType" class="form-select" type="date" wire:model="customerType">
                    @foreach($customerTypelist as $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                </select>
                @error('customerType') <span class="text-danger error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="col-md-3 pt-4">
            <div class="text-center">
                <button class="btn bg_own_color form-control text-light" wire:loading.attr="disabled" wire:click="applyFilter">{{ trans('backend.applyfilter') }}</button>
            </div>
        </div>
    </div>
    <script>
        var adultsInput = document.getElementById("adults");
        var childrenInput = document.getElementById("children");

        // Add a keyup event listener for adults input
        adultsInput.addEventListener("keyup", function() {
            var currentValue = this.value.trim();
            if (currentValue === "") {
            this.value = "1";
            }
        });

        // Add a keyup event listener for children input
        childrenInput.addEventListener("keyup", function() {
            var currentValue = this.value.trim();
            if (currentValue === "") {
            this.value = "0";
            }
        });
    </script>
</div>