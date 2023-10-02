<!-- Card Header - Dropdown -->
<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between row">
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
        <label>From</label>
        @if($selectType !== 'year')
            <input id="fromDate" name="fromDate" wire:model="fromDate" class="form-control" type="{{ $selectType }}">
        @endif
        @if($selectType == 'year')
            <input id="fromDate" name="fromDate" wire:model="fromDate" class="date-own form-control" type="text">
        @endif
    </div>
    <div class="col-md-2">
        <label>To</label>
        @if($selectType !== 'year')
            <input id="toDate" name="toDate" wire:model="toDate" class="form-control" type="{{ $selectType }}">
        @endif
        @if($selectType == 'year')
            <input id="toDate" name="toDate" wire:model="toDate" class="date-own form-control" type="text">
        @endif
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="perPage" class="form-control-label">{{ trans('backend.perpage') }}</label>
            <select id="perPage" class="form-control form-select" wire:model="perPage">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="99999999">All</option>
            </select>
        </div>
    </div>
    <div class="col-md-2 d-flex flex-column align-items-center text-center">
        <label>Has ordered?</label>
        <input id="hasOrdered" type="checkbox" wire:model="hasOrdered" class="form-control form-check-input">
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