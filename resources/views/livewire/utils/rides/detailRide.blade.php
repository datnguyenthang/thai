<!----Modal Ride------->
{{--Show modal boostrap to quick view detail order--}}
<div class="modal fade show" tabindex="-1" wire:model="boardingPass" wire:key="modal-{{ $rideId }}"
    style="display: @if($showModal === true) block @else none @endif;" role="dialog">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('backend.ridedetail') }}</h5>
                <button class="btn-close" wire:click="$set('showModal', false)"></button>
            </div>
            <div class="modal-body">

                @if($showModal === true && $listPassengers)
                    <div class="row">
                        <table class="table table-striped">
                            <tr>
                                <th>Order Code</th>
                                <th>Phone</th>
                                {{--<th>Email</th>--}}
                                <th>Adult Quantity</th>
                                <th>Children Quantity</th>
                                <th>Pickup</th>
                                <th>Dropoff</th>
                                <th>Full Name</th>
                                <th>Customer Type</th>
                                <th>User Name</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($listPassengers as $listPassenger)
                                @php
                                    $class = "";
                                    if ($listPassenger->status == CONFIRMEDORDER)  $class = "table-success";
                                    if ($listPassenger->status == CANCELDORDER)  $class = "table-danger";
                                @endphp
                                <tr class="{{ $class }}">
                                    <td>{{ $listPassenger->code }}</td>
                                    <td>{{ $listPassenger->phone }}</td>
                                    {{--<td>{{ $listPassenger->email }}</td>--}}
                                    <td>{{ $listPassenger->adultQuantity }}</td>
                                    <td>{{ $listPassenger->childrenQuantity }}</td>
                                    <td>{{ $listPassenger->pickup }}</td>
                                    <td>{{ $listPassenger->dropoff }}</td>
                                    <td>{{ $listPassenger->fullname }}</td>
                                    <td>{{ $listPassenger->CustomerType }}</td>
                                    <td>{{ $listPassenger->name }}</td>
                                    <td>{{ PAYMENTSTATUS[$listPassenger->paymentStatus] }}</td>
                                    <td>{{ ORDERSTATUS[$listPassenger->status] }}</td>
                                    <td>
                                        <a class="btn bg_own_color text-light"
                                            href="/{{ $role }}processorder/{{$listPassenger->orderId}}"
                                            target="_blank"
                                        >
                                            {{ trans('backend.vieworder') }}
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn bg_own_color text-light"
                                            wire:key="passenger-{{ $listPassenger->orderTicketId }}"
                                            wire:click="boardingPass({{ $rideId }}, {{ $listPassenger->orderTicketId }})"
                                            wire:loading.attr="disabled">
                                                {{ trans('backend.download') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
                <div class="modal-footer">
                    <button type="button" wire:click="$set('showModal', false)" class="btn btn-secondary">{{ trans('backend.close') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>