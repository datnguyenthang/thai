<div>
    <h1>{{ trans('backend.createride') }}</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="userid" value="{{ $rideId }}">

        <div class="form-outline mb-4">
            <label class="form-label">{{ trans('backend.ridename') }}</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
    
        <div class="form-outline mb-4">
            <label class="form-label" for="fromLocation">{{ trans('backend.fromlocation') }}</label>
            <select id="fromLocation" class="form-select w-50" wire:model="fromLocation">
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
            @error('fromLocation') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
    
        <div class="form-outline mb-4">
            <label class="form-label" for="toLocation">{{ trans('backend.tolocation') }}</label>
            <select id="toLocation" class="form-select w-50" wire:model="toLocation">
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
            @error('toLocation') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="departTime">{{ trans('backend.departuretime') }}</label>
            <input type="time" class="form-control w-50" id="departTime" wire:model="departTime" >
            @error('departTime') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="returnTime">{{ trans('backend.returntime') }}</label>
            <input type="time" class="form-control w-50" id="returnTime" wire:model="returnTime">
            @error('returnTime') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="departDate">{{ trans('backend.departdate') }}</label>
            <input type="date" class="form-control w-50" id="departDate" wire:model="departDate">
            @error('returnTime') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="hoursBeforeBooking">{{ trans('backend.hoursBeforeBooking') }}</label>
            <input type="number" class="form-control w-50" id="hoursBeforeBooking" wire:model="hoursBeforeBooking" min="0" />
            @error('hoursBeforeBooking') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="colorCode">{{ trans('backend.colorcode') }}</label>
            <input type="text" class="form-control w-50" id="colorCode" wire:model.defer="colorCode"  />
            @error('colorCode') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="status">{{ trans('backend.ridestatus') }}</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(RIDESTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @error('status') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <hr />
        <h1>{{ trans('backend.createseatclasses') }}</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>{{ trans('backend.seatclassname') }}</th>
                    <th>{{ trans('backend.capacity') }}</th>
                    <th>{{ trans('backend.price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($seatClasses as $index => $seatClass)
                <tr>
                    <td>
                        <input type="hidden" name="seatClasses.{{ $index }}.id" wire:model.defer="seatClasses.{{ $index }}.id">
                        <input type="text" id="nameClass_{{ $index }}" wire:model.defer="seatClasses.{{ $index }}.nameClass">
                        @error('seatClasses.'. $index .'.nameClass') <br /><span class="text-danger error">{{ $message }}</span> @enderror
                    </td>
                    <td>
                        <input type="number" id="capacity_{{ $index }}" wire:model.defer="seatClasses.{{ $index }}.capacity" min="0" />
                        @error('seatClasses.'. $index .'.capacity') <br /><span class="text-danger error">{{ $message }}</span> @enderror
                    </td>
                    <td>
                        <input type="number" id="price_{{ $index }}" wire:model.defer="seatClasses.{{ $index }}.price" min="0" />
                        @error('seatClasses.'. $index .'.price') <br /><span class="text-danger error">{{ $message }}</span> @enderror
                    </td>
                    @if($index > 0)
                    <td>
                        <button type="button" wire:click="removeSeatClass({{ $index }})">{{ trans('backend.remove') }}</button>
                    </td>
                    @endif
                </tr>
            @endforeach

        </table>
        @error('seatClass') <span class="text-danger error">{{ $message }}</span> @enderror

        <br />
    
        <button type="button" wire:loading.attr="disabled" class="d-flex justify-content-center" wire:click="addSeatClass">{{ trans('backend.addseatclass') }}</button>

        <br />
        <button type="submit" wire:loading.attr="disabled" class="btn btn-primary">{{ trans('backend.save') }}</button>
    </form>
</div>