<div class="row mt-2">
    <div class="col-md-2">
        <div class="form-group">
            <label for="rideName" class="form-control-label">{{ trans('backend.ridename') }}</label>
            <input type="text" wire:model.debounce.500ms="rideName" class="form-control" placeholder="{{ trans('backend.search') }}...">
        </div>
    </div>
    <div class="col-md-2">
        <label>Depart</label>
        <select id="fromLocation" name="fromLocation" class="form-control form-select" wire:model="fromLocation" placeholder="{{ trans('messages.pickup') }}">
            <option value=""></option>
            @foreach($locationList as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <label>Destination</label>
        <select id="toLocation" name="toLocation" class="form-control form-select" wire:model="toLocation" placeholder="{{ trans('messages.dropoff') }}">
            <option value=""></option>
            @foreach($locationList as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="fromDate" class="form-control-label">{{ trans('backend.fromdate') }}</label>
            <input type="date" wire:model="fromDate" class="form-control" placeholder="">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="toDate" class="form-control-label">{{ trans('backend.todate') }}</label>
            <input type="date" wire:model="toDate" class="form-control" placeholder="">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="departTime" class="form-control-label">{{ trans('backend.departtime') }}</label>
            <input type="time" wire:model.debounce.500ms="departTime" class="form-control" placeholder="">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="perPage" class="form-control-label">{{ trans('backend.perpage') }}</label>
            <select id="perPage" class="form-control form-select" wire:model="perPage">
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="2000">2000</option>
                <option value="5000">5000</option>
            </select>
        </div>
    </div>
    @if (count($selected) > 0)
    <div class="col-md-2">
        <div class="form-group">
            <label for="action" class="form-control-label">{{ trans('backend.action') }}</label>
            <button class="btn bg_own_color" wire:click="editMassRide(1)">Edit Selected Ride</button>
        </div>
    </div>
    @endif
</div>