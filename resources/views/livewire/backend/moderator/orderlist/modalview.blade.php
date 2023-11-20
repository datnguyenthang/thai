{{--Show modal boostrap to quick view detail order--}}
<div class="modal fade show" tabindex="-1" 
style="display: @if($showModal === true) block @else none @endif;" role="dialog" wire:model="viewOrder">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('backend.orderdetail') }}</h5>
                <button class="btn-close" wire:click="$set('showModal', false)"></button>
            </div>
            <div class="modal-body">
                @if($showModal === true && $orderDetail)
                    <div class="row">
                        <div class="col-md-6">
                            <p>{{ trans('backend.ordercode') }}: {{ $orderDetail['code'] }}</p>
                            <p>{{ trans('backend.agentname') }}: {{ $orderDetail['agentName'] }}</p>
                            <p>{{ trans('backend.fullname') }}: {{ $orderDetail['fullname'] }}</p>
                            <p>{{ trans('backend.phone') }}: {{ $orderDetail['phone'] }}</p>
                            <p>{{ trans('backend.email') }}: {{ $orderDetail['email'] }}</p>
                            <p>{{ trans('backend.bookingdate') }}: {{ $orderDetail['bookingDate'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <!--<p>{{ trans('backend.id') }} : {{ $orderDetail['id'] }}</p>-->
                            <p>{{ trans('backend.id') }} : {{ TRIPTYPE[$orderDetail['isReturn']] }}</p>
                            <p>{{ trans('backend.customerType') }}: {{ $orderDetail->customerTypeName ? $orderDetail->customerTypeName : 'ONLINE' }}</p>
                            <p>{{ trans('backend.adults') }}: {{ $orderDetail['adultQuantity'] }}</p>
                            <p>{{ trans('backend.children') }}: {{ $orderDetail['childrenQuantity'] }}</p>
                            <p>{{ trans('backend.note') }}: {{ $orderDetail['note'] }}</p>
                            <p>{{ trans('backend.orderstatus') }}: {{ ORDERSTATUS[$orderDetail->status] }}</p>
                        </div>
                    </div>
                    <hr class="dashed-stroke">
                    <!-- Other details -->
                    <div class="row">
                        @foreach ($orderDetail->orderTickets as $orderTicket)
                            @if($orderDetail->isReturn == ONEWAY)
                                @if($orderTicket->type == DEPARTURETICKET)
                                    <div class="col-md-6 dashed-line">
                                        <h5>{{ trans('backend.ticketdepart') }}</h5>
                                        <p>{{ trans('backend.ridename') }} : {{ $orderTicket['name'] }}</p>
                                        <p>{{ trans('backend.fromlocation') }} : {{ $orderTicket['fromLocationName'] }}</p>
                                        <p>{{ trans('backend.tolocation') }} : {{ $orderTicket['toLocationName'] }}</p>
                                        <p>{{ trans('backend.departtime') }} : {{ $orderTicket['departTime'] }}</p>
                                        <p>{{ trans('backend.returntime') }}: {{ $orderTicket['returnTime'] }}</p>
                                        <p>{{ trans('backend.departdate') }}: {{ $orderTicket['departDate'] }}</p>
                                        <p>{{ trans('backend.pickup') }}: {{ $orderTicket['pickup'] }}</p>
                                        <p>{{ trans('backend.dropoff') }}: {{ $orderTicket['dropoff'] }}</p>
                                        <button class="btn bg_own_color text-light"
                                            wire:click="downnloadTicket({{ $orderTicket->orderId}}, {{$orderTicket->id}})"
                                            wire:loading.attr="disabled">{{ trans('backend.downloadeticket') }}</button>
                                            
                                        <button class="btn bg_own_color text-light"
                                            wire:click="downnloadboardingPass({{ $orderTicket->orderId }}, {{ $orderTicket->id }})"
                                            wire:loading.attr="disabled">{{ trans('backend.downloadeboardingpass') }}</button>
                                    </div>
                                @endif
                            @endif

                            @if($orderDetail->isReturn == ROUNDTRIP)
                                @if($orderTicket->type == DEPARTURETICKET)
                                    <div class="col-md-6">
                                        <h5>{{ trans('backend.ticketdepart') }}</h5>
                                        <p>{{ trans('backend.ridename') }} : {{ $orderTicket['name'] }}</p>
                                        <p>{{ trans('backend.fromlocation') }} : {{ $orderTicket['fromLocationName'] }}</p>
                                        <p>{{ trans('backend.tolocation') }} : {{ $orderTicket['toLocationName'] }}</p>
                                        <p>{{ trans('backend.departtime') }} : {{ $orderTicket['departTime'] }}</p>
                                        <p>{{ trans('backend.returntime') }}: {{ $orderTicket['returnTime'] }}</p>
                                        <p>{{ trans('backend.departdate') }}: {{ $orderTicket['departDate'] }}</p>
                                        <p>{{ trans('backend.pickup') }}: {{ $orderTicket['pickup'] }}</p>
                                        <p>{{ trans('backend.dropoff') }}: {{ $orderTicket['dropoff'] }}</p>
                                        <button class="btn bg_own_color text-light"
                                            wire:click="downnloadTicket({{ $orderTicket->orderId }}, {{$orderTicket->id}})"
                                            wire:loading.attr="disabled">{{ trans('backend.downloadeticket') }}</button>

                                        <button class="btn bg_own_color text-light"
                                            wire:click="downnloadboardingPass({{ $orderTicket->orderId }}, {{ $orderTicket->id }})"
                                            wire:loading.attr="disabled">{{ trans('backend.downloadeboardingpass') }}</button>
                                    </div>
                                @endif

                                @if($orderTicket->type == RETURNTICKET)
                                    <div class="col-md-6">
                                        <h5>{{ trans('backend.ticketreturn') }}</h5>
                                        <p>{{ trans('backend.ridename') }} : {{ $orderTicket['name'] }}</p>
                                        <p>{{ trans('backend.fromlocation') }} : {{ $orderTicket['fromLocationName'] }}</p>
                                        <p>{{ trans('backend.tolocation') }} : {{ $orderTicket['toLocationName'] }}</p>
                                        <p>{{ trans('backend.departtime') }} : {{ $orderTicket['departTime'] }}</p>
                                        <p>{{ trans('backend.returntime') }}: {{ $orderTicket['returnTime'] }}</p>
                                        <p>{{ trans('backend.departdate') }}: {{ $orderTicket['departDate'] }}</p>
                                        <p>{{ trans('backend.pickup') }}: {{ $orderTicket['pickup'] }}</p>
                                        <p>{{ trans('backend.dropoff') }}: {{ $orderTicket['dropoff'] }}</p>
                                        <button class="btn bg_own_color text-light"
                                            wire:click="downnloadTicket({{ $orderTicket->orderId }}, {{$orderTicket->id}})"
                                            wire:loading.attr="disabled">{{ trans('backend.downloadeticket') }}</button>

                                        <button class="btn bg_own_color text-light"
                                            wire:click="downnloadboardingPass({{ $orderTicket->orderId }}, {{ $orderTicket->id }})"
                                            wire:loading.attr="disabled">{{ trans('backend.downloadeboardingpass') }}</button>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
            </div>
        </div>
    </div>
</div>