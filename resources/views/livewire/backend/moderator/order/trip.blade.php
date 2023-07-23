<div>
    @if ($step === 2)
        <div class="container">
            <div class="row align-items-start d-flex">

                <div class="col-md-12 col-md-offset-1">
                    <div style="background-color: #cfb0b1;">
                        <h2 class="mt-3" >
                            {{ $fromLocationName }}
                                <i class="fas fa-arrow-right fa-md fa-1x"></i>
                            {{ $toLocationName }}
                        </h2>
                    </div>
            
                    @if (sizeof($departRides))
                        <span> {{ trans('backend.foundrides', ['totalride' => count($departRides)]) }}</span>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ trans('backend.nametrip') }}</th>
                                    <th>{{ trans('backend.departtime') }}</th>
                                    <th>{{ trans('backend.returntime') }}</th>
                                    <th>{{ trans('backend.seatclass') }}</th>
                                    <th>{{ trans('backend.seatclassprice') }}</th>
                                    <th>{{ trans('backend.selecttrip') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($customerTypeType == ONLINEPRICE)
                                    @foreach ($departRides as $ride)
                                        <tr>
                                            <td>{{ $ride->name }}</td>
                                            <td>{{ $ride->departTime }}</td>
                                            <td>{{ $ride->returnTime }}</td>
                                            <td>{{ $ride->seatClass }}</td>
                                            <th>฿{{ round($ride->price) }} ({{ trans('backend.onlineprice') }})</th>
                                            <td>
                                                <input type="radio" name="departRadio" 
                                                        value="{{ $ride->seatClassId }}"
                                                        wire:key="{{ $ride->seatClassId }}"
                                                        wire:click="chooseDepartTrip({{ $ride->id }}, {{ $ride->seatClassId }})"
                                                        @if (!$order_depart_seatClassId && $loop->first)
                                                            wire:init="chooseDepartTrip({{ $ride->id }}, {{ $ride->seatClassId }})" 
                                                        @endif
                                                        @if ($ride->seatClassId ===$order_depart_seatClassId) checked @endif
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if($customerTypeType != ONLINEPRICE)
                                    @foreach ($departRides as $ride)
                                        <tr class="table-secondary">
                                            <td>{{ $ride->name }}</td>
                                            <td>{{ $ride->departTime }}</td>
                                            <td>{{ $ride->returnTime }}</td>
                                            <td>{{ $ride->seatClass }}</td>
                                            <th>฿{{ round($customerTypePrice) }} ({{ $customerType[$customerTypeType] }})</th>
                                            <td>
                                                <input type="radio" name="departRadio" 
                                                        value="{{ $ride->seatClassId }}"
                                                        wire:key="{{ $ride->seatClassId }}"
                                                        wire:click="chooseDepartTrip({{ $ride->id }}, {{ $ride->seatClassId }})"
                                                        @if (!$order_depart_seatClassId && $loop->first)
                                                            wire:init="chooseDepartTrip({{ $ride->id }}, {{ $ride->seatClassId }})" 
                                                        @endif
                                                        @if ($ride->seatClassId ===$order_depart_seatClassId) checked @endif
                                                >
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    @else
                        <h4 style="color: #CC2131">{{ trans('backend.notripmatch') }}</h4>
                    @endif
            
                    {{----------------------------------------}}
            
                    @if ($tripType == ROUNDTRIP)
                        <div style="background-color: #cfb0b1;">
                            <h2 class="mt-3" >
                                {{ $toLocationName }}
                                    <i class="fas fa-arrow-right fa-md fa-1x"></i>
                                {{ $fromLocationName }}
                            </h2>
                        </div>
            
                        @if (sizeof($returnRides))
                            <span> {{ trans('backend.foundrides', ['totalride' => count($returnRides)]) }}</span>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ trans('backend.nametrip') }}</th>
                                        <th>{{ trans('backend.departtime') }}</th>
                                        <th>{{ trans('backend.returntime') }}</th>
                                        <th>{{ trans('backend.seatclass') }}</th>
                                        <th>{{ trans('backend.seatclassprice') }}</th>
                                        <th>{{ trans('backend.selecttrip') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($customerTypeType == ONLINEPRICE)
                                        @foreach ($returnRides as $ride)
                                            <tr>
                                                <td>{{ $ride->name }}</td>
                                                <td>{{ $ride->departTime }}</td>
                                                <td>{{ $ride->returnTime }}</td>
                                                <td>{{ $ride->seatClass }}</td>
                                                <th>฿{{ round($ride->price) }} ({{ $customerType[$customerTypeType] }})</th>
                                                <td>
                                                    <input type="radio" name="returnRadio"
                                                            value="{{ $ride->seatClassId }}"
                                                            wire:key="{{ $ride->seatClassId }}"
                                                            wire:click="chooseReturnTrip({{ $ride->id }}, {{ $ride->seatClassId }})"
                                                            @if (!$order_return_seatClassId && $loop->first)
                                                                wire:init="chooseReturnTrip({{ $ride->id }}, {{ $ride->seatClassId }})"
                                                            @endif
                                                            @if ($ride->seatClassId === $order_return_seatClassId) checked @endif
                                                    >
                                            </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    @if($customerTypeType != ONLINEPRICE)
                                        @foreach ($returnRides as $ride)
                                            <tr class="table-secondary">
                                                <td>{{ $ride->name }}</td>
                                                <td>{{ $ride->departTime }}</td>
                                                <td>{{ $ride->returnTime }}</td>
                                                <td>{{ $ride->seatClass }}</td>
                                                <th>฿{{ round($customerTypePrice) }} ({{ trans('backend.agentprice') }})</th>
                                                <td>
                                                    <input type="radio" name="returnRadio"
                                                            value="{{ $ride->seatClassId }}"
                                                            wire:key="{{ $ride->seatClassId }}"
                                                            wire:click="chooseReturnTrip({{ $ride->id }}, {{ $ride->seatClassId }})"
                                                            @if (!$order_return_seatClassId && $loop->first)
                                                                wire:init="chooseReturnTrip({{ $ride->id }}, {{ $ride->seatClassId }})"
                                                            @endif
                                                            @if ($ride->seatClassId === $order_return_seatClassId) checked @endif
                                                    >
                                            </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
            
                        @else
                            <h4 style="color: #CC2131">{{ trans('backend.notripmatch') }}</h4>
                        @endif 
                        
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>