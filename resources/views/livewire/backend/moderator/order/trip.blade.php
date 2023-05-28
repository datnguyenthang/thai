@if ($search)
    <div class="row align-items-start d-flex">

        <div class="col-md-12 col-md-offset-1">
            <div style="background-color: #def8f3;">
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
                            <th>{{ trans('backend.distancetime') }}</th>
                            <th>{{ trans('backend.returntime') }}</th>
                            <th>{{ trans('backend.seatclass') }}</th>
                            <th>{{ trans('backend.selecttrip') }}</th>
                            <th>{{ trans('backend.seatclassprice') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departRides as $ride)
                            <tr>
                                <td>{{ $ride->name }}</td>
                                <td>{{ $ride->departTime }}</td>
                                <td>{{ $ride->distanceTime }}</td>
                                <td>{{ $ride->returnTime }}</td>
                                <td>{{ $ride->seatClass }}</td>
                                <th>{{ $ride->price }}</th>
                                <td><input type="radio" name="depart" wire:click="chooseDepartTrip({{ $ride->id }}, {{ $ride->seatClassId }})"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h4 style="color: #CC2131">{{ trans('backend.notripmatch') }}</h4>
            @endif
    
            {{----------------------------------------}}
    
            @if ($tripType == ROUNDTRIP)
                <div style="background-color: #def8f3;">
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
                                <th>{{ trans('backend.distancetime') }}</th>
                                <th>{{ trans('backend.returntime') }}</th>
                                <th>{{ trans('backend.seatclass') }}</th>
                                <th>{{ trans('backend.seatclassprice') }}</th>
                                <th>{{ trans('backend.selecttrip') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returnRides as $ride)
                                <tr>
                                    <td>{{ $ride->name }}</td>
                                    <td>{{ $ride->departTime }}</td>
                                    <td>{{ $ride->distanceTime }}</td>
                                    <td>{{ $ride->returnTime }}</td>
                                    <td>{{ $ride->seatClass }}</td>
                                    <th>{{ $ride->price }}</th>
                                    <td><input type="radio" name="return" wire:click="chooseReturnTrip({{ $ride->id }}, {{ $ride->seatClassId }})"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
    
                @else
                    <h4 style="color: #CC2131">{{ trans('backend.notripmatch') }}</h4>
                @endif 
                
            @endif
        </div>
        
    </div>
@endif