<div>
    <h1>Create Ride</h1>
    <form wire:submit.prevent="save">
        <input type="hidden" name="userid" value="{{ $rideId }}">

        <div class="form-outline mb-4">
            <label class="form-label">Name:</label>
            <input type="text" class="form-control w-50" wire:model="name">
            @error('name') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
    
        <div class="form-outline mb-4">
            <label class="form-label" for="fromLocation">From location</label>
            <select id="fromLocation" class="form-select w-50" wire:model="fromLocation">
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
            @error('fromLocation') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>
    
        <div class="form-outline mb-4">
            <label class="form-label" for="toLocation">To location</label>
            <select id="toLocation" class="form-select w-50" wire:model="toLocation">
                @foreach($locations as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
            @error('toLocation') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="departTime">Depart Time:</label>
            <input type="time" class="form-control w-50" id="departTime" wire:model="departTime" >
            @error('departTime') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="returnTime">Return Time:</label>
            <input type="time" class="form-control w-50" id="returnTime" wire:model="returnTime">
            @error('returnTime') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="departDate">Depart Date:</label>
            <input type="date" class="form-control w-50" id="departDate" wire:model="departDate">
            @error('returnTime') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <div class="form-outline mb-4">
            <label class="form-label" for="status">Status</label>
            <select id="status" class="form-select w-50" wire:model="status">
                @foreach(RIDESTATUS as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @error('status') <span class="text-danger error">{{ $message }}</span> @enderror
        </div>

        <hr />
        <h1>Create Seat Classes</h1>
        <table class="table">
            <thead>
                <tr>
                  <th>Name</th>
                  <th>Capacity</th>
                  <th>Price</th>
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
                        <input type="number" id="capacity_{{ $index }}" wire:model.defer="seatClasses.{{ $index }}.capacity">
                        @error('seatClasses.'. $index .'.capacity') <br /><span class="text-danger error">{{ $message }}</span> @enderror
                    </td>
                    </td>
                    <td>
                        <input type="number" id="price_{{ $index }}" wire:model.defer="seatClasses.{{ $index }}.price">
                        @error('seatClasses.'. $index .'.price') <br /><span class="text-danger error">{{ $message }}</span> @enderror
                    </td>
                    @if($index  > 0)
                    <td>
                        <button type="button" wire:click="removeSeatClass({{ $index }})">Remove</button>
                    </td>
                    @endif
                </tr>
            @endforeach

        </table>
        @error('seatClass') <span class="text-danger error">{{ $message }}</span> @enderror

        <br />
    
        <button type="button" class="d-flex justify-content-center" wire:click="addSeatClass">Add Seat Class</button>

        <br />
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>