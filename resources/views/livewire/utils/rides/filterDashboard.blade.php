<!-- Content Row -->
<div class="row">

    <!-- List Rides
    <div class="col-xl-8 col-lg-7"> 
    -->
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <div class="col-md-2">
                    <h6 class="m-0 font-weight-bold text-primary">Rides in day</h6>
                </div>
                <div class="col-md-2">
                    <label>Depart</label>
                    <select id="fromLocation" name="fromLocation" class="form-control form-select" wire:model="fromLocation" placeholder="{{ trans('messages.pickup') }}">
                        <option value=""></option>
                        @foreach($fromLocationList as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Destination</label>
                    <select id="toLocation" name="toLocation" class="form-control form-select" wire:model="toLocation" placeholder="{{ trans('messages.dropoff') }}">
                        <option value=""></option>
                        @foreach($toLocationList as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>From</label>
                    <input id="fromDate" name="fromDate" wire:model="fromDate" class="form-control" type="date" required>
                </div>
                <div class="col-md-2">
                    <label>To</label>
                    <input id="toDate" name="toDate" wire:model="toDate" class="form-control" type="date" required>
                </div>
                <div class="col-md-2 d-flex flex-column align-items-center text-center">
                    <label>Has ordered?</label>
                    <input id="hasOrdered" type="checkbox" wire:model="hasOrdered" class="form-control form-check-input">
                </div>
            </div>

        </div>
    </div>
</div>