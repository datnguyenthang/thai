<div>
    <div class="row align-items-start d-flex">
        {{ Breadcrumbs::render('trip') }}
        @if (sizeof($departRides) > 0 || sizeof($returnRides) > 0)
            <h4>{{ trans('messages.selecttime') }}</h4>
        @endif
        <div class="col-md-8 col-md-offset-1">
            <div class="bg_own_color">
                <h4 class="mt-3 ms-3 text-light">
                    {{ $fromLocationName }}
                        <i class="fas fa-arrow-right fa-md fa-1x"></i>
                    {{ $toLocationName }}
                </h4>
            </div>

            @if (sizeof($departRides)) 
                <div class="col d-flex justify-content-center align-items-center">
                    <span class="text-center text-dark mt-3 mb-3 fs-6"> {{ trans('messages.foundrides', ['totalride' => count($departRides)]) }}</span>
                </div>

                @foreach($departRides as $key => $ride)
                    <div class="row m-0 mt-2 p-2 border rounded-2 pointer" role="button"
                        wire:key="depart-{{ $key }}"
                        @if(is_null($order_depart_rideId)) wire:click="chooseTicketDepart({{ $ride->id }}, {{ $ride->seatClassId }})" @endif
                    >
                        <div class="fs-5 mt-2 fw-bold text-dark">{{ $ride->name }}</div>
                        <div class="col-md-1">
                            <img src="img/seudamgo_ferry.png" alt="seudamgo_ferry"  style="height: 50px; width: 50px;">
                        </div>
                        <div class="col-md-2 pl-0">
                            <span class="fw-bold fs-5 lh-1 text-dark">{{ $ride->departTime }}</span><br/>
                            <span class="fs-10 text-secondary lh-1-2">{{ $fromLocationName }}</span>
                        </div>

                        <div class="col-md-2 pl-0">
                            <!--
                            <span class="fs-5 text-secondary lh-1-2">
                                ·· {{ $ride->distanceTime }} ··
                            </span>
                            -->
                        </div>
                        <div class="col-md-2">
                            <span class="fw-bold fs-5 lh-1 text-dark">{{ $ride->returnTime }}</span><br/>
                            <span class="fs-10 text-secondary lh-1-2 text-ellipsis">{{ $toLocationName }}</span>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="fw-bold fs-5 lh-1">{{ trans('messages.seatclass') }}</span><br/>
                            <span class="fs-10 text-secondary lh-1-2 seatclass">{{ $ride->seatClass }}</span>
                        </div>
                        <div class="col-md-3 justify-content-center text-center">
                            <span class="fw-bold fs-5 lh-1 text-center text-dark">฿{{ round($ride->price) }}</span>
                        </div>
                    </div>
                @endforeach

            @else
                <h5 style="color: #CC2131">{{ trans('messages.notripmatch') }}</h5>
            @endif

            {{----------------------------------------}}

            @if ($tripType == ROUNDTRIP)
                <div class="bg_own_color">
                    <h4 class="mt-5 ms-3 text-light">
                        {{ $toLocationName }}
                            <i class="fas fa-arrow-right fa-md fa-1x"></i>
                        {{ $fromLocationName }}
                    </h4>
                </div>

                @if (sizeof($returnRides))
                    <div class="col d-flex justify-content-center align-items-center">
                        <span class="text-center text-dark mt-3 mb-3 fs-6"> {{ trans('messages.foundrides', ['totalride' => count($returnRides)]) }}</span>
                    </div>

                    @foreach ($returnRides as $key => $ride) 
                        <div class="row m-0 mt-2 mb-2 p-2 border rounded-2 pointer" role="button"
                            wire:key="return-{{ $key }}"
                            @if(is_null($order_return_rideId)) wire:click="chooseTicketReturn({{ $ride->id }}, {{ $ride->seatClassId }})" @endif
                        >
                            <div class="fs-5 mt-2 fw-bold text-dark">{{ $ride->name }}</div>
                            <div class="col-md-1">
                                <img src="https://images.ferryhopper.com/companies/optimized/SJT-min.png" alt="SEAJETS"  style="height: 50px; width: 50px;">
                            </div>
                            <div class="col-md-2 pl-0">
                                <span class="fw-bold fs-5 lh-1 text-dark">{{ $ride->departTime }}</span><br/>
                                <span class="fs-10 text-secondary lh-1-2">{{ $fromLocationName }}</span>
                            </div>
                            <div class="col-md-2 pl-0">
                                <!--
                                <span class="fs-5 text-secondary lh-1-2">
                                    ·· {{ $ride->distanceTime }} ··
                                </span>
                                -->
                            </div>
                            <div class="col-md-2">
                                <span class="fw-bold fs-5 lh-1 text-dark">{{ $ride->returnTime }}</span><br/>
                                <span class="fs-10 text-secondary lh-1-2 text-ellipsis">{{ $toLocationName }}</span>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="fw-bold fs-5 lh-1">{{ trans('messages.seatclass') }}</span>
                                <span class="fs-10 text-secondary lh-1-2">{{ $ride->seatClass }}</span>
                            </div>
                            <div class="col-md-3 justify-content-center text-center">
                                <span class="fw-bold fs-5 lh-1 text-center text-dark">฿{{ round($ride->price) }}</span>
                            </div>
                        </div>
                    @endforeach 

                @else
                    <h5 style="color: #CC2131">{{ trans('messages.notripmatch') }}</h5>
                @endif 
                
            @endif
        </div>
        
        <div class="col-md-4 col-md-offset-1 ">
            <form action="{{ route('proceedbooking') }}" method="post">
                @CSRF 
                <input type="hidden" name="tripType" wire:model="tripType" value={{ $tripType }}>
                <input type="hidden" name="countTicketSelected" wire:model="countTicketSelected">
                <input type="hidden" name="fromLocation" wire:model="fromLocation" value={{ $fromLocation }}>
                <input type="hidden" name="toLocation" wire:model="toLocation" value={{ $toLocation }}>
                <input type="hidden" name="departureDate" wire:model="departureDate" value={{ $departureDate }}>
                <input type="hidden" name="returnDate" wire:model="returnDate" value={{ $returnDate }}>
                <input type="hidden" name="adults" wire:model="adults" value={{ $adults }}>
                <input type="hidden" name="children" wire:model="children" value={{ $children }}>

                <input type="hidden" wire:model="order_depart_rideId" name="order_depart_rideId" value>
                <input type="hidden" wire:model="order_depart_seatClassId" name="order_depart_seatClassId" value>
                <input type="hidden" wire:model="order_return_rideId" name="order_return_rideId" value>
                <input type="hidden" wire:model="order_return_seatClassId" name="order_return_seatClassId" value>

                <div class="d-flex flex-column justify-content-normal align-items-normal border rounded-3 overflow-hidden p-20 mt-3">
                    <div class="pt-2  pb-3 fw-bold fs-5 border-bottom border-1 pb-3">
                        <h5 class="text-center"> {{ $text_ticket_select }}</h5>
                    </div>
                    <div class="">
                    @if (isset($departRides[0]))
                        <div class="p-3 {{ $order_depart_rideId > 0 ? '' : 'd-none' }}">
                            <div class="d-flex justify-content-between">
                                <h5 class="text-center fw-bold mt-3" >
                                    {{ $fromLocationName }}
                                        <i class="fas fa-arrow-right fa-md fa-1x"></i>
                                    {{ $toLocationName }}
                                </h5>
                                <div class="mt-2 pointer" role="button" wire:click="clearDepartTicket">
                                    <i aria-hidden="true" class="fas fa-close"></i>
                                </div>                            
                            </div>
                            <div class="text-left">
                                <span><i class="fas fa-chair fa-lg"></i> {{ $departRides[0]->name }} ({{ $departRides[0]->seatClass }})</span>
                                <span class="float-end">{{ $departRides[0]->price }}฿</span>
                            </div>
                            <span>{{ date('F j, Y', strtotime($departRides[0]->departDate)) }}, {{$departRides[0]->departTime }}</span>
                        </div>
                    @endif
                    @if ($tripType == ROUNDTRIP && $order_return_rideId > 0)
                        @if (isset($returnRides[0]))
                            <div class="p-3 {{ $order_return_rideId > 0 ? '' : 'd-none' }}">
                                <hr />
                                <div class="d-flex justify-content-between">
                                    <h5 class="text-center fw-bold mt-3" >
                                        {{ $toLocationName }}
                                            <i class="fas fa-arrow-right fa-md fa-1x"></i>
                                        {{ $fromLocationName }}
                                    </h5>
                                    <div class="mt-2 pointer" role="button" wire:click="clearReturnTicket">
                                        <i aria-hidden="true" class="fas fa-close"></i>
                                    </div>
                                </div>
                                <div class="text-left">
                                    <span><i class="fas fa-chair fa-lg"></i> {{ $returnRides[0]->name }} ({{ $returnRides[0]->seatClass }})</span>
                                    <span class="float-end">{{ $returnRides[0]->price }}฿</span>
                                </div>
                                <span>{{ date('F j, Y', strtotime($returnRides[0]->departDate)) }}, {{$returnRides[0]->departTime }}</span>
                            </div>
                        @endif
                    @endif
                    </div>
                    <div class="text-center pt-2 pb-2">
                        <button class="btn btn-danger btn-lg btn_order" {{ $enableSubmit ? '' : 'disabled' }}>
                            {{ trans('messages.continuebooking') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
