<!-- Card Header - Dropdown -->
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between row bg-success text-dark bg-opacity-25">
    <div class="col-md-3">
        <label>Depart</label>
        <select id="fromLocation" name="fromLocation" class="form-control form-select" wire:model="fromLocation" placeholder="{{ trans('messages.pickup') }}">
            <option value=""></option>
            @foreach($locationList as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label>Destination</label>
        <select id="toLocation" name="toLocation" class="form-control form-select" wire:model="toLocation" placeholder="{{ trans('messages.dropoff') }}">
            <option value=""></option>
            @foreach($locationList as $location)
                <option value="{{ $location->id }}">{{ $location->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label>From</label>
        @if($selectType !== 'year')
            <input id="fromDate" name="fromDate" wire:model="fromDate" class="form-control" type="{{ $selectType }}">
        @endif
        @if($selectType == 'year')
            <input id="fromDate" name="fromDate" wire:model="fromDate" class="date-own form-control" type="text">
        @endif
    </div>
    <div class="col-md-3">
        <label>To</label>
        @if($selectType !== 'year')
            <input id="toDate" name="toDate" wire:model="toDate" class="form-control" type="{{ $selectType }}">
        @endif
        @if($selectType == 'year')
            <input id="toDate" name="toDate" wire:model="toDate" class="date-own form-control" type="text">
        @endif
    </div>

    @if($selectType == 'year')
        <script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script type="module">
            $('.date-own').datepicker({
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years',
                autoClose: true
            }).on('changeYear', function(e) {
                const selectedYear = e.date.getFullYear();
                Livewire.emit('yearSelected', e.target.id, selectedYear);
            });
        </script>
    @endif
</div>