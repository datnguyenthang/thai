<div class="row align-items-start d-flex">
    <div class="col-md-8 col-md-offset-1">
        <div style="background-color: #def8f3;">
            <h2 class="select-departure-header mt-3" >
                {{ $fromLocationName }}
                    <i class="fas fa-arrow-right fa-md fa-1x"></i>
                {{ $toLocationName }}
            </h2>
        </div>

        @if (sizeof($departRides))
            <span> {{ count($departRides) }} direct trips found</span>
                @foreach($departRides as $ride)
                    <div class="row m-0 p-2 border rounded-2 pointer depart" role="button">
                        <input type="hidden" class="departRideId" name="departRideId" value="{{ $ride->id }}" />
                        <input type="hidden" class="departSeatClassId" name="departSeatClassId" value="{{ $ride->seatClassId }}" />
                        <div class="fs-5 mt-2 fw-bold name">{{ $ride->name }}</div>
                        <div class="col-md-1">
                            <img src="https://images.ferryhopper.com/companies/optimized/SJT-min.png" alt="SEAJETS"  style="height: 50px; width: 50px;">
                        </div>
                        <div class="col-md-3 pl-0">
                            <span class="fw-bold fs-5 lh-1 departtime">07:00</span><br/>
                            <span class="fs-5 text-secondary lh-1-2">{{ $ride->fromLocationName }}</span>
                        </div>
                        <div class="col-md-3 pl-0">
                            <span class="fs-5 text-secondary lh-1-2">·· 2h 45m ··</span>
                        </div>
                        <div class="col-md-3">
                            <span class="fw-bold fs-5 lh-1 returntime">09:45</span><br/>
                            <span class="fs-5 text-secondary lh-1-2 text-ellipsis">{{ $ride->toLocationName }}</span>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="fw-bold fs-5 lh-1">{{ trans('messages.seatclass') }}</span><br/>
                            <span class="fs-5 text-secondary lh-1-2 seatclass">{{ $ride->seatClass }}</span>
                        </div>
                    </div>
                @endforeach

        @else
            <h4 style="color: #CC2131">{{ trans('messages.notripmatch') }}</h4>
        @endif

        {{----------------------------------------}}

        @if ($tripType == ROUNDTRIP)
            <div style="background-color: #def8f3;">
                <h2 class="select-departure-header mt-3" >
                    {{ $toLocationName }}
                        <i class="fas fa-arrow-right fa-md fa-1x"></i>
                    {{ $fromLocationName }}
                </h2>
            </div>

            @if (sizeof($returnRides))
                <span> {{ count($returnRides) }} direct trips found</span>
                @foreach ($returnRides as $ride) 
                    <div class="row m-0 p-2 border rounded-2 pointer return" role="button">
                        <input type="hidden" class="returnRideId" name="returnRideId" value="{{ $ride->id }}" />
                        <input type="hidden" class="returnSeatClassId" name="returnSeatClassId" value="{{ $ride->seatClassId }}" />

                        <div class="fs-5 mt-2 fw-bold name">{{ $ride->name }}</div>
                        <div class="col-md-1">
                            <img src="https://images.ferryhopper.com/companies/optimized/SJT-min.png" alt="SEAJETS"  style="height: 50px; width: 50px;">
                        </div>
                        <div class="col-md-3 pl-0">
                            <span class="fw-bold fs-5 lh-1 departtime">07:00</span><br/>
                            <span class="fs-5 text-secondary lh-1-2">{{ $ride->fromLocationName }}</span>
                        </div>
                        <div class="col-md-3 pl-0">
                            <span class="fs-5 text-secondary lh-1-2">·· 2h 45m ··</span>
                        </div>
                        <div class="col-md-3">
                            <span class="fw-bold fs-5 lh-1 returntime">09:45</span><br/>
                            <span class="fs-5 text-secondary lh-1-2 text-ellipsis">{{ $ride->toLocationName }}</span>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="fw-bold fs-5 lh-1">{{ trans('messages.seatclass') }}</span>
                            <span class="fs-5 text-secondary lh-1-2 seatclass">{{ $ride->seatClass }}</span>
                        </div>
                    </div>
                @endforeach 

            @else
                <h4 style="color: #CC2131">{{ trans('messages.notripmatch') }}</h4>
            @endif 
            
        @endif

        @if (sizeof($departRides) > 0 || sizeof($returnRides) > 0)
            {{--<h4 style="color: #CC2131">{{ trans('messages.selecttime') }}</h4>--}}
        @endif
    </div>
    
    <div class="col-md-4 col-md-offset-1 ">
        <form id="order" action="{{ route('proceedbooking') }}" method="post">
            @CSRF 
            <input type="hidden" name="tripType" wire:model="tripType" value={{ $tripType }}>
            <input type="hidden" name="fromLocation" wire:model="fromLocation" value={{ $fromLocation }}>
            <input type="hidden" name="toLocation" wire:model="toLocation" value={{ $toLocation }}>
            <input type="hidden" name="departureDate" wire:model="departureDate" value={{ $departureDate }}>
            <input type="hidden" name="returnDate" wire:model="returnDate" value={{ $returnDate }}>
            <input type="hidden" name="adults" wire:model="adults" value={{ $adults }}>
            <input type="hidden" name="children" wire:model="children" value={{ $children }}>
            <div class="d-flex flex-column justify-content-normal align-items-normal border rounded-3 overflow-hidden p-20 mt-3">
                <div class="text-center pt-2 fw-bold fs-5 border-bottom border-1 pb-3">
                    <h5><span class="numberTripSelected">0</span> of {{ $tripType == ROUNDTRIP ? 2 : 1 }} trip selected</h5>
                </div>
                <div class="info">
                    <div class="info-trip p-3 ">
                        <div class="depart_trip d-none">
                            <input type="hidden" wire:model="order_depart_rideId" class="order_depart_rideId" name="order_depart_rideId" value>
                            <input type="hidden" wire:model="order_depart_seatClassId" class="order_depart_seatClassId" name="order_depart_seatClassId" value>
                            <div class="d-flex justify-content-between">
                                <h5 class="depart_trip_text text-center fw-bold mt-3" >
                                    {{ $fromLocationName }}
                                        <i class="fas fa-arrow-right fa-md fa-1x"></i>
                                    {{ $toLocationName }}
                                </h5>
                                <div class="mt-2 remove_depart pointer" role="button">
                                    <i aria-hidden="true" class="fas fa-close"></i>
                                </div>
                            </div>
                            <span class="text-center depart_name"></span>
                            <span class="text-center depart_time"></span>
                            <span class="text-center depart_return_time"></span>
                            <span class="text-center depart_seat_class"></span>
                        </div>
                    @if ($tripType == ROUNDTRIP)
                        <hr />
                        <div class="return_trip d-none">
                            <input type="hidden" wire:model="order_return_rideId" class="order_return_rideId" name="order_return_rideId" value>
                            <input type="hidden" wire:model="order_return_seatClassId" class="order_return_seatClassId" name="order_return_seatClassId" value>
                            <div class="d-flex justify-content-between">
                                
                                <h5 class="return_trip_text text-center fw-bold mt-3" >
                                    {{ $toLocationName }}
                                        <i class="fas fa-arrow-right fa-md fa-1x"></i>
                                    {{ $fromLocationName }}
                                </h5>
                                <div class="mt-2 remove_return pointer" role="button">
                                    <i aria-hidden="true" class="fas fa-close"></i>
                                </div>
                            </div>
                            <span class="text-center return_name"></span>
                            <span class="text-center return_time"></span>
                            <span class="text-center return_return_time"></span>
                            <span class="text-center return_seat_class"></span>
                        </div>
                    @endif
                    </div>
                </div>
                <div class="text-center pt-2 pb-2">
                    <button class="btn btn-success btn-lg btn_order" disabled>
                        {{ trans('messages.continue') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script type='module'>
    $('.depart').click(function(){
        clear_depart();
        $('.depart').not(this).addClass('d-none');
        $('.depart_trip').removeClass('d-none');
        $('.depart_trip_text').removeClass('d-none');
        $('.depart_name').html($(this).find('.name').text());
        $('.depart_time').html($(this).find('.departtime').text());
        $('.depart_return_time').html($(this).find('.returntime').text());
        $('.depart_seat_class').html($(this).find('.seatclass').text());

        $('.order_depart_rideId').val($(this).find('.departRideId').val());
        $('.order_depart_seatClassId').val($(this).find('.departSeatClassId').val());

        $('.numberTripSelected').text(parseInt($('.numberTripSelected').innerHTML) + 1);

        if (checkEligibleSubmit()) $('.btn_order').attr("disabled", false);
    });

    $('.return').click(function(){
        clear_return();
        $('.return').not(this).addClass('d-none');
        $('.return_trip').removeClass('d-none');
        $('.return_trip_text').removeClass('d-none');
        $('.return_name').html($(this).find('.name').text());
        $('.return_time').html($(this).find('.departtime').text());
        $('.return_return_time').html($(this).find('.returntime').text());
        $('.return_seat_class').html($(this).find('.seatclass').text());

        $('.order_return_rideId').val($(this).find('.returnRideId').val());
        $('.order_return_seatClassId').val($(this).find('.returnSeatClassId').val());

        $('.numberTripSelected').text(parseInt($('.numberTripSelected').innerHTML) +- 1);

        if (checkEligibleSubmit()) $('.btn_order').attr("disabled", false);
    });

    $('.remove_depart').click(function(){
        clear_depart();
        $('.depart').removeClass('d-none');
    });
    $('.remove_return').click(function(){
        clear_return();
        $('.return').not(this).removeClass('d-none');
    });


    function clear_depart(){
        $('.depart_trip').addClass('d-none');
        $('.depart_trip_text').addClass('d-none');
        $('.depart_name').html('');
        $('.depart_time').html('');
        $('.depart_return_time').html('');
        $('.depart_seat_class').html('');

        $('.btn_order').attr("disabled", true);

        $('.order_depart_rideId').val('');
        $('.order_depart_seatClassId').val('');

        $('.numberTripSelected').text(parseInt($('.numberTripSelected').innerHTML) - 1);
    }

    function clear_return(){
        $('.return_trip').addClass('d-none');
        $('.return_trip_text').addClass('d-none');
        $('.return_name').html('');
        $('.return_time').html('');
        $('.return_return_time').html('');
        $('.return_seat_class').html('');

        $('.btn_order').attr("disabled", true);

        $('.order_return_rideId').val('');
        $('.order_return_seatClassId').val('');

        $('.numberTripSelected').text(parseInt($('.numberTripSelected').innerHTML) - 1);
    }

    function checkEligibleSubmit(){
        let check = true;

        $('form#order input[type="hidden"]').each(function() {
            if ($(this).val() === "") {
                check = false;
                return check;
            }
        });
        return check;
    }
</script>
