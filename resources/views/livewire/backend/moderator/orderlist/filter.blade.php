<div class="row">
    {{--
    <div class="col-md-2">
        <div class="form-group">
            <label for="orderid" class="form-control-label">{{ trans('backend.orderid') }}</label>
            <input wire:model.defer="orderid" class="form-control" type="text" placeholder="">
        </div>
    </div>
    --}}
    
    <div class="col-md-2">
        <div class="form-group">
            <label for="trip" class="form-control-label">{{ trans('backend.ordercode') }}</label>
            <input wire:model.defer="orderCode" class="form-control" type="text" placeholder="">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="fromLocation" class="form-control-label">{{ trans('messages.departure') }}</label>
            <select id="fromLocation" name="fromLocation" class="form-select" wire:model="fromLocation">
                <option value=""></option>
                @foreach($locationList as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="toLocation" class="form-control-label">{{ trans('messages.destination') }}</label>
            <select id="toLocation" name="toLocation" class="form-select" wire:model="toLocation">
                <option value=""></option>
                @foreach($locationList as $location)
                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="ridename" class="form-control-label">{{ trans('backend.ridename') }}</label>
            <input wire:model.defer="rideName" class="form-control" type="text" placeholder="">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="customername" class="form-control-label">{{ trans('backend.customername') }}</label>
            <input wire:model.defer="customerName" class="form-control" type="text" placeholder="">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="customerphone" class="form-control-label">{{ trans('backend.customerphone') }}</label>
            <input wire:model.defer="customerPhone" class="form-control" type="text" placeholder="">
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="bookingdate" class="form-control-label">{{ trans('backend.bookingdate') }}</label>
            <input wire:model.defer="bookingDate" class="form-control" type="date" placeholder="">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="agentId" class="form-control-label">{{ trans('backend.agentname') }}</label>
            <select id="agentId" name="agentId" class="form-select" wire:model.defer="agentId" >
                <option value=""></option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="trip" class="form-control-label">{{ trans('backend.orderstatus') }}</label>
            <select id="orderStatus" name="orderStatus" class="form-select" wire:model.defer="orderStatus" >
                <option value=""></option>
                @foreach(ORDERSTATUS as $key=>$value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="trip" class="form-control-label">{{ trans('backend.customerType') }}</label>
            <select id="customerType" name="customerType" class="form-select" wire:model.defer="customerType" >
                <option value="-1"></option>
                @foreach(CUSTOMERTYPE as $key=>$value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="form-control-label"></label>
            <button class="btn bg_own_color text-light form-control" wire:click="filter">{{ trans('backend.applysearch') }}</button>
        </div>
    </div>

    @if ($isAllowed)
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-control-label"></label>
                <button class="btn bg_own_color text-light form-control" wire:click="downloadOrderList">{{ trans('backend.download') }}</button>
            </div>
        </div>
    @endif
</div>